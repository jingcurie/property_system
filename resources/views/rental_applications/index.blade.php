@extends('layouts.app')


@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => __('application.list_page_title'),
        'pageIcon' => 'bi bi-clipboard-check',
        'rowClickUrl' => fn($item) => route('rental_applications.show', $item),
        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => __('application.create_application'),
                    'url' => route('rental_applications.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => __('property.export_label'),
                    'url' => route('properties.export', request()->all()),
                    'class' => 'btn btn-outline-secondary',
                ],
            ],
            'selected' => [
                [
                    'type' => 'dropdown',
                    'icon' => 'bi bi-list',
                    'label' => '批量操作',
                    'class' => 'btn btn-secondary dropdown-toggle',
                    'items' => [
                        [
                            'label' => '批量删除',
                            'action' => 'bulk-delete',
                            'icon' => 'bi bi-trash',
                        ],
                        [
                            'label' => '批量审核通过',
                            'action' => 'bulk-approve',
                            'icon' => 'bi bi-check-lg',
                        ],
                        [
                            'label' => '批量拒绝',
                            'action' => 'bulk-reject',
                            'icon' => 'bi bi-x-lg',
                        ],
                    ],
                ],
            ],
        ],
    
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

        'quickFilters' => [
            [
                'key' => 'status',
                'label' => ut('modules.application.filter_status'),
                'column' => 'status',
                'options' => dict('application_status', app()->getLocale()),
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'reviewer',
                'label' => ut('modules.application.filter_reviewer'),
                'type' => 'select',
                'relation' => 'reviewer',
                'column' => 'id',
                'options' => \App\Models\User::pluck('name', 'id')->toArray(),
            ],
            [
                'key' => 'submitted_date',
                'label' => ut('modules.application.filter_submitted_date'),
                'type' => 'date_range',
                'column' => 'submitted_at',
            ],
            [
                'key' => 'reviewed_date',
                'label' => ut('modules.application.filter_reviewed_date'),
                'type' => 'date_range',
                'column' => 'reviewed_at',
            ],
            [
                'key' => 'property_type',
                'label' => ut('modules.application.filter_property_type'),
                'type' => 'select',
                'relation' => 'property',
                'column' => 'property_type',
                'options' => dict('property_type', app()->getLocale()),
            ],
            [
                'key' => 'risk_score',
                'label' => ut('modules.application.filter_risk_score'),
                'type' => 'number_range',
                'column' => 'risk_score',
            ],
        ],
    
        'records' => $rentalApplications,
        'paginator' => $rentalApplications,
    
        'columns' => [
            [
                'label' => __('application.column_application_code'),
                'type' => 'custom',
                'sortable' => true,
                'column' => 'application_code',
                'render' => function ($item) {
                    $code = e($item->application_code ?? 'N/A');
                    $submittedDate = $item->submitted_at ? 
                        (is_string($item->submitted_at) ? 
                            \Carbon\Carbon::parse($item->submitted_at)->format('M d, Y') : 
                            $item->submitted_at->format('M d, Y')
                        ) : 'N/A';
                    
                    return "<div class='d-flex flex-column'>
                                <span class='fw-bold text-dark'>{$code}</span>
                                <span class='text-muted small'>提交: {$submittedDate}</span>
                            </div>";
                },
            ],
            [
                'label' => __('application.column_property'),
                'type' => 'custom',
                'render' => function ($item) {
                    $property = $item->property;
                    if (!$property) {
                        return '<span class="text-muted">-</span>';
                    }
    
                    $propertyName = e($property->property_name ?? '未命名');
                    $address = trim(
                        "{$property->address_street} {$property->address_city}, {$property->address_province} {$property->address_postal_code}");
                    $url = route('properties.show', $property);
    
                    return "<div class='d-flex flex-column'>
                                <a href=\"{$url}\" target=\"_blank\" class='text-primary text-decoration-underline'>{$propertyName}</a>
                                <span class='text-muted small'>" . e($address) . "</span>
                            </div>";
                },
            ],
            [
                'label' => __('application.column_applicant'),
                'type' => 'custom',
                'render' => function ($item) {
                    $applicant = $item->applicants->first();
                    if (!$applicant) {
                        return '<span class="text-muted">-</span>';
                    }
                    
                    $name = e($applicant->full_name ?? 'N/A');
                    $email = e($applicant->email ?? '');
                    $phone = e($applicant->phone ?? '');
                    
                    return "<div class='d-flex flex-column'>
                                <span class='fw-bold text-dark'>{$name}</span>
                                <span class='text-muted small'>{$email}</span>
                                <span class='text-muted small'>{$phone}</span>
                            </div>";
                },
            ],
            [
                'label' => __('application.column_employment'),
                'type' => 'custom',
                'render' => function ($item) {
                    $employment = $item->employment;
                    if (!$employment) {
                        return '<span class="text-muted">-</span>';
                    }
                    
                    $employerName = e($employment->employer_name ?? 'N/A');
                    $jobTitle = e($employment->job_title ?? '');
                    $monthlyIncome = $employment->monthly_income ? '$' . number_format($employment->monthly_income, 2) . '/月' : 'N/A';
                    
                    $employmentInfo = $jobTitle ? "{$employerName} - {$jobTitle}" : $employerName;
                    
                    return "<div class='d-flex flex-column'>
                                <span class='text-dark'>{$employmentInfo}</span>
                                <span class='text-success small'>{$monthlyIncome}</span>
                            </div>";
                },
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
            // [
            //     'label' => __('application.action_delete'),
            //     'url' => fn($item) => 'javascript:void(0);',
            //     'icon' => 'bi bi-trash',
            //     'class' => 'text-danger',
            //     'onclick' => fn($item) => "submitDelete('" .
            //         route('rental_applications.destroy', $item->id) .
            //         "')",
            //     'group' => '1',
            // ],
            [
                'label' => ut('modules.application.action_delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'record-action text-danger',
                'action' => 'delete',
                'group' => '1',
            ],
            [
                'label' => __('application.action_review'),
                'icon' => 'bi bi-check-circle',
                'url' => fn($item) => 'javascript:void(0);',
                'action' => 'review',
                'data' => [
                    'id' => fn($item) => $item->id,
                    'status' => fn($item) => $item->status,
                    'notes' => fn($item) => $item->review_notes ?? '',
                ],
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
            console.log('openReviewStatusModal called with:', {id, status, note});
            
            const modalElement = document.getElementById('statusReviewModal');
            console.log('Modal element:', modalElement);
            
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }
            
            const idInput = document.getElementById('status_application_id');
            const statusSelect = document.getElementById('new_status');
            const notesTextarea = document.getElementById('review_notes');
            
            console.log('Form elements:', {idInput, statusSelect, notesTextarea});
            
            if (idInput) idInput.value = id;
            if (statusSelect) statusSelect.value = status;
            if (notesTextarea) notesTextarea.value = note;
            
            try {
                console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
                console.log('Bootstrap.Modal available:', typeof bootstrap.Modal !== 'undefined');
                
                const modal = new bootstrap.Modal(modalElement);
                console.log('Modal instance created:', modal);
                
                modal.show();
                console.log('Modal.show() called');
            } catch (error) {
                console.error('Error showing modal:', error);
                console.error('Error stack:', error.stack);
            }
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
            console.log('DOM loaded, initializing tooltips');
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