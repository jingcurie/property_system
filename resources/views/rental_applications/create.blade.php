@extends('layouts.app')

@section('content')
    <h1 class="mb-4">New Rental Application</h1>

    @include('rental_applications.partials.form', [
        'formAction' => route('rental_applications.store'),
        'isEdit' => false,
        'application' => null,
        'properties' => $properties,
    ])
@endsection

{{-- @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
@endpush --}}

{{-- @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        let myDropzone = null;

        function openUploadModal() {
            const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
            modal.show();

            const dropzoneElement = document.getElementById('file-dropzone');
            if (dropzoneElement.dropzone) {
                dropzoneElement.dropzone.destroy();
            }

            myDropzone = new Dropzone(dropzoneElement, {
                paramName: 'file',
                maxFilesize: 10,
                acceptedFiles: '.jpg,.jpeg,.png,.pdf,.doc,.docx',
                addRemoveLinks: true,
                dictDefaultMessage: '拖拽文件到此处或点击上传',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                success: function(file, response) {
                    const res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (!res.success || !res.file) return;

                    // const f = res.file;
                    // appendFileToList(f);
                },
                error: function(file, errorMessage) {
                    alert("上传失败：" + errorMessage);
                }
            });
        }

        function confirmUploadFiles() {
            const files = myDropzone.getAcceptedFiles();
            const displayTableBody = document.getElementById('file-list');

            // 清空 Dropzone 中的文件（注意：在移除前先提取数据）
            files.forEach((file, index) => {
                const fileId = 'temp_' + Date.now() + '_' + index;

                const tr = document.createElement('tr');
                tr.setAttribute('data-temp-id', fileId);

                tr.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-text fs-4 me-2"></i>
                        ${file.name}
                        <input type="hidden" name="files[${fileId}][name]" value="${escapeHtml(file.name)}">
                    </div>
                </td>
                <td>
                    <select class="form-select form-select-sm" name="files[${fileId}][category]">
                        <option value="">请选择</option>
                        <option value="contract">合同</option>
                        <option value="id">身份证明</option>
                        <option value="photo">房源图片</option>
                        <option value="other">其他</option>
                    </select>
                </td>
                <td>
                    <textarea class="form-control form-control-sm" name="files[${fileId}][description]" rows="1" placeholder="请输入备注"></textarea>
                </td>
                <td class="text-end">
  <div class="dropdown">
    <button class="btn-action-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item text-danger" href="#" onclick="handleDelete(this)">
          <i class="bi bi-trash me-2"></i> Delete
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="#" onclick="handleEmail(this)">
          <i class="bi bi-envelope me-2"></i> Email
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="#" onclick="handleDownload(this)">
          <i class="bi bi-download me-2"></i> Download
        </a>
      </li>
      <li>
        <a class="dropdown-item" href="#" onclick="handleView(this)">
          <i class="bi bi-eye me-2"></i> View
        </a>
      </li>
    </ul>
  </div>
</td>

                `;

                displayTableBody.appendChild(tr);
            });

            // 清空 Dropzone 预览 & 文件队列
            myDropzone.removeAllFiles(true);

            // 关闭模态框
            const modal = bootstrap.Modal.getInstance(document.getElementById('fileUploadModal'));
            if (modal) modal.hide();
        }

        // 转义HTML，防止 XSS
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
@endpush --}}
