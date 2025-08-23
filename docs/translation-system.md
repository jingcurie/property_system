# 统一翻译系统使用指南

## 概述

统一翻译系统将所有模块的中英文对照集中在一个文件中管理，支持多层级访问和JavaScript翻译。

## 文件结构

```
lang/
├── unified.php          # 统一翻译文件
├── zh/                  # 中文翻译文件（旧系统）
├── en/                  # 英文翻译文件（旧系统）
└── zh_CN/              # 其他语言文件

public/js/
└── translations.js      # JavaScript翻译处理

app/
└── helpers.php          # 翻译辅助函数
```

## 翻译文件结构

### 统一翻译文件 (lang/unified.php)

```php
return [
    'zh' => [
        'common' => [
            'create' => '新增',
            'edit' => '编辑',
            'view' => '查看',
            // ... 通用翻译
        ],
        'modules' => [
            'property' => [
                'page_title' => '房源列表',
                'create_title' => '新增房源',
                // ... 房源模块翻译
            ],
            'application' => [
                'list_page_title' => '租赁申请列表',
                'create_application' => '申请',
                // ... 申请模块翻译
            ],
        ],
        'js' => [
            'confirm_delete' => '确定要删除这条记录吗？',
            'delete_success' => '删除成功',
            // ... JavaScript翻译
        ],
    ],
    'en' => [
        // 英文翻译结构相同
    ],
];
```

## 使用方法

### 1. PHP后端翻译

#### 基础用法
```php
// 使用统一翻译函数
echo ut('common.create');                    // 输出: 新增
echo ut('modules.property.page_title');      // 输出: 房源列表
echo ut('modules.application.create_application'); // 输出: 申请

// 指定语言
echo ut('common.create', 'en');              // 输出: Create
```

#### 在Blade模板中使用
```php
{{-- 替换原来的 __() 函数 --}}
{{-- 原来: __('application.create_application') --}}
{{-- 现在: --}}
{{ ut('modules.application.create_application') }}

{{-- 通用翻译 --}}
{{ ut('common.refresh') }}
{{ ut('common.export') }}
{{ ut('common.delete') }}
```

#### 在控制器中使用
```php
public function index()
{
    return view('properties.index', [
        'pageTitle' => ut('modules.property.page_title'),
        'createLabel' => ut('modules.property.create_title'),
    ]);
}
```

### 2. JavaScript前端翻译

#### 基础用法
```javascript
// 获取翻译文本
const message = js__('confirm_delete');
const successMsg = js__('delete_success');

// 带参数替换
const text = js__('welcome_message', { name: 'John' });
```

#### 确认对话框
```javascript
// 删除确认
confirmDelete(() => {
    // 执行删除操作
    deleteRecord(id);
});

// 批量删除确认
confirmBatchDelete(() => {
    // 执行批量删除
    batchDelete(selectedIds);
});

// 审核确认
confirmReview('approve', () => {
    // 执行审核通过
    approveApplication(id);
});
```

#### 消息提示
```javascript
// 成功消息
showSuccess('delete_success');

// 错误消息
showError('delete_failed');
```

#### 文件上传相关
```javascript
// 文件上传成功
alert(FileTranslations.uploadSuccess());

// 文件太大
alert(FileTranslations.fileTooLarge());
```

#### 搜索相关
```javascript
// 搜索占位符
searchInput.placeholder = SearchTranslations.placeholder();

// 无搜索结果
if (noResults) {
    showMessage(SearchTranslations.noResults());
}
```

## 迁移指南

### 从旧系统迁移

#### 1. 替换翻译函数调用

**原来的方式:**
```php
__('property.page_title')
__('application.create_application')
__('layout.success')
```

**新的方式:**
```php
ut('modules.property.page_title')
ut('modules.application.create_application')
ut('common.success')
```

#### 2. 更新Blade模板

**原来的方式:**
```php
@include('components.pages.index-table', [
    'pageTitle' => __('property.page_title'),
    'createLabel' => __('property.create_label'),
])
```

**新的方式:**
```php
@include('components.pages.index-table', [
    'pageTitle' => ut('modules.property.page_title'),
    'createLabel' => ut('modules.property.create_title'),
])
```

#### 3. 更新JavaScript

**原来的方式:**
```javascript
if (confirm('确定要删除吗？')) {
    // 删除操作
}
```

**新的方式:**
```javascript
confirmDelete(() => {
    // 删除操作
});
```

## 最佳实践

### 1. 翻译键命名规范

- **通用翻译**: `common.xxx`
- **模块翻译**: `modules.{module_name}.xxx`
- **JavaScript翻译**: `js.xxx`

### 2. 层级结构

```
common/           # 通用翻译
├── create        # 新增
├── edit          # 编辑
├── delete        # 删除
└── ...

modules/          # 模块翻译
├── property/     # 房源模块
│   ├── page_title
│   ├── create_title
│   └── ...
├── application/  # 申请模块
│   ├── list_page_title
│   ├── create_application
│   └── ...
└── ...

js/               # JavaScript翻译
├── confirm_delete
├── delete_success
└── ...
```

### 3. 缓存机制

翻译数据会自动缓存1小时，提高性能：
```php
// 缓存键格式
"unified_trans_{key}_{locale}"
"js_translations_{locale}"
```

### 4. 扩展新模块

添加新模块翻译：

1. 在 `lang/unified.php` 中添加新模块
2. 在 `modules` 下创建模块结构
3. 使用 `ut('modules.{module_name}.xxx')` 调用

```php
'modules' => [
    'new_module' => [
        'page_title' => '新模块标题',
        'create_title' => '新增',
        // ...
    ],
],
```

## 优势

1. **统一管理**: 所有翻译集中在一个文件中
2. **层级清晰**: 按模块和功能分类
3. **缓存优化**: 自动缓存提高性能
4. **JavaScript支持**: 完整的前端翻译支持
5. **易于维护**: 修改翻译只需编辑一个文件
6. **向后兼容**: 支持渐进式迁移

## 注意事项

1. 确保 `app/helpers.php` 已加载
2. JavaScript翻译文件 `public/js/translations.js` 已引入
3. 在布局文件中初始化JavaScript翻译
4. 迁移时保持向后兼容性 