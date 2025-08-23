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

    <div class="card shadow-sm refresh-body">
        <div class="table-responsive" id="refresh-part">
            @include('components.pages.index-table-refresh-part')
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
