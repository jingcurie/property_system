@props([
    'module' => '模块', // 模块中文名，例如 "房源"
    'routePrefix' => '', // 路由前缀，例如 'properties'
    'columns' => [], // 每列配置 ['field' => 'name', 'label' => '名称', 'sortable' => true]
    'records' => [], // 数据集合
    'filtersEnabled' => false,
    'extraActions' => false, // 是否显示导出按钮和批量删除
    'showExport' => false, // ← 加这一行，默认不显示导出按钮
    'createRoute' => '',
    'exportRoute' => '',
    'batchDeleteRoute' => '',
])

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark">
            <i class="bi bi-list-ul me-2"></i> {{ $module }}管理
        </h2>

        <div class="d-flex gap-2">
            @if ($showExport)
                <a href="{{ route($routePrefix . '.export', request()->all()) }}" class="btn btn-outline-success">
                    <i class="bi bi-download me-1"></i>
                </a>
            @endif

            @if ($createRoute)
                <a href="{{ $createRoute }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> 新增{{ $module }}
                </a>
            @endif

            @if ($extraActions)
                <a href="#" class="btn btn-danger" onclick="submitBatchDelete()">
                    <i class="bi bi-trash3 me-1"></i> 批量删除
                </a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ route($routePrefix . '.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                placeholder="搜索关键词...">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <form id="batch-delete-form" method="POST" action="{{ $batchDeleteRoute }}">
        @csrf

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            @foreach ($columns as $col)
                                @php
                                    $sortField = $col['field'];
                                    $isActive = request('sort') === $sortField;
                                    $dir = $isActive && request('direction') === 'asc' ? 'desc' : 'asc';
                                @endphp
                                <th>
                                    @if ($col['sortable'] ?? false)
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortField, 'direction' => $dir]) }}"
                                            class="sort-link {{ $isActive ? 'text-dark fw-bold' : 'text-muted' }}">
                                            {{ $col['label'] }}
                                            <span class="sort-icons">
                                                @if ($isActive)
                                                    <i
                                                        class="bi {{ $dir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                                                @else
                                                    <i class="bi bi-caret-up"></i>
                                                    <i class="bi bi-caret-down"></i>
                                                @endif
                                            </span>
                                        </a>
                                    @else
                                        {{ $col['label'] }}
                                    @endif
                                </th>
                            @endforeach
                            <th class="text-end">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $record->id }}"></td>
                                @foreach ($columns as $col)
                                    <td>{!! data_get($record, $col['field']) !!}</td>
                                @endforeach
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn-action-menu" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route($routePrefix . '.show', $record->id) }}">
                                                    <i class="bi bi-eye me-2"></i> 查看
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route($routePrefix . '.edit', $record->id) }}">
                                                    <i class="bi bi-pencil-square me-2"></i> 编辑
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item text-danger"
                                                    onclick="submitDelete('{{ route($routePrefix . '.destroy', $record->id) }}')">
                                                    <i class="bi bi-trash me-2"></i> 删除
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center py-4 text-muted">暂无数据</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function submitDelete(actionUrl) {
        if (confirm('确认删除？')) {
            const form = document.getElementById('delete-form');
            form.action = actionUrl;
            form.submit();
        }
    }

    function submitBatchDelete() {
        const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
        if (selected.length === 0) {
            alert('请至少选择一个进行删除');
            return;
        }
        if (confirm('确认批量删除？')) {
            document.getElementById('batch-delete-form').submit();
        }
    }

    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
