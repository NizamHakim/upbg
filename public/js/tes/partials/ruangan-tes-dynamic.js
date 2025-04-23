const ruanganTesDynamic = document.getElementById("ruangan-tes-dynamic");

const ruanganContainer = ruanganTesDynamic.querySelector(".ruangan-container");
ruanganContainer.addEventListener("click", function (e) {
  if (e.target.closest(".delete-ruangan")) {
    e.stopPropagation();
    const ruanganItem = e.target.closest(".ruangan-item");
    ruanganItem.remove();
  }
});

const tambahRuangan = ruanganTesDynamic.querySelector(".tambah-ruangan");
tambahRuangan.addEventListener("click", function () {
  const templateRuangan = document.getElementById("template-ruangan");
  const ruanganItem = templateRuangan.content.cloneNode(true);
  const select = ruanganItem.querySelector("[name='ruangan']");
  initializeTomSelect(select);
  ruanganContainer.appendChild(ruanganItem);
});
