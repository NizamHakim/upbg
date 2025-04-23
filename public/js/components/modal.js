function openModal(modal, callback = null) {
  const modalContent = modal.querySelector(".modal-content");
  const body = document.querySelector("body");

  modal.classList.replace("hidden", "flex");
  setTimeout(() => {
    modal.classList.add("open");
    body.classList.remove("overflow-y-scroll");
    body.classList.add("overflow-y-hidden", "pr-[10px]");
  }, 1);

  const cancelButtons = modalContent.querySelectorAll(".cancel-button");
  function handleCancel(e) {
    e.stopPropagation();
    closeModal(modal, closeCallback);
  }
  cancelButtons.forEach((cancelButton) => {
    cancelButton.addEventListener("click", handleCancel);
  });

  function handleClickOutside(e) {
    e.stopPropagation();
    const dimensions = modalContent.getBoundingClientRect();
    if (e.clientX < dimensions.left || e.clientX > dimensions.right || e.clientY < dimensions.top || e.clientY > dimensions.bottom) {
      closeModal(modal, closeCallback);
    }
  }
  document.addEventListener("click", handleClickOutside);

  function closeCallback() {
    cancelButtons.forEach((cancelButton) => {
      cancelButton.removeEventListener("click", handleCancel);
    });
    document.removeEventListener("click", handleClickOutside);
    if (callback) callback();
  }

  return closeCallback;
}

function closeModal(modal, callback) {
  const modalContent = modal.querySelector(".modal-content");
  const body = document.querySelector("body");

  modal.classList.remove("open");

  function handleTransitionEnd(e) {
    if (e.propertyName === "opacity") {
      modal.classList.replace("flex", "hidden");
      body.classList.remove("overflow-y-hidden", "pr-[10px]");
      body.classList.add("overflow-y-scroll");
      modal.removeEventListener("transitionend", handleTransitionEnd);
    }
  }
  modal.addEventListener("transitionend", handleTransitionEnd);

  const form = modalContent.querySelector("form");
  if (form) resetForm(form);

  callback();
}
