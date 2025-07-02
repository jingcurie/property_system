@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add a Role</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                {{-- Role Name --}}
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Role name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Role Permissions --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Role Permissions</label>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Administrator Access <span class="text-muted">Select all</span>
                        </label>
                    </div>

                    @php
                        $permissionGroups = config('permission_groups');
                    @endphp

                    <div class="table-responsive border rounded">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%">Module</th>
                                    <th style="width: 15%">Read</th>
                                    <th style="width: 15%">Write</th>
                                    <th style="width: 15%">Create</th>
                                    <th style="width: 15%">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissionGroups as $module => $permissions)
                                    <tr>
                                        <td class="fw-semibold align-middle">{{ $module }}</td>
                                        @foreach (['view', 'edit', 'create', 'delete'] as $action)
                                            @php
                                                $permKey = collect($permissions)->filter(function ($label, $key) use ($action) {
                                                    return str_ends_with($key, ".{$action}");
                                                })->keys()->first();
                                            @endphp
                                            <td class="text-center">
                                                @if ($permKey)
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permKey }}"
                                                            id="{{ $permKey }}">
                                                        <label class="form-check-label" for="{{ $permKey }}"></label>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Discard</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
