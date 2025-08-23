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

<form method="POST"
    action="{{ $property->exists ? route('properties.update', $property->property_id) : route('properties.store') }}"
    enctype="multipart/form-data">

    @csrf
    @if ($property->exists)
        @method('PUT')
    @endif

    <div class="container-fluid">

        <!-- 基础信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.basic_info') }}</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ ut('modules.property.property_name') }} *</label>
                    <input type="text" name="property_name" class="form-control" required maxlength="100"
                        value="{{ old('property_name', $property->property_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ ut('modules.property.property_type') }} *</label>
                    <select name="property_type" class="form-select select-filter" required>
                        <option value="">{{ ut('modules.property.select_property_type') }}</option>
                        @foreach (dict('property_type', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('property_type', $property->property_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ ut('modules.property.ownership_type') }} *</label>
                    <select name="ownership_type" class="form-select select-filter" required>
                        @foreach (dict('ownership_type', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('ownership_type', $property->ownership_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.year_built') }}</label>
                    <input type="number" name="year_built" class="form-control" min="1800"
                        max="{{ date('Y') }}" value="{{ old('year_built', $property->year_built ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{ ut('modules.property.street_address') }} *</label>
                    <input type="text" name="address_street" class="form-control" required
                        value="{{ old('address_street', $property->address_street ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.city') }} *</label>
                    <input type="text" name="address_city" class="form-control" required
                        value="{{ old('address_city', $property->address_city ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ ut('modules.property.province') }} *</label>
                    <select name="address_province" class="form-select select-filter" required>
                        <option value="">{{ ut('modules.property.select_province') }}</option>
                        @foreach (dict('provinces', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('provinces', $property->property_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.postal_code') }} *</label>
                    <input type="text" name="address_postal_code" class="form-control" required maxlength="10"
                        value="{{ old('address_postal_code', $property->address_postal_code ?? '') }}">
                </div>
            </div>
        </div>

        <!-- 房屋特征 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.property_features') }}</div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.bedrooms') }} *</label>
                    <input type="number" name="bedrooms" class="form-control" required min="0"
                        value="{{ old('bedrooms', $property->feature->bedrooms ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.bathrooms') }} *</label>
                    <input type="number" step="0.5" name="bathrooms" class="form-control" required min="0"
                        value="{{ old('bathrooms', $property->feature->bathrooms ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.square_footage') }}</label>
                    <input type="number" name="square_footage" class="form-control" min="0"
                        value="{{ old('square_footage', $property->feature->square_footage ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.parking_spaces') }}</label>
                    <input type="number" name="parking_spaces" class="form-control" min="0"
                        value="{{ old('parking_spaces', $property->feature->parking_spaces ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.parking_type') }}</label>
                    <select name="parking_type" class="form-select select-filter">
                        @foreach (dict('parking_type', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('parking_type', $property->feature->parking_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.heating_type') }}</label>
                    <input type="text" name="heating_type" class="form-control" maxlength="50"
                        value="{{ old('heating_type', $property->feature->heating_type ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.cooling_type') }}</label>
                    <input type="text" name="cooling_type" class="form-control" maxlength="50"
                        value="{{ old('cooling_type', $property->feature->cooling_type ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.laundry') }} *</label>
                    <select name="laundry" class="form-select select-filter" required>
                        @foreach (['In-unit', 'Shared', 'None'] as $option)
                            <option value="{{ $option }}" @selected(old('laundry', $property->feature->laundry ?? 'None') == $option)>
                                {{ ut('modules.property.laundry_options.' . $option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input" name="furnished" value="1"
                            id="furnished" @checked(old('furnished', $property->feature->furnished ?? false))>
                        <label class="form-check-label" for="furnished">{{ ut('modules.property.furnished') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 配套设施 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.amenities') }}</div>
            <div class="card-body row g-3">
                @foreach ([
        'has_gym' => 'has_gym',
        'has_pool' => 'has_pool',
        'has_balcony' => 'has_balcony',
        'has_elevator' => 'has_elevator',
        'has_dishwasher' => 'has_dishwasher',
        'has_fridge' => 'has_fridge',
        'has_stove' => 'has_stove',
        'has_microwave' => 'has_microwave',
        'has_air_conditioning' => 'has_air_conditioning',
    ] as $field => $label)
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-check form-switch ms-2">
                            <input type="checkbox" class="form-check-input" name="{{ $field }}"
                                value="1" id="{{ $field }}" @checked(old($field, $property->amenity->$field ?? false))>
                            <label class="form-check-label ms-2"
                                for="{{ $field }}">{{ __('property.' . $label) }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 出租信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.rental_info') }}</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.availability_status') }} *</label>
                    <select name="availability_status" class="form-select select-filter" required>
                        @foreach (dict('availability_status', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('availability_status', $property->rentalInfo->availability_status ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.monthly_rent') }} *</label>
                    <input type="number" name="monthly_rent" class="form-control" required step="0.01"
                        min="0" value="{{ old('monthly_rent', $property->rentalInfo->monthly_rent ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.security_deposit') }}</label>
                    <input type="number" name="security_deposit" class="form-control" step="0.01"
                        min="0"
                        value="{{ old('security_deposit', $property->rentalInfo->security_deposit ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.lease_term_type') }} *</label>
                    <select name="lease_term_type" class="form-select select-filter" required>
                        @foreach (dict('lease_term_type', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('lease_term_type', $property->rentalInfo->lease_term_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.min_lease_term') }}</label>
                    <input type="number" name="min_lease_term" class="form-control" min="1"
                        value="{{ old('min_lease_term', $property->rentalInfo->min_lease_term ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.available_date') }}</label>
                    <input type="date" name="available_date" class="form-control"
                        value="{{ old('available_date', $property->rentalInfo->available_date ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label d-block mb-1">{{ ut('modules.property.utilities_included') }}</label>
                    <div class="utility-checkbox-group">
                        @foreach (dict('utilities', app()->getLocale()) as $value => $label)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="utilities_included[]"
                                    value="{{ $value }}" id="util_{{ $value }}"
                                    @checked(collect(old('utilities_included', explode(',', $property->rentalInfo->utilities_included ?? '')))->contains($value))>
                                <label class="form-check-label"
                                    for="util_{{ $value }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.pet_policy') }} *</label>
                    <select name="pet_policy" class="form-select select-filter" required>
                        @foreach (dict('pet_policy', app()->getLocale()) as $value => $label)
                            <option value="{{ $value }}" @selected(old('pet_policy', $property->rentalInfo->pet_policy ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ ut('modules.property.pet_fee') }}</label>
                    <input type="number" name="pet_fee" class="form-control" step="0.01" min="0"
                        value="{{ old('pet_fee', $property->rentalInfo->pet_fee ?? '') }}">
                </div>
            </div>
        </div>

        <!-- 财务信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.financial_info') }}</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.management_fee_percentage') }}</label>
                    <input type="number" name="management_fee_percentage" class="form-control" step="0.01"
                        min="0" max="100"
                        value="{{ old('management_fee_percentage', $property->financialInfo->management_fee_percentage ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.annual_property_tax') }}</label>
                    <input type="number" name="annual_property_tax" class="form-control" step="0.01"
                        min="0"
                        value="{{ old('annual_property_tax', $property->financialInfo->annual_property_tax ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.maintenance_fund') }}</label>
                    <input type="number" name="maintenance_fund" class="form-control" step="0.01"
                        min="0"
                        value="{{ old('maintenance_fund', $property->financialInfo->maintenance_fund ?? '') }}">
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input" name="hst_included" value="1"
                            id="hst_included" @checked(old('hst_included', $property->financialInfo->hst_included ?? false))>
                        <label class="form-check-label ms-2"
                            for="hst_included">{{ ut('modules.property.hst_included') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 合规信息 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.compliance_info') }}</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.property_tax_id') }}</label>
                    <input type="text" name="property_tax_id" class="form-control" maxlength="50"
                        value="{{ old('property_tax_id', $property->complianceInfo->property_tax_id ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.rental_license_number') }}</label>
                    <input type="text" name="rental_license_number" class="form-control" maxlength="50"
                        value="{{ old('rental_license_number', $property->complianceInfo->rental_license_number ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.insurance_policy_number') }}</label>
                    <input type="text" name="insurance_policy_number" class="form-control" maxlength="50"
                        value="{{ old('insurance_policy_number', $property->complianceInfo->insurance_policy_number ?? '') }}">
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input" name="fire_safety_compliance" value="1"
                            id="fire_safety_compliance" @checked(old('fire_safety_compliance', $property->complianceInfo->fire_safety_compliance ?? false))>
                        <label class="form-check-label"
                            for="fire_safety_compliance">{{ ut('modules.property.fire_safety_compliance') }}</label>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check form-switch ms-2">
                        <input type="checkbox" class="form-check-input" name="accessibility_compliance"
                            value="1" id="accessibility_compliance" @checked(old('accessibility_compliance', $property->complianceInfo->accessibility_compliance ?? false))>
                        <label class="form-check-label"
                            for="accessibility_compliance">{{ ut('modules.property.accessibility_compliance') }}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ ut('modules.property.last_inspection_date') }}</label>
                    <input type="date" name="last_inspection_date" class="form-control"
                        value="{{ old('last_inspection_date', $property->complianceInfo->last_inspection_date ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Dropzone 上传模块 -->
        <div class="card mb-4">
            <div class="card-header fw-bold">{{ ut('modules.property.media_upload') }}</div>
            <div class="card-body">
                <div class="dropzone" id="property-dropzone"></div>
                <input type="hidden" name="cover_media" id="cover_media"
                    value="{{ old('cover_media', $property->media->firstWhere('is_cover', 1)?->file_name ?? '') }}">
                <div id="hidden_inputs">
                    @if (isset($property))
                        @foreach ($property->media as $media)
                            <input type="hidden" name="uploaded_files[]" value="{{ $media->file_path }}"
                                data-file-name="{{ basename($media->file_path) }}">
                            <input type="hidden" name="existing_files[]" value="{{ $media->file_path }}">
                        @endforeach
                    @endif
                </div>
                <div id="media_order_inputs"></div>
                <small class="text-muted d-block mt-2">{{ ut('modules.property.upload_hint') }}</small>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 fs-7">
                {{ isset($property) ? ut('modules.property.update_property') : ut('modules.property.save_property') }}
            </button>
            <a href="{{ route('properties.index') }}"
                class="btn btn-secondary ms-2 px-4 py-2">{{ ut('modules.property.cancel') }}</a>
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
            init: function() {
                const self = this;
                @if (isset($property) && $property->media)
                    const files = {!! json_encode(
                        $property->media->map(function ($media) use ($property) {
                            return [
                                'name' => basename($media->file_path),
                                'size' => Storage::disk('private')->exists($media->file_path)
                                    ? Storage::disk('private')->size($media->file_path)
                                    : 123456,
                                'url' => url('/media/property/' . $property->property_id . '/' . basename($media->file_path)),
                                'is_cover' =>
                                    basename($media->file_path) ===
                                    old('cover_media', $property->media->firstWhere('is_cover', 1)?->file_path),
                            ];
                        }),
                    ) !!};

                    files.forEach(function(f) {
                        const mock = {
                            name: f.name,
                            size: f.size,
                            accepted: true
                        };
                        self.emit("addedfile", mock);
                        self.emit("thumbnail", mock, f.url);
                        self.emit("complete", mock);
                        mock.previewElement.setAttribute('data-file-name', f.name);

                        setTimeout(() => {
                            if (mock.previewElement) {
                                const img = mock.previewElement.querySelector('img');
                                if (img) {
                                    img.style.objectFit = 'contain';
                                    img.style.maxHeight = '160px';
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
                        toggle.innerHTML = '{{ ut('modules.property.set_as_cover') }}';
                        toggle.classList.add('cover-toggle');
                        toggle.onclick = function() {
                            document.querySelectorAll('.dz-preview').forEach(p => p.classList
                                .remove('cover'));
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
            success: function(file, response) {
                console.log(file, response)
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'uploaded_files[]';
                input.value = response.path;
                document.getElementById('hidden_inputs').appendChild(input);
                file.upload.filename = response.name;
                file.previewElement.setAttribute('data-file-name', response.name);

                const toggle = document.createElement('a');
                toggle.innerHTML = '{{ ut('modules.property.set_as_cover') }}';
                toggle.classList.add('cover-toggle');
                toggle.onclick = function() {
                    document.querySelectorAll('.dz-preview').forEach(p => p.classList.remove('cover'));
                    file.previewElement.classList.add('cover');
                    coverInput.value = response.id;
                };
                file.previewElement.appendChild(toggle);

                updateMediaOrderInputs();
            },
            removedfile: function(file) {
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
            onEnd: function() {
                updateMediaOrderInputs();
            }
        });

        function updateMediaOrderInputs() {
            const container = document.getElementById('media_order_inputs');
            container.innerHTML = '';

            const items = document.querySelectorAll('#property-dropzone .dz-preview');
            console.log(items);
            items.forEach(item => {
                const filename = item.getAttribute('data-file-name');
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
            dropzone.on("success", function() {
                // 成功回调
            });
        }
    </script>
@endpush
