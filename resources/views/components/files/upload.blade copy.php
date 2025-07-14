@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet" />
@endpush

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">Recent Files</span>
        <a href="javascript:void(0);" onclick="openUploadModal()">Add</a>
    </div>
    <div class="card-body p-0">
        {{-- <ul class="list-group list-group-flush"> --}}
             {{-- @forelse ($files as $file) --}}
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                        <th>File name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody id="file-list">
                        <!-- 将从 modal 中拷贝数据 -->
                    </tbody>
                </table>
            {{-- @empty
                <li class="list-group-item">You don't have any files for this item right now. <a
                        href="javascript:void(0);" onclick="openUploadModal()">Upload your first file</a>.</li>
            @endforelse --}}
        {{-- </ul> --}}
    </div>
</div>

<!-- 上传 Modal -->
<!-- 上传Modal -->
<!-- Modal：文件上传 -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="fileUploadModalLabel">上传附件</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <!-- Dropzone 上传区 -->
        <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data"
              class="dropzone" id="file-dropzone">
          @csrf
          <input type="hidden" name="fileable_type" value="{{ $fileable_type }}">
          <input type="hidden" name="fileable_id" value="{{ $fileable_id }}">
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" onclick="confirmUploadFiles()">确认添加</button>
      </div>

    </div>
  </div>
</div>




@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        function deleteFile(id) {
            if (!confirm('Are you sure you want to delete this file?')) return;
            fetch(`/files/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) location.reload();
                else alert('Delete failed');
            });
        }

        function openPreviewModal(id) {
            window.open(`/files/${id}/preview`, '_blank');
        }
    </script>
@endpush
