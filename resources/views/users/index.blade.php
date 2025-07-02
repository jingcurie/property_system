@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-dark"><i class="bi bi-people-fill"></i> 用户列表</h2>

            <!-- 默认显示：新增 / 导出 -->
            <div id="default-toolbar" class="d-flex gap-2">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> 新增角色
                </a>
            </div>

            <!-- 勾选后显示：批量删除 -->
            <div id="selected-toolbar" class="d-none">
                <span class="me-2 text-muted">已选中 <strong id="selected-count">0</strong> 项</span>
                <a href="#" class="btn btn-danger" onclick="submitBatchDelete()">
                    <i class="bi bi-trash3 me-1"></i> 批量删除
                </a>
            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="card border-0 shadow-sm">
                <div class="input-group">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                        placeholder="请输入用户名或邮箱">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i> 搜索
                    </button>
                </div>
            </div>
        </form>

        <form id="batch-delete-form" method="POST" action="{{ route('users.batchDelete') }}"
            onsubmit="return validateBatchDelete();">
            @csrf
            <div class="card shadow-sm">
                <div class="">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:30px;">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>
                                    @php
                                        $sortField = 'name';
                                        $isActive = request('sort') === $sortField;
                                        $dir = $isActive && request('direction') === 'asc' ? 'desc' : 'asc';
                                        $url = request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $dir]);
                                    @endphp

                                    <a href="{{ $url }}"
                                        class="sort-link {{ $isActive ? 'text-dark fw-bold' : 'text-muted' }}">
                                        用户名
                                        <span class="sort-icons">
                                            @if ($isActive)
                                                <i
                                                    class="bi {{ $dir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                                            @else
                                                <i class="bi bi-caret-up"></i>
                                                <i class="bi bi-caret-down"></i>
                                            @endif
                                        </span>
                                        <a href="{{ route('users.index') }}" class="ms-1 text-muted" title="恢复默认顺序">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </a>
                                    </a>
                                </th>
                                <th>
                                    @php
                                        $sortField = 'email';
                                        $isActive = request('sort') === $sortField;
                                        $dir = $isActive && request('direction') === 'asc' ? 'desc' : 'asc';
                                        $url = request()->fullUrlWithQuery(['sort' => 'email', 'direction' => $dir]);
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="sort-link {{ $isActive ? 'text-dark fw-bold' : 'text-muted' }}">
                                        邮箱
                                        <span class="sort-icons">
                                            @if ($isActive)
                                                <i
                                                    class="bi {{ $dir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                                            @else
                                                <i class="bi bi-caret-up"></i>
                                                <i class="bi bi-caret-down"></i>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th>角色</th>
                                <th>
                                    @php
                                        $sortField = 'created_at';
                                        $isActive = request('sort') === $sortField;
                                        $dir = $isActive && request('direction') === 'asc' ? 'desc' : 'asc';
                                        $url = request()->fullUrlWithQuery([
                                            'sort' => 'created_at',
                                            'direction' => $dir,
                                        ]);
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="sort-link {{ $isActive ? 'text-dark fw-bold' : 'text-muted' }}">
                                        创建时间
                                        <span class="sort-icons">
                                            @if ($isActive)
                                                <i
                                                    class="bi {{ $dir === 'asc' ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                                            @else
                                                <i class="bi bi-caret-up"></i>
                                                <i class="bi bi-caret-down"></i>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th class="text-end">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td><input type="checkbox" name="selected_ids[]" value="{{ $user->user_id }}"></td>
                                    <td>
                                        <img src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/default.png') }}"
                                            alt="头像" class="rounded-circle avatar-img">
                                        {{ $user->name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'admin' => 'badge-admin',
                                                'manager' => 'badge-manager',
                                                'finance' => 'badge-finance',
                                                'support' => 'badge-support',
                                                'agent' => 'badge-agent',
                                                'user' => 'badge-user',
                                                'viewer' => 'badge-viewer',
                                            ];
                                        @endphp
                                        @foreach ($user->getRoleNames() as $role)
                                            <span class="badge badge-soft {{ $roleColors[$role] ?? 'bg-default-role' }}">
                                                {{ $role }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown d-flex justify-content-end">
                                            <button type="button" class="btn-action-menu" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                        <i class="bi bi-eye me-2"></i> 查看
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> 编辑
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger"
                                                        onclick="submitDelete('{{ route('users.destroy', $user->id) }}')">
                                                        <i class="bi bi-trash me-2"></i> 删除
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">暂无用户数据</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('components._pagination', ['paginator' => $users])
        </form>

        <form id="delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function submitDelete(actionUrl) {
            if (confirm('确认删除？')) {
                const form = document.getElementById('delete-form');
                form.action = actionUrl;
                form.submit();
            }
        }

        function submitBatchDelete() {
            const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (selected.length === 0) {
                alert('请至少选择一个用户进行删除');
                return;
            }
            if (confirm('确认批量删除所选用户？')) {
                document.getElementById('batch-delete-form').submit();
            }
        }

        document.getElementById('select-all')?.addEventListener('change', function(e) {
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = e.target.checked);
        });

        function updateToolbarVisibility() {
            const checkedCount = document.querySelectorAll('input[name="selected_ids[]"]:checked').length;
            const defaultToolbar = document.getElementById('default-toolbar');
            const selectedToolbar = document.getElementById('selected-toolbar');
            const countSpan = document.getElementById('selected-count');

            if (checkedCount > 0) {
                defaultToolbar.classList.add('d-none');
                selectedToolbar.classList.remove('d-none');
                countSpan.textContent = checkedCount;

            } else {
                defaultToolbar.classList.remove('d-none');
                selectedToolbar.classList.add('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => {
                cb.addEventListener('change', updateToolbarVisibility);
            });
            document.getElementById('select-all')?.addEventListener('change', () => {
                document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => {
                    cb.checked = document.getElementById('select-all').checked;
                });
                updateToolbarVisibility();
            });
        });
    </script>


@endsection
