<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Lease;
use App\Models\File;
use App\Services\DocuSignService;

class DocuSignWebhookController extends Controller
{
    public function handle(Request $request)
    {
        set_time_limit(120);

        \Log::info('收到 DocuSign Webhook 推送', [
            'headers' => $request->headers->all(),
            'content' => $request->getContent()
        ]);

        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['event'])) {
            return response('Invalid payload', 400);
        }

        $event = $data['event'];
        $envelopeId = trim($data['data']['envelopeId'] ?? '');
        $accountId = trim($data['data']['accountId'] ?? env('DOCUSIGN_ACCOUNT_ID'));

        \Log::info('DocuSign Event', [
            'event' => $event,
            'envelope_id' => $envelopeId
        ]);

        if ($event !== 'envelope-completed') {
            return response('Event logged, no action needed', 200);
        }

        $file = File::where('envelope_id', $envelopeId)->first();
        if (!$file || $file->fileable_type !== Lease::class) {
            return response('Lease file not found', 404);
        }

        $lease = Lease::find($file->fileable_id);
        if (!$lease) {
            return response('Lease not found', 404);
        }

        try {
            // 每次都重新获取新的token
            $service = new DocuSignService();
            $apiClient = $service->getApiClient();

            $privateKey = file_get_contents(storage_path('app/docusign/private.key'));
            
            // 获取新的JWT token
            [$token, $statusCode, $headers] = $apiClient->requestJWTUserToken(
                trim(env('DOCUSIGN_CLIENT_ID')),
                trim(env('DOCUSIGN_USER_ID')),
                $privateKey,
                ['signature', 'impersonation'],
                60
            );

            $accessToken = $token->getAccessToken();
            \Log::info('获取到新的AccessToken', ['token_length' => strlen($accessToken)]);

            // 等待文档就绪
            sleep(10);

            \Log::info('准备下载 combined PDF', [
                'account_id' => $accountId,
                'envelope_id' => $envelopeId,
            ]);

            // 使用HTTP直接下载（避免SDK的token过期问题）
            $downloadUrl = "https://demo.docusign.net/restapi/v2.1/accounts/{$accountId}/envelopes/{$envelopeId}/documents/combined";
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/pdf'
            ])->timeout(60)->get($downloadUrl);

            if ($response->successful()) {
                $signedPdf = $response->body();
                
                if (strlen($signedPdf) > 1000) { // 确保不是错误响应
                    Storage::disk('public')->put($file->path, $signedPdf);

                    $file->update([
                        'signature_status'   => 'completed',
                        'tenant_signed'      => true,
                        'tenant_signed_date' => now(),
                        'size'               => strlen($signedPdf),
                        'path'               => $signedPath,  // 更新为新路径
                    ]);

                    \Log::info('PDF 下载成功', ['size' => strlen($signedPdf)]);
                    return response('Webhook handled', 200);
                } else {
                    \Log::error('下载的PDF文件太小', ['size' => strlen($signedPdf)]);
                    return response('PDF file too small', 500);
                }
            } else {
                \Log::error('HTTP下载失败', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response('HTTP download failed', 500);
            }

        } catch (\Exception $e) {
            \Log::error('DocuSign 下载异常', ['msg' => $e->getMessage()]);
            return response('Download failed', 500);
        }
    }
}