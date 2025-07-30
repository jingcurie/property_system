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
        $envelopeId = $data['data']['envelopeId'] ?? null;
        $accountId = $data['data']['accountId'] ?? env('DOCUSIGN_ACCOUNT_ID');

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
            $service = new DocuSignService();
            $apiClient = $service->getApiClient();

            // 获取 JWT access_token（一定要有 scope）
            $privateKey = file_get_contents(storage_path('app/docusign/private.key'));
            \Log::info($privateKey);
            \Log::info(trim(env('DOCUSIGN_CLIENT_ID')));
            \Log::info(trim(env('DOCUSIGN_USER_ID')));
            [$token, $statusCode, $headers] = $apiClient->requestJWTUserToken(
                trim(env('DOCUSIGN_CLIENT_ID')),
                trim(env('DOCUSIGN_USER_ID')),
                $privateKey,
                ['signature', 'impersonation'],
                60
            );

            $accessToken = $token->getAccessToken();
            Log::info('获取到 AccessToken', ['access_token' => $accessToken]);

            $apiClient->getConfig()->addDefaultHeader(
                'Authorization',
                'Bearer ' . $accessToken
            );
            $envelopeApi = new \DocuSign\eSign\Api\EnvelopesApi($apiClient);

            // 等待文档就绪（重要）
            sleep(10);

            \Log::info('准备下载 combined PDF', [
                'account_id' => $accountId,
                'envelope_id' => $envelopeId,
            ]);


            $envelopeId = trim($data['data']['envelopeId'] ?? '');
            $accountId = trim($data['data']['accountId'] ?? env('DOCUSIGN_ACCOUNT_ID'));

            // 验证
            \Log::info('Envelope ID Debug', ['value' => $envelopeId, 'length' => strlen($envelopeId)]);

            // 下载
            $signedPdf = $envelopeApi->getDocument($accountId, $envelopeId, 'combined');

            Storage::disk('public')->put($file->path, $signedPdf);

            $file->update([
                'signature_status'   => 'completed',
                'tenant_signed'      => true,
                'tenant_signed_date' => now(),
                'size'               => strlen($signedPdf),
            ]);

            \Log::info('PDF 下载成功', ['size' => strlen($signedPdf)]);

            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            \Log::error('DocuSign 下载异常', ['msg' => $e->getMessage()]);
            return response('Download failed', 500);
        }


        return response('Webhook handled', 200);
    }
}
