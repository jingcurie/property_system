@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h5 class="mb-4 fw-bold">Roles List</h5>
    {{-- <nav class="mb-4 small text-muted">
        <span>Home</span> &nbsp;/&nbsp;
        <span>User Management</span> &nbsp;/&nbsp;
        <span class="text-dark fw-semibold">Roles</span>
    </nav> --}}

    <div class="row g-4">
        @foreach($roles as $role)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold">{{ ucfirst($role->name) }}</h5>
                        <p class="text-muted small mb-2">
                            Total users with this role: <strong>{{ $role->users_count }}</strong>
                        </p>
                        <ul class="list-unstyled small mb-3">
                            @foreach($role->display_permissions->take(5) as $permission)
                              
    <li class="mb-1 text-primary-emphasis">
        <i class="bi bi-dot"></i> {{ $permission->description ?? $permission->name }}
    </li>

                            @endforeach
                            @if($role->display_permissions->count() > 5)
                                <li class="fst-italic text-primary-emphasis">
                                    <i class="bi bi-three-dots"></i> and {{ $role->display_permissions->count() - 5 }} more...
                                </li>
                            @endif
                        </ul>
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary btn-sm">View Role</a>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">Edit Role</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Add New Role Card --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('roles.create') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm d-flex align-items-center justify-content-center">
                <div class="text-muted">
                    <i class="bi bi-stars display-4"></i>
                    <p class="mt-2 fw-semibold">Add New Role</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
