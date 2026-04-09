document.addEventListener("DOMContentLoaded", () => {
  const newPasswordInput = document.getElementById("newPassword");
  const requirementsBox = document.getElementById("newPasswordRequirements");
  const confirmPasswordInput = document.getElementById("confirmPassword");
  const passwordMessage = document.getElementById("passwordMessage");

  // Show checklist when New Password field is focused
  newPasswordInput.addEventListener("focus", () => {
    requirementsBox.style.display = "block";
  });

  // Hide checklist if empty when field loses focus
  newPasswordInput.addEventListener("blur", () => {
    if (newPasswordInput.value === "") {
      requirementsBox.style.display = "none";
    }
  });

  // Live validation for requirements
  newPasswordInput.addEventListener("input", () => {
    const value = newPasswordInput.value;

    document.getElementById("new-lowercase").classList.toggle("valid", /[a-z]/.test(value));
    document.getElementById("new-uppercase").classList.toggle("valid", /[A-Z]/.test(value));
    document.getElementById("new-number").classList.toggle("valid", /\d/.test(value));
    document.getElementById("new-special").classList.toggle("valid", /[^A-Za-z0-9]/.test(value));
    document.getElementById("new-length").classList.toggle("valid", value.length >= 6);

    // ✅ Check if all requirements are met
    const allValid = [...requirementsBox.querySelectorAll("li")].every(li =>
      li.classList.contains("valid")
    );

    if (allValid) {
      requirementsBox.style.display = "none"; // hide tooltip once all are green
    } else {
      requirementsBox.style.display = "block"; // keep showing until all are met
    }
  });

  // Live check for password match
  confirmPasswordInput.addEventListener("input", () => {
    if (confirmPasswordInput.value === newPasswordInput.value) {
      passwordMessage.textContent = "✔ Passwords match";
      passwordMessage.style.color = "#66bb6a"; // green
    } else {
      passwordMessage.textContent = "✘ Passwords do not match";
      passwordMessage.style.color = "#ff5252"; // red
    }
  });
});
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
      const targetId = icon.getAttribute("data-target");
      const input = document.getElementById(targetId);

      if (input.type === "password") {
        input.type = "text";   // show password
        // Swap to "eye-off" SVG if you have it
      } else {
        input.type = "password"; // hide password
        // Swap back to "eye" SVG
      }
    });
  });
});
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  toast.textContent = message;

  // Different colors for success vs error
  toast.style.backgroundColor = type === "success" ? "#4CAF50" : "#f44336";

  toast.className = "toast show";
  setTimeout(() => {
    toast.className = toast.className.replace("show", "");
  }, 3000); // disappears after 3 seconds
}

function resetPassword(event) {
  event.preventDefault();

  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (newPassword !== confirmPassword) {
    showToast("Passwords do not match!", "error");
    return;
  }

  const formData = new FormData();
  formData.append("newPassword", newPassword);
  formData.append("confirmPassword", confirmPassword);

  fetch("reset_password.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    if (data.includes("success")) {
      showToast("Password reset successfully!", "success");
      setTimeout(() => {
        window.location.href = "login.php?reset=success";
      }, 1500);
    } else {
      showToast(data, "error");
    }
  })
  .catch(error => {
    console.error("Error:", error);
    showToast("Something went wrong.", "error");
  });
}