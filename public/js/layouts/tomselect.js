const tomSelects = document.querySelectorAll(".tom-select");
tomSelects.forEach((el) => {
  initializeTomSelect(el);
});

function initializeTomSelect(el) {
  new TomSelect(el, {});
}
