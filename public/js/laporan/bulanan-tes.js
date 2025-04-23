const tesResult = document.getElementById("tes-result");
const filterTesForm = document.getElementById("filter-tes-form");
filterTesForm.addEventListener("submit", async function (e) {
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
  tesResult.innerHTML = loading;

  const response = await fetchRequest(url.toString(), "GET");
  if (response.ok) {
    const json = await response.json();
    tesResult.innerHTML = json.table;
  } else {
    handleError(response, tesResult);
  }
});

tesResult.addEventListener("click", async function (e) {
  if (e.target.closest(".tes-item")) {
    const tesItem = e.target.closest(".tes-item");
    const checkbox = tesItem.querySelector("input[type='checkbox']");
    if (checkbox && !e.target.matches("input[type='checkbox']")) {
      checkbox.checked = !checkbox.checked;
      const event = new Event("change", { bubbles: true });
      checkbox.dispatchEvent(event);
    }
  }
});

tesResult.addEventListener("change", async function (e) {
  const checkboxes = document.querySelectorAll("[name='tes[]']");
  const semuaTes = document.querySelector("[name='semua-tes']");
  const checkedCheckboxes = document.querySelectorAll("[name='tes[]']:checked");

  if (e.target.matches("[name='tes[]']")) {
    if (checkedCheckboxes.length === 0) {
      semuaTes.checked = false;
    } else if (checkboxes.length === checkedCheckboxes.length) {
      semuaTes.checked = true;
    } else {
      semuaTes.checked = false;
    }
  } else if (e.target.matches("[name='semua-tes']")) {
    checkboxes.forEach((checkbox) => {
      checkbox.checked = e.target.checked;
    });
  }
});
