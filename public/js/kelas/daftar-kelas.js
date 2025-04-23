const filterKelas = document.getElementById("filter-kelas");
const openFilter = filterKelas.querySelector(".open-filter");

openFilter.addEventListener("click", handleFilterOpen);

function handleFilterOpen() {
  const filterContainer = filterKelas.querySelector(".filter-container");
  const closeFilter = filterKelas.querySelector(".close-filter");

  filterContainer.classList.add("open");
  function addOverflow() {
    filterContainer.classList.add("sm:overflow-visible");
    filterContainer.removeEventListener("transitionend", addOverflow);
  }
  filterContainer.addEventListener("transitionend", addOverflow);

  openFilter.removeEventListener("click", handleFilterOpen);
  openFilter.addEventListener("click", handleFilterClose);
  closeFilter.addEventListener("click", handleFilterClose);
}

function handleFilterClose() {
  const filterContainer = filterKelas.querySelector(".filter-container");
  const closeFilter = filterKelas.querySelector(".close-filter");

  filterContainer.classList.remove("sm:overflow-visible");
  filterContainer.classList.remove("open");

  openFilter.removeEventListener("click", handleFilterClose);
  closeFilter.removeEventListener("click", handleFilterClose);
  openFilter.addEventListener("click", handleFilterOpen);
}

const resetFilter = filterKelas.querySelector(".reset-filter");
resetFilter.addEventListener("click", function () {
  const filterFields = filterKelas.querySelectorAll(".filter-field");
  filterFields.forEach((field) => {
    if (field.classList.contains("tom-select")) {
      field?.tomselect?.clear();
    } else if (field.classList.contains("fp-datepicker")) {
      field?._flatpickr?.clear();
    } else {
      field.value = "";
    }
  });
});

const filterForm = document.querySelector(".filter-form");
filterForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());
  const url = new URL(e.target.action);
  for (const key in data) {
    if (data[key] !== "") {
      url.searchParams.set(key, data[key]);
    }
  }
  window.location.href = url.toString();
});
