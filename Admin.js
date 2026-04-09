document.addEventListener('DOMContentLoaded', function() {
  // Get the calendar container
  const calendarEl = document.getElementById('calendar');

  // Initialize FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',   // Month grid view
    nowIndicator: true,            // Show current time indicator
    selectable: true,              // Allow date selection
    editable: false,               // Disable drag/drop unless you want it
    events: 'reminders.php',       // Fetch reminders dynamically from backend

    // Handle event clicks
    eventClick: function(info) {
      alert("Reminder: " + info.event.title + 
            "\nDate: " + info.event.start.toLocaleString());
    },

    // Optional: handle date clicks if you want to add reminders directly
    dateClick: function(info) {
      console.log("Clicked date:", info.dateStr);
      // You could open a modal or form here to add a new reminder
    }
  });

  // Render the calendar
  calendar.render();
window.dashboardCalendar = calendar;

  // Auto-refresh events every minute to stay up-to-date
  setInterval(() => {
    calendar.refetchEvents();
  }, 60000);
});

document.addEventListener('DOMContentLoaded', fetchLiveNotices);

let notices = [];
let currentIndex = 0;

function typeWriter(element, text, delay = 50, callback) {
  element.textContent = "";
  const chars = Array.from(text);
  let i = 0;

  function typing() {
    if (i < chars.length) {
      element.textContent += chars[i];
      i++;
      setTimeout(typing, delay);
    } else if (callback) {
      callback();
    }
  }
  typing();
}

function showNotice() {
  if (!notices || notices.length === 0) return;

  const notice = notices[currentIndex];
  const noticeCard = document.getElementById("noticeCard");
  const previewTitle = document.getElementById("previewTitle");
  const previewContent = document.getElementById("previewContent");

  noticeCard.classList.remove("show");

  setTimeout(() => {
    typeWriter(previewTitle, notice.title, 80, () => {
      typeWriter(previewContent, notice.content, 40, () => {
        noticeCard.classList.add("show");
      });
    });
  }, 2000);

  currentIndex = (currentIndex + 1) % notices.length;
}

function getPriorityValue(priority) {
  return parseInt(priority, 10) || 0;
}

function fetchLiveNotices() {
  fetch('fetch_live_preview.php?ts=' + Date.now()) // cache-busting
    .then(res => res.json())
    .then(data => {
      if (Array.isArray(data) && data.length > 0) {
        notices = data.sort((a, b) => getPriorityValue(b.priority) - getPriorityValue(a.priority));
        currentIndex = 0;
        showNotice();
        setInterval(showNotice, 20000);
      } else {
        document.getElementById("previewTitle").textContent = "No Active Notices";
        document.getElementById("previewContent").textContent = "Admin will post updates here soon.";
      }
    })
    .catch(err => console.error("Error fetching live previews:", err));
}

// --- Priority helpers ---
function getPriorityValue(priorityStr) {
  const p = priorityStr ? priorityStr.toLowerCase() : '';
  if (p === 'high') return 3;
  if (p === 'medium') return 2;
  if (p === 'low') return 1;
  return 0;
}

// --- Add new notice dynamically (optional) ---
function appendPrioritizedNotice(newNotice) {
  notices.push(newNotice);
  notices.sort((a, b) => getPriorityValue(b.priority) - getPriorityValue(a.priority));
  currentIndex = 0;
  showNotice();
}
// --- DETECT NOTICES POSTED BY STAFF ---
window.addEventListener('storage', (event) => {
  if (event.key === 'new_staff_notice') {
    try {
      const newNotice = JSON.parse(event.newValue);

      // Add with priority handling
      appendPrioritizedNotice(newNotice);

      // Optional: Alert the admin directly (comment out if too intrusive)
      // alert(`New Notice Posted by Staff: ${newNotice.title}`);
    } catch (e) {
      console.error("Error parsing staff notice:", e);
    }
  }
});

function showSection(sectionId) {
  // List all section IDs
  const sections = [
    'dashboard',
    'Notices',
    'Reports',
    'Messages',
    'Reminders',
    'Profile',
    'Settings',
    'Logout'
  ];
  sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });

  // Show the one we clicked
  const target = document.getElementById(sectionId);
  if (target) {
    // Restore the correct layout for dashboard
    if (sectionId === 'dashboard') {
      target.style.display = 'flex';   // dashboard needs flex
    } else {
      target.style.display = 'block';  // other sections can be block
    }
  }
}
document.querySelectorAll('.report-card').forEach(card => {
  card.addEventListener('click', (event) => {
    if (event.target.closest('.report-tools')){
      return;
    }
    const details = card.querySelector('.report-details');

    if (details.style.display !== 'block') {
      details.style.display = 'block';

      // Expired Posts
      if (card.id === 'expiredReportsCard') {
        const listDiv = document.getElementById('expiredPostsList');
        if (listDiv) {
          listDiv.innerHTML = '<div style="text-align:center;"><i class="ph ph-spinner-gap ph-spin"></i> Loading...</div>';
          fetch("fetch_expired_notices.php")
            .then(res => res.text())
            .then(html => {
              if (html.toLowerCase().includes("failed") || html.toLowerCase().includes("error")) {
                listDiv.innerHTML = '<p style="color:red;">Server Error loading posts.</p>';
              } else {
                listDiv.innerHTML = html;
              }
            })
            .catch(err => {
              console.error(err);
              listDiv.innerHTML = '<p style="color:red;">Error loading posts.</p>';
            });
        }
      }

      // Active Posts
      else if (card.id === 'activeReportsCard') {
        const listDiv = document.getElementById('activePostsList');
        if (listDiv) {
          listDiv.innerHTML = '<div style="text-align:center;"><i class="ph ph-spinner-gap ph-spin"></i> Loading...</div>';
          fetch("fetch_active_notices.php")
            .then(res => res.text())
            .then(html => {
              if (html.toLowerCase().includes("failed") || html.toLowerCase().includes("error")) {
                listDiv.innerHTML = '<p style="color:red;">Server Error loading posts.</p>';
              } else {
                listDiv.innerHTML = html;
              }
            })
            .catch(err => {
              console.error(err);
              listDiv.innerHTML = '<p style="color:red;">Error loading posts.</p>';
            });
        }
      }

      // Pending Posts (NEW)
      else if (card.id === 'pendingReportsCard') {
        const listDiv = document.getElementById('pendingPostsList');
        if (listDiv) {
          listDiv.innerHTML = '<div style="text-align:center;"><i class="ph ph-spinner-gap ph-spin"></i> Loading...</div>';
          fetch("fetch_pending_notices.php")
            .then(res => res.text())
            .then(html => {
              if (html.toLowerCase().includes("failed") || html.toLowerCase().includes("error")) {
                listDiv.innerHTML = '<p style="color:red;">Server Error loading posts.</p>';
              } else {
                listDiv.innerHTML = html;
              }
            })
            .catch(err => {
              console.error(err);
              listDiv.innerHTML = '<p style="color:red;">Error loading posts.</p>';
            });
        }
      }

    } else {
      details.style.display = 'none';
    }
  });
});
function saveNotice() {
  const formData = new FormData();
  formData.append("title", document.getElementById("title").value);
  formData.append("content", document.getElementById("content").value);
  formData.append("category", document.getElementById("category").value);
  formData.append("priority", document.getElementById("priority").value);
  formData.append("posting_date", document.getElementById("postingDate").value);
  formData.append("expiry_date", document.getElementById("expiryDate").value);

  const attachment = document.getElementById("attachment").files[0];
  if (attachment) {
    formData.append("attachment", attachment);
  }

  fetch("notice_save.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(error => console.error("Error:", error));
}

function postNotice() {
  const title = document.getElementById("title").value;
  const content = document.getElementById("content").value;

  if (!title || !content) {
    alert("Please enter a title and content.");
    return;
  }

  const formData = new FormData();
  formData.append("title", title);
  formData.append("content", content);
  formData.append("category", document.getElementById("category").value);
  formData.append("priority", document.getElementById("priority").value);
  formData.append("posting_date", document.getElementById("postingDate").value);
  formData.append("expiry_date", document.getElementById("expiryDate").value);

  const attachment = document.getElementById("attachment").files[0];
  if (attachment) {
    formData.append("attachment", attachment);
  }

  fetch("notice_post.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.json()) // expect JSON now
    .then(data => {
      if (data.status === "success") {
        alert(data.message); // confirmation popup
        fetchLiveNotices();  // refresh live preview immediately
      } else {
        alert("Error: " + data.message);
      }

      // Clear fields after posting
      document.getElementById("title").value = '';
      document.getElementById("content").value = '';
      document.getElementById("category").value = '';
      document.getElementById("priority").value = '';
      document.getElementById("postingDate").value = '';
      document.getElementById("expiryDate").value = '';
      document.getElementById("attachment").value = '';
    })
    .catch(error => console.error("Error posting notice:", error));
}

function expiredNotice() {
  const title = document.getElementById("title").value;
  const content = document.getElementById("content").value;

  if (!title || !content) {
    alert("Please enter a title and content.");
    return;
  }

  const formData = new FormData();
  formData.append("title", title);
  formData.append("content", content);
  formData.append("category", document.getElementById("category").value);
  formData.append("priority", document.getElementById("priority").value);
  formData.append("posting_date", document.getElementById("postingDate").value);
  formData.append("expiry_date", document.getElementById("expiryDate").value);

  const attachment = document.getElementById("attachment").files[0];
  if (attachment) {
    formData.append("attachment", attachment);
  }

  fetch("notice_expired.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      if (data.toLowerCase().includes("failed") || data.toLowerCase().includes("error")) {
        alert("Server Error:\n" + data);
        return;
      }

      let successMsg = data.replace(/(<([^>]+)>)/gi, "").trim();
      alert(successMsg || "Notice saved as expired successfully!");

      // Clear fields
      document.getElementById("title").value = '';
      document.getElementById("content").value = '';
    })
    .catch(error => console.error("Error:", error));
}


function addNotice(status) {
  console.log("addNotice called for " + status);
  showSection('Notices');
  const statusField = document.getElementById("noticeStatus");
  if (statusField) statusField.value = status;
  document.getElementById("Notices").scrollIntoView({ behavior: "smooth" });
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("reminderForm");
  const list = document.getElementById("reminderList");
  let reminders = [];

  // Load reminders on page load
  fetch("get_reminder.php")
    .then(res => res.json())
    .then(data => {
      reminders = data;
      reminders.forEach(r => addReminderToList(r));
    });

  // Save new reminder
  form.addEventListener("submit", async function(e) {
    e.preventDefault(); // stop page reload

    const formData = new FormData(this);

    try {
      const res = await fetch("save_reminder.php", {
        method: "POST",
        body: formData
      });
      const data = await res.json(); // expect JSON from PHP

      if (data.status === "success" || data.id) {
        alert("Reminder saved!");
        addReminderToList(data);

        // Refresh dashboard calendar if available
        if (window.dashboardCalendar) {
          window.dashboardCalendar.refetchEvents();
        }
      } else {
        alert("Error saving reminder: " + (data.message || "unknown error"));
      }
    } catch (err) {
      console.error("Save reminder failed:", err);
      alert("Failed to save reminder.");
    }
  });
});


// ✅ Single unified function with buttons
function addReminderToList(reminder) {
  const item = document.createElement("div");
  item.className = "reminder-item";
  item.dataset.id = reminder.id;

  item.innerHTML = `
    <div class="reminder-text">
      ${reminder.title} - ${reminder.reminder_date} ${reminder.reminder_time}
    </div>
    <div class="reminder-actions">
      <button class="snooze-btn">Snooze 15m</button>
      <button class="complete-btn">Complete</button>
    </div>
  `;

  if (reminder.status === "completed") {
    item.classList.add("completed");
  }

  // Snooze button
  item.querySelector(".snooze-btn").addEventListener("click", () => {
    fetch("snooze_reminder.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${reminder.id}`
    }).then(() => {
      alert("Reminder snoozed for 15 minutes!");
      if (window.dashboardCalendar) {
        window.dashboardCalendar.refetchEvents();
      }
    });
  });

  // ✅ Single complete button listener
  item.querySelector(".complete-btn").addEventListener("click", () => {
    fetch("complete_reminder.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${reminder.id}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        item.classList.add("completed");
        alert("Reminder marked as complete!");
        if (window.dashboardCalendar) {
          window.dashboardCalendar.refetchEvents();
        }
      } else {
        alert("Error: " + data.message);
      }
    });
  });

  document.getElementById("reminderList").appendChild(item);
}

function saveDetails(event) {
  event.preventDefault();
  document.getElementById("name").textContent = document.getElementById("editName").value;
  document.getElementById("username").textContent = document.getElementById("editUsername").value;
  document.getElementById("email").textContent = document.getElementById("editEmail").value;
  document.getElementById("phone").textContent = document.getElementById("editPhone").value;
  document.getElementById("role").textContent = document.getElementById("editRole").value;
  toggleForm();
}
function resetPassword(event) {
  event.preventDefault();
  
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const message = document.getElementById("passwordMessage");
  const success = document.getElementById("successMessage");

  // Password rules: at least 6 chars, uppercase, lowercase, number, special char
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

  if (!passwordRegex.test(newPassword)) {
    message.textContent = "Password must be at least 6 characters, include uppercase, lowercase, number, and special character.";
    success.textContent = "";
    return;
  }

  if (newPassword !== confirmPassword) {
    message.textContent = "Passwords do not match!";
    success.textContent = "";
    return;
  }

  // If all checks pass
  message.textContent = "";
  success.textContent = "✅ Password has been reset successfully!";
  
  // TODO: send new password to backend (PHP/MySQL) for saving
}

function showSection(sectionId) {
  // List all section IDs you want to toggle
  const sections = ["dashboard", "Notices", "Reports", "Reminders", "profile-page"];

  // Hide all sections
  sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = "none";
  });

  // Show the selected section
  const target = document.getElementById(sectionId);
  if (target) target.style.display = "block";
}
document.getElementById("logoutLink").addEventListener("click", function(e) {
  e.preventDefault(); // prevent default link behavior

  const msg = document.getElementById("logoutOverlay");
  msg.style.display = "block"; // show message

  // Hide after 3 seconds and redirect
  setTimeout(() => {
    msg.style.display = "none";
    window.location.href = "LOGIN1.html"; // replace with your login page
  }, 3000);
});
document.addEventListener("DOMContentLoaded", () => {
  const logoutLink = document.getElementById("logoutLink");
  const msg = document.getElementById("logoutOverlay");

  logoutLink.addEventListener("click", function(e) {
    e.preventDefault(); // stop default link behavior

    msg.classList.add("show"); // show message

    // Hide after 3 seconds and redirect
    setTimeout(() => {
      msg.classList.remove("show");
      window.location.href = "LOGIN1.html"; // replace with your login page
    }, 3000);
  });
});

function toggleForm() {
  const form = document.getElementById("editForm");
  if (form.style.display === "none") {
    form.style.display = "block";
  } else {
    form.style.display = "none";
  }
}
function more() {
  // Example: show a message or expand a section
  console.log("Hover triggered");
}

function loadDashboardMessages() {
  const container = document.getElementById("messagesContainer"); 
  if (!container) return; // only run if dashboard container exists

  fetch("getMessages.php")
    .then(res => res.json())
    .then(messages => {
      // Render ALL messages, no limit
      container.innerHTML = messages.map((m) => {
        let details = '';
        if (m.type === 'student') {
          details = `ID: ${m.student_id} | Grade: ${m.grade} | Category: ${m.category}`;
        } else if (m.type === 'parent') {
          details = `Parent | Grade: ${m.grade} | Category: ${m.category} | Email: ${m.email}`;
        }

        // No reply UI at all
        return `
          <div class="message-card">
            <div class="message-details">${details}</div>
            <div class="message-text">${m.message}</div>
            <small>${m.created_at}</small>
          </div>
        `;
      }).join("");
    })
    .catch(err => console.error("Error loading dashboard messages:", err));
}

document.addEventListener("DOMContentLoaded", loadDashboardMessages);
let noticeChart;

function loadNoticeChart() {
  const canvas = document.getElementById("noticeChart");
  if (!canvas) {
    console.error("Canvas #noticeChart not found in DOM");
    return;
  }

  fetch("getNoticeStats.php")
    .then(res => res.json())
    .then(data => {
      const ctx = canvas.getContext("2d");

      if (noticeChart) {
        noticeChart.destroy();
      }

      noticeChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels: ["Active", "Pending", "Expired"],
          datasets: [{
            data: [data.active || 0, data.pending || 0, data.expired || 0],
            backgroundColor: ["#4caf50", "#ffeb3b", "#f44336"]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: "bottom" },
            title: { display: true, text: "📊 Notices Breakdown" }
          }
        }
      });
    })
    .catch(err => console.error("Error loading notice stats:", err));
}

document.addEventListener("DOMContentLoaded", loadNoticeChart);
setTimeout(function() {
    var popup = document.getElementById('reply-popup');
    if (popup) {
      popup.style.display = 'none';
    }
  }, 60000); 
  function loadPendingNotices() {
  fetch('../auth/reports.php') // adjust path if needed
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('pendingPostsList');
      container.innerHTML = '';

      if (data.length === 0) {
        container.innerHTML = '<p>No pending notices.</p>';
      } else {
        data.forEach(row => {
          const noticeDiv = document.createElement('div');
          noticeDiv.id = `notice_${row.id}`;
          noticeDiv.style.borderBottom = "1px solid #ddd";
          noticeDiv.style.padding = "10px";
          noticeDiv.style.marginBottom = "10px";

          noticeDiv.innerHTML = `
            <h4 id="title_${row.id}">${row.title}</h4>
            <p id="content_${row.id}">${row.content}</p>
            <small>Category: ${row.category} | Posted: ${row.posting_date} | Expiry: ${row.expiry_date}</small><br><br>
            <button onclick="updateNotice(${row.id}, 'approve')" style="background:green;color:white;">Approve</button>
            <button onclick="updateNotice(${row.id}, 'deny')" style="background:red;color:white;margin-left:10px;">Deny</button>
          `;

          container.appendChild(noticeDiv);
        });
      }
    })
 .catch(err => {
      console.error("Fetch error:", err);
      document.getElementById('pendingPostsList').innerHTML = '<p style="color:red;">Failed to load notices.</p>';
    });
}

// Approve/Deny action
function updateNotice(id, action) {
  fetch('/auth/handleNotice.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&action=${action}`
  })
  .then(res => res.text())
  .then(() => {
    // Remove from pending list
    const noticeDiv = document.getElementById(`notice_${id}`);
    if (noticeDiv) noticeDiv.remove();
if (action === 'approve') {
      const preview = document.getElementById('livePreview');
      const title = document.getElementById(`title_${id}`).innerText;
      const content = document.getElementById(`content_${id}`).innerText;

      preview.innerHTML = `
        <div style="border:1px solid #ccc; padding:10px; margin-top:10px;">
          <h3>${title}</h3>
          <p>${content}</p>
          <small>Status: Active</small>
        </div>
      `;
    }
  })
  .catch(err => console.error("Update error:", err));
}

// Load notices on page load
loadPendingNotices();
