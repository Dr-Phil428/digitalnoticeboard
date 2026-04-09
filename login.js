const passwordInput = document.getElementById("password");
const tooltip = document.getElementById("loginPasswordRequirements");

passwordInput.addEventListener("focus", () => {
  tooltip.classList.add("show");
});

passwordInput.addEventListener("input", () => {
  const value = passwordInput.value;

  const lowercase = /[a-z]/.test(value);
  const uppercase = /[A-Z]/.test(value);
  const number    = /\d/.test(value);
  const special   = /[!@#$%^&*]/.test(value);
  const length    = value.length >= 6;

  document.getElementById("login-lowercase").style.color = lowercase ? "green" : "red";
  document.getElementById("login-uppercase").style.color = uppercase ? "green" : "red";
  document.getElementById("login-number").style.color    = number ? "green" : "red";
  document.getElementById("login-special").style.color   = special ? "green" : "red";
  document.getElementById("login-length").style.color    = length ? "green" : "red";

  // Hide tooltip when all conditions are met
  if (lowercase && uppercase && number && special && length) {
    tooltip.classList.remove("show");
  } else {
    tooltip.classList.add("show");
  }
});

passwordInput.addEventListener("blur", () => {
  tooltip.classList.remove("show");
});

document.querySelectorAll(".toggle-password").forEach(icon => {

    icon.addEventListener("click", () => {

        const target = document.getElementById(icon.dataset.target);

        if(target.type === "password"){
            target.type = "text";
        } else {
            target.type = "password";
        }

    });

});
