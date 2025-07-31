{{-- resources/views/components/pages/index-table.blade.php --}}
<div class="container-fluid px-0">
    <!-- 顶部标题栏 -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="{{ $pageIcon ?? 'bi bi-list' }} text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">{{ $pageTitle ?? 'list' }}</h4>
            </div>
        </div>

        <!-- 操作栏 -->
        {{-- <div class="d-flex gap-2">
            <!-- 默认：新增 / 导出 -->
            <div id="default-toolbar" class="d-flex gap-2">
                @if (!empty($exportUrl))
                    <a href="{{ $exportUrl }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> 导出
                    </a>
                @endif
                <a href="{{ $createUrl }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> 新增{{ $createLabel }}
                </a>
            </div>
            <!-- 已勾选：批量操作 -->
            <div class="dropdown d-none" id="selected-toolbar">
                <span class="me-2 text-muted">已选中 <strong id="selected-count">0</strong> 项</span>
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="bulkActionDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    批量处理
                </button>
                <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                    <li>
                        <button href="#" class="dropdown-item bulk-action" onclick="submitBatchDelete()">
                            <i class="bi bi-trash3 me-1"></i> 批量删除
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item bulk-action" onclick="submitBatchApprove()">
                            <i class="bi bi-check-circle me-2"></i> 批量审核通过
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item bulk-action" onclick="submitBatchReject()">
                            <i class="bi bi-x-circle me-2"></i> 批量拒绝
                        </button>
                    </li>
                </ul>
            </div>
        </div> --}}
        <!-- Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2" id="toolbar-default">
                @foreach ($toolbar['default'] as $button)
                    @if ($button['type'] === 'link')
                        <a href="{{ $button['url'] ?? '#' }}" class="btn {{ $button['class'] ?? 'btn-primary' }}"
                            @if (isset($button['onclick'])) onclick="{{ $button['onclick'] }}" @endif>
                            <i class="{{ $button['icon'] }}"></i> {{ $button['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- 选中状态下的工具栏 -->
            <div id="toolbar-selected" class="d-flex align-items-center gap-3 d-none">
                <span class="text-muted">已选中 <strong id="selected-count">0</strong> 项</span>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle d-flex align-items-center gap-2 px-3"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-gear-fill"></i>
                        <span>批量操作</span>
                    </button>
                    <ul class="dropdown-menu shadow-sm">
                        <li>
                            <a class="dropdown-item text-danger" href="#" data-action="bulk-delete">
                                <i class="bi bi-trash-fill me-2"></i> 批量删除
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-success" href="#" data-action="bulk-approve">
                                <i class="bi bi-check-circle-fill me-2"></i> 批量审核通过
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-warning" href="#" data-action="bulk-reject">
                                <i class="bi bi-x-circle-fill me-2"></i> 批量拒绝
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <!-- 搜索栏 -->
        <form method="GET" action="{{ $searchAction ?? request()->url() }}" id="filter-form" class="mb-3">
            <input type="hidden" name="searchKeywordFields" value="{{ json_encode($searchKeywordFields) }}">
            <div
                class="card-header bg-light border-0 bg-transparent py-2 px-0 d-flex flex-wrap gap-2 align-items-center">
                @if (!empty($searchKeywordFields))
                    <div class="input-group border rounded" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-0 rounded-start">
                            <i class="bi bi-search text-secondary"></i>
                        </span>

                        @php
                            $placeholders = collect($searchKeywordFields ?? [])
                                ->pluck('label')
                                ->implode(' / ');
                        @endphp
                        <input type="text" name="keyword" value="{{ old('keyword', request('keyword')) }}"
                            class="form-control border-0 shadow-none rounded-end" placeholder="搜索：{{ $placeholders }}">
                    </div>
                @endif


                @php
                    $mergedFilterFields = [];

                    // 先加高级筛选字段
                    if (!empty($filterFields)) {
                        $mergedFilterFields = $filterFields;
                    }

                    // 再加快速筛选字段（自动补 type）
                    if (!empty($quickFilters)) {
                        $quickWithType = array_map(function ($filter) {
                            return array_merge(['type' => 'select'], $filter); // 默认加 type=select
                        }, $quickFilters);

                        $mergedFilterFields = array_merge($mergedFilterFields, $quickWithType);
                    }
                @endphp

                <input type="hidden" name="filterFields" value="{{ json_encode($mergedFilterFields) }}">

                {{-- 快速筛选区 --}}
                @if (!empty($quickFilters))
                    <div class="dropdown">
                        @foreach ($quickFilters as $filter)
                            @php
                                $hasFilter = in_array($filter['key'], (array) request('filters', []));
                                $param = request("filter_values.{$filter['key']}", null);

                                if (!$hasFilter) {
                                    // 没有在 filters 里 → 初始加载 → 默认全选
                                    $selectedValues = array_keys($filter['options']);
                                } else {
                                    // 在 filters 里 → 用户有操作
                                    $selectedValues = $param !== null ? (array) $param : []; // 没有值 → 全不选
                                }
                            @endphp

                            @php
                                $selectedLabels = [];
                                if (count($selectedValues) > 0) {
                                    foreach ($selectedValues as $val) {
                                        $selectedLabels[] = $filter['options'][$val] ?? $val;
                                    }
                                }
                            @endphp

                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $filter['label'] }}
                                @if (count($selectedValues) > 0)
                                    ({{ count($selectedValues) }}: {{ implode(', ', $selectedLabels) }})
                                @endif
                            </button>
                            <ul class="dropdown-menu p-2" style="min-width: 220px;" data-bs-auto-close="outside">
                                <input type="hidden" name="filters[]" value="{{ $filter['key'] }}">
                                @php
                                    $allSelected = count($selectedValues) === count($filter['options']);
                                @endphp
                                <li class="d-flex align-items-center px-2 mb-2">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input quick-filter-select-toggle"
                                            data-key="{{ $filter['key'] }}" {{ $allSelected ? 'checked' : '' }}>
                                        全选
                                    </label>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                @foreach ($filter['options'] as $value => $label)
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center">
                                            <input type="checkbox" name="filter_values[{{ $filter['key'] }}][]"
                                                value="{{ $value }}"
                                                class="form-check-input me-2 quick-filter-checkbox quick-filter-{{ $filter['key'] }}"
                                                {{ in_array($value, $selectedValues) ? 'checked' : '' }}>
                                            {{ $label }}
                                        </label>
                                    </li>
                                @endforeach
                                {{-- 应用按钮放在最后 --}}
                                <li class="mt-2 px-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        应用筛选
                                    </button>
                                </li>
                            </ul>
                        @endforeach


                    </div>
                @endif

                <!-- 其他筛选字段容器（可选） -->
                @if (!empty($filterFields))
                    <div class="dropdown">

                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-funnel me-1"></i> 添加筛选
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach ($filterFields as $filter)
                                <li class="px-3 py-1">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox"
                                            value="{{ $filter['key'] }}" id="filter-{{ $filter['key'] }}">
                                        <label class="form-check-label" for="filter-{{ $filter['key'] }}">
                                            {{ $filter['label'] }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                @endif

                <div class="" id="filter-action-bar" style="display: none; text-align:right;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> 查询</button>
                </div>
            </div>
            <div class="filter-section" id="dynamic-filters" style="display: none;">
                <div class="row g-2 flex-nowrap overflow-auto" id="filter-row">
                    @if (request('filters'))
                        @foreach (request('filters') as $filter)
                            <div class="col-md-auto">
                                @include($partialsForfilter, [
                                    'filter' => $filter,
                                    'value' => request('filter_values')[$filter] ?? null,
                                ])
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @if (request('keyword') || request('filters'))
                <div class="alert alert-info d-flex justify-content-between align-items-center px-3 py-2 mb-3">
                    <div>
                        当前显示的是搜索结果
                        @if (request('keyword'))
                            ，关键词：<strong>{{ request('keyword') }}</strong>
                        @endif
                        @if (request('filters'))
                            ，已使用 {{ count(request('filters')) }} 个筛选条件
                        @endif

                        {{-- 追加总记录数 --}}
                        ，共 <strong>{{ $records->total() ?? $records->count() }}</strong> 条记录
                    </div>
                    <a href="{{ route($routeName) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> 清除筛选
                    </a>
                </div>
            @endif
        </form>

        <!-- 表格容器-->
        <div class="table-responsive">
            <form id="batch-delete-form" method="POST" action="{{ $batchDeleteUrl ?? '#' }}"
                onsubmit="return confirm('确认批量删除？')">
                @csrf

                {{-- 把排序信息从$columns提取出来存入隐藏字段 --}}
                @php
                    $sortableFields = collect($columns)
                        ->filter(fn($col) => $col['sortable'] ?? false)
                        ->flatMap(function ($col) {
                            if (isset($col['sort_field'])) {
                                return [$col['sort_field'] => true];
                            } elseif (isset($col['sort_fields'])) {
                                return collect($col['sort_fields'])->mapWithKeys(fn($field) => [$field => true]);
                            } elseif (isset($col['column'])) {
                                return [$col['column'] => true];
                            }
                            return [];
                        })
                        ->keys()
                        ->map(function ($key) {
                            if (str_contains($key, '.')) {
                                $parts = explode('.', $key);
                                $column = array_pop($parts);
                                $relation = implode('.', $parts);
                                return ['key' => $key, 'relation' => $relation, 'column' => $column];
                            } else {
                                return ['key' => $key, 'relation' => null, 'column' => $key];
                            }
                        })
                        ->keyBy('key')
                        ->toArray();
                @endphp

                <input type="hidden" name="sortableFields" value='@json($sortableFields)'>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <!-- 勾选框列 -->
                            <th style="width: 30px;">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>

                            <!-- 动态表头列 -->
                            @foreach ($columns as $col)
                                @php
                                    $sortField = $col['column'] ?? null;
                                    $isSortable = $col['sortable'] ?? false;
                                    $isActive = request('sort') === $sortField;
                                    $dir = $isActive && request('direction') === 'asc' ? 'desc' : 'asc';
                                @endphp
                                <th>
                                    @php
                                        $sortableFieldsJson = json_encode($sortableFields);
                                    @endphp
                                    @if ($isSortable && $sortField)
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'direction' => $dir, 'sortableFields' => $sortableFieldsJson]) }}"
                                            class="sort-link {{ $isActive ? 'text-dark fw-bold' : 'text-muted' }}">
                                            {{ $col['label'] }}
                                            <span class="sort-icons">
                                                @if ($isActive)
                                                    <i
                                                        class="bi {{ $dir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                                                @else
                                                    <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                                @endif
                                            </span>
                                        </a>
                                    @else
                                        {{ $col['label'] }}
                                    @endif

                                    @if ($sortField && request('sort') === $sortField)
                                        <a href="{{ route($routeName) }}" class="ms-1 text-muted" title="恢复默认顺序">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </a>
                                    @endif
                                </th>
                            @endforeach

                            @if (!empty($actions))
                                <th class="text-end">操作</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $item)
                            @php
                                $clickUrl = isset($rowClickUrl) ? $rowClickUrl($item) : null;
                            @endphp
                            <tr
                                @if ($clickUrl) onclick="handleRowClick(event, '{{ $clickUrl }}')"
                                class="table-row-hover"
                                style="cursor: pointer;" @endif>
                                <!-- 勾选框 -->
                                <td>
                                    <input type="checkbox" name="selected_ids[]"
                                        value="{{ $item->id ?? $item->getKey() }}" class="form-check-input">
                                </td>

                                <!-- 动态表格列 -->
                                @foreach ($columns as $col)
                                    <td>
                                        @php
                                            $type = $col['type'] ?? 'text';
                                            $value = data_get($item, $col['column'] ?? null);
                                        @endphp

                                        @switch($type)
                                            @case('image')
                                                @php
                                                    $cover = $item->media->firstWhere('is_cover', 1) ?? null;
                                                @endphp

                                                @if ($cover)
                                                    <div class="position-relative rounded border"
                                                        style="width: 80px; height: 50px; overflow: hidden; background: #f8f9fa; cursor: pointer;">
                                                        <img src="{{ url('/media/property/' . $item->property_id . '/' . basename($cover->file_path)) }}"
                                                            class="w-100 h-100" style={{ $col['style'] }}
                                                            onclick="openMediaModal('{{ $item->property_id }}')">
                                                    </div>
                                                @else
                                                    <span class="text-muted small">无</span>
                                                @endif
                                            @break

                                            @case('badge')
                                                @php
                                                    $badgeClass = $col['badge_map'][$value] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }}">{{ $value ?? '未知' }}</span>
                                            @break

                                            @case('combine')
                                                <div>
                                                    {{ data_get($item, $col['columns'][0]) }}
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ data_get($item, $col['columns'][1]) }}
                                                    </small>
                                                </div>
                                            @break

                                            @case('custom')
                                                {!! $col['render']($item) !!}
                                            @break

                                            @default
                                                @if (isset($col['link']))
                                                    @php
                                                        $link = is_callable($col['link'])
                                                            ? $col['link']($item)
                                                            : $col['link'];
                                                    @endphp
                                                    <a href="{{ $link }}">
                                                        {{ $value }}
                                                    </a>
                                                @else
                                                    {{ $value }}
                                                @endif
                                        @endswitch
                                    </td>
                                @endforeach

                                <!-- 操作按钮列 -->
                                @if (!empty($actions))
                                    <td class="text-end">
                                        <div class="dropdown d-flex justify-content-end no-row-click">
                                            <button type="button" class="btn-action-menu" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end border-0">
                                                @php
                                                    $groupedActions = collect($actions)
                                                        ->groupBy(fn($a) => $a['group'] ?? 'default')
                                                        ->sortKeys(); // group 1, 2, 3... 的顺序
                                                    $groupCount = $groupedActions->count();
                                                @endphp

                                                @foreach ($groupedActions as $i => $group)
                                                    @if (!$loop->first)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @endif

                                                    @foreach ($group as $action)
                                                        <li>
                                                            <a class="dropdown-item {{ $action['class'] ?? '' }}"
                                                                href="{{ is_callable($action['url']) ? $action['url']($item) : $action['url'] }}"
                                                                {!! isset($action['onclick'])
                                                                    ? 'onclick="' . (is_callable($action['onclick']) ? $action['onclick']($item) : $action['onclick']) . '"'
                                                                    : '' !!}>
                                                                <i class="{{ $action['icon'] ?? '' }} me-2"></i>
                                                                {{ $action['label'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                @endif


                            </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columns) + 2 }}" class="text-center py-4 text-muted">暂无数据
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- 分页（由主页面传入 paginator）-->
            <div class="card-footer bg-white border-0 py-1">
                @if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    @include('components._pagination', ['paginator' => $paginator])
                @endif
            </div>
        </div>


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
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="关闭"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="mediaCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" id="carousel-inner">
                                <!-- JavaScript 动态注入内容 -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">上一张</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">下一张</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- script section --}}
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
                            content =
                                `<video controls class="d-block mx-auto" style="max-height: 500px;"><source src="${fileUrl}"></video>`;
                        } else {
                            content =
                                `<img src="${fileUrl}" class="d-block mx-auto" style="max-height: 500px;">`;
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
            showConfirm('确定要删除该条记录吗？', function() {
                const form = document.getElementById('delete-form');
                form.action = actionUrl;
                form.submit();
            });
        }

        function bulkDelete() {
            const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');

            if (selected.length === 0) {
                console.log(selected);
                showWarning('请至少选择一条记录进行删除');
                return;
            }
            showConfirm('确定要删除该条记录吗？', function() {
                document.getElementById('batch-delete-form').submit();
            });
        }
    </script>


    {{-- <!-- JS：批量选择 -->
    <script>
        

        document.getElementById('select-all')?.addEventListener('change', function(e) {
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = e.target.checked);
        });
    </script> --}}

    <script>
        const activeFilters = new Set(@json(request('filters') ?? []));

        function syncFilterVisibility() {
            const row = document.getElementById('filter-row');
            const container = document.getElementById('dynamic-filters');
            const actionBar = document.getElementById('filter-action-bar');
            if (row && container && actionBar) {
                const hasFilters = row.querySelectorAll('.filter-box').length > 0;
                console.log(hasFilters);
                container.style.display = hasFilters ? 'block' : 'none';
                actionBar.style.display = hasFilters ? 'flex' : 'none';
            }
        }

        function syncFilterState(option) {
            const filterFields = @json($filterFields);
            const row = document.getElementById('filter-row');
            const existing = document.querySelector(`[data-filter="${option}"]`);
            if (existing) {
                const wrapper = existing.closest('.col-md-auto');
                if (wrapper) wrapper.remove();
                activeFilters.delete(option);
                setTimeout(syncFilterVisibility, 0);
            } else {
                const moduleName = @json($module); // 安全地将 Blade 变量输出为 JS 字符串
                fetch(`/filters/field?filter=${option}&filters=${encodeURIComponent(JSON.stringify(filterFields))}`)
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
                    // 重新提交表单
                    const form = document.getElementById('filter-form');
                    if (form) form.submit();
                }
            }
        });
    </script>

    <script>
        function updateToolbarVisibility() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            const checkedCount = document.querySelectorAll('input[name="selected_ids[]"]:checked').length;
            const defaultToolbar = document.getElementById('toolbar-default');
            const selectedToolbar = document.getElementById('toolbar-selected');
            const countSpan = document.getElementById('selected-count');

            if (checkedCount > 0) {
                defaultToolbar?.classList.add('d-none');
                selectedToolbar?.classList.remove('d-none');
                if (countSpan) countSpan.textContent = checkedCount;
            } else {
                defaultToolbar?.classList.remove('d-none');
                selectedToolbar?.classList.add('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            const selectAll = document.getElementById('select-all');

            checkboxes.forEach(cb => cb.addEventListener('change', updateToolbarVisibility));
            selectAll?.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateToolbarVisibility();
            });
        });

        document.addEventListener('click', e => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const action = btn.getAttribute('data-action');
            switch (action) {
                case 'bulk-delete':
                    bulkDelete();
                    break;
                case 'bulk-approve':
                    bulkApprove();
                    break;
                case 'bulk-reject':
                    bulkReject();
                    break;
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            document.querySelectorAll('.toggle-active').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const url = this.dataset.url;
                    const userId = this.dataset.id;
                    const role = this.dataset.role; // 新增：需在 HTML 加上 data-role
                    const isActive = this.checked ? 1 : 0;
                    const checkboxEl = this;

                    // 如果是 admin，不允许更改状态
                    if (role === 'admin') {
                        Swal.fire({
                            icon: 'warning',
                            title: '无法修改',
                            text: '超级管理员状态不允许修改！',
                        });
                        // 恢复原状态
                        checkboxEl.checked = !checkboxEl.checked;
                        return;
                    }

                    // 弹出确认提示
                    const targetStatus = isActive ? '启用' : '禁用';
                    const message = `确定要将该用户状态修改为【${targetStatus}】吗？`;

                    Swal.fire({
                        icon: 'question',
                        title: '请确认操作',
                        text: message,
                        showCancelButton: true,
                        confirmButtonText: '确定',
                        cancelButtonText: '取消'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // 执行 AJAX 状态变更
                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify({
                                        is_active: isActive
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error('操作失败');
                                    return response.json();
                                })
                                .then(data => {
                                    console.log(data.message || '状态更新成功');
                                    // 更新文本状态
                                    const statusTextEl = checkboxEl.closest(
                                        '.form-check').querySelector('.status-text');
                                    if (statusTextEl) {
                                        statusTextEl.textContent = isActive ? '启用' :
                                            '禁用';
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '状态更新失败',
                                        text: '请稍后重试',
                                    });
                                    // 失败时恢复原状态
                                    checkboxEl.checked = !checkboxEl.checked;
                                });
                        } else {
                            // 用户取消操作，恢复原状态
                            checkboxEl.checked = !checkboxEl.checked;
                        }
                    });
                });
            });
        });
    </script>

    <script>
        function handleRowClick(event, url) {
            const ignoreTags = ['A', 'BUTTON', 'SVG', 'INPUT', 'LABEL', 'IMG'];
            if (ignoreTags.includes(event.target.tagName) || event.target.closest('.no-row-click')) {
                return;
            }

            window.location.href = url;
        }
    </script>

    <script>
        document.addEventListener('change', e => {
            if (e.target.classList.contains('quick-filter-select-toggle')) {
                const key = e.target.dataset.key;
                const isChecked = e.target.checked;
                document.querySelectorAll(`.quick-filter-${key}`).forEach(cb => {
                    cb.checked = isChecked;
                });
            }
        });
        // 阻止 checkbox 点击关闭菜单
        document.querySelectorAll('.quick-filter-checkbox').forEach(cb => {
            cb.addEventListener('click', e => e.stopPropagation());

            cb.addEventListener('change', function() {
                const form = this.closest('form');
                refreshTable(form);
            });
        });

        // 全选

        document.querySelectorAll('.quick-filter-select-all').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const key = this.dataset.key;
                const checkboxes = document.querySelectorAll('.quick-filter-' + key);
                checkboxes.forEach(cb => cb.checked = true);
                // 不再触发 refreshTable，等用户点击“应用”再提交
            });
        });

        // 全不选
        document.querySelectorAll('.quick-filter-deselect-all').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const key = this.dataset.key;
                const checkboxes = document.querySelectorAll('.quick-filter-' + key);
                checkboxes.forEach(cb => cb.checked = false);
                // 不再触发 refreshTable
            });
        });
    </script>
