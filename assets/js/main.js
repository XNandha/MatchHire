/* ---------------------------------------------------------
 * MAIN.JS - MatchHire
 * Smooth Apple-like UI Interactions + Job Portal Enhancements
 * --------------------------------------------------------- */

/* ===============================
   NAVBAR SCROLL EFFECT (Apple-like)
   =============================== */
const navbar = document.querySelector(".navbar");
let lastScrollY = 0;

window.addEventListener("scroll", () => {
    if (window.scrollY > 10) {
        navbar.classList.add("nav-scrolled");
    } else {
        navbar.classList.remove("nav-scrolled");
    }
    lastScrollY = window.scrollY;
});


/* ===============================
   SOFT HOVER SCALE ON JOB CARDS
   =============================== */
const jobCards = document.querySelectorAll(".job-card");

jobCards.forEach(card => {
    card.addEventListener("mouseenter", () => {
        card.style.transform = "translateY(-6px)";
        card.style.transition = "0.25s ease";
        card.style.boxShadow = "0 10px 25px rgba(0,0,0,0.08)";
    });
    card.addEventListener("mouseleave", () => {
        card.style.transform = "translateY(0)";
        card.style.boxShadow = "0 6px 18px rgba(0,0,0,0.05)";
    });
});


/* ===============================
   SMOOTH PAGE FADE-IN EFFECT
   =============================== */
document.addEventListener("DOMContentLoaded", () => {
    document.body.classList.add("page-loaded");
});


/* ===============================
   GENERAL SOFT FADE UTILITY
   =============================== */
function fadeIn(el, duration = 300) {
    el.style.opacity = 0;
    el.style.display = "block";
    let last = +new Date();
    let tick = function () {
        el.style.opacity =
            +el.style.opacity + (new Date() - last) / duration;

        last = +new Date();

        if (+el.style.opacity < 1) {
            window.requestAnimationFrame(tick);
        }
    };
    tick();
}


/* ===============================
   MODAL SYSTEM (Reusable)
   =============================== */
/*
  Modal HTML structure to be included in any view:

  <div class="modal-overlay" id="m1">
     <div class="modal-box">
        <button class="modal-close">&times;</button>
        <div class="modal-content"> ... </div>
     </div>
  </div>
*/
const modals = document.querySelectorAll(".modal-overlay");

modals.forEach(modal => {
    const closeBtn = modal.querySelector(".modal-close");
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            modal.classList.remove("modal-show");
        });
    }
});

function showModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add("modal-show");
}

function hideModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove("modal-show");
}


/* ===============================
   TOAST ALERT / NOTIFICATION SYSTEM
   =============================== */

function toast(message, type = "info") {
    let toastBox = document.createElement("div");
    toastBox.className = `toast ${type}`;
    toastBox.innerText = message;

    document.body.appendChild(toastBox);

    setTimeout(() => {
        toastBox.style.opacity = "0";
        setTimeout(() => toastBox.remove(), 300);
    }, 2500);
}

// Example: toast("Lamaran berhasil dikirim!", "success");


/* ===============================
   CONFIRM DIALOG (For Delete / Submit Actions)
   =============================== */

function confirmAction(message, callback) {
    const c = window.confirm(message);
    if (c && typeof callback === "function") {
        callback();
    }
}


/* ===============================
   SMOOTH SCROLL (Apple-like)
   =============================== */
function smoothScrollTo(element) {
    document.querySelector(element)?.scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}


/* ===============================
   AUTO-HIGHLIGHT ACTIVE MENU
   =============================== */
const navLinks = document.querySelectorAll(".nav-menu a");
navLinks.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add("active-link");
    }
});


/* ===============================
   FORM ENHANCEMENTS
   =============================== */

// Auto-resize textarea (Apple-like)
const textareas = document.querySelectorAll("textarea");
textareas.forEach(textarea => {
    textarea.addEventListener("input", () => {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
    });
});


/* ===============================
   JOB SEARCH FILTER (optional enhancement)
   =============================== */

function filterJobs(keyword) {
    keyword = keyword.toLowerCase();
    document.querySelectorAll(".job-card").forEach(card => {
        const title = card.querySelector("h3")?.innerText.toLowerCase();
        const company = card.querySelector(".job-company")?.innerText.toLowerCase();
        if (title.includes(keyword) || company.includes(keyword)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll(".fade-section");

    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                }
            });
        },
        {
            threshold: 0.15
        }
    );

    sections.forEach(section => observer.observe(section));
});


/* ===============================
   END OF MAIN.JS
   =============================== */
