const tambahUserForm = document.getElementById("tambah-user-form");
tambahUserForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  clearErrors(tambahUserForm);
  const route = tambahUserForm.action;
  const submitButton = e.submitter;
  const formData = new FormData(tambahUserForm);

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "POST", formData, true);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.replace(json.redirect);
  } else {
    handleError(response, tambahUserForm);
  }
});

const useNik = document.querySelector("[name='use-nik']");
const nikInput = tambahUserForm.querySelector("[name='nik']");
const passwordInput = tambahUserForm.querySelector("[name='password']");
const confirmPasswordInput = tambahUserForm.querySelector("[name='confirm-password']");

useNik.addEventListener("change", function () {
  if (useNik.checked) {
    passwordInput.type = "text";
    passwordInput.readOnly = true;
    passwordInput.value = nikInput.value;
    confirmPasswordInput.type = "text";
    confirmPasswordInput.readOnly = true;
    confirmPasswordInput.value = nikInput.value;
    nikInput.addEventListener("input", attached);
  } else {
    passwordInput.type = "password";
    passwordInput.readOnly = false;
    passwordInput.value = "";
    confirmPasswordInput.type = "password";
    confirmPasswordInput.readOnly = false;
    confirmPasswordInput.value = "";
    nikInput.removeEventListener("input", attached);
  }
});

function attached() {
  passwordInput.value = nikInput.value;
  confirmPasswordInput.value = nikInput.value;
}

let cropper;
const modalPreview = document.getElementById("modal-preview");
const profilePreview = document.getElementById("profile-preview");
const photoPicker = document.getElementById("photo-picker");

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
      photoPicker.files = dataTransfer.files;
    }, "image/png");

    closeModal(cropperModal, submitCallback);
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    cropper.destroy();
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
