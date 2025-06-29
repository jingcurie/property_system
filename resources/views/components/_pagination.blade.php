@if ($paginator->total() > 0)
    <div class="mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <!-- 左侧：页码范围 + 每页条数 -->
            <div class="d-flex align-items-center flex-wrap gap-2 small text-muted">
                {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} / {{ $paginator->total() }}（共 {{ $paginator->lastPage() }} 页）

                <label class="ms-3 mb-0">每页</label>
                <select name="per_page" class="form-select form-select-sm w-auto"
                    onchange="window.location.href = addQueryParam(window.location.href, 'per_page', this.value)">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected(request('per_page', 10) == $size)>{{ $size }}</option>
                    @endforeach
                </select>
                <label class="mb-0">条</label>
            </div>

            <!-- 右侧：分页按钮 + 跳页 -->
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-end">

                <!-- 分页按钮 -->
                <div>
                    {{ $paginator->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>

                <!-- 跳页功能 -->
                <form method="GET" action="" onsubmit="return goToPage(this)" class="d-flex align-items-center gap-1">
                    {{-- @foreach(request()->except('page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach --}}
                    @foreach(request()->except('page') as $key => $val)
    @if(is_array($val))
        @foreach($val as $subKey => $subVal)
            @if(is_array($subVal))
                @foreach($subVal as $subSubKey => $subSubVal)
                    <input type="hidden" name="{{ $key }}[{{ $subKey }}][{{ $subSubKey }}]" value="{{ $subSubVal }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}[{{ $subKey }}]" value="{{ $subVal }}">
            @endif
        @endforeach
    @else
        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
    @endif
@endforeach

                    <label class="text-muted small mb-0">跳至</label>
                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}"
                        class="form-control form-control-sm w-auto text-center" style="min-width: 60px;" required>
                    <label class="text-muted small mb-0">页</label>
                    <button type="submit" class="btn btn-sm btn-outline-primary">跳转</button>
                </form>

            </div>
        </div>
    </div>

    <!-- 样式修正分页高度 -->
    <style>
        .pagination {
            margin-bottom: 0 !important;
        }
        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            height: 32px;
            line-height: 1.5;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
    </style>

    <!-- JS -->
    <script>
        function addQueryParam(url, key, value) {
            let u = new URL(url);
            u.searchParams.set(key, value);
            return u.toString();
        }

        function goToPage(form) {
            const input = form.querySelector('input[name="page"]');
            const max = parseInt(input.max);
            const val = parseInt(input.value);
            if (isNaN(val) || val < 1 || val > max) {
                alert('请输入 1 到 ' + max + ' 之间的页码');
                return false;
            }
            return true;
        }
    </script>
@endif
