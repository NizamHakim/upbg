const ruanganContainer = document.querySelector(".ruangan-container");
ruanganContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-ruangan")) {
    e.stopPropagation();
    const route = e.target.closest(".edit-ruangan").dataset.route;
    const ruanganItem = e.target.closest(".ruangan-item");
    showEditRuanganModal(route, ruanganItem);
  } else if (e.target.closest(".hapus-ruangan")) {
    e.stopPropagation();
    const route = e.target.closest(".hapus-ruangan").dataset.route;
    const ruanganItem = e.target.closest(".ruangan-item");
    showHapusRuanganModal(route, ruanganItem);
  }
});

const tambahRuangan = document.getElementById("tambah-ruangan");
tambahRuangan.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahRuanganModal = document.getElementById("tambah-ruangan-modal");
  const modalForm = tambahRuanganModal.querySelector("form");

  const submitCallback = openModal(tambahRuanganModal, closeCallback);

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
      ruanganContainer.innerHTML = json.table;
      closeModal(tambahRuanganModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
});

function showEditRuanganModal(route, ruanganItem) {
  const editRuanganModal = document.getElementById("edit-ruangan-modal");
  const kodeInput = editRuanganModal.querySelector('[name="kode"]');
  const kapasitasInput = editRuanganModal.querySelector('[name="kapasitas"]');
  kodeInput.value = ruanganItem.querySelector(".kode-ruangan").textContent;
  kapasitasInput.value = ruanganItem.querySelector(".kapasitas-ruangan").textContent;

  const submitCallback = openModal(editRuanganModal, closeCallback);

  const modalForm = editRuanganModal.querySelector("form");
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
      ruanganItem.innerHTML = json.ruanganItem;
      closeModal(editRuanganModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showHapusRuanganModal(route, ruanganItem) {
  const hapusRuanganModal = document.getElementById("hapus-ruangan-modal");
  const kodeRuangan = hapusRuanganModal.querySelector(".kode-ruangan");
  const kode = ruanganItem.querySelector(".kode-ruangan").textContent;
  kodeRuangan.textContent = kode;

  const submitCallback = openModal(hapusRuanganModal, closeCallback);

  const modalForm = hapusRuanganModal.querySelector("form");
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
      ruanganContainer.innerHTML = json.table;
      closeModal(hapusRuanganModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
