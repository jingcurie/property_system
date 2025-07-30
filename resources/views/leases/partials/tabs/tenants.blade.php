{{-- 房客信息卡片 --}}
<div class="card">
    <div class="card-header fw-bold">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill me-3 text-primary fs-4"></i>
                <h4 class="mb-0 fw-bold text-dark">房客信息管理</h4>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        @if ($lease->tenants && $lease->tenants->count() > 0)
            <div class="row g-4">
                @foreach ($lease->tenants as $index => $tenant)
                    <div class="col-lg-6">
                        <div class="owner-card h-100 position-relative">
                            <div class="card-body p-4">
                                {{-- 头部 --}}
                                <div class="d-flex align-items-start mb-3">
                                    <div class="owner-avatar"
                                        style="background: {{ $index % 2 == 0 ? 'linear-gradient(135deg, #2563eb, #3b82f6)' : 'linear-gradient(135deg, #dc2626, #ef4444)' }};">
                                        {{ mb_substr($tenant->first_name, 0, 1) . mb_substr($tenant->last_name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold mb-1 text-dark">{{ $tenant->first_name . " " .  $tenant->last_name}}</h5>
                                        @php
                                            $pivot = $tenant->pivot ?? null;
                                            $share = $pivot ? $pivot->share_percentage : 0;
                                            $isPrimary = $pivot && $pivot->is_primary;
                                        @endphp
                                        <span class="badge {{ $isPrimary ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                            <i class="bi bi-{{ $isPrimary ? 'check-circle' : 'person' }} me-1"></i>{{ $isPrimary ? '主租客' : '次租客' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- 联系信息 --}}
                                 <div class="info-item">
                                    <i class="bi bi-calendar2-day    info-icon"></i>
                                    <span class="info-label">出身年月</span>
                                    <span class="info-value">{{ $tenant->date_of_birth ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-telephone info-icon"></i>
                                    <span class="info-label">电话</span>
                                    <span class="info-value">{{ $tenant->phone ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-envelope info-icon"></i>
                                    <span class="info-label">邮箱</span>
                                    <span class="info-value">{{ $tenant->email ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-telephone-inbound info-icon"></i>
                                    <span class="info-label">紧急电话</span>
                                    <span class="info-value">{{ $tenant->emergency_contact ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-briefcase info-icon"></i>
                                    <span class="info-label">职业</span>
                                    <span class="info-value">{{ $tenant->occupation ?? '—' }}</span>
                                </div>

                                <div class="info-item">
                                    <i class="bi bi-bar-chart info-icon"></i>
                                    <span class="info-label">信用分数</span>
                                    <span class="info-value">{{ $tenant->credit_score ?? '—' }}</span>
                                </div>

                                {{-- 备注 --}}
                                @if ($tenant->notes)
                                    <div class="mt-3 p-3 bg-light rounded-3">
                                        <small class="text-muted">
                                            <i class="bi bi-chat-quote me-1"></i>备注：
                                        </small>
                                        <div class="mt-1 text-dark">{{ $tenant->notes }}</div>
                                    </div>
                                @endif

                                {{-- 所有权信息改成“租客份额” --}}
                                @if ($pivot)
                                    <div class="ownership-section">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold text-dark">
                                                <i class="bi bi-diagram-3 me-2 text-primary"></i>租赁分摊份额
                                            </h6>
                                            <span class="percentage-badge"
                                                style="background: {{ $share >= 50 ? 'linear-gradient(135deg, #059669, #10b981)' : 'linear-gradient(135deg, #d97706, #f59e0b)' }};">
                                                {{ $share }}%
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                {{-- 操作 --}}
                                <div class="position-absolute top-0 end-0 m-2 dropdown">
                                    <button class="btn-action-menu" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item btn-edit-tenant"
                                                data-tenant='@json($tenant)' data-bs-toggle="modal"
                                                data-bs-target="#editTenantModal">
                                                <i class="bi bi-pencil-square me-1"></i> 编辑
                                            </button>
                                        </li>
                                        <li>
                                            <form id="delete-tenant-form-{{ $tenant->tenant_id }}"
                                                {{-- action="{{ route('tenants.softDestroy', ['lease' => $lease->lease_id, 'tenant' => $tenant->tenant_id]) }}" --}}
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="button"
                                                    onclick="showConfirm('确认删除该房客吗？', () => document.getElementById('delete-tenant-form-{{ $tenant->tenant_id }}').submit())">
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
                <h5 class="mt-3 mb-2">暂无房客信息</h5>
                <p class="mb-4">点击上方"新增租客"按钮添加第一个房客</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTenantModal">
                    <i class="bi bi-plus-circle me-2"></i>添加第一个房客
                </button>
            </div>
        @endif
    </div>
</div>
