<?php
require_once __DIR__ . '/db.php';

$message = "";
$messageClass = "success";
$assignedEnrollment = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $gender = trim($_POST["gender"] ?? "");

    $errors = [];

    if (!preg_match("/^[A-Za-z ]{2,50}$/", $name)) {
        $errors[] = "Enter a valid full name (letters and spaces only, 2-50 characters).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid university email address.";
    }
    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {
        $errors[] = "Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.";
    }
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/", $password)) {
        $errors[] = "Password must have at least 6 characters with at least one letter and one number.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    if ($course === "") {
        $errors[] = "Please select an academic course.";
    }
    if ($year === "") {
        $errors[] = "Please select an academic year.";
    }
    if ($gender === "") {
        $errors[] = "Please select your gender.";
    }

    if (empty($errors)) {
        $conn = get_db_connection();

        $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $errors[] = "This email is already registered. Please log in or use a different email.";
            mysqli_stmt_close($checkStmt);
        } else {
            mysqli_stmt_close($checkStmt);

            $parts = explode('@', $email);
            $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $parts[0]);
            if ($baseUsername === '') {
                $baseUsername = 'student_' . rand(100, 999);
            }
            $username = $baseUsername;

            $uCheck = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
            mysqli_stmt_bind_param($uCheck, "s", $username);
            mysqli_stmt_execute($uCheck);
            mysqli_stmt_store_result($uCheck);
            if (mysqli_stmt_num_rows($uCheck) > 0) {
                $username = $baseUsername . '_' . rand(10, 99);
            }
            mysqli_stmt_close($uCheck);

            $countRes = mysqli_query($conn, "SELECT MAX(id) AS max_id FROM students");
            $row = mysqli_fetch_assoc($countRes);
            $nextNum = ($row['max_id'] ? (int)$row['max_id'] : 0) + 1;
            $assignedEnrollment = sprintf("STU2026%03d", $nextNum);

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';

            mysqli_begin_transaction($conn);

            $userInsert = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($userInsert, "ssss", $username, $email, $passwordHash, $role);
            $userSuccess = mysqli_stmt_execute($userInsert);
            $newUserId = mysqli_insert_id($conn);
            mysqli_stmt_close($userInsert);

            if ($userSuccess && $newUserId) {
                $studentInsert = mysqli_prepare($conn, "INSERT INTO students (user_id, enrollment_no, full_name, email, mobile, course, year, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($studentInsert, "isssssss", $newUserId, $assignedEnrollment, $name, $email, $mobile, $course, $year, $gender);
                $studentSuccess = mysqli_stmt_execute($studentInsert);
                mysqli_stmt_close($studentInsert);

                if ($studentSuccess) {
                    mysqli_commit($conn);

                    require_once __DIR__ . '/audit.php';
                    record_audit_action($conn, $newUserId, 'REGISTER_USER', 'users', $newUserId, "Self-registered student account $username ($assignedEnrollment)");

                    $csvFile = __DIR__ . "/../storage/registrations.csv";
                    $isNewFile = !file_exists($csvFile) || filesize($csvFile) === 0;
                    $csvHandle = @fopen($csvFile, "a");
                    if ($csvHandle) {
                        if ($isNewFile) {
                            fputcsv($csvHandle, ["Enrollment", "Name", "Email", "Mobile", "Course", "Year", "Gender", "Registered At"], ',', '"', '\\');
                        }
                        fputcsv($csvHandle, [
                            $assignedEnrollment,
                            htmlspecialchars($name, ENT_QUOTES, "UTF-8"),
                            htmlspecialchars($email, ENT_QUOTES, "UTF-8"),
                            htmlspecialchars($mobile, ENT_QUOTES, "UTF-8"),
                            htmlspecialchars($course, ENT_QUOTES, "UTF-8"),
                            htmlspecialchars($year, ENT_QUOTES, "UTF-8"),
                            htmlspecialchars($gender, ENT_QUOTES, "UTF-8"),
                            date("Y-m-d H:i:s")
                        ], ',', '"', '\\');
                        fclose($csvHandle);
                    }

                    $message = "Student registration completed successfully. Your enrollment ID is " . $assignedEnrollment . " and your login username is " . $username . ".";
                    $messageClass = "success";
                } else {
                    mysqli_rollback($conn);
                    $message = "Failed to create student profile record.";
                    $messageClass = "error";
                }
            } else {
                mysqli_rollback($conn);
                $message = "Failed to create user account.";
                $messageClass = "error";
            }
        }
    }

    if (!empty($errors)) {
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
    <title>Registration Result - CHARUSAT Student Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">
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
            <h5 class="mb-0 fw-bold text-primary">Registration Status</h5>
        </div>
        <div class="card-body p-4 text-center">
            <div class="alert <?php echo $messageClass === 'success' ? 'alert-success' : 'alert-danger'; ?> py-3 mb-4">
                <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
            </div>
            <div class="d-flex justify-content-center gap-2">
                <a href="../pages/register.html" class="btn btn-outline-primary">Back to Registration</a>
                <a href="../pages/login.html" class="btn btn-primary">Go to Login</a>
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
