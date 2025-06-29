@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark">房源详情</h2>
        <div>
            <a href="{{ route('properties.edit', $property->property_id) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-pencil-square me-1"></i> 编辑
            </a>
            <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> 返回列表
            </a>
        </div>
    </div>

    @include('properties.partials.show_sections')
</div>
@endsection
