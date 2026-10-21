document.addEventListener("DOMContentLoaded", function () {
    var params = new URLSearchParams(window.location.search);
    var alertBox = document.getElementById("loginAlert");

    if (alertBox && (params.has("error") || params.has("msg"))) {
        var error = params.get("error");
        var msg = params.get("msg");
        var text = "";
        var isError = true;

        if (error === "invalid_credentials") {
            text = "Invalid username or password. Please try again.";
        } else if (error === "empty_fields") {
            text = "Please enter both username and password.";
        } else if (error === "server_error") {
            text = "Database error. Please try again later.";
        } else if (msg === "logged_out") {
            text = "You have been logged out successfully.";
            isError = false;
        } else if (msg === "timeout") {
            text = "Your session expired due to inactivity. Please log in again.";
        } else if (msg === "unauthorized") {
            text = "Please log in to access your dashboard.";
        }

        if (text !== "") {
            var alertClass = isError ? "alert-danger" : "alert-success";
            alertBox.innerHTML = '<div class="alert ' + alertClass + ' py-2 small mb-3 text-center" role="alert">' + text + '</div>';
        }
    }
});

document.querySelector("form").addEventListener("submit", function (event) {
    var username = document.getElementById("username").value.trim();
    var password = document.getElementById("password").value.trim();

    if (username === "" || password === "") {
        event.preventDefault();
        var alertBox = document.getElementById("loginAlert");
        if (alertBox) {
            alertBox.innerHTML = '<div class="alert alert-danger py-2 small mb-3 text-center" role="alert">Please fill in both Username and Password fields.</div>';
        } else {
            alert("Please fill in both Username and Password fields.");
        }
    }
});
