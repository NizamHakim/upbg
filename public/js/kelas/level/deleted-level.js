const levelContainer = document.querySelector(".level-container");
levelContainer.addEventListener("click", function (e) {
  if (e.target.closest(".restore-level")) {
    e.stopPropagation();
    const route = e.target.closest(".restore-level").dataset.route;
    const levelItem = e.target.closest(".level-item");
    showRestoreLevelModal(route, levelItem);
  }
});

function showRestoreLevelModal(route, levelItem) {
  const restoreLevelModal = document.getElementById("restore-level-modal");
  const namaKodeLevel = restoreLevelModal.querySelector(".nama-kode-level");
  const nama = levelItem.querySelector(".nama-level").textContent;
  const kode = levelItem.querySelector(".kode-level").textContent;
  namaKodeLevel.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(restoreLevelModal, closeCallback);

  const modalForm = restoreLevelModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      levelContainer.innerHTML = json.table;
      closeModal(restoreLevelModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
