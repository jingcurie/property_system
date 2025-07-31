{{-- resources/views/admin/dict/index.blade.php --}}
@extends('layouts.app')

@section('title', '字典管理')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">字典管理</h5>
                    </div>

                    <div class="card-body">
                        {{-- 分组选择区域 --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">选择字典分组</label>
                                <select id="groupSelect" class="form-select">
                                    <option value="">请选择分组</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->description }} ({{ $group->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#groupModal">
                                    <i class="fas fa-plus"></i> 新增分组
                                </button>
                                <button type="button" id="editGroupBtn" class="btn btn-outline-secondary me-2" disabled>
                                    <i class="fas fa-edit"></i> 编辑分组
                                </button>
                                <button type="button" id="deleteGroupBtn" class="btn btn-outline-danger" disabled>
                                    <i class="fas fa-trash"></i> 删除分组
                                </button>
                            </div>
                        </div>

                        {{-- 字典项区域 --}}
                        <div id="itemsArea" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">字典项列表</h6>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#itemModal">
                                    <i class="fas fa-plus"></i> 新增字典项
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30">排序</th>
                                            <th>代码</th>
                                            <th>值</th>
                                            <th>翻译</th>
                                            <th>状态</th>
                                            <th width="120">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTable">
                                        {{-- Ajax动态加载 --}}
                                    </tbody>
                                </table>
                            </div>

                            <div id="emptyState" class="text-center py-5" style="display: none;">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>该分组下暂无字典项</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#itemModal">
                                        新增第一个字典项
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 分组管理弹窗 --}}
    <div class="modal fade" id="groupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">分组管理</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="groupForm">
                    <div class="modal-body">
                        <input type="hidden" id="groupId">
                        <div class="mb-3">
                            <label class="form-label">分组代码 <span class="text-danger">*</span></label>
                            <input type="text" id="groupCode" class="form-control" placeholder="如: property_type"
                                required>
                            <div class="form-text">英文字母、数字、下划线，用于程序调用</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">分组描述 <span class="text-danger">*</span></label>
                            <input type="text" id="groupDescription" class="form-control" placeholder="如: 房产类型" required>
                            <div class="form-text">用于管理界面显示</div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="groupActive" class="form-check-input" checked>
                            <label class="form-check-label">启用状态</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 字典项管理弹窗 --}}
    <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">字典项管理</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="itemForm">
                    <div class="modal-body">
                        <input type="hidden" id="itemId">
                        <input type="hidden" id="itemGroupId">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">代码 <span class="text-danger">*</span></label>
                                    <input type="text" id="itemCode" class="form-control" placeholder="如: apartment"
                                        required>
                                    <div class="form-text">英文字母、数字、下划线</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">值 <span class="text-danger">*</span></label>
                                    <input type="text" id="itemValue" class="form-control" placeholder="如: Apartment"
                                        required>
                                    <div class="form-text">存储和传输使用的值</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">排序</label>
                                    <input type="number" id="itemSort" class="form-control" min="0"
                                        placeholder="自动分配">
                                    <div class="form-text">数字越小越靠前，留空自动分配</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <br>
                                    <div class="form-check">
                                        <input type="checkbox" id="itemActive" class="form-check-input" checked>
                                        <label class="form-check-label">启用状态</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-3">多语言翻译</h6>
                        <div class="row">
                            @foreach ($languages as $code => $name)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $name }} ({{ $code }})</label>
                                    <input type="text" id="translation_{{ $code }}" class="form-control"
                                        placeholder="请输入{{ $name }}翻译">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .bi-grip-vertical {
            cursor: move !important;
            font-size: 16px;
            user-select: none;
        }

        #itemsTable .ui-sortable-helper {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }

        .sortable-placeholder {
            height: 52px;
            background: #e3f2fd;
            border: 2px dashed #2196f3;
            visibility: visible !important;
        }

        .sortable-placeholder td {
            border: none !important;
        }

        .sortable-placeholder:before {
            content: "拖拽到此处";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: #2196f3;
            font-size: 14px;
            pointer-events: none;
        }

        #itemsTable tr {
            user-select: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentGroupId = null;

            // 检查jQuery UI是否加载
            if (typeof $.ui === 'undefined') {
                console.error('jQuery UI 未正确加载！');
                return;
            }

            // 分组选择变化
            $('#groupSelect').change(function() {
                currentGroupId = $(this).val();
                if (currentGroupId) {
                    loadItems(currentGroupId);
                    $('#itemsArea').show();
                    $('#editGroupBtn, #deleteGroupBtn').prop('disabled', false);
                } else {
                    $('#itemsArea').hide();
                    $('#editGroupBtn, #deleteGroupBtn').prop('disabled', true);
                }
            });

            // 加载字典项
            function loadItems(groupId) {
                $.get('/dict/items', {
                        group_id: groupId
                    })
                    .done(function(items) {
                        renderItems(items);
                        // 延迟初始化拖拽，确保DOM完全渲染
                        setTimeout(function() {
                            initSortable();
                        }, 100);
                    })
                    .fail(function() {
                        showToast('加载字典项失败', 'danger');
                    });
            }

            // 渲染字典项表格
            function renderItems(items) {
                let tbody = $('#itemsTable');
                if (tbody.length === 0) {
                    alert("ddd");
                }
                tbody.empty();

                if (items.length === 0) {
                    $('#emptyState').show();
                    return;
                }

                $('#emptyState').hide();

                items.forEach(item => {
                    const translations = Object.entries(item.translations)
                        .map(([lang, label]) =>
                            `<span class="badge bg-light text-dark me-1">${lang}: ${label}</span>`)
                        .join('');

                    tbody.append(`
            <tr data-id="${item.id}" data-item='${JSON.stringify(item)}'>
                <td class="text-center">
                    <div style="display: flex; align-items: center;">
                        <i class="bi bi-grip-vertical text-muted"></i>
                        <span class="text-sm text-muted ms-1">${item.sort_order}</span>
                    </div>
                </td>
                <td><code>${item.code}</code></td>
                <td>${item.value}</td>
                <td>${translations || '<span class="text-muted">无翻译</span>'}</td>
                <td>
                    ${item.is_active ? 
                        '<span class="badge bg-success">启用</span>' : 
                        '<span class="badge bg-secondary">禁用</span>'
                    }
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary edit-item" data-id="${item.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-item" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                });
            }


            // 初始化排序
            function initSortable() {
                // 先销毁旧的sortable
                if ($("#itemsTable").hasClass('ui-sortable')) {
                    $("#itemsTable").sortable('destroy');
                }

                $("#itemsTable").sortable({
                    handle: ".bi-grip-vertical",
                    placeholder: "sortable-placeholder",
                    axis: "y",
                    containment: "parent",
                    tolerance: "pointer",
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                    start: function(e, ui) {
                        ui.placeholder.height(ui.item.outerHeight());
                    },
                    update: function(event, ui) {
                        const items = [];
                        $("#itemsTable tr").each(function() {
                            const itemId = $(this).data('id');
                            if (itemId) {
                                items.push(itemId);
                            }
                        });

                        $.post('/dict/items/sort', {
                            _token: '{{ csrf_token() }}',
                            group_id: currentGroupId,
                            items: items
                        }).done(function() {
                            showToast('排序更新成功', 'success');
                            // 更新显示的序号
                            $("#itemsTable tr").each(function(index) {
                                $(this).find('.text-muted.ms-1').text(index + 1);
                            });
                        }).fail(function() {
                            showToast('排序更新失败', 'danger');
                            loadItems(currentGroupId);
                        });
                    }
                });

                console.log('拖拽排序初始化完成');
            }

            // 分组表单提交
            $('#groupForm').submit(function(e) {
                e.preventDefault();

                const groupId = $('#groupId').val();
                const data = {
                    _token: '{{ csrf_token() }}',
                    code: $('#groupCode').val(),
                    description: $('#groupDescription').val(),
                    is_active: $('#groupActive').is(':checked') ? 1 : 0 // 修复：转换boolean
                };

                const url = groupId ? `/dict/groups/${groupId}` : '/dict/groups';
                const method = groupId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: data
                }).done(function(response) {
                    $('#groupModal').modal('hide');
                    showToast(response.message, 'success');

                    // 更新分组下拉
                    if (!groupId) {
                        $('#groupSelect').append(
                            `<option value="${response.group.id}">${response.group.description} (${response.group.code})</option>`
                            );
                        $('#groupSelect').val(response.group.id).change();
                    } else {
                        const option = $(`#groupSelect option[value="${groupId}"]`);
                        option.text(`${data.description} (${data.code})`);
                    }
                }).fail(function(xhr) {
                    const error = xhr.responseJSON?.message || '操作失败';
                    showToast(error, 'danger');
                });
            });

            // 字典项表单提交
            $('#itemForm').submit(function(e) {
                e.preventDefault();

                const itemId = $('#itemId').val();
                const data = {
                    _token: '{{ csrf_token() }}',
                    group_id: $('#itemGroupId').val(),
                    code: $('#itemCode').val(),
                    value: $('#itemValue').val(),
                    sort_order: $('#itemSort').val(),
                    is_active: $('#itemActive').is(':checked') ? 1 : 0,
                    translations: {}
                };

                // 收集翻译
                @foreach ($languages as $code => $name)
                    data.translations['{{ $code }}'] = $('#translation_{{ $code }}')
                .val();
                @endforeach

                const url = itemId ? `/dict/items/${itemId}` : '/dict/items';
                const method = itemId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: data
                }).done(function(response) {
                    $('#itemModal').modal('hide');
                    showToast(response.message, 'success');
                    loadItems(currentGroupId);
                }).fail(function(xhr) {
                    const error = xhr.responseJSON?.message || '操作失败';
                    showToast(error, 'danger');
                });
            });

            // 编辑分组
            $('#editGroupBtn').click(function() {
                const groupId = $('#groupSelect').val();
                const option = $(`#groupSelect option[value="${groupId}"]`);
                const text = option.text();
                const match = text.match(/^(.+) \((.+)\)$/);

                if (match) {
                    $('#groupId').val(groupId);
                    $('#groupDescription').val(match[1]);
                    $('#groupCode').val(match[2]);
                    $('#groupActive').prop('checked', true);
                    $('.modal-title').text('编辑分组');
                    $('#groupModal').modal('show');
                }
            });

            // 删除分组
            $('#deleteGroupBtn').click(function() {
                const groupId = $('#groupSelect').val();
                showConfirm('确定要删除这个分组吗？分组下的所有字典项也会被删除。', function() {
                    $.ajax({
                        url: `/dict/groups/${groupId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    }).done(function(response) {
                        showToast(response.message, 'success');
                        $(`#groupSelect option[value="${groupId}"]`).remove();
                        $('#groupSelect').val('').change();
                    }).fail(function(xhr) {
                        const error = xhr.responseJSON?.message || '删除失败';
                        showToast(error, 'danger');
                    });
                });
            });

            // 新增字典项
            $('#itemModal').on('show.bs.modal', function() {
                if (!$('#itemId').val()) {
                    // 新增模式
                    $('#itemGroupId').val(currentGroupId);
                    $('.modal-title').text('新增字典项');
                }
            });

            // 编辑字典项
            $(document).on('click', '.edit-item', function() {
                const row = $(this).closest('tr');
                const item = JSON.parse(row.attr('data-item'));

                $('#itemId').val(item.id);
                $('#itemGroupId').val(item.group_id);
                $('#itemCode').val(item.code);
                $('#itemValue').val(item.value);
                $('#itemSort').val(item.sort_order);
                $('#itemActive').prop('checked', item.is_active); // 修复：去掉多余的语法

                // 填充翻译字段
                @foreach ($languages as $code => $name)
                    $('#translation_{{ $code }}').val(item.translations['{{ $code }}'] ||
                        '');
                @endforeach

                $('.modal-title').text('编辑字典项');
                $('#itemModal').modal('show');
            });

            // 删除字典项
            $(document).on('click', '.delete-item', function() {
                const itemId = $(this).data('id');
                showConfirm('确定要删除这个字典项吗？此操作不可恢复。', function() {
                    $.ajax({
                        url: `/dict/items/${itemId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    }).done(function(response) {
                        showToast(response.message, 'success');
                        loadItems(currentGroupId);
                    }).fail(function(xhr) {
                        const error = xhr.responseJSON?.message || '删除失败';
                        showToast(error, 'danger');
                    });
                });
            });

            // 清空表单
            $('#groupModal, #itemModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('input[type="hidden"]').val(''); // 修复：拼写错误
                $('.modal-title').text($(this).attr('id') === 'groupModal' ? '新增分组' : '新增字典项');
            });
        });
    </script>
@endpush
