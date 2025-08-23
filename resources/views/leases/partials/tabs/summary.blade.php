<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>
            {{ ut('modules.lease.page_title') }} #{{ $lease->lease_number ?? $lease->lease_id }}
        </h4>
        <div>
            <a href="{{ route('leases.edit', $lease->lease_id) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-pencil-square"></i> {{ ut('modules.lease.actions.edit') }}
            </a>

            <a href="{{ route('leases.generatePdf', $lease->lease_id) }}" class="btn btn-outline-danger" target="_blank"
                id="generate-pdf-btn-{{ $lease->lease_id }}" onclick="handleGeneratePdf(this, event)">
                <i class="bi bi-file-earmark-pdf"></i>
                <span class="btn-text">{{ ut('modules.lease.actions.generate_pdf') }}</span>
            </a>
            <form id="send-form-{{ $lease->lease_id }}">
                @csrf
                <button type="button" class="btn btn-primary" onclick="openDocusignModal({{ $lease->lease_id }})">
                    <span class="btn-text">发送签署合同</span>
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- 基本信息 -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">{{ ut('modules.lease.sections.basic_info') }}</div>
                <div class="card-body">
                    <p><strong>{{ ut('modules.lease.fields.lease_number') }}:</strong> {{ $lease->lease_number }}</p>
                    <p><strong>{{ ut('modules.lease.fields.start_date') }}:</strong> {{ $lease->start_date }}</p>
                    <p><strong>{{ ut('modules.lease.fields.end_date') }}:</strong> {{ $lease->end_date }}</p>
                    <p><strong>{{ ut('modules.lease.fields.status') }}:</strong>
                        <span class="badge bg-{{ $lease->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($lease->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- 租客信息 -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">{{ ut('modules.lease.sections.tenant_info') }}</div>
                <div class="card-body">
                    <p><strong>{{ ut('modules.lease.fields.tenant') }}:</strong> {{ $lease->tenant->name ?? '-' }}</p>
                    <p><strong>{{ ut('modules.lease.fields.phone') }}:</strong> {{ $lease->tenant->phone ?? '-' }}</p>
                    <p><strong>{{ ut('modules.lease.fields.email') }}:</strong> {{ $lease->tenant->email ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- 房源信息 -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">{{ ut('modules.lease.sections.property_info') }}</div>
                <div class="card-body">
                    <p><strong>{{ ut('modules.lease.fields.property') }}:</strong>
                        {{ $lease->property->property_name ?? '-' }}</p>
                    <p><strong>{{ ut('modules.lease.fields.address') }}:</strong>
                        {{ $lease->property->address_street ?? '-' }},
                        {{ $lease->property->address_city ?? '' }}
                        {{ $lease->property->address_province ?? '' }}
                    </p>
                    <p><strong>{{ ut('modules.lease.fields.monthly_rent') }}:</strong>
                        ${{ number_format($lease->monthly_rent, 2) }}
                    </p>
                </div>
            </div>
        </div>

      




        <a href="{{ route('leases.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
</div>
