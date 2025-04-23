const filterPeserta = document.getElementById("filter-peserta");
filterPeserta.addEventListener("submit", function (e) {
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
