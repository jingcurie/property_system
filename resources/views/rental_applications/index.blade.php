@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => '租赁申请列表',
        'pageIcon' => 'bi bi-clipboard-check',
        'createUrl' => route('rental_applications.create'),
        'createLabel' => '申请',
        'exportUrl' => route('rental_applications.export'), // ✅ 导出按钮
    
        'searchKeywordFields' => ['application_code','notes'],
        'filterFields' => [['key' => 'status', 'label' => '状态'], ['key' => 'property_id', 'label' => '房源']],
    
        'records' => $rentalApplications,
        'paginator' => $rentalApplications,
    
        'columns' => [
            ['label' => '编号', 'field' => 'application_code', 'sortable' => true],
    
            [
                'label' => '房源',
                'type' => 'custom',
                'render' => function ($item) {
                    $property = $item->property;
                    if (!$property) {
                        return '-';
                    }
    
                    $address = trim(
                        "{$property->address_street} {$property->address_city}, {$property->address_province} {$property->address_postal_code}");
                    $url = route('properties.show', $property);
    
                    return "<a href=\"{$url}\" target=\"_blank\">" . e($address) . '</a>';
                },
            ],
            ['label' => '申请人', 'field' => 'applicant.full_name'],
            [
                'label' => '租期(月)',
                'field' => 'employment.min_lease_term', // 如果是来自 RentalInfo 表，请使用 with 加载并添加对应 accessor
            ],
            ['label' => '申请时间', 'field' => 'submitted_at', 'sortable' => true],
            ['label' => '审核人', 'field' => 'reviewer.name'],
            [
                'label' => '审核备注',
                'type' => 'custom',
                'render' => function ($item) {
                    $note = trim($item->notes ?? '');
                    if ($note === '') {
                        return '';
                    } //  没备注就不显示图标
    
                    $escapedNote = e($note);
                    return "<i class='bi bi-chat-left-text text-primary'
                                    data-bs-toggle='tooltip'
                                    data-bs-placement='top'
                                    title=\"{$escapedNote}\"
                                    style='cursor: pointer;'>
                                </i>";
                },
            ],
            [
                'label' => '状态',
                'field' => 'status',
                'type' => 'badge',
                'badge_map' => [
                    'submitted' => 'secondary',
                    'under_review' => 'info',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ],
            ],
            [
                'label' => '状态更新时间',
                'type' => 'custom',
                'field' => 'updated_at',
                'sortable' => true,
                'render' => function ($item) {
                    if (!$item->updated_at) {
                        return '-';
                    }
                    return $item->updated_at->timezone('america/vancouver')->format('Y-m-d H:i');
                },
            ],
        ],
    
        'actions' => [
            [
                'label' => '查看',
                'url' => fn($item) => route('rental_applications.show', $item),
                'icon' => 'bi bi-eye',
                'group' => '1'
            ],
            [
                'label' => '编辑',
                'url' => fn($item) => route('rental_applications.edit', $item),
                'icon' => 'bi bi-pencil',
                'group' => '1'
            ],
            [
                'label' => '删除',
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" .
                    route('rental_applications.destroy', $item->id) .
                    "')",
                'group' => '1'
            ],
            [
                'label' => '审核',
                'icon' => 'bi bi-check-circle',
                'url' => fn($item) => 'javascript:void(0);',
                'onclick' => fn($item) => "openReviewStatusModal({$item->id}, '{$item->status}', '" .
                    e($item->review_notes ?? '') .
                    "')",
                'group' => '2'
            ],
        ],
    
        'batchDeleteUrl' => route('rental_applications.batchDelete'),
        'batchApproveUrl' => route('rental_applications.batchApprove'), // ✅ 新增批量通过
        'batchRejectUrl' => route('rental_applications.batchReject'), // ✅ 新增批量拒绝
        'routeName' => 'rental_applications.index',
        'module' => 'rental_applications',
        'partialsForfilter' => 'rental_applications.partials.filter_fields',
    ])

    <!-- ✅ 审核备注模态框 -->
    <div class="modal fade" id="reviewNoteModal" tabindex="-1" aria-labelledby="reviewNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="reviewNoteForm">
                    <div class="modal-header">
                        <h5 class="modal-title">审核备注</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="review_notes" class="form-control" rows="4" placeholder="输入审核备注..."></textarea>
                        <input type="hidden" name="id" id="note_application_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">保存备注</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<div class="modal fade" id="statusReviewModal" tabindex="-1" aria-labelledby="statusReviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="statusReviewForm">
                <div class="modal-header">
                    <h5 class="modal-title">审核状态变更</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="status_application_id">
                    <div class="mb-3">
                        <label for="new_status" class="form-label">新状态</label>
                        <select name="status" id="new_status" class="form-select" required>
                            <option value="submitted">已提交</option>
                            <option value="under_review">审核中</option>
                            <option value="approved">已通过</option>
                            <option value="rejected">已拒绝</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="review_notes" class="form-label">审核备注</label>
                        <textarea name="review_notes" class="form-control" id="review_notes" rows="3"></textarea>
                        <small class="text-muted">备注将追加至现有内容（系统自动记录时间）</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">保存修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // 审核按钮动作：打开 modal 并填入已有值
        function openReviewStatusModal(id, status, note) {
            document.getElementById('status_application_id').value = id;
            document.getElementById('new_status').value = status;
            document.getElementById('review_notes').value = note;
            new bootstrap.Modal(document.getElementById('statusReviewModal')).show();
        }

        // 提交审核状态变更表单
        document.getElementById('statusReviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const id = this.querySelector('[name="id"]').value;
            const status = this.querySelector('[name="status"]').value;
            const notes = this.querySelector('[name="review_notes"]').value;

            // ✅ 可选：提醒备注为空
            // if (!notes.trim()) {
            //     alert('请填写备注内容');
            //     return;
            // }

            fetch(`/rental_applications/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status,
                        review_notes: notes
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // ✅ 自动关闭 Modal
                        bootstrap.Modal.getInstance(document.getElementById('statusReviewModal')).hide();
                        location.reload();
                    } else {
                        alert('状态更新失败');
                    }
                });
        });

        // 启用 Bootstrap Tooltip（带专业配置）
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el, {
                    placement: 'bottom', // 固定在下方
                    fallbackPlacements: [], // 禁止其他位置
                    boundary: 'viewport', // 限定在视口内
                    customClass: 'custom-tooltip' // 可自定义样式
                });
            });
        });
    </script>
@endpush
