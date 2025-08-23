# 统一翻译系统使用指南

## 🎯 系统已就绪！

统一翻译系统已经完全配置好并可以立即使用。以下是实际使用方法和示例。

## 📁 文件结构

```
lang/
├── unified.php          # ✅ 统一翻译文件（已完成）
├── zh/                  # 旧系统文件（可逐步迁移）
└── en/                  # 旧系统文件（可逐步迁移）

public/js/
└── translations.js      # ✅ JavaScript翻译处理（已完成）

app/
└── helpers.php          # ✅ 翻译辅助函数（已完成）

resources/views/layouts/
└── app.blade.php        # ✅ 已集成JavaScript翻译
```

## 🚀 立即开始使用

### 1. PHP后端翻译

#### 在Blade模板中：
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

#### 在控制器中：
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

#### 确认对话框：
```javascript
// 删除确认
confirmDelete(() => {
    deleteRecord(id);
});

// 批量删除确认
confirmBatchDelete(() => {
    batchDelete(selectedIds);
});

// 审核确认
confirmReview('approve', () => {
    approveApplication(id);
});
```

#### 消息提示：
```javascript
// 成功消息
showSuccess('delete_success');

// 错误消息
showError('update_failed');
```

#### 文件上传相关：
```javascript
// 文件上传成功
alert(FileTranslations.uploadSuccess());

// 文件太大
alert(FileTranslations.fileTooLarge());
```

## 📋 迁移检查清单

### ✅ 已完成
- [x] 统一翻译文件创建
- [x] 辅助函数配置
- [x] JavaScript翻译集成
- [x] 布局文件更新
- [x] Rental Application模块迁移示例

### 🔄 待迁移模块
- [ ] Property模块
- [ ] Lease模块  
- [ ] User模块
- [ ] 其他模块

## 🔧 迁移步骤

### 步骤1：替换翻译函数调用

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

### 步骤2：更新Blade模板

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

### 步骤3：更新JavaScript

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

## 📊 翻译键对照表

### 通用翻译 (common.*)
| 旧键 | 新键 | 中文 | 英文 |
|------|------|------|------|
| `layout.success` | `common.success` | 成功 | Success |
| `layout.error` | `common.error` | 错误 | Error |
| `layout.confirm` | `common.confirm` | 确认 | Confirm |
| `layout.cancel` | `common.cancel` | 取消 | Cancel |

### 模块翻译 (modules.*)
| 旧键 | 新键 | 中文 | 英文 |
|------|------|------|------|
| `application.list_page_title` | `modules.application.list_page_title` | 租赁申请列表 | Rental Application List |
| `application.create_application` | `modules.application.create_application` | 申请 | Apply |
| `property.page_title` | `modules.property.page_title` | 房源列表 | Property List |

### JavaScript翻译 (js.*)
| 旧键 | 新键 | 中文 | 英文 |
|------|------|------|------|
| `application.message_update_failed` | `js.update_failed` | 更新失败 | Update failed |
| `application.confirm_delete` | `js.confirm_delete` | 确定要删除这条记录吗？ | Are you sure you want to delete this record? |

## 🎨 实际使用示例

### 示例1：Property模块迁移

**原来的代码:**
```php
@include('components.pages.index-table', [
    'pageTitle' => __('property.page_title'),
    'createLabel' => __('property.create_label'),
    'toolbar' => [
        'default' => [
            [
                'label' => __('property.create_label'),
                'url' => route('properties.create'),
            ],
            [
                'label' => __('property.export_label'),
                'url' => route('properties.export'),
            ],
        ],
    ],
])
```

**迁移后的代码:**
```php
@include('components.pages.index-table', [
    'pageTitle' => ut('modules.property.page_title'),
    'createLabel' => ut('modules.property.create_title'),
    'toolbar' => [
        'default' => [
            [
                'label' => ut('modules.property.create_title'),
                'url' => route('properties.create'),
            ],
            [
                'label' => ut('common.export'),
                'url' => route('properties.export'),
            ],
        ],
    ],
])
```

### 示例2：JavaScript迁移

**原来的代码:**
```javascript
if (confirm('确定要删除这条记录吗？')) {
    deleteRecord(id);
}

if (data.success) {
    alert('删除成功');
} else {
    alert('删除失败');
}
```

**迁移后的代码:**
```javascript
confirmDelete(() => {
    deleteRecord(id);
});

if (data.success) {
    showSuccess('delete_success');
} else {
    showError('delete_failed');
}
```

## 🚀 优势总结

1. **统一管理**: 所有翻译集中在一个文件中
2. **层级清晰**: 按模块和功能分类
3. **缓存优化**: 自动缓存提高性能
4. **JavaScript支持**: 完整的前端翻译支持
5. **易于维护**: 修改翻译只需编辑一个文件
6. **向后兼容**: 支持渐进式迁移

## 📝 下一步

1. **逐步迁移其他模块**: 按照示例迁移Property、Lease等模块
2. **添加新翻译**: 在`lang/unified.php`中添加新的翻译键
3. **测试验证**: 确保所有翻译正常工作
4. **清理旧文件**: 迁移完成后可以删除旧的翻译文件

现在你可以开始使用这个统一的翻译系统了！🎉 