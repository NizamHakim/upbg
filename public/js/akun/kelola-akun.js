const dataAkun = document.getElementById("data-akun");

const editNIK = document.getElementById("edit-nik");
editNIK.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditNIKModal();
});

function showEditNIKModal() {
  const editNIKModal = document.getElementById("edit-nik-modal");
  const nik = dataAkun.querySelector(".nik").textContent;

  const inputNIK = editNIKModal.querySelector("[name='nik']");
  inputNIK.value = nik;

  const submitCallback = openModal(editNIKModal, closeCallback);

  const modalForm = editNIKModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      dataAkun.innerHTML = json.dataAkun;
      closeModal(editNIKModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

const editNama = document.getElementById("edit-nama");
editNama.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditNamaModal();
});

function showEditNamaModal() {
  const editNamaModal = document.getElementById("edit-nama-modal");
  const name = dataAkun.querySelector(".name").textContent;
  const nickname = dataAkun.querySelector(".nickname").textContent;

  const inputName = editNamaModal.querySelector("[name='name']");
  const inputNickname = editNamaModal.querySelector("[name='nickname']");

  inputName.value = name;
  inputNickname.value = nickname;

  const submitCallback = openModal(editNamaModal, closeCallback);

  const modalForm = editNamaModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      dataAkun.innerHTML = json.dataAkun;
      closeModal(editNamaModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

const editPhone = document.getElementById("edit-phone");
editPhone.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditPhoneModal();
});

function showEditPhoneModal() {
  const editPhoneModal = document.getElementById("edit-phone-modal");
  const phone = dataAkun.querySelector(".phone").textContent;

  const inputPhone = editPhoneModal.querySelector("[name='phone']");
  inputPhone.value = phone;

  const submitCallback = openModal(editPhoneModal, closeCallback);

  const modalForm = editPhoneModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      dataAkun.innerHTML = json.dataAkun;
      closeModal(editPhoneModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

const editEmail = document.getElementById("edit-email");
editEmail.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditEmailModal();
});

function showEditEmailModal() {
  const editEmailModal = document.getElementById("edit-email-modal");
  const email = dataAkun.querySelector(".email").textContent;

  const inputEmail = editEmailModal.querySelector("[name='email']");
  inputEmail.value = email;

  const submitCallback = openModal(editEmailModal, closeCallback);

  const modalForm = editEmailModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      dataAkun.innerHTML = json.dataAkun;
      closeModal(editEmailModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

const editPassword = document.getElementById("edit-password");
editPassword.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditPasswordModal();
});

function showEditPasswordModal() {
  const editPasswordModal = document.getElementById("edit-password-modal");

  const submitCallback = openModal(editPasswordModal, closeCallback);

  const modalForm = editPasswordModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      closeModal(editPasswordModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

let cropper;
const profilePicture = document.getElementById("profile-picture");
const photoPicker = document.getElementById("photo-picker");
const modalPreview = document.getElementById("modal-preview");

photoPicker.addEventListener("change", function () {
  const file = photoPicker.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      modalPreview.src = e.target.result;
      cropper = new Cropper(modalPreview, {
        aspectRatio: 1,
        viewMode: 2,
      });
    };
    reader.readAsDataURL(file);
    showCropperModal();
  }
});

function showCropperModal() {
  const cropperModal = document.getElementById("cropper-modal");
  const modalForm = cropperModal.querySelector("form");

  const editProfilePictureModal = document.getElementById("edit-profile-picture-modal");
  const profilePreview = editProfilePictureModal.querySelector(".profile-preview");
  const profileInput = editProfilePictureModal.querySelector("[name='profile-picture']");

  const submitCallback = openModal(cropperModal, closeCallback);

  function handleSubmit(e) {
    e.preventDefault();
    const canvas = cropper.getCroppedCanvas();
    const dataUrl = canvas.toDataURL();
    profilePreview.src = dataUrl;

    canvas.toBlob((blob) => {
      const croppedFile = new File([blob], "cropped-image.png", { type: "image/png" });
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(croppedFile);
      profileInput.files = dataTransfer.files;
    }, "image/png");

    closeModal(cropperModal, submitCallback);
    showEditProfilePictureModal();
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    cropper.destroy();
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showEditProfilePictureModal() {
  const editProfilePictureModal = document.getElementById("edit-profile-picture-modal");

  const submitCallback = openModal(editProfilePictureModal, closeCallback);

  const modalForm = editProfilePictureModal.querySelector("form");

  async function handleSubmit(e) {
    e.preventDefault();

    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const submitButton = e.submitter;
    const formData = new FormData(modalForm);

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "POST", formData, true);
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
}
