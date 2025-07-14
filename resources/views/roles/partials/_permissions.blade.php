<div class="row">
    @foreach ($permissions as $group => $perms)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $group }}</strong>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input select-all-group" data-group="{{ Str::slug($group) }}" id="selectAll_{{ $loop->index }}">
                        <label class="form-check-label" for="selectAll_{{ $loop->index }}">Select All</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($perms as $permission)
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           id="perm_{{ $permission->id }}"
                                           class="form-check-input permission-checkbox group-{{ Str::slug($group) }}"
                                           {{ in_array($permission->name, $rolePermissions ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->description ?? $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

