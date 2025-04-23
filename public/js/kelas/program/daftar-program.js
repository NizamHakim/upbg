const programContainer = document.querySelector(".program-container");
programContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-program")) {
    e.stopPropagation();
    const route = e.target.closest(".edit-program").dataset.route;
    const programItem = e.target.closest(".program-item");
    showEditProgramModal(route, programItem);
  } else if (e.target.closest(".hapus-program")) {
    e.stopPropagation();
    const route = e.target.closest(".hapus-program").dataset.route;
    const programItem = e.target.closest(".program-item");
    showHapusProgramModal(route, programItem);
  }
});

const tambahProgram = document.getElementById("tambah-program");
tambahProgram.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahProgramModal = document.getElementById("tambah-program-modal");
  const modalForm = tambahProgramModal.querySelector("form");

  const submitCallback = openModal(tambahProgramModal, closeCallback);

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
      programContainer.innerHTML = json.table;
      closeModal(tambahProgramModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
});

function showEditProgramModal(route, programItem) {
  const editProgramModal = document.getElementById("edit-program-modal");
  const namaInput = editProgramModal.querySelector('[name="nama"]');
  const kodeInput = editProgramModal.querySelector('[name="kode"]');
  namaInput.value = programItem.querySelector(".nama-program").textContent;
  kodeInput.value = programItem.querySelector(".kode-program").textContent;

  const submitCallback = openModal(editProgramModal, closeCallback);

  const modalForm = editProgramModal.querySelector("form");
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
      programItem.innerHTML = json.programItem;
      closeModal(editProgramModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showHapusProgramModal(route, programItem) {
  const hapusProgramModal = document.getElementById("hapus-program-modal");
  const namaKodeProgram = hapusProgramModal.querySelector(".nama-kode-program");
  const nama = programItem.querySelector(".nama-program").textContent;
  const kode = programItem.querySelector(".kode-program").textContent;
  namaKodeProgram.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(hapusProgramModal, closeCallback);

  const modalForm = hapusProgramModal.querySelector("form");
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
      programContainer.innerHTML = json.table;
      closeModal(hapusProgramModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
