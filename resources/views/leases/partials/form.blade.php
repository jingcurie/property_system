<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
  @csrf
  @if($isEdit)
    @method('PUT')
  @endif

  {{-- Basic Info --}}
  <div class="card mb-4">
    <div class="card-header">Basic Information</div>
    <div class="card-body row g-3">
      <div class="col-md-4">
        <label class="form-label">Lease Number</label>
        <input type="text" name="lease_number" class="form-control" value="{{ old('lease_number', $lease->lease_number ?? '') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Version</label>
        <input type="text" name="version_number" class="form-control" value="{{ old('version_number', $lease->version_number ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Lease Type</label>
        <select name="lease_type" class="form-select">
          <option value="fixed_term" {{ old('lease_type', $lease->lease_type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Term</option>
          <option value="month_to_month" {{ old('lease_type', $lease->lease_type ?? '') == 'month_to_month' ? 'selected' : '' }}>Month-to-Month</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Property</label>
        <select name="property_id" class="form-select" required>
          @foreach($properties as $property)
            <option value="{{ $property->property_id }}" {{ old('property_id', $lease->property_id ?? '') == $property->property_id ? 'selected' : '' }}>
              {{ $property->address_street .  ", " . $property->address_city}}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Tenant</label>
        <select name="tenant_id" class="form-select" required>
          @foreach($tenants as $tenant)
            <option value="{{ $tenant->tenant_id }}" {{ old('tenant_id', $lease->tenant_id ?? '') == $tenant->tenant_id ? 'selected' : '' }}>
              {{ $tenant->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" {{ old('status', $lease->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="pending" {{ old('status', $lease->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="terminated" {{ old('status', $lease->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
        </select>
      </div>
    </div>
  </div>

  {{-- Term & Rent --}}
  <div class="card mb-4">
    <div class="card-header">Term & Rent</div>
    <div class="card-body row g-3">
      <div class="col-md-4">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($lease?->start_date)->format('Y-m-d') ?? '') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($lease?->end_date)->format('Y-m-d') ?? '') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Monthly Rent</label>
        <input type="number" step="0.01" name="monthly_rent" class="form-control" value="{{ old('monthly_rent', $lease->monthly_rent ?? '') }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Rent Due Day</label>
        <input type="number" name="rent_due_day" class="form-control" value="{{ old('rent_due_day', $lease->rent_due_day ?? '') }}">
      </div>
    </div>
  </div>

  {{-- Deposits & Fees --}}
  <div class="card mb-4">
    <div class="card-header">Deposits & Fees</div>
    <div class="card-body row g-3">
      <div class="col-md-4">
        <label class="form-label">Security Deposit</label>
        <input type="number" step="0.01" name="security_deposit" class="form-control" value="{{ old('security_deposit', $lease->security_deposit ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Furniture Deposit</label>
        <input type="number" step="0.01" name="furniture_deposit" class="form-control" value="{{ old('furniture_deposit', $lease->furniture_deposit ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Pet Deposit</label>
        <input type="number" step="0.01" name="pet_deposit" class="form-control" value="{{ old('pet_deposit', $lease->pet_deposit ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Cleaning Fee</label>
        <input type="number" step="0.01" name="cleaning_fee" class="form-control" value="{{ old('cleaning_fee', $lease->cleaning_fee ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Late Fee</label>
        <input type="number" step="0.01" name="late_fee_amount" class="form-control" value="{{ old('late_fee_amount', $lease->late_fee_amount ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">NSF Fee</label>
        <input type="number" step="0.01" name="nsf_fee" class="form-control" value="{{ old('nsf_fee', $lease->nsf_fee ?? '') }}">
      </div>
    </div>
  </div>

  {{-- Lease Terms --}}
  <div class="card mb-4">
    <div class="card-header">Lease Terms</div>
    <div class="card-body row g-3">
      @foreach([
        'pets_allowed' => 'Pets Allowed',
        'smoking_allowed' => 'Smoking Allowed',
        'subletting_allowed' => 'Subletting Allowed',
        'tenant_insurance_required' => 'Tenant Insurance Required',
        'insurance_required' => 'General Insurance Required',
        'furnished' => 'Furnished',
        'strata_acknowledged' => 'Strata Bylaws Acknowledged',
        'form_k_signed' => 'Form K Signed',
      ] as $field => $label)
        <div class="col-md-3 form-check">
          <input type="checkbox" name="{{ $field }}" class="form-check-input" id="{{ $field }}" {{ old($field, $lease->$field ?? false) ? 'checked' : '' }}>
          <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
        </div>
      @endforeach
      <div class="col-md-6">
        <label class="form-label">Minimum Coverage Amount</label>
        <input type="number" step="0.01" name="minimum_coverage_amount" class="form-control" value="{{ old('minimum_coverage_amount', $lease->minimum_coverage_amount ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Termination Policy</label>
        <input type="text" name="termination_policy" class="form-control" value="{{ old('termination_policy', $lease->termination_policy ?? '') }}">
      </div>
    </div>
  </div>

  {{-- Parking & Storage --}}
  <div class="card mb-4">
    <div class="card-header">Parking & Storage</div>
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Parking Info</label>
        <input type="text" name="parking_info" class="form-control" value="{{ old('parking_info', $lease->parking_info ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Storage Info</label>
        <input type="text" name="storage_info" class="form-control" value="{{ old('storage_info', $lease->storage_info ?? '') }}">
      </div>
    </div>
  </div>

  {{-- Additional Fees --}}
  <div class="card mb-4">
    <div class="card-header">Additional Fees</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead><tr><th>Fee Type</th><th>Amount</th><th></th></tr></thead>
        <tbody id="fee-table-body">
          @if(old('fees', isset($leaseFees) ? $leaseFees : []))
            @foreach(old('fees', $leaseFees ?? []) as $i => $fee)
              <tr>
                <td><input type="text" name="fees[{{ $i }}][type]" value="{{ $fee['type'] ?? '' }}" class="form-control" required></td>
                <td><input type="number" step="0.01" name="fees[{{ $i }}][amount]" value="{{ $fee['amount'] ?? '' }}" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-fee">×</button></td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
      <button type="button" class="btn btn-outline-primary btn-sm" id="add-fee">+ Add Fee</button>
    </div>
  </div>

  {{-- Attachments --}}
  <div class="card mb-4">
    <div class="card-header">Attachments</div>
    <div class="card-body">
      <input type="file" name="attachments[]" class="form-control" multiple>
    </div>
  </div>

  {{-- Submit --}}
  <div class="text-end">
    <a href="{{ route('leases.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Lease' : 'Create Lease' }}</button>
  </div>
</form>

@push('scripts')
<script>
  let feeIndex = {{ isset($leaseFees) ? count($leaseFees) : 0 }};
  document.getElementById('add-fee').addEventListener('click', () => {
    const row = `
      <tr>
        <td><input type="text" name="fees[${feeIndex}][type]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="fees[${feeIndex}][amount]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-fee">×</button></td>
      </tr>
    `;
    document.getElementById('fee-table-body').insertAdjacentHTML('beforeend', row);
    feeIndex++;
  });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-fee')) {
      e.target.closest('tr').remove();
    }
  });
</script>
@endpush
