// {{-- ============================================ --}}
// {{-- 1. 媒体预览功能（房源专用） --}}
// {{-- ============================================ --}}

// 打开房源媒体预览模态框
function openMediaModal(propertyId) {
  fetch(`/property/${propertyId}/media`)
    .then((response) => response.json())
    .then((data) => {
      const carouselInner = document.getElementById("carousel-inner");
      carouselInner.innerHTML = "";

      data.forEach((item, index) => {
        const isActive = index === 0 ? "active" : "";
        const fileUrl = `/media/property/${propertyId}/${item.filename}`;

        let content;
        if (item.type === "video") {
          content = `<video controls class="d-block mx-auto" style="max-height: 500px;"><source src="${fileUrl}"></video>`;
        } else {
          content = `<img src="${fileUrl}" class="d-block mx-auto" style="max-height: 500px;">`;
        }

        carouselInner.innerHTML += `
                            <div class="carousel-item ${isActive} text-center" style="padding:2rem">
                                ${content}
                            </div>`;
      });

      const modal = new bootstrap.Modal(document.getElementById("mediaModal"));
      modal.show();
    });
}

// {{-- ============================================ --}}
// {{-- 2. 单记录和批量记录删，恢复和物理删除处理行为 --}}
// {{-- ============================================ --}}
function recordAction(action, ids, module) {
  const isBulk = Array.isArray(ids) && ids.length > 0;
  if (!Array.isArray(ids)) ids = [ids]; // 单条转数组

  // 根据 action 自动生成 URL
  let url;
  if (isBulk) {
    // 批量操作
    if (action === "delete") {
      url = `/${module}/batch-delete`; // 批量软删除
    } else if (action === "restore") {
      url = `/trash/${module}/bulk-restore`;
    } else if (action === "force_delete") {
      url = `/trash/${module}/bulk-force-delete`;
    }
  } else {
    // 单个操作
    if (action === "delete") {
      url = `/${module}/${ids}`; // 单条软删除（DELETE 方法）
    } else if (action === "restore") {
      url = `/trash/${module}/${ids}/restore`; // 单条恢复
    } else if (action === "force_delete") {
      url = `/trash/${module}/${ids}/force-delete`; // 单条彻底删除
    }
  }

  // alert(isBulk + " " + action + " " + url);

  const count = ids.length;
  const confirmText = {
    delete: isBulk
      ? `确定要删除选中的 ${count} 条记录吗？`
      : "确定要删除该记录吗？",
    restore: isBulk
      ? `确定要恢复选中的 ${count} 条记录吗？`
      : "确定要恢复该记录吗？",
    force_delete: isBulk
      ? `确定要彻底删除选中的 ${count} 条记录吗？此操作不可撤销！`
      : "确定要彻底删除该记录吗？此操作不可撤销！",
  }[action];

  showConfirm(confirmText, function () {
    $.ajax({
      url: url,
      type: "POST",
      data: {
        ids: ids,
        _method: !isBulk && action === "delete" ? "DELETE" : "POST",
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        // 成功提示
        const message = isBulk
          ? `成功${
              action === "delete"
                ? "删除"
                : action === "restore"
                ? "恢复"
                : "彻底删除"
            } ${ids.length} 条记录`
          : res.message ||
            (action === "delete"
              ? "删除成功"
              : action === "restore"
              ? "恢复成功"
              : "彻底删除成功");

        showToast(message, "success", "top-center");
        refreshTable();
      },
      error: function (xhr) {
        showToast(
          xhr.responseJSON?.message || "操作失败",
          "danger",
          "top-center"
        );
      },
    });
  });
}

$(document).on("click", ".record-action", function (e) {
  e.preventDefault();

  const action = $(this).data("action"); // delete / restore / force_delete
  const id = $(this).data("id");
  recordAction(action, id, window.moduleName);
});

$(document).on("click", ".bulk-action", function () {
  const action = $(this).data("action");
  const selectedIds = $("input[name='selected_ids[]']:checked")
    .map(function () {
      return $(this).val();
    })
    .get();

  if (selectedIds.length === 0) {
    showToast("请先选择至少一条记录", "warning", "top-center");
    return;
  }

  recordAction(action, selectedIds, window.moduleName);
});

function refreshTable(pageUrl = null) {
  const url = pageUrl || window.location.href;

  $.get(url, function (response) {
    // 从返回的 HTML 中提取 tbody
    const newTbody = $(response).find("#refresh-part").html();
    $("#refresh-part").html(newTbody);
    console.log(newTbody);
  });

  updateTableHeaderStickyOffset();
}

// {{-- ============================================ --}}
// {{-- 3. 高级筛选器功能（动态添加/移除筛选条件） --}}
// {{-- ============================================ --}}
function searchAndFilters(config) {
  // ✅ 去掉注释，使用解构赋值
  const {
    activeFilters: activeFiltersArray,
    filterFields,
    module,
    csrfToken,
  } = config;

  // ✅ 转换为 Set
  const activeFilters = new Set(activeFiltersArray ?? []);

  // 控制筛选器区域显示/隐藏
  function syncFilterVisibility() {
    const row = document.getElementById("filter-row");
    const container = document.getElementById("dynamic-filters");
    const actionBar = document.getElementById("filter-action-bar");
    if (row && container && actionBar) {
      const hasFilters = row.querySelectorAll(".filter-box").length > 0;
      console.log(hasFilters);
      container.style.display = hasFilters ? "block" : "none";
      actionBar.style.display = hasFilters ? "flex" : "none";
    }
  }

  // 添加/移除筛选条件
  function syncFilterState(option) {
    // ✅ 现在可以直接使用 filterFields 和 module 了
    const row = document.getElementById("filter-row");
    const existing = document.querySelector(`[data-filter="${option}"]`);

    if (existing) {
      // 移除筛选器
      const wrapper = existing.closest(".col-md-auto");
      if (wrapper) wrapper.remove();
      activeFilters.delete(option);
      setTimeout(syncFilterVisibility, 0);
    } else {
      // 添加筛选器
      fetch(
        `/filters/field?filter=${option}&filters=${encodeURIComponent(
          JSON.stringify(filterFields)
        )}`
      )
        .then((res) => res.text())
        .then((html) => {
          const wrapper = document.createElement("div");
          wrapper.classList.add("col-md-auto");
          wrapper.innerHTML = html;
          row.appendChild(wrapper);
          activeFilters.add(option);
          setTimeout(syncFilterVisibility, 0);
        });
    }
  }

  // // 筛选器初始化和事件绑定
  // document.addEventListener("DOMContentLoaded", () => {
  //   document.querySelectorAll(".filter-checkbox").forEach((el) => {
  //     const key = el.value;
  //     el.checked = activeFilters.has(key);
  //     el.addEventListener("change", () => syncFilterState(key));
  //   });
  //   setTimeout(syncFilterVisibility, 0);
  // });
  // 使用事件委托
  document.addEventListener("change", (e) => {
    if (e.target.classList.contains("filter-checkbox")) {
      const key = e.target.value;
      syncFilterState(key);
    }
  });

  // 页面初始化时刷新可见性
  document.addEventListener("DOMContentLoaded", () => {
    setTimeout(syncFilterVisibility, 0);
  });

  // 移除筛选器的点击事件
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("remove-filter")) {
      const filterBox = e.target.closest("[data-filter]");
      if (filterBox) {
        const key = filterBox.getAttribute("data-filter");
        const wrapper = filterBox.closest(".col-md-auto");
        if (wrapper) wrapper.remove();
        const checkbox = document.querySelector(
          `.filter-checkbox[value="${key}"]`
        );
        if (checkbox) checkbox.checked = false;
        activeFilters.delete(key);
        setTimeout(syncFilterVisibility, 0);
        // 重新提交表单
        const form = document.getElementById("filter-form");
        if (form) form.submit();
      }
    }
  });
}

// {{-- ============================================ --}}
// {{-- 4. 工具栏切换功能（默认工具栏 ↔ 批量操作工具栏） --}}
// {{-- ============================================ --}}

// 根据选中状态切换工具栏显示
function updateToolbarVisibility() {
  const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
  const checkedCount = document.querySelectorAll(
    'input[name="selected_ids[]"]:checked'
  ).length;
  const defaultToolbar = document.getElementById("toolbar-default");
  const selectedToolbar = document.getElementById("toolbar-selected");
  const countSpan = document.getElementById("selected-count");

  if (checkedCount > 0) {
    defaultToolbar?.classList.add("d-none");
    selectedToolbar?.classList.remove("d-none");
    if (countSpan) countSpan.textContent = checkedCount;
  } else {
    defaultToolbar?.classList.remove("d-none");
    selectedToolbar?.classList.add("d-none");
  }
}

// 复选框事件绑定
document.addEventListener("change", (e) => {
  if (e.target.matches('input[name="selected_ids[]"]')) {
    updateToolbarVisibility();
  }

  if (e.target.matches("#select-all")) {
    const selectAll = e.target;
    document
      .querySelectorAll('input[name="selected_ids[]"]')
      .forEach((cb) => (cb.checked = selectAll.checked));
    updateToolbarVisibility();
  }
});

document.querySelectorAll('.row-checkbox').forEach(checkbox => {
  checkbox.addEventListener('change', () => {
    const allCheckboxes = document.querySelectorAll('.row-checkbox');
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const selectAll = document.querySelector('#select-all');

    // 如果所有行都选中，则全选也选中，否则取消
    selectAll.checked = allCheckboxes.length === checkedBoxes.length;
  });
});

// {{-- ============================================ --}}
// {{-- 6. 表格行点击跳转功能 --}}
// {{-- ============================================ --}}

function handleRowClick(event, url) {
  const ignoreTags = ["A", "BUTTON", "SVG", "INPUT", "LABEL", "IMG"];

  // 1️⃣ 如果是忽略的元素，直接返回
  if (
    ignoreTags.includes(event.target.tagName) ||
    event.target.closest(".no-row-click")
  ) {
    return;
  }

  // 2️⃣ 检查是否点击了第一列（checkbox 所在列）
  const cell = event.target.closest("td");
  if (cell && cell.cellIndex === 0) {
    return; // 第一列（通常是 checkbox 列）不跳转
  }

  // 3️⃣ 允许跳转
  window.location.href = url;
}

// {{-- ============================================ --}}
// {{-- 7. 快速筛选功能（下拉多选筛选器） --}}
// {{-- ============================================ --}}

// 快速筛选全选/全不选切换
document.addEventListener("change", (e) => {
  if (e.target.classList.contains("quick-filter-select-toggle")) {
    const key = e.target.dataset.key;
    const isChecked = e.target.checked;
    document.querySelectorAll(`.quick-filter-${key}`).forEach((cb) => {
      cb.checked = isChecked;
    });
  }
});

// 快速筛选复选框事件
document.querySelectorAll(".quick-filter-checkbox").forEach((cb) => {
  // 阻止点击关闭下拉菜单
  cb.addEventListener("click", (e) => e.stopPropagation());

  // 复选框变化时刷新表格
  cb.addEventListener("change", function () {
    const form = this.closest("form");
    refreshTable(form); // ⚠️ 这个函数未定义！
  });
});

// 全选按钮（已废弃的代码，保留注释）
document.querySelectorAll(".quick-filter-select-all").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    const key = this.dataset.key;
    const checkboxes = document.querySelectorAll(".quick-filter-" + key);
    checkboxes.forEach((cb) => (cb.checked = true));
  });
});

// 全不选按钮（已废弃的代码，保留注释）
document.querySelectorAll(".quick-filter-deselect-all").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    const key = this.dataset.key;
    const checkboxes = document.querySelectorAll(".quick-filter-" + key);
    checkboxes.forEach((cb) => (cb.checked = false));
  });
});

function updateTableHeaderStickyOffset() {
  const stickyHeader = document.querySelector(".sticky-top");
  const tableHeaders = document.querySelectorAll("thead th");

  if (stickyHeader && tableHeaders.length) {
    const offset = stickyHeader.offsetHeight || 0;
    tableHeaders.forEach((th) => {
      th.style.top = `${offset}px`;
    });
  }
}

// {{-- ============================================ --}}
// {{-- 8. Action点击处理 --}}
// {{-- ============================================ --}}

document.addEventListener('click', function(e) {
  if (e.target.closest('.dropdown-item')) {
    const actionItem = e.target.closest('.dropdown-item');
    const action = actionItem.getAttribute('data-action');
    
    if (action === 'review') {
      e.preventDefault();
      const id = actionItem.getAttribute('data-id');
      const status = actionItem.getAttribute('data-status');
      const notes = actionItem.getAttribute('data-notes') || '';
      
      console.log('Review action clicked:', {id, status, notes});
      
      // 调用审核modal函数
      if (typeof openReviewStatusModal === 'function') {
        openReviewStatusModal(id, status, notes);
      } else {
        console.error('openReviewStatusModal function not found');
      }
    }
  }
});
