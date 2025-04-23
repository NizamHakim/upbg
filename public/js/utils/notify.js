document.addEventListener("DOMContentLoaded", () => {
  const notifications = document.querySelectorAll(".notification");
  notifications.forEach((notification) => {
    showNotification(notification);
  });
});

function insertNotification(notificationHTML) {
  const notificationRail = document.querySelector(".notification-rail");
  notificationRail.insertAdjacentHTML("beforeend", notificationHTML);
  const notification = notificationRail.lastElementChild;
  setTimeout(() => {
    showNotification(notification);
  }, 10);
}

function showNotification(notification) {
  const progress = notification.querySelector(".notification-progress");
  const closeNotification = notification.querySelector(".close-notification");

  notification.classList.add("show");

  notification.addEventListener("mouseover", () => {
    progress.style.animationPlayState = "paused";
  });
  notification.addEventListener("mouseout", () => {
    progress.style.animationPlayState = "running";
  });

  progress.addEventListener("animationend", () => {
    hideNotification(notification);
  });

  closeNotification.addEventListener("click", () => {
    hideNotification(notification);
  });
}

function hideNotification(notification) {
  notification.classList.remove("show");
  function transitionEnd(e) {
    notification.remove();
  }
  notification.addEventListener("transitionend", transitionEnd);
}
