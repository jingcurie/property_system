@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">控制面板 Dashboard</h1>
            <p class="text-muted">欢迎回来，{{ Auth::user()->name }}！</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- 模块 1：统计卡片 -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">总用户</h5>
                    <p class="card-text fs-4 fw-bold">123</p>
                    <i class="lucide lucide-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">房源数量</h5>
                    <p class="card-text fs-4 fw-bold">87</p>
                    <i class="lucide lucide-home"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">活跃租客</h5>
                    <p class="card-text fs-4 fw-bold">56</p>
                    <i class="lucide lucide-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">待审核申请</h5>
                    <p class="card-text fs-4 fw-bold">4</p>
                    <i class="lucide lucide-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 可继续添加图表、最近活动等内容 -->
</div>
@endsection
