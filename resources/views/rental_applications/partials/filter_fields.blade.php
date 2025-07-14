@if ($filter === 'status')
    <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;"
        data-filter="status">
        <input type="hidden" name="filters[]" value="status">
        <label class="form-label">审批状态</label>
        <select name="filter_values[status]" class="form-select">
            @foreach (['submitted', 'under_review', 'approved', 'rejected'] as $option)
                <option value="{{ $option }}" @selected(($value ?? '') == $option)>
                    {{ $option }}
                </option>
            @endforeach
        </select>
        <button type="button" class="remove-filter">×</button>
    </div>
@endif
