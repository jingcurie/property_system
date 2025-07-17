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
    <link href="{{ asset('css/roleCard.css') }}" rel="stylesheet">
    @stack('styles') {{-- 必须加这个，@push 才能工作 --}}
</head>

<body>
    <div class="d-flex">
        <div id="sidebar" class="sidebar d-flex flex-column">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-logo ajax-linkr">
                    <i class="bi bi-house-door-fill fs-4 me-2"></i>
                    <span class="app-name d-none d-md-inline">{{ config('app.name', 'Properties Management') }}</span>
                </a>

                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-chevron-left"></i>
                </div>
            </div>

            <ul class="sidebar-nav">
                {{-- dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="ajax-link nav-link active">
                        <div class="nav-link-content">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>
                </li>
                {{-- rentals --}}
                <li class="nav-item has-submenu">
                    <a href="{{ route('rental_applications.index') }}" class="ajax-link nav-link"
                        onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-buildings"></i>
                            <span>Rental</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('properties.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-house-check"></i> Properties
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-badge"></i> Rental owners
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-fill-check"></i> Tenants
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-calendar2-week"></i> Events
                        </a>
                    </div>
                    <div class="floating-menu">
                         <a href="{{ route('properties.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-house-check"></i> Properties
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-badge"></i> Rental owners
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-fill-check"></i> Tenants
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-calendar2-week"></i> Events
                        </a>
                    </div>
                </li>

                {{-- Leasing --}}
                <li class="nav-item has-submenu">
                    <a href="#" class="ajax-link nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-pencil-square"></i>
                            <span>Leasing</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('rental_applications.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-clipboard-check"></i> Applications
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-lines-fill"></i> Appliants
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-journal-check"></i> Leasing
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('rental_applications.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-clipboard-check"></i> Applications
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-lines-fill"></i> Appliants
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-journal-check"></i> Leasing
                        </a>
                    </div>
                </li>

                {{-- Files --}}
                <li class="nav-item">
                    <a href="{{ route('file-center.index') }}" class="ajax-link nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-folder2-open"></i>
                            <span>Files</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Maintenance --}}
                <li class="nav-item has-submenu">
                    <a href="#" class="ajax-link nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-tools"></i>
                            <span>Maintenance</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('rental_applications.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-wrench"></i> Work orders
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-hammer"></i> Repairs
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-people"></i> Vendors
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('rental_applications.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-clipboard-check"></i> Applications
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-person-lines-fill"></i> Appliants
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-journal-check"></i> Leasing
                        </a>
                    </div>
                </li>

                <li class="nav-item has-submenu" >
                    <a href="#" class="ajax-link nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-graph-up"></i>
                            <span>Financial</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#" class="ajax-link submenu-item" >
                            <i class="bi bi-arrow-up-circle"></i> Income
                        </a>
                        <a href="#" class="ajax-link submenu-item" >
                            <i class="bi bi-arrow-down-circle"></i> Expense
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-file-earmark-bar-graph"></i> Reportss
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#" class="ajax-link submenu-item" >
                            <i class="bi bi-arrow-up-circle"></i> 收入管理
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-arrow-down-circle"></i> 支出管理
                        </a>
                        <a href="#" class="ajax-link submenu-item">
                            <i class="bi bi-file-earmark-bar-graph"></i> 财务报表
                        </a>
                    </div>
                </li>

                {{-- User --}}
                <li class="nav-item has-submenu">
                    <a href="#" class="ajax-link nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-building"></i>
                            <span>User</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('users.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-people"></i> Users
                        </a>
                        <a href="{{ route('roles.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-person-gear"></i> Roles
                        </a>
                        <a href="{{ route('permissions.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-shield-lock"></i> Permissions
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('users.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-people"></i> Users
                        </a>
                        <a href="{{ route('roles.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-person-gear"></i> Roles
                        </a>
                        <a href="{{ route('permissions.index') }}" class="ajax-link submenu-item">
                            <i class="bi bi-shield-lock"></i> Permissions
                        </a>
                    </div>
                </li>

                {{-- Trash --}}
                <li class="nav-item">
                    <a href="#" class="ajax-link nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-trash3"></i>
                            <span>Trash</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="nav-item">
                    <a href="#" class="ajax-link nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-gear"></i>
                            <span>Setting</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf </form>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="ajax-link nav-link">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
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
                {{-- <h1>{{ __('passwords.reset') }}</h1><span class="text-muted small">语言：{{ app()->getLocale() }}</span> --}}
                {{-- <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-house-door-fill fs-5 text-primary"></i>
                    <span class="fw-bold">{{ __('Welcome to the Property Management System') }}</span>
                </div> --}}
                <div class="search-container">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="搜索房源、租户、合同..." />
                    </div>

                <!-- 右侧功能区 -->
                <div class="d-flex align-items-center gap-4">
                    <!-- 消息图标 -->
                    {{-- <a href="#" class="ajax-link text-dark position-relative d-inline-block"
                        style="line-height: 1;">
                        <i class="bi bi-bell" style="font-size: 1.2rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger text-white"
                            style="font-size: 0.6rem; padding: 0.25em 0.45em; border-radius: 999px;">
                            5
                        </span>
                    </a> --}}

                    <button class="notification-btn" onclick="toggleNotifications()">
                        <i class="bi bi-bell" style="font-size: 1.2rem;"></i>
                        <span class="notification-badge">3</span>
                    </button>



                    <!-- 系统设置 -->
                    {{-- <a href="{{ route('settings.index') }}" class="ajax-link text-dark"> --}}
                    <a href="/" class="ajax-link text-dark">
                        <i class="bi bi-gear fs-5"></i>
                    </a>

                    <!-- 语言切换 -->
                    <div class="dropdown">
                        <a class="ajax-link dropdown-toggle text-dark" href="#" role="button"
                            data-bs-toggle="dropdown">
                            🌐 {{ app()->getLocale() === 'zh_CN' ? '中文' : 'English' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="ajax-link dropdown-item"
                                    href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="ajax-link dropdown-item" href="{{ route('lang.switch', 'zh') }}">中文</a>
                            </li>
                        </ul>
                    </div>

                    <!-- 用户头像与名称 -->
                    <div class="dropdown">
                        <a class="ajax-link d-flex align-items-center text-dark dropdown-toggle" href="#"
                            role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}" alt="Avatar"
                                class="rounded-circle me-2" width="32" height="32">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- <li><a class="ajax-link dropdown-item" href="{{ route('profile') }}">{{ __('个人资料') }}</a></li> --}}
                            <li><a class="ajax-link dropdown-item" href="/">{{ __('个人资料') }}</a></li>
                            <li><a class="ajax-link dropdown-item" href="#"
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

     <!-- 通知弹窗 -->
    <div class="notification-popup" id="notificationPopup">
    <div class="notification-header">
        <h6 class="mb-0">Notification Center</h6>
        <button class="btn-close btn-close-white" onclick="toggleNotifications()"></button>
    </div>
    <div class="notification-list">
        <div class="notification-item unread">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-primary"></i>
                <div>
                    <div class="fw-medium">New Rental Application</div>
                    <div class="text-muted small">Zhang San submitted a rental application - 5 minutes ago</div>
                </div>
            </div>
        </div>
        <div class="notification-item">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success"></i>
                <div>
                    <div class="fw-medium">System Backup Completed</div>
                    <div class="text-muted small">Data backup successfully completed - 1 hour ago</div>
                </div>
            </div>
        </div>
        <div class="notification-item">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                <div>
                    <div class="fw-medium">Maintenance Reminder</div>
                    <div class="text-muted small">Property A-101 requires maintenance - 2 hours ago</div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let notificationVisible = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const icon = sidebar.querySelector('.sidebar-toggle i');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            icon.classList.toggle('bi-chevron-left');
            icon.classList.toggle('bi-chevron-right');

            if (sidebar && sidebar.classList.contains('collapsed')) {
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
            }

        }

        // 切换子菜单
        function toggleSubmenu(element) {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('collapsed')) {
                return; // 收缩状态下不执行
            }

            const parent = element.closest('.nav-item');
            const isActive = parent.classList.contains('active');

            // 清除所有 nav-item 的 active
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            // 如果原来没激活，则激活它
            if (!isActive) {
                parent.classList.add('active');
            }
        }

        // 切换通知面板
        function toggleNotifications() {
            const popup = document.getElementById('notificationPopup');
            notificationVisible = !notificationVisible;
            
            if (notificationVisible) {
                popup.classList.add('show');
            } else {
                popup.classList.remove('show');
            }
        }
    </script>

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




    <script>
        $(document).ready(function() {
            function loadContent(url, pushState = true) {
                $.ajax({
                    url: url,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(data) {
                        const dom = $('<div>').append($.parseHTML(data));
                        const newContent = dom.find('#main-content').html();
                        if (newContent) {
                            $('#main-content').html(newContent);
                            if (pushState) history.pushState(null, '', url);
                        }
                    },
                    error: function() {
                        $('#main-content').html('<div class="alert alert-danger">加载失败，请稍后再试。</div>');
                    }
                });
            }

            $(document).on('click', '.ajax-link', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                loadContent(url);

                // 仅高亮当前点击项
                $('.ajax-link').removeClass('active');
                $(this).addClass('active');
            });

            window.addEventListener('popstate', function() {
                loadContent(location.href, false);
            });
        });
    </script>

    @stack('scripts') {{-- ✅ 加在这里，让 FilePond 初始化代码生效 --}}

</body>

</html>
