// // {{-- ============================================ --}}
// // {{-- 5. 通知 --}}
// // {{-- ============================================ --}}
function toggleNotificationPanel() {
  const panel = document.getElementById("notificationPanel");
  const isVisible = panel.classList.contains("show");

  if (isVisible) {
    panel.classList.remove("show");
  } else {
    panel.classList.add("show");
    loadNotifications(); // 如需动态加载可取消注释
  }
}

function loadNotifications() {
  fetch("/notifications")
    .then((res) => res.json())
    .then((data) => {
      const unreadCount = data.filter((n) => !n.is_read).length;
      updateNotificationBadge(unreadCount);

      const panel = document.getElementById("notification-list");
      panel.innerHTML = "";

      if (!data || data.length === 0) {
        panel.innerHTML = `
          <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
            <div>暂无通知</div>
          </div>`;
        return;
      }

      data.forEach((notification) => {
        const item = document.createElement("div");
        item.classList.add("notification-item");
        if (!notification.is_read) item.classList.add("unread");
        item.setAttribute("data-id", notification.notification_id);
        item.setAttribute("data-type", notification.type || "system");
        item.setAttribute("data-priority", notification.priority || "0");

        item.innerHTML = `
          <div class="notification-header">
            <div class="notification-icon priority-${notification.priority || 0}">
              <i class="bi bi-bell"></i>
            </div>
            <div class="notification-content">
              <h4 class="notification-title">${notification.title || ""}</h4>
              <p class="notification-text">${notification.content || ""}</p>
              <div class="notification-meta">
                <div class="meta-left">
                  <div class="priority-dot ${notification.priority == 2 ? "high" : (notification.priority == 1 ? "medium" : "low")}"></div>
                  <span>${timeAgo(notification.created_at)}</span>
                </div>
                <span>${notification.type || "系统"}</span>
              </div>
              <div class="notification-actions">
                ${
                  !notification.is_read
                    ? `
                      <button class="action-btn primary" onclick="handleAction(${notification.notification_id}, event)">处理</button>
                      <button class="action-btn" onclick="markRead(${notification.notification_id}, event)">已读</button>
                    `
                    : `
                      <button class="action-btn" onclick="deleteNotification(${notification.notification_id}, event)">删除</button>
                    `
                }
              </div>
            </div>
          </div>
        `;

        item.addEventListener("click", () => toggleExpand(item));
        panel.appendChild(item);
      });
    })
    .catch((err) => console.error("获取通知失败:", err));
}


// 点击外部关闭
document.addEventListener("click", function (e) {
  const panel = document.getElementById("notificationPanel");
  const bellButton = document.querySelector(".btn-light");

  if (!panel.contains(e.target) && !bellButton.contains(e.target)) {
    panel.classList.remove("show");
  }
});

function toggleExpand(element) {
  document.querySelectorAll(".notification-item").forEach((item) => {
    if (item !== element) item.classList.remove("expanded");
  });
  element.classList.toggle("expanded");
}

function filterNotifications(type, btn) {
  document
    .querySelectorAll(".filter-btn")
    .forEach((b) => b.classList.remove("active"));
  btn.classList.add("active");

  document.querySelectorAll(".notification-item").forEach((item) => {
    let show = false;
    if (type === "all") show = true;
    else if (type === "unread") show = item.classList.contains("unread");
    else if (type === "priority-2")
      show = item.getAttribute("data-priority") === "2";
    else show = item.getAttribute("data-type") === type;
    item.style.display = show ? "block" : "none";
  });
}

function markRead(id, event) {
    event.stopPropagation();

    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(() => {
        const item = document.querySelector(`[data-id="${id}"]`);
        if (item) item.classList.remove("unread");

        const unreadCount = document.querySelectorAll(".notification-item.unread").length;
        updateNotificationBadge(unreadCount);
    })
    .catch(err => console.error('标记通知已读失败:', err));
}

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(() => {
        document.querySelectorAll(".notification-item.unread").forEach(item => item.classList.remove("unread"));
        updateNotificationBadge(0);
    })
    .catch(err => console.error('标记所有通知已读失败:', err));
}

function clearAll() {
  if (confirm("确定清空所有通知？")) {
    document.getElementById("notification-list").innerHTML = `
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
                <div>暂无通知</div>
            </div>
        `;
  }
}

function handleAction(id, event) {
  event.stopPropagation();
  alert(`处理通知 ${id}`);
  markRead(id, event);
}

function deleteNotification(id, event) {
  event.stopPropagation();
  const item = document.querySelector(`[data-id="${id}"]`);
  if (item) {
    item.style.opacity = "0";
    item.style.transform = "translateX(20px)";
    item.style.transition = "all 0.3s ease";
    setTimeout(() => item.remove(), 300);
  }
}

function updateNotificationBadge(unreadCount) {
  const badge = document.getElementById("notification_amount");
  if (!badge) return;

  if (unreadCount > 0) {
    badge.textContent = unreadCount; // 只显示数字
    badge.style.display = "inline-block";
  } else {
    badge.textContent = ""; // 清空
    badge.style.display = "none"; // 隐藏
  }
}

document.addEventListener("DOMContentLoaded", function () {
  loadNotifications(); // 立即加载一次
  setInterval(loadNotifications, 60000); // 每 60 秒刷新
});

// 辅助函数
function timeAgo(isoString) {
  const date = new Date(isoString);
  const now = new Date();
  const diff = (now - date) / 1000; // 秒

  if (diff < 60) return "刚刚";
  if (diff < 3600) return `${Math.floor(diff / 60)} 分钟前`;
  if (diff < 86400) return `${Math.floor(diff / 3600)} 小时前`;
  if (diff < 86400 * 7) return `${Math.floor(diff / 86400)} 天前`;

  return date.toLocaleDateString("zh-CN");
}

function getNotificationIcon(type) {
  switch (type) {
    case "security":
      return '<i class="bi bi-shield-exclamation"></i>';
    case "business":
      return '<i class="bi bi-file-person"></i>';
    case "maintenance":
      return '<i class="bi bi-wrench"></i>';
    default:
      return '<i class="bi bi-bell"></i>';
  }
}

function getNotificationTypeLabel(type) {
  switch (type) {
    case "security":
      return "安全";
    case "business":
      return "业务";
    case "maintenance":
      return "维修";
    default:
      return "系统";
  }
}

function getPriorityClass(priority) {
  return priority == 2 ? "high" : priority == 1 ? "medium" : "low";
}

// // 自动刷新和改进的通知系统
// let notificationVisible = false;
// let refreshInterval;

// // 切换通知面板
// function toggleNotifications() {
//   const popup = document.getElementById("notificationPopup");
//   notificationVisible = !notificationVisible;

//   if (notificationVisible) {
//     popup.classList.add("show");
//     fetchNotifications(); // 打开时立即刷新
//     // 开启更频繁的刷新
//     clearInterval(refreshInterval);
//     refreshInterval = setInterval(fetchNotifications, 10000); // 10秒刷新一次
//   } else {
//     popup.classList.remove("show");
//     // 关闭时恢复正常刷新频率
//     clearInterval(refreshInterval);
//     refreshInterval = setInterval(fetchNotifications, 30000); // 30秒刷新一次
//   }
// }

// // 页面初始化
// document.addEventListener("DOMContentLoaded", function () {
//   // 初始执行一次
//   fetchNotifications();

//   // 开始定时刷新（30秒）
//   refreshInterval = setInterval(fetchNotifications, 30000);

//   // 页面获得焦点时立即刷新
//   window.addEventListener("focus", function () {
//     fetchNotifications();
//   });

//   // 点击弹窗外区域关闭
//   document.addEventListener("click", function (event) {
//     const popup = document.getElementById("notificationPopup");
//     const button = event.target.closest('[onclick="toggleNotifications()"]');

//     if (!popup.contains(event.target) && !button && notificationVisible) {
//       toggleNotifications();
//     }
//   });

//   // ESC键关闭弹窗
//   document.addEventListener("keydown", function (event) {
//     if (event.key === "Escape" && notificationVisible) {
//       toggleNotifications();
//     }
//   });
// });

// // 增强的fetchNotifications函数
// function fetchNotifications() {
//   fetch("/notifications/unread")
//     .then((res) => {
//       if (!res.ok) throw new Error("Network response was not ok");
//       return res.json();
//     })
//     .then((data) => {
//       const badge = document.getElementById("notification_amount");
//       badge.innerText = data.count;
//       badge.style.display = data.count > 0 ? "inline-block" : "none";

//       const popup = document.getElementById("notificationPopup");
//       const list = popup.querySelector(".notification-list");
//       list.innerHTML = "";

//       if (data.notifications.length === 0) {
//         list.innerHTML = `
//                     <div class="text-center text-muted py-4">
//                         <i class="bi bi-bell-slash" style="font-size: 2rem; opacity: 0.5;"></i>
//                         <div class="mt-2">暂无未读通知</div>
//                     </div>
//                 `;
//         return;
//       }

//       data.notifications.forEach((item) => {
//         const notifEl = document.createElement("div");
//         notifEl.className =
//           "notification-item border-bottom" +
//           (item.is_read == 0 ? " unread" : "");

//         notifEl.innerHTML = `
//                     <div class="d-flex align-items-start gap-3">
//                         <i class="bi ${item.icon} text-${item.color} mt-1"></i>
//                         <div class="flex-grow-1" onclick="markAsRead(${
//                           item.id
//                         })">
//                             <div class="fw-medium mb-1" style="font-size: 0.9rem;">${
//                               item.title
//                             }</div>
//                             <div class="text-muted small mb-1">${
//                               item.content
//                             }</div>
//                             <div class="text-muted" style="font-size: 0.75rem;">
//                                 <i class="bi bi-clock me-1"></i>${formatTime(
//                                   item.created_at
//                                 )}
//                             </div>
//                         </div>
//                         <button class="btn btn-outline-danger btn-sm" onclick="deleteNotification(${
//                           item.id
//                         })" title="删除">
//                             <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
//                         </button>
//                     </div>
//                 `;
//         list.appendChild(notifEl);
//       });
//     })
//     .catch((error) => {
//       console.error("获取通知失败:", error);
//     });
// }

// // 增强的时间格式化
// function formatTime(timeString) {
//   const now = new Date();
//   const time = new Date(timeString);
//   const diff = Math.floor((now - time) / 1000);

//   if (diff < 60) return "刚刚";
//   if (diff < 300) return "几分钟前";
//   if (diff < 3600) return Math.floor(diff / 60) + "分钟前";
//   if (diff < 86400) return Math.floor(diff / 3600) + "小时前";
//   if (diff < 604800) return Math.floor(diff / 86400) + "天前";
//   return new Date(timeString).toLocaleDateString();
// }

// // 显示操作反馈
// function showFeedback(message, type = "success") {
//   // 创建简单的提示框
//   const toast = document.createElement("div");
//   toast.className = `alert alert-${type} position-fixed`;
//   toast.style.cssText =
//     "top: 20px; right: 20px; z-index: 9999; min-width: 250px;";
//   toast.innerHTML = `
//         <div class="d-flex align-items-center">
//             <i class="bi bi-check-circle me-2"></i>
//             ${message}
//         </div>
//     `;

//   document.body.appendChild(toast);

//   setTimeout(() => {
//     toast.remove();
//   }, 3000);
// }

// // 优化的标记已读函数
// function markAsRead(notificationId) {
//   fetch(`/notifications/${notificationId}/read`, {
//     method: "PUT",
//     headers: {
//       "X-CSRF-TOKEN": "{{ csrf_token() }}",
//       "Content-Type": "application/json",
//     },
//   })
//     .then((res) => res.json())
//     .then((data) => {
//       if (data.success) {
//         fetchNotifications();
//         showFeedback("通知已标记为已读");
//       }
//     })
//     .catch((error) => {
//       console.error("标记已读失败:", error);
//       showFeedback("操作失败，请重试", "danger");
//     });
// }

// // 优化的全部已读函数
// function markAllAsRead() {
//   fetch("/notifications/read-all", {
//     method: "PUT",
//     headers: {
//       "X-CSRF-TOKEN": "{{ csrf_token() }}",
//       "Content-Type": "application/json",
//     },
//   })
//     .then((res) => res.json())
//     .then((data) => {
//       if (data.success) {
//         fetchNotifications();
//         showFeedback("所有通知已标记为已读");
//       }
//     })
//     .catch((error) => {
//       console.error("全部已读失败:", error);
//       showFeedback("操作失败，请重试", "danger");
//     });
// }

// // 优化的删除函数
// function deleteNotification(notificationId) {
//   if (!confirm("确定要删除这条通知吗？")) return;

//   fetch(`/notifications/${notificationId}`, {
//     method: "DELETE",
//     headers: {
//       "X-CSRF-TOKEN": "{{ csrf_token() }}",
//       "Content-Type": "application/json",
//     },
//   })
//     .then((res) => res.json())
//     .then((data) => {
//       if (data.success) {
//         fetchNotifications();
//         showFeedback("通知已删除");
//       }
//     })
//     .catch((error) => {
//       console.error("删除通知失败:", error);
//       showFeedback("删除失败，请重试", "danger");
//     });
// }
