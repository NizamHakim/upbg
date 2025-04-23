document.addEventListener("click", (e) => {
  if (e.target.closest(".jadwal-item.kelas")) {
    e.stopPropagation();
    const kelas = e.target.closest(".jadwal-item.kelas");
    const route = kelas.getAttribute("data-route");
    showJadwalModal("kelas", route);
  } else if (e.target.closest(".jadwal-item.tes")) {
    e.stopPropagation();
    const tes = e.target.closest(".jadwal-item.tes");
    const route = tes.getAttribute("data-route");
    showJadwalModal("tes", route);
  }
});

async function showJadwalModal(tipe, route) {
  if (tipe === "kelas") {
    const kelasModal = document.getElementById("detail-jadwal-kelas");

    const mountKelas = document.getElementById("mount-kelas");
    const div = document.createElement("div");
    div.setAttribute("class", "flex justify-center items-center");
    div.innerHTML = `${createLoadingAnimation()}`;
    mountKelas.replaceChildren(div);
    openModal(kelasModal);

    const response = await fetchRequest(route, "GET");

    if (response.ok) {
      const html = await response.text();
      mountKelas.innerHTML = html;
    } else {
      console.log(response);
    }
  } else if (tipe === "tes") {
    const tesModal = document.getElementById("detail-jadwal-tes");

    const mountTes = document.getElementById("mount-tes");
    const div = document.createElement("div");
    div.setAttribute("class", "flex justify-center items-center");
    div.innerHTML = `${createLoadingAnimation()}`;
    mountTes.replaceChildren(div);
    openModal(tesModal);

    const response = await fetchRequest(route, "GET");

    if (response.ok) {
      const html = await response.text();
      mountTes.innerHTML = html;
    } else {
      console.log(response);
    }
  }
}

const datepickerDesktop = document.querySelector("[name='datepicker-desktop']");
datepickerDesktop.addEventListener("change", async function (e) {
  const date = e.target.value;
  const url = new URL(e.target.dataset.route);
  url.searchParams.set("date", date);
  const loading = `
    <div class="is-fetching p-4">
      ${createLoadingAnimation()}
    </div>
  `;

  const jadwalDesktop = document.getElementById("jadwal-desktop");
  jadwalDesktop.innerHTML = loading;
  const jadwalMobileContainer = document.getElementById("jadwal-mobile-container");
  jadwalMobileContainer.innerHTML = loading;

  const tanggalDisplayDesktop = document.getElementById("tanggal-display-desktop");

  const response = await fetchRequest(url, "GET");
  if (response.ok) {
    const json = await response.json();
    jadwalDesktop.innerHTML = json.table;
    tanggalDisplayDesktop.innerHTML = json.desktop;
    jadwalMobileContainer.innerHTML = json.mobile;
  } else {
    handleError(response, tableContent);
  }
});

const calendarEl = document.getElementById("calendar");
const calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: "dayGridMonth", // Default view
  headerToolbar: {
    left: "prev",
    center: "title",
    right: "next",
  },
  locale: "id",
  dateClick: function (info) {
    datepickerDesktop._flatpickr.setDate(info.dateStr, true, "Y-m-d");
  },
});
calendar.render();
