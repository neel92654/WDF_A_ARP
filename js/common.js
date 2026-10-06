function toggleMenu() {
    var menu = document.getElementById("mainMenu");
    var button = document.querySelector(".menu-button");
    if (menu) {
        menu.classList.toggle("menu-hidden");
        if (button) {
            button.setAttribute("aria-expanded", menu.classList.contains("menu-hidden") ? "false" : "true");
        }
    }
}

function updateThemeButton() {
    var icon = document.getElementById("themeIcon");
    var text = document.getElementById("themeText");
    var dark = document.body.classList.contains("dark");
    if (icon) icon.textContent = dark ? "☀" : "☾";
    if (text) text.textContent = dark ? "Light" : "Dark";
    
    if (dark) {
        document.documentElement.setAttribute("data-bs-theme", "dark");
    } else {
        document.documentElement.setAttribute("data-bs-theme", "light");
    }
}

function toggleTheme() {
    document.body.classList.toggle("dark");
    var theme = document.body.classList.contains("dark") ? "dark" : "light";
    localStorage.setItem("theme", theme);
    updateThemeButton();
}

window.addEventListener("load", function () {
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
    }
    updateThemeButton();
});
