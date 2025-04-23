const pengajarKelasDynamic = document.getElementById("pengajar-kelas-dynamic");

const pengajarContainer = pengajarKelasDynamic.querySelector(".pengajar-container");
pengajarContainer.addEventListener("click", function (e) {
  if (e.target.closest(".delete-pengajar")) {
    e.stopPropagation();
    const pengajarItem = e.target.closest(".pengajar-item");
    pengajarItem.remove();
  }
});

const tambahPengajar = pengajarKelasDynamic.querySelector(".tambah-pengajar");
tambahPengajar.addEventListener("click", function () {
  const templatePengajar = document.getElementById("template-pengajar");
  const pengajarItem = templatePengajar.content.cloneNode(true);
  const select = pengajarItem.querySelector("[name='pengajar']");
  initializeTomSelect(select);
  pengajarContainer.appendChild(pengajarItem);
});
