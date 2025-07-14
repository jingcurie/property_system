@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-card-checklist me-1"></i> 查看租赁申请</h2>

    <div class="card mb-4">
        <div class="card-header fw-bold">申请信息</div>
        <div class="card-body">
            <p><strong>申请编号：</strong> {{ $rentalApplication->application_code }}</p>
            <p><strong>状态：</strong> {{ $rentalApplication->status }}</p>
            <p><strong>申请时间：</strong> {{ $rentalApplication->submitted_at }}</p>
            <p><strong>审核时间：</strong> {{ $rentalApplication->reviewed_at }}</p>
            <p><strong>审核人：</strong> {{ optional($rentalApplication->reviewer)->name ?? 'N/A' }}</p>
            <p><strong>备注：</strong> {{ $rentalApplication->notes }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">申请人信息</div>
        <div class="card-body">
            @php $applicant = $rentalApplication->applicants->first(); @endphp
            @if($applicant)
                <p><strong>姓名：</strong> {{ $applicant->full_name }}</p>
                <p><strong>电话：</strong> {{ $applicant->phone }}</p>
                <p><strong>邮箱：</strong> {{ $applicant->email }}</p>
                <p><strong>地址：</strong> {{ $applicant->address_line1 }} {{ $applicant->address_line2 }}, {{ $applicant->city }}, {{ $applicant->state }} {{ $applicant->zip_code }}, {{ $applicant->country }}</p>
            @else
                <p class="text-muted">无申请人信息</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">就业信息</div>
        <div class="card-body">
            @php $employment = $rentalApplication->applicants->first()->employmentDetail ?? null; @endphp
            @if($employment)
                <p><strong>公司：</strong> {{ $employment->employer_name }}</p>
                <p><strong>职位：</strong> {{ $employment->job_title }}</p>
                <p><strong>月收入：</strong> ${{ number_format($employment->monthly_income, 2) }}</p>
            @else
                <p class="text-muted">无就业信息</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">授权与同意</div>
        <div class="card-body">
            @php $consent = $rentalApplication->consent; @endphp
            @if($consent)
                <p><strong>信用查询同意：</strong> {{ $consent->credit_check_consent ? '是' : '否' }}</p>
                <p><strong>背景调查同意：</strong> {{ $consent->background_check_consent ? '是' : '否' }}</p>
                <p><strong>签署提供方：</strong> {{ $consent->esignature_provider }}</p>
                <p><strong>签署编号：</strong> {{ $consent->esignature_id }}</p>
            @else
                <p class="text-muted">无授权信息</p>
            @endif
        </div>
    </div>

    <a href="{{ route('rental_applications.index') }}" class="btn btn-secondary">返回列表</a>
</div>
@endsection
