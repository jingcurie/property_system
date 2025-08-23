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

        $file = File::where('envelope_id', $envelopeId)->first();
        if (!$file || $file->fileable_type !== Lease::class) {
            return response('Lease file not found', 404);
        }

        try {
            // 获取 DocuSign API Client
            $service = new DocuSignService();
            $apiClient = $service->getApiClient();

            $privateKey = file_get_contents(storage_path('app/docusign/private.key'));
            [$token] = $apiClient->requestJWTUserToken(
                env('DOCUSIGN_CLIENT_ID'),
                env('DOCUSIGN_USER_ID'),
                $privateKey,
                ['signature', 'impersonation'],
                60
            );
            $accessToken = $token->getAccessToken();

            // 1️⃣ 更新状态
            $file->signature_status = str_replace('envelope-', '', $event);

            // 2️⃣ 更新接收人（签署人）
            $envelopesApi = new \DocuSign\eSign\Api\EnvelopesApi($apiClient);
            $recipients = $envelopesApi->listRecipients($accountId, $envelopeId);
            $signerNames = collect($recipients->getSigners() ?? [])->pluck('name')->implode(', ');

            $baseDescription = preg_replace('/\nTo: .*/', '', $file->description);
            $file->description = trim($baseDescription . "\nTo: " . $signerNames);

            // 3️⃣ 更新 last_change
            $file->updated_at = now();
            $file->save();

            // 4️⃣ 如果是完成，下载 PDF
            if ($event === 'envelope-completed') {
                sleep(10);
                $downloadUrl = "https://demo.docusign.net/restapi/v2.1/accounts/{$accountId}/envelopes/{$envelopeId}/documents/combined";
                $response = \Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/pdf'
                ])->timeout(60)->get($downloadUrl);

                if ($response->successful() && strlen($response->body()) > 1000) {
                    \Storage::disk('public')->put($file->path, $response->body());
                    $file->update([
                        'tenant_signed' => true,
                        'tenant_signed_date' => now(),
                        'size' => strlen($response->body()),
                    ]);
                }
            }

            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            \Log::error('DocuSign Webhook 异常', ['msg' => $e->getMessage()]);
            return response('Webhook error', 500);
        }
    }
}
