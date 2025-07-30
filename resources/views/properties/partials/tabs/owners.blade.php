{{-- 房东信息卡片 --}}
<div class="card">
    <div class="card-header fw-bold">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill me-3 text-primary fs-4"></i>
                <h4 class="mb-0 fw-bold text-dark">房东信息管理
                    {{-- <span class="owner-count-badge"> --}}
                {{-- <i class="bi bi-person-check me-1"></i>
                共 {{ $property->owners->count() }} 位房东
            </span> --}}
                </h4>
            </div>
            
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOwnerModal">
                    <i class="bi bi-plus-circle me-2"></i>添加房东
                </button>
        </div>
    </div>
    <div class="card-body p-4">
        @if ($property->owners && $property->owners->count() > 0)
            <div class="row g-4">
                @foreach ($property->owners as $index => $owner)
                    <div class="col-lg-6">
                        <div class="owner-card h-100 position-relative">
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
                                    <i class="bi bi-telephone info-icon"></i>
                                    <span class="info-label">紧急电话</span>
                                    <span class="info-value">{{ $owner->emergency_contact_phone ?? '—' }}</span>
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

<!-- 新增房东 Modal -->
<div class="modal fade" id="addOwnerModal" tabindex="-1" aria-labelledby="addOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addOwnerForm" method="POST">
                @csrf
                @method('POST') <!-- 默认新增；JS 中改为 PUT -->
                <input type="hidden" name="owner_id" id="owner_id">
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

                    <input type="hidden" name="property_id" value="{{ $property->property_id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveOwner()">保存</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
