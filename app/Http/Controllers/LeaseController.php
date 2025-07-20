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
        foreach ([
            'pets_allowed', 'smoking_allowed', 'subletting_allowed',
            'tenant_insurance_required', 'insurance_required', 'furnished',
            'strata_acknowledged', 'form_k_signed'
        ] as $field) {
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

    public function index()
    {
        return view('leases.index'); // 待补充
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

    public function update(Request $request, Lease $lease)
    {
        // 可选：实现更新逻辑（暂略）
    }
}
