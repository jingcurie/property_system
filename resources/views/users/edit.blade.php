@extends('layouts.app')

@section('content')
<div class="container py-4 d-flex justify-content-center">
    <div class="card user-form-card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">编辑用户</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 用户名 --}}
                <div class="mb-3">
                    <label for="name" class="form-label">用户名</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                {{-- 邮箱 --}}
                <div class="mb-3">
                    <label for="email" class="form-label">邮箱</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                {{-- 密码（可选） --}}
                <div class="mb-3">
                    <label for="password" class="form-label">密码（留空则不修改）</label>
                    <input type="text" name="password" class="form-control" placeholder="可留空不修改">
                </div>

                {{-- 角色选择 --}}
                <div class="mb-3">
                    <label for="role" class="form-label">角色</label>
                    <select name="role" class="form-select" required>
                        <option value="">请选择角色</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 当前头像 --}}
                <div class="mb-3">
                    <label class="form-label d-block">头像</label>
                    <img id="avatar-preview" src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('avatars/default.png') }}"
                         class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                    <input type="hidden" name="avatar" id="avatar-hidden" value="{{ $user->avatar }}">
                    <input type="hidden" name="avatar_uploaded" id="avatar-uploaded">
                    <button type="button" class="btn btn-outline-primary ms-3" data-bs-toggle="modal" data-bs-target="#avatarModal">选择头像</button>
                </div>

                {{-- 提交按钮 --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">返回</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> 更新用户</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 头像选择 Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">选择头像</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">固定头像</label>
                    <div class="d-flex flex-wrap gap-2">
                        @php $avatars = File::files(public_path('avatars')); @endphp
                        @foreach($avatars as $file)
                            @php $filename = $file->getFilename(); @endphp
                            <img src="{{ asset('avatars/' . $filename) }}" data-value="{{ $filename }}"
                                 class="selectable-avatar rounded border border-2 {{ $filename === $user->avatar ? 'selected' : '' }}"
                                 style="width: 60px; height: 60px; cursor: pointer;">
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">上传头像</label>
                    <input type="file" id="upload-avatar" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>

{{-- 选中样式 --}}
<style>
.selectable-avatar:hover {
    outline: 2px solid #0d6efd;
}
.selectable-avatar.selected {
    outline: 3px solid #0d6efd;
    outline-offset: 1px;
}
</style>

{{-- 脚本 --}}
<script>
document.querySelectorAll('.selectable-avatar').forEach(img => {
    img.addEventListener('click', function () {
        document.querySelectorAll('.selectable-avatar').forEach(i => i.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('avatar-preview').src = this.src;
        document.getElementById('avatar-hidden').value = this.dataset.value;
        document.getElementById('avatar-uploaded').value = '';
    });
});

document.getElementById('upload-avatar').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('avatar-uploaded').value = file.name;
            document.getElementById('avatar-hidden').value = '';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
