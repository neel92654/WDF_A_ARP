<?php
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/db.php';

$session = check_auth('admin');
$conn = get_db_connection();

$message = "";
$messageClass = "success";

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=students_export_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Enrollment', 'Full Name', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'Created At'], ',', '"', '\\');
    $exportQuery = mysqli_query($conn, "SELECT id, enrollment_no, full_name, email, mobile, course, year, gender, created_at FROM students ORDER BY id ASC");
    while ($row = mysqli_fetch_assoc($exportQuery)) {
        fputcsv($output, $row, ',', '"', '\\');
    }
    fclose($output);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $enrollment = trim($_POST['enrollment_no'] ?? '');
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $year = trim($_POST['year'] ?? '');
        $gender = trim($_POST['gender'] ?? '');

        if ($enrollment === '' || $name === '' || $email === '' || $mobile === '' || $course === '' || $year === '') {
            $message = "Please fill in all required fields.";
            $messageClass = "danger";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please enter a valid email address.";
            $messageClass = "danger";
        } else {
            $dupCheck = mysqli_prepare($conn, "SELECT id FROM students WHERE enrollment_no = ? OR email = ? LIMIT 1");
            mysqli_stmt_bind_param($dupCheck, "ss", $enrollment, $email);
            mysqli_stmt_execute($dupCheck);
            mysqli_stmt_store_result($dupCheck);

            if (mysqli_stmt_num_rows($dupCheck) > 0) {
                $message = "Student with this enrollment number or email already exists.";
                $messageClass = "danger";
                mysqli_stmt_close($dupCheck);
            } else {
                mysqli_stmt_close($dupCheck);

                $insertStmt = mysqli_prepare($conn, "INSERT INTO students (enrollment_no, full_name, email, mobile, course, year, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($insertStmt, "sssssss", $enrollment, $name, $email, $mobile, $course, $year, $gender);
                if (mysqli_stmt_execute($insertStmt)) {
                    $newId = mysqli_insert_id($conn);
                    require_once __DIR__ . '/../php/audit.php';
                    record_audit_action($conn, (int)$session['user_id'], 'CREATE_STUDENT', 'students', $newId, "Enrolled student $enrollment ($name)");
                    $message = "Student record created successfully.";
                    $messageClass = "success";
                } else {
                    $message = "Failed to create student record.";
                    $messageClass = "danger";
                }
                mysqli_stmt_close($insertStmt);
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $enrollment = trim($_POST['enrollment_no'] ?? '');
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $year = trim($_POST['year'] ?? '');
        $gender = trim($_POST['gender'] ?? '');

        if ($id <= 0 || $enrollment === '' || $name === '' || $email === '') {
            $message = "Invalid update parameters.";
            $messageClass = "danger";
        } else {
            $updateStmt = mysqli_prepare($conn, "UPDATE students SET enrollment_no = ?, full_name = ?, email = ?, mobile = ?, course = ?, year = ?, gender = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateStmt, "sssssssi", $enrollment, $name, $email, $mobile, $course, $year, $gender, $id);
            if (mysqli_stmt_execute($updateStmt)) {
                require_once __DIR__ . '/../php/audit.php';
                record_audit_action($conn, (int)$session['user_id'], 'UPDATE_STUDENT', 'students', $id, "Updated student $enrollment ($name)");
                $message = "Student record updated successfully.";
                $messageClass = "success";
            } else {
                $message = "Failed to update student record.";
                $messageClass = "danger";
            }
            mysqli_stmt_close($updateStmt);
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $delStmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
            mysqli_stmt_bind_param($delStmt, "i", $id);
            if (mysqli_stmt_execute($delStmt)) {
                require_once __DIR__ . '/../php/audit.php';
                record_audit_action($conn, (int)$session['user_id'], 'DELETE_STUDENT', 'students', $id, "Deleted student ID $id");
                $message = "Student record deleted successfully.";
                $messageClass = "success";
            } else {
                $message = "Failed to delete student record.";
                $messageClass = "danger";
            }
            mysqli_stmt_close($delStmt);
        }
    }
}

$search = trim($_GET['search'] ?? '');
$courseFilter = trim($_GET['course'] ?? 'all');

$sql = "SELECT id, enrollment_no, full_name, email, mobile, course, year, gender, created_at FROM students WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (full_name LIKE ? OR enrollment_no LIKE ? OR email LIKE ?)";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

if ($courseFilter !== '' && $courseFilter !== 'all') {
    $sql .= " AND course = ?";
    $params[] = $courseFilter;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management CRUD - Student Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
            <img src="../images/image.png" alt="CHARUSAT Logo" height="36" class="d-inline-block align-text-top me-2 bg-white rounded p-1">
            <span class="fw-bold">CHARUSAT STUDENT HUB</span>
            <span class="badge bg-danger ms-2">Admin</span>
        </a>
        <div class="d-flex align-items-center order-lg-last ms-auto">
            <button class="btn btn-outline-light btn-sm me-2" id="themeToggle" onclick="toggleTheme()" aria-label="Toggle theme">
                <span id="themeIcon">☾</span> <span id="themeText" class="d-none d-sm-inline">Dark</span>
            </button>
            <a href="../php/logout.php" class="btn btn-light btn-sm me-2 me-lg-0 text-primary fw-semibold">Logout</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Overview</a></li>
                <li class="nav-item"><a class="nav-link active" href="manage_students.php">Manage Students</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link" href="events_api.php">Events API CRUD</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Student View</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-2 border-bottom">
        <div>
            <h2 class="h3 fw-bold text-primary mb-1">Student Management</h2>
            <p class="text-muted mb-0">Total Records: <strong><?php echo count($students); ?></strong> student(s)</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="manage_students.php?export=csv" class="btn btn-outline-secondary btn-sm">Export to CSV</a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Add Student</button>
        </div>
    </div>

    <?php if ($message !== ""): ?>
    <div class="alert alert-<?php echo htmlspecialchars($messageClass); ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="get" action="manage_students.php" class="row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, enrollment, or email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-12 col-md-4">
                    <select name="course" class="form-select form-select-sm">
                        <option value="all" <?php echo $courseFilter === 'all' ? 'selected' : ''; ?>>All Courses</option>
                        <option value="B.Tech Computer Engineering" <?php echo $courseFilter === 'B.Tech Computer Engineering' ? 'selected' : ''; ?>>B.Tech Computer Engineering</option>
                        <option value="B.Tech IT" <?php echo $courseFilter === 'B.Tech IT' ? 'selected' : ''; ?>>B.Tech IT</option>
                        <option value="BCA" <?php echo $courseFilter === 'BCA' ? 'selected' : ''; ?>>BCA</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    <a href="manage_students.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Enrollment</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Gender</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No student records matching your query.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($students as $s): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($s['enrollment_no']); ?></strong></td>
                            <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo htmlspecialchars($s['mobile']); ?></td>
                            <td><?php echo htmlspecialchars($s['course']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($s['year']); ?></span></td>
                            <td><?php echo htmlspecialchars($s['gender']); ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 edit-btn"
                                    data-id="<?php echo (int)$s['id']; ?>"
                                    data-enrollment="<?php echo htmlspecialchars($s['enrollment_no']); ?>"
                                    data-name="<?php echo htmlspecialchars($s['full_name']); ?>"
                                    data-email="<?php echo htmlspecialchars($s['email']); ?>"
                                    data-mobile="<?php echo htmlspecialchars($s['mobile']); ?>"
                                    data-course="<?php echo htmlspecialchars($s['course']); ?>"
                                    data-year="<?php echo htmlspecialchars($s['year']); ?>"
                                    data-gender="<?php echo htmlspecialchars($s['gender']); ?>"
                                    data-bs-toggle="modal" data-bs-target="#editStudentModal">
                                    Edit
                                </button>
                                <form method="post" action="manage_students.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student record?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="manage_students.php">
                <input type="hidden" name="action" value="create">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Enrollment Number</label>
                        <input type="text" name="enrollment_no" class="form-control form-control-sm" required placeholder="e.g. STU2026022">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required placeholder="Student Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-sm" required placeholder="student@charusat.edu.in">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mobile Number</label>
                        <input type="tel" name="mobile" class="form-control form-control-sm" required placeholder="10-digit mobile number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Course</label>
                        <select name="course" class="form-select form-select-sm" required>
                            <option value="B.Tech Computer Engineering">B.Tech Computer Engineering</option>
                            <option value="B.Tech IT">B.Tech IT</option>
                            <option value="BCA">BCA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Year</label>
                        <select name="year" class="form-select form-select-sm" required>
                            <option value="First Year">First Year</option>
                            <option value="Second Year">Second Year</option>
                            <option value="Third Year">Third Year</option>
                            <option value="Fourth Year">Fourth Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Gender</label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="manage_students.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editStudentId">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="editStudentModalLabel">Edit Student Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Enrollment Number</label>
                        <input type="text" name="enrollment_no" id="editEnrollment" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="full_name" id="editName" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" id="editEmail" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mobile Number</label>
                        <input type="tel" name="mobile" id="editMobile" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Course</label>
                        <select name="course" id="editCourse" class="form-select form-select-sm" required>
                            <option value="B.Tech Computer Engineering">B.Tech Computer Engineering</option>
                            <option value="B.Tech IT">B.Tech IT</option>
                            <option value="BCA">BCA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Year</label>
                        <select name="year" id="editYear" class="form-select form-select-sm" required>
                            <option value="First Year">First Year</option>
                            <option value="Second Year">Second Year</option>
                            <option value="Third Year">Third Year</option>
                            <option value="Fourth Year">Fourth Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Gender</label>
                        <select name="gender" id="editGender" class="form-select form-select-sm" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="bg-light text-center py-3 mt-auto border-top">
    <div class="container">
        <p class="text-muted small mb-0">&copy; 2026 CHARUSAT Student Hub - Charotar University of Science and Technology</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/common.js"></script>
<script>
document.querySelectorAll(".edit-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
        document.getElementById("editStudentId").value = this.dataset.id;
        document.getElementById("editEnrollment").value = this.dataset.enrollment;
        document.getElementById("editName").value = this.dataset.name;
        document.getElementById("editEmail").value = this.dataset.email;
        document.getElementById("editMobile").value = this.dataset.mobile;
        document.getElementById("editCourse").value = this.dataset.course;
        document.getElementById("editYear").value = this.dataset.year;
        document.getElementById("editGender").value = this.dataset.gender;
    });
});
</script>
</body>
</html>
