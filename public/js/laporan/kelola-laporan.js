const kelolaLaporanContainer = document.getElementById("kelola-laporan-container");
kelolaLaporanContainer.addEventListener("click", async function (e) {
  if (e.target.closest(".kelola-laporan-item")) {
    e.stopPropagation();
    const laporanItem = e.target.closest(".kelola-laporan-item");
    const route = laporanItem.dataset.route;
    showEditConfig(route, laporanItem);
  }
});

function showEditConfig(route, laporanItem) {
  const updateLaporanModal = document.getElementById("update-laporan-modal");
  const itemKey = updateLaporanModal.querySelector(".item-key");
  const itemValue = updateLaporanModal.querySelector('[name="item-value"]');

  itemKey.textContent = laporanItem.querySelector(".item-key").textContent;
  itemValue.placeholder = laporanItem.querySelector(".item-key").textContent;
  itemValue.value = laporanItem.querySelector(".item-value").textContent;

  const submitCallback = openModal(updateLaporanModal, closeCallback);

  const modalForm = updateLaporanModal.querySelector("form");
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
      laporanItem.innerHTML = json.laporanItem;
      closeModal(updateLaporanModal, submitCallback);
    } else {
      handleError(response, modalForm);
    }
  }
  modalForm.addEventListener("submit", handleSubmit);

  function closeCallback() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
