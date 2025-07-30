{{-- 房客信息管理 --}}
<div class="card">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-people-fill me-3 text-primary fs-4"></i>
            <h4 class="mb-0 fw-bold text-dark">房客信息管理</h4>
        </div>
    </div>

    <div class="card-body p-4">
        {{-- 当前房客卡片区块 --}}
        @if ($activeLeasesTenants->count())
            <h5 class="text-dark mb-4">当前租客</h5>
            <div class="row g-4">
                @foreach ($activeLeasesTenants as $index => $tenantInfo)
                    @php
                        $lease = $tenantInfo->lease;
                        $pivot = $tenantInfo->pivot ?? null;
                        $share = $pivot->share_percentage ?? 0;
                        $isPrimary = $pivot->is_primary ?? false;
                    @endphp

                    <div class="col-lg-6">
                        <div class="owner-card h-100 position-relative">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="owner-avatar"
                                        style="background: {{ $index % 2 == 0 ? 'linear-gradient(135deg, #2563eb, #3b82f6)' : 'linear-gradient(135deg, #dc2626, #ef4444)' }};">
                                        {{ mb_substr($tenantInfo->first_name, 0, 1) . mb_substr($tenantInfo->last_name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fw-bold mb-1 text-dark">{{ $tenantInfo->first_name }} {{ $tenantInfo->last_name }}</h5>
                                        <span class="badge {{ $isPrimary ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                            <i class="bi bi-{{ $isPrimary ? 'check-circle' : 'person' }} me-1"></i>{{ $isPrimary ? '主租客' : '次租客' }}
                                        </span>
                                        <div class="small text-muted">合同截止：{{ $lease->end_date }}</div>
                                    </div>
                                </div>

                                {{-- 联系方式 --}}
                                <div class="info-item">
                                    <i class="bi bi-calendar2-day info-icon"></i>
                                    <span class="info-label">出身年月</span>
                                    <span class="info-value">{{ $tenantInfo->date_of_birth ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-telephone info-icon"></i>
                                    <span class="info-label">电话</span>
                                    <span class="info-value">{{ $tenantInfo->phone ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-envelope info-icon"></i>
                                    <span class="info-label">邮箱</span>
                                    <span class="info-value">{{ $tenantInfo->email ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-telephone-inbound info-icon"></i>
                                    <span class="info-label">紧急电话</span>
                                    <span class="info-value">{{ $tenantInfo->emergency_contact ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-briefcase info-icon"></i>
                                    <span class="info-label">职业</span>
                                    <span class="info-value">{{ $tenantInfo->occupation ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-bar-chart info-icon"></i>
                                    <span class="info-label">信用分数</span>
                                    <span class="info-value">{{ $tenantInfo->credit_score ?? '—' }}</span>
                                </div>

                                {{-- 分摊比例 --}}
                                <div class="ownership-section mt-3">
                                    <h6 class="fw-bold text-dark">
                                        <i class="bi bi-diagram-3 me-2 text-primary"></i>租赁分摊份额
                                    </h6>
                                    <span class="percentage-badge"
                                        style="background: {{ $share >= 50 ? 'linear-gradient(135deg, #059669, #10b981)' : 'linear-gradient(135deg, #d97706, #f59e0b)' }};">
                                        {{ $share }}%
                                    </span>
                                </div>

                                @if ($tenantInfo->notes)
                                    <div class="mt-3 p-3 bg-light rounded-3">
                                        <small class="text-muted">
                                            <i class="bi bi-chat-quote me-1"></i>备注：
                                        </small>
                                        <div class="mt-1 text-dark">{{ $tenantInfo->notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">暂无当前租客</p>
        @endif

        {{-- 历史房客列表 --}}
        @if ($expiredLeasesTenants->count())
            <hr class="my-5">
            <h5 class="text-dark mb-4">历史租客</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>姓名</th>
                            <th>电话</th>
                            <th>邮箱</th>
                            <th>合同时间</th>
                            <th>是否主租客</th>
                            <th>备注</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expiredLeasesTenants as $index => $tenantInfo)
                            @php
                                $tenant = $tenantInfo->tenant;
                                $lease = $tenantInfo->lease;
                                $pivot = $tenantInfo->pivot ?? null;
                            @endphp
                            <tr>
                                <td>{{ $tenantInfo->first_name }} {{ $tenantInfo->last_name }}</td>
                                <td>{{ $tenantInfo->phone ?? '—' }}</td>
                                <td>{{ $tenantInfo->email ?? '—' }}</td>
                                <td>{{ $tenantInfo->lease->start_date }} ~ {{ $tenantInfo->lease->end_date }}</td>
                                <td>{{ ($tenantInfo->pivot->is_primary) ? '是' : '否' }}</td>
                                <td>{{ $tenantInfo->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
