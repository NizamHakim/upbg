const editForm = document.querySelector(".update-kelas-form");
editForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  clearErrors(editForm);
  const route = editForm.getAttribute("action");
  const submitButton = e.submitter;

  const formData = new FormData(editForm);
  const data = Object.fromEntries(formData);
  delete data["pengajar"];
  delete data["hari"];
  delete data["waktu-mulai"];
  delete data["waktu-selesai"];
  data["pengajar"] = formData.getAll("pengajar");
  data["hari"] = formData.getAll("hari");
  data["waktu-mulai"] = formData.getAll("waktu-mulai");
  data["waktu-selesai"] = formData.getAll("waktu-selesai");

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "PUT", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.href = json.redirect;
  } else {
    handleError(response, editForm);
  }
});
