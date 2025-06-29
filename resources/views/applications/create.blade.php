@extends('layouts.app')

@section('content')
<div class="container">
    <h3>申请租赁：{{ $property->title }}</h3>

    <form method="POST" action="{{ route('applications.store', $property) }}">
        @csrf
        <div class="mb-3">
            <label>姓名</label>
            <input type="text" name="applicant_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>联系电话</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>起始日期</label>
            <input type="date" name="start_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>结束日期</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>备注</label>
            <textarea name="message" class="form-control" rows="3"></textarea>
        </div>

        <button class="btn btn-primary">提交申请</button>
    </form>
</div>
@endsection
