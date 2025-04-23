document.addEventListener("click", (e) => {
  if (e.target.closest("close-alert")) {
    const alert = e.target.closest(".alert");
    alert.remove();
  }
});
