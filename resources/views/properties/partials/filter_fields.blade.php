@if($filter === 'rent')
  <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;" data-filter="rent">
    <input type="hidden" name="filters[]" value="rent">
    <div class="d-flex gap-2">
      <div class="flex-fill">
        <label class="form-label">最小租金</label>
        <input type="number" class="form-control" name="filter_values[rent][min]" value="{{ $value['min'] ?? '' }}">
      </div>
      <div class="flex-fill">
        <label class="form-label">最大租金</label>
        <input type="number" class="form-control" name="filter_values[rent][max]" value="{{ $value['max'] ?? '' }}">
      </div>
    </div>
    <button type="button" class="remove-filter">×</button>
  </div>
@elseif($filter === 'city')
  <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;" data-filter="city">
    <input type="hidden" name="filters[]" value="city">
    <label class="form-label">城市</label>
    <input type="text" class="form-control" name="filter_values[city]" value="{{ $value ?? '' }}">
    <button type="button" class="remove-filter">×</button>
  </div>
@elseif($filter === 'type')
  <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;" data-filter="type">
    <input type="hidden" name="filters[]" value="type">
    <label class="form-label">房源类型</label>
    <select name="filter_values[type]" class="form-select">
      @foreach(['Apartment','House','Townhouse','Condo','Basement','Other'] as $option)
        <option value="{{ $option }}" @selected(($value ?? '') == $option)> {{ $option }} </option>
      @endforeach
    </select>
    <button type="button" class="remove-filter">×</button>
  </div>
@elseif($filter === 'owner_id')
  @php $owners = DB::table('activeowners')->get(); @endphp
  <div class="filter-box flex-shrink-0 d-inline-block me-2 mb-2" style="min-width: 250px; max-width: 300px;" data-filter="owner_id">
    <input type="hidden" name="filters[]" value="owner_id">
    <label class="form-label">房东</label>
    <select name="filter_values[owner_id]" class="form-select">
      @foreach($owners as $owner)
        <option value="{{ $owner->owner_id }}" @selected(($value ?? '') == $owner->owner_id)> {{ $owner->first_name }} {{ $owner->last_name }} </option>
      @endforeach
    </select>
    <button type="button" class="remove-filter">×</button>
  </div>
@endif
