<!-- 上传组件 -->
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet" />
@endpush

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">Attachments</span>
        <a href="javascript:void(0);" onclick="openUploadModal()">Add</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mt-3" id="attachments-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody id="file-list">
                {{-- JS 或 Blade 动态渲染行 --}}
            </tbody>
        </table>

        <div id="no-files-placeholder" class="text-center text-muted py-4">
            <i class="bi bi-upload" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-0">No files yet. <a href="javascript:void(0);" onclick="openUploadModal()">Upload your
                    first file to get started.</a></p>
        </div>

    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">上传附件</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('files.upload') }}" method="POST" class="dropzone" id="file-dropzone">
                    @csrf
                    <input type="hidden" name="fileable_type" value="{{ $fileable_type }}">
                    <input type="hidden" name="fileable_id" value="{{ $fileable_id }}">
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button class="btn btn-primary" onclick="confirmUploadFiles()">确认添加</button>
            </div>

        </div>
    </div>
</div>

<!-- file view modal -->
<div class="modal fade modal-fullscreen" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen h-100 m-0">
        <div class="modal-content  h-100 ">

            <div class="modal-body p-0 d-flex">

                <!-- 左侧预览区域 -->
                <div class="flex-fill bg-dark d-flex justify-content-center align-items-center" id="filePreviewViewer">
                    <img src="" id="filePreviewImage" style="max-width: 100%; max-height: 100%;" class="d-none">
                    <iframe id="filePreviewPDF" style="width: 100%; height: 100%;" class="d-none"
                        frameborder="0"></iframe>
                    <div id="filePreviewOther" class="d-none"></div>
                </div>

                <!-- 右侧信息区域（Buildium风格） -->
                <div class="bg-light p-4" style="width: 400px; overflow-y: auto;">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="mb-0 fw-bold">Summary</h5>
                        {{-- <a href="#" class="text-decoration-none small text-primary">Edit</a> --}}
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <div class="text-uppercase small text-muted fw-bold">Title</div>
                        <div id="filePreviewTitle" class="fw-semibold">--</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <div class="text-uppercase small text-muted fw-bold">Description</div>
                        <div id="filePreviewDescription">--</div>
                    </div>

                    <!-- Category + Size + Type -->
                    <div class="mb-3 row">
                        <div class="col-4">
                            <div class="text-uppercase small text-muted fw-bold">Category</div>
                            <div id="filePreviewCategory" class="small text-muted">--</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase small text-muted fw-bold">Size</div>
                            <div id="filePreviewSize" class="small text-muted">--</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase small text-muted fw-bold">Type</div>
                            <div id="filePreviewType" class="small text-muted">--</div>
                        </div>
                    </div>

                    <!-- Updated at and by-->
                    <div class="mb-3 row">
                        <div class="text-uppercase small text-muted fw-bold">Upload at/by</div>
                        <div id="filePreviewUploadedAtBy" class="small text-muted">--</div>
                    </div>

                    <!-- File Name -->
                    <div class="mb-3">
                        <div class="text-uppercase small text-muted fw-bold">File Name</div>
                        <div id="filePreviewFilename" class="small text-muted">--</div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        let myDropzone;

        function openUploadModal() {
            const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
            modal.show();
        }

        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#file-dropzone", {
            addRemoveLinks: true,
            success: function(file, response) {
                file.tempFileData = response.file;
            }
        });

        function confirmUploadFiles() {
            const accepted = myDropzone.getAcceptedFiles().filter(f => f.tempFileData);
            for (const file of accepted) {
                const f = file.tempFileData;
                console.log(f);
                $('#file-list').append(`
            <tr data-id="${f.id}"
                data-filename="${f.filename || ''}"
                data-path="${f.path || ''}"
                data-mime="${f.mime_type || ''}"
                data-size="${f.size || 0}"
                data-disk="${f.disk || 'public'}"
                data-fileable-type="${f.fileable_type}"
                data-created_at="''"
                data-uploaded_by="${f.uploaded_by || 0}">
                <td  class="d-flex align-items-cente"><i class="bi ${getFileIconClass(f.filename)} fs-3"></i> 
                    <input type="text" class="form-control form-control-sm file-title" value="${escapeHtml(f.filename)}">
                    </td>
                <td>
                    <select class="form-select form-select-sm file-category form-control">
                        <option value="contract">Uncategorized</option>
                        <option value="contract">Applicant file</option>
                        <option value="id">Lease file</option>
                        <option value="photo">Report</option>
                    </select>
                </td>
                <td><input type="text" class="form-control form-control-sm file-description" value="${escapeHtml(f.description)}"></td>
                <td class="d-flex justify-content-center align-items-center"">
                    <div class="dropdown">
                        <button class="btn-action-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="handleEmail(this)"><i class="bi bi-envelope me-2"></i> Email</a></li>
                            <li><a class="dropdown-item" href="/${f.path}" download><i class="bi bi-download me-2"></i> Download</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="previewFile('${f.id}')"><i class="bi bi-eye me-2"></i> View</a></li>
                            <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteFileRow(${f.id})"><i class="bi bi-trash me-2"></i> Delete</a></li>
                        </ul>
                    </div>
                </td>       
            </tr>
        `);
            }

            updateAttachmentsInput();
            // 清空 Dropzone 预览 & 文件队列
            myDropzone.removeAllFiles(true);
            const modal = bootstrap.Modal.getInstance(document.getElementById('fileUploadModal'));
            modal.hide();
        }

        function deleteFileRow(id) {
            const row = $(`#file-list tr[data-id="${id}"]`);
            if (row.length > 0) {
                row.remove();
                updateAttachmentsInput();
            }
        }

        function updateAttachmentsInput() {
            const data = [];
            $('#file-list tr').each(function(index) {
                const id = $(this).data('id');
                const title = $(this).find('.file-title').val();
                const name = $(this).find('td:first').text().trim();
                const category = $(this).find('.file-category').val();
                const description = $(this).find('.file-description').val();
                const path = $(this).data('path') || '';
                const isCover = $(this).find('.set-cover-btn').hasClass('active') ? 1 : 0;
                const isPrivate = $(this).find('.file-private-checkbox').is(':checked') ? 1 : 0;
                const mimeType = $(this).data('mime') || '';
                const size = $(this).data('size') || 0;
                const filename = $(this).data('filename') || '';
                const disk = $(this).data('disk') || 'public';
                const fileable_type = $(this).data('fileable-type') || '';
                const created_at = $(this).data('created_at') || '';
                const uploaded_by = $(this).data('uploaded_by') || '';

                data.push({
                    id,
                    title,
                    filename,
                    path,
                    mime_type: mimeType,
                    size: size,
                    disk: disk,
                    category,
                    description,
                    is_cover: isCover,
                    is_private: isPrivate,
                    sort_order: index,
                    fileable_type,
                    created_at,
                    uploaded_by,
                });
            });
            $('#attachments').val(JSON.stringify(data));
            toggleAttachmentDisplay();
        }


        //view file function
        function previewFile(fileId) {
            const attachments = JSON.parse(document.getElementById('attachments').value || '[]');
            const file = attachments.find(f => f.id == fileId);
            if (!file || !file.path) {
                alert("File path not found.");
                return;
            }

            // const ext = file.filename.split('.').pop().toLowerCase();
            // const isImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);
            // const isPDF = ext === 'pdf';

            showFilePreview(file);

        }

        function showFilePreview(file) {
            const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
            modal.show();


            const ext = file.filename.split('.').pop().toLowerCase();

            // 清空所有预览
            $('#filePreviewImage').addClass('d-none');
            $('#filePreviewPDF').addClass('d-none');
            $('#filePreviewOther').addClass('d-none').html('');

            // 判断类型并显示对应内容
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                $('#filePreviewImage').attr('src', '/storage/' + file.path).removeClass('d-none');
            } else if (ext === 'pdf') {
                $('#filePreviewPDF').attr('src', '/storage/' + file.path).removeClass('d-none');
            } else if (['mp4', 'webm', 'ogg', 'mov', 'avi'].includes(ext)) {
                $('#filePreviewOther').html(`
            <video controls class="w-100">
                <source src="/storage/${file.path}" type="video/${ext}">
                Your browser does not support the video tag.
            </video>
        `).removeClass('d-none');
            } else {
                // 默认未知文件提示 + 下载链接
                $('#filePreviewOther').html(`
            <div class="text-center text-muted">
                <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                <p class="mt-2 mb-0">This file type is not previewable.</p>
                <a href="/storage/${file.path}" class="btn btn-sm btn-outline-secondary mt-2" download>Download</a>
            </div>
        `).removeClass('d-none');
            }
            console.log(file);
            // 填充文件信息
            $('#filePreviewTitle').text(file.title || '--');
            $('#filePreviewDescription').text(file.description || '--');
            $('#filePreviewCategory').text(file.category || 'Uncategorized');
            $('#filePreviewSize').text(formatSize(file.size) || '--');
            $('#filePreviewType').text(ext.toUpperCase());
            $('#filePreviewUploadedAtBy').text(file.created_at + " by " + file.uploaded_by || '--');
            // $('#filePreviewUploadedBy').text(file.uploaded_by || '--');
            $('#filePreviewFilename').text(file.filename);

            // 分享状态
            $('#shareOwner').prop('checked', file.share_owner || false);
            $('#shareTenant').prop('checked', file.share_tenant || false);
        }


        $(document).ready(function() {
            // 监听 category select 修改
            $('#file-list').on('change', '.file-category', function() {
                updateAttachmentsInput();
            });

            // 监听 description 输入框修改
            $('#file-list').on('input', '.file-title', function() {
                updateAttachmentsInput();
            });

            // 监听 description 输入框修改
            $('#file-list').on('input', '.file-description', function() {
                updateAttachmentsInput();
            });

            const attachmentsRaw = $('#attachments').val();
            if (attachmentsRaw && attachmentsRaw.trim() !== '[]') {
                const data = JSON.parse(attachmentsRaw);
                $('#file-list').empty(); // 清空旧内容，避免重复渲染

                data.forEach(f => {
                    $('#file-list').append(`
                <tr data-id="${f.id}"
                    data-filename="${f.filename || ''}"
                    data-path="${f.path || ''}"
                    data-mime="${f.mime_type || ''}"
                    data-size="${f.size || 0}"
                    data-disk="${f.disk || 'public'}"
                    data-fileable-type="${f.fileable_type || ''}"
                    data-created_at="${f.created_at}"
                    data-uploaded_by="${f.uploaded_by}">
                    <td class="d-flex align-items-cente"><i class="bi ${getFileIconClass(f.filename)} fs-3"></i>
                        <input type="text" class="form-control form-control-sm file-title" value="${escapeHtml(f.title)}">
                        </td>
                    <td>
                        <select class="form-select form-select-sm file-category form-control">
                            <option value="contract" ${f.category === 'contract' ? 'selected' : ''}>Uncategorized</option>
                            <option value="contract" ${f.category === 'contract' ? 'selected' : ''}>Applicant file</option>
                            <option value="id" ${f.category === 'id' ? 'selected' : ''}>Lease file</option>
                            <option value="photo" ${f.category === 'photo' ? 'selected' : ''}>Report</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm file-description" value="${escapeHtml(f.description)}"></td>
                    <td class="d-flex justify-content-center align-items-center">
                        <div class="dropdown">
                            <button class="btn-action-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="handleEmail(this)"><i class="bi bi-envelope me-2"></i> Email</a></li>
                                <li><a class="dropdown-item" href="/${f.path}" download><i class="bi bi-download me-2"></i> Download</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="previewFile('${f.id}')"><i class="bi bi-eye me-2"></i> View</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteFileRow(${f.id})"><i class="bi bi-trash me-2"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>       
                </tr>
            `);
                });

                // 渲染完后同步隐藏 input，防止丢数据
                updateAttachmentsInput();
            }
        });

        // 文件大小格式化工具函数
        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1024 / 1024).toFixed(1) + ' MB';
        }

        function getFileIconClass(filename) {
            const ext = filename.split('.').pop().toLowerCase();

            const map = {
                image: ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
                pdf: ['pdf'],
                word: ['doc', 'docx'],
                excel: ['xls', 'xlsx', 'csv'],
                ppt: ['ppt', 'pptx'],
                text: ['txt', 'md'],
                zip: ['zip', 'rar', '7z'],
                play: ['mp4', 'avi', 'mov', 'webm'],
                audio: ['mp3', 'wav', 'aac'],
                code: ['js', 'php', 'py', 'html', 'css', 'json'],
            };

            for (const [type, exts] of Object.entries(map)) {
                if (exts.includes(ext)) return `bi-file-earmark-${type}`;
            }

            return 'bi-file-earmark'; // default icon
        }

        function toggleAttachmentDisplay() {
            const attachmentsInput = document.getElementById('attachments');
            const table = document.getElementById('attachments-table');
            const placeholder = document.getElementById('no-files-placeholder');

            const value = attachmentsInput.value.trim();
            const hasData = value && value !== '[]';

            table.style.display = hasData ? 'table' : 'none';
            placeholder.style.display = hasData ? 'none' : 'block';
        }

        // 页面加载时判断一次
        document.addEventListener('DOMContentLoaded', toggleAttachmentDisplay);

        // 转义HTML，防止 XSS
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"']/g, function(m) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                })[m];
            });
        }
    </script>
@endpush
