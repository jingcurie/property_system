@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- 上方统计卡片 --}}
    <div class="row g-4 mb-4">
        @foreach ($statCards as $card)
            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ $card['title'] }}</div>
                            <div class="fs-4 fw-bold">{{ $card['value'] }}</div>
                        </div>
                        <div class="bg-{{ $card['color'] }} bg-opacity-10 text-{{ $card['color'] }} rounded-circle p-3">
                            <i class="bi bi-{{ $card['icon'] }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 图表区域 --}}
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">30天租赁请求趋势</div>
                <div class="card-body">
                    <canvas id="rentalTrendChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">房源状态分布</div>
                <div class="card-body">
                    <canvas id="propertyStatusChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 事务流动区 --}}
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">最新租赁请求</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>申请人</th><th>时间</th><th>状态</th></tr></thead>
                        <tbody>
                            @foreach($latestApplications as $app)
                                <tr>
                                    <td>{{ $app['name'] }}</td>
                                    <td>{{ $app['date'] }}</td>
                                    <td><span class="badge bg-{{ $app['status_color'] }}">{{ $app['status'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">租赁即将到期</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>房源</th><th>租户</th><th>到期日</th></tr></thead>
                        <tbody>
                            @foreach($expiringLeases as $lease)
                                <tr>
                                    <td>{{ $lease['property'] }}</td>
                                    <td>{{ $lease['tenant'] }}</td>
                                    <td>{{ $lease['due'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 保维与通知区 --}}
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">房源维修状态</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($maintenanceProperties as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $item['property'] }}</span>
                                <span class="text-muted">{{ $item['status'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">最新通知</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($notifications as $note)
                            <li class="mb-2">
                                <i class="bi bi-dot text-primary"></i>
                                <strong>{{ $note['title'] }}</strong>
                                <div class="text-muted small">{{ $note['time'] }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('rentalTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($rentalTrend)) !!},
            datasets: [{
                label: '申请量',
                data: {!! json_encode(array_values($rentalTrend)) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('propertyStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($propertyStatus->keys()) !!},
            datasets: [{
                data: {!! json_encode($propertyStatus->values()) !!},
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
            }]
        }
    });
</script>
@endpush