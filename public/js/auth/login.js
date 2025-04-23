document.addEventListener("DOMContentLoaded", () => {
  const loginContainer = document.querySelector(".login-container");
  loginContainer.classList.replace("opacity-0", "opacity-100");
  loginContainer.classList.replace("translate-y-4", "translate-y-0");
});

const togglePassword = document.querySelector(".toggle-password");
const password = document.querySelector('[name="password"]');
togglePassword.addEventListener("click", () => {
  if (password.type === "password") {
    password.type = "text";
    togglePassword.querySelector("i").classList.replace("fa-eye", "fa-eye-slash");
  } else {
    password.type = "password";
    togglePassword.querySelector("i").classList.replace("fa-eye-slash", "fa-eye");
  }
});
