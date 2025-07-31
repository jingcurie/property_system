{{-- Tabs 导航 --}}

@php
    $feature = $property->feature;
    $amenity = $property->amenity;
    $rental = $property->rentalInfo;
    $financial = $property->financialInfo;
    $compliance = $property->complianceInfo;
    $utilities = collect(explode(',', $rental->utilities_included ?? ''));

    use Illuminate\Support\Str;
@endphp


<ul class="nav tab-line-tabs" id="propertyTabs" role="tablist">
    @foreach([
        'summary' => 'Summary',
        'owners' => 'Owners',
        'tenants' => 'Tenants',
        'leases' => 'Leases',
        'financials' => 'Financials',
        'files' => 'Files',
        'maintenance' => 'Maintenance',
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
    <div class="tab-pane fade show active" id="tab-summary">@include('properties.partials.tabs.summary')</div>
    <div class="tab-pane fade" id="tab-owners">@include('properties.partials.tabs.owners')</div>
    <div class="tab-pane fade" id="tab-tenants">@include('properties.partials.tabs.tenants')</div>
    <div class="tab-pane fade" id="tab-leases">@include('properties.partials.tabs.leases')</div>
    <div class="tab-pane fade" id="tab-financials"></div>
    <div class="tab-pane fade" id="tab-files">@include('properties.partials.tabs.files')</div>
    <div class="tab-pane fade" id="tab-maintenance"></div>
    <div class="tab-pane fade" id="tab-events"></div>
</div>


<script>
    function saveOwner() {
        const form = document.getElementById('addOwnerForm');
        const ownerId = document.getElementById('owner_id').value;
        const propertyId = document.querySelector('input[name=property_id]').value; // 或你传进来的变量
        const formData = new FormData(form);

        let url = '';
        let method = '';

        if (ownerId) {
            // 编辑模式
            url = `/properties/${propertyId}/owners/${ownerId}`;
            method = 'PUT';
        } else {
            // 新增模式
            url = `/properties/${propertyId}/owners`;
            method = 'POST';
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    // 'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                $('#addOwnerModal').modal('hide');
                // 可选：刷新页面 / 更新表格 / 关闭Modal
                location.reload(); // 或用Ajax更新表格
            })
            .catch(error => {
                console.error('Error:', error);
                alert('保存失败，请检查输入');
            });
    }
</script>

<script>
    // 默认恢复表单状态（新增模式）
    function resetOwnerModal() {
        $('#addOwnerForm').trigger("reset");
        $('#addOwnerModalLabel').text("新增业主");
        $('#addOwnerForm').attr("action", "{{ route('owners.store') }}");
        $('#addOwnerForm').find('input[name="_method"]').val('POST');
        $('#owner_id').val('');
    }

    // 点击“编辑”按钮时触发，传入 owner 对象
    function editOwner(owner) {
        resetOwnerModal(); // 清空旧数据
        $('#addOwnerModalLabel').text("编辑业主");

        // 打开模态框


        // 设置提交 URL 与 Method
        const updateUrl = `{{ url('owners') }}/${owner.owner_id}`; // 假设是 RESTful 路由
        $('#addOwnerForm').attr("action", updateUrl);
        $('#addOwnerForm').find('input[name="_method"]').val('PUT');
        console.log(owner);
        // 填充字段
        $('#addOwnerForm').find('input[name="first_name"]').val(owner.first_name);
        $('#addOwnerForm').find('input[name="last_name"]').val(owner.last_name);
        $('#addOwnerForm').find('input[name="phone"]').val(owner.phone);
        $('#addOwnerForm').find('input[name="email"]').val(owner.email);
        $('#addOwnerForm').find('input[name="emergency_contact"]').val(owner.emergency_contact);
        $('#addOwnerForm').find('input[name="emergency_contact_phone"]').val(owner.emergency_contact_phone);
        $('#addOwnerForm').find('input[name="address"]').val(owner.address);
        $('#addOwnerForm').find('input[name="tax_id"]').val(owner.tax_id);
        $('#addOwnerForm').find('input[name="notes"]').val(owner.notes);
        $('#addOwnerForm').find('input[name="ownership_percentage"]').val(owner.pivot.ownership_percentage);
        $('#addOwnerForm').find('input[name="start_date"]').val(owner.pivot.start_date);
        $('#addOwnerForm').find('input[name="end_date"]').val(owner.pivot.end_date);
        $('#owner_id').val(owner.owner_id);

        //    const modal = new bootstrap.Modal(document.getElementById('addOwnerModal'));
        modal.show();
    }

    // 示例绑定（根据你的结构调整）
    $(document).on('click', '.btn-edit-owner', function() {
        const owner = $(this).data('owner'); // 从 data-owner 属性取出对象
        editOwner(owner);
    });

    $(document).on('click', '[data-bs-target="#addOwnerModal"]', function() {
        // resetOwnerModal(); // 每次点击都重置表单
    });
</script>
