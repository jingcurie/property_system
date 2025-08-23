@extends('layouts.app')

@section('content')

    <ul class="nav tab-line-tabs" id="propertyTabs" role="tablist">
    @foreach([
        'summary' => 'Summary',
        'tenants' => 'Tenants',
        'owners' => 'Owners',
        'property' => 'Property',
        'financials' => 'Financials',
        'files' => 'Files',
        'events' => 'Events'
    ] as $key => $label)
        <li class="nav-item">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                data-bs-target="#tab-{{ $key }}" type="button" role="tab">
                {{ $label }}
            </button>
        </li>
    @endforeach
    </ul>

    {{-- Tabs 内容区域 --}}
    <div class="tab-content pt-4">
        <div class="tab-pane fade show active" id="tab-summary">@include('leases.partials.tabs.summary')</div>
        <div class="tab-pane fade" id="tab-tenants">@include('leases.partials.tabs.tenants')</div>
        <div class="tab-pane fade" id="tab-owners">@include('leases.partials.tabs.owners')</div>
         <div class="tab-pane fade" id="tab-property">@include('leases.partials.tabs.property')</div>
        <div class="tab-pane fade" id="tab-financials"></div>
        <div class="tab-pane fade" id="tab-files">@include('leases.partials.tabs.files')</div>
        <div class="tab-pane fade" id="tab-events"></div>
    </div>

    {{-- DocuSign 签署人选择模态框 --}}
    <div class="modal fade" id="docusignModal" tabindex="-1" aria-labelledby="docusignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="docusignModalLabel">
                        <i class="bi bi-envelope me-2"></i>选择签署人
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">预定义签署人</h6>
                        <div id="signersList">
                            <!-- 签署人列表将在这里动态加载 -->
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">添加自定义签署人</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="customSignerName" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="customSignerName" placeholder="请输入姓名">
                            </div>
                            <div class="col-md-4">
                                <label for="customSignerEmail" class="form-label">邮箱</label>
                                <input type="email" class="form-control" id="customSignerEmail" placeholder="请输入邮箱">
                            </div>
                            <div class="col-md-4">
                                <label for="customSignerType" class="form-label">签署人类型</label>
                                <select class="form-select" id="customSignerType">
                                    <option value="tenant">租客</option>
                                    <option value="owner">业主</option>
                                    <option value="agent">代理公司</option>
                                    <option value="custom">自定义</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomSigner()">
                                <i class="bi bi-plus-circle me-1"></i>添加签署人
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="sendDocusignWithCustomSigners()">
                        <i class="bi bi-send me-1"></i>发送合同
                    </button>
                </div>
            </div>
        </div>
    </div>
    
@endsection

<script>
    function handleGeneratePdf(button, event) {
        // 检查是否已经在处理中
        if (button.classList.contains('processing')) {
            event.preventDefault();
            return false;
        }

        // 标记为处理中
        button.classList.add('processing');
        button.style.pointerEvents = 'none';

        // 保存原始内容
        const originalContent = button.innerHTML;

        // 显示loading状态
        button.innerHTML = `
        <div class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        生成中...
    `;

        // 5秒后恢复按钮状态（因为是新窗口，无法监听完成事件）
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('processing');
            button.style.pointerEvents = 'auto';
        }, 5000);

        setTimeout(() => {
            location.reload();
        }, 3000);

        // 继续正常的链接跳转
        return true;
    }
</script>

<style>
    .btn.processing {
        opacity: 0.7;
        cursor: wait !important;
    }
</style>

<script>
    function sendDocusignAjax(leaseId, button) {
        if (button.disabled) return;

        button.disabled = true;
        const originalText = button.innerHTML;

        button.innerHTML = `
        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
        发送中...
    `;

        fetch(`/leases/${leaseId}/send-docusign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ 发送结果:', data);

                if (data.success) {
                    // 成功状态
                    button.innerHTML = '<i class="bi bi-check-circle me-2"></i>发送成功';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');

                    // 使用您现有的SweetAlert成功提示
                    showSuccess(data.message || '合同已成功发送给租客签署！');

                    // 3秒后刷新页面
                    setTimeout(() => location.reload(), 3000);
                } else {
                    throw new Error(data.message || '发送失败');
                }
            })
            .catch(error => {
                console.error('❌ 发送失败:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.add('btn-danger');

                // 使用您现有的SweetAlert错误提示
                showError('发送失败：' + error.message);

                // 5秒后恢复按钮
                setTimeout(() => {
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-primary');
                }, 5000);
            });
    }

    // 新的DocuSign功能
    function openDocusignModal(leaseId) {
        // 显示模态框
        const modal = new bootstrap.Modal(document.getElementById('docusignModal'));
        modal.show();
        
        // 存储leaseId供后续使用
        document.getElementById('docusignModal').setAttribute('data-lease-id', leaseId);
        
        // 加载签署人列表
        loadSignersList(leaseId);
    }

    function loadSignersList(leaseId) {
        fetch(`/leases/${leaseId}/get-signers`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 保存签署人数据到全局变量
                    window.signersData = data.signers;
                    renderSignersList(data.signers);
                } else {
                    showError('加载签署人列表失败：' + data.message);
                }
            })
            .catch(error => {
                console.error('加载签署人列表失败:', error);
                showError('加载签署人列表失败');
            });
    }

    function renderSignersList(signers) {
        const container = document.getElementById('signersList');
        container.innerHTML = '';
        
        // 类型配置
        const typeConfig = {
            'tenant': { color: 'primary', text: '租客' },
            'owner': { color: 'success', text: '业主' },
            'agent': { color: 'info', text: '代理公司' },
            'custom': { color: 'warning', text: '自定义' }
        };
        
        signers.forEach((signer, index) => {
            const typeInfo = typeConfig[signer.type] || typeConfig['custom'];
            
            const signerHtml = `
                <div class="signer-item border rounded p-3 mb-2" data-index="${index}">
                    <div class="form-check">
                        <input class="form-check-input signer-checkbox" type="checkbox" 
                               id="signer-${index}" value="${index}" 
                               ${signer.checked !== false ? 'checked' : ''}>
                        <label class="form-check-label" for="signer-${index}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${signer.name}</strong>
                                    <span class="badge bg-${typeInfo.color} ms-2">${typeInfo.text}</span>
                                </div>
                                <small class="text-muted">${signer.email}</small>
                            </div>
                        </label>
                    </div>
                </div>
            `;
            container.innerHTML += signerHtml;
        });
    }

    function addCustomSigner() {
        const name = document.getElementById('customSignerName').value.trim();
        const email = document.getElementById('customSignerEmail').value.trim();
        const type = document.getElementById('customSignerType').value;
        
        if (!name || !email) {
            showError('请输入姓名和邮箱');
            return;
        }
        
        if (!isValidEmail(email)) {
            showError('请输入有效的邮箱地址');
            return;
        }
        
        const container = document.getElementById('signersList');
        const index = container.children.length;
        
        // 根据类型设置标签颜色和文本
        const typeConfig = {
            'tenant': { color: 'primary', text: '租客' },
            'owner': { color: 'success', text: '业主' },
            'agent': { color: 'info', text: '代理公司' },
            'custom': { color: 'warning', text: '自定义' }
        };
        
        const typeInfo = typeConfig[type] || typeConfig['custom'];
        
        const signerHtml = `
            <div class="signer-item border rounded p-3 mb-2" data-index="${index}">
                <div class="form-check">
                    <input class="form-check-input signer-checkbox" type="checkbox" 
                           id="signer-${index}" value="${index}" checked>
                    <label class="form-check-label" for="signer-${index}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${name}</strong>
                                <span class="badge bg-${typeInfo.color} ms-2">${typeInfo.text}</span>
                            </div>
                            <small class="text-muted">${email}</small>
                        </div>
                    </label>
                </div>
                <input type="hidden" name="signers[${index}][name]" value="${name}">
                <input type="hidden" name="signers[${index}][email]" value="${email}">
                <input type="hidden" name="signers[${index}][type]" value="${type}">
            </div>
        `;
        container.innerHTML += signerHtml;
        
        // 清空输入框
        document.getElementById('customSignerName').value = '';
        document.getElementById('customSignerEmail').value = '';
        document.getElementById('customSignerType').value = 'custom';
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function sendDocusignWithCustomSigners() {
        const leaseId = document.getElementById('docusignModal').getAttribute('data-lease-id');
        const selectedSigners = [];
        
        // 收集选中的签署人
        document.querySelectorAll('.signer-checkbox:checked').forEach(checkbox => {
            const index = parseInt(checkbox.value);
            const signerItem = checkbox.closest('.signer-item');
            
            // 检查是否有自定义输入
            const customName = signerItem.querySelector('input[name^="signers"][name$="[name]"]');
            const customEmail = signerItem.querySelector('input[name^="signers"][name$="[email]"]');
            const customType = signerItem.querySelector('input[name^="signers"][name$="[type]"]');
            
            if (customName && customEmail && customType) {
                // 自定义签署人
                selectedSigners.push({
                    name: customName.value,
                    email: customEmail.value,
                    type: customType.value,
                    recipient_id: selectedSigners.length + 1,
                    routing_order: selectedSigners.length + 1,
                });
            } else {
                // 预定义签署人（从服务器数据中获取）
                // 这里需要从之前加载的数据中获取
                const signersData = window.signersData || [];
                if (signersData[index]) {
                    selectedSigners.push({
                        name: signersData[index].name,
                        email: signersData[index].email,
                        type: signersData[index].type,
                        recipient_id: selectedSigners.length + 1,
                        routing_order: selectedSigners.length + 1,
                    });
                }
            }
        });
        
        if (selectedSigners.length === 0) {
            showError('请至少选择一个签署人');
            return;
        }
        
        // 发送请求
        const button = document.querySelector('#docusignModal .btn-primary');
        button.disabled = true;
        const originalText = button.innerHTML;
        
        button.innerHTML = `
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            发送中...
        `;
        
        fetch(`/leases/${leaseId}/send-docusign`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ signers: selectedSigners })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message || '合同已成功发送给所有签署人！');
                setTimeout(() => location.reload(), 3000);
            } else {
                throw new Error(data.message || '发送失败');
            }
        })
        .catch(error => {
            console.error('发送失败:', error);
            showError('发送失败：' + error.message);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
</script>
