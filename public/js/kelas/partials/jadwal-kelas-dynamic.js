const jadwalKelasDynamic = document.getElementById("jadwal-kelas-dynamic");
const jadwalKelasContainer = jadwalKelasDynamic.querySelector(".jadwal-kelas-container");

function createNewJadwalItem(hari = "", waktuMulai = "", waktuSelesai = "") {
  const templateJadwal = document.getElementById("template-jadwal");
  const jadwalItem = templateJadwal.content.cloneNode(true);

  const hariArray = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  jadwalItem.querySelector(".hari").textContent = hari == "" ? "Hari" : hariArray[hari];
  jadwalItem.querySelector(".waktu-mulai").textContent = waktuMulai == "" ? "Waktu Mulai" : waktuMulai;
  jadwalItem.querySelector(".waktu-selesai").textContent = waktuSelesai == "" ? "Waktu Selesai" : waktuSelesai;

  jadwalItem.querySelector("[name='hari']").value = hari;
  jadwalItem.querySelector("[name='waktu-mulai']").value = waktuMulai;
  jadwalItem.querySelector("[name='waktu-selesai']").value = waktuSelesai;

  jadwalKelasContainer.appendChild(jadwalItem);

  // change event
  const event = new Event("change", { bubbles: true });
  jadwalKelasContainer.dispatchEvent(event);
}

const tambahJadwal = jadwalKelasDynamic.querySelector(".tambah-jadwal");
tambahJadwal.addEventListener("click", function (e) {
  e.stopPropagation();
  showAddJadwalModal();
});

function showAddJadwalModal() {
  const addJadwalModal = document.getElementById("add-jadwal-modal");
  const modalForm = addJadwalModal.querySelector("form");

  const submitCallback = openModal(addJadwalModal, removeSubmitEvent);

  function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);

    const hari = modalForm.querySelector('[name="hari"]');
    const waktuMulai = modalForm.querySelector('[name="waktu-mulai"]');
    const waktuSelesai = modalForm.querySelector('[name="waktu-selesai"]');

    createNewJadwalItem(hari.value, waktuMulai.value, waktuSelesai.value);
    closeModal(addJadwalModal, submitCallback);
  }
  modalForm.addEventListener("submit", handleSubmit);

  function removeSubmitEvent() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}

jadwalKelasContainer.addEventListener("click", function (e) {
  if (e.target.closest(".edit-jadwal")) {
    e.stopPropagation();
    const jadwalItem = e.target.closest(".jadwal-kelas-item");
    showEditJadwalModal(jadwalItem);
  } else if (e.target.closest(".delete-jadwal")) {
    e.stopPropagation();
    const jadwalItem = e.target.closest(".jadwal-kelas-item");
    jadwalItem.remove();
  }
});

function showEditJadwalModal(jadwalItem) {
  const editJadwalModal = document.getElementById("edit-jadwal-modal");
  const hariItem = jadwalItem.querySelector("[name='hari']");
  const waktuMulaiItem = jadwalItem.querySelector("[name='waktu-mulai']");
  const waktuSelesaiItem = jadwalItem.querySelector("[name='waktu-selesai']");

  const modalForm = editJadwalModal.querySelector("form");
  const inputHari = modalForm.querySelector("[name='hari']");
  const inputWaktuMulai = modalForm.querySelector("[name='waktu-mulai']");
  const inputWaktuSelesai = modalForm.querySelector("[name='waktu-selesai']");

  inputHari.tomselect.setValue(hariItem.value);
  inputWaktuMulai._flatpickr.setDate(waktuMulaiItem.value, true, "H:i");
  inputWaktuSelesai._flatpickr.setDate(waktuSelesaiItem.value, true, "H:i");

  const submitCallback = openModal(editJadwalModal, removeSubmitEvent);

  function handleSubmit(e) {
    e.preventDefault();
    clearErrors(modalForm);

    hariItem.value = inputHari.value;
    waktuMulaiItem.value = inputWaktuMulai.value;
    waktuSelesaiItem.value = inputWaktuSelesai.value;

    jadwalItem.querySelector(".hari").textContent = inputHari.selectedOptions[0].textContent;
    jadwalItem.querySelector(".waktu-mulai").textContent = inputWaktuMulai.value;
    jadwalItem.querySelector(".waktu-selesai").textContent = inputWaktuSelesai.value;

    const event = new Event("change", { bubbles: true });
    jadwalItem.dispatchEvent(event);

    closeModal(editJadwalModal, submitCallback);
  }
  modalForm.addEventListener("submit", handleSubmit);

  function removeSubmitEvent() {
    modalForm.removeEventListener("submit", handleSubmit);
  }
}
