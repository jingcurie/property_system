{{-- 租赁合同管理 --}}
<div class="card">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-text me-3 text-primary fs-4"></i>
            <h4 class="mb-0 fw-bold text-dark">租赁合同</h4>
        </div>
    </div>

    <div class="card-body p-4">
        {{-- 当前合同 --}}
        @if ($activeLeases->count())
            <h5 class="text-dark mb-4">当前合同</h5>
            <div class="row g-4">
                @foreach ($activeLeases as $lease)
                    <div class="col-lg-6">
                        <div class="card border-success shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-2">
                                    <i class="bi bi-file-text me-2"></i>
                                    合同编号：{{ $lease->lease_number ?? '未命名' }}
                                </h6>
                                <p class="mb-1"><strong>起止日期：</strong> {{ $lease->start_date }} ~ {{ $lease->end_date }}</p>
                                <p class="mb-1"><strong>租金金额：</strong> {{ $lease->rent_amount ?? '—' }}</p>
                                <p class="mb-2"><strong>状态：</strong>
                                    <span class="badge bg-success-subtle text-success">进行中</span>
                                </p>
                                @if ($lease->notes)
                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ $lease->notes }}
                                    </p>
                                @endif
                                <a href="{{ route('leases.show', $lease->lease_id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> 查看
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">暂无当前合同</p>
        @endif

        {{-- 历史合同 --}}
        @if ($expiredLeases->count())
            <hr class="my-5">
            <h5 class="text-dark mb-4">历史合同</h5>
            <div class="card table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>合同编号</th>
                            <th>起止日期</th>
                            <th>租金金额</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expiredLeases as $lease)
                            <tr>
                                <td>{{ $lease->lease_number ?? '未命名' }}</td>
                                <td>{{ $lease->start_date }} ~ {{ $lease->end_date }}</td>
                                <td>{{ $lease->rent_amount ?? '—' }}</td>
                                <td>{{ $lease->notes ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('leases.show', $lease->lease_id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> 查看
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
