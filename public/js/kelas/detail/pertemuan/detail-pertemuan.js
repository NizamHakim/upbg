const deletePertemuan = document.querySelector(".delete-pertemuan");
if (deletePertemuan) {
  deletePertemuan.addEventListener("click", (e) => {
    e.stopPropagation();
    const deletePertemuanModal = document.getElementById("delete-pertemuan-modal");
    const modalForm = deletePertemuanModal.querySelector("form");

    async function handleSubmit(e) {
      e.preventDefault();
      const route = modalForm.action;
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

    openModal(deletePertemuanModal, closeCallback);
  });
}

const mulaiPertemuan = document.querySelector(".mulai-pertemuan");

if (mulaiPertemuan) {
  mulaiPertemuan.addEventListener("click", async (e) => {
    e.stopPropagation();
    const mulaiPertemuanModal = document.getElementById("mulai-pertemuan-modal");
    const modalForm = mulaiPertemuanModal.querySelector("form");

    async function handleSubmit(e) {
      e.preventDefault();
      clearErrors(modalForm);
      const route = modalForm.action;
      const submitButton = e.submitter;
      const formData = new FormData(modalForm);
      const data = Object.fromEntries(formData.entries());

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "PATCH", data);
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

    openModal(mulaiPertemuanModal, closeCallback);
  });
}

const reschedulePertemuan = document.querySelector(".reschedule-pertemuan");
if (reschedulePertemuan) {
  reschedulePertemuan.addEventListener("click", (e) => {
    e.stopPropagation();
    const reschedulePertemuanModal = document.getElementById("reschedule-pertemuan-modal");
    const modalForm = reschedulePertemuanModal.querySelector("form");

    // populate form because modal clear form upon close
    const tanggal = modalForm.querySelector("[name='tanggal']");
    const waktuMulai = modalForm.querySelector("[name='waktu-mulai']");
    const waktuSelesai = modalForm.querySelector("[name='waktu-selesai']");
    const ruangan = modalForm.querySelector("[name='ruangan']");
    tanggal._flatpickr.setDate(tanggal.dataset.currentValue, true);
    waktuMulai._flatpickr.setDate(waktuMulai.dataset.currentValue, true, "H:i");
    waktuSelesai._flatpickr.setDate(waktuSelesai.dataset.currentValue, true, "H:i");
    ruangan.tomselect.setValue(ruangan.dataset.currentValue);

    async function handleSubmit(e) {
      e.preventDefault();
      clearErrors(modalForm);

      const submitButton = e.submitter;
      const route = modalForm.action;
      const formData = new FormData(modalForm);
      const data = Object.fromEntries(formData.entries());

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "PATCH", data);
      stopFetchingAnimation(submitButton);

      if (response.ok) {
        const json = await response.json();
        console.log(json);
        window.location.href = json.redirect;
      } else {
        handleError(response, modalForm);
      }
    }
    modalForm.addEventListener("submit", handleSubmit);

    function closeCallback() {
      modalForm.removeEventListener("submit", handleSubmit);
    }

    openModal(reschedulePertemuanModal, closeCallback);
  });
}

const topikCatatan = document.getElementById("topik-catatan");
if (topikCatatan) {
  const editTopikCatatan = topikCatatan.querySelector(".edit-topik-catatan");
  editTopikCatatan.addEventListener("click", (e) => {
    e.stopPropagation();
    const editTopikCatatanModal = document.getElementById("edit-topik-catatan-modal");
    const modalForm = editTopikCatatanModal.querySelector("form");

    // populate form
    const topikForm = modalForm.querySelector("[name='topik']");
    const catatanForm = modalForm.querySelector("[name='catatan']");
    const topik = topikCatatan.querySelector(".topik");
    const catatan = topikCatatan.querySelector(".catatan");
    topikForm.value = topik.textContent == "-" ? "" : topik.textContent;
    catatanForm.value = catatan.textContent == "-" ? "" : catatan.textContent;

    const submitCallback = openModal(editTopikCatatanModal, closeCallback);

    async function handleSubmit(e) {
      e.preventDefault();
      const submitButton = e.submitter;
      const route = modalForm.action;
      const formData = new FormData(modalForm);
      const data = Object.fromEntries(formData.entries());

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "PATCH", data);
      stopFetchingAnimation(submitButton);

      if (response.ok) {
        const json = await response.json();
        const topikCatatanContainer = topikCatatan.querySelector(".topik-catatan-container");
        topikCatatanContainer.innerHTML = json.topikCatatan;
        insertNotification(json.notification);
        closeModal(editTopikCatatanModal, submitCallback);
      } else {
        handleError(response, modalForm);
      }
    }

    modalForm.addEventListener("submit", handleSubmit);

    function closeCallback() {
      modalForm.removeEventListener("submit", handleSubmit);
    }
  });
}

const daftarPresensi = document.getElementById("daftar-presensi");
if (daftarPresensi) {
  const presensiControl = daftarPresensi.querySelector(".presensi-control");
  presensiControl.addEventListener("submit", async (e) => {
    if (e.target.matches(".tandai-semua-hadir")) {
      e.preventDefault();
      const route = e.target.action;
      const submitButton = e.submitter;

      playFetchingAnimation(submitButton);
      const response = await fetchRequest(route, "PUT");
      stopFetchingAnimation(submitButton);

      if (response.ok) {
        const json = await response.json();
        presensiContainer.innerHTML = json.presensiTable;
        updateCounter(json.hadirCount);
        insertNotification(json.notification);
      } else {
        handleError(response, null);
      }
    }
  });
  presensiControl.addEventListener("click", (e) => {
    if (e.target.closest(".tambah-presensi")) {
      e.stopPropagation();
      const tambahPresensiModal = document.getElementById("tambah-presensi-modal");
      const modalForm = tambahPresensiModal.querySelector("form");
      const route = modalForm.action;

      async function handleSubmit(e) {
        e.preventDefault();
        const submitButton = e.submitter;
        const formData = new FormData(modalForm);
        const data = Object.fromEntries(formData.entries());

        playFetchingAnimation(submitButton);
        const response = await fetchRequest(route, "POST", data);
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

      openModal(tambahPresensiModal, closeCallback);
    }
  });

  const presensiContainer = daftarPresensi.querySelector(".presensi-container");
  presensiContainer.addEventListener("submit", async (e) => {
    if (e.target.matches(".form-toggle-kehadiran")) {
      e.preventDefault();
      const form = e.target;
      const route = form.action;
      const data = { hadir: e.submitter.value };
      const response = await fetchRequest(route, "PATCH", data);
      if (response.ok) {
        const json = await response.json();
        form.innerHTML = json.btnPresensi;
        updateCounter(json.hadirCount);
      } else {
        handleError(response, null);
      }
    }
  });

  presensiContainer.addEventListener("click", (e) => {
    if (e.target.closest(".delete-presensi")) {
      e.stopPropagation();
      const presensiItem = e.target.closest(".presensi-item");
      const deletePresensiModal = document.getElementById("delete-presensi-modal");
      const modalForm = deletePresensiModal.querySelector("form");
      const route = presensiItem.dataset.deleteRoute;

      // populate form alert
      const namaNikPeserta = modalForm.querySelector(".nama-nik-peserta");
      const nama = presensiItem.querySelector(".nama-peserta").textContent;
      const nik = presensiItem.querySelector(".nik-peserta").textContent;
      namaNikPeserta.textContent = `${nama} - ${nik}`;

      async function handleSubmit(e) {
        e.preventDefault();
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

      openModal(deletePresensiModal, closeCallback);
    }
  });

  function updateCounter(hadirCount) {
    const hadirCounter = daftarPresensi.querySelector(".hadir-counter");
    hadirCounter.innerHTML = hadirCount;
  }
}
