const programContainer = document.querySelector(".program-container");
programContainer.addEventListener("click", function (e) {
  if (e.target.closest(".restore-program")) {
    e.stopPropagation();
    const route = e.target.closest(".restore-program").dataset.route;
    const programItem = e.target.closest(".program-item");
    showRestoreProgramModal(route, programItem);
  }
});

function showRestoreProgramModal(route, programItem) {
  const restoreProgramModal = document.getElementById("restore-program-modal");
  const namaKodeProgram = restoreProgramModal.querySelector(".nama-kode-program");
  const nama = programItem.querySelector(".nama-program").textContent;
  const kode = programItem.querySelector(".kode-program").textContent;
  namaKodeProgram.textContent = `${nama} (${kode})`;

  const submitCallback = openModal(restoreProgramModal, closeCallback);

  const modalForm = restoreProgramModal.querySelector("form");
  async function handleSubmit(e) {
    e.preventDefault();
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      insertNotification(json.notification);
      programContainer.innerHTML = json.table;
      closeModal(restoreProgramModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
