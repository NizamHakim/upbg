const kategoriContainer = document.querySelector(".kategori-container");
kategoriContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-kategori")) {
    e.stopPropagation();
    const route = e.target.closest(".edit-kategori").dataset.route;
    const kategoriItem = e.target.closest(".kategori-item");
    showEditKategoriModal(route, kategoriItem);
  } else if (e.target.closest(".hapus-kategori")) {
    e.stopPropagation();
    const route = e.target.closest(".hapus-kategori").dataset.route;
    const kategoriItem = e.target.closest(".kategori-item");
    showHapusKategoriModal(route, kategoriItem);
  }
});

const tambahKategori = document.getElementById("tambah-kategori");
tambahKategori.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahKategoriModal = document.getElementById("tambah-kategori-modal");
  const modalForm = tambahKategoriModal.querySelector("form");

  const submitCallback = openModal(tambahKategoriModal, closeCallback);

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
      kategoriContainer.innerHTML = json.table;
      closeModal(tambahKategoriModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
});

function showEditKategoriModal(route, kategoriItem) {
  const editKategoriModal = document.getElementById("edit-kategori-modal");
  const namaInput = editKategoriModal.querySelector('[name="nama"]');
  namaInput.value = kategoriItem.querySelector(".nama-kategori").textContent;

  const submitCallback = openModal(editKategoriModal, closeCallback);

  const modalForm = editKategoriModal.querySelector("form");
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
      kategoriItem.innerHTML = json.kategoriItem;
      closeModal(editKategoriModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showHapusKategoriModal(route, kategoriItem) {
  const hapusKategoriModal = document.getElementById("hapus-kategori-modal");
  const namaKategori = hapusKategoriModal.querySelector(".nama-kategori");
  const nama = kategoriItem.querySelector(".nama-kategori").textContent;
  namaKategori.textContent = `${nama}`;

  const submitCallback = openModal(hapusKategoriModal, closeCallback);

  const modalForm = hapusKategoriModal.querySelector("form");
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
      kategoriContainer.innerHTML = json.table;
      closeModal(hapusKategoriModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
