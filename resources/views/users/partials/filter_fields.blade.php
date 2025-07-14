@if ($filter === 'name')
    <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;"
        data-filter="name">
        <input type="hidden" name="filters[]" value="name">
        <label class="form-label">角色</label>
        <select name="filter_values[name]" class="form-select">
            @foreach (\Spatie\Permission\Models\Role::all() as $option)
                <option value="{{ $option->name }}" @selected(($value ?? '') == $option->name)>
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
        <button type="button" class="remove-filter">×</button>
    </div>
@endif
