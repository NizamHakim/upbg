const pesertaResult = document.getElementById("peserta-result");
const filterPesertaForm = document.getElementById("filter-peserta-form");
filterPesertaForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());
  const url = new URL(e.target.action);
  for (const key in data) {
    if (data[key] !== "") {
      url.searchParams.set(key, data[key]);
    }
  }
  console.log(url.toString());

  const loading = `
    <div class="is-fetching p-4">
      ${createLoadingAnimation()}
    </div>
  `;
  pesertaResult.innerHTML = loading;

  const response = await fetchRequest(url.toString(), "GET");
  if (response.ok) {
    const json = await response.json();
    pesertaResult.innerHTML = json.table;
  } else {
    handleError(response, pesertaResult);
  }
});

const departemenSelect = document.querySelector("[name=departemen]");
new TomSelect(departemenSelect, {
  valueField: "occupation",
  labelField: "occupation",
  searchField: "occupation",
  load: async function (query, callback) {
    const route = new URL(departemenSelect.dataset.route);
    route.searchParams.set("departemen", query);
    const response = await fetchRequest(route.toString(), "GET");
    if (response.ok) {
      const json = await response.json();
      callback(json);
    }
  },
});

const mahasiswaSelect = document.querySelector("[name=mahasiswa]");
new TomSelect(mahasiswaSelect, {
  valueField: "id",
  labelField: "nama",
  searchField: "nama",
  load: async function (query, callback) {
    const route = new URL(mahasiswaSelect.dataset.route);
    route.searchParams.set("departemen", query);
    const response = await fetchRequest(route.toString(), "GET");
    if (response.ok) {
      const json = await response.json();
      callback(json);
    }
  },
});
