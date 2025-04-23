const tambahKelasForm = document.querySelector(".tambah-kelas-form");
tambahKelasForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  clearErrors(tambahKelasForm);
  const route = tambahKelasForm.getAttribute("action");
  const submitButton = e.submitter;

  const formData = new FormData(tambahKelasForm);
  const data = Object.fromEntries(formData);
  delete data["pengajar"];
  delete data["hari"];
  delete data["waktu-mulai"];
  delete data["waktu-selesai"];
  delete data["nik"];
  delete data["nama"];
  delete data["occupation"];
  data["nik"] = formData.getAll("nik");
  data["nama"] = formData.getAll("nama");
  data["occupation"] = formData.getAll("occupation");
  data["pengajar"] = formData.getAll("pengajar");
  data["hari"] = formData.getAll("hari");
  data["waktu-mulai"] = formData.getAll("waktu-mulai");
  data["waktu-selesai"] = formData.getAll("waktu-selesai");

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "POST", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.href = json.redirect;
  } else {
    handleError(response, tambahKelasForm);
  }
});
