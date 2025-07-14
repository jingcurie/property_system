@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}" method="POST">
            @csrf
            @if(isset($role)) @method('PUT') @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Role name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" required
                               value="{{ old('name', $role->name ?? '') }}">
                    </div>
                </div>
            </div>

            {{-- 权限分组展示 --}}
            @include('roles.partials._permissions')

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Discard</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.select-all-group').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const group = this.dataset.group;
            document.querySelectorAll(`.group-${group}`).forEach(cb => cb.checked = this.checked);
        });
    });
</script>
@endpush
