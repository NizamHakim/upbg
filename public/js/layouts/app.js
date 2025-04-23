const openSidenav = document.querySelector(".open-sidenav");
if (openSidenav) {
  openSidenav.addEventListener("click", (e) => {
    e.stopPropagation();
    openSidenavMobile();
  });
}

function openSidenavMobile() {
  const sidenav = document.querySelector(".sidenav");

  sidenav.classList.remove("hidden");
  setTimeout(() => {
    sidenav.classList.add("open");
  }, 1);

  function handleClickOutside(e) {
    e.stopPropagation();
    const sidenavContent = document.querySelector(".sidenav-content");
    if (!sidenavContent.contains(e.target)) {
      closeSidenavMobile(closeCallback);
    }
  }
  sidenav.addEventListener("click", handleClickOutside);

  function closeCallback() {
    sidenav.removeEventListener("click", handleClickOutside);
  }
}

function closeSidenavMobile(callback) {
  const sidenav = document.querySelector(".sidenav");

  sidenav.classList.remove("open");

  function handleTransitionEnd(e) {
    if (e.propertyName === "opacity") {
      sidenav.classList.add("hidden");
      sidenav.removeEventListener("transitionend", handleTransitionEnd);
      callback();
    }
  }
  sidenav.addEventListener("transitionend", handleTransitionEnd);
}

function mutationCallback(mutationsList, observer) {
  for (const mutation of mutationsList) {
    if (mutation.type === "childList") {
      mutation.addedNodes.forEach((node) => {
        if (node.tagName === "SELECT" && node.classList.contains("tom-select")) {
          initializeTomSelect(node);
        }
        if (node.querySelectorAll) {
          const tomSelectElements = node.querySelectorAll(".tom-select");
          tomSelectElements.forEach((element) => {
            initializeTomSelect(element);
          });
        }
      });
    }
  }
}

const observer = new MutationObserver(mutationCallback);

observer.observe(document.body, {
  childList: true,
  subtree: true,
});
