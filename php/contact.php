<?php
$message = "";
$messageClass = "success";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $contactMessage = trim($_POST["message"] ?? "");

    $errors = [];

    if ($name === "") {
        $errors[] = "Name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email.";
    }
    if ($subject === "") {
        $errors[] = "Subject is required.";
    }
    if ($contactMessage === "") {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $file = __DIR__ . "/../storage/contacts.csv";
        $isNewFile = !file_exists($file) || filesize($file) === 0;
        $handle = fopen($file, "a");

        if ($handle) {
            if ($isNewFile) {
                fputcsv($handle, ["Name", "Email", "Subject", "Message", "Submitted At"]);
            }
            fputcsv($handle, [
                htmlspecialchars($name, ENT_QUOTES, "UTF-8"),
                htmlspecialchars($email, ENT_QUOTES, "UTF-8"),
                htmlspecialchars($subject, ENT_QUOTES, "UTF-8"),
                htmlspecialchars($contactMessage, ENT_QUOTES, "UTF-8"),
                date("Y-m-d H:i:s")
            ]);
            fclose($handle);
            $message = "Your message was submitted successfully.";
        } else {
            $message = "Unable to save your message.";
            $messageClass = "error";
        }
    } else {
        $message = implode(" ", $errors);
        $messageClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Result - CHARUSAT Student Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/contact.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../pages/dashboard.html">
            <img src="../images/image.png" alt="CHARUSAT Logo" height="36" class="d-inline-block align-text-top me-2 bg-white rounded p-1">
            <span class="fw-bold">CHARUSAT STUDENT HUB</span>
        </a>
        <div class="ms-auto">
            <button class="btn btn-outline-light btn-sm" id="themeToggle" onclick="toggleTheme()" aria-label="Toggle theme" title="Toggle Light/Dark Theme">
                <span id="themeIcon">☾</span> <span id="themeText" class="d-none d-sm-inline">Dark</span>
            </button>
        </div>
    </div>
</nav>

<main class="container my-auto py-5 flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px; width: 100%;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">Message Submission Result</h5>
        </div>
        <div class="card-body p-4 text-center">
            <div class="alert <?php echo $messageClass === 'success' ? 'alert-success' : 'alert-danger'; ?> py-3 mb-4">
                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></h6>
            </div>
            <div class="d-flex justify-content-center gap-2">
                <a href="../pages/contact.html" class="btn btn-outline-primary">Back to Contact Form</a>
                <a href="../pages/dashboard.html" class="btn btn-primary">Go to Dashboard</a>
            </div>
        </div>
    </div>
</main>

<footer class="bg-white text-center py-3 mt-auto border-top">
    <div class="container">
        <p class="text-muted small mb-0">&copy; 2026 CHARUSAT Student Hub - Charotar University of Science and Technology</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/common.js"></script>
</body>
</html>
