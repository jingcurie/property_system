@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card permission-form-card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add a permission</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Permission Key <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="例如：user.view" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" id="description" class="form-control" placeholder="例如：查看用户">
                    </div>

                    <!-- 隐藏 guard_name 字段，直接默认 web -->
                    <input type="hidden" name="guard_name" value="web">

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection