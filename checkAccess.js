// Show the correct form depending on the role selected
function showForm(role) {
  // Hide both forms first
  document.getElementById("adminForm").style.display = "none";
  document.getElementById("staffForm").style.display = "none";

  // Show the chosen form
  if (role === "admin") {
    document.getElementById("adminForm").style.display = "block";
  } else if (role === "staff") {
    document.getElementById("staffForm").style.display = "block";
  }
}

// Handle "Others" selection
function denyAccess() {
  alert("Access Denied");
  setTimeout(() => {
    window.location.href = "notices.html"; 
  }, 1000);
}