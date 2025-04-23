const daftarPresensi = document.getElementById("daftar-presensi");
if (daftarPresensi) {
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
        const hadirCounter = daftarPresensi.querySelector(".hadir-counter");
        form.innerHTML = json.btnPresensi;
        hadirCounter.innerHTML = json.hadirCount;
      } else {
        handleError(response, null);
      }
    }
  });

  const filterRuangan = document.querySelector(".filter-ruangan");
  filterRuangan.addEventListener("change", updateTable);

  const filterSearch = document.querySelector(".filter-search");
  filterSearch.addEventListener("submit", (e) => {
    e.preventDefault();
    updateTable();
  });

  async function updateTable() {
    const route = window.location.href;
    const url = new URL(route);

    const ruangan = filterRuangan.value;
    const search = filterSearch.querySelector("input").value;
    if (ruangan) url.searchParams.set("ruangan", ruangan);
    if (search) url.searchParams.set("search", search);

    presensiContainer.classList.add("is-fetching", "p-4");
    presensiContainer.innerHTML = createLoadingAnimation();
    const response = await fetchRequest(url.toString(), "GET");
    if (response.ok) {
      const html = await response.text();
      presensiContainer.classList.remove("is-fetching", "p-4");
      presensiContainer.innerHTML = html;
    } else {
      handleError(response, null);
    }
  }
}

const deleteTes = document.querySelector(".delete-tes");

if (deleteTes) {
  deleteTes.addEventListener("click", (e) => {
    e.stopPropagation();
    const deleteTesModal = document.getElementById("delete-tes-modal");
    const modalForm = deleteTesModal.querySelector("form");

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

    openModal(deleteTesModal, closeCallback);
  });
}

const noticePresensi = document.getElementById("notice-presensi");
if (noticePresensi) {
  noticePresensi.addEventListener("submit", async (e) => {
    e.preventDefault();
    const route = e.target.action;
    const submitButton = e.submitter;

    playFetchingAnimation(submitButton);
    const response = await fetchRequest(route, "PATCH");
    stopFetchingAnimation(submitButton);

    if (response.ok) {
      const json = await response.json();
      window.location.replace(json.redirect);
    } else {
      handleError(response, null);
    }
  });
}
