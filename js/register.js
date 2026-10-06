var form = document.getElementById("registrationForm");
var password = document.getElementById("password");

password.addEventListener("input", function () {
    var value = password.value;
    var strength = document.getElementById("passwordStrength");
    if (value.length < 6) strength.textContent = "Password strength: Weak";
    else if (/[A-Z]/.test(value) && /[0-9]/.test(value)) strength.textContent = "Password strength: Strong";
    else strength.textContent = "Password strength: Medium";
});

form.addEventListener("submit", function (event) {
    var valid = true;
    document.querySelectorAll(".error").forEach(function (e) { e.textContent = ""; });
    document.getElementById("successMessage").textContent = "";

    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("email").value.trim();
    var mobile = document.getElementById("mobile").value.trim();
    var pass = password.value;
    var confirm = document.getElementById("confirmPassword").value;
    var course = document.getElementById("course").value;
    var year = document.getElementById("year").value;
    var gender = document.querySelector('input[name="gender"]:checked');
    var terms = document.getElementById("terms").checked;

    if (!/^[A-Za-z ]{2,50}$/.test(name)) { document.getElementById("nameError").textContent = "Enter a valid name."; valid=false; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { document.getElementById("emailError").textContent = "Enter a valid email."; valid=false; }
    if (!/^[6-9][0-9]{9}$/.test(mobile)) { document.getElementById("mobileError").textContent = "Enter a valid 10-digit mobile number."; valid=false; }
    if (!/^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/.test(pass)) { document.getElementById("passwordError").textContent = "Use at least 6 characters with a letter and number."; valid=false; }
    if (pass !== confirm) { document.getElementById("confirmError").textContent = "Passwords do not match."; valid=false; }
    if (course === "") { document.getElementById("courseError").textContent = "Select a course."; valid=false; }
    if (year === "") { document.getElementById("yearError").textContent = "Select a year."; valid=false; }
    if (!gender) { document.getElementById("genderError").textContent = "Select gender."; valid=false; }
    if (!terms) { document.getElementById("termsError").textContent = "Accept the terms."; valid=false; }

    if (!valid) {
        event.preventDefault();
    }
});
