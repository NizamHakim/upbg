const pengawasTesDynamic = document.getElementById("pengawas-tes-dynamic");

const pengawasContainer = pengawasTesDynamic.querySelector(".pengawas-container");
pengawasContainer.addEventListener("click", function (e) {
  if (e.target.closest(".delete-pengawas")) {
    e.stopPropagation();
    const pengawasItem = e.target.closest(".pengawas-item");
    pengawasItem.remove();
  }
});

const tambahPengawas = pengawasTesDynamic.querySelector(".tambah-pengawas");
tambahPengawas.addEventListener("click", function () {
  const templatePengawas = document.getElementById("template-pengawas");
  const pengawasItem = templatePengawas.content.cloneNode(true);
  const select = pengawasItem.querySelector("[name='pengawas']");
  initializeTomSelect(select);
  pengawasContainer.appendChild(pengawasItem);
});
