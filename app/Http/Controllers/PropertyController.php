<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\Amenity;
use App\Models\FinancialInfo;
use App\Models\ComplianceInfo;
use App\Models\RentalInfo;
use App\Models\PropertyMedia;
use App\Models\Marketing;
use App\Models\PropertyOwnership;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * 显示所有房源列表
     */
    public function index(Request $request)
    {
        // $query = Property::where('is_active', 1)
        //     ->with([
        //         'feature',
        //         'rentalInfo',
        //         'media',
        //         'ownership.owner'
        //     ]);

        // 筛选
        // if ($request->filled('keyword')) {
        //     $kw = $request->keyword;
        //     $query->where(function ($q) use ($kw) {
        //         $q->where('property_name', 'like', "%$kw%")
        //             ->orWhere('address_street', 'like', "%$kw%")
        //             ->orWhere('address_city', 'like', "%$kw%")
        //             ->orWhereHas('ownership.owner', function ($sub) use ($kw) {
        //                 $sub->where('first_name', 'like', "%$kw%")
        //                     ->orWhere('last_name', 'like', "%$kw%");
        //             });
        //     });
        // }

        // if ($request->filled('city')) {
        //     $query->where('address_city', 'like', '%' . $request->city . '%');
        // }

        // if ($request->filled('status')) {
        //     $query->whereHas('rentalInfo', function ($q) use ($request) {
        //         $q->where('availability_status', $request->status);
        //     });
        // }

        // if ($request->filled('type')) {
        //     $query->where('property_type', $request->type);
        // }
        $query = Property::with(['rentalInfo', 'ownership.owner'])
            ->where('is_active', 1);

        if ($request->filled('keyword')) {
            $kw = '%' . $request->keyword . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('property_name', 'like', $kw)
                    ->orWhere('address_street', 'like', $kw)
                    ->orWhere('address_city', 'like', $kw);
            });
        }

        if ($request->filled('filters')) {
            foreach ($request->filters as $filter) {
                $value = $request->input("filter_values.$filter");
                match ($filter) {
                    'rent' => $query->whereHas('rentalInfo', fn($q) => $q->whereBetween('monthly_rent', [$value['min'] ?? 0, $value['max'] ?? 1000000])),
                    'city' => $query->where('address_city', 'like', "%{$value}%"),
                    'type' => $query->where('property_type', $value),
                    'owner_id' => $query->whereHas('ownership', fn($q) => $q->where('owner_id', $value)),
                    default => null
                };
            }
        }

        $owners = DB::table('activeowners')->get();
        $properties = $query->paginate(15);

        // 排序处理
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        if (in_array($sort, ['property_name', 'property_type', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } elseif ($sort === 'monthly_rent') {
            $query->join('rentalinfo', 'properties.property_id', '=', 'rentalinfo.property_id')
                ->orderBy('rentalinfo.monthly_rent', $direction)
                ->select('properties.*'); // 保留主表字段
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // $properties = $query->paginate(10)->appends($request->all());
        $perPage = $request->input('per_page', 10);
        $properties = $query->paginate($perPage)->appends($request->all());

        return view('properties.index', compact('properties'));
    }



    /**
     * 显示创建页面
     */
    public function create()
    {
        $property = new Property();
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
                'laundry' => $request->laundry ?? 'None'
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
                    'has_air_conditioning'
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
                    'last_inspection_date'
                ])
            ));

            $cover = $request->input('cover_media'); // 用户选择的封面文件名

            // 保存媒体文件
            if ($request->has('uploaded_files')) {
                foreach ($request->input('uploaded_files', []) as $tempPath) {
                    if (!$tempPath || !is_string($tempPath)) {
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
            'media'
        ])->findOrFail($propertyId);

        $property = Property::with([
            'media' => function ($q) {
                $q->orderBy('sort_order');
            }
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
                'description'
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
                'laundry'
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
                'has_air_conditioning'
            ]);

            if (!empty($amenityData)) {
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
                'maintenance_fund'
            ]));

            $property->complianceInfo()->update($request->only([
                'property_tax_id',
                'rental_license_number',
                'insurance_policy_number',
                'fire_safety_compliance',
                'accessibility_compliance',
                'last_inspection_date'
            ]));

            // ✅ 先删除旧媒体记录 & 文件
            $keepFiles = $request->input('existing_files', []);
            $cover = $request->input('cover_media');

            // 删除被移除的旧媒体
            $property->media->each(function ($media) use ($keepFiles) {
                if (!in_array($media->file_path, $keepFiles)) {
                    Storage::delete($media->file_path);
                    $media->delete();
                }
            });

            // 保存媒体文件
            if ($request->has('uploaded_files')) {
                foreach ($request->input('uploaded_files', []) as $tempPath) {

                    if (!$tempPath || !is_string($tempPath)) {
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
    public function destroy($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        $property->update([
            'is_active' => 0,
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('properties.index')->with('success', '房源已删除');
    }

    public function export(Request $request)
    {
        $filename = 'properties_export_' . now()->format('Ymd_His') . '.csv';

        $query = Property::where('is_active', 1)
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
                '房东'
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
        $property = Property::with(['feature', 'media', 'rentalInfo', 'ownership.owner'])->findOrFail($id);
        return view('properties.show', compact('property'));
    }

    public function batchDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return back()->with('error', '请选择要删除的房源');
        }

        $count = Property::whereIn('property_id', $ids)->update([
            'is_active' => 0,
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('properties.index')->with('success', "成功删除 {$count} 个房源");
    }
}
