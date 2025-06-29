@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">编辑房源</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('properties.partials.form')
</div>
@endsection

{{-- 关键：确保 styles 和 scripts 被注入 --}}
@push('styles')
    @stack('styles')
@endpush

@push('scripts')
    @stack('scripts')
@endpush
