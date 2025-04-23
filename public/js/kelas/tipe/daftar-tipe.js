const tipeContainer = document.querySelector(".tipe-container");
tipeContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-tipe")) {
    e.stopPropagation();
    const route = e.target.closest(".edit-tipe").dataset.route;
    const tipeItem = e.target.closest(".tipe-item");
    showEditTipeModal(route, tipeItem);
  } else if (e.target.closest(".hapus-tipe")) {
    e.stopPropagation();
    const route = e.target.closest(".hapus-tipe").dataset.route;
    const tipeItem = e.target.closest(".tipe-item");
    showHapusTipeModal(route, tipeItem);
  }
});

const tambahTipe = document.getElementById("tambah-tipe");
tambahTipe.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahTipeModal = document.getElementById("tambah-tipe-modal");
  const modalForm = tambahTipeModal.querySelector("form");

  const submitCallback = openModal(tambahTipeModal, closeCallback);

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
      tipeContainer.innerHTML = json.table;
      closeModal(tambahTipeModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
});

function showEditTipeModal(route, tipeItem) {
  const editTipeModal = document.getElementById("edit-tipe-modal");
  const namaInput = editTipeModal.querySelector('[name="nama"]');
  const kodeInput = editTipeModal.querySelector('[name="kode"]');
  const kategoriInput = editTipeModal.querySelector('[name="kategori"]');
  namaInput.value = tipeItem.querySelector(".nama-tipe").textContent;
  kodeInput.value = tipeItem.querySelector(".kode-tipe").textContent;
  kategoriInput.tomselect.setValue(tipeItem.querySelector(".kategori-tipe").dataset.id);

  const submitCallback = openModal(editTipeModal, closeCallback);

  const modalForm = editTipeModal.querySelector("form");
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
      tipeItem.innerHTML = json.tipeItem;
      closeModal(editTipeModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showHapusTipeModal(route, tipeItem) {
  const hapusTipeModal = document.getElementById("hapus-tipe-modal");
  const namaKodeTipe = hapusTipeModal.querySelector(".nama-kode-tipe");
  const nama = tipeItem.querySelector(".nama-tipe").textContent;
  const kode = tipeItem.querySelector(".kode-tipe").textContent;
  namaKodeTipe.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(hapusTipeModal, closeCallback);

  const modalForm = hapusTipeModal.querySelector("form");
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
      tipeContainer.innerHTML = json.table;
      closeModal(hapusTipeModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
