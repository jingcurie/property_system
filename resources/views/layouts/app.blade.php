<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '物业管理系统') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />


    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet" />

    <!-- lucide icon -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/filters.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tooltip.css') }}" rel="stylesheet">
    @stack('styles') {{-- 必须加这个，@push 才能工作 --}}
</head>

<body>
    <div class="d-flex">
        <div id="sidebar" class="sidebar d-flex flex-column">
            <a href="{{ url('/') }}" class="navbar-brand text-white mb-4 px-3 d-flex align-items-center">
                <i class="bi bi-house-door-fill fs-4"></i>
                <span class="ms-2 d-none d-md-inline">{{ config('app.name', '物业管理系统') }}</span>
            </a>

            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="bi bi-chevron-left"></i>
            </div>

            <ul class="nav nav-pills flex-column w-100 px-2">
                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link" onclick="toggleSubmenu(event, this)">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-building"></i>
                            <span>房源管理</span> 
                        </div>
                        <i class="bi bi-caret-down-fill arrow"></i>
                    </a>
                    <div class="floating-submenu">
                        <a href="{{ route('properties.index') }}">
                            <i class="bi bi-card-list"></i> 房源列表
                        </a>
                        <a href="{{ route('properties.index') }}">
                            <i class="bi bi-card-list"></i> 房源列表
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('rental_applications.index') }}"
                        class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clipboard-check"></i>
                            <span>租赁申请</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link" onclick="toggleSubmenu(event, this)">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-building"></i>
                            <span>用户管理</span>
                        </div>
                        <i class="bi bi-caret-down-fill arrow"></i>
                    </a>
                    <div class="floating-submenu">
                        <a href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> 用户
                        </a>
                        <a href="{{ route('roles.index') }}">
                            <i class="bi bi-person-gear"></i>角色
                        </a>
                        <a href="{{ route('permissions.index') }}">
                            <i class="bi bi-shield-lock"></i> 权限
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i> {{-- 或 lucide-log-out --}}
                            <span>退出登录</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div id="main-content" class="main-content flex-grow-1">
            <nav
                class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm w-100 d-flex justify-content-between align-items-center px-3 py-3">
                <!-- 左侧 LOGO / 欢迎语 -->
                <h1>{{ __('passwords.reset') }}</h1><span class="text-muted small">语言：{{ app()->getLocale() }}</span>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-house-door-fill fs-5 text-primary"></i>
                    <span class="fw-bold">{{ __('欢迎使用物业管理系统') }}</span>
                </div>

                <!-- 右侧功能区 -->
                <div class="d-flex align-items-center gap-4">
                    <!-- 消息图标 -->
                    <a href="#" class="text-dark position-relative d-inline-block" style="line-height: 1;">
                        <i class="bi bi-envelope fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger text-white"
                            style="font-size: 0.6rem; padding: 0.25em 0.45em; border-radius: 999px;">
                            5
                        </span>
                    </a>



                    <!-- 系统设置 -->
                    {{-- <a href="{{ route('settings.index') }}" class="text-dark"> --}}
                    <a href="/" class="text-dark">
                        <i class="bi bi-gear fs-5"></i>
                    </a>

                    <!-- 语言切换 -->
                    <div class="dropdown">
                        <a class="dropdown-toggle text-dark" href="#" role="button"
                            data-bs-toggle="dropdown">
                            🌐 {{ app()->getLocale() === 'zh_CN' ? '中文' : 'English' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'zh') }}">中文</a></li>
                        </ul>
                    </div>

                    <!-- 用户头像与名称 -->
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-dark dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar"
                                class="rounded-circle me-2" width="32" height="32">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- <li><a class="dropdown-item" href="{{ route('profile') }}">{{ __('个人资料') }}</a></li> --}}
                            <li><a class="dropdown-item" href="/">{{ __('个人资料') }}</a></li>
                            <li><a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('退出登录') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
                    </div>
                @endif
            </div>

            <main>
                @yield('content')
            </main>

            <footer class="text-center text-muted py-4 border-top">
                &copy; {{ date('Y') }} 物业管理系统. Powered by Laravel.
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const icon = sidebar.querySelector('.toggle-btn i');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            icon.classList.toggle('bi-chevron-left');
            icon.classList.toggle('bi-chevron-right');
        }

        function toggleSubmenu(e, el) {
            e.preventDefault();
            const parent = el.closest('.has-submenu');
            const all = document.querySelectorAll('.has-submenu');
            all.forEach(item => {
                if (item !== parent) item.classList.remove('active');
            });
            parent.classList.toggle('active');
        }
    </script>
    <!-- FilePond JS -->
    {{--
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond-plugin-sort/dist/filepond-plugin-sort.js"></script> --}}

    <script>
        lucide.createIcons(); // 初始化所有图标
    </script>

    <!-- FilePond JS 插件依赖：放在 @stack('scripts') 前，顺序不能乱 -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 成功提示
        function showSuccess(message = '操作成功！') {
            Swal.fire({
                icon: 'success',
                title: '成功',
                text: message,
                timer: 1800,
                showConfirmButton: false
            });
        }

        // 错误提示
        function showError(message = '操作失败，请稍后重试！') {
            Swal.fire({
                icon: 'error',
                title: '错误',
                text: message,
            });
        }

        // 警告提示
        function showWarning(message = '警告信息') {
            Swal.fire({
                icon: 'warning',
                title: '请注意',
                text: message,
            });
        }

        // 确认框（带回调）
        function showConfirm(message = '确定执行此操作？', callback) {
            Swal.fire({
                icon: 'question',
                title: '确认操作',
                text: message,
                showCancelButton: true,
                confirmButtonText: '确认',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
    </script>

    @stack('scripts') {{-- ✅ 加在这里，让 FilePond 初始化代码生效 --}}

</body>

</html>
