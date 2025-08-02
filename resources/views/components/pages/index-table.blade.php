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
            {{-- 批量toolbar放到card头了 --}}
        </div>
    </div>

    <div class="card shadow-sm">
        <!-- 搜索栏 -->
        <div class="sticky-top bg-white" style="top: 0px; z-index: 1030;"> {{-- 新增 sticky --}}
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
                                class="form-control border-0 shadow-none rounded-end"
                                placeholder="搜索：{{ $placeholders }}">
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

                                @endphp

                                {{-- 如果是 select 类型 → 渲染单选下拉 --}}
                                @if (($filter['type'] ?? 'multi-select') === 'select')
                                    @php
                                        if ($param !== null) {
                                            // 用户有选择 → 使用用户选择
                                            $selectedValues = (array) $param;
                                        } else {
                                            // 没有任何选择 → 默认第一个选项
                                            $selectedValues = [array_key_first($filter['options'])];
                                        }
                                    @endphp
                                    <div class="mb-2">
                                        <select name="filter_values[{{ $filter['key'] }}]" class="form-select mt-2"
                                            onchange="this.form.submit()">
                                            @foreach ($filter['options'] as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ in_array($value, $selectedValues) ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="filters[]" value="{{ $filter['key'] }}">
                                    </div>
                                @else
                                    {{-- 复选框模式 --}}
                                    @php
                                        if (!$hasFilter) {
                                            $selectedValues = array_keys($filter['options']); // 初始默认全选
                                        } else {
                                            $selectedValues = $param !== null ? (array) $param : []; // 没有值 → 全不选
                                        }

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
                                    <ul class="dropdown-menu p-2" style="min-width: 220px;"
                                        data-bs-auto-close="outside">
                                        <input type="hidden" name="filters[]" value="{{ $filter['key'] }}">
                                        @php
                                            $allSelected = count($selectedValues) === count($filter['options']);
                                        @endphp
                                        <li class="d-flex align-items-center px-2 mb-2">
                                            <label class="form-check-label">
                                                <input type="checkbox"
                                                    class="form-check-input quick-filter-select-toggle"
                                                    data-key="{{ $filter['key'] }}"
                                                    {{ $allSelected ? 'checked' : '' }}>
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

                                        <li class="mt-2 px-2 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                应用筛选
                                            </button>
                                        </li>
                                    </ul>
                                @endif
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

                    <!-- 选中状态下的批量工具栏 -->
                    <div id="toolbar-selected" class="d-flex align-items-center gap-3 d-none ms-auto">
                        <span class="text-muted">已选中 <strong id="selected-count">0</strong> 项</span>
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle d-flex align-items-center gap-2 px-3"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill"></i>
                                <span>批量操作</span>
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                @foreach ($toolbar['selected'][0]['items'] as $item)
                                    <li>
                                        <a class="dropdown-item 
                            @if ($item['action'] === 'bulk-delete' || $item['action'] === 'bulkForceDelete') text-danger @endif"
                                            href="#" data-action="{{ $item['action'] }}">
                                            @if (!empty($item['icon']))
                                                <i class="{{ $item['icon'] }} me-2"></i>
                                            @endif
                                            {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
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
        </div>

        <!-- 表格容器-->
        <div class="table-responsive">
            {{-- form删除法我已经去掉，现在用的是ajax，如果要恢复请从github中找 --}}

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
                        <tr id="row-{{ $item->getKey() }}"
                            @if ($clickUrl) onclick="handleRowClick(event, '{{ $clickUrl }}')"
                                class="table-row-hover"
                                style="cursor: pointer;" @endif>
                            <!-- 勾选框 -->
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="{{ $item->getKey() }}"
                                    class="form-check-input">
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

    @push('scripts')
        <script src="{{ asset('js/index-table.js') }}"></script>
        <script>
            window.moduleName = "{{ $module ?? '' }}"; //trash模块用的，用于确认哪一个表。
        </script>
        <script>
            // 把php的变量带入js文件
            searchAndFilters({
                activeFilters: new Set(@json(request('filters') ?? [])),
                filterFields: @json($filterFields),
                module: @json($module)
            });
        </script>
    @endpush
