@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    .dz-preview .dz-remove {
        font-size: 12px;
        color: red;
        cursor: pointer;
    }
    .dz-preview .cover-toggle {
        display: block;
        font-size: 12px;
        color: blue;
        cursor: pointer;
        margin-top: 4px;
    }
    .dz-preview.cover {
        border: 2px solid green;
    }
</style>
@endpush

<form method="POST" action="{{ $property->exists ? route('properties.update', $property->property_id) : route('properties.store') }}" enctype="multipart/form-data">

    @csrf
    @if($property->exists) @method('PUT') @endif

    <div class="container-fluid">

        <!-- 基础信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">基础信息</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">房源名称 *</label>
                    <input type="text" name="property_name" class="form-control" required maxlength="100" value="{{ old('property_name', $property->property_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">房源类型 *</label>
                    <select name="property_type" class="form-select select-filter" required>
                        @foreach(['Apartment','House','Townhouse','Condo','Basement','Other'] as $type)
                            <option value="{{ $type }}" @selected(old('property_type', $property->property_type ?? '') == $type)> {{ $type }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label">业权类型 *</label>
                  <select name="ownership_type" class="form-select select-filter" required>
                      @foreach(['Owned', 'Managed'] as $type)
                          <option value="{{ $type }}" @selected(old('ownership_type', $property->ownership_type ?? '') == $type)> {{ $type }} </option>
                      @endforeach
                  </select>
              </div>

                <div class="col-md-4">
                    <label class="form-label">建造年份</label>
                    <input type="number" name="year_built" class="form-control" min="1800" max="{{ date('Y') }}" value="{{ old('year_built', $property->year_built ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">街道地址 *</label>
                    <input type="text" name="address_street" class="form-control" required value="{{ old('address_street', $property->address_street ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">城市 *</label>
                    <input type="text" name="address_city" class="form-control" required value="{{ old('address_city', $property->address_city ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">所在省份 *</label>
                    <select name="address_province" class="form-select select-filter" required>
                        @foreach(['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK'] as $province)
                            <option value="{{ $province }}" @selected(old('address_province', $property->address_province ?? '') == $province)> {{ $province }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">邮编 *</label>
                    <input type="text" name="address_postal_code" class="form-control" required maxlength="10" value="{{ old('address_postal_code', $property->address_postal_code ?? '') }}">
                </div>

                
            </div>
        </div>

        <!-- 房屋特征 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">房屋特征</div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label">卧室数 *</label>
                    <input type="number" name="bedrooms" class="form-control" required min="0" value="{{ old('bedrooms', $property->feature->bedrooms ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">卫生间数 *</label>
                    <input type="number" step="0.5" name="bathrooms" class="form-control" required min="0" value="{{ old('bathrooms', $property->feature->bathrooms ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">面积（平方英尺）</label>
                    <input type="number" name="square_footage" class="form-control" min="0" value="{{ old('square_footage', $property->feature->square_footage ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">停车位数量</label>
                    <input type="number" name="parking_spaces" class="form-control" min="0" value="{{ old('parking_spaces', $property->feature->parking_spaces ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">停车类型</label>
                    <select name="parking_type" class="form-select select-filter">
                        @foreach(['Indoor','Outdoor','Garage','None'] as $type)
                            <option value="{{ $type }}" @selected(old('parking_type', $property->feature->parking_type ?? '') == $type)> {{ $type }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">供暖类型</label>
                    <input type="text" name="heating_type" class="form-control" maxlength="50" value="{{ old('heating_type', $property->feature->heating_type ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">制冷类型</label>
                    <input type="text" name="cooling_type" class="form-control" maxlength="50" value="{{ old('cooling_type', $property->feature->cooling_type ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">洗衣方式 *</label>
                    <select name="laundry" class="form-select select-filter" required>
                        @foreach(['In-unit','Shared','None'] as $option)
                            <option value="{{ $option }}" @selected(old('laundry', $property->feature->laundry ?? 'None') == $option)> {{ $option }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input" name="furnished" value="1" id="furnished" @checked(old('furnished', $property->feature->furnished ?? false))>
                        <label class="form-check-label" for="furnished">带家具</label>
                    </div>
                </div>

                

            </div>
        </div>

        <!-- 配套设施 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">配套设施</div>
            <div class="card-body row g-3">
                @foreach([
                    'has_gym' => '健身房',
                    'has_pool' => '游泳池',
                    'has_balcony' => '阳台',
                    'has_elevator' => '电梯',
                    'has_dishwasher' => '洗碗机',
                    'has_fridge' => '冰箱',
                    'has_stove' => '炉灶',
                    'has_microwave' => '微波炉',
                    'has_air_conditioning' => '空调'
                ] as $field => $label)
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-check form-switch ms-2">
                            <input type="checkbox" class="form-check-input" name="{{ $field }}" value="1" id="{{ $field }}"
                                @checked(old($field, $property->amenity->$field ?? false))>
                            <label class="form-check-label ms-2" for="{{ $field }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- 出租信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">出租信息</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">出租状态 *</label>
                    <select name="availability_status" class="form-select select-filter" required>
                        @foreach(['Available','Leased','Under Maintenance'] as $status)
                            <option value="{{ $status }}" @selected(old('availability_status', $property->rentalInfo->availability_status ?? '') == $status)> {{ $status }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">月租金 ($) *</label>
                    <input type="number" name="monthly_rent" class="form-control" required step="0.01" min="0" value="{{ old('monthly_rent', $property->rentalInfo->monthly_rent ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">押金 ($)</label>
                    <input type="number" name="security_deposit" class="form-control" step="0.01" min="0" value="{{ old('security_deposit', $property->rentalInfo->security_deposit ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">租期类型 *</label>
                    <select name="lease_term_type" class="form-select select-filter" required>
                        @foreach(['Monthly','Fixed Term','Annual'] as $type)
                            <option value="{{ $type }}" @selected(old('lease_term_type', $property->rentalInfo->lease_term_type ?? '') == $type)> {{ $type }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">最短租期（月）</label>
                    <input type="number" name="min_lease_term" class="form-control" min="1" value="{{ old('min_lease_term', $property->rentalInfo->min_lease_term ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">可入住日期</label>
                    <input type="date" name="available_date" class="form-control" value="{{ old('available_date', $property->rentalInfo->available_date ?? '') }}">
                </div>

                <div class="col-md-12">
    <label class="form-label d-block mb-1">包含水电费项目</label>
    <div class="utility-checkbox-group">
        @foreach(['Water','Electricity','Gas','Internet','Cable'] as $item)
            <div class="form-check">
                <input type="checkbox" class="form-check-input"
                       name="utilities_included[]"
                       value="{{ $item }}"
                       id="util_{{ $item }}"
                       @checked(collect(old('utilities_included', explode(',', $property->rentalInfo->utilities_included ?? '')))->contains($item))>
                <label class="form-check-label" for="util_{{ $item }}">{{ $item }}</label>
            </div>
        @endforeach
    </div>
</div>


                <div class="col-md-3">
                    <label class="form-label">宠物政策 *</label>
                    <select name="pet_policy" class="form-select select-filter" required>
                        @foreach(['Allowed','Restricted','Not Allowed'] as $policy)
                            <option value="{{ $policy }}" @selected(old('pet_policy', $property->rentalInfo->pet_policy ?? '') == $policy)> {{ $policy }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">宠物附加费 ($)</label>
                    <input type="number" name="pet_fee" class="form-control" step="0.01" min="0" value="{{ old('pet_fee', $property->rentalInfo->pet_fee ?? '') }}">
                </div>
            </div>
        </div>

        <!-- 财务信息 -->
        <div class="card mb-4">
          <div class="card-header fw-bold">财务信息</div>
          <div class="card-body row g-3">
            <div class="col-md-4">
              <label class="form-label">管理费比例 (%)</label>
              <input type="number" name="management_fee_percentage" class="form-control" step="0.01" min="0" max="100" value="{{ old('management_fee_percentage', $property->financialInfo->management_fee_percentage ?? '') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">年物业税 ($)</label>
              <input type="number" name="annual_property_tax" class="form-control" step="0.01" min="0" value="{{ old('annual_property_tax', $property->financialInfo->annual_property_tax ?? '') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">维修基金 ($)</label>
              <input type="number" name="maintenance_fund" class="form-control" step="0.01" min="0" value="{{ old('maintenance_fund', $property->financialInfo->maintenance_fund ?? '') }}">
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="form-check form-switch ms-2">
                    <input type="checkbox" class="form-check-input" name="hst_included" value="1" id="hst_included"
                        @checked(old('hst_included', $property->financialInfo->hst_included ?? false))>
                    <label class="form-check-label ms-2" for="hst_included">租金已含 HST（销售税）</label>
                </div>
            </div>

            
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header fw-bold">合规信息</div>
          <div class="card-body row g-3">
            <div class="col-md-4">
              <label class="form-label">物业税号</label>
              <input type="text" name="property_tax_id" class="form-control" maxlength="50" value="{{ old('property_tax_id', $property->complianceInfo->property_tax_id ?? '') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">租赁许可证编号</label>
              <input type="text" name="rental_license_number" class="form-control" maxlength="50" value="{{ old('rental_license_number', $property->complianceInfo->rental_license_number ?? '') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">保险单号</label>
              <input type="text" name="insurance_policy_number" class="form-control" maxlength="50" value="{{ old('insurance_policy_number', $property->complianceInfo->insurance_policy_number ?? '') }}">
            </div>

            <div class="col-md-4 d-flex align-items-center">
                <div class="form-check form-switch ms-2">
                    <input type="checkbox" class="form-check-input" name="fire_safety_compliance" value="1" id="fire_safety_compliance" @checked(old('fire_safety_compliance', $property->complianceInfo->fire_safety_compliance ?? false))>
                    <label class="form-check-label" for="fire_safety_compliance">通过消防合规检查</label>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="form-check form-switch ms-2">
                     <input type="checkbox" class="form-check-input" name="accessibility_compliance" value="1" id="accessibility_compliance" @checked(old('accessibility_compliance', $property->complianceInfo->accessibility_compliance ?? false))>
                    <label class="form-check-label" for="accessibility_compliance">符合无障碍标准</label>
                </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">最近检查日期</label>
              <input type="date" name="last_inspection_date" class="form-control" value="{{ old('last_inspection_date', $property->complianceInfo->last_inspection_date ?? '') }}">
            </div>
          </div>
        </div>

        <!-- Dropzone 上传模块 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">房源图片 / 视频上传</div>
            <div class="card-body">
                <div class="dropzone" id="property-dropzone"></div>
                <input type="hidden" name="cover_media" id="cover_media" value="{{ old('cover_media', $property->media->firstWhere('is_cover', 1)?->file_name ?? '') }}">
                <div id="hidden_inputs">
                    @if(isset($property))
                        @foreach ($property->media as $media)
                            <input type="hidden" name="uploaded_files[]" value="{{ $media->file_path }}" data-file-name="{{ basename($media->file_path) }}">
                            <input type="hidden" name="existing_files[]" value="{{ $media->file_path }}">
                        @endforeach
                    @endif
                </div>
                <div id="media_order_inputs"></div>
                <small class="text-muted d-block mt-2">点击上传或拖拽文件，最多上传 20 个。点击“设为封面”按钮选择封面图。</small>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 fs-7">{{ isset($property) ? '更新房源' : '保存房源' }}</button>
            <a href="{{ route('properties.index') }}" class="btn btn-secondary ms-2 px-4 py-2">取消</a>
        </div>

    </div>
</form>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
if (Dropzone.instances.length > 0) {
  Dropzone.instances.forEach(dz => dz.destroy());
}
Dropzone.autoDiscover = false;

const coverInput = document.getElementById('cover_media');
const dropzone = new Dropzone("#property-dropzone", {
    url: "{{ route('media.tempUpload') }}",
    maxFilesize: 50,
    maxFiles: 20,
    acceptedFiles: 'image/*,video/*',
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    init: function () {
        const self = this;
        @if(isset($property) && $property->media)
            const files = {!! json_encode($property->media->map(function($media) use ($property) {
                return [
                    'name' => basename($media->file_path),
                    'size' => Storage::disk('private')->exists($media->file_path)
                        ? Storage::disk('private')->size($media->file_path)
                        : 123456,
                    'url' => url("/media/property/" . $property->property_id . "/" . basename($media->file_path)),
                    'is_cover' => basename($media->file_path) === old('cover_media', $property->media->firstWhere('is_cover', 1)?->file_path)
                ];
            })) !!};

            files.forEach(function(f) {
                const mock = { name: f.name, size: f.size, accepted: true };
                self.emit("addedfile", mock);
                self.emit("thumbnail", mock, f.url);
                self.emit("complete", mock);
                mock.previewElement.setAttribute('data-file-name', f.name);

                setTimeout(() => {
                if (mock.previewElement) {
                    const img = mock.previewElement.querySelector('img');
                    if (img) {
                    img.style.objectFit = 'contain'; // 显示完整图像
                    img.style.maxHeight = '160px';   // 可根据你 Dropzone 的实际样式调整
                    img.style.width = 'auto';
                    img.style.height = 'auto';
                    img.style.margin = '0 auto';
                    }
                }
                }, 10);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'uploaded_files[]';
                input.value = "property_media/{{ $property->property_id }}/" + f.name;
                document.getElementById('hidden_inputs').appendChild(input);

                const keep = document.createElement('input');
                keep.type = 'hidden';
                keep.name = 'existing_files[]';
                keep.value = "property_media/{{ $property->property_id }}/" + f.name;
                document.getElementById('hidden_inputs').appendChild(keep);

                const toggle = document.createElement('a');
                toggle.innerHTML = '设为封面';
                toggle.classList.add('cover-toggle');
                toggle.onclick = function () {
                    document.querySelectorAll('.dz-preview').forEach(p => p.classList.remove('cover'));
                    mock.previewElement.classList.add('cover');
                    coverInput.value = f.name;
                };
                mock.previewElement.appendChild(toggle);

                if (f.is_cover) {
                    mock.previewElement.classList.add('cover');
                    coverInput.value = f.name;
                }
            });
        @endif
    },
    success: function (file, response) {
        console.log(file, response)
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'uploaded_files[]';
        input.value = response.path;
        document.getElementById('hidden_inputs').appendChild(input);
        file.upload.filename = response.name;
        file.previewElement.setAttribute('data-file-name', response.name); // ✅ 追加

        const toggle = document.createElement('a');
        toggle.innerHTML = '设为封面';
        toggle.classList.add('cover-toggle');
        toggle.onclick = function () {
            document.querySelectorAll('.dz-preview').forEach(p => p.classList.remove('cover'));
            file.previewElement.classList.add('cover');
            coverInput.value = response.id;
        };
        file.previewElement.appendChild(toggle);

        updateMediaOrderInputs();
    },
    removedfile: function (file) {
        const name = file.upload?.filename || file.name;
        const inputs = document.querySelectorAll(`input[value$='${name}']`);
        inputs.forEach(i => i.remove());
        if (coverInput.value === name) coverInput.value = '';
        file.previewElement.remove();
    }
});

// 初始化 SortableJS 用于排序
new Sortable(document.querySelector("#property-dropzone"), {
    animation: 150,
    onEnd: function () {
        updateMediaOrderInputs();
    }
});

function updateMediaOrderInputs() {
    
    const container = document.getElementById('media_order_inputs');
    container.innerHTML = '';

    const items = document.querySelectorAll('#property-dropzone .dz-preview');
    console.log(items);
    items.forEach(item => {
        const filename = item.getAttribute('data-file-name'); // ✅ 从 preview 取更稳
        if (filename) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'media_order[]';
            input.value = filename;
            container.appendChild(input);
        }
    });
    console.log(container);
}

// 上传完成也更新一下排序字段
if (typeof dropzone !== 'undefined') {
    
    dropzone.on("success", function () {
        //alert("eee");
        
    });
}
</script>
@endpush
