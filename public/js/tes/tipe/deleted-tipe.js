const tipeContainer = document.querySelector(".tipe-container");
tipeContainer.addEventListener("click", function (e) {
  if (e.target.closest(".restore-tipe")) {
    e.stopPropagation();
    const route = e.target.closest(".restore-tipe").dataset.route;
    const tipeItem = e.target.closest(".tipe-item");
    showRestoreTipeModal(route, tipeItem);
  }
});

function showRestoreTipeModal(route, tipeItem) {
  const restoreTipeModal = document.getElementById("restore-tipe-modal");
  const namaKodeTipe = restoreTipeModal.querySelector(".nama-kode-tipe");
  const nama = tipeItem.querySelector(".nama-tipe").textContent;
  const kode = tipeItem.querySelector(".kode-tipe").textContent;
  namaKodeTipe.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(restoreTipeModal, closeCallback);

  const modalForm = restoreTipeModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      tipeContainer.innerHTML = json.table;
      closeModal(restoreTipeModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
