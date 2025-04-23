const ruanganContainer = document.querySelector(".ruangan-container");
ruanganContainer.addEventListener("click", function (e) {
  if (e.target.closest(".restore-ruangan")) {
    e.stopPropagation();
    const route = e.target.closest(".restore-ruangan").dataset.route;
    const ruanganItem = e.target.closest(".ruangan-item");
    showRestoreRuanganModal(route, ruanganItem);
  }
});

function showRestoreRuanganModal(route, ruanganItem) {
  const restoreRuanganModal = document.getElementById("restore-ruangan-modal");
  const kodeRuangan = restoreRuanganModal.querySelector(".kode-ruangan");
  const kode = ruanganItem.querySelector(".kode-ruangan").textContent;
  kodeRuangan.textContent = kode;

  const submitCallback = openModal(restoreRuanganModal, closeCallback);

  const modalForm = restoreRuanganModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      ruanganContainer.innerHTML = json.table;
      closeModal(restoreRuanganModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
