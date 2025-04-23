function openMenu(menu) {
  menu.classList.replace("hidden", "flex");

  const menuTrigger = menu.parentElement.querySelector(".menu-trigger");
  function handleClickToClose(e) {
    e.stopPropagation();
    closeMenu(menu, closeCallback);
  }
  menuTrigger.addEventListener("click", handleClickToClose);

  function handleClickOutside(e) {
    const dimensions = menu.getBoundingClientRect();
    if (e.clientX < dimensions.left || e.clientX > dimensions.right || e.clientY < dimensions.top || e.clientY > dimensions.bottom) {
      closeMenu(menu, closeCallback);
      document.removeEventListener("click", handleClickOutside);
    }
  }
  document.addEventListener("click", handleClickOutside);

  function handleClickItem(e) {
    if (e.target.closest(".menu-item")) {
      closeMenu(menu, closeCallback);
    }
  }
  menu.addEventListener("click", handleClickItem);

  function closeCallback() {
    menuTrigger.removeEventListener("click", handleClickToClose);
    document.removeEventListener("click", handleClickOutside);
    menu.removeEventListener("click", handleClickItem);
  }

  return closeCallback;
}

function closeMenu(menu, callback) {
  menu.classList.replace("flex", "hidden");
  callback();
}

document.addEventListener("click", (e) => {
  if (e.target.closest(".menu-trigger")) {
    const menu = e.target.closest(".menu-trigger").parentElement.querySelector(".menu");
    openMenu(menu);
  }
});
