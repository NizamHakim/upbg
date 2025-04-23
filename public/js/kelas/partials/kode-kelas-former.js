const kodeKelasFormer = document.querySelector("#kode-kelas-former");

const kodeFormers = kodeKelasFormer.querySelectorAll(".kode-former");
kodeFormers.forEach((former) => {
  if (former.querySelector("input[type='number']")) {
    former.querySelector("input[type='number']").addEventListener("input", updateKodeKelas);
  } else if (former.querySelector("select")) {
    former.querySelector("select").addEventListener("change", updateKodeKelas);
  } else if (former.querySelector(".fp-datepicker")) {
    former.querySelector(".fp-datepicker").addEventListener("change", updateKodeKelas);
  }
});

function updateKodeKelas() {
  const kodeKelas = kodeKelasFormer.querySelector('[name="kode-kelas"]');

  const kodeProgram = extractText(kodeKelasFormer.querySelector("[name='program']"));
  const kodeTipe = extractText(kodeKelasFormer.querySelector("[name='tipe']"));
  const nomorKelas = kodeKelasFormer.querySelector('[name="nomor"]').value;
  const kodeLevel = extractText(kodeKelasFormer.querySelector("[name='level']"));
  const banyakPertemuan = kodeKelasFormer.querySelector('[name="banyak-pertemuan"]').value;
  const tanggalMulai = new Date(kodeKelasFormer.querySelector('[name="tanggal-mulai"]').value);
  const bulanMulai = monthParse(tanggalMulai.getMonth());
  const tahunMulai = tanggalMulai.getFullYear();

  let array = [`${kodeProgram}`, `${kodeTipe}.${nomorKelas}`, `${kodeLevel}`, `${banyakPertemuan}`, `${bulanMulai}`, `${tahunMulai}`];
  array = array.filter((item) => item !== "" && item !== null && item !== "NaN" && item !== "undefined" && item !== ".");
  kodeKelas.value = array.join("/");
}

function extractText(select) {
  const text = select.selectedOptions[0].textContent;
  return text.match(/\(([^)]+)\)/) ? text.match(/\(([^)]+)\)/)[1] : "";
}

function monthParse(num) {
  const roman = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  return roman[num];
}
