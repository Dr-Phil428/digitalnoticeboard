const titleEl = document.getElementById("previewTitle");
const contentEl = document.getElementById("previewContent");

let notices = [];
let currentIndex = 0;

// Typewriter effect
function typeWriter(text, element, callback) {
  let i = 0;
  element.innerHTML = "";
  const interval = setInterval(() => {
    element.innerHTML += text.charAt(i);
    i++;
    if (i >= text.length) {
      clearInterval(interval);
      if (callback) callback();
    }
  }, 50); // typing speed
}

// Show a notice with typing + slide-out cycle
function showNotice(index) {
  if (notices.length === 0) return;

  const notice = notices[index];

  // Reset slide-out classes
  titleEl.classList.remove("slide-out-title");
  contentEl.classList.remove("slide-out-content");

  // Typewriter effect for title then content
  typeWriter(notice.title, titleEl, () => {
    typeWriter(notice.content, contentEl, () => {
      // After typing finishes, wait 25s then slide out
      setTimeout(() => {
        // Slide out content first
        contentEl.classList.add("slide-out-content");

        // Then slide out title after a short delay
        setTimeout(() => {
          titleEl.classList.add("slide-out-title");

          // After both slide out, clear text and show next notice
          setTimeout(() => {
            titleEl.innerHTML = "";     // clear old title
            contentEl.innerHTML = "";   // clear old content
            currentIndex = (currentIndex + 1) % notices.length;
            showNotice(currentIndex);
          }, 1200); // wait for title animation
        }, 300); // stagger delay
      }, 25000); // 25 seconds visible
    });
  });
}

// Fetch notices dynamically from PHP
fetch("getNotices.php")
  .then(res => res.json())
  .then(data => {
    notices = data;
    if (notices.length > 0) {
      showNotice(currentIndex);
    }
  })
  .catch(err => console.error("Error fetching notices:", err));