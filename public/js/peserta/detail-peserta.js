const detailPeserta = document.getElementById("detail-peserta");

const editPeserta = document.querySelector(".edit-peserta");
editPeserta.addEventListener("click", function (e) {
  e.stopPropagation();
  showEditPesertaModal();
});

function showEditPesertaModal() {
  const editPesertaModal = document.getElementById("edit-peserta-modal");
  const nikInput = editPesertaModal.querySelector('[name="nik"]');
  const namaInput = editPesertaModal.querySelector('[name="nama"]');
  const occupationInput = editPesertaModal.querySelector('[name="occupation"]');

  nikInput.value = detailPeserta.querySelector(".nik").textContent;
  namaInput.value = detailPeserta.querySelector(".nama").textContent;
  occupationInput.value = detailPeserta.querySelector(".occupation").textContent;

  const submitCallback = openModal(editPesertaModal, closeCallback);

  const modalForm = editPesertaModal.querySelector("form");

  async function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);
    const route = modalForm.getAttribute("action");
    const formData = new FormData(modalForm);
    const data = Object.fromEntries(formData.entries());
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PUT", data);
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      const dataPeserta = detailPeserta.querySelector(".data-peserta");
      insertNotification(json.notification);
      dataPeserta.innerHTML = json.dataPeserta;
      closeModal(editPesertaModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

const deletePeserta = document.querySelector(".delete-peserta");
deletePeserta.addEventListener("click", function (e) {
  e.stopPropagation();
  showDeletePesertaModal();
});

function showDeletePesertaModal() {
  const deletePesertaModal = document.getElementById("delete-peserta-modal");
  const nama = detailPeserta.querySelector(".nama").textContent;
  const nik = detailPeserta.querySelector(".nik").textContent;
  const namaNikPeserta = deletePesertaModal.querySelector(".nama-nik-peserta");
  namaNikPeserta.textContent = `${nama} (${nik})`;

  const modalForm = deletePesertaModal.querySelector("form");

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

  openModal(deletePesertaModal, closeCallback);
}
