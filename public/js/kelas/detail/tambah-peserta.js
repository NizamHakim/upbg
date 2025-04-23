const tambahPesertaForm = document.querySelector(".tambah-peserta-form");
tambahPesertaForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  clearErrors(tambahPesertaForm);
  const route = tambahPesertaForm.getAttribute("action");
  const submitButton = e.submitter;
  const formData = new FormData(tambahPesertaForm);
  const data = Object.fromEntries(formData);

  delete data["nik"];
  delete data["nama"];
  delete data["occupation"];
  data["nik"] = formData.getAll("nik");
  data["nama"] = formData.getAll("nama");
  data["occupation"] = formData.getAll("occupation");

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "POST", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.href = json.redirect;
  } else {
    handleError(response, tambahPesertaForm);
  }
});
