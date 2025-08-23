<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\ComplianceInfo;
use App\Models\FinancialInfo;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyMedia;
use App\Models\RentalInfo;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * 显示所有房源列表
     */
    public function index(Request $request)
    {
        $query = Property::with([
            'rentalInfo', 
            'owners', 
            'media',
            'feature',  // 添加feature关联，避免N+1查询
            'amenity'   // 添加amenity关联，支持设施显示
        ]);

        //调用helpers中的functions
        $query = applyKeywordSearch($query, $request);
        $query = applyFilters($query, $request);
        $query = applySorting($query, $request);
        $properties = applyPagination($query, $request);

        return view('properties.index', compact('properties'));
    }

    /**
     * 显示创建页面
     */
    public function create()
    {
        $property = new Property;

        return view('properties.create', compact('property'));
    }

    /**
     * 保存新房源
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_name' => 'required|string|max:100',
            'property_type' => 'required',
            'ownership_type' => 'required',
            'address_street' => 'required',
            'address_city' => 'required',
            'address_province' => 'required',
            'address_postal_code' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $propertyId = 'P' . Str::upper(Str::random(8));

            // 创建主房源
            $property = Property::create([
                'property_id' => $propertyId,
                'property_name' => $request->property_name,
                'property_type' => $request->property_type,
                'ownership_type' => $request->ownership_type,
                'year_built' => $request->year_built,
                'address_street' => $request->address_street,
                'address_city' => $request->address_city,
                'address_province' => $request->address_province,
                'address_postal_code' => $request->address_postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total_floors' => $request->total_floors,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 子表：Feature
            PropertyFeature::create([
                'property_id' => $propertyId,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'square_footage' => $request->square_footage,
                'parking_spaces' => $request->parking_spaces,
                'parking_type' => $request->parking_type,
                'heating_type' => $request->heating_type,
                'cooling_type' => $request->cooling_type,
                'furnished' => $request->furnished ? 1 : 0,
                'laundry' => $request->laundry ?? 'None',
            ]);

            // 子表：Amenities
            Amenity::create(array_merge(
                ['property_id' => $propertyId],
                $request->only([
                    'has_gym',
                    'has_pool',
                    'has_balcony',
                    'has_elevator',
                    'has_dishwasher',
                    'has_fridge',
                    'has_stove',
                    'has_microwave',
                    'has_air_conditioning',
                ])
            ));

            // 子表：Rental Info
            RentalInfo::create([
                'property_id' => $propertyId,
                'availability_status' => $request->availability_status,
                'monthly_rent' => $request->monthly_rent,
                'security_deposit' => $request->security_deposit,
                'lease_term_type' => $request->lease_term_type,
                'min_lease_term' => $request->min_lease_term,
                'available_date' => $request->available_date,
                'utilities_included' => is_array($request->utilities_included) ? implode(',', $request->utilities_included) : null, // ✅ 修复点
                'pet_policy' => $request->pet_policy,
                'pet_fee' => $request->pet_fee,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 子表：Financial Info
            FinancialInfo::create(array_merge(
                ['property_id' => $propertyId],
                $request->only(['management_fee_percentage', 'annual_property_tax', 'hst_included', 'maintenance_fund'])
            ));

            // 子表：Compliance Info
            ComplianceInfo::create(array_merge(
                ['property_id' => $propertyId],
                $request->only([
                    'property_tax_id',
                    'rental_license_number',
                    'insurance_policy_number',
                    'fire_safety_compliance',
                    'accessibility_compliance',
                    'last_inspection_date',
                ])
            ));

            $cover = $request->input('cover_media'); // 用户选择的封面文件名

            // 保存媒体文件
            if ($request->has('uploaded_files')) {
                foreach ($request->input('uploaded_files', []) as $tempPath) {
                    if (! $tempPath || ! is_string($tempPath)) {
                        continue;
                    }

                    $filename = basename($tempPath);

                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $type = in_array(strtolower($ext), ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                    $fullTempPath = 'temp/property-media/' . $filename;
                    $newPath = "property_media/{$property->property_id}/{$filename}";

                    // 移动临时文件到正式目录
                    if (Storage::exists($fullTempPath)) {
                        Storage::move($fullTempPath, $newPath);

                        PropertyMedia::create([
                            'property_id' => $property->property_id,
                            'media_type' => $type,
                            'file_path' => $newPath,
                            'is_cover' => ($filename === $cover),
                        ]);
                    }
                }
            }

            if ($cover) {
                PropertyMedia::where('property_id', $property->property_id)->update(['is_cover' => 0]);
                PropertyMedia::where('property_id', $property->property_id)
                    ->whereRaw('RIGHT(file_path, LENGTH(?)) = ?', [$cover, $cover])
                    ->update(['is_cover' => 1]);
            }

            // 拖拽排序字段（media_order[]）写入 sort_order
            $mediaOrder = $request->input('media_order', []);
            foreach ($mediaOrder as $index => $filename) {
                PropertyMedia::where('property_id', $property->property_id)
                    ->whereRaw('RIGHT(file_path, LENGTH(?)) = ?', [$filename, $filename])
                    ->update(['sort_order' => $index]);
            }

            DB::commit();

            return redirect()->route('properties.index')->with('success', '房源已成功添加');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => '保存失败：' . $e->getMessage()])->withInput();
        }
    }

    /**
     * 编辑页面
     */
    public function edit($propertyId)
    {
        $property = Property::with([
            'feature',
            'amenity',
            'rentalInfo',
            'financialInfo',
            'complianceInfo',
            'media',
        ])->findOrFail($propertyId);

        $property = Property::with([
            'media' => function ($q) {
                $q->orderBy('sort_order');
            },
        ])->findOrFail($propertyId);

        return view('properties.edit', compact('property'));
    }

    /**
     * 更新房源信息
     */
    public function update(Request $request, $propertyId)
    {
        $request->validate([
            'property_name' => 'required|string|max:100',
            'property_type' => 'required',
            'ownership_type' => 'required',
            'address_street' => 'required',
            'address_city' => 'required',
            'address_province' => 'required',
            'address_postal_code' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $property = Property::findOrFail($propertyId);
            $property->update($request->only([
                'property_name',
                'property_type',
                'ownership_type',
                'year_built',
                'address_street',
                'address_city',
                'address_province',
                'address_postal_code',
                'latitude',
                'longitude',
                'total_floors',
                'description',
            ]));

            $property->feature()->update($request->only([
                'bedrooms',
                'bathrooms',
                'square_footage',
                'parking_spaces',
                'parking_type',
                'heating_type',
                'cooling_type',
                'furnished',
                'laundry',
            ]));

            $amenityData = $request->only([
                'has_gym',
                'has_pool',
                'has_balcony',
                'has_elevator',
                'has_dishwasher',
                'has_fridge',
                'has_stove',
                'has_microwave',
                'has_air_conditioning',
            ]);

            if (! empty($amenityData)) {
                $property->amenity()->update($amenityData);
            }

            $property->rentalInfo()->update([
                'availability_status' => $request->availability_status,
                'monthly_rent' => $request->monthly_rent,
                'security_deposit' => $request->security_deposit,
                'lease_term_type' => $request->lease_term_type,
                'min_lease_term' => $request->min_lease_term,
                'available_date' => $request->available_date,
                'utilities_included' => is_array($request->utilities_included) ? implode(',', $request->utilities_included) : null,
                'pet_policy' => $request->pet_policy,
                'pet_fee' => $request->pet_fee,
            ]);

            $property->financialInfo()->update($request->only([
                'management_fee_percentage',
                'annual_property_tax',
                'hst_included',
                'maintenance_fund',
            ]));

            $property->complianceInfo()->update($request->only([
                'property_tax_id',
                'rental_license_number',
                'insurance_policy_number',
                'fire_safety_compliance',
                'accessibility_compliance',
                'last_inspection_date',
            ]));

            // ✅ 先删除旧媒体记录 & 文件
            $keepFiles = $request->input('existing_files', []);
            $cover = $request->input('cover_media');

            // 删除被移除的旧媒体
            $property->media->each(function ($media) use ($keepFiles) {
                if (! in_array($media->file_path, $keepFiles)) {
                    Storage::delete($media->file_path);
                    $media->delete();
                }
            });

            // 保存媒体文件
            if ($request->has('uploaded_files')) {
                foreach ($request->input('uploaded_files', []) as $tempPath) {

                    if (! $tempPath || ! is_string($tempPath)) {
                        continue;
                    }

                    $filename = basename($tempPath);
                    $fullTempPath = 'temp/property-media/' . $filename;
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $type = in_array(strtolower($ext), ['mp4', 'mov', 'avi']) ? 'video' : 'image';

                    $newPath = "property_media/{$property->property_id}/{$filename}";

                    // 移动临时文件到正式目录
                    if (Storage::exists($fullTempPath)) {

                        Storage::move($fullTempPath, $newPath);

                        PropertyMedia::create([
                            'property_id' => $property->property_id,
                            'media_type' => $type,
                            'file_path' => $newPath,
                            'is_cover' => ($filename === $cover),
                        ]);
                    }
                }
            }

            // 更新封面图（无论是旧图或新图）
            if ($cover) {
                PropertyMedia::where('property_id', $property->property_id)->update(['is_cover' => 0]);
                PropertyMedia::where('property_id', $property->property_id)
                    ->whereRaw('RIGHT(file_path, LENGTH(?)) = ?', [$cover, $cover])
                    ->update(['is_cover' => 1]);
            }

            // 更新媒体排序
            $mediaOrder = $request->input('media_order', []);
            foreach ($mediaOrder as $index => $filename) {
                PropertyMedia::where('property_id', $property->property_id)
                    ->whereRaw('RIGHT(file_path, LENGTH(?)) = ?', [$filename, $filename])
                    ->update(['sort_order' => $index]);
            }

            DB::commit();

            return redirect()->route('properties.index')->with('success', '房源信息已更新');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => '更新失败：' . $e->getMessage()])->withInput();
        }
    }

    /**
     * 软删除
     */
    // public function destroy($propertyId)
    // {
    //     $property = Property::findOrFail($propertyId);
    //     $property->update([
    //         'deleted_at' => now(),
    //         'deleted_by' => Auth::id(),
    //     ]);

    //     return redirect()->route('properties.index')->with('success', '房源已删除');
    // }

    public function destroy($propertyId)
    {
        $property = Property::find($propertyId);

        if (!$property) {
            return response()->json([
                'status'  => 'error',
                'message' => '该房源不存在',
            ], 404);
        }

        $property->delete();

        $property->update([
            'deleted_by' => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => '该房源已删除',
        ]);
    }

    public function export(Request $request)
    {
        $filename = 'properties_export_' . now()->format('Ymd_His') . '.csv';

        $query = Property::whereNull('deleted_at')
            ->with(['feature', 'rentalInfo', 'ownership.owner']);

        // 同样的筛选逻辑（可抽出复用）
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('property_name', 'like', "%$kw%")
                    ->orWhere('address_street', 'like', "%$kw%")
                    ->orWhere('address_city', 'like', "%$kw%")
                    ->orWhereHas('ownership.owner', function ($sub) use ($kw) {
                        $sub->where('first_name', 'like', "%$kw%")
                            ->orWhere('last_name', 'like', "%$kw%");
                    });
            });
        }

        // 导出数据
        $properties = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($properties) {
            $output = fopen('php://output', 'w');

            // CSV 表头
            fputcsv($output, [
                '房源名称',
                '类型',
                '地址',
                '租金',
                '卧室',
                '卫浴',
                '状态',
                '房东',
            ]);

            foreach ($properties as $p) {
                fputcsv($output, [
                    $p->property_name,
                    $p->property_type,
                    "{$p->address_street}, {$p->address_city}, {$p->address_province}",
                    optional($p->rentalInfo)->monthly_rent,
                    optional($p->feature)->bedrooms,
                    optional($p->feature)->bathrooms,
                    optional($p->rentalInfo)->availability_status,
                    optional($p->ownership->owner)->full_name,
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $property = Property::with([
            'feature',
            'media',
            'rentalInfo',
            'ownerships.owner',
            'FinancialInfo',
            'files',
            'files.uploader',
            'leases.tenants',
        ])->findOrFail($id);

        $allOwners = Owner::whereNull('deleted_at')->get();

        $attachments = $property->files->map(function ($file) {
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

        $today = now()->toDateString();

        // 获取所有 leases（含租客）
        $leases = $property->leases->whereNull('deleted_at');

        // 当前有效合同
        $activeLeases = $leases->filter(fn($lease) => $lease->end_date >= $today);
        // 已过期合同
        $expiredLeases = $leases->filter(fn($lease) => $lease->end_date < $today);

        // 当前有效合同的租客
        $activeLeasesTenants = collect();
        // 已过期合同的租客
        $expiredLeasesTenants = collect();

        foreach ($leases as $lease) {
            $leaseTenants = $lease->tenants->whereNull('deleted_at');

            foreach ($leaseTenants as $tenant) {
                $tenant->setRelation('lease', $lease); // 加入 lease 属性
                $tenant->setRelation('pivot', $tenant->pivot); // 确保 pivot 仍在

                if ($lease->end_date >= $today) {
                    $activeLeasesTenants->push($tenant);
                } else {
                    $expiredLeasesTenants->push($tenant);
                }
            }
        }

        return view('properties.show', compact(
            'property',
            'allOwners',
            'attachments',
            'activeLeasesTenants',
            'expiredLeasesTenants',
            'activeLeases',
            'expiredLeases'
        ));
    }

    public function batchDelete(Request $request)
    {
        $ids = $request->input('ids', []);

         if (empty($ids)) {
            return response()->json(['error' => '请选择至少一条记录'], 422);
        }

        //注意这里其他模块要改id名称，class名称
        $properties = Property::whereIn('property_id', $ids)->get();
        foreach ($properties as $property) { //为了记录删除记录，不得已用for没办法用whereIn
            $property->delete();
            $property->update([
                'deleted_by' => Auth::id(),
            ]);
        }

        $count = $properties->count();

        return response()->json([
            'success' => true,
            'message' => "成功删除 {$count} 个房源",
            'deleted_count' => $count,
        ]);
    }


    // PropertyController.php
    public function addOwner(Request $request, Property $property)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,owner_id',
            'ownership_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $property->owners()->attach($validated['owner_id'], [
            'ownership_percentage' => $validated['ownership_percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->back()->with('success', '房东已成功关联。');
    }
}
