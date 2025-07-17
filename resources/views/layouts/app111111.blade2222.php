<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="csrf-token-placeholder">
    <title>高级物业管理系统</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #161f2c;
            --dark-secondary: #1f2937;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 64px;
            --header-height: 70px;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-light: #cbd5e0;
            --text-dark: #1e293b;
            --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 10px 25px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-x: hidden;
        }

        /* 页面加载动画 */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .page-loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* 高级侧边栏 */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--dark-bg);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--glass-border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sidebar-logo i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .sidebar-toggle {
            position: absolute;
            top: 50%;
            right: -14px;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: var(--primary-color);
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-medium);
        }

        .sidebar:hover .sidebar-toggle {
            opacity: 1;
        }

        .sidebar-toggle:hover {
            background: var(--primary-dark);
            transform: translateY(-50%) scale(1.1);
        }

        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100% - var(--header-height));
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .nav-item {
            margin: 0.25rem 0.75rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-color);
            color: white;
            transform: translateX(4px);
            box-shadow: var(--shadow-medium);
        }

        .nav-link-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .nav-link:hover i {
            transform: scale(1.2);
        }

        .nav-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .nav-item.active .nav-arrow {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-top: 0.5rem;
        }

        .nav-item.active .submenu {
            max-height: 200px;
        }

        .submenu-item {
            padding: 0.5rem 1rem 0.5rem 3rem;
            color: var(--text-light);
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
            border-radius: 0.25rem;
            margin: 0.25rem 0;
        }

        .submenu-item:hover,
        .submenu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        /* 折叠状态的浮动菜单 */
        .sidebar.collapsed .nav-item {
            position: relative;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-arrow {
            display: none;
        }

        .sidebar.collapsed .nav-item:hover .floating-menu {
            display: block;
        }

        .floating-menu {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
            min-width: 200px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-heavy);
            padding: 0.5rem 0;
            z-index: 2000;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .floating-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .floating-menu a:hover {
            background: var(--primary-color);
            color: white;
        }

        /* 主内容区 */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* 顶部导航 */
        .top-navbar {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: var(--shadow-light);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* 搜索框 */
        .search-container {
            position: relative;
        }

        .search-input {
            width: 300px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            width: 400px;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        /* 通知按钮 */
        .notification-btn {
            position: relative;
            padding: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .notification-btn:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* 主题切换 */
        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* 用户菜单 */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-menu:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }

        /* 内容区域 */
        .content-area {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* 面包屑 */
        .breadcrumb-container {
            margin-bottom: 2rem;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.8);
        }

        .breadcrumb-item.active {
            color: white;
        }

        /* 卡片组件 */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-change.positive {
            color: var(--success-color);
        }

        .stat-change.negative {
            color: var(--danger-color);
        }

        /* 图表容器 */
        .chart-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .chart-title {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* 数据表格 */
        .data-table {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            overflow: hidden;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-title {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }
            
            .search-input {
                width: 200px;
            }
            
            .search-input:focus {
                width: 250px;
            }
            
            .navbar-actions {
                gap: 1rem;
            }
        }

        /* 动画效果 */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in-left {
            animation: slideInLeft 0.5s ease-in-out;
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        /* 快捷键提示 */
        .shortcut-hint {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .shortcut-hint.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* 实时状态指示器 */
        .status-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            color: white;
            font-size: 0.8rem;
            z-index: 1000;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success-color);
            animation: pulse 2s infinite;
        }

        /* 通知弹窗 */
        .notification-popup {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 320px;
            max-height: 400px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            transform: translateX(350px);
            transition: transform 0.3s ease;
            z-index: 2000;
        }

        .notification-popup.show {
            transform: translateX(0);
        }

        .notification-header {
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: background 0.2s ease;
        }

        .notification-item:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .notification-item.unread {
            background: rgba(59, 130, 246, 0.1);
        }

        /* 页脚 */
        .footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        /* 主题切换动画 */
        .theme-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body>
    <!-- 页面加载器 -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    <!-- 实时状态指示器 -->
    <div class="status-indicator">
        <div class="status-dot"></div>
        <span>系统运行正常</span>
    </div>

    <!-- 快捷键提示 -->
    <div class="shortcut-hint" id="shortcutHint">
        按 Ctrl+K 打开快速搜索
    </div>

    <!-- 通知弹窗 -->
    <div class="notification-popup" id="notificationPopup">
        <div class="notification-header">
            <h6 class="mb-0">通知中心</h6>
            <button class="btn-close btn-close-white" onclick="toggleNotifications()"></button>
        </div>
        <div class="notification-list">
            <div class="notification-item unread">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bell-fill text-primary"></i>
                    <div>
                        <div class="fw-medium">新的租赁申请</div>
                        <div class="text-muted small">张三提交了租赁申请 - 5分钟前</div>
                    </div>
                </div>
            </div>
            <div class="notification-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <div>
                        <div class="fw-medium">系统备份完成</div>
                        <div class="text-muted small">数据备份已成功完成 - 1小时前</div>
                    </div>
                </div>
            </div>
            <div class="notification-item">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    <div>
                        <div class="fw-medium">维护提醒</div>
                        <div class="text-muted small">房源 A-101 需要维护 - 2小时前</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <!-- 侧边栏 -->
        <div class="sidebar slide-in-left" id="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-logo">
                    <i class="bi bi-house-door-fill"></i>
                    <span>物业管理系统</span>
                </a>
                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-chevron-left"></i>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#dashboard" class="nav-link active" onclick="loadPage('dashboard')">
                        <div class="nav-link-content">
                            <i class="bi bi-speedometer2"></i>
                            <span>仪表盘</span>
                        </div>
                    </a>
                </div>

                <div class="nav-item" onclick="toggleSubmenu(this)">
                    <a href="#" class="nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-buildings"></i>
                            <span>租赁管理</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#properties" class="submenu-item" onclick="loadPage('properties')">
                            <i class="bi bi-house-check"></i> 房源管理
                        </a>
                        <a href="#owners" class="submenu-item" onclick="loadPage('owners')">
                            <i class="bi bi-person-badge"></i> 业主管理
                        </a>
                        <a href="#tenants" class="submenu-item" onclick="loadPage('tenants')">
                            <i class="bi bi-person-fill-check"></i> 租户管理
                        </a>
                        <a href="#events" class="submenu-item" onclick="loadPage('events')">
                            <i class="bi bi-calendar2-week"></i> 事件管理
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#properties">房源管理</a>
                        <a href="#owners">业主管理</a>
                        <a href="#tenants">租户管理</a>
                        <a href="#events">事件管理</a>
                    </div>
                </div>

                <div class="nav-item" onclick="toggleSubmenu(this)">
                    <a href="#" class="nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-pencil-square"></i>
                            <span>租赁业务</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#applications" class="submenu-item" onclick="loadPage('applications')">
                            <i class="bi bi-file-earmark-plus"></i> 租赁申请
                        </a>
                        <a href="#contracts" class="submenu-item" onclick="loadPage('contracts')">
                            <i class="bi bi-file-earmark-text"></i> 合同管理
                        </a>
                        <a href="#payments" class="submenu-item" onclick="loadPage('payments')">
                            <i class="bi bi-credit-card"></i> 支付管理
                        </a>
                        <a href="#inspections" class="submenu-item" onclick="loadPage('inspections')">
                            <i class="bi bi-search"></i> 房屋检查
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#applications">租赁申请</a>
                        <a href="#contracts">合同管理</a>
                        <a href="#payments">支付管理</a>
                        <a href="#inspections">房屋检查</a>
                    </div>
                </div>

                <div class="nav-item" onclick="toggleSubmenu(this)">
                    <a href="#" class="nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-tools"></i>
                            <span>维护管理</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#maintenance" class="submenu-item" onclick="loadPage('maintenance')">
                            <i class="bi bi-wrench"></i> 维护工单
                        </a>
                        <a href="#repairs" class="submenu-item" onclick="loadPage('repairs')">
                            <i class="bi bi-hammer"></i> 维修记录
                        </a>
                        <a href="#vendors" class="submenu-item" onclick="loadPage('vendors')">
                            <i class="bi bi-people"></i> 供应商管理
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#maintenance">维护工单</a>
                        <a href="#repairs">维修记录</a>
                        <a href="#vendors">供应商管理</a>
                    </div>
                </div>

                <div class="nav-item" onclick="toggleSubmenu(this)">
                    <a href="#" class="nav-link">
                        <div class="nav-link-content">
                            <i class="bi bi-graph-up"></i>
                            <span>财务管理</span>
                        </div>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <a href="#income" class="submenu-item" onclick="loadPage('income')">
                            <i class="bi bi-arrow-up-circle"></i> 收入管理
                        </a>
                        <a href="#expenses" class="submenu-item" onclick="loadPage('expenses')">
                            <i class="bi bi-arrow-down-circle"></i> 支出管理
                        </a>
                        <a href="#reports" class="submenu-item" onclick="loadPage('reports')">
                            <i class="bi bi-file-earmark-bar-graph"></i> 财务报表
                        </a>
                    </div>
                    <div class="floating-menu">
                        <a href="#income">收入管理</a>
                        <a href="#expenses">支出管理</a>
                        <a href="#reports">财务报表</a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="#analytics" class="nav-link" onclick="loadPage('analytics')">
                        <div class="nav-link-content">
                            <i class="bi bi-bar-chart"></i>
                            <span>数据分析</span>
                        </div>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="#settings" class="nav-link" onclick="loadPage('settings')">
                        <div class="nav-link-content">
                            <i class="bi bi-gear"></i>
                            <span>系统设置</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>

        <!-- 主内容区 -->
        <div class="main-content" id="mainContent">
            <!-- 顶部导航 -->
            <nav class="top-navbar">
                <div class="navbar-brand">
                    <i class="bi bi-house-door-fill"></i>
                    <span>物业管理系统</span>
                </div>

                <div class="navbar-actions">
                    <!-- 搜索框 -->
                    <div class="search-container">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="搜索房源、租户、合同..." />
                    </div>

                    <!-- 通知按钮 -->
                    <button class="notification-btn" onclick="toggleNotifications()">
                        <i class="bi bi-bell" style="font-size: 1.2rem;"></i>
                        <span class="notification-badge">3</span>
                    </button>

                    <!-- 主题切换 -->
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="bi bi-moon-stars"></i>
                        <span>暗色</span>
                    </button>

                    <!-- 用户菜单 -->
                    <div class="user-menu" onclick="toggleUserMenu()">
                        <img src="https://via.placeholder.com/32x32/667eea/ffffff?text=管" alt="用户头像" class="user-avatar">
                        <span>管理员</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
            </nav>

            <!-- 面包屑导航 -->
            <div class="content-area">
                <div class="breadcrumb-container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">首页</a></li>
                            <li class="breadcrumb-item active">仪表盘</li>
                        </ol>
                    </nav>
                </div>

                <!-- 页面内容 -->
                <div id="pageContent">
                    <!-- 仪表盘内容 -->
                    <div class="fade-in">
                        <!-- 统计卡片 -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-title">总房源数</div>
                                    <div class="stat-icon" style="background: var(--primary-color);">
                                        <i class="bi bi-house-fill text-white"></i>
                                    </div>
                                </div>
                                <div class="stat-value">156</div>
                                <div class="stat-change positive">
                                    <i class="bi bi-arrow-up"></i>
                                    <span>+12% 较上月</span>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-title">已出租</div>
                                    <div class="stat-icon" style="background: var(--success-color);">
                                        <i class="bi bi-check-circle-fill text-white"></i>
                                    </div>
                                </div>
                                <div class="stat-value">142</div>
                                <div class="stat-change positive">
                                    <i class="bi bi-arrow-up"></i>
                                    <span>+8% 较上月</span>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-title">空置房源</div>
                                    <div class="stat-icon" style="background: var(--warning-color);">
                                        <i class="bi bi-exclamation-triangle-fill text-white"></i>
                                    </div>
                                </div>
                                <div class="stat-value">14</div>
                                <div class="stat-change negative">
                                    <i class="bi bi-arrow-down"></i>
                                    <span>-5% 较上月</span>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-header">
                                    <div class="stat-title">月收入</div>
                                    <div class="stat-icon" style="background: var(--gradient-success);">
                                        <i class="bi bi-currency-dollar text-white"></i>
                                    </div>
                                </div>
                                <div class="stat-value">¥485,200</div>
                                <div class="stat-change positive">
                                    <i class="bi bi-arrow-up"></i>
                                    <span>+15% 较上月</span>
                                </div>
                            </div>
                        </div>

                        <!-- 图表区域 -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="chart-container">
                                    <div class="chart-header">
                                        <h5 class="chart-title">租金收入趋势</h5>
                                        <div>
                                            <select class="form-select form-select-sm" style="width: auto;">
                                                <option>最近12个月</option>
                                                <option>最近6个月</option>
                                                <option>最近3个月</option>
                                            </select>
                                        </div>
                                    </div>
                                    <canvas id="incomeChart" height="300"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="chart-container">
                                    <div class="chart-header">
                                        <h5 class="chart-title">房源状态分布</h5>
                                    </div>
                                    <canvas id="statusChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- 数据表格 -->
                        <div class="data-table">
                            <div class="table-header">
                                <h5 class="table-title">最近租赁活动</h5>
                                <div class="table-actions">
                                    <button class="btn-action btn-primary">
                                        <i class="bi bi-plus-circle"></i> 新增租赁
                                    </button>
                                    <button class="btn-action" style="background: var(--secondary-color); color: white;">
                                        <i class="bi bi-download"></i> 导出
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="color: white;">房源编号</th>
                                            <th style="color: white;">租户姓名</th>
                                            <th style="color: white;">租金</th>
                                            <th style="color: white;">租期</th>
                                            <th style="color: white;">状态</th>
                                            <th style="color: white;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color: rgba(255,255,255,0.9);">A-101</td>
                                            <td style="color: rgba(255,255,255,0.9);">张三</td>
                                            <td style="color: rgba(255,255,255,0.9);">¥3,500</td>
                                            <td style="color: rgba(255,255,255,0.9);">12个月</td>
                                            <td><span class="badge bg-success">已签约</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-light">查看</button>
                                                <button class="btn btn-sm btn-outline-light">编辑</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: rgba(255,255,255,0.9);">B-205</td>
                                            <td style="color: rgba(255,255,255,0.9);">李四</td>
                                            <td style="color: rgba(255,255,255,0.9);">¥4,200</td>
                                            <td style="color: rgba(255,255,255,0.9);">6个月</td>
                                            <td><span class="badge bg-warning">待签约</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-light">查看</button>
                                                <button class="btn btn-sm btn-outline-light">编辑</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="color: rgba(255,255,255,0.9);">C-308</td>
                                            <td style="color: rgba(255,255,255,0.9);">王五</td>
                                            <td style="color: rgba(255,255,255,0.9);">¥2,800</td>
                                            <td style="color: rgba(255,255,255,0.9);">24个月</td>
                                            <td><span class="badge bg-success">已签约</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-light">查看</button>
                                                <button class="btn btn-sm btn-outline-light">编辑</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="footer">
                <p>&copy; 2024 高级物业管理系统. 版权所有. | 技术支持：开发团队</p>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    <script>
        // 全局变量
        let isCollapsed = false;
        let currentTheme = 'light';
        let notificationVisible = false;

        // 页面加载完成后初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 隐藏加载器
            setTimeout(() => {
                document.getElementById('pageLoader').classList.add('hidden');
            }, 1000);

            // 初始化图表
            initializeCharts();

            // 显示快捷键提示
            setTimeout(() => {
                showShortcutHint();
            }, 2000);

            // 设置定时器更新状态
            setInterval(updateSystemStatus, 30000);
        });

        // 切换侧边栏
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggle = document.querySelector('.sidebar-toggle i');
            
            isCollapsed = !isCollapsed;
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                toggle.classList.replace('bi-chevron-left', 'bi-chevron-right');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('collapsed');
                toggle.classList.replace('bi-chevron-right', 'bi-chevron-left');
            }
        }

        // 切换子菜单
        function toggleSubmenu(element) {
            const isActive = element.classList.contains('active');
            
            // 关闭所有子菜单
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // 如果当前菜单不是激活状态，则激活它
            if (!isActive) {
                element.classList.add('active');
            }
        }

        // 加载页面内容
        function loadPage(page) {
            // 更新导航状态
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // 激活当前页面链接
            event.target.closest('.nav-link').classList.add('active');
            
            // 这里可以添加实际的页面加载逻辑
            console.log('Loading page:', page);
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

        // 切换主题
        function toggleTheme() {
            const body = document.body;
            const themeToggle = document.querySelector('.theme-toggle');
            
            if (currentTheme === 'light') {
                body.classList.add('dark-theme');
                themeToggle.innerHTML = '<i class="bi bi-sun"></i><span>明亮</span>';
                currentTheme = 'dark';
            } else {
                body.classList.remove('dark-theme');
                themeToggle.innerHTML = '<i class="bi bi-moon-stars"></i><span>暗色</span>';
                currentTheme = 'light';
            }
        }

        // 切换用户菜单
        function toggleUserMenu() {
            // 这里可以添加用户菜单的逻辑
            console.log('Toggle user menu');
        }

        

        // 显示快捷键提示
        function showShortcutHint() {
            const hint = document.getElementById('shortcutHint');
            hint.classList.add('show');
            
            setTimeout(() => {
                hint.classList.remove('show');
            }, 3000);
        }

        // 更新系统状态
        function updateSystemStatus() {
            // 这里可以添加实际的系统状态检查逻辑
            console.log('System status updated');
        }

        // 快捷键处理
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.querySelector('.search-input').focus();
            }
        });

        // 点击外部关闭通知面板
        document.addEventListener('click', function(e) {
            const popup = document.getElementById('notificationPopup');
            const button = document.querySelector('.notification-btn');
            
            if (notificationVisible && !popup.contains(e.target) && !button.contains(e.target)) {
                toggleNotifications();
            }
        });
    </script>
    
</body>
</html>