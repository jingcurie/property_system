    @extends('layouts.app')

    @section('content')
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-dark">房源管理</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('properties.export', request()->all()) }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> 导出CSV
                    </a>
                    <a href="{{ route('properties.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> 新增房源
                    </a>
                    <a href="#" class="btn btn-danger" onclick="submitBatchDelete()">
                        <i class="bi bi-trash3 me-1"></i> 批量删除
                    </a>
                </div>
            </div>

<form method="GET" action="{{ route('properties.index') }}" id="filter-form" class="mb-4">
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-light fw-bold d-flex px-0 flex-wrap gap-2 align-items-center">
      <div>
        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="搜索房源名、地址..." size="30">
      </div>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-funnel me-1"></i> 添加筛选字段
        </button>
        <ul class="dropdown-menu" aria-labelledby="filterDropdown" id="filter-menu">
          <li class="px-3 py-1">
            <div class="form-check">
              <input class="form-check-input filter-checkbox" type="checkbox" value="rent" id="filter-rent">
              <label class="form-check-label" for="filter-rent">租金</label>
            </div>
            <div class="form-check">
              <input class="form-check-input filter-checkbox" type="checkbox" value="city" id="filter-city">
              <label class="form-check-label" for="filter-city">城市</label>
            </div>
            <div class="form-check">
              <input class="form-check-input filter-checkbox" type="checkbox" value="type" id="filter-type">
              <label class="form-check-label" for="filter-type">房源类型</label>
            </div>
            <div class="form-check">
              <input class="form-check-input filter-checkbox" type="checkbox" value="owner_id" id="filter-owner">
              <label class="form-check-label" for="filter-owner">房东</label>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="card-body p-3" id="dynamic-filters" style="display: none;">
      <div class="row g-2 flex-nowrap overflow-auto" id="filter-row">
        @if(request('filters'))
          @foreach(request('filters') as $filter)
            <div class="col-md-auto">
              @include('properties.partials.filter_fields', ['filter' => $filter, 'value' => request('filter_values')[$filter] ?? null])
            </div>
          @endforeach
        @endif
      </div>
    </div>

    <div class="card-footer bg-white" id="filter-action-bar" style="display: none; text-align:right;">
      <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> 查询</button> &nbsp;&nbsp;
      <a href="{{ route('properties.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> 重置</a>
    </div>
  </div>
</form>

<style>
  .remove-filter {
    position: absolute;
    top: 2px;
    right: 5px;
    padding: 0;
    border: none;
    background: none;
    color: #888;
    font-size: 1rem;
    line-height: 1;
    z-index: 10;
  }
  .filter-box {
    position: relative;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.5rem;
    background: #f9f9f9;
  }
</style>
            <form id="batch-delete-form" method="POST" action="{{ route('properties.batchDelete') }}" onsubmit="return validateBatchDelete();">
                @csrf
                <!-- 表格 -->
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:30px;">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>
                                        @php $active = request('sort') === 'property_name';
                                        $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'property_name', 'direction' => $dir]) }}"
                                            class="text-decoration-none {{ $active ? 'fw-bold text-dark' : 'text-muted' }}">
                                            房源名称 @if($active)<i
                                            class="bi bi-caret-{{ $dir === 'asc' ? 'down' : 'up' }}-fill"></i>@endif
                                        </a>
                                    </th>
                                    <th>封面</th>
                                    <th>地址</th>
                                    <th>
                                        @php $active = request('sort') === 'property_type';
                                        $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'property_type', 'direction' => $dir]) }}"
                                            class="text-decoration-none {{ $active ? 'fw-bold text-dark' : 'text-muted' }}">
                                            类型 @if($active)<i
                                            class="bi bi-caret-{{ $dir === 'asc' ? 'down' : 'up' }}-fill"></i>@endif
                                        </a>
                                    </th>
                                    <th>卧室/卫浴</th>
                                    <th>
                                        @php $active = request('sort') === 'monthly_rent';
                                        $dir = request('direction') === 'asc' ? 'desc' : 'asc'; @endphp
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'monthly_rent', 'direction' => $dir]) }}"
                                            class="text-decoration-none {{ $active ? 'fw-bold text-dark' : 'text-muted' }}">
                                            租金 @if($active)<i
                                            class="bi bi-caret-{{ $dir === 'asc' ? 'down' : 'up' }}-fill"></i>@endif
                                        </a>
                                    </th>
                                    <th>状态</th>
                                    <th>房东</th>
                                    <th class="text-end">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($properties as $property)
                                    <tr>
                                        <td><input type="checkbox" name="selected_ids[]" value="{{ $property->property_id }}"></td>
                                        <td>
                                            <a href="{{ route('properties.show', $property->property_id) }}" class="text-decoration-none fw-bold">
                                                {{ $property->property_name }}
                                            </a>
                                        </td>
                                        <td style="width:80px;">
                                            @php $cover = $property->media->firstWhere('is_cover', 1); @endphp
                                            @if($cover)
                                                <div class="position-relative rounded border"
                                                    style="width: 100px; height: 70px; overflow: hidden; background: #f8f9fa; cursor: pointer;"
                                                    onclick="openMediaModal('{{ $property->property_id }}')">
                                                    <img src="{{ url('/media/property/' . $property->property_id . '/' . basename($cover->file_path)) }}"
                                                        class="w-100 h-100"
                                                        style="object-fit: cover; object-position: center;">
                                                </div>
                                            @else
                                                <span class="text-muted small">无</span>
                                            @endif
                                        </td>
                                        <td>{{ $property->address_street }}<br><small
                                                class="text-muted">{{ $property->address_city }},
                                                {{ $property->address_province }}</small></td>
                                        <td>{{ $property->property_type }}</td>
                                        <td>{{ optional($property->feature)->bedrooms }} /
                                            {{ optional($property->feature)->bathrooms }}</td>
                                        <td>${{ number_format(optional($property->rentalInfo)->monthly_rent, 2) }}</td>
                                        <td>
                                            @php $status = optional($property->rentalInfo)->availability_status; @endphp
                                            <span class="badge 
                                                @if($status === 'Available') bg-success 
                                                @elseif($status === 'Leased') bg-secondary 
                                                @elseif($status === 'Under Maintenance') bg-warning text-dark 
                                                    @else bg-light text-muted 
                                                @endif">
                                                {{ $status ?? '未知' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ optional(optional($property->ownership)->owner)->full_name ?? '未指定' }}
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle"
                                                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" title="Actions">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('properties.show', $property->property_id) }}">
                                                            <i class="bi bi-eye me-2"></i> 查看
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('properties.edit', $property->property_id) }}">
                                                            <i class="bi bi-pencil-square me-2"></i> 编辑
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" 
                                                        class="dropdown-item text-danger" 
                                                        onclick="submitDelete('{{ route('properties.destroy', $property->property_id) }}')">
                                                        <i class="bi bi-trash me-2"></i> 删除
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">暂无数据</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        @include('components._pagination', ['paginator' => $properties])
                    </div>
                </div>
        </form>

        <form id="delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <!-- Modal: 房源媒体预览 -->
        <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">房源媒体预览</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="关闭"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="mediaCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" id="carousel-inner">
                                <!-- JavaScript 动态注入内容 -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">上一张</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">下一张</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function openMediaModal(propertyId) {
                fetch(`/property/${propertyId}/media`)
                    .then(response => response.json())
                    .then(data => {
                        const carouselInner = document.getElementById('carousel-inner');
                        carouselInner.innerHTML = '';

                        data.forEach((item, index) => {
                            const isActive = index === 0 ? 'active' : '';
                            const fileUrl = `/media/property/${propertyId}/${item.filename}`;

                            let content;
                            if (item.type === 'video') {
                                content = `<video controls class="d-block mx-auto" style="max-height: 500px;"><source src="${fileUrl}"></video>`;
                            } else {
                                content = `<img src="${fileUrl}" class="d-block mx-auto" style="max-height: 500px;">`;
                            }

                            carouselInner.innerHTML += `
                                <div class="carousel-item ${isActive} text-center" style="padding:2rem">
                                    ${content}
                                </div>`;
                        });

                        const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
                        modal.show();
                    });
            }
        </script>
            

        <script>
            function submitDelete(actionUrl) {
                if (confirm('确认删除？')) {
                    const form = document.getElementById('delete-form');
                    form.action = actionUrl;
                    form.submit();
                }
            }
        </script>
        

        <!-- JS：批量选择 -->
        <script>
            function submitBatchDelete() {
                const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
                if (selected.length === 0) {
                    alert('请至少选择一个房源进行删除');
                    return;
                }
                if (confirm('确认批量删除所选房源？')) {
                    document.getElementById('batch-delete-form').submit();
                }
            }

            document.getElementById('select-all')?.addEventListener('change', function (e) {
                document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = e.target.checked);
            });
        </script>

<script>
  const activeFilters = new Set(@json(request('filters') ?? []));

  function syncFilterVisibility() {
    const row = document.getElementById('filter-row');
    const container = document.getElementById('dynamic-filters');
    const actionBar = document.getElementById('filter-action-bar');
    if (row && container && actionBar) {
      const hasFilters = row.querySelectorAll('.filter-box').length > 0;
      container.style.display = hasFilters ? 'block' : 'none';
      actionBar.style.display = hasFilters ? 'flex' : 'none';
    }
  }

  function syncFilterState(option) {
    const row = document.getElementById('filter-row');
    const existing = document.querySelector(`[data-filter="${option}"]`);
    if (existing) {
      const wrapper = existing.closest('.col-md-auto');
      if (wrapper) wrapper.remove();
      activeFilters.delete(option);
      setTimeout(syncFilterVisibility, 0);
    } else {
      fetch(`/filters/field?filter=${option}`)
        .then(res => res.text())
        .then(html => {
          const wrapper = document.createElement('div');
          wrapper.classList.add('col-md-auto');
          wrapper.innerHTML = html;
          row.appendChild(wrapper);
          activeFilters.add(option);
          setTimeout(syncFilterVisibility, 0);
        });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.filter-checkbox').forEach(el => {
      const key = el.value;
      el.checked = activeFilters.has(key);
      el.addEventListener('change', () => syncFilterState(key));
    });
    setTimeout(syncFilterVisibility, 0);
  });

  document.addEventListener('click', e => {
    if (e.target.classList.contains('remove-filter')) {
      const filterBox = e.target.closest('[data-filter]');
      if (filterBox) {
        const key = filterBox.getAttribute('data-filter');
        const wrapper = filterBox.closest('.col-md-auto');
        if (wrapper) wrapper.remove();
        const checkbox = document.querySelector(`.filter-checkbox[value="${key}"]`);
        if (checkbox) checkbox.checked = false;
        activeFilters.delete(key);
        setTimeout(syncFilterVisibility, 0);
      }
    }
  });
</script>

@endsection