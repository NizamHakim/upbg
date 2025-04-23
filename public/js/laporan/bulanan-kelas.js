const includeTidakTerlaksana = document.querySelector(["[name='include-tidak-terlaksana']"]);
includeTidakTerlaksana.addEventListener("change", function (e) {
  const alertTidakTerlaksana = document.querySelector(".alert-tidak-terlaksana");
  if (e.target.checked) {
    alertTidakTerlaksana.classList.remove("hidden");
  } else {
    alertTidakTerlaksana.classList.add("hidden");
  }
});

const kelasResult = document.getElementById("kelas-result");
const filterKelasForm = document.getElementById("filter-kelas-form");
filterKelasForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());
  const url = new URL(e.target.action);
  for (const key in data) {
    if (data[key] !== "") {
      url.searchParams.set(key, data[key]);
    }
  }

  const loading = `
    <div class="is-fetching p-4">
      ${createLoadingAnimation()}
    </div>
  `;
  kelasResult.innerHTML = loading;

  const response = await fetchRequest(url.toString(), "GET");
  if (response.ok) {
    const json = await response.json();
    kelasResult.innerHTML = json.table;
  } else {
    handleError(response, kelasResult);
  }
});

kelasResult.addEventListener("click", async function (e) {
  if (e.target.closest(".kelas-item")) {
    const kelasItem = e.target.closest(".kelas-item");
    const checkbox = kelasItem.querySelector("input[type='checkbox']");
    if (checkbox && !e.target.matches("input[type='checkbox']")) {
      checkbox.checked = !checkbox.checked;
      const event = new Event("change", { bubbles: true });
      checkbox.dispatchEvent(event);
    }
  }
});

kelasResult.addEventListener("change", async function (e) {
  const checkboxes = document.querySelectorAll("[name='kelas[]']");
  const semuaKelas = document.querySelector("[name='semua-kelas']");
  const checkedCheckboxes = document.querySelectorAll("[name='kelas[]']:checked");

  if (e.target.matches("[name='kelas[]']")) {
    if (checkedCheckboxes.length === 0) {
      semuaKelas.checked = false;
    } else if (checkboxes.length === checkedCheckboxes.length) {
      semuaKelas.checked = true;
    } else {
      semuaKelas.checked = false;
    }
  } else if (e.target.matches("[name='semua-kelas']")) {
    checkboxes.forEach((checkbox) => {
      checkbox.checked = e.target.checked;
    });
  }
});
