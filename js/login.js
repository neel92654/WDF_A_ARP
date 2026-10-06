document.querySelector("form").addEventListener("submit", function (event) {
    var username = document.getElementById("username").value.trim();
    var password = document.getElementById("password").value.trim();

    if (username === "" || password === "") {
        event.preventDefault();
        alert("Please fill in both Username and Password fields.");
    }
});
