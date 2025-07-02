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


    {{-- <style>
        body {
            background-color: #f8f9fa;
            font-size: 0.9rem;
        }

        .sidebar {
            height: 100vh;
            width: 240px;
            background-color: #1e293b;
            padding-top: 1rem;
            transition: width 0.3s;
            position: fixed;
            z-index: 1000;
            overflow-x: visible;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        .sidebar .nav-link {
            color: #cbd5e0;
            padding: 0.75rem 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: rem;
            white-space: nowrap;
            border-radius: 0.5rem;
            position: relative;
            transition: background-color 0.2s;
            justify-content: space-between;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #3b82f6;
            color: #fff;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-link .arrow {
            display: none;
        }

        .main-content {
            margin-left: 240px;
            padding: 2rem;
            transition: margin-left 0.3s;
        }

        .main-content.collapsed {
            margin-left: 64px;
        }

        .toggle-btn {
            position: absolute;
            top: 50px;
            right: -14px;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
            border: 2px solid #fff;
        }

        .sidebar:hover .toggle-btn {
            opacity: 1;
        }

        .has-submenu {
            position: relative;
            z-index: 9999;
        }

        .has-submenu .arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .has-submenu.active .arrow {
            transform: rotate(-180deg);
        }

        .has-submenu .floating-submenu {
            display: none;
        }

        .sidebar.collapsed .has-submenu:hover .floating-submenu {
            display: flex;
        }

        .floating-submenu {
            position: absolute;
            top: 0;
            left: 100%;
            min-width: 180px;
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            padding: 0.5rem 0;
            flex-direction: column;
            border: 1px solid #e2e8f0;
            z-index: 99999;
            transition: max-height 0.3s ease;
        }

        .floating-submenu a {
            padding: 0.5rem 1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .floating-submenu a:hover {
            background-color: #f1f5f9;
            color: #3b82f6;
        }

        .sidebar:not(.collapsed) .has-submenu.active .floating-submenu {
            display: flex;
            position: relative;
            left: 0;
            /* top: 0.5rem; */
            background-color: #273549;
            border: none;
            box-shadow: none;
            padding-left: 1.5rem;
        }

        .sidebar:not(.collapsed) .floating-submenu a {
            color: #fff;
        }

        .sidebar:not(.collapsed) .floating-submenu a:hover {
            background-color: #3b82f6;
            color: #fff;
        }

        .property-table td {
            vertical-align: middle;
            font-size: 15px;
        }

        .property-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table td,
        .table th {
            border-color: #f1f3f5 !important;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-radius: 10px !important;
            border: 1px solid rgb(232, 230, 230);
            padding: 0.5rem;
        }

        .card .table {
            border-width: 0px !important;
        }

        .property-img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.95rem !important;
            /* 默认大约是 0.75rem，改大 */
            font-weight: 600;
            padding: 0.5em 1em;
        }

        .badge-soft {
            border-radius: 12px;
            font-size: 0.75rem;
            padding: 0.4em 0.65em;
            font-weight: 500;
        }

        .badge-available {
            background-color: #ace3ca;
            color: #0f5132;
        }

        .badge-maintenance {
            background-color: #f6c99d;
            color: #663803;
        }

        .badge-leased {
            background-color: #cccdcf;
            color: #41464b;
        }

        .action-btn {
            border: none;
            background: transparent;
            color: #6c757d;
        }

        .action-btn:hover {
            color: #212529;
            background-color: #f1f3f5;
            border-radius: 6px;
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 64px;
            }

            .main-content {
                margin-left: 64px;
            }

            .sidebar.collapsed {
                width: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }
        }

        /* 筛选组件现代美化 */
        .filter-box {
            position: relative;
            background-color: #f9fafb;
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            padding: 1rem;
            min-width: 220px;
        }

        .remove-filter {
            position: absolute;
            top: 6px;
            right: 8px;
            border: none;
            background: none;
            font-size: 1.2rem;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }

        /* 搜索框与按钮更精致 */
        #filter-form input[type="text"] {
            max-width: 300px;
            border-radius: 0.4rem;
            font-size: 14px;

        }

        /* 按钮统一样式优化 */
        #filter-form .btn {
            border-radius: 0.4rem;
            font-size: 14px;
            padding: 0.5rem 1rem;
        }

        #filter-form .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        #filter-form .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        #filter-form .btn-secondary {
            background-color: #e5e7eb;
            color: #333;
            border: 1px solid #ccc;
        }

        #filter-form .btn-secondary:hover {
            background-color: #d1d5db;
        }

        /* 移动端优化：筛选区域横向滚动 */
        #filter-row {
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        /* 分页按钮现代样式 */
        .pagination .page-link {
            border-radius: 0.375rem;
            padding: 0.4rem 0.75rem;
            margin: 0 2px;
        }
    </style> --}}

    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/filters.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/checkbox.css') }}" rel="stylesheet">
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
                    <a href="{{ route('applications.index') }}"
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
            <nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-4">
                <div class="container-fluid">
                    <span class="navbar-text">欢迎使用物业管理系统</span>
                </div>
            </nav>

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
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    @stack('scripts') {{-- ✅ 加在这里，让 FilePond 初始化代码生效 --}}

</body>

</html>