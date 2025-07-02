@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">权限列表</h4>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> 新建权限</a>
    </div>

    {{-- 批量操作栏 --}}
    <div id="bulk-actions" class="mb-3 d-none">
        <form id="bulk-delete-form" action="{{ route('permissions.bulk-delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="selected-ids">
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> 批量删除</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                    <th>NAME</th>
                    <th>ASSIGNED TO</th>
                    <th>CREATED DATE</th>
                    <th class="text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td><input type="checkbox" class="select-item" value="{{ $permission->id }}"></td>
                        <td class="fw-semibold">{{ $permission->name }}</td>
                        <td>
                            @forelse ($permission->roles as $role)
                                <span class="badge bg-secondary">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </td>
                        <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('确定要删除该权限？');" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('components._pagination', ['paginator' => $permissions])
    </div>
</div>

{{-- 批量操作脚本 --}}
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-item');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedIdsInput = document.getElementById('selected-ids');

    function updateBulkActions() {
        const selected = Array.from(checkboxes).filter(c => c.checked).map(c => c.value);
        selectedIdsInput.value = selected.join(',');
        bulkActions.classList.toggle('d-none', selected.length === 0);
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(c => c.checked = this.checked);
        updateBulkActions();
    });

    checkboxes.forEach(c => {
        c.addEventListener('change', updateBulkActions);
    });
</script>
@endsection
