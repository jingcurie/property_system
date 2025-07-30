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

    // Toast提示函数
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }
</script>
