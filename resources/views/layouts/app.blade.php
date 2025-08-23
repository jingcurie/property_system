<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', ut('layout.default_title')) }}</title>

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
    <link href="{{ asset('css/ownerInfoCard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notification-panel.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <div id="sidebar" class="sidebar d-flex flex-column">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-logo">
                    <i class="bi bi-house-door-fill fs-4 me-2"></i>
                    <span class="app-name d-none d-md-inline">{{ config('app.name', ut('layout.app_name')) }}</span>
                </a>

                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-chevron-left"></i>
                </div>
            </div>

            <ul class="sidebar-nav">
                {{-- dashboard - 没有子菜单的主菜单 --}}
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        onclick="handleMainMenuClick(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-speedometer2"></i>
                            <span>{{ ut('menu.dashboard') }}</span>
                        </div>
                    </a>
                </li>

                {{-- rentals - 有子菜单的主菜单 --}}
                <li class="nav-item has-submenu {{ request()->routeIs('properties.*', 'owners.*', 'tenants.*', 'events.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-buildings"></i>
                            <span>{{ ut('menu.rental') }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('properties.index') }}"
                            class="submenu-item {{ request()->routeIs('properties.index') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-house-check"></i> {{ ut('menu.properties') }}
                        </a>
                        <a href="{{ route('owners.index') }}" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-person-badge"></i> {{ ut('menu.rental_owners') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-person-fill-check"></i> {{ ut('menu.tenants') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-calendar2-week"></i> {{ ut('menu.events') }}
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('properties.index') }}" 
                            class="submenu-item {{ request()->routeIs('properties.index') ? 'active' : '' }}"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-house-check"></i> {{ ut('menu.properties') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-person-badge"></i> {{ ut('menu.rental_owners') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-person-fill-check"></i> {{ ut('menu.tenants') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-calendar2-week"></i> {{ ut('menu.events') }}
                        </a>
                    </div>
                </li>

                {{-- Leasing --}}
                <li class="nav-item has-submenu {{ request()->routeIs('rental_applications.*', 'applicants.*', 'leases.*', 'draft_leases.*', 'lease_renewals.*', 'active_leases.*', 'terminated_leases.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-pencil-square"></i>
                            <span>{{ ut('menu.leasing') }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('rental_applications.index') }}"
                            class="submenu-item {{ request()->routeIs('rental_applications.index') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-clipboard-check"></i> {{ ut('menu.applications') }}
                        </a>
                        <a href="{{ route('applicants.index') }}"
                            class="submenu-item {{ request()->routeIs('applicants.index') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-person-lines-fill"></i> {{ ut('menu.applicants') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.draft_leases') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.lease_renewals') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.active_leases') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.terminated_leases') }}
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('rental_applications.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-clipboard-check"></i> {{ ut('menu.applications') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-person-lines-fill"></i> {{ ut('menu.applicants') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.draft_leases') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.lease_renewals') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.active_leases') }}
                        </a>
                        <a href="{{ route('leases.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-journal-check"></i> {{ ut('menu.terminated_leases') }}
                        </a>
                    </div>
                </li>

                {{-- Files --}}
                <li class="nav-item {{ request()->routeIs('file-center.*') ? 'active' : '' }}">
                    <a href="{{ route('file-center.index') }}"
                        class="nav-link {{ request()->routeIs('file-center.index') ? 'active' : '' }}"
                        onclick="handleMainMenuClick(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-folder2-open"></i>
                            <span>{{ ut('menu.files') }}</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Maintenance --}}
                <li class="nav-item has-submenu {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-tools"></i>
                            <span>{{ ut('menu.maintenance') }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-wrench"></i> {{ ut('menu.work_orders') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-hammer"></i> {{ ut('menu.repairs') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-people"></i> {{ ut('menu.vendors') }}
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-wrench"></i> {{ ut('menu.work_orders') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-hammer"></i> {{ ut('menu.repairs') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-people"></i> {{ ut('menu.vendors') }}
                        </a>
                    </div>
                </li>

                {{-- Financial --}}
                <li class="nav-item has-submenu {{ request()->routeIs('financial.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-graph-up"></i>
                            <span>{{ ut('menu.financial') }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-arrow-up-circle"></i> {{ ut('menu.income') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-arrow-down-circle"></i> {{ ut('menu.expense') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleSubmenuClick(this)">
                            <i class="bi bi-file-earmark-bar-graph"></i> {{ ut('menu.reports') }}
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-arrow-up-circle"></i> {{ ut('menu.income') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-arrow-down-circle"></i> {{ ut('menu.expense') }}
                        </a>
                        <a href="#" class="submenu-item" onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-file-earmark-bar-graph"></i> {{ ut('menu.reports') }}
                        </a>
                    </div>
                </li>

                {{-- User --}}
                <li
                    class="nav-item has-submenu {{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="nav-link" onclick="toggleSubmenu(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-building"></i>
                            <span>{{ ut('menu.user') }}</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="{{ route('users.index') }}"
                            class="submenu-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-people"></i> {{ ut('menu.users') }}
                        </a>
                        <a href="{{ route('roles.index') }}"
                            class="submenu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-person-gear"></i> {{ ut('menu.roles') }}
                        </a>
                        <a href="{{ route('permissions.index') }}"
                            class="submenu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                            onclick="handleSubmenuClick(this)">
                            <i class="bi bi-shield-lock"></i> {{ ut('menu.permissions') }}
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="{{ route('users.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-people"></i> {{ ut('menu.users') }}
                        </a>
                        <a href="{{ route('roles.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-person-gear"></i> {{ ut('menu.roles') }}
                        </a>
                        <a href="{{ route('permissions.index') }}" class="submenu-item"
                            onclick="handleFloatingMenuClick(this)">
                            <i class="bi bi-shield-lock"></i> {{ ut('menu.permissions') }}
                        </a>
                    </div>
                </li>

                {{-- Trash --}}
                <li class="nav-item {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                    <a href="{{ route('trash.index') }}"
                        class="nav-link {{ request()->routeIs('trash.*') ? 'active' : '' }}"
                        onclick="handleMainMenuClick(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-trash3"></i>
                            <span>{{ ut('menu.trash') }}</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link" onclick="handleMainMenuClick(this)">
                        <div class="nav-link-content">
                            <i class="bi bi-gear"></i>
                            <span>{{ ut('menu.setting') }}</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>{{ ut('menu.logout') }}</span>
                        </div>
                        <i class="arrow-placeholder"></i>
                    </a>
                </li>

                {{-- Language Switch Links (for mobile) --}}
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="{{ route('lang.switch', 'en') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-translate"></i>
                            <span>English</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="{{ route('lang.switch', 'zh-CN') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-translate"></i>
                            <span>中文</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div id="main-content" class="main-content flex-grow-1">
            <!-- 美化后的顶部导航栏 -->
            <nav
                class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm w-100 d-flex justify-content-between align-items-center px-4 py-2">
                <!-- 左侧欢迎区域 -->
                <div class="d-flex align-items-center">
                    <div class="welcome-section">
                        <h6 class="mb-0 text-primary fw-bold">{{ ut('layout.welcome_message') }}</h6>
                        <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
                    </div>
                </div>

                <!-- 中间搜索区域 (可选) -->
                <div class="search-container d-none d-lg-flex">
                    <div class="position-relative">
                        <i
                            class="bi bi-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                        <input type="text" class="form-control ps-5 border-0 bg-light rounded-pill"
                            placeholder="{{ ut('layout.search_placeholder') }}" style="width: 300px;">
                    </div>
                </div>

                <!-- 右侧功能区 -->
                <div class="d-flex align-items-center gap-3">
                    <!-- 分隔线 -->
                    <div class="vr d-none d-md-block" style="height: 30px;"></div>

                    <!-- 通知按钮 -->
                    <div class="position-relative">
                        <button class="btn btn-light rounded-circle p-1 border-0 shadow-sm"
                            onclick="toggleNotificationPanel()" style="width: 38px; height: 38px;">
                            <i class="bi bi-bell fs-6" style="transform: translate(50%, 0%) !important;"></i>
                            <span id="notification_amount"
                                class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill"
                                style="transform: translate(-50%, -25%) !important; padding:0.2rem 0.4rem; font-size:0.9rem !important"></span>
                        </button>
                    </div>

                    <!-- 系统设置 -->
                    <a href="/" class="btn btn-light rounded-circle p-1 border-0 shadow-sm"
                        title="{{ ut('layout.settings') }}" style="width: 38px; height: 38px;">
                        <i class="bi bi-gear fs-6" style="transform: translate(50%, 0%) !important;"></i>
                    </a>

                    <!-- 语言切换 -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill px-3 py-1 border-0 shadow-sm dropdown-toggle"
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-2"></i>
                            {{ app()->getLocale() === 'zh' ? '中文' : 'English' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2">
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('lang.switch', 'en') }}">
                                    <i class="bi bi-flag-usa me-2"></i>English
                                </a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="{{ route('lang.switch', 'zh-CN') }}">
                                    <i class="bi bi-flag me-2"></i>中文
                                </a></li>
                        </ul>
                    </div>

                    <!-- 用户头像与名称 -->
                    <div class="dropdown">
                        <button
                            class="btn btn-light rounded-pill d-flex align-items-center px-3 py-1 border-0 shadow-sm dropdown-toggle"
                            type="button" data-bs-toggle="dropdown">
                            <img src="avatars/{{ Auth::user()->avatar ?? '/images/default-avatar.png' }}"
                                alt="Avatar" class="rounded-circle me-2" width="26" height="26">
                            <span class="fw-medium">{{ Str::limit(Auth::user()->name, 10) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2">
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex align-items-center">
                                    {{-- <img src="{{ Auth::user()->avatar_url ?? '/images/default-avatar.png' }}"
                                        alt="Avatar" class="rounded-circle me-2" width="40" height="40"> --}}
                                    <div>
                                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><a class="dropdown-item rounded-2 py-2" href="/">
                                    <i class="bi bi-person me-2"></i>{{ ut('layout.profile') }}
                                </a></li>
                            <li><a class="dropdown-item rounded-2 py-2" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>{{ ut('layout.logout') }}
                                </a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div id="alert-container" class="alerts-wrapper mx-3 m-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center py-2 px-3"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"
                            aria-label="{{ ut('layout.close') }}"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center py-2 px-3"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"
                            aria-label="{{ ut('layout.close') }}"></button>
                    </div>
                @endif
            </div>

            <main>
                @yield('content')
            </main>

            <footer class="text-center text-muted py-4 border-top">
                &copy; {{ date('Y') }} {{ ut('layout.footer_text') }}
            </footer>
        </div>
    </div>

    @include('layouts.partials.notification-panel', [
    'notifications' => \App\Models\Notification::latest()->take(10)->get()
])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 页面加载时恢复状态
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, restoring states...'); // 调试用
            restoreSidebarState();
            handleMenuStateOnLoad();
            adjustMainContentLayout();
        });

        // 窗口大小改变时重新调整布局
        window.addEventListener('resize', function() {
            adjustMainContentLayout();
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const icon = sidebar.querySelector('.sidebar-toggle i');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            icon.classList.toggle('bi-chevron-left');
            icon.classList.toggle('bi-chevron-right');

            // 保存状态到 sessionStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            sessionStorage.setItem('sidebar-collapsed', isCollapsed);

            if (isCollapsed) {
                // 收缩状态：强制收缩所有子菜单，但保持主菜单高亮
                document.querySelectorAll('.nav-item.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            } else {
                // 展开状态：恢复之前记忆的菜单状态
                restoreMenuState();
            }

            adjustMainContentLayout();
        }

        // 切换子菜单 - 只在展开状态下工作
        function toggleSubmenu(element) {
            const sidebar = document.querySelector('.sidebar');

            // 收缩状态下不展开子菜单
            if (sidebar.classList.contains('collapsed')) {
                return;
            }

            const parent = element.closest('.nav-item');
            const isActive = parent.classList.contains('active');

            // 只清除其他主菜单的 active，不清除自己
            document.querySelectorAll('.nav-item').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('active');
                }
            });

            // 清除所有子菜单项的 active
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.classList.remove('active');
            });

            // 切换当前主菜单的展开状态
            if (!isActive) {
                parent.classList.add('active');
                // 保存展开的主菜单
                const menuText = parent.querySelector('.nav-link span').textContent;
                sessionStorage.setItem('expanded-menu', menuText);
            } else {
                parent.classList.remove('active');
                // 收缩子菜单，清除记忆
                sessionStorage.removeItem('expanded-menu');
                sessionStorage.removeItem('active-submenu');
            }
        }

        // 处理主菜单点击（没有子菜单的）
        function handleMainMenuClick(element) {
            console.log('Main menu clicked:', element); // 调试用

            // 清除所有菜单项的 active 状态
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            // 清除所有子菜单项的 active 状态
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.classList.remove('active');
            });

            // 高亮当前点击的主菜单项
            const parent = element.closest('.nav-item');
            parent.classList.add('active');

            // 保存当前选中的主菜单到 sessionStorage
            const menuText = parent.querySelector('.nav-link span').textContent.trim();
            sessionStorage.setItem('active-main-menu', menuText);

            // 清除子菜单相关的记忆
            sessionStorage.removeItem('expanded-menu');
            sessionStorage.removeItem('active-submenu');

            console.log('Saved main menu:', menuText); // 调试用
        }


        // 处理子菜单点击
        function handleSubmenuClick(element) {
            // 清除所有子菜单的 active
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.classList.remove('active');
            });

            // 高亮当前子菜单项
            element.classList.add('active');

            // 保存当前选中的子菜单项
            const submenuText = element.textContent.trim();
            sessionStorage.setItem('active-submenu', submenuText);

            // 保存展开的主菜单
            const parentMenu = element.closest('.nav-item').querySelector('.nav-link span').textContent;
            sessionStorage.setItem('expanded-menu', parentMenu);

            // 清除主菜单记忆
            sessionStorage.removeItem('active-main-menu');
        }

        // 处理浮动菜单点击（收缩状态下）
        function handleFloatingMenuClick(element) {
            const sidebar = document.querySelector('.sidebar');

            if (sidebar.classList.contains('collapsed')) {
                // 收缩状态：记忆点击的项目，但不展开
                const submenuText = element.textContent.trim();
                sessionStorage.setItem('active-submenu', submenuText);

                // 记忆应该展开的主菜单
                const parentMenu = element.closest('.nav-item').querySelector('.nav-link span').textContent;
                sessionStorage.setItem('expanded-menu', parentMenu);

                // 清除主菜单记忆
                sessionStorage.removeItem('active-main-menu');
            }
        }

        // 清除所有 active 状态
        function clearAllActive() {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.classList.remove('active');
            });
        }

        // 页面加载时处理菜单状态
        function handleMenuStateOnLoad() {
            const sidebar = document.getElementById('sidebar');
            const isCollapsed = sidebar.classList.contains('collapsed');

            if (isCollapsed) {
                // 收缩状态：强制收缩所有子菜单，移除所有 active
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
            } else {
                // 展开状态：恢复记忆的菜单状态
                restoreMenuState();
            }
        }

        // 恢复侧边栏状态
        function restoreSidebarState() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const icon = sidebar.querySelector('.sidebar-toggle i');

            // 🎯 从 sessionStorage 读取状态，如果是 null 则默认展开
            const savedState = sessionStorage.getItem('sidebar-collapsed');
            const isCollapsed = savedState === 'true'; // 只有明确是 'true' 才收缩

            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                icon.classList.remove('bi-chevron-left');
                icon.classList.add('bi-chevron-right');
            } else {
                // 🎯 确保默认是展开状态
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('collapsed');
                icon.classList.add('bi-chevron-left');
                icon.classList.remove('bi-chevron-right');
            }
        }

        // 恢复菜单状态
        function restoreMenuState() {
            // 从 sessionStorage 读取状态
            const expandedMenu = sessionStorage.getItem('expanded-menu');
            const activeSubmenu = sessionStorage.getItem('active-submenu');
            const activeMainMenu = sessionStorage.getItem('active-main-menu');
            console.log(expandedMenu);
            console.log(activeSubmenu);
            console.log(activeMainMenu);

            console.log('Restoring state:', {
                expandedMenu,
                activeSubmenu,
                activeMainMenu
            }); // 调试用

            if (expandedMenu && activeSubmenu) {
                // 恢复展开的子菜单和高亮的子菜单项
                document.querySelectorAll('.nav-item').forEach(item => {
                    const menuText = item.querySelector('.nav-link span')?.textContent.trim();
                    if (menuText === expandedMenu) {
                        item.classList.add('active');

                        // 高亮对应的子菜单项
                        item.querySelectorAll('.submenu-item').forEach(subItem => {
                            if (subItem.textContent.trim() === activeSubmenu) {
                                subItem.classList.add('active');
                            }
                        });
                    }
                });
            } else if (activeMainMenu) {
                // 恢复高亮的主菜单项（没有子菜单的）
                document.querySelectorAll('.nav-item').forEach(item => {
                    const menuText = item.querySelector('.nav-link span')?.textContent.trim();
                    if (menuText === activeMainMenu) {
                        item.classList.add('active');
                    }
                });
            }
        }

        // 调整主内容区域布局
        function adjustMainContentLayout() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const isCollapsed = sidebar.classList.contains('collapsed');
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                // 移动端：菜单覆盖模式
                if (isCollapsed) {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                } else {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                }
            } else {
                // 桌面端：菜单推挤模式
                if (isCollapsed) {
                    mainContent.style.marginLeft = '60px';
                    mainContent.style.width = 'calc(100% - 60px)';
                } else {
                    mainContent.style.marginLeft = '280px';
                    mainContent.style.width = 'calc(100% - 250px)';
                }
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

    <!-- 统一翻译系统 -->
    <script src="{{ asset('js/translations.js') }}"></script>
    <script>
        // 初始化JavaScript翻译
        window.Translations = @json(get_js_translations());
        initTranslations(window.Translations);
        
        // 兼容旧的APP_TEXTS
        window.APP_TEXTS = {
            successTitle: '{{ ut('common.success') }}',
            successMessage: '{{ ut('common.success') }}',
            errorTitle: '{{ ut('common.error') }}',
            errorMessage: '{{ ut('common.error') }}',
            confirmTitle: '{{ ut('common.confirm') }}',
            confirmMessage: '{{ ut('common.confirm') }}',
            confirm: '{{ ut('common.confirm') }}',
            cancel: '{{ ut('common.cancel') }}'
        };
    </script>

    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/noticification.js') }}"></script>
    <script src="{{ asset('js/alert.js') }}"></script>

    @stack('scripts')

</body>

</html>
