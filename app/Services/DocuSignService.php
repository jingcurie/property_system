<?php

namespace App\Services;

use DocuSign\eSign\Client\ApiClient;  // 修正：加了Client
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Recipients;

class DocuSignService
{
    public function getApiClient()
    {
        try {
            $config = new Configuration();
            $config->setHost(env('DOCUSIGN_BASE_URL'));
            $apiClient = new ApiClient($config);

            // 读取私钥
            $privateKeyPath = storage_path('app/docusign/private.key');
            $privateKey = file_get_contents($privateKeyPath);

            // 设置正确的OAuth基础URL
            $apiClient->getOAuth()->setOAuthBasePath(env('DOCUSIGN_OAUTH_BASE_URL'));

            $response = $apiClient->requestJWTUserToken(
                env('DOCUSIGN_CLIENT_ID'),
                env('DOCUSIGN_USER_ID'),
                $privateKey,
                ['signature', 'impersonation'], // ✅ 第四个参数是 scopes
                3600,
            );

            // 设置访问令牌
            $accessToken = $response[0]['access_token'];
            $config->addDefaultHeader('Authorization', 'Bearer ' . $accessToken);

            return $apiClient;
        } catch (\Exception $e) {
            throw new \Exception('DocuSign API连接失败: ' . $e->getMessage());
        }
    }

    // public function sendContractForSignatureToMultipleSigners($pdfPath, $signerName, $signerEmail)
    // {
    //     try {
    //         $apiClient = $this->getApiClient();
    //         $envelopeApi = new EnvelopesApi($apiClient);

    //         // 创建文档
    //         $document = new Document([
    //             'document_base64' => base64_encode(file_get_contents($pdfPath)),
    //             'name' => 'Lease Contract',
    //             'file_extension' => 'pdf',
    //             'document_id' => '1'
    //         ]);

    //         // 签名者
    //         $signer = new Signer([
    //             'email' => $signerEmail,
    //             'name' => $signerName,
    //             'recipient_id' => "1",
    //             'routing_order' => "1",
    //             // 'tabs' => ['sign_here_tabs' => [$signHere]]
    //         ]);

    //         $recipients = new Recipients(['signers' => [$signer]]);

    //         // 创建信封
    //         $envelopeDefinition = new EnvelopeDefinition([
    //             'email_subject' => "Please sign your lease contract",
    //             'documents' => [$document],
    //             'recipients' => $recipients,
    //             'status' => 'sent'
    //         ]);

    //         $result = $envelopeApi->createEnvelope(env('DOCUSIGN_ACCOUNT_ID'), $envelopeDefinition);

    //         return [
    //             'success' => true,
    //             'envelope_id' => $result->getEnvelopeId(),
    //             'status' => $result->getStatus()
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }
    public function sendContractForSignatureToMultipleSigners($pdfPath, array $signers)
    {
        try {
            $apiClient = $this->getApiClient();
            $envelopeApi = new EnvelopesApi($apiClient);

            $document = new Document([
                'document_base64' => base64_encode(file_get_contents($pdfPath)),
                'name' => 'Lease Contract',
                'file_extension' => 'pdf',
                'document_id' => '1'
            ]);

            $signerObjects = [];
            foreach ($signers as $index => $signerInfo) {
                // 为每个签署人创建多个签名位置
                $signaturePositions = $this->getMultipleSignaturePositions($signerInfo['type'], $index, count($signers));
                
                $signHereTabs = [];
                foreach ($signaturePositions as $posIndex => $position) {
                    $signHereTabs[] = new SignHere([
                        'document_id' => '1',
                        'page_number' => $position['page'],
                        'x_position' => $position['x'],
                        'y_position' => $position['y']
                    ]);
                }

                $signer = new Signer([
                    'email' => $signerInfo['email'],
                    'name' => $signerInfo['name'],
                    'recipient_id' => (string)($index + 1),
                    'routing_order' => (string)($index + 1),
                    'tabs' => new \DocuSign\eSign\Model\Tabs([
                        'sign_here_tabs' => $signHereTabs
                    ])
                ]);

                $signerObjects[] = $signer;
            }

            $recipients = new Recipients(['signers' => $signerObjects]);

            $envelopeDefinition = new EnvelopeDefinition([
                'email_subject' => "Please sign your lease contract",
                'documents' => [$document],
                'recipients' => $recipients,
                'status' => 'sent'
            ]);

            $result = $envelopeApi->createEnvelope(env('DOCUSIGN_ACCOUNT_ID'), $envelopeDefinition);

            return [
                'success' => true,
                'envelope_id' => $result->getEnvelopeId(),
                'status' => $result->getStatus()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * 为每个签署人获取多个签名位置
     * 基于实际PDF文档的所有签名位置
     */
    private function getMultipleSignaturePositions($signerType, $signerIndex, $totalSigners)
    {
        $positions = [];
        
        switch ($signerType) {
            case 'tenant':
                // 租客需要在多个地方签名
                $positions = [
                    // 第6页 - 主要签名位置
                    [
                        'page' => '6',
                        'x' => 100,
                        'y' => 400 + ($signerIndex * 80)
                    ],
                    // 第13页 - 最后一页签名
                    [
                        'page' => '13',
                        'x' => 120,
                        'y' => 300 + ($signerIndex * 60)
                    ],
                    // 第2页 - 初始签名（如果选择E选项）
                    [
                        'page' => '2',
                        'x' => 150,
                        'y' => 500 + ($signerIndex * 30)
                    ]
                ];
                break;
                
            case 'owner':
                // 业主需要在多个地方签名
                $positions = [
                    // 第6页 - 主要签名位置
                    [
                        'page' => '6',
                        'x' => 100,
                        'y' => 200 + ($signerIndex * 80)
                    ],
                    // 第13页 - 最后一页签名
                    [
                        'page' => '13',
                        'x' => 120,
                        'y' => 500
                    ],
                    // 第2页 - 初始签名（如果选择E选项）
                    [
                        'page' => '2',
                        'x' => 150,
                        'y' => 500
                    ]
                ];
                break;
                
            case 'agent':
                // 代理公司签名位置
                $positions = [
                    [
                        'page' => '6',
                        'x' => 100,
                        'y' => 600 + ($signerIndex * 80)
                    ],
                    [
                        'page' => '13',
                        'x' => 120,
                        'y' => 600 + ($signerIndex * 60)
                    ]
                ];
                break;
                
            default: // custom
                $positions = [
                    [
                        'page' => '6',
                        'x' => 100,
                        'y' => 700 + ($signerIndex * 80)
                    ],
                    [
                        'page' => '13',
                        'x' => 120,
                        'y' => 700 + ($signerIndex * 60)
                    ]
                ];
                break;
        }
        
        return $positions;
    }
}
