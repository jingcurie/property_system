{{-- 
|***************************************************************************
| 字段配置参数说明（控制器中的预加载用法说明：）
|***************************************************************************
| 为了支持关联表字段的搜索与筛选，控制器中需使用 Eloquent 的 with() 方法预加载关联关系：
| ➤ 使用 with([...]) 的目的：
|   - 提前预加载关联数据，避免后续列表中使用属性时触发 N+1 查询
|   - 提高页面加载效率，避免循环中大量重复 SQL 查询
|   - 可用于前端展示如“房源名称”、“申请人姓名”、“工作单位”等字段
|
| 示例：
| $query = Property::with([
|        'rentalInfo',   //对外广告出租信息，非实际出租信息
|        'ownership.owner', //房主关联信息
|        'media',  //图片视频信息，注意和files是分开的，目前还没合并
|        ])->whereNull('deleted_at');
|
| 上述关联方法应在 Property 模型中定义，如：
|  public function feature(){
|    return $this->hasOne(PropertyFeature::class, 'property_id', 'property_id');
|  }
|
|  public function amenity() {
|    return $this->hasOne(Amenity::class, 'property_id', 'property_id');
|  }
|
|***************************************************************************
| *** 字段配置参数说明（适用于 searchKeywordFields 和 filterFields）***
|***************************************************************************
|
| 所有字段统一采用结构化配置，以下是各字段的含义及示例说明：
|
| 一、searchKeywordFields：关键字模糊搜索字段（支持主表和关联表）
| ------------------------------------------------------------------------
| 用于顶部关键字搜索栏，匹配用户输入的 keyword 内容。
| 每项为一个字段对象，字段格式如下：
|
| [
|     'relation' => '表关系名称',     // 字段所属的 Eloquent 关联关系（如 rentalInfo、owner），主表字段则为 null
|     'column'   => '字段名',         // 实际数据库字段名，用于 where 或 whereHas
|     'label'    => '显示名称',        // 显示在 placeholder 或 UI 提示中的字段名称（支持翻译函数）
| ]
|
| 示例：
| [
|     'relation' => null,
|     'column' => 'property_name',
|     'label' => __('property.search_fields.property_name'),
| ]
| 代表：搜索主表字段 `property_name`，前端显示为“房源名称”
|
| [
|     'relation' => 'rentalInfo',
|     'column' => 'monthly_rent',
|     'label' => __('property.search_fields.monthly_rent'),
| ]
| 代表：搜索关联表 `rentalInfo` 中的 `monthly_rent` 字段
|
|
| 二、filterFields：筛选字段（支持 select / range / text 等类型）
| ------------------------------------------------------------------------
| 用于页面中的高级筛选区块，每项为一个字段过滤条件，格式如下：
|
| [
|     'key'      => 'url参数名',           // 用于 URL 查询参数和表单字段的名称
|     'label'    => '筛选标签',            // 用于筛选表单 UI 显示的文字（支持翻译函数）
|     'type'     => '筛选类型',            // 可选值包括：select、text、range、date、checkbox 等
|     'relation' => '表关系名称',         // 字段所属的 Eloquent 关联关系（如 rentalInfo、owner），主表字段则为 null 或省略
|     'column'   => '字段名',              // 实际数据库字段名
|     'options'  => [                      // 如果是 select 类型，需提供选项列表
|         'value1' => '显示名称1',
|         'value2' => '显示名称2',
|     ],
| ]
|
| 示例：
| [
|     'key' => 'status',
|     'label' => __('property.filters.status'),
|     'type' => 'select',
|     'relation' => 'rentalInfo',
|     'column' => 'availability_status',
|     'options' => [
|         'Available' => __('property.availability_statuses.Available'),
|         'Leased' => __('property.availability_statuses.Leased'),
|         'Under Maintenance' => __('property.availability_statuses.Under_Maintenance'),
|     ],
| ]
| 表示：筛选关联表 rentalInfo 中的 availability_status 字段，渲染为下拉框
|
| 如果 type = 'range'，系统会自动渲染两个区间输入框（如最小租金 / 最大租金）
| 如果 type = 'text'，系统会渲染一个文本输入框，用于精确匹配

|***************************************************************************
| *** columns 字段配置参数说明（表格列显示配置）***
|***************************************************************************
|
| columns 配置用于定义数据表格中的列显示方式，支持多种显示类型和交互功能。
| 每个列配置为一个数组对象，支持以下参数：
|
| 一、基础列配置参数
| ------------------------------------------------------------------------
| [
|     'label'    => '列标题',              // 表头显示的文字（支持翻译函数）
|     'column'   => '字段名',              // 数据字段名，支持关联字段如 'relation.field'
|     'sortable' => true/false,           // 是否支持排序（可选，默认false）
|     'type'     => '显示类型',            // 列的显示类型（可选，默认'text'）
| ]
|
| 二、支持的显示类型（type）
| ------------------------------------------------------------------------
| 
| 1. 'text'（默认类型）- 简单文本显示
| [
|     'label' => __('property.columns.type'),
|     'column' => 'property_type',
|     'sortable' => true,
| ]
| 
| 2. 'custom' - 自定义渲染（通过render函数）
| [
|     'label' => __('property.columns.property_name'),
|     'column' => 'property_name',
|     'type' => 'custom',
|     'render' => function ($item) {
|         // 自定义HTML渲染逻辑
|         return '<div class="custom-content">' . $item->property_name . '</div>';
|     },
|     'sortable' => true,
| ]
| 
| 3. 'combine' - 组合多个字段显示
| [
|     'label' => __('property.columns.address'),
|     'columns' => ['address_street', 'address_city'],  // 注意：使用 'columns'（复数）
|     'type' => 'combine',
|     'sortable' => true,
| ]
| 
| 4. 'badge' - 徽章样式显示（带颜色状态）
| [
|     'label' => __('property.columns.status'),
|     'column' => 'rentalInfo.availability_status',
|     'type' => 'badge',
|     'badge_map' => [                      // 值与Bootstrap徽章样式的映射
|         'Available' => 'success',        // bg-success
|         'Leased' => 'secondary',         // bg-secondary  
|         'Under Maintenance' => 'warning', // bg-warning
|     ],
|     'sortable' => true,
| ]
|
| 三、关联字段配置
| ------------------------------------------------------------------------
| 支持通过点号语法访问关联表字段：
| 
| [
|     'column' => 'rentalInfo.monthly_rent',        // 一层关联
|     'column' => 'ownership.owner.full_name',      // 多层关联
| ]
| 
| ⚠️ 注意：使用关联字段时，必须在Controller中预加载对应关系：
| $query = Property::with(['rentalInfo', 'ownership.owner']);
|
| 四、actions 操作按钮配置
| ------------------------------------------------------------------------
| actions 数组定义每行数据的操作按钮（查看、编辑、删除等）：
| 
| 'actions' => [
|     [
|         'label' => __('property.actions.view'),           // 按钮文字
|         'url' => fn($item) => route('properties.show', $item->property_id), // 链接地址
|         'icon' => 'bi bi-eye',                           // Bootstrap Icons图标
|         'class' => 'text-primary',                       // CSS样式类（可选）
|     ],
|     [
|         'label' => __('property.actions.delete'),
|         'url' => fn($item) => 'javascript:void(0);',     // JavaScript操作
|         'icon' => 'bi bi-trash',
|         'class' => 'text-danger',
|         'onclick' => fn($item) => "submitDelete('" . 
|             route('properties.destroy', $item->property_id) . "')", // 点击事件
|     ],
| ]
|
| 五、render函数高级用法
| ------------------------------------------------------------------------
| render 函数接收当前行数据 $item 作为参数，可以访问所有预加载的关联数据：
| 
| 'render' => function ($item) {
|     // 访问主表字段
|     $name = e($item->property_name ?? '未命名');
|     
|     // 访问关联表数据（需要预加载）
|     $cover = $item->media->firstWhere('is_cover', 1);
|     
|     // 条件判断
|     if ($cover) {
|         $url = url('/media/property/' . $item->property_id . '/' . basename($cover->file_path));
|     } else {
|         $url = asset('images/default_property_cover_image.png');
|     }
|     
|     // 返回自定义HTML
|     return '<div class="d-flex align-items-center">
|                 <img src="' . $url . '" class="rounded me-2" style="width:40px;height:40px;">
|                 <span>' . $name . '</span>
|             </div>';
| }
|
| 六、排序配置说明
| ------------------------------------------------------------------------
| 当 'sortable' => true 时，系统会自动生成排序功能：
| 
| - 简单字段：直接按字段值排序
| - 关联字段：通过子查询实现关联表排序
| - 组合字段：按第一个字段排序
| - 自定义字段：需要手动指定排序字段（通过 sort_field 参数）
|
| 示例：为自定义字段指定排序字段
| [
|     'type' => 'custom',
|     'render' => function($item) { ... },
|     'sortable' => true,
|     'sort_field' => 'property_name',        // 指定实际排序字段
| ]
|
|
| ⚠️ 注意事项：
| - 若使用关联字段，请确保控制器中调用 with([...]) 正确预加载对应关系，避免 N+1 问题
| - 控制器中将根据 relation + column 自动构造 where 或 whereHas 查询
| - 所有字段建议配合数据库真实字段名命名，避免 UI 与字段映射不清晰
| - 所有 label 推荐使用翻译函数，确保支持国际化
| - 如果字段值在数据库中为数值型，请使用 range 或 select 进行筛选（避免模糊搜索）
| --}}


@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => ut('modules.property.page_title'),
        'pageIcon' => 'bi bi-houses-fill',
        // 'createUrl' => route('properties.create'),
        // 'createLabel' => __('property.create_label'),
        // 'exportUrl' => route('properties.export', request()->all()),
        'rowClickUrl' => fn($item) => route('properties.show', $item->property_id),
    
        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => ut('modules.property.create_title'),
                    'url' => route('properties.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => ut('common.export'),
                    'url' => route('properties.export', request()->all()),
                    'class' => 'btn btn-outline-secondary',
                ],
                [
                    'type' => 'button',
                    'icon' => 'bi bi-arrow-clockwise',
                    'label' => ut('common.refresh'),
                    'class' => 'btn btn-outline-secondary',
                    'onclick' => 'window.location.reload()',
                ],
            ],
            'selected' => [
                [
                    'type' => 'dropdown',
                    'icon' => 'bi bi-list',
                    'label' => ut('common.batch_operations'),
                    'class' => 'btn btn-secondary dropdown-toggle',
                    'items' => [
                        [
                            'label' => ut('common.batch_delete'),
                            'action' => 'delete',
                            'icon' => 'bi bi-trash',
                        ],
                        [
                            'label' => ut('common.batch_export'),
                            'action' => 'export',
                            'icon' => 'bi bi-download',
                        ],
                    ],
                ],
            ],
        ],
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'property_name',
                'label' => ut('modules.property.search_property_name'),
            ],
            [
                'relation' => null,
                'column' => 'address_street',
                'label' => ut('modules.property.search_address'),
            ],
            [
                'relation' => null,
                'column' => 'address_city',
                'label' => ut('modules.property.search_city'),
            ],
        ],
    
        'quickFilters' => [
            [
                'key' => 'status',
                'label' => ut('modules.property.availability_status'),
                'relation' => 'rentalInfo',
                'column' => 'availability_status',
                'options' => dict('availability_status', app()->getLocale()),
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'rent',
                'label' => ut('modules.property.monthly_rent'),
                'type' => 'number_range',
                'relation' => 'rentalInfo',
                'column' => 'monthly_rent',
            ],
            [
                'key' => 'city',
                'label' => ut('modules.property.city'),
                'type' => 'text',
                'column' => 'address_city',
            ],
            [
                'key' => 'type',
                'label' => ut('modules.property.property_type'),
                'type' => 'select',
                'column' => 'property_type',
                'options' => dict('property_type', app()->getLocale()),
            ],
            [
                'key' => 'bedrooms',
                'label' => ut('modules.property.bedrooms'),
                'type' => 'select',
                'relation' => 'feature',
                'column' => 'bedrooms',
                'options' => [
                    '1' => '1 卧室',
                    '2' => '2 卧室',
                    '3' => '3 卧室',
                    '4' => '4+ 卧室',
                ],
            ],
            [
                'key' => 'square_footage',
                'label' => ut('modules.property.square_footage'),
                'type' => 'number_range',
                'relation' => 'feature',
                'column' => 'square_footage',
            ],
            [
                'key' => 'parking',
                'label' => ut('modules.property.parking_spaces'),
                'type' => 'select',
                'relation' => 'feature',
                'column' => 'parking_spaces',
                'options' => [
                    '0' => '无停车位',
                    '1' => '1个停车位',
                    '2' => '2个停车位',
                    '3' => '3+个停车位',
                ],
            ],
        ],
    
        'records' => $properties,
        'paginator' => $properties,
    
        'columns' => [
            [
                'label' => ut('modules.property.column_property_name'),
                'column' => 'property_name',
                'type' => 'custom',
                'render' => function ($item) {
                    $name = e($item->property_name ?? '未命名');
                    $cover = $item->media->firstWhere('is_cover', 1);
                    $address = implode(', ', array_filter([$item->address_city, $item->address_province]));

                    $html = '<div class="d-flex align-items-center text-decoration-none gap-3">';

                    if ($cover) {
                        $url = url('/media/property/' . $item->property_id . '/' . basename($cover->file_path));
                    } else {
                        $url = asset('images/default_property_cover_image.png');
                    }

                    $html .= '<div class="position-relative">';
                    $html .= '<img onclick="openMediaModal(' . $item->property_id . ')" 
                                   src="' . $url . '" 
                                   alt="' . $name . '" 
                                   style="width: 56px; height: 56px; object-fit: cover; object-position: center; border-radius: 15px;" 
                                   onerror="this.src=\'' . asset('images/default_property_cover_image.png') . '\'">';
                    $html .= '</div>';

                    $html .= '<div class="d-flex flex-column">';
                    $html .= '<span class="text-body fw-medium">' . $name . '</span>';
                    $html .= '<span class="text-muted small">' . e($item->address_street) . '</span>';
                    $html .= '<span class="text-muted small">' . e($address) . '</span>';
                    $html .= '</div></div>';

                    return $html;
                },
                'sortable' => true,
            ],
            [
                'label' => ut('modules.property.column_type'),
                'column' => 'property_type',
                'type' => 'badge',
                'badge_map' => dict_colors('property_type'),
                'sortable' => true,
            ],
            [
                'label' => ut('modules.property.column_bedrooms_bathrooms'),
                'type' => 'custom',
                'render' => function($item) {
                    $bedrooms = $item->feature->bedrooms ?? '-';
                    $bathrooms = $item->feature->bathrooms ?? '-';
                    $squareFootage = $item->feature->square_footage ?? null;
                    
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="fw-medium">' . $bedrooms . ' / ' . $bathrooms . '</span>';
                    if ($squareFootage) {
                        $html .= '<span class="text-muted small">' . number_format($squareFootage) . ' sq ft</span>';
                    }
                    $html .= '</div>';
                    
                    return $html;
                },
                'sortable' => true,
            ],
            [
                'label' => ut('modules.property.column_rent'),
                'column' => 'rentalInfo.monthly_rent',
                'type' => 'custom',
                'render' => function($item) {
                    $rent = $item->rentalInfo->monthly_rent ?? 0;
                    $deposit = $item->rentalInfo->security_deposit ?? 0;
                    $availableDate = $item->rentalInfo->available_date ?? null;
                    
                    $html = '<div class="d-flex flex-column">';
                    if ($rent > 0) {
                        $html .= '<span class="fw-bold text-success">$' . number_format($rent, 2) . '</span>';
                        if ($deposit > 0) {
                            $html .= '<span class="text-muted small">押金: $' . number_format($deposit, 2) . '</span>';
                        }
                    } else {
                        $html .= '<span class="text-muted">-</span>';
                    }
                    if ($availableDate) {
                        $html .= '<span class="text-info small">可用: ' . \Carbon\Carbon::parse($availableDate)->format('M d') . '</span>';
                    }
                    $html .= '</div>';
                    
                    return $html;
                },
                'sortable' => true,
            ],
            [
                'label' => ut('modules.property.column_status'),
                'column' => 'rentalInfo.availability_status',
                'type' => 'badge',
                'badge_map' => [
                    'Available' => 'success',
                    'Leased' => 'secondary',
                    'Under Maintenance' => 'warning',
                    'Reserved' => 'info',
                ],
                'sortable' => true,
            ],
            [
                'label' => ut('modules.property.column_owners'),
                'type' => 'custom',
                'render' => function ($item) {
                    if (!$item->owners || $item->owners->isEmpty()) {
                        return '<span class="text-muted">-</span>';
                    }

                    $owners = $item->owners->map(function ($owner) {
                        $name = trim($owner->first_name . ' ' . $owner->last_name);
                        $percentage = $owner->pivot->ownership_percentage ?? null;
                        
                        if ($percentage) {
                            return e($name) . ' (' . $percentage . '%)';
                        }
                        return e($name);
                    })->implode(' / ');

                    return '<div class="d-flex align-items-center">
                                <i class="bi bi-person-badge me-2 text-secondary"></i>
                                <span class="small">' . $owners . '</span>
                            </div>';
                },
            ],
            [
                'label' => ut('modules.property.column_features'),
                'type' => 'custom',
                'render' => function ($item) {
                    $features = [];
                    
                    // 停车信息
                    if ($item->feature->parking_spaces ?? 0) {
                        $features[] = '<i class="bi bi-car-front text-primary" title="停车位"></i> ' . $item->feature->parking_spaces;
                    }
                    
                    // 设施信息
                    if ($item->amenity) {
                        if ($item->amenity->has_gym) $features[] = '<i class="bi bi-dumbbell text-success" title="健身房"></i>';
                        if ($item->amenity->has_pool) $features[] = '<i class="bi bi-water text-info" title="游泳池"></i>';
                        if ($item->amenity->has_balcony) $features[] = '<i class="bi bi-sun text-warning" title="阳台"></i>';
                    }
                    
                    if (empty($features)) {
                        return '<span class="text-muted">-</span>';
                    }
                    
                    return '<div class="d-flex gap-1">' . implode('', $features) . '</div>';
                },
            ],
        ],
    
        'actions' => [
            [
                'label' => ut('modules.property.action_view'),
                'url' => fn($item) => route('properties.show', $item->property_id),
                'icon' => 'bi bi-eye',
                'class' => 'text-primary',
            ],
            [
                'label' => ut('modules.property.action_edit'),
                'url' => fn($item) => route('properties.edit', $item->property_id),
                'icon' => 'bi bi-pencil-square',
                'class' => 'text-warning',
            ],
            [
                'label' => ut('modules.property.action_application_management'),
                'url' => fn($item) => route('rental_applications.index', ['property_id' => $item->property_id]),
                'icon' => 'bi bi-file-earmark-text',
                'class' => 'text-info',
            ],
            [
                'label' => ut('modules.property.action_media_management'),
                'url' => fn($item) => 'javascript:openMediaModal(' . $item->property_id . ')',
                'icon' => 'bi bi-images',
                'class' => 'text-secondary',
            ],
            [
                'label' => ut('modules.property.action_delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'record-action text-danger',
                'action' => 'delete'
            ],
        ],
    
        'batchDeleteUrl' => route('properties.batchDelete'),
        'routeName' => 'properties.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'properties',
        'countPerPage' => 25,
    ])
@endsection
