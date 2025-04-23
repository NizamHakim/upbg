const editPertemuanForm = document.getElementById("edit-pertemuan-form");
editPertemuanForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearErrors(editPertemuanForm);
  const route = editPertemuanForm.action;
  const submitButton = e.submitter;
  const formData = new FormData(editPertemuanForm);
  const data = Object.fromEntries(formData);

  playFetchingAnimation(submitButton);
  const response = await fetchRequest(route, "PUT", data);
  stopFetchingAnimation(submitButton);

  if (response.ok) {
    const json = await response.json();
    window.location.href = json.redirect;
  } else {
    handleError(response, editPertemuanForm);
  }
});

const selectTerlaksana = document.querySelector("[name='terlaksana']");
selectTerlaksana.addEventListener("change", (e) => {
  const alert = document.querySelector(".alert-terlaksana");
  if (alert) alert.remove();

  if (e.target.value === "0") {
    const alert = document.createElement("div");
    alert.setAttribute("class", "alert alert-red alert-terlaksana col-span-2");
    alert.innerHTML = `
      <p class="font-semibold mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Peringatan</p>
      <p>Mengubah status pertemuan menjadi <span class="font-semibold">tidak terlaksana</span> berarti <span class="font-semibold">menghapus daftar presensi</span> untuk pertemuan ini secara permanen!</p>
    `;
    selectTerlaksana.parentElement.insertAdjacentElement("afterend", alert);
  } else if (e.target.value === "1") {
    const alert = document.createElement("div");
    alert.setAttribute("class", "alert alert-green alert-terlaksana col-span-2");
    alert.innerHTML = `
      <p class="font-semibold mb-2"><i class="fa-solid fa-circle-info mr-2"></i> Info</p>
      <p>Mengubah status pertemuan menjadi <span class="font-semibold">terlaksana</span> akan <span class="font-semibold">membuat daftar presensi</span> untuk pertemuan ini.</p>
    `;
    selectTerlaksana.parentElement.insertAdjacentElement("afterend", alert);
  }
});
