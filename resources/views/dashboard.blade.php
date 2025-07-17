@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb with improved styling -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="text-muted">
                <i class="bi bi-calendar3"></i> {{ date('M d, Y') }}
            </div>
        </div>
        
        {{-- Enhanced Statistics Cards --}}
        <div class="row g-4 mb-4">
            @foreach ($statCards as $card)
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 card-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-uppercase text-muted fw-semibold small mb-1">{{ $card['title'] }}</div>
                                    <div class="h4 fw-bold mb-0">{{ $card['value'] }}</div>
                                    @if(isset($card['change']))
                                        <div class="small text-{{ $card['change'] > 0 ? 'success' : 'danger' }}">
                                            <i class="bi bi-arrow-{{ $card['change'] > 0 ? 'up' : 'down' }}"></i>
                                            {{ abs($card['change']) }}%
                                        </div>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <div class="icon-shape bg-{{ $card['color'] }} bg-gradient text-white rounded-3">
                                        <i class="bi bi-{{ $card['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Enhanced Charts Section --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">30-Day Rental Request Trends</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Export Data</a></li>
                                    <li><a class="dropdown-item" href="#">View Details</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="rentalTrendChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="card-title mb-0">Property Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="propertyStatusChart" height="180"></canvas>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                    <span class="small">Available</span>
                                </div>
                                <span class="badge bg-success">{{ $propertyStatus['Available'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                    <span class="small">Occupied</span>
                                </div>
                                <span class="badge bg-warning">{{ $propertyStatus['Occupied'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                    <span class="small">Maintenance</span>
                                </div>
                                <span class="badge bg-danger">{{ $propertyStatus['Maintenance'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Data Tables --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Latest Rental Applications</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-3">Applicant</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestApplications as $app)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white me-2">
                                                        {{ substr($app['name'], 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $app['name'] }}</div>
                                                        <div class="text-muted small">{{ $app['email'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small">{{ $app['date'] }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $app['status_color'] }} bg-gradient">{{ $app['status'] }}</span>
                                            </td>
                                            <td class="pe-3">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">View Details</a></li>
                                                        <li><a class="dropdown-item" href="#">Approve</a></li>
                                                        <li><a class="dropdown-item text-danger" href="#">Reject</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Expiring Leases</h5>
                            <a href="#" class="btn btn-sm btn-outline-warning">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-3">Property</th>
                                        <th class="border-0">Tenant</th>
                                        <th class="border-0">Due Date</th>
                                        <th class="border-0 pe-3">Days Left</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expiringLeases as $lease)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-semibold">{{ $lease['property'] }}</div>
                                                <div class="text-muted small">{{ $lease['address'] ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $lease['tenant'] }}</div>
                                            </td>
                                            <td>
                                                <div class="text-muted small">{{ $lease['due'] }}</div>
                                            </td>
                                            <td class="pe-3">
                                                @php
                                                    $daysLeft = $lease['days_left'] ?? 0;
                                                    $badgeColor = $daysLeft <= 7 ? 'danger' : ($daysLeft <= 30 ? 'warning' : 'success');
                                                @endphp
                                                <span class="badge bg-{{ $badgeColor }}">{{ $daysLeft }} days</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Maintenance & Notifications --}}
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="card-title mb-0">Property Maintenance Status</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach ($maintenanceProperties as $item)
                                <div class="list-group-item border-0 px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item['property'] }}</h6>
                                            <p class="text-muted mb-1 small">{{ $item['issue'] ?? 'General maintenance' }}</p>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-muted me-1"></i>
                                                <span class="text-muted small">{{ $item['reported_date'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            @php
                                                $statusColor = match($item['status']) {
                                                    'Completed' => 'success',
                                                    'In Progress' => 'warning',
                                                    'Pending' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ $item['status'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="card-title mb-0">Recent Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach ($notifications as $note)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-marker bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-{{ $note['icon'] ?? 'bell' }} text-white small"></i>
                                        </div>
                                        <div class="timeline-content flex-grow-1">
                                            <h6 class="mb-1">{{ $note['title'] }}</h6>
                                            <p class="text-muted mb-1 small">{{ $note['message'] ?? 'No additional details' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">
                                                    <i class="bi bi-clock me-1"></i>{{ $note['time'] }}
                                                </span>
                                                @if($note['urgent'] ?? false)
                                                    <span class="badge bg-danger badge-sm">Urgent</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .icon-shape {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
        
        .timeline-marker {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }
        
        .timeline-item:not(:last-child) .timeline-marker::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 100%;
            width: 2px;
            height: 20px;
            background: #dee2e6;
        }
        
        .timeline-marker {
            position: relative;
        }
        
        .badge-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .text-gray-800 {
            color: #374151 !important;
        }
        
        .table-responsive {
            border-radius: 0.375rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Enhanced Rental Trend Chart
        const rentalTrendCtx = document.getElementById('rentalTrendChart');
        new Chart(rentalTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($rentalTrend)) !!},
                datasets: [{
                    label: 'Applications',
                    data: {!! json_encode(array_values($rentalTrend)) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        border: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Enhanced Property Status Chart
        const propertyStatusCtx = document.getElementById('propertyStatusChart');
        new Chart(propertyStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($propertyStatus->keys()) !!},
                datasets: [{
                    data: {!! json_encode($propertyStatus->values()) !!},
                    backgroundColor: [
                        '#10b981', // Success/Available
                        '#f59e0b', // Warning/Occupied
                        '#ef4444'  // Danger/Maintenance
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endpush