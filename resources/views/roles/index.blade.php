@extends('layouts.app')

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '成功',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '错误',
            text: '{{ session('error') }}',
        });
    </script>
@endif

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-wrapper me-3">
                <i class="bi bi-person-fill-gear text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Roles List</h4>
            </div>
        </div>
        {{-- <h4 class="mb-4 fw-bold"><i class="bi bi-person-fill-gear"></i> Roles List</h4> --}}
        <div class="row g-4">
            @foreach ($roles as $role)
                <div class="col-md-6 col-lg-4">
                    <div class="card role-card h-100 shadow-sm border-0">
                        {{-- 卡片头部（角色名 + 三点按钮） --}}
                        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-start">
                            <h5 class="mb-0">{{ ucfirst($role->name) }}</h5>

                            <div class="dropdown">
                                <button class="btn role-action-btn" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('roles.show', $role) }}"><i class='bi bi-eye me-2'></i> View Role</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('roles.edit', $role) }}"><i class='bi bi-pencil-square me-2'></i> Edit Role</a>
                                    </li>
                                    @if ($role->name !== 'admin')
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a href="javascript:;" class="dropdown-item text-danger btn-delete-role"
                                                data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                                                <i class='bi bi-trash me-2'></i> Delete Role
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        {{-- 卡片内容 --}}
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Total users with this role: <strong>{{ $role->users_count }}</strong>
                            </p>

                            <ul class="list-unstyled mb-0">
                                @foreach ($role->display_permissions->take(5) as $permission)
                                    <li class="mb-2 fs-6 text-primary-emphasis">
                                        <i class="bi bi-dot"></i> {{ $permission->description ?? $permission->name }}
                                    </li>
                                @endforeach
                                @if ($role->display_permissions->count() > 5)
                                    <li class="text-muted fst-italic">
                                        <i class="bi bi-three-dots"></i> and {{ $role->display_permissions->count() - 5 }}
                                        more...
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach


            {{-- Add New Role Card --}}
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('roles.create') }}"
                    class="card h-100 text-center text-decoration-none border-0 shadow-sm d-flex align-items-center justify-content-center">
                    <div class="text-muted">
                        <i class="bi bi-stars display-4"></i>
                        <p class="mt-2 fw-semibold">Add New Role</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    document.querySelectorAll('.btn-delete-role').forEach(btn => {
        btn.addEventListener('click', function() {
            alert("dddd");
            const roleId = this.dataset.id;
            const roleName = this.dataset.name;

            Swal.fire({
                title: `确认删除角色「${roleName}」？`,
                icon: 'warning',
                html: `
                删除该角色将导致所有被赋予此角色的用户失去权限，<br>
                他们将无法继续使用系统功能。<br><br>
                <strong class="text-danger">此操作不可恢复，请谨慎！</strong>
            `,
                showCancelButton: true,
                confirmButtonText: '确认删除',
                cancelButtonText: '取消',
            }).then((result) => {
                if (result.isConfirmed) {
                    // 构建并提交表单
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/roles/${roleId}`;

                    const csrf = document.querySelector('meta[name="csrf-token"]').content;

                    form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                `;

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
