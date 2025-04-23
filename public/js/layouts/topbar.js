const switchRoleButton = document.querySelector(".switch-role-button");
const switchRoleDropdown = document.querySelector(".switch-role-dropdown");

if (switchRoleButton && switchRoleDropdown) {
  switchRoleButton.addEventListener("click", () => {
    toggleSwitchRoleDropdown();
  });

  function toggleSwitchRoleDropdown(expand = null) {
    const open = expand !== null ? expand : switchRoleDropdown.classList.contains("open");
    if (open) {
      switchRoleDropdown.classList.remove("open");
      switchRoleButton.classList.remove("open");
      function transitionend(e) {
        switchRoleDropdown.classList.replace("flex", "hidden");
        switchRoleDropdown.removeEventListener("transitionend", transitionend);
      }
      switchRoleDropdown.addEventListener("transitionend", transitionend);
    } else {
      switchRoleDropdown.classList.replace("hidden", "flex");
      setTimeout(() => {
        switchRoleDropdown.classList.add("open");
      }, 1);
    }
  }
}

document.addEventListener("click", (e) => {
  if (e.target.closest(".profile-menu-button")) {
    openProfileMenu();
  }
});

function openProfileMenu() {
  const profileMenuButton = document.querySelector(".profile-menu-button");
  function handleProfileButtonClick(e) {
    e.stopPropagation();
    closeProfileMenu(closeCallback);
  }
  profileMenuButton.addEventListener("click", handleProfileButtonClick);

  const profileMenu = document.querySelector(".profile-menu");
  profileMenu.classList.remove("hidden");
  setTimeout(() => {
    profileMenu.classList.add("open");
  }, 1);

  function handleClickOutside(e) {
    e.stopPropagation();
    if (!profileMenu.contains(e.target) && !profileMenuButton.contains(e.target)) {
      closeProfileMenu(closeCallback);
    }
  }
  document.addEventListener("click", handleClickOutside);

  function closeCallback() {
    profileMenuButton.removeEventListener("click", handleProfileButtonClick);
    document.removeEventListener("click", handleClickOutside);
  }
}

function closeProfileMenu(callback) {
  const profileMenu = document.querySelector(".profile-menu");
  profileMenu.classList.remove("open");

  function handleTransitionEnd(e) {
    if (e.propertyName === "transform") {
      profileMenu.classList.add("hidden");
      profileMenu.removeEventListener("transitionend", handleTransitionEnd);
    }
  }
  profileMenu.addEventListener("transitionend", handleTransitionEnd);

  callback();
}
