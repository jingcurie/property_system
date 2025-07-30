{{-- 房东信息卡片 --}}
<div class="card">
    <div class="card-header fw-bold">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill me-3 text-primary fs-4"></i>
                <h4 class="mb-0 fw-bold text-dark">房东信息管理</h4>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        @if ($lease->property && $lease->property->owners->count() > 0)
            <div class="row g-4">
                @foreach ($lease->property->owners as $index => $owner)
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
                                            <i class="bi bi-{{ $percentage >= 50 ? 'check-circle' : 'star' }} me-1"></i>{{ $percentage >= 50 ? '主要业主' : '次要业主' }}
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
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5 class="mt-3 mb-2">暂无房东信息</h5>
                <p class="mb-4">该租约关联的房源暂无房东信息</p>
            </div>
        @endif
    </div>
</div>
