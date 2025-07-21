@php
    $feature = $property->feature;
    $amenity = $property->amenity;
    $rental = $property->rentalInfo;
    $financial = $property->financialInfo;
    $compliance = $property->complianceInfo;
    $utilities = collect(explode(',', $rental->utilities_included ?? ''));

    use Illuminate\Support\Str;
@endphp

<!-- 顶部基本信息 + 轮播图 -->
<div class="row mb-4">
    <div class="col-md-6">
        @if ($property->media->count())
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php
                        $coverShown = false;
                    @endphp
                    @foreach ($property->media as $index => $media)
                        @if (Str::endsWith($media->getAttribute('file_path'), ['.mp4', '.mov', '.webm']))
                            <div class="carousel-item {{ !$coverShown ? 'active' : '' }}">
                                <video controls class="d-block"
                                    style="max-height: 400px; object-fit: contain; margin: 0 auto;">
                                    <source
                                        src="{{ url('/media/property/' . Str::after($media->file_path, 'property_media/')) }}">
                                </video>
                            </div>
                            @php $coverShown = true; @endphp
                        @endif
                    @endforeach
                    @foreach ($property->media as $index => $media)
                        @if (!Str::endsWith($media->getAttribute('file_path'), ['.mp4', '.mov', '.webm']))
                            <div class="carousel-item {{ !$coverShown ? 'active' : '' }}">
                                <img src="{{ url('/media/property/' . Str::after($media->file_path, 'property_media/')) }}"
                                    class="d-block" style="max-height: 400px; object-fit: contain; margin: 0 auto;">
                            </div>
                            @php $coverShown = true; @endphp
                        @endif
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        @else
            <div class="bg-light d-flex justify-content-center align-items-center" style="height:300px;">
                <span class="text-muted">无封面图</span>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h4 class="fw-bold">{{ $property->property_name }}</h4>
        <p class="text-muted">{{ $property->address_street }}, {{ $property->address_city }},
            {{ $property->address_province }} {{ $property->address_postal_code }}</p>
        <p>类型：{{ $property->property_type }}</p>
        <p>业权类型：{{ $property->ownership_type }}</p>
        <p>建造年份：{{ $property->year_built ?: '—' }}</p>
        <p>租金：${{ number_format($rental->monthly_rent, 2) }} / 月</p>
        <p>押金：${{ number_format($rental->security_deposit, 2) }}</p>
        <p>状态：<span class="badge bg-success">{{ $rental->availability_status }}</span></p>
        {{-- <p>房东：{{ optional(optional($property->ownership)->owner)->full_name ?? '未指定' }}</p> --}}
    </div>
</div>

<!-- 房屋特征 -->
<!-- 房屋特征 -->
<div class="card mb-4">
    <div class="card-header fw-bold">房屋特征</div>
    <div class="card-body row g-3">
        <div class="col-md-3">卧室数：{{ $feature->bedrooms }}</div>
        <div class="col-md-3">卫浴数：{{ $feature->bathrooms }}</div>
        <div class="col-md-3">面积：{{ $feature->square_footage }} 平方英尺</div>
        <div class="col-md-3">停车位：{{ $feature->parking_spaces }}</div>
        <div class="col-md-3">停车类型：{{ $feature->parking_type }}</div>
        <div class="col-md-3">供暖类型：{{ $feature->heating_type }}</div>
        <div class="col-md-3">制冷类型：{{ $feature->cooling_type }}</div>
        <div class="col-md-3">带家具：<span
                class="badge bg-{{ $feature->furnished ? 'success' : 'secondary' }}">{{ $feature->furnished ? '是' : '否' }}</span>
        </div>
        <div class="col-md-3">洗衣方式：{{ $feature->laundry }}</div>
    </div>
</div>

<!-- 配套设施 -->
<div class="card mb-4">
    <div class="card-header fw-bold">配套设施</div>
    <div class="card-body row g-3">
        @foreach ([
        'has_gym' => '健身房',
        'has_pool' => '游泳池',
        'has_balcony' => '阳台',
        'has_elevator' => '电梯',
        'has_dishwasher' => '洗碗机',
        'has_fridge' => '冰箱',
        'has_stove' => '炉灶',
        'has_microwave' => '微波炉',
        'has_air_conditioning' => '空调',
    ] as $key => $label)
            <div class="col-md-3">
                <span class="badge bg-{{ $amenity->$key ? 'success' : 'secondary' }}">{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>

<!-- 出租信息 -->
<div class="card mb-4">
    <div class="card-header fw-bold">出租信息</div>
    <div class="card-body row g-3">
        <div class="col-md-3">租金：${{ number_format($rental->monthly_rent, 2) }}</div>
        <div class="col-md-3">押金：${{ number_format($rental->security_deposit, 2) }}</div>
        <div class="col-md-3">状态：<span class="badge bg-primary">{{ $rental->availability_status }}</span></div>
        <div class="col-md-3">租期：{{ $rental->lease_term_type }}</div>
        <div class="col-md-3">最短租期：{{ $rental->min_lease_term }} 月</div>
        <div class="col-md-3">可入住：{{ $rental->available_date }}</div>
        <div class="col-md-3">宠物政策：{{ $rental->pet_policy }}</div>
        <div class="col-md-3">宠物附加费：${{ number_format($rental->pet_fee, 2) }}</div>
        <div class="col-md-12">包含水电：
            @foreach (['Water', 'Electricity', 'Gas', 'Internet', 'Cable'] as $item)
                @if ($utilities->contains($item))
                    <span class="badge bg-info text-dark me-1">{{ $item }}</span>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- 财务信息 -->
{{-- <div class="card mb-4">
  <div class="card-header fw-bold">财务信息</div>
  <div class="card-body row g-3">
    <div class="col-md-3">管理费比例：{{ $financial->management_fee_percentage }}%</div>
    <div class="col-md-3">年物业税：${{ number_format($financial->annual_property_tax, 2) }}</div>
    <div class="col-md-3">维修基金：${{ number_format($financial->maintenance_fund, 2) }}</div>
    <div class="col-md-3">已含HST：<span class="badge bg-{{ $financial->hst_included ? 'success' : 'secondary' }}">{{ $financial->hst_included ? '是' : '否' }}</span></div>
  </div>
</div> --}}

<!-- 合规信息 -->
<div class="card mb-4">
    <div class="card-header fw-bold">合规信息</div>
    <div class="card-body row g-3">
        <div class="col-md-4">物业税号：{{ $compliance->property_tax_id }}</div>
        <div class="col-md-4">租赁许可证编号：{{ $compliance->rental_license_number }}</div>
        <div class="col-md-4">保险单号：{{ $compliance->insurance_policy_number }}</div>
        <div class="col-md-4">消防合规：<span
                class="badge bg-{{ $compliance->fire_safety_compliance ? 'success' : 'secondary' }}">{{ $compliance->fire_safety_compliance ? '是' : '否' }}</span>
        </div>
        <div class="col-md-4">无障碍合规：<span
                class="badge bg-{{ $compliance->accessibility_compliance ? 'success' : 'secondary' }}">{{ $compliance->accessibility_compliance ? '是' : '否' }}</span>
        </div>
        <div class="col-md-4">最近检查日期：{{ $compliance->last_inspection_date }}</div>
    </div>
</div>

{{-- 房东信息区域 --}}
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOwnerModal">
    <i class="bi bi-plus-circle"></i> 新增业主
</button>

@if ($property->owners && $property->owners->count() > 0)
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">房东信息</h5>
            <span class="text-muted small">共 {{ $property->owners->count() }} 位房东</span>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($property->owners as $owner)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border shadow-sm position-relative">
                            {{-- 右上角操作按钮 --}}
                            <div class="position-absolute top-0 end-0 m-2 dropdown">
                                <button class="btn-action-menu" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-owner='@json($owner)'>
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item btn-edit-owner"
                                            data-owner='@json($owner)' data-bs-toggle="modal"
                                            data-bs-target="#addOwnerModal">
                                            <i class="bi bi-pencil-square me-1"></i> 编辑
                                        </button>
                                    </li>
                                    <li>
                                        <form id="delete-owner-form-{{ $owner->owner_id }}"
                                            action="{{ route('owners.softDestroy', ['property' => $property->property_id, 'owner' => $owner->owner_id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="button"
                                                onclick="showConfirm('确认删除该房东吗？', () => document.getElementById('delete-owner-form-{{ $owner->owner_id }}').submit())">
                                                <i class="bi bi-trash me-1"></i> 删除
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-2">{{ $owner->full_name }}</h6>
                                <p class="mb-1"><strong>电话：</strong> {{ $owner->phone ?? '—' }}</p>
                                <p class="mb-1"><strong>邮箱：</strong> {{ $owner->email ?? '—' }}</p>
                                <p class="mb-1"><strong>紧急联系人：</strong> {{ $owner->emergency_contact ?? '—' }}</p>
                                <p class="mb-1"><strong>紧急联系人电话：</strong>
                                    {{ $owner->emergency_contact_phone ?? '—' }}</p>
                                <p class="mb-1"><strong>地址：</strong> {{ $owner->address ?? '—' }}</p>
                                <p class="mb-1"><strong>税号：</strong> {{ $owner->tax_id ?? '—' }}</p>
                                <p class="mb-1"><strong>备注：</strong> {{ $owner->notes ?? '—' }}</p>

                                @php
                                    $ownership = $owner->pivot ?? null;
                                @endphp
                                @if ($ownership)
                                    <hr>
                                    <p class="mb-1"><strong>持有比例：</strong> {{ $ownership->ownership_percentage }}%
                                    </p>
                                    <p class="mb-1"><strong>起始日期：</strong> {{ $ownership->start_date }}</p>
                                    <p class="mb-1"><strong>结束日期：</strong> {{ $ownership->end_date ?? '—' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        暂无房东信息，点击上方按钮添加第一个房东。
    </div>
@endif

<!-- 新增房东 Modal -->
<div class="modal fade" id="addOwnerModal" tabindex="-1" aria-labelledby="addOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addOwnerForm" method="POST">
                @csrf
                @method('POST') <!-- 默认新增；JS 中改为 PUT -->
                <input type="text" name="owner_id" id="owner_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOwnerModalLabel">新增业主</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">电话</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">邮箱</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">紧急联系人</label>
                        <input type="text" name="emergency_contact" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">联系电话</label>
                        <input type="text" name="emergency_contact_phone" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">地址</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">税号</label>
                        <input type="text" name="tax_id" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">备注</label>
                        <input type="text" name="notes" class="form-control">
                    </div>

                    <hr class="mt-4">

                    <div class="col-md-4">
                        <label class="form-label">拥有比例 (%)</label>
                        <input type="number" name="ownership_percentage" class="form-control" required
                            min="0" max="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">起始日期</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">结束日期</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>

                    <input type="text" name="property_id" value="{{ $property->property_id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveOwner()">保存</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- 房东信息卡片 --}}
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill me-3 text-primary fs-4"></i>
                <h4 class="mb-0 fw-bold text-dark">房东信息管理</h4>
            </div>
            <span class="owner-count-badge">
                <i class="bi bi-person-check me-1"></i>
                共 {{ $property->owners->count() }} 位房东
            </span>
        </div>
    </div>

    <div class="card-body p-4">
        @if ($property->owners && $property->owners->count() > 0)
            <div class="row g-4">
                @foreach ($property->owners as $index => $owner)
                    <div class="col-lg-6">
                        <div class="owner-card h-100">
                            <div class="card-body p-4">
                                {{-- 头部信息 --}}
                                <div class="d-flex align-items-start mb-3">
                                    <div class="owner-avatar"
                                        style="background: {{ $index % 2 == 0 ? 'linear-gradient(135deg, #2563eb, #3b82f6)' : 'linear-gradient(135deg, #dc2626, #ef4444)' }};">
                                        {{ mb_substr($owner->full_name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold mb-1 text-dark">{{ $owner->full_name }}</h5>
                                        @php
                                            $ownership = $owner->pivot ?? null;
                                            $percentage = $ownership ? $ownership->ownership_percentage : 0;
                                        @endphp
                                        <span
                                            class="badge {{ $percentage >= 50 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                            <i
                                                class="bi bi-{{ $percentage >= 50 ? 'check-circle' : 'star' }} me-1"></i>{{ $percentage >= 50 ? '主要业主' : '次要业主' }}
                                        </span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0 text-secondary" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="bi bi-pencil me-2"></i>编辑</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i
                                                        class="bi bi-trash me-2"></i>删除</a></li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- 联系信息 --}}
                                <div class="info-item">
                                    <i class="bi bi-telephone info-icon"></i>
                                    <span class="info-label">电话</span>
                                    <span class="info-value">{{ $owner->phone ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-envelope info-icon"></i>
                                    <span class="info-label">邮箱</span>
                                    <span class="info-value">{{ $owner->email ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-person-exclamation info-icon"></i>
                                    <span class="info-label">紧急联系人</span>
                                    <span class="info-value">{{ $owner->emergency_contact ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-geo-alt info-icon"></i>
                                    <span class="info-label">地址</span>
                                    <span class="info-value">{{ $owner->address ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-file-text info-icon"></i>
                                    <span class="info-label">税号</span>
                                    <span class="info-value">{{ $owner->tax_id ?? '—' }}</span>
                                </div>

                                {{-- 所有权信息 --}}
                                @if ($ownership)
                                    <div class="ownership-section">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold text-dark">
                                                <i class="bi bi-diagram-3 me-2 text-primary"></i>所有权信息
                                            </h6>
                                            <span class="percentage-badge"
                                                style="background: {{ $percentage >= 50 ? 'linear-gradient(135deg, #059669, #10b981)' : 'linear-gradient(135deg, #d97706, #f59e0b)' }};">
                                                {{ $percentage }}%
                                            </span>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted">起始日期</small>
                                                <div class="fw-semibold">{{ $ownership->start_date ?? '—' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">结束日期</small>
                                                <div class="fw-semibold">{{ $ownership->end_date ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- 备注信息 --}}
                                @if ($owner->notes)
                                    <div class="mt-3 p-3 bg-light rounded-3">
                                        <small class="text-muted">
                                            <i class="bi bi-chat-quote me-1"></i>备注：
                                        </small>
                                        <div class="mt-1 text-dark">{{ $owner->notes }}</div>
                                    </div>
                                @endif

                                {{-- 操作按钮 --}}
                                <div class="position-absolute top-0 end-0 m-2 dropdown">
                                    <button class="btn-action-menu" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" data-owner='@json($owner)'>
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item btn-edit-owner"
                                                data-owner='@json($owner)' data-bs-toggle="modal"
                                                data-bs-target="#addOwnerModal">
                                                <i class="bi bi-pencil-square me-1"></i> 编辑
                                            </button>
                                        </li>
                                        <li>
                                            <form id="delete-owner-form-{{ $owner->owner_id }}"
                                                action="{{ route('owners.softDestroy', ['property' => $property->property_id, 'owner' => $owner->owner_id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="button"
                                                    onclick="showConfirm('确认删除该房东吗？', () => document.getElementById('delete-owner-form-{{ $owner->owner_id }}').submit())">
                                                    <i class="bi bi-trash me-1"></i> 删除
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- 空状态 --}}
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5 class="mt-3 mb-2">暂无房东信息</h5>
                <p class="mb-4">点击上方"新增业主"按钮添加第一个房东</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOwnerModal">
                    <i class="bi bi-plus-circle me-2"></i>添加第一个房东
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    function saveOwner() {
        const form = document.getElementById('addOwnerForm');
        const ownerId = document.getElementById('owner_id').value;
        const propertyId = document.querySelector('input[name=property_id]').value; // 或你传进来的变量
        const formData = new FormData(form);

        let url = '';
        let method = '';

        if (ownerId) {
            // 编辑模式
            url = `/properties/${propertyId}/owners/${ownerId}`;
            method = 'PUT';
        } else {
            // 新增模式
            url = `/properties/${propertyId}/owners`;
            method = 'POST';
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    // 'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                $('#addOwnerModal').modal('hide');
                // 可选：刷新页面 / 更新表格 / 关闭Modal
                location.reload(); // 或用Ajax更新表格
            })
            .catch(error => {
                console.error('Error:', error);
                alert('保存失败，请检查输入');
            });
    }
</script>

<script>
    // 默认恢复表单状态（新增模式）
    function resetOwnerModal() {
        $('#addOwnerForm').trigger("reset");
        $('#addOwnerModalLabel').text("新增业主");
        $('#addOwnerForm').attr("action", "{{ route('owners.store') }}");
        $('#addOwnerForm').find('input[name="_method"]').val('POST');
        $('#owner_id').val('');
    }

    // 点击“编辑”按钮时触发，传入 owner 对象
    function editOwner(owner) {
        resetOwnerModal(); // 清空旧数据
        $('#addOwnerModalLabel').text("编辑业主");

        // 打开模态框


        // 设置提交 URL 与 Method
        const updateUrl = `{{ url('owners') }}/${owner.owner_id}`; // 假设是 RESTful 路由
        $('#addOwnerForm').attr("action", updateUrl);
        $('#addOwnerForm').find('input[name="_method"]').val('PUT');
        console.log(owner);
        // 填充字段
        $('#addOwnerForm').find('input[name="first_name"]').val(owner.first_name);
        $('#addOwnerForm').find('input[name="last_name"]').val(owner.last_name);
        $('#addOwnerForm').find('input[name="phone"]').val(owner.phone);
        $('#addOwnerForm').find('input[name="email"]').val(owner.email);
        $('#addOwnerForm').find('input[name="emergency_contact"]').val(owner.emergency_contact);
        $('#addOwnerForm').find('input[name="emergency_contact_phone"]').val(owner.emergency_contact_phone);
        $('#addOwnerForm').find('input[name="address"]').val(owner.address);
        $('#addOwnerForm').find('input[name="tax_id"]').val(owner.tax_id);
        $('#addOwnerForm').find('input[name="notes"]').val(owner.notes);
        $('#addOwnerForm').find('input[name="ownership_percentage"]').val(owner.pivot.ownership_percentage);
        $('#addOwnerForm').find('input[name="start_date"]').val(owner.pivot.start_date);
        $('#addOwnerForm').find('input[name="end_date"]').val(owner.pivot.end_date);
        $('#owner_id').val(owner.owner_id);

        //    const modal = new bootstrap.Modal(document.getElementById('addOwnerModal'));
        modal.show();
    }

    // 示例绑定（根据你的结构调整）
    $(document).on('click', '.btn-edit-owner', function() {
        const owner = $(this).data('owner'); // 从 data-owner 属性取出对象
        editOwner(owner);
    });

    $(document).on('click', '[data-bs-target="#addOwnerModal"]', function() {
        // resetOwnerModal(); // 每次点击都重置表单
    });
</script>
