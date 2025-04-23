const levelContainer = document.querySelector(".level-container");
levelContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-level")) {
    e.stopPropagation();
    const route = e.target.closest(".edit-level").dataset.route;
    const levelItem = e.target.closest(".level-item");
    showEditLevelModal(route, levelItem);
  } else if (e.target.closest(".hapus-level")) {
    e.stopPropagation();
    const route = e.target.closest(".hapus-level").dataset.route;
    const levelItem = e.target.closest(".level-item");
    showHapusLevelModal(route, levelItem);
  }
});

const tambahLevel = document.getElementById("tambah-level");
tambahLevel.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahLevelModal = document.getElementById("tambah-level-modal");
  const modalForm = tambahLevelModal.querySelector("form");

  const submitCallback = openModal(tambahLevelModal, closeCallback);

  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.action;
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "POST", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      levelContainer.innerHTML = json.table;
      closeModal(tambahLevelModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
});

function showEditLevelModal(route, levelItem) {
  const editLevelModal = document.getElementById("edit-level-modal");
  const namaInput = editLevelModal.querySelector('[name="nama"]');
  const kodeInput = editLevelModal.querySelector('[name="kode"]');
  namaInput.value = levelItem.querySelector(".nama-level").textContent;
  kodeInput.value = levelItem.querySelector(".kode-level").textContent;

  const submitCallback = openModal(editLevelModal, closeCallback);

  const modalForm = editLevelModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PUT", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      levelItem.innerHTML = json.levelItem;
      closeModal(editLevelModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showHapusLevelModal(route, levelItem) {
  const hapusLevelModal = document.getElementById("hapus-level-modal");
  const namaKodeLevel = hapusLevelModal.querySelector(".nama-kode-level");
  const nama = levelItem.querySelector(".nama-level").textContent;
  const kode = levelItem.querySelector(".kode-level").textContent;
  namaKodeLevel.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(hapusLevelModal, closeCallback);

  const modalForm = hapusLevelModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "DELETE", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      levelContainer.innerHTML = json.table;
      closeModal(hapusLevelModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
