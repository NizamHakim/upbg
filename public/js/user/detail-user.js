const updateRoleForm = document.getElementById("update-role-form");
updateRoleForm.addEventListener("click", (e) => {
  if (e.target.closest(".cancel-button")) {
    const roleContainer = document.querySelector(".role-container");
    const roles = JSON.parse(roleContainer.dataset.roles);
    const checkboxes = updateRoleForm.querySelectorAll('[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
      if (roles.includes(Number(checkbox.value))) {
        checkbox.checked = true;
      } else {
        checkbox.checked = false;
      }
    });
    const control = updateRoleForm.querySelector(".control");
    control.classList.replace("flex", "hidden");
  }
});

updateRoleForm.addEventListener("change", () => {
  const control = updateRoleForm.querySelector(".control");
  control.classList.replace("hidden", "flex");
});

updateRoleForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const route = updateRoleForm.action;
  const submitButton = e.submitter;

  const formData = new FormData(updateRoleForm);
  const data = Object.fromEntries(formData.entries());
  delete data["role"];
  data["role"] = formData.getAll("role");

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "PUT", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.replace(json.redirect);
  } else {
    handleError(response, updateRoleForm);
  }
});

const hapusUser = document.querySelector(".hapus-user");
if (hapusUser) {
  hapusUser.addEventListener("click", (e) => {
    e.stopPropagation();
    showHapusUserModal();
  });

  function showHapusUserModal() {
    const hapusUserModal = document.getElementById("hapus-user-modal");
    const modalForm = hapusUserModal.querySelector("form");

    async function handleSubmit(e) {
      e.preventDefault();
      const submitButton = e.submitter;
      const route = modalForm.action;

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "DELETE");
      stopFetchingAnimation(submitButton);

      if (response.ok) {
        const json = await response.json();
        window.location.replace(json.redirect);
      } else {
        handleError(response, modalForm);
      }
    }
    modalForm.addEventListener("submit", handleSubmit);

    function closeCallback() {
      modalForm.removeEventListener("submit", handleSubmit);
    }

    openModal(hapusUserModal, closeCallback);
  }
}

const resetPassword = document.querySelector(".reset-password");

if (resetPassword) {
  resetPassword.addEventListener("click", (e) => {
    e.stopPropagation();
    showResetPasswordModal();
  });

  function showResetPasswordModal() {
    const resetPasswordModal = document.getElementById("reset-password-modal");
    const modalForm = resetPasswordModal.querySelector("form");

    const submitCallback = openModal(resetPasswordModal, closeCallback);

    async function handleSubmit(e) {
      e.preventDefault();
      const submitButton = e.submitter;
      const route = modalForm.action;

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "PATCH");
      stopFetchingAnimation(submitButton);

      if (response.ok) {
        const json = await response.json();
        insertNotification(json.notification);
        closeModal(resetPasswordModal, submitCallback);
      } else {
        handleError(response, modalForm);
      }
    }
    modalForm.addEventListener("submit", handleSubmit);

    function closeCallback() {
      modalForm.removeEventListener("submit", handleSubmit);
    }
  }
}
