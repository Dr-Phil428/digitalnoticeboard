// Toggle between student and parent forms
function toggleForm() {
  const type = document.querySelector('input[name="userType"]:checked').value;
  document.getElementById('studentForm').style.display = (type === 'student') ? 'block' : 'none';
  document.getElementById('parentForm').style.display = (type === 'parent') ? 'block' : 'none';
}

// Optional: smooth scroll to feedback section if needed
function showFeedback() {
  const section = document.getElementById('feedbackSection');
  section.style.display = 'block';
  section.scrollIntoView({ behavior: 'smooth' });
}