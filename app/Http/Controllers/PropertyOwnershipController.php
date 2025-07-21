<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Owner;
use App\Models\PropertyOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PropertyOwnershipController extends Controller
{
    public function store(Request $request)
    {
        // \Log::info('进入 store 方法');
        \Log::info($request->all());
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,property_id', // property_id 是外键，确保该房源存在
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:255',

            'ownership_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        \Log::info('验证通过');

        // 先新增业主
        $owner = Owner::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'address' => $validated['address'],
            'tax_id' => $validated['tax_id'],
            'notes' => $validated['notes'],
        ]);

        $property = Property::findOrFail($validated['property_id']);
        // \Log::info($owner);
        // \Log::info($property);

        // 再挂接房源

        // \Log::info('正在添加中间表记录', [
        //     // 'property_id' => $property->property_id,
        //     'owner_id' => $owner->owner_id,
        // ]);
        $property->owners()->attach($owner->owner_id, [
            'ownership_percentage' => $validated['ownership_percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_primary' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // \Log::info('attach 成功');

        return response()->json([
            'status' => 'success',
            'message' => '添加成功',
        ]);
    }

    public function update(Request $request, $propertyId, $ownerId)
    {
        \Log::info($request->all());
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,property_id', // property_id 是外键，确保该房源存在
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:255',

            'ownership_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        \Log::info(' test2');

        // 1. 更新 owners 表
        Owner::where('owner_id', $ownerId)->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'phone'     => $validated['phone'],
            'email'     => $validated['email'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'address'   => $validated['address'],
            'tax_id' => $validated['tax_id'],
            'notes' => $validated['notes'],
        ]);

        // 2. 更新 property_ownership 中间表
        DB::table('propertyOwnership')
            ->where('property_id', $propertyId)
            ->where('owner_id', $ownerId)
            ->update([
                'ownership_percentage' => $validated['ownership_percentage'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'updated_at' => now(),
            ]);

        return response()->json(['status' => 'success', 'message' => '添加成功',]);
    }

    public function destroy($propertyId, $ownerId)
    {
        Owner::where('owner_id', $ownerId)->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        PropertyOwnership::where('owner_id', $ownerId)
            ->where('property_id', $propertyId)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id(),
            ]);

        return redirect()->back()->with('success', '删除成功。');
    }
}
