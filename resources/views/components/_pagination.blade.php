@if ($paginator->total() > 0)
    <div class="mt-4 mb-5">
        <div class="d-flex justify-content-between flex-wrap align-items-center gap-3" style="min-height: 48px;">

            {{-- 左侧：记录信息 + 每页条数 --}}
            <div class="d-flex align-items-center gap-2 small text-muted">
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

            {{-- 右侧：分页按钮 + 跳转页 --}}
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-end">

                {{-- 分页按钮 --}}
                <div class="pagination-wrapper">
                    {{ $paginator->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>

                {{-- 跳转页 --}}
                <form method="GET" action="" onsubmit="return goToPage(this)" class="d-flex align-items-center gap-1">
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

                    {{-- <label class="text-muted small mb-0">跳至</label>
                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}"
                        class="form-control form-control-sm w-auto text-center" style="min-width: 60px;" required>
                    <label class="text-muted small mb-0">页</label>
                    <button type="submit" class="btn btn-sm btn-outline-primary">跳转</button> --}}
                </form>
            </div>
        </div>
    </div>

    {{-- 样式美化 --}}
    <style>
        .pagination {
            margin-bottom: 0 !important;
        }

        .pagination .page-link {
            border: none;
            border-radius: 0.5rem;
            background-color: transparent;
            color: #0d6efd;
            transition: all 0.2s;
            margin:0 0.3rem
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            color: white;
        }

        .pagination .page-link:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
        }

        /* ✅ 为上一页和下一页按钮保留边框 */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border: 1px solid #c9cacc;
        background-color: transparent;  
        padding:0px 15px;
        margin:0 0.5rem;
        margin: top 2px;px;
        font-size:1.3rem;
    }
    .pagination .page-item:first-child .page-link{
        border-radius: 30% 5px 5px 30%;
    }

    .pagination .page-item:last-child .page-link{
        border-radius: 5px 30% 30% 5px;
    }

    .pagination .page-item:first-child .page-link:hover,
    .pagination .page-item:last-child .page-link:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    </style>

    {{-- JS 脚本 --}}
    <script>
        function addQueryParam(url, key, value) {
            const u = new URL(url);
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
