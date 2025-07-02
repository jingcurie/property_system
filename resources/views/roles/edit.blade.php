@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Role</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Role name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Role Permissions</label>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">Administrator Access <span class="text-muted">Select all</span></label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Module</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Write</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(config('permission_groups') as $module => $permissions)
                                    <tr>
                                        <td class="fw-bold">{{ $module }}</td>
                                        @foreach(['Read', 'Write', 'Create', 'Delete'] as $type)
                                            @php
                                                $permKey = collect($permissions)->flip()->get($type);
                                            @endphp
                                            <td class="text-center">
                                                @if($permKey)
                                                    <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="form-check-input checkbox-permission" {{ in_array($permKey, $rolePermissions) ? 'checked' : '' }}>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Discard</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.checkbox-permission').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
