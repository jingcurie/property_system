@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">新增房源</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('properties.partials.form', ['property' => $property])

    @push('styles')
    <style>
        /* 紧凑样式用于包含水电费项目 checkbox */
        .utility-checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0.5rem 0;
        }
        .utility-checkbox-group .form-check {
            flex: 0 0 auto;
            margin-bottom: 0;
        }
    </style>
    @endpush
</div>
@endsection
