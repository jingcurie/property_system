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
// {{-- 2. 软删除功能（单个 + 批量） --}}
// {{-- ============================================ --}}

// 删除单条记录
function deleteCurrentRecord(url, id) {
  showConfirm("确定要删除该记录吗？", function () {
    $.ajax({
      url: url,
      type: "POST",
      data: {
        _method: "DELETE",
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        // 从DOM移除该行
        $("#row-" + id).fadeOut(300, function () {
          $(this).remove();
        });
        showToast(res.message || "删除成功", "success", "top-center");
      },
      error: function (xhr) {
        showToast(
          xhr.responseJSON?.message || "删除失败",
          "danger",
          "top-center"
        );
      },
    });
  });
}

// 批量删除功能
document.addEventListener("DOMContentLoaded", () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  document
    .querySelector('[data-action="bulk-delete"]')
    ?.addEventListener("click", function (e) {
      e.preventDefault();

      const selectedCheckboxes = document.querySelectorAll(
        'input[name="selected_ids[]"]:checked'
      );
      const ids = Array.from(selectedCheckboxes).map((cb) => cb.value);

      if (ids.length === 0) {
        showToast("请选择要删除的记录", "warning", "top-center");
        return;
      }

      showConfirm("确定要删除选中的记录吗？", function () {
        fetch("/properties/batch-delete", {
          // 注意：这里URL是硬编码的，可能需要动态化
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            selected_ids: ids,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              ids.forEach((id) => {
                const row = document.querySelector(`#row-${id}`);
                if (row) row.remove();
              });
              showToast(`${data.message}`, "success", "top-center");
            } else {
              showToast(data.message || "删除失败", "danger", "top-center");
            }
          })
          .catch(() => {
            showToast("请求失败，请稍后再试", "danger", "top-center");
          });
      });
    });
});

// {{-- ============================================ --}}
// {{-- 回收站恢复（单个 + 批量） --}}
// {{-- ============================================ --}}

function restoreRecord(url, id) {
  showConfirm("确定要恢复该记录吗？", function () {
    $.ajax({
      url: url,
      type: "POST",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        // 从 DOM 移除该行
        $("#row-" + id).fadeOut(300, function () {
          $(this).remove();
        });
        showToast(res.message || "恢复成功", "success", "top-center");
      },
      error: function (xhr) {
        showToast(
          xhr.responseJSON?.message || "恢复失败",
          "danger",
          "top-center"
        );
      },
    });
  });
}

//回收站批量恢复和物理删除合二为一
function bulkAction(action, module) {
  let selectedIds = $("input[name='selected_ids[]']:checked")
    .map(function () {
      return $(this).val();
    })
    .get();

  if (selectedIds.length === 0) {
    showToast("请先选择至少一条记录", "warning", "top-center");
    return;
  }

  let url =
    action === "restore"
      ? `/trash/${module}/bulk-restore`
      : `/trash/${module}/bulk-force-delete`;

  let confirmText =
    action === "restore"
      ? "确定要恢复选中的记录吗？"
      : "确定要彻底删除选中的记录吗？此操作不可撤销！";

  showConfirm(confirmText, function () {
    $.ajax({
      url: url,
      type: "POST",
      data: {
        ids: selectedIds,
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        showToast(res.message || "操作成功", "success", "top-center");

        // 移除选中的行
        selectedIds.forEach(function (id) {
          $("#row-" + id).fadeOut(300, function () {
            $(this).remove();
          });
        });
      },
      error: function (xhr) {
        showToast(
          xhr.responseJSON?.error || "操作失败",
          "danger",
          "top-center"
        );
      },
    });
  });
}

$(document).on("click", ".dropdown-item[data-action]", function (e) {
  e.preventDefault();

  let action = $(this).data("action"); // 可能是 bulkRestore 或 bulkForceDelete
  let module = window.moduleName || ''; // 后端 Blade 传入的 module 名称

  if (action === "bulkRestore") {
    bulkAction("restore", module);
  } else if (action === "bulkForceDelete") {
    bulkAction("force_delete", module);
  }
});

// {{-- ============================================ --}}
// {{-- 回收站硬删除恢复（单个 + 批量） --}}
// {{-- ============================================ --}}

function forceDeleteRecord(url, id) {
  showConfirm("确定要彻底删除该记录吗？此操作不可撤销！", function () {
    $.ajax({
      url: url,
      type: "POST",
      data: {
        // _method: "DELETE",
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        // 从 DOM 移除该行
        $("#row-" + id).fadeOut(300, function () {
          $(this).remove();
        });
        showToast(res.message || "彻底删除成功", "success", "top-center");
      },
      error: function (xhr) {
        showToast(
          xhr.responseJSON?.message || "删除失败",
          "danger",
          "top-center"
        );
      },
    });
  });
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

  // 筛选器初始化和事件绑定
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".filter-checkbox").forEach((el) => {
      const key = el.value;
      el.checked = activeFilters.has(key);
      el.addEventListener("change", () => syncFilterState(key));
    });
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
document.addEventListener("DOMContentLoaded", () => {
  const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
  const selectAll = document.getElementById("select-all");

  // 单个复选框变化时更新工具栏
  checkboxes.forEach((cb) =>
    cb.addEventListener("change", updateToolbarVisibility)
  );

  // 全选复选框功能
  selectAll?.addEventListener("change", () => {
    checkboxes.forEach((cb) => (cb.checked = selectAll.checked));
    updateToolbarVisibility();
  });
});

// {{-- ============================================ --}}
// {{-- 5. 用户状态切换功能（启用/禁用用户） --}}
// {{-- ============================================ --}}

document.addEventListener("DOMContentLoaded", function () {
  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

  // 用户状态切换开关
  document.querySelectorAll(".toggle-active").forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      const url = this.dataset.url;
      const userId = this.dataset.id;
      const role = this.dataset.role;
      const isActive = this.checked ? 1 : 0;
      const checkboxEl = this;

      // 超级管理员不允许修改状态
      if (role === "admin") {
        Swal.fire({
          icon: "warning",
          title: "无法修改",
          text: "超级管理员状态不允许修改！",
        });
        checkboxEl.checked = !checkboxEl.checked;
        return;
      }

      // 确认操作
      const targetStatus = isActive ? "启用" : "禁用";
      const message = `确定要将该用户状态修改为【${targetStatus}】吗？`;

      Swal.fire({
        icon: "question",
        title: "请确认操作",
        text: message,
        showCancelButton: true,
        confirmButtonText: "确定",
        cancelButtonText: "取消",
      }).then((result) => {
        if (result.isConfirmed) {
          // 执行状态更新
          fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
              is_active: isActive,
            }),
          })
            .then((response) => {
              if (!response.ok) throw new Error("操作失败");
              return response.json();
            })
            .then((data) => {
              console.log(data.message || "状态更新成功");
              // 更新状态文本
              const statusTextEl = checkboxEl
                .closest(".form-check")
                .querySelector(".status-text");
              if (statusTextEl) {
                statusTextEl.textContent = isActive ? "启用" : "禁用";
              }
            })
            .catch((error) => {
              Swal.fire({
                icon: "error",
                title: "状态更新失败",
                text: "请稍后重试",
              });
              checkboxEl.checked = !checkboxEl.checked;
            });
        } else {
          checkboxEl.checked = !checkboxEl.checked;
        }
      });
    });
  });
});

// {{-- ============================================ --}}
// {{-- 6. 表格行点击跳转功能 --}}
// {{-- ============================================ --}}

// 点击表格行跳转（排除按钮、链接等元素）
function handleRowClick(event, url) {
  const ignoreTags = ["A", "BUTTON", "SVG", "INPUT", "LABEL", "IMG"];
  if (
    ignoreTags.includes(event.target.tagName) ||
    event.target.closest(".no-row-click")
  ) {
    return;
  }
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
