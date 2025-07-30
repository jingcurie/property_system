@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => __('application.list_page_title'),
        'pageIcon' => 'bi bi-clipboard-check',
        'createUrl' => route('rental_applications.create'),
        'createLabel' => __('application.create_application'),
        'exportUrl' => route('rental_applications.export'),
        'rowClickUrl' => fn($item) => route('rental_applications.show', $item),
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'application_code',
                'label' => __('application.search_application_code'),
            ],
            [
                'relation' => null,
                'column' => 'notes',
                'label' => __('application.search_notes'),
            ],
            [
                'relation' => 'property',
                'column' => 'property_name',
                'label' => __('application.column_property'),
            ],
            [
                'relation' => 'applicants',
                'column' => 'full_name',
                'label' => __('application.column_applicant'),
            ],
        ],  
    
        'filterFields' => [
            [
                'key' => 'status',
                'label' => __('application.filter_status'),
                'type' => 'select',
                'column' => 'status',
                'options' => [
                    'submitted' => __('application.status_submitted'),
                    'under_review' => __('application.status_under_review'),
                    'approved' => __('application.status_approved'),
                    'rejected' => __('application.status_rejected'),
                ],
            ],
        ],
    
        'records' => $rentalApplications,
        'paginator' => $rentalApplications,
    
        'columns' => [
            [
                'label' => __('application.column_application_code'),
                'column' => 'application_code',
                'sortable' => true,
            ],
    
            [
                'label' => __('application.column_property'),
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
            [
                'label' => __('application.column_applicant'),
                'column' => 'applicant.full_name',
            ],
            [
                'label' => __('application.column_lease_term'),
                'column' => 'employment.min_lease_term',
            ],
            [
                'label' => __('application.column_submitted_at'),
                'column' => 'submitted_at',
                'sortable' => true,
            ],
            [
                'label' => __('application.column_reviewer'),
                'column' => 'reviewer.name',
            ],
            [
                'label' => __('application.column_review_notes'),
                'type' => 'custom',
                'render' => function ($item) {
                    $note = trim($item->notes ?? '');
                    if ($note === '') {
                        return '';
                    }
    
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
                'label' => __('application.column_status'),
                'column' => 'status',
                'type' => 'badge',
                'badge_map' => [
                    'submitted' => 'secondary',
                    'under_review' => 'info',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ],
            ],
            [
                'label' => __('application.column_updated_at'),
                'type' => 'custom',
                'column' => 'updated_at',
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
                'label' => __('application.action_view'),
                'url' => fn($item) => route('rental_applications.show', $item),
                'icon' => 'bi bi-eye',
                'group' => '1',
            ],
            [
                'label' => __('application.action_edit'),
                'url' => fn($item) => route('rental_applications.edit', $item),
                'icon' => 'bi bi-pencil',
                'group' => '1',
            ],
            [
                'label' => __('application.action_delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" .
                    route('rental_applications.destroy', $item->id) .
                    "')",
                'group' => '1',
            ],
            [
                'label' => __('application.action_review'),
                'icon' => 'bi bi-check-circle',
                'url' => fn($item) => 'javascript:void(0);',
                'onclick' => fn($item) => "openReviewStatusModal({$item->id}, '{$item->status}', '" .
                    e($item->review_notes ?? '') .
                    "')",
                'group' => '2',
            ],
        ],
    
        'batchDeleteUrl' => route('rental_applications.batchDelete'),
        'batchApproveUrl' => route('rental_applications.batchApprove'),
        'batchRejectUrl' => route('rental_applications.batchReject'),
        'routeName' => 'rental_applications.index',
        'module' => 'rental_applications',
        'partialsForfilter' => 'rental_applications.partials.filter_fields',
    ])

    <!-- 审核备注模态框 -->
    <div class="modal fade" id="reviewNoteModal" tabindex="-1" aria-labelledby="reviewNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="reviewNoteForm">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('application.modal_review_note_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="review_notes" class="form-control" rows="4"
                            placeholder="{{ __('application.modal_review_note_placeholder') }}"></textarea>
                        <input type="hidden" name="id" id="note_application_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('application.modal_save_note') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="statusReviewModal" tabindex="-1" aria-labelledby="statusReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="statusReviewForm">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('application.modal_status_review_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="status_application_id">
                        <div class="mb-3">
                            <label for="new_status" class="form-label">{{ __('application.modal_new_status') }}</label>
                            <select name="status" id="new_status" class="form-select" required>
                                <option value="submitted">{{ __('application.status_submitted') }}</option>
                                <option value="under_review">{{ __('application.status_under_review') }}</option>
                                <option value="approved">{{ __('application.status_approved') }}</option>
                                <option value="rejected">{{ __('application.status_rejected') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="review_notes"
                                class="form-label">{{ __('application.modal_review_notes_label') }}</label>
                            <textarea name="review_notes" class="form-control" id="review_notes" rows="3"></textarea>
                            <small class="text-muted">{{ __('application.modal_review_notes_hint') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('application.modal_save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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
                        bootstrap.Modal.getInstance(document.getElementById('statusReviewModal')).hide();
                        location.reload();
                    } else {
                        alert('{{ __('application.message_update_failed') }}');
                    }
                });
        });

        // 启用 Bootstrap Tooltip
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el, {
                    placement: 'bottom',
                    fallbackPlacements: [],
                    boundary: 'viewport',
                    customClass: 'custom-tooltip'
                });
            });
        });
    </script>
@endpush
