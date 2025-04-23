const deleteKelas = document.querySelector(".delete-kelas");

if (deleteKelas) {
  deleteKelas.addEventListener("click", (e) => {
    e.stopPropagation();
    const deleteKelasModal = document.getElementById("delete-kelas-modal");
    const modalForm = deleteKelasModal.querySelector("form");

    async function handleSubmit(e) {
      e.preventDefault();
      clearErrors(modalForm);
      const route = modalForm.action;
      const submitButton = e.submitter;

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

    openModal(deleteKelasModal, closeCallback);
  });
}

const tambahPertemuan = document.querySelector(".tambah-pertemuan");
tambahPertemuan.addEventListener("click", function (e) {
  e.stopPropagation();
  const tambahPertemuanModal = document.getElementById("tambah-pertemuan-modal");
  const modalForm = tambahPertemuanModal.querySelector("form");

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
      window.location.replace(json.redirect);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }

  openModal(tambahPertemuanModal, closeCallback);
});
