<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\LeaseFeeStructure;
use App\Models\Property;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use mikehaertl\pdftk\Pdf;

class LeaseController extends Controller
{
    public function create()
    {
        return view('leases.create', [
            'lease' => null,
            'leaseFees' => [],
            'formAction' => route('leases.store'),
            'isEdit' => false,
            'properties' => Property::all(),
            'tenants' => Tenant::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_number' => 'required|string|max:50',
            'version_number' => 'nullable|integer',
            'lease_type' => 'required|string',
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'monthly_rent' => 'required|numeric',
            'rent_due_day' => 'nullable|integer',
            'security_deposit' => 'nullable|numeric',
            'furniture_deposit' => 'nullable|numeric',
            'pet_deposit' => 'nullable|numeric',
            'cleaning_fee' => 'nullable|numeric',
            'late_fee_amount' => 'nullable|numeric',
            'nsf_fee' => 'nullable|numeric',
            'minimum_coverage_amount' => 'nullable|numeric',
            'termination_policy' => 'nullable|string',
            'parking_info' => 'nullable|string',
            'storage_info' => 'nullable|string',
            'attachments.*' => 'file|max:20480',
        ]);

        // 布尔字段
        foreach (
            [
                'pets_allowed',
                'smoking_allowed',
                'subletting_allowed',
                'tenant_insurance_required',
                'insurance_required',
                'furnished',
                'strata_acknowledged',
                'form_k_signed'
            ] as $field
        ) {
            $validated[$field] = $request->has($field);
        }

        // 保存主表
        $lease = Lease::create($validated);

        // 保存附加费用
        if ($request->has('fees')) {
            foreach ($request->fees as $fee) {
                if (!empty($fee['type']) && is_numeric($fee['amount'])) {
                    LeaseFeeStructure::create([
                        'lease_id' => $lease->id,
                        'type' => $fee['type'],
                        'amount' => $fee['amount'],
                    ]);
                }
            }
        }

        // 附件上传
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('leases/' . $lease->id, 'public');
                $lease->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('leases.index')->with('success', 'Lease created successfully!');
    }

    /**
     * 显示所有合同列表
     */
    public function index(Request $request)
    {
        $query = Lease::with(['tenants', 'property'])->whereNull('deleted_at');

        //调用helpers中的functions
        $query = applyKeywordSearch($query, $request);
        $query = applyFilters($query, $request);
        $query = applySorting($query, $request);
        $leases = applyPagination($query, $request);

        return view('leases.index', compact('leases'));
    }

    public function edit(Lease $lease)
    {
        return view('leases.edit', [
            'lease' => $lease,
            'leaseFees' => $lease->feeStructures()->get()->toArray(),
            'formAction' => route('leases.update', $lease),
            'isEdit' => true,
            'properties' => Property::all(),
            'tenants' => Tenant::all(),
        ]);
    }

    public function show($id)
    {
        $lease = Lease::findOrFail($id);

        $attachments = $lease->files->map(function ($file) {
            return [
                'id' => $file->id,
                'title' => $file->title,
                'filename' => $file->filename,
                'path' => $file->path,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'disk' => $file->disk,
                'category' => $file->category,
                'description' => $file->description,
                'is_cover' => $file->is_cover,
                'is_private' => $file->is_private,
                'sort_order' => $file->sort_order,
                'fileable_type' => $file->fileable_type,
                'created_at' => optional($file->created_at)->toDateTimeString(),
                'uploaded_by' => $file->uploader->name ?? 'Unknown',
            ];
        });

        return view('leases.show', compact('lease', 'attachments'));
    }

    public function update(Request $request, Lease $lease)
    {
        $validated = $request->validate([
            'lease_number' => 'required|string|max:50',
            'version_number' => 'nullable|integer',
            'lease_type' => 'required|string',
            'property_id' => 'required|exists:properties,property_id',
            'tenant_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'monthly_rent' => 'required|numeric',
            'rent_due_day' => 'nullable|integer',
            'security_deposit' => 'nullable|numeric',
            'furniture_deposit' => 'nullable|numeric',
            'pet_deposit' => 'nullable|numeric',
            'cleaning_fee' => 'nullable|numeric',
            'late_fee_amount' => 'nullable|numeric',
            'nsf_fee' => 'nullable|numeric',
            'minimum_coverage_amount' => 'nullable|numeric',
            'termination_policy' => 'nullable|string',
            'parking_info' => 'nullable|string',
            'storage_info' => 'nullable|string',
            'attachments.*' => 'file|max:20480',
        ]);

        // 布尔字段更新
        foreach (
            [
                'pets_allowed',
                'smoking_allowed',
                'subletting_allowed',
                'tenant_insurance_required',
                'insurance_required',
                'furnished',
                'strata_acknowledged',
                'form_k_signed'
            ] as $field
        ) {
            $validated[$field] = $request->has($field);
        }

        // 更新主表
        $lease->update($validated);

        // 替换附加费用：先删再插
        $lease->feeStructures()->delete();

        if ($request->has('fees')) {
            foreach ($request->fees as $fee) {
                if (!empty($fee['type']) && is_numeric($fee['amount'])) {
                    $lease->feeStructures()->create([
                        'type' => $fee['type'],
                        'amount' => $fee['amount'],
                    ]);
                }
            }
        }

        // 附件追加上传
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('leases/' . $lease->id, 'public');
                $lease->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully!');
    }

    // public function sendDocusign($id)
    // {
    //     $lease = Lease::with('tenants')->findOrFail($id);

    //     // 获取合同 PDF 文件（category 应为 'leases'）
    //     $contractFile = $lease->files()->where('category', 'leases')->latest()->first();

    //     if (!$contractFile) {
    //         // 根据请求类型返回不同响应
    //         if (request()->expectsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => '找不到合同文件'
    //             ]);
    //         }
    //         return redirect()->back()->with('error', '找不到合同文件');
    //     }

    //     $pdfPath = storage_path('app/public/' . $contractFile->path);
    //     $signerName = $lease->tenant->first_name . ' ' . $lease->tenant->last_name;
    //     $signerEmail = $lease->tenant->email;

    //     $docusign = new \App\Services\DocuSignService();
    //     $result = $docusign->sendContractForSignature($pdfPath, $signerName, $signerEmail);

    //     if ($result['success']) {
    //         // ✅ 更新文件记录 envelope_id 和 签署状态
    //         $contractFile->update([
    //             'envelope_id'       => $result['envelope_id'],
    //             'signature_status'  => 'sent',
    //         ]);

    //         // 根据请求类型返回不同响应
    //         if (request()->expectsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'envelope_id' => $result['envelope_id'],
    //                 'status' => $result['status'],
    //                 'message' => '合同已发送给租户签署'
    //             ]);
    //         }
    //         return redirect()->back()->with('success', '合同已发送给租户签署');
    //     } else {
    //         // 根据请求类型返回不同响应
    //         if (request()->expectsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => '发送失败：' . $result['error']
    //             ]);
    //         }
    //         return redirect()->back()->with('error', '发送失败：' . $result['error']);
    //     }
    // }
    public function sendDocusign($id)
    {
        $lease = Lease::with(['tenants', 'property.owners'])->findOrFail($id);

        // 获取合同 PDF 文件（category 应为 'leases'）
        $contractFile = $lease->files()->where('category', 'contract')->latest()->first();

        if (!$contractFile) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => '找不到合同文件'])
                : redirect()->back()->with('error', '找不到合同文件');
        }
        
        $pdfPath = storage_path('app/public/' . $contractFile->path);

        // 从请求中获取签署人列表，如果没有则使用默认的租客列表
        $signers = request()->input('signers', []);
        
        if (empty($signers)) {
            // 默认签署人：租客 + 业主
            $signers = [];
            
            // 添加租客
            foreach ($lease->tenants as $index => $tenant) {
                $signers[] = [
                    'name' => $tenant->first_name . ' ' . $tenant->last_name,
                    'email' => $tenant->email,
                    'type' => 'tenant',
                    'recipient_id' => count($signers) + 1,
                    'routing_order' => count($signers) + 1,
                ];
            }
            
            // 添加业主
            foreach ($lease->property->owners as $index => $owner) {
                $signers[] = [
                    'name' => $owner->first_name . ' ' . $owner->last_name,
                    'email' => $owner->email,
                    'type' => 'owner',
                    'recipient_id' => count($signers) + 1,
                    'routing_order' => count($signers) + 1,
                ];
            }
        }

        // 调用服务
        $docusign = new \App\Services\DocuSignService();
        $result = $docusign->sendContractForSignatureToMultipleSigners($pdfPath, $signers);

        if ($result['success']) {
            // ✅ 更新文件记录 envelope_id 和签署状态
            $contractFile->update([
                'envelope_id'      => $result['envelope_id'],
                'signature_status' => 'sent',
            ]);

            return request()->expectsJson()
                ? response()->json([
                    'success' => true,
                    'envelope_id' => $result['envelope_id'],
                    'status' => $result['status'],
                    'message' => '合同已发送给所有签署人'
                ])
                : redirect()->back()->with('success', '合同已发送给所有签署人');
        } else {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => '发送失败：' . $result['error']])
                : redirect()->back()->with('error', '发送失败：' . $result['error']);
        }
    }

    /**
     * 获取租赁合同的签署人列表
     */
    public function getSigners($id)
    {
        $lease = Lease::with(['tenants', 'property.owners'])->findOrFail($id);
        
        $signers = [];
        
        // 添加租客
        foreach ($lease->tenants as $tenant) {
            $signers[] = [
                'name' => $tenant->first_name . ' ' . $tenant->last_name,
                'email' => $tenant->email,
                'type' => 'tenant',
                'checked' => true, // 默认选中
            ];
        }
        
        // 添加业主
        foreach ($lease->property->owners as $owner) {
            $signers[] = [
                'name' => $owner->first_name . ' ' . $owner->last_name,
                'email' => $owner->email,
                'type' => 'owner',
                'checked' => true, // 默认选中
            ];
        }
        
        return response()->json([
            'success' => true,
            'signers' => $signers
        ]);
    }



    // =================== PDF生成相关方法 ===================

    /**
     * 发现PDF表单字段
     */
    public function discoverFormFields()
    {
        $templateFile = storage_path('app/templates/Lease_Agreement_2024.pdf');

        if (!file_exists($templateFile)) {
            return response()->json(['error' => 'Template file not found'], 404);
        }

        // 直接使用PDFtk命令行
        $command = "pdftk '" . $templateFile . "' dump_data_fields";
        $output = shell_exec($command);

        if ($output) {
            $fields = $this->parseFieldsOutput($output);

            return response()->json([
                'success' => true,
                'method' => 'Command line only',
                'field_count' => count($fields),
                'fields' => $fields,
                'raw_output' => $output // 调试用
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'PDFtk command returned no output',
                'command' => $command
            ]);
        }
    }

    private function parseFieldsOutput($output)
    {
        $fields = [];
        $lines = explode("\n", $output);
        $currentField = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, 'FieldName:') === 0) {
                // 保存前一个字段
                if (!empty($currentField)) {
                    $fields[] = $currentField;
                }
                // 开始新字段
                $currentField = ['name' => trim(substr($line, 10))];
            } elseif (strpos($line, 'FieldType:') === 0) {
                $currentField['type'] = trim(substr($line, 10));
            } elseif (strpos($line, 'FieldValue:') === 0) {
                $currentField['value'] = trim(substr($line, 11));
            } elseif (strpos($line, 'FieldMaxLength:') === 0) {
                $currentField['max_length'] = trim(substr($line, 15));
            } elseif (strpos($line, 'FieldFlags:') === 0) {
                $currentField['flags'] = trim(substr($line, 11));
            }
        }

        // 添加最后一个字段
        if (!empty($currentField)) {
            $fields[] = $currentField;
        }

        return $fields;
    }

    /**
     * 生成PDF合同 - 核心功能
     */
    public function generatePdf($id)
    {
        try {
            // 1. 获取数据
            $lease = Lease::with(['tenants', 'property'])
                ->where('lease_id', $id)
                ->firstOrFail();

            $contractData = $this->assembleContractData($lease);

            // 2. 文件路径设置 - 修正为storage路径
            $templateFile = storage_path('app/templates/Lease_Agreement_2024.pdf');
            $fileName = 'lease_contract_' . $lease->lease_id . '_' . date('YmdHis') . '.pdf';

            // 使用正确的storage路径
            $relativePath = 'uploads/files/leases/' . $lease->lease_id;
            $fullPath = storage_path('app/public/' . $relativePath);  // 修改这里
            $outputFile = $fullPath . '/' . $fileName;

            // 确保目录存在
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // 3. 创建FDF数据文件
            $fdfFile = $this->createFdfFile($contractData);

            // 4. 使用PDFtk生成PDF
            $command = "pdftk '" . $templateFile . "' fill_form '" . $fdfFile . "' output '" . $outputFile . "' flatten 2>&1";
            $result = shell_exec($command);

            // 5. 检查结果
            if (file_exists($outputFile) && filesize($outputFile) > 0) {

                // 6. 保存到files表
                $fileRecord = $lease->files()->create([
                    'title' => 'Lease Contract',
                    'filename' => $fileName,
                    'path' => $relativePath . '/' . $fileName,
                    'mime_type' => 'application/pdf',
                    'size' => filesize($outputFile),
                    'storage' => 'public',
                    'fileable_type' => 'App\Models\Lease',
                    'fileable_id' => $lease->lease_id,
                    'category' => 'contract',
                    'description' => 'Generated lease contract for tenant: ',
                    'is_cover' => false,
                    'sort_order' => 0,
                    'is_private' => true,
                    'uploaded_by' => auth()->id() ?? null,
                ]);

                // 7. 更新lease状态
                $lease->update([
                    'contract_file_path' => 'storage/' . $relativePath . '/' . $fileName,
                    'status' => 'active'
                ]);

                // 清理临时文件
                unlink($fdfFile);

                // 8. 浏览器直接预览 PDF
                return redirect('storage/' . $relativePath . '/' . $fileName);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to generate PDF contract',
                    'error' => 'PDF file was not created successfully'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while generating the contract',
                'error' => $e->getMessage()
            ]);
        }
    }

    // 添加格式化文件大小的辅助方法
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * 创建FDF数据文件
     */
    // private function createFdfFile($contractData)
    // {
    //     $fdfHeader = "%FDF-1.2\n%âãÏÓ\n1 0 obj\n<<\n/FDF << /Fields [";
    //     $fdfFooter = "] >>\n>>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n%%EOF";

    //     // 字段映射
    //     $fieldMappings = [
    //         // 房东信息
    //         'last name' => 'Sutton-Group MetroLand Realty',
    //         'first and middle names' => '',
    //         'last name_2' => '',
    //         'first and middle names_2' => '',

    //         // 租客信息
    //         'last name_3' => $contractData['tenant_last_name'],
    //         'first and middle names_3' => $contractData['tenant_first_name'],
    //         'last name_4' => '',
    //         'first and middle names_4' => '',

    //         // 联系信息
    //         'TT email 1' => $contractData['tenant_email'],
    //         'optional other phone number' => $contractData['tenant_phone'],
    //         'TT email 2' => '',

    //         // 物业地址
    //         'unit #' => '',
    //         'street number and street name1' => $contractData['property_address_street'],
    //         'City1' => $contractData['property_address_city'],
    //         'Province1' => $contractData['property_address_province'],
    //         'Postalcode1' => $contractData['property_address_postal_code'],

    //         // 房东服务地址
    //         'unitsite' => '',
    //         'street number and street name_2' => '8962 University High Street',
    //         'city2' => 'Burnaby',
    //         'province2' => 'BC',
    //         'postal code2' => 'V5A 4Y6',
    //         'daytime phone number' => '604-XXX-XXXX',
    //         'LL email 1' => 'info@suttonmetroland.com',

    //         // 日期信息
    //         'This tenancy created by this agreement starts on' => $contractData['start_day'],
    //         'month1' => $contractData['start_month'],
    //         'year1' => $contractData['start_year'],
    //         'C and is for a fixed term ending on' => 'On',
    //         'This tenancy created by this agreement ends on' => $contractData['end_day'],
    //         'month2' => $contractData['end_month'],
    //         'year2' => $contractData['end_year'],
    //         'D At the end of this time the tenancy will continue on a monthtomonth basis or another fixed length of' => 'On',

    //         // 租金信息
    //         'The tenant will pay the rent of' => '$' . $contractData['monthly_rent'],
    //         'month to the landlord on' => 'On',
    //         'the first day of the rental period which falls on the due date eg 1st 2nd 3rd  31st' => $contractData['rent_due_day'],
    //         'month subject to rent increases given in accordance with the RTA' => 'On',

    //         // 服务
    //         'water' => $this->isUtilityIncluded($contractData, 'water') ? 'Yes' : 'Off',
    //         'Electricity' => $this->isUtilityIncluded($contractData, 'electricity') ? 'Yes' : 'Off',
    //         'Heat' => $this->isUtilityIncluded($contractData, 'heat') ? 'Yes' : 'Off',
    //         'Internet' => $this->isUtilityIncluded($contractData, 'internet') ? 'Yes' : 'Off',
    //         'natural gas' => $this->isUtilityIncluded($contractData, 'gas') ? 'Yes' : 'Off',
    //         'Parking' => $this->isUtilityIncluded($contractData, 'parking') ? 'Yes' : 'Off',

    //         // 押金
    //         'The tenant is required to pay a security deposit of' => '$' . $contractData['security_deposit'],
    //         'month3' => $contractData['start_month'],
    //         'year3' => $contractData['start_year'],
    //         'The tenant is required to pay a pet damage deposit of' => $contractData['pet_deposit'] > 0 ? '$' . $contractData['pet_deposit'] : '',
    //         'month4' => $contractData['pet_deposit'] > 0 ? $contractData['start_month'] : '',
    //         'year4' => $contractData['pet_deposit'] > 0 ? $contractData['start_year'] : '',
    //         'not applicable' => $contractData['pet_deposit'] <= 0 ? 'On' : 'Off',

    //         // 附录
    //         'If there is an Addendum attached  provide the following information on the Addendum that forms part of this' => 'On',

    //         // 签名
    //         'last name_5' => 'Sutton-Group MetroLand Realty',
    //         'Landlords first and middle name' => '',
    //         'Date' => date('Y-m-d'),
    //         'last name_7' => $contractData['tenant_last_name'],
    //         'first and middle names_7' => $contractData['tenant_first_name'],
    //         'Date_3' => date('Y-m-d'),
    //     ];

    //     // 构建FDF数据
    //     $fieldsData = [];
    //     foreach ($fieldMappings as $fieldName => $value) {
    //         if ($value !== null && $value !== '') {
    //             $escapedValue = $this->escapeFdfValue($value);
    //             $fieldsData[] = "<< /T (" . $fieldName . ") /V (" . $escapedValue . ") >>";
    //         }
    //     }

    //     $fdfContent = $fdfHeader . "\n" . implode("\n", $fieldsData) . "\n" . $fdfFooter;

    //     $tempFile = tempnam(sys_get_temp_dir(), 'lease_form_') . '.fdf';
    //     file_put_contents($tempFile, $fdfContent);

    //     return $tempFile;
    // }

    private function createFdfFile($contractData)
    {
        $fdfHeader = "%FDF-1.2\n%âãÏÓ\n1 0 obj\n<<\n/FDF << /Fields [";
        $fdfFooter = "] >>\n>>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n%%EOF";


        // 拆名字
        $firstNames = explode(',', $contractData['tenant_first_names']);
        $lastNames = explode(',', $contractData['tenant_last_names']);

        // 去空格
        $firstNames = array_map('trim', $firstNames);
        $lastNames = array_map('trim', $lastNames);

        // 分离第一个人和其余人
        $firstFirstName = $firstNames[0] ?? '';
        $firstLastName = $lastNames[0] ?? '';
        $otherFirstNames = array_slice($firstNames, 1);
        $otherLastNames = array_slice($lastNames, 1);

        // 联系方式处理
        $emails = explode(',', $contractData['tenant_emails'] ?? '');
        $phones = explode(',', $contractData['tenant_phones'] ?? '');
        $emails = array_map('trim', $emails);
        $phones = array_map('trim', $phones);

        // 字段映射
        $fieldMappings = [
            // 房东信息
            'last name' => 'Sutton-Group MetroLand Realty',
            'first and middle names' => '',
            'last name_2' => '',
            'first and middle names_2' => '',



            // 租客名字部分（first/last name 只取第一个人+其余人）
            'last name_3'             => $firstLastName,
            'first and middle names_3' => $firstFirstName,
            'last name_4'             => implode('/', $otherLastNames),
            'first and middle names_4' => implode('/', $otherFirstNames),

            //租客联系方式
            'TT email 1'              => $emails[0] ?? '',
            'optional other phone number' => $phones[0] ?? '',
            'TT email 2'              => implode('/', array_slice($emails, 1)),

            // 物业地址
            'unit #' => '',
            'street number and street name1' => $contractData['property_address_street'],
            'City1' => $contractData['property_address_city'],
            'Province1' => $contractData['property_address_province'],
            'Postalcode1' => $contractData['property_address_postal_code'],

            // 房东服务地址
            'unitsite' => '',
            'street number and street name_2' => '8962 University High Street',
            'city2' => 'Burnaby',
            'province2' => 'BC',
            'postal code2' => 'V5A 4Y6',
            'daytime phone number' => '604-XXX-XXXX',
            'LL email 1' => 'info@suttonmetroland.com',

            // 日期信息
            'This tenancy created by this agreement starts on' => $contractData['start_day'],
            'month1' => $contractData['start_month'],
            'year1' => $contractData['start_year'],
            'C and is for a fixed term ending on' => 'On',
            'This tenancy created by this agreement ends on' => $contractData['end_day'],
            'month2' => $contractData['end_month'],
            'year2' => $contractData['end_year'],
            'D At the end of this time the tenancy will continue on a monthtomonth basis or another fixed length of' => 'On',

            // 租金信息
            'The tenant will pay the rent of' => '$' . $contractData['monthly_rent'],
            'month to the landlord on' => 'On',
            'the first day of the rental period which falls on the due date eg 1st 2nd 3rd  31st' => $contractData['rent_due_day'],
            'month subject to rent increases given in accordance with the RTA' => 'On',

            // 服务
            'water' => $this->isUtilityIncluded($contractData, 'water') ? 'Yes' : 'Off',
            'Electricity' => $this->isUtilityIncluded($contractData, 'electricity') ? 'Yes' : 'Off',
            'Heat' => $this->isUtilityIncluded($contractData, 'heat') ? 'Yes' : 'Off',
            'Internet' => $this->isUtilityIncluded($contractData, 'internet') ? 'Yes' : 'Off',
            'natural gas' => $this->isUtilityIncluded($contractData, 'gas') ? 'Yes' : 'Off',
            'Parking' => $this->isUtilityIncluded($contractData, 'parking') ? 'Yes' : 'Off',

            // 押金
            'The tenant is required to pay a security deposit of' => '$' . $contractData['security_deposit'],
            'month3' => $contractData['start_month'],
            'year3' => $contractData['start_year'],
            'The tenant is required to pay a pet damage deposit of' => $contractData['pet_deposit'] > 0 ? '$' . $contractData['pet_deposit'] : '',
            'month4' => $contractData['pet_deposit'] > 0 ? $contractData['start_month'] : '',
            'year4' => $contractData['pet_deposit'] > 0 ? $contractData['start_year'] : '',
            'not applicable' => $contractData['pet_deposit'] <= 0 ? 'On' : 'Off',

            // 附录
            'If there is an Addendum attached  provide the following information on the Addendum that forms part of this' => 'On',

            // 签名
            'last name_5' => 'Sutton-Group MetroLand Realty',
            'Landlords first and middle name' => '',
            'Date' => date('Y-m-d'),
            'last name_7' => $firstLastName,
            'first and middle names_7' => $firstFirstName,
            'Date_3' => date('Y-m-d'),
        ];

        // 构建FDF数据
        $fieldsData = [];
        foreach ($fieldMappings as $fieldName => $value) {
            if ($value !== null && $value !== '') {
                $escapedValue = $this->escapeFdfValue($value);
                $fieldsData[] = "<< /T (" . $fieldName . ") /V (" . $escapedValue . ") >>";
            }
        }

        $fdfContent = $fdfHeader . "\n" . implode("\n", $fieldsData) . "\n" . $fdfFooter;

        $tempFile = tempnam(sys_get_temp_dir(), 'lease_form_') . '.fdf';
        file_put_contents($tempFile, $fdfContent);

        return $tempFile;
    }


    /**
     * 转义FDF特殊字符
     */
    private function escapeFdfValue($value)
    {
        $value = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string)$value);
        return $value;
    }

    /**
     * 组装合同数据
     */
    private function assembleContractData($lease)
    {
        $first_names = [];
        $last_names = [];
        $phones = [];
        $emails = [];

        foreach ($lease->tenants as $tenant) {

            // dd($tenant->first_name);
            if (!empty($tenant->first_name)) {
                $first_names[] = $tenant->first_name;
            }
            if (!empty($tenant->last_name)) {
                $last_names[] = $tenant->last_name;
            }
            if (!empty($tenant->phone)) {
                $phones[] = $tenant->phone;
            }
            if (!empty($tenant->email)) {
                $emails[] = $tenant->email;
            }
        }

        return [
            'lease_number' => $lease->lease_number,
            'lease_type' => $lease->lease_type,
            'start_date' => $lease->start_date->format('Y-m-d'),
            'end_date' => $lease->end_date->format('Y-m-d'),
            'tenant_first_names' => implode(', ', $first_names),
            'tenant_last_names'  => implode(', ', $last_names),
            'tenant_phones'      => implode(', ', $phones),
            'tenant_emails'      => implode(', ', $emails),
            'property_address_street' => $lease->property->address_street,
            'property_address_city' => $lease->property->address_city,
            'property_address_province' => $lease->property->address_province,
            'property_address_postal_code' => $lease->property->address_postal_code,
            'monthly_rent' => number_format($lease->monthly_rent, 2),
            'security_deposit' => number_format($lease->security_deposit, 2),
            'pet_deposit' => number_format($lease->pet_deposit, 2),
            'rent_due_day' => $lease->rent_due_day,
            'utilities_included' => $lease->utilities_included ?? '',
            'start_day' => $lease->start_date->format('d'),
            'start_month' => $lease->start_date->format('m'),
            'start_year' => $lease->start_date->format('Y'),
            'end_day' => $lease->end_date->format('d'),
            'end_month' => $lease->end_date->format('m'),
            'end_year' => $lease->end_date->format('Y'),
            'pets_allowed' => $lease->pets_allowed ? 'Yes' : 'No',
            'smoking_allowed' => $lease->smoking_allowed ? 'Yes' : 'No',
            'furnished' => $lease->furnished ? 'Yes' : 'No',
        ];
    }

    /**
     * 检查是否包含某项服务
     */
    private function isUtilityIncluded($contractData, $utility)
    {
        $utilities = strtolower($contractData['utilities_included'] ?? '');
        return strpos($utilities, strtolower($utility)) !== false;
    }

    // 在LeaseController中添加这个调试方法
    public function debugPdfGeneration($id)
    {
        try {
            $lease = Lease::with(['tenant', 'property'])
                ->where('lease_id', $id)
                ->firstOrFail();

            // 检查各个路径
            $templateFile = storage_path('app/templates/Lease_Agreement_2024.pdf');
            $relativePath = 'uploads/files/leases/' . $lease->lease_id;
            $fullPath = public_path($relativePath);
            $fileName = 'lease_contract_' . $lease->lease_id . '_' . date('YmdHis') . '.pdf';
            $outputFile = $fullPath . '/' . $fileName;

            return response()->json([
                'lease_id' => $lease->lease_id,
                'template_file' => $templateFile,
                'template_exists' => file_exists($templateFile),
                'relative_path' => $relativePath,
                'full_path' => $fullPath,
                'public_path' => public_path(),
                'uploads_exists' => file_exists(public_path('uploads')),
                'uploads_writable' => is_writable(public_path('uploads')),
                'files_dir_exists' => file_exists(public_path('uploads/files')),
                'files_dir_writable' => is_writable(public_path('uploads/files')),
                'target_dir_exists' => file_exists($fullPath),
                'parent_dir_writable' => is_writable(dirname($fullPath)),
                'output_file_path' => $outputFile,
                'mkdir_result' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
