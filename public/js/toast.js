// 支持的位置参数：'top-left'左上角 / 'top-center' 上中 'top-right'  /  右上角（默认） 'center-left'  左中  /  'center' 正中央
// 'center-right' 右中 / 'bottom-left' 左下角 / 'bottom-center' 下中 / 'bottom-right' 右下角
function showToast(message, type = "info", position = "top-right") {
  // 创建toast元素
  const toast = document.createElement("div");
  toast.className = `toast-item alert alert-${type}`;
  toast.setAttribute("data-position", position);

  // 计算同位置现有toast数量，确定偏移量
  const existingToasts = document.querySelectorAll(
    `[data-position="${position}"]`
  );
  const offset = existingToasts.length * 80; // 每个toast间隔80px

  // 根据位置设置样式和动画
  const positions = {
    "top-left": {
      final: {
        top: `${20 + offset}px`,
        left: "20px",
      },
      initial: {
        top: `${20 + offset}px`,
        left: "20px",
        transform: "translateX(-100%)",
      },
      animate: {
        transform: "translateX(0)",
      },
    },
    "top-center": {
      final: {
        top: `${20 + offset}px`,
        left: "50%",
        transform: "translateX(-50%)",
      },
      initial: {
        top: `${20 + offset}px`,
        left: "50%",
        transform: "translateX(-50%) translateY(-100%)",
      },
      animate: {
        transform: "translateX(-50%) translateY(0)",
      },
    },
    "top-right": {
      final: {
        top: `${20 + offset}px`,
        right: "20px",
      },
      initial: {
        top: `${20 + offset}px`,
        right: "20px",
        transform: "translateX(100%)",
      },
      animate: {
        transform: "translateX(0)",
      },
    },
    "center-left": {
      final: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        left: "20px",
        transform: "translateY(-50%)",
      },
      initial: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        left: "20px",
        transform: "translateY(-50%) translateX(-100%)",
      },
      animate: {
        transform: "translateY(-50%) translateX(0)",
      },
    },
    center: {
      final: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        left: "50%",
        transform: "translate(-50%, -50%)",
      },
      initial: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        left: "50%",
        transform: "translate(-50%, -50%) scale(0.3)",
      },
      animate: {
        transform: "translate(-50%, -50%) scale(1)",
      },
    },
    "center-right": {
      final: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        right: "20px",
        transform: "translateY(-50%)",
      },
      initial: {
        top: `calc(50% + ${offset - existingToasts.length * 40}px)`,
        right: "20px",
        transform: "translateY(-50%) translateX(100%)",
      },
      animate: {
        transform: "translateY(-50%) translateX(0)",
      },
    },
    "bottom-left": {
      final: {
        bottom: `${20 + offset}px`,
        left: "20px",
      },
      initial: {
        bottom: `${20 + offset}px`,
        left: "20px",
        transform: "translateX(-100%)",
      },
      animate: {
        transform: "translateX(0)",
      },
    },
    "bottom-center": {
      final: {
        bottom: `${20 + offset}px`,
        left: "50%",
        transform: "translateX(-50%)",
      },
      initial: {
        bottom: `${20 + offset}px`,
        left: "50%",
        transform: "translateX(-50%) translateY(100%)",
      },
      animate: {
        transform: "translateX(-50%) translateY(0)",
      },
    },
    "bottom-right": {
      final: {
        bottom: `${20 + offset}px`,
        right: "20px",
      },
      initial: {
        bottom: `${20 + offset}px`,
        right: "20px",
        transform: "translateX(100%)",
      },
      animate: {
        transform: "translateX(0)",
      },
    },
  };

  const pos = positions[position] || positions["top-right"];

  // 设置初始样式（隐藏状态）
  const initialStyle = Object.entries(pos.initial)
    .map(([key, value]) => `${key}: ${value}`)
    .join("; ");
  toast.style.cssText = `
        position: fixed;
        z-index: 9999;
        min-width: 250px;
        max-width: 400px;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        ${initialStyle};
    `;

  // 图标映射
  const icons = {
    success: "bi-check-circle",
    danger: "bi-x-circle",
    warning: "bi-exclamation-triangle",
    info: "bi-info-circle",
  };

  const icon = icons[type] || icons["info"];

  // 设置内容
  toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi ${icon} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" onclick="hideToast(this.parentElement.parentElement)"></button>
        </div>
    `;

  // 添加到页面
  document.body.appendChild(toast);

  // 滑入动画
  setTimeout(() => {
    toast.style.opacity = "1";
    Object.entries(pos.animate).forEach(([key, value]) => {
      toast.style[key] = value;
    });
  }, 10);

  // 自动消失
  setTimeout(() => {
    hideToast(toast);
  }, 3000);
}

// 隐藏toast函数（支持手动和自动关闭）
function hideToast(toast) {
  if (!toast || !toast.parentNode) return;

  const position = toast.getAttribute("data-position");
  const hideTransforms = {
    "top-left": "translateX(-100%)",
    "top-center": "translateX(-50%) translateY(-100%)",
    "top-right": "translateX(100%)",
    "center-left": "translateY(-50%) translateX(-100%)",
    center: "translate(-50%, -50%) scale(0.3)",
    "center-right": "translateY(-50%) translateX(100%)",
    "bottom-left": "translateX(-100%)",
    "bottom-center": "translateX(-50%) translateY(100%)",
    "bottom-right": "translateX(100%)",
  };

  toast.style.opacity = "0";
  toast.style.transform =
    hideTransforms[position] || hideTransforms["top-right"];

  setTimeout(() => {
    if (toast.parentNode) {
      toast.remove();
      // 重新调整同位置剩余toast的位置
      reorderToasts(position);
    }
  }, 300);
}

// 重新排列同位置的toast
function reorderToasts(position) {
  const toasts = document.querySelectorAll(`[data-position="${position}"]`);

  toasts.forEach((toast, index) => {
    const offset = index * 80;

    // 根据位置类型调整
    if (position.includes("top-")) {
      toast.style.top = `${20 + offset}px`;
    } else if (position.includes("bottom-")) {
      toast.style.bottom = `${20 + offset}px`;
    } else if (position.includes("center")) {
      const centerOffset = offset - (toasts.length - 1) * 40;
      toast.style.top = `calc(50% + ${centerOffset}px)`;
    }
  });
}

// 批量显示toast的辅助函数
function showToasts(
  messages,
  type = "info",
  position = "top-right",
  delay = 200
) {
  messages.forEach((message, index) => {
    setTimeout(() => {
      showToast(message, type, position);
    }, index * delay);
  });
}

// 清除所有toast
function clearAllToasts() {
  const toasts = document.querySelectorAll(".toast-item");
  toasts.forEach((toast) => hideToast(toast));
}

// 清除指定位置的toast
function clearToasts(position) {
  const toasts = document.querySelectorAll(`[data-position="${position}"]`);
  toasts.forEach((toast) => hideToast(toast));
}
