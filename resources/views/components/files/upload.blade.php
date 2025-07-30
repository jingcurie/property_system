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


        <table class="table table-hover table-bordered mt-3" id="attachments-table" style="border-collapse: collapse;">
            <thead>

            </thead>
            <tbody>
                {{-- JS 或 Blade 动态渲染行 --}}
            </tbody>
        </table>
        <div id="batch-actions-bar" class="d-flex justify-content-end align-items-center gap-3 d-none   ">
            <div class="text-muted small" id="selected-count">0 selected</div>
            <button class="btn btn-sm btn-primary" onclick="sendSelectedEmails()"><i class="bi bi-trash me-1"></i> Send
                Email</button>
        </div>

        <div id="no-files-placeholder" class="text-center text-muted py-4 d-none">
            <i class="bi bi-upload" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-0">No files yet. <a href="javascript:void(0);" onclick="openUploadModal()">Upload your
                    first file to get started.</a></p>
        </div>
        <input type="text" id="attachments" name="attachments" value='@json(old('attachments', $attachments ?? '[]'))'>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('files.upload') }}" method="POST" class="dropzone" id="file-dropzone">
                    @csrf
                    <input type="hidden" name="fileable_type" value="{{ $fileable_type }}">
                    <input type="hidden" name="fileable_id" value="{{ $fileable_id }}">
                </form>

                <!-- 编辑表格：上传后展示文件并编辑 -->
                <table class="table mt-4" id="temp-file-table">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="saveUploadFiles()">Save</button>
            </div>

            <input type="text" id="temp-attachments" name="attachments" value=''>


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

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="emailForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="file_ids" id="file_ids">
                    <div class="mb-3">
                        <label>To</label>
                        <input type="email" class="form-control" name="to" required>
                    </div>
                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea class="form-control" name="body" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Attachment</label>
                        <div id="email-attachment-preview"></div> <!-- JS 填入文件名 -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="sendBtn" class="btn btn-primary">
                        <span id="sendBtnText">发送</span>
                        <span id="sendBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                            aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        let myDropzone;
        let tempFiles = [];

        function openUploadModal() {
            const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
            modal.show();
        }

        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#file-dropzone", {
            addRemoveLinks: false,
            success: function(file, response) {
                file.tempFileData = response.file;
                insertDataIntoInputOfAttachments();
            }
        });

        function insertDataIntoInputOfAttachments() {
            const accepted = myDropzone.getAcceptedFiles().filter(f => f.tempFileData);

            const newData = accepted.map(file => {
                const f = file.tempFileData;
                return {
                    id: f.id || null,
                    title: f.title || '',
                    filename: f.filename || '',
                    path: f.path || '',
                    fileable_type: f.fileable_type || '',
                    fileable_id: f.fileable_id || '',
                    mime_type: f.mime_type || '',
                    size: f.size || 0,
                    disk: f.disk || 'public',
                    uploaded_by: f.uploaded_by || null,
                    category: f.category || 'uncategorized',
                    description: f.description || '',
                    is_cover: f.is_cover || false
                };
            });

            const input = document.getElementById('temp-attachments');
            let oldData = [];

            try {
                oldData = JSON.parse(input.value || '[]');
            } catch (e) {
                console.warn('旧 JSON 无效，已重置为空数组');
            }

            // ✅ 避免添加重复 path 的文件
            const existingPaths = new Set(oldData.map(f => f.path));

            const trulyNewFiles = newData.filter(file => !existingPaths.has(file.path));

            // ✅ 只追加新文件，保留旧文件
            const merged = oldData.concat(trulyNewFiles);

            input.value = JSON.stringify(merged);
            renderTempTable();
        }

        function renderTempTable() {
            const thead = document.querySelector('#temp-file-table thead');
            thead.innerHTML = `
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th style="width: 60px;"></th>
                </tr>
            `
            const input = document.getElementById('temp-attachments');

            try {
                tempFiles = JSON.parse(input.value || '[]');
            } catch (e) {
                console.error('Invalid JSON in #temp-attachments');
                return;
            }

            const tbody = document.querySelector('#temp-file-table tbody');
            tbody.innerHTML = '';

            tempFiles.forEach((file, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="text" class="form-control" value="${file.title || ''}" 
                        onchange="updateTempField(${index}, 'title', this.value)"></td>
                    <td>
                        <select name="category" class="form-select form-select-sm" onchange="updateTempField(${index}, 'category', this.value)">
                            <option value="uncategorized" ${file.category === 'uncategorized' ? 'selected' : ''}>Uncategorized</option>
                            <option value="applicant" ${file.category === 'applicant' ? 'selected' : ''}>Applicant Files</option>
                            <option value="lease" ${file.category === 'lease' ? 'selected' : ''}>Leases Files</option>
                            <option value="report" ${file.category === 'report' ? 'selected' : ''}>Report Files</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" value="${file.description || ''}" 
                        onchange="updateTempField(${index}, 'description', this.value)"></td>
                    <td><button class="btn btn-sm btn-danger" onclick="removeTempFile(${index})">Delete</button></td>
                `;
                tbody.appendChild(row);
            });
        }

        function updateTempField(index, field, value) {
            tempFiles[index][field] = value;
            $('#temp-attachments').val(JSON.stringify(tempFiles));
        }

        function removeTempFile(index) {
            // 获取要删除的文件，用来找 Dropzone 中的对应文件
            const fileToRemove = tempFiles[index];

            // 从 tempFiles 数组中移除
            tempFiles.splice(index, 1);
            $('#temp-attachments').val(JSON.stringify(tempFiles));

            // 从 Dropzone 中移除对应的文件（通过 filename 或 path 匹配）
            myDropzone.getAcceptedFiles().forEach(file => {
                if (
                    file.tempFileData &&
                    (file.tempFileData.filename === fileToRemove.filename || file.tempFileData.path === fileToRemove
                        .path)
                ) {
                    myDropzone.removeFile(file); // 删除 Dropzone 中的文件
                }
            });

            // 重新渲染表格
            renderTempTable();
        }

        function saveUploadFiles() {
            const button = event.target;
            button.disabled = true;
            button.innerText = 'Saving...';

            const tempInput = document.getElementById('temp-attachments');
            let attachments = [];

            try {
                attachments = JSON.parse(tempInput.value || '[]');
            } catch (e) {
                alert('附件数据格式错误');
                button.disabled = false;
                button.innerText = 'Save';
                return;
            }

            if (attachments.length === 0) {
                alert('无保存的附件内容');
                button.disabled = false;
                button.innerText = 'Save';
                return;
            }

            fetch('/files/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        attachments
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('保存失败');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const savedFiles = data.savedFiles;
                        console.log(savedFiles);
                        const input_attachments = document.getElementById('attachments');

                        // 1. 获取旧数据
                        let oldAttachments = [];
                        try {
                            oldAttachments = JSON.parse(input_attachments.value || '[]');
                        } catch (e) {
                            oldAttachments = [];
                        }

                        // 2. 合并新旧数据（避免重复 ID）
                        const newIds = savedFiles.map(f => f.id);
                        const merged = [
                            ...oldAttachments.filter(f => !newIds.includes(f.id)),
                            ...savedFiles
                        ];

                        // 3. 写回合并后的结果
                        input_attachments.value = JSON.stringify(merged);

                        console.log( savedFiles);

                        // ✅ 渲染文件表格
                        renderSavedFiles(savedFiles);

                        // ✅ 清空临时 input 和表格
                        tempInput.value = '[]';
                        document.querySelector('#temp-file-table thead').innerHTML = '';
                        document.querySelector('#temp-file-table tbody').innerHTML = '';
                        if (typeof myDropzone !== 'undefined') myDropzone.removeAllFiles();

                        // ✅ 关闭弹窗
                        const modal = bootstrap.Modal.getInstance(document.getElementById('fileUploadModal'));
                        if (modal) modal.hide();

                        showSuccess('附件上传成功！');
                    } else {
                        showError(data.message || '附件上传失败');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showError('附件上传过程中出现错误');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerText = 'Save';
                });
        }



        function getIconByType(filename) {
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

        // 文件大小格式化工具函数
        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1024 / 1024).toFixed(1) + ' MB';
        }

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

        function renderSavedFiles() {
            const input = document.getElementById('attachments');
            let attachments = [];

            try {
                attachments = JSON.parse(input.value || '[]');
            } catch (e) {
                console.warn('附件 JSON 解析失败');
                return;
            }

            const thead = document.querySelector('#attachments-table thead');
            thead.innerHTML = `
                <tr>
                    <th style="width: 40px;"><input type="checkbox" id="selectAllFiles" /></th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th style="width: 60px;"></th>
                </tr>
            `

            const tbody = document.querySelector('#attachments-table tbody');
            tbody.innerHTML = '';

            attachments.forEach(f => {
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', f.id || '');
                tr.setAttribute('data-filename', f.filename || '');
                tr.setAttribute('data-path', f.path || '');
                tr.setAttribute('data-mime', f.mime_type || '');
                tr.setAttribute('data-size', f.size || 0);
                tr.setAttribute('data-disk', f.disk || 'public');
                tr.setAttribute('data-fileable-type', f.fileable_type || '');
                tr.setAttribute('data-created_at', f.created_at || '');
                tr.setAttribute('data-uploaded_by', f.uploaded_by || '');


                 tr.setAttribute('onclick',"handleRowClick(event," + f.id + ")");
                 tr.setAttribute('style', "cursor: pointer;");
                tr.innerHTML = `
                    <td><input type="checkbox" class="file-checkbox" data-id="${f.id}" /></td>
                    <td>
                        <i class="bi ${getFileIconClass(f.filename)} fs-4 me-2"></i>
                        <span class="file-title">${escapeHtml(f.title || '(未命名)')}</span>
                    </td>
                    <td>
                        <span class="file-category">${formatCategory(f.category)}</span>
                    </td>
                    <td>
                        <span class="file-description">${escapeHtml(f.description || '')}</span>
                    </td>
                    <td class="d-flex justify-content-center align-items-center">
                        <div class="dropdown">
                            <button class="btn-action-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                    <a href="#" class="dropdown-item" onclick="openEmailModal(this)"
                                        data-id="${f.id}"
                                        data-title="${f.filename}">
                                        <i class="bi bi-envelope me-2"></i> Email
                                        </a></li>
                                <li><a class="dropdown-item" href="/storage/${f.path}" download="${f.filename}"><i class="bi bi-download me-2"></i> Download</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="previewFile('${f.id}')"><i class="bi bi-eye me-2"></i> View</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteAttachment(${f.id})"><i class="bi bi-trash me-2"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            const placeholder = document.getElementById('no-files-placeholder');
            if (attachments.length === 0) {
                placeholder.classList.remove('d-none');
            } else {
                placeholder.classList.add('d-none');
            }
        }

        // 分类转换
        function formatCategory(c) {
            const map = {
                'lease': 'Lease file',
                'report': 'Report file',
                'applicant': 'Applicant file',
                'uncategorized': 'Uncategorized'
            };
            return map[c] || c || '-';
        }

        function handleRowClick(event, fileId) {
            const tag = event.target.tagName.toLowerCase();
            const isInteractive = ['input', 'button', 'a', 'svg', 'path'].includes(tag) || event.target.closest(
            '.dropdown');
            if (isInteractive) return; // 忽略点击 checkbox、按钮、菜单等

            previewFile(fileId); // 打开预览
        }

        //view file function
        function previewFile(fileId) {
            const attachments = JSON.parse(document.getElementById('attachments').value || '[]');
            const file = attachments.find(f => f.id == fileId);
            if (!file || !file.path) {
                alert("File path not found.");
                return;
            }

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

            // // 分享状态
            // $('#shareOwner').prop('checked', file.share_owner || false);
            // $('#shareTenant').prop('checked', file.share_tenant || false);
        }

        function deleteAttachment(id) {
            showConfirm('确定要删除此附件吗？', function() {
                fetch(`/files/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            //从 DOM 删除表格行
                            const row = document.querySelector(`tr[data-id="${id}"]`);
                            if (row) row.remove();

                            //从隐藏 input 中删除该文件对象
                            const input = document.getElementById('attachments');
                            let files = JSON.parse(input.value || '[]');
                            files = files.filter(f => f.id !== id);
                            input.value = JSON.stringify(files);

                            //成功提示
                            showSuccess('附件删除成功');
                        } else {
                            Swal.fire('错误', data.message || '删除失败', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('错误', '请求失败，请稍后再试', 'error');
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderSavedFiles(); // 重新渲染表格
        });
    </script>

    <!--email script-->
    <script>
        function openEmailModal(el) {
            const fileId = el.dataset.id;
            const fileTitle = el.dataset.title;

            document.querySelector('#file_id').value = fileId;
            document.querySelector('#email-attachment-preview').innerText = fileTitle;

            const modal = new bootstrap.Modal(document.getElementById('emailModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('emailForm');
            if (form.dataset.bound !== 'true') {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const data = new FormData(form);

                    const sendBtn = document.getElementById('sendBtn');
                    const sendBtnText = document.getElementById('sendBtnText');
                    const sendBtnSpinner = document.getElementById('sendBtnSpinner');

                    sendBtn.disabled = true;
                    sendBtnText.textContent = '发送中...';
                    sendBtnSpinner.classList.remove('d-none');

                    fetch('/email/send', {
                            method: 'POST',
                            body: data,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            }
                        })
                        .then(async (res) => {
                            const result = await res.json();
                            if (!res.ok) throw new Error(result.error || '未知错误');
                            showSuccess('发送成功');
                            bootstrap.Modal.getInstance(document.getElementById('emailModal'))
                                .hide();
                        })
                        .catch(err => {
                            console.error('发送失败', err);
                            showError('发送失败：' + err.message);
                        });
                });

                // 标记为已绑定
                form.dataset.bound = 'true';
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('file-checkbox')) {
                updateSelectedCount();
            }
            if (e.target.id === 'selectAllFiles') {
                const checked = e.target.checked;
                document.querySelectorAll('.file-checkbox').forEach(cb => cb.checked = checked);
                updateSelectedCount();
            }
        });

        function updateSelectedCount() {
            const selected = document.querySelectorAll('.file-checkbox:checked');
            const count = selected.length;

            const bar = document.getElementById('batch-actions-bar');
            const label = document.getElementById('selected-count');

            if (count > 0) {
                bar.classList.remove('d-none');
                label.textContent = `${count} selected`;
            } else {
                bar.classList.add('d-none');
            }
        }

        function sendSelectedEmails() {
            const selectedIds = Array.from(document.querySelectorAll('.file-checkbox:checked'))
                .map(cb => parseInt(cb.dataset.id));

            const attachments = JSON.parse(document.getElementById('attachments').value || '[]');
            const selectedFiles = attachments.filter(f => selectedIds.includes(f.id));

            if (selectedFiles.length === 0) {
                showError('请选择至少一个文件');
                return;
            }

            // 预览展示多个文件名
            const preview = selectedFiles.map(f => `<div>${f.filename}</div>`).join('');
            document.querySelector('#email-attachment-preview').innerHTML = preview;

            // ✅ 替换：提交多个 ID（用逗号连接）
            document.querySelector('#file_ids').value = selectedFiles.map(f => f.id).join(',');

            const modal = new bootstrap.Modal(document.getElementById('emailModal'));
            modal.show();
        }
    </script>
@endpush
