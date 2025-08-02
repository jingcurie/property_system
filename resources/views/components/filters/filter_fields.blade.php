@php
    // 找到对应配置
    $config = collect($filterFields)->firstWhere('key', $filter);
@endphp

@if ($config)
    <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;"
        data-filter="{{ $filter }}">
        <input type="hidden" name="filters[]" value="{{ $filter }}">
        <label class="form-label">{{ $config['label'] ?? ucfirst($filter) }}</label>

        @if ($config['type'] === 'text')
            <input type="text" name="filter_values[{{ $filter }}]" class="form-control"
                value="{{ $value }}">
        @elseif ($config['type'] === 'select')
            <select name="filter_values[{{ $filter }}]" class="form-select">
                <option value=""></option>
                @foreach ($config['options'] ?? [] as $optionValue => $optionLabel)
                    {{-- @php
                        if (is_numeric($optionValue)) {
                            $optionValue = $optionLabel;
                        }
                    @endphp --}}
                    <option value="{{ $optionValue }}" @selected($value == $optionValue)>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        @elseif ($config['type'] === 'number_range')
            <div class="d-flex gap-2">
                <div class="flex-fill">
                    <input type="number" class="form-control" name="filter_values[{{ $filter }}][min]"
                        value="{{ $value['min'] ?? '' }}" placeholder="最小">
                </div>
                <div class="flex-fill">
                    <input type="number" class="form-control" name="filter_values[{{ $filter }}][max]"
                        value="{{ $value['max'] ?? '' }}" placeholder="最大">
                </div>
            </div>
        @elseif ($config['type'] === 'date_range')
            <div class="d-flex gap-2">
                <input type="date" class="form-control" style="width: 50%;"
                    name="filter_values[{{ $filter }}][start]" value="{{ $value['start'] ?? '' }}">
                <input type="date" class="form-control" style="width: 50%;"
                    name="filter_values[{{ $filter }}][end]" value="{{ $value['end'] ?? '' }}">
            </div>
        @endif


        <button type="button" class="remove-filter btn btn-sm btn-outline-danger mt-1">×</button>
    </div>
@endif
