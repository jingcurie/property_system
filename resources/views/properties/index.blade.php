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
        'pageTitle' => __('property.page_title'),
        'pageIcon' => 'bi bi-houses-fill',
        'createUrl' => route('properties.create'),
        'createLabel' => __('property.create_label'),
        'exportUrl' => route('properties.export', request()->all()),
        'rowClickUrl' => fn($item) => route('properties.show', $item->property_id),
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'property_name',
                'label' => __('property.search_fields.property_name'),
            ],
            [
                'relation' => null,
                'column' => 'address_street',
                'label' => __('property.search_fields.address'),
            ],
            [
                'relation' => null,
                'column' => 'address_city',
                'label' => __('property.search_fields.city'),
            ],
        ],
    
        'quickFilters' => [
            [
                'key' => 'status',
                'label' => __('property.filters.status'),
                'relation' => 'rentalInfo',
                'column' => 'availability_status',
                'options' => [
                    'Available' => __('property.availability_statuses.Available'),
                    'Leased' => __('property.availability_statuses.Leased'),
                    'Under Maintenance' => __('property.availability_statuses.Under_Maintenance'),
                ],
            ],
        ],
    
        'filterFields' => [
            // [
            //     'key' => 'status',
            //     'label' => __('property.filters.status'),
            //     'type' => 'select',
            //     'relation' => 'rentalInfo',
            //     'column' => 'availability_status',
            //     'options' => [
            //         'Available' => __('property.availability_statuses.Available'),
            //         'Leased' => __('property.availability_statuses.Leased'),
            //         'Under Maintenance' => __('property.availability_statuses.Under_Maintenance'),
            //     ],
            // ],
            [
                'key' => 'rent',
                'label' => __('property.filters.monthly_rent'),
                'type' => 'range',
                'relation' => 'rentalInfo',
                'column' => 'monthly_rent',
            ],
            [
                'key' => 'city',
                'label' => __('property.filters.city'),
                'type' => 'text',
                'column' => 'address_city',
            ],
            [
                'key' => 'type',
                'label' => __('property.filters.property_type'),
                'type' => 'select',
                'column' => 'property_type',
                'options' => [
                    'apartment' => __('property.property_types.Apartment'),
                    'house' => __('property.property_types.House'),
                    'townhouse' => __('property.property_types.Townhouse'),
                ],
            ],
        ],
    
        'records' => $properties,
        'paginator' => $properties,
    
        'columns' => [
            [
                'label' => __('property.columns.property_name'),
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
    
                    $html .=
                        '<img onclick="openMediaModal(' .
                        $item->property_id .
                        ')" src="' .
                        $url .
                        '" alt="' .
                        $name .
                        '" style="width: 56px; height: 56px; object-fit: cover; object-position: center; border-radius: 15px;" >';
    
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
                'label' => __('property.columns.address'),
                'columns' => ['address_street', 'address_city'],
                'type' => 'combine',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.type'),
                'column' => 'property_type',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.bedrooms_bathrooms'),
                'type' => 'custom',
                'render' => fn($item) => ($item->feature->bedrooms ?? '-') .
                    ' / ' .
                    ($item->feature->bathrooms ?? '-'),
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.rent'),
                'column' => 'rentalInfo.monthly_rent',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.status'),
                'column' => 'rentalInfo.availability_status',
                'type' => 'badge',
                'badge_map' => [
                    'Available' => 'success',
                    'Leased' => 'secondary',
                    'Under Maintenance' => 'warning',
                ],
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.owners'),
                'type' => 'custom',
                'render' => function ($item) {
                    if (!$item->owners || $item->owners->isEmpty()) {
                        return '<span class="text-muted">-</span>';
                    }
    
                    $owners = $item->owners->map(function ($owner) {
                            return e(trim($owner->first_name . ' ' . $owner->last_name));
                        })->implode(' / ');
    
                    return '<div class="d-flex align-items-center">
                            <i class="bi bi-person-badge me-2 text-secondary"></i>
                            <span>' .
                        $owners .
                        '</span>
                        </div>';
                },
            ],
        ],
    
        'actions' => [
            [
                'label' => __('property.actions.view'),
                'url' => fn($item) => route('properties.show', $item->property_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => __('property.actions.edit'),
                'url' => fn($item) => route('properties.edit', $item->property_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => __('property.actions.delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" .
                    route('properties.destroy', $item->property_id) .
                    "')",
            ],
        ],
    
        'batchDeleteUrl' => route('properties.batchDelete'),
        'routeName' => 'properties.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'properties',
    ])
@endsection
