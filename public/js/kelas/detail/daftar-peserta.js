const pesertaContainer = document.querySelector(".peserta-container");
pesertaContainer.addEventListener("click", (e) => {
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
  const modalTanggalBergabung = editPesertaModal.querySelector(".tanggal-bergabung-peserta");
  const modalStatusPeserta = editPesertaModal.querySelector(".status-peserta");

  const nik = pesertaItem.querySelector(".nik-peserta").textContent;
  const nama = pesertaItem.querySelector(".nama-peserta").textContent;
  const tanggalBergabung = pesertaItem.querySelector(".tanggal-bergabung-peserta").textContent;
  const status = pesertaItem.querySelector(".status-peserta").textContent;

  modalNik.textContent = nik;
  modalNama.textContent = nama;
  modalTanggalBergabung.textContent = tanggalBergabung;
  modalStatusPeserta.querySelector("[name=status-peserta]").checked = status === "Aktif";
  modalStatusPeserta.querySelector(".checkbox-label").textContent = status;

  const submitCallback = openModal(editPesertaModal, closeCallback);

  const modalForm = editPesertaModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const route = pesertaItem.dataset.editRoute;
    const submitButton = e.submitter;
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      const statusPesertaContainer = pesertaItem.querySelector(".status-peserta-container");
      statusPesertaContainer.innerHTML = json.statusPeserta;
      closeModal(editPesertaModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function handleAktifChange(e) {
    modalStatusPeserta.querySelector(".checkbox-label").textContent = e.target.checked ? "Aktif" : "Tidak Aktif";
  }
  modalStatusPeserta.addEventListener("change", handleAktifChange);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
    modalStatusPeserta.removeEventListener("change", handleAktifChange);
  }
}

function showDeletePesertaModal(pesertaItem) {
  const deletePesertaModal = document.getElementById("delete-peserta-modal");
  const namaNikPeserta = deletePesertaModal.querySelector(".nama-nik-peserta");

  const nik = pesertaItem.querySelector(".nik-peserta").textContent;
  const nama = pesertaItem.querySelector(".nama-peserta").textContent;

  namaNikPeserta.textContent = `${nama} - ${nik}`;

  const modalForm = deletePesertaModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const route = pesertaItem.dataset.deleteRoute;
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "DELETE");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      window.location.href = json.redirect;
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }

  openModal(deletePesertaModal, closeCallback);
}
