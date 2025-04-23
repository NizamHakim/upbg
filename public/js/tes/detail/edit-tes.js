const editTesForm = document.querySelector(".edit-tes-form");
editTesForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  clearErrors(editTesForm);
  const route = editTesForm.getAttribute("action");
  const submitButton = e.submitter;

  const formData = new FormData(editTesForm);
  const data = Object.fromEntries(formData);
  delete data["ruangan"];
  delete data["pengawas"];
  data["ruangan"] = formData.getAll("ruangan");
  data["pengawas"] = formData.getAll("pengawas");

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "PUT", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.href = json.redirect;
  } else {
    handleError(response, editTesForm);
  }
});
