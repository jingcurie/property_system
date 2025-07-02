@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row">
            {{-- 左侧角色信息卡 --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">{{ $role->name }}</h4>
                        <hr>
                        <h6>Permissions</h6>
                        <ul class="list-unstyled">
                            @foreach ($role->permissions as $permission)
                                <li><i class="bi bi-check-circle text-success me-2"></i>
                                    {{ $permission->description ?? $permission->name }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary mt-3">Edit Role</a>
                    </div>
                </div>
            </div>

            {{-- 右侧用户列表 --}}
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Users Assigned ({{ $users->total() }})</h5>

                        {{-- 批量操作条 --}}
                        <div id="batch-actions" class="d-none mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div></div>
                                <form id="batch-remove-form" method="POST"
                                    action="{{ route('roles.users.batchDelete', $role) }}">
                                    @csrf
                                    <input type="hidden" name="user_ids" id="batch-user-ids">
                                    <span id="selected-count">0</span> user(s) selected &nbsp;
                                    <button type="submit" class="btn btn-danger btn-sm">Remove Selected</button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>User</th>
                                        <th>Joined Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($user->avatar)
                                                        <img src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/default.png') }}"
                                                            alt="头像" class="rounded-circle avatar-img">
                                                    @else
                                                        <div class="bg-secondary text-white rounded-circle text-center me-2"
                                                            style="width:40px; height:40px; line-height:40px;">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div>{{ $user->name }}</div>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y, g:i a') }}</td>
                                            <td class="text-end">
                                                <div class="dropdown d-flex justify-content-end">
                                                    {{-- <button class="btn btn-light btn-sm" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button> --}}
                                                    <button type="button" class="btn-action-menu" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <form method="POST"
                                                                action="{{ route('roles.users.remove', [$role, $user]) }}"
                                                                onsubmit="return confirm('Remove user from this role?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="bi bi-trash me-2"></i> Remove
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No users assigned to this
                                                role.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- 分页 --}}
                        @include('components._pagination', ['paginator' => $users])
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const selectAll = document.getElementById('select-all');
        const batchActions = document.getElementById('batch-actions');
        const selectedCount = document.getElementById('selected-count');
        const batchUserIds = document.getElementById('batch-user-ids');

        function updateBatchUI() {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            if (selected.length > 0) {
                batchActions.classList.remove('d-none');
                selectedCount.innerText = selected.length;
                batchUserIds.value = selected.map(cb => cb.value).join(',');
            } else {
                batchActions.classList.add('d-none');
                selectedCount.innerText = 0;
                batchUserIds.value = '';
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateBatchUI));

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBatchUI();
        });
    </script>
@endpush
