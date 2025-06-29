<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', '物业管理系统') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 220px;
            background-color: #0d3c61;
            color: white;
            flex-shrink: 0;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #155a8a;
        }
        .main-content {
            flex-grow: 1;
            background: #f8f9fa;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center py-3">{{ config('app.name') }}</h4>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('properties.index') }}" class="{{ request()->routeIs('properties.*') ? 'active' : '' }}">房源管理</a>
        <a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.*') ? 'active' : '' }}">租赁申请</a>
        {{-- 后续可加更多菜单 --}}
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
