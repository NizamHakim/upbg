const kodeTesFormer = document.getElementById("kode-tes-former");

const kodeFormers = kodeTesFormer.querySelectorAll(".kode-former");
kodeFormers.forEach((former) => {
  if (former.querySelector("select")) {
    former.querySelector("select").addEventListener("change", updateKodeTes);
  } else if (former.querySelector(".fp-datepicker")) {
    former.querySelector(".fp-datepicker").addEventListener("change", updateKodeTes);
  } else {
    former.querySelector("input").addEventListener("input", updateKodeTes);
  }
});

function updateKodeTes() {
  const kodeTes = kodeTesFormer.querySelector('[name="kode-tes"]');

  const kodeTipe = extractText(kodeTesFormer.querySelector("[name='tipe']"));
  const namaTes = kodeTesFormer.querySelector("[name='nama']").value;
  const tanggal = new Date(kodeTesFormer.querySelector('[name="tanggal"]').value);
  const bulan = monthParse(tanggal.getMonth());
  const tahun = tanggal.getFullYear();

  let array = [`${kodeTipe}`, `${namaTes}`, `${bulan}`, `${tahun}`];
  array = array.filter((item) => item !== "" && item !== null && item !== "NaN" && item !== "undefined" && item !== ".");
  kodeTes.value = array.join("/");
}

function extractText(select) {
  return select.selectedOptions[0].dataset.kode;
}

function monthParse(num) {
  const roman = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  return roman[num];
}
