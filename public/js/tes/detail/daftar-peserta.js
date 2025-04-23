const ubahRuanganBatchForm = document.querySelector(".ubah-ruangan-batch");
if (ubahRuanganBatchForm) {
  ubahRuanganBatchForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    clearErrors(ubahRuanganBatchForm);
    const route = ubahRuanganBatchForm.getAttribute("action");
    const submitButton = e.submitter;

    const formData = new FormData(ubahRuanganBatchForm);
    const data = Object.fromEntries(formData);

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      updatePesertaTable(json.pesertaTable);
      updatePembagianRuangan(json.pembagianRuangan);
      insertNotification(json.notification);
      resetForm(ubahRuanganBatchForm);
    } else {
      handleError(response, ubahRuanganBatchForm);
    }
  });
}

const filterRuangan = document.querySelector(".filter-ruangan");
filterRuangan.addEventListener("change", filterTable);

const filterSearch = document.querySelector(".filter-search");
filterSearch.addEventListener("submit", (e) => {
  e.preventDefault();
  filterTable();
});

const pesertaTableContainer = document.querySelector(".peserta-container");
pesertaTableContainer.addEventListener("change", async function (e) {
  if (e.target.matches("[name='ruangan']")) {
    const route = e.target.dataset.route;
    const data = { ruangan: e.target.value };

    const response = await fetchRequest(route, "PATCH", data);
    if (response.ok) {
      const json = await response.json();
      updatePembagianRuangan(json.pembagianRuangan);
      insertNotification(json.notification);
      const pesertaItem = e.target.closest(".peserta-item");
      pesertaItem.querySelector(".ruangan-peserta").textContent = e.target.selectedOptions[0].textContent;
    } else {
      handleError(response, null);
    }
  }
});

pesertaTableContainer.addEventListener("click", async function (e) {
  if (e.target.closest(".edit-peserta")) {
    e.stopPropagation();
    const pesertaItem = e.target.closest(".peserta-item");
    showEditPesertaModal(pesertaItem);
  } else if (e.target.closest(".delete-peserta")) {
    e.stopPropagation();
    const pesertaItem = e.target.closest(".peserta-item");
    showDeletePesertaModal(pesertaItem);
  }
});

function showEditPesertaModal(pesertaItem) {
  const editPesertaModal = document.getElementById("edit-peserta-modal");

  const modalNik = editPesertaModal.querySelector(".nik-peserta");
  const modalNama = editPesertaModal.querySelector(".nama-peserta");
  const modalRuangan = editPesertaModal.querySelector("[name=ruangan]");

  const nik = pesertaItem.querySelector(".nik-peserta").textContent;
  const nama = pesertaItem.querySelector(".nama-peserta").textContent;
  const ruangan = pesertaItem.querySelector("[name=ruangan]");

  modalNik.textContent = nik;
  modalNama.textContent = nama;
  modalRuangan.tomselect.setValue(ruangan.value);

  const submitCallback = openModal(editPesertaModal, closeCallback);

  const modalForm = editPesertaModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    ruangan.tomselect.setValue(modalRuangan.value);
    closeModal(editPesertaModal, submitCallback);
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

function showDeletePesertaModal(pesertaItem) {
  const deletePesertaModal = document.getElementById("delete-peserta-modal");
  const namaNikPeserta = deletePesertaModal.querySelector(".nama-nik-peserta");

  const nik = pesertaItem.querySelector(".nik-peserta").textContent;
  const nama = pesertaItem.querySelector(".nama-peserta").textContent;

  namaNikPeserta.textContent = `${nama} - ${nik}`;

  const modalForm = deletePesertaModal.querySelector("form");

  const submitCallback = openModal(deletePesertaModal, closeCallback);

  async function handleSubmit(e) {
    e.preventDefault();
    const route = pesertaItem.dataset.deleteRoute;
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "DELETE");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      closeModal(deletePesertaModal, submitCallback);
      updatePesertaTable(json.pesertaTable);
      updatePembagianRuangan(json.pembagianRuangan);
      updateTotalPeserta(json.totalPeserta);
      insertNotification(json.notification);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

async function filterTable() {
  const route = window.location.href;
  const url = new URL(route);

  const ruangan = filterRuangan.value;
  const search = filterSearch.querySelector("input").value;
  if (ruangan) url.searchParams.set("ruangan", ruangan);
  if (search) url.searchParams.set("search", search);

  pesertaTableContainer.classList.add("is-fetching", "p-4");
  pesertaTableContainer.innerHTML = createLoadingAnimation();
  const response = await fetchRequest(url.toString(), "GET");
  if (response.ok) {
    const html = await response.text();
    pesertaTableContainer.classList.remove("is-fetching", "p-4");
    updatePesertaTable(html);
  } else {
    handleError(response, null);
  }
}

function updatePesertaTable(pesertaTable) {
  pesertaTableContainer.innerHTML = pesertaTable;
}

function updatePembagianRuangan(pembagianRuangan) {
  const pembagianRuanganContainer = document.querySelector(".pembagian-ruangan");
  pembagianRuanganContainer.innerHTML = pembagianRuangan;
}

function updateTotalPeserta(totalPeserta) {
  const totalPesertaContainer = document.querySelector(".total-peserta");
  totalPesertaContainer.innerHTML = totalPeserta;
}
