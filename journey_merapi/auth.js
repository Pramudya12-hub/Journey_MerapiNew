/* ================== DARK MODE ================== */
const darkBtn = document.getElementById("darkModeToggle");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
}

if (darkBtn) {
    darkBtn.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("darkMode",
            document.body.classList.contains("dark-mode") ? "enabled" : "disabled"
        );
    });
}

/* ================== PAGE TRANSITION ================== */
document.body.classList.add("page-transition");

window.addEventListener("load", () => {
    setTimeout(() => document.body.classList.add("page-loaded"), 50);
});

document.querySelectorAll("a").forEach(link => {
    if (!link.href || link.target === "_blank" || link.href.includes("#")) return;

    link.addEventListener("click", function (e) {
        e.preventDefault();
        const url = this.href;
        document.body.classList.remove("page-loaded");
        setTimeout(() => window.location.href = url, 300);
    });
});

/* ================== MODAL SYSTEM ================== */
const loginModal = document.getElementById("loginModal");
const registerModal = document.getElementById("registerModal");
const openLogin = document.getElementById("openLogin");

/* OPEN LOGIN */
if (openLogin) {
    openLogin.addEventListener("click", () => {
        loginModal.classList.add("active");
    });
}

/* CLOSE ALL */
document.querySelectorAll(".close-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        loginModal.classList.remove("active");
        registerModal.classList.remove("active");
    });
});

/* SWITCH LOGIN → REGISTER */
document.querySelectorAll("[data-switch='register']").forEach(btn => {
    btn.addEventListener("click", e => {
        e.preventDefault();
        loginModal.classList.remove("active");
        registerModal.classList.add("active");
    });
});

/* SWITCH REGISTER → LOGIN */
document.querySelectorAll("[data-switch='login']").forEach(btn => {
    btn.addEventListener("click", e => {
        e.preventDefault();
        registerModal.classList.remove("active");
        loginModal.classList.add("active");
    });
});

/* CLOSE IF CLICK OUTSIDE */
window.addEventListener("click", e => {
    if (e.target === loginModal) loginModal.classList.remove("active");
    if (e.target === registerModal) registerModal.classList.remove("active");
});
