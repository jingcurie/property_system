
<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="main-form">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Application Info -->
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ __('application.application_info') }}</div>
        <div class="card-body row g-3">
            @if (isset($property))
                {{-- 已知房源，自动填入且不可修改 --}}
                <div class="col-md-6">
                    <label class="form-label">{{ __('application.property') }}</label>
                    <input type="text" class="form-control" value="{{ $property->property_name }}" disabled>
                    <input type="hidden" name="property_id" value="{{ $property->property_id }}">
                </div>
            @else
                {{-- 未知房源，显示下拉选择 --}}
                <div class="col-md-6">
                    <label for="property_id" class="form-label">{{ __('application.property') }} <span class="text-danger">*</span></label>
                    <select id="my-select" name="property_id"
                        class="form-select @error('property_id') is-invalid @enderror" required>
                        @php
                            $selectedId = old('property_id', $application->property_id ?? '');
                        @endphp

                        @if (!$selectedId)
                            <option value="">{{ __('application.select_property_placeholder') }}</option>
                        @endif

                        @foreach ($properties as $property)
                            <option value="{{ $property->property_id }}"
                                {{ $selectedId == $property->property_id ? 'selected' : '' }}>
                                {{ $property->property_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="col-md-6">
                <label for="application_code" class="form-label">{{ __('application.application_code') }} <span class="text-danger">*</span></label>
                <input type="text" name="application_code" id="application_code"
                    class="form-control @error('application_code') is-invalid @enderror"
                    value="{{ old('application_code', $application->application_code ?? '') }}" required>
                @error('application_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Applicant Info -->
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ __('application.applicant_info') }}</div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('application.full_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[full_name]" class="form-control"
                    value="{{ old('applicant.full_name', $application->applicant->full_name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.email') }} <span class="text-danger">*</span></label>
                <input type="email" name="applicant[email]" class="form-control"
                    value="{{ old('applicant.email', $application->applicant->email ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.phone') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[phone]" class="form-control"
                    value="{{ old('applicant.phone', $application->applicant->phone ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.date_of_birth') }} <span class="text-danger">*</span></label>
                <input type="date" name="applicant[date_of_birth]" class="form-control"
                    value="{{ old('applicant.date_of_birth', $application->applicant->date_of_birth ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.government_id_type') }}</label>
                <select name="applicant[government_id_type]" class="form-select">
                    @foreach (['SSN', 'SIN', 'ITIN'] as $opt)
                        <option value="{{ $opt }}"
                            {{ old('applicant.government_id_type', $application->applicant->government_id_type ?? '') == $opt ? 'selected' : '' }}>
                            {{ __('application.id_types.' . $opt) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.ssn_last4') }}</label>
                <input type="text" name="applicant[ssn_last4]" class="form-control"
                    value="{{ old('applicant.ssn_last4', $application->applicant->ssn_last4 ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.address_line1') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[address_line1]" class="form-control"
                    value="{{ old('applicant.address_line1', $application->applicant->address_line1 ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.address_line2') }}</label>
                <input type="text" name="applicant[address_line2]" class="form-control"
                    value="{{ old('applicant.address_line2', $application->applicant->address_line2 ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.city') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[city]" class="form-control"
                    value="{{ old('applicant.city', $application->applicant->city ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('application.state') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[state]" class="form-control"
                    value="{{ old('applicant.state', $application->applicant->state ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('application.zip_code') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[zip_code]" class="form-control"
                    value="{{ old('applicant.zip_code', $application->applicant->zip_code ?? '') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('application.country') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[country]" class="form-control"
                    value="{{ old('applicant.country', $application->applicant->country ?? 'Canada') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.emergency_contact_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[emergency_contact_name]" class="form-control"
                    value="{{ old('applicant.emergency_contact_name', $application->applicant->emergency_contact_name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.emergency_contact_phone') }} <span class="text-danger">*</span></label>
                <input type="text" name="applicant[emergency_contact_phone]" class="form-control"
                    value="{{ old('applicant.emergency_contact_phone', $application->applicant->emergency_contact_phone ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.insurance_provider') }}</label>
                <input type="text" name="applicant[renters_insurance_provider]" class="form-control"
                    value="{{ old('applicant.renters_insurance_provider', $application->applicant->renters_insurance_provider ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.policy_number') }}</label>
                <input type="text" name="applicant[policy_number]" class="form-control"
                    value="{{ old('applicant.policy_number', $application->applicant->policy_number ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('application.coverage_amount') }}</label>
                <input type="number" step="0.01" name="applicant[coverage_amount]" class="form-control"
                    value="{{ old('applicant.coverage_amount', $application->applicant->coverage_amount ?? '') }}">
            </div>
        </div>
    </div>

    <!-- Employment Info -->
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ __('application.employment_income') }}</div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('application.employer_name') }} <span class="text-danger">*</span></label>
                <input type="text" name="employment[employer_name]" class="form-control"
                    value="{{ old('employment.employer_name', $application->employment->employer_name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.job_title') }} <span class="text-danger">*</span></label>
                <input type="text" name="employment[job_title]" class="form-control"
                    value="{{ old('employment.job_title', $application->employment->job_title ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.monthly_income') }} <span class="text-danger">*</span></label>
                <input type="number" name="employment[monthly_income]" class="form-control"
                    value="{{ old('employment.monthly_income', $application->employment->monthly_income ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.other_income_source') }}</label>
                <input type="text" name="employment[other_income_source]" class="form-control"
                    value="{{ old('employment.other_income_source', $application->employment->other_income_source ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.income_verified_by') }}</label>
                <select name="employment[income_verified_by]" class="form-select">
                    <option value="manual"
                        {{ old('employment.income_verified_by', $application->employment->income_verified_by ?? '') == 'manual' ? 'selected' : '' }}>
                        {{ __('application.verification_methods.manual') }}
                    </option>
                    <option value="third_party"
                        {{ old('employment.income_verified_by', $application->employment->income_verified_by ?? '') == 'third_party' ? 'selected' : '' }}>
                        {{ __('application.verification_methods.third_party') }}
                    </option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.verification_date') }}</label>
                <input type="date" name="employment[verification_date]" class="form-control"
                    value="{{ old('employment.verification_date', $application->employment->verification_date ?? '') }}">
            </div>
        </div>
    </div>

    <!-- Consent Info -->
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ __('application.authorization_consent') }}</div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="consent[credit_check_consent]" value="1"
                        {{ old('consent.credit_check_consent', $application->consent->credit_check_consent ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ __('application.credit_check_consent') }}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="consent[background_check_consent]" value="1"
                        {{ old('consent.background_check_consent', $application->consent->background_check_consent ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ __('application.background_check_consent') }}</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.esignature_provider') }}</label>
                <input type="text" name="consent[esignature_provider]" class="form-control"
                    value="{{ old('consent.esignature_provider', $application->consent->esignature_provider ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('application.esignature_id') }}</label>
                <input type="text" name="consent[esignature_id]" class="form-control"
                    value="{{ old('consent.esignature_id', $application->consent->esignature_id ?? '') }}">
            </div>
        </div>
    </div>

    <div class="form-check my-4">
        <input class="form-check-input @error('fair_housing_acknowledged') is-invalid @enderror" type="checkbox"
            name="fair_housing_acknowledged" id="fair_housing_acknowledged" value="1"
            {{ old('fair_housing_acknowledged', $application->fair_housing_acknowledged ?? false) ? 'checked' : '' }} required>
        <label class="form-check-label" for="fair_housing_acknowledged">
            {{ __('application.fair_housing_policy') }}
        </label>
        @error('fair_housing_acknowledged')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" id="fingerprint" name="fingerprint"
        value="{{ old('fingerprint') ?? \Illuminate\Support\Str::uuid() }}">

    <div class="text-end d-flex justify-content-between align-items-center">
        <a href="{{ route('rental_applications.index') }}" class="btn btn-outline-secondary">{{ __('application.cancel') }}</a>
        <button type="submit" class="btn btn-primary" onclick="submitMainForm()">
            {{ $isEdit ? __('application.update_application') : __('application.submit_application') }}
        </button>
    </div>
</form>

<!-- 引入 Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@push('scripts')
    <script>
        $(function() {
            $('#my-select').select2({
                tags: true,
                placeholder: '{{ __('application.select_or_input_placeholder') }}',
                allowClear: true
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fp = btoa(navigator.userAgent + screen.width + screen.height + new Date().getTimezoneOffset());
            document.getElementById('fingerprint').value = fp;
        });

        function submitMainForm() {
            const form = document.getElementById('main-form');
            if (form.checkValidity()) {
                form.submit();
            } else {
                form.reportValidity();
            }
        }
    </script>
@endpush