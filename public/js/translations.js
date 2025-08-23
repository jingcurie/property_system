/**
 * 统一翻译系统 - JavaScript部分
 * 处理前端翻译和国际化
 */

// 全局翻译对象
window.Translations = {};

/**
 * 初始化翻译
 * @param {Object} translations 翻译数据
 */
function initTranslations(translations) {
    window.Translations = translations || {};
}

/**
 * 获取翻译文本
 * @param {string} key 翻译键
 * @param {Object} params 参数替换
 * @returns {string} 翻译后的文本
 */
function __(key, params = {}) {
    let text = window.Translations[key] || key;
    
    // 参数替换
    Object.keys(params).forEach(param => {
        text = text.replace(new RegExp(`{${param}}`, 'g'), params[param]);
    });
    
    return text;
}

/**
 * 获取JavaScript翻译
 * @param {string} key 翻译键
 * @param {Object} params 参数替换
 * @returns {string} 翻译后的文本
 */
function js__(key, params = {}) {
    return __(key, params);
}

/**
 * 确认对话框
 * @param {string} key 翻译键
 * @param {Function} callback 确认回调
 */
function confirmAction(key, callback) {
    const message = js__(key);
    if (confirm(message)) {
        callback();
    }
}

/**
 * 显示成功消息
 * @param {string} key 翻译键
 */
function showSuccess(key) {
    const message = js__(key);
    // 这里可以集成你的消息提示系统
    alert(message);
}

/**
 * 显示错误消息
 * @param {string} key 翻译键
 */
function showError(key) {
    const message = js__(key);
    // 这里可以集成你的消息提示系统
    alert(message);
}

/**
 * 表单验证消息
 * @param {string} field 字段名
 * @param {string} rule 验证规则
 * @returns {string} 验证消息
 */
function getValidationMessage(field, rule) {
    const key = `validation.${field}.${rule}`;
    return js__(key);
}

/**
 * 批量操作确认
 * @param {string} action 操作类型
 * @param {Function} callback 确认回调
 */
function confirmBatchAction(action, callback) {
    const key = `confirm_batch_${action}`;
    confirmAction(key, callback);
}

/**
 * 删除确认
 * @param {Function} callback 确认回调
 */
function confirmDelete(callback) {
    confirmAction('confirm_delete', callback);
}

/**
 * 批量删除确认
 * @param {Function} callback 确认回调
 */
function confirmBatchDelete(callback) {
    confirmAction('confirm_batch_delete', callback);
}

/**
 * 审核确认
 * @param {string} action 审核动作 (approve/reject)
 * @param {Function} callback 确认回调
 */
function confirmReview(action, callback) {
    const key = `confirm_${action}`;
    confirmAction(key, callback);
}

/**
 * 文件上传相关翻译
 */
const FileTranslations = {
    uploadSuccess: () => js__('upload_success'),
    uploadFailed: () => js__('upload_failed'),
    fileTooLarge: () => js__('file_too_large'),
    invalidFileType: () => js__('invalid_file_type'),
};

/**
 * 搜索相关翻译
 */
const SearchTranslations = {
    placeholder: () => js__('search_placeholder'),
    noResults: () => js__('no_search_results'),
    filterApplied: () => js__('filter_applied'),
    filterCleared: () => js__('filter_cleared'),
};

/**
 * 分页相关翻译
 */
const PaginationTranslations = {
    loadingMore: () => js__('loading_more'),
    noMoreData: () => js__('no_more_data'),
};

/**
 * 通用翻译
 */
const CommonTranslations = {
    loading: () => js__('loading'),
    errorOccurred: () => js__('error_occurred'),
    networkError: () => js__('network_error'),
    tryAgain: () => js__('try_again'),
    success: () => js__('success'),
    error: () => js__('error'),
    warning: () => js__('warning'),
    info: () => js__('info'),
};

// 导出到全局
window.__ = __;
window.js__ = js__;
window.confirmAction = confirmAction;
window.confirmDelete = confirmDelete;
window.confirmBatchDelete = confirmBatchDelete;
window.confirmReview = confirmReview;
window.showSuccess = showSuccess;
window.showError = showError;
window.getValidationMessage = getValidationMessage;
window.FileTranslations = FileTranslations;
window.SearchTranslations = SearchTranslations;
window.PaginationTranslations = PaginationTranslations;
window.CommonTranslations = CommonTranslations; 