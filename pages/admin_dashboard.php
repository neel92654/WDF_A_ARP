<?php
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/db.php';

$session = check_auth('admin');
$conn = get_db_connection();

$totalStudentsRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$totalStudents = mysqli_fetch_assoc($totalStudentsRes)['total'] ?? 0;

$totalEventsRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
$totalEvents = mysqli_fetch_assoc($totalEventsRes)['total'] ?? 0;

$totalRegsRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations");
$totalRegistrations = mysqli_fetch_assoc($totalRegsRes)['total'] ?? 0;

$totalUsersRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$totalUsers = mysqli_fetch_assoc($totalUsersRes)['total'] ?? 0;

$recentStudents = [];
$res = mysqli_query($conn, "SELECT id, enrollment_no, full_name, email, course, year, created_at FROM students ORDER BY id DESC LIMIT 5");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $recentStudents[] = $row;
    }
}

$auditLogs = [];
$resLogs = mysqli_query($conn, "SELECT a.id, a.action_name, a.entity_type, a.entity_id, a.details, a.created_at, u.username FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.id DESC LIMIT 6");
if ($resLogs) {
    while ($row = mysqli_fetch_assoc($resLogs)) {
        $auditLogs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CHARUSAT Student Hub</title>
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
            <button class="btn btn-outline-light btn-sm me-2" id="themeToggle" onclick="toggleTheme()" aria-label="Toggle theme" title="Toggle Light/Dark Theme">
                <span id="themeIcon">☾</span> <span id="themeText" class="d-none d-sm-inline">Dark</span>
            </button>
            <a href="../php/logout.php" class="btn btn-light btn-sm me-2 me-lg-0 text-primary fw-semibold">Logout</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Overview</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_students.php">Manage Students</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link" href="events_api.php">Events API CRUD</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Student View</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="h3 fw-bold text-primary mb-1">Administrative Control Panel</h2>
            <p class="text-muted mb-0">
                <span><strong>Admin Account:</strong> <?php echo htmlspecialchars($session['username'] ?? 'admin'); ?></span> | 
                <span><strong>Role:</strong> System Administrator</span>
            </p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="manage_students.php" class="btn btn-primary btn-sm">+ New Student</a>
            <a href="manage_events.php" class="btn btn-outline-primary btn-sm">+ New Event</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Total Students</div>
                    <div class="h3 fw-bold text-primary mb-1"><?php echo htmlspecialchars((string)$totalStudents); ?></div>
                    <span class="badge bg-primary">Registered</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Active Events</div>
                    <div class="h3 fw-bold text-primary mb-1"><?php echo htmlspecialchars((string)$totalEvents); ?></div>
                    <span class="badge bg-primary">Campus Directory</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Event Bookings</div>
                    <div class="h3 fw-bold text-primary mb-1"><?php echo htmlspecialchars((string)$totalRegistrations); ?></div>
                    <span class="badge bg-primary">Confirmed</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Portal Users</div>
                    <div class="h3 fw-bold text-primary mb-1"><?php echo htmlspecialchars((string)$totalUsers); ?></div>
                    <span class="badge bg-primary">Auth Accounts</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4 text-center">
                    <div class="h4 text-primary fw-bold mb-2">Student Records</div>
                    <p class="text-muted small mb-3">View, search, filter, add, edit, and delete student database records.</p>
                    <a href="manage_students.php" class="btn btn-primary btn-sm w-100">Open Student Management &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4 text-center">
                    <div class="h4 text-primary fw-bold mb-2">Event Management</div>
                    <p class="text-muted small mb-3">Create events, edit schedules, and upload official posters with file validation.</p>
                    <a href="manage_events.php" class="btn btn-primary btn-sm w-100">Open Event Management &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4 text-center">
                    <div class="h4 text-primary fw-bold mb-2">Fetch API CRUD</div>
                    <p class="text-muted small mb-3">Asynchronous real-time event updates using Fetch API without page reload.</p>
                    <a href="events_api.php" class="btn btn-primary btn-sm w-100">Open Fetch API CRUD &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">Recently Registered Students</h5>
            <a href="manage_students.php" class="btn btn-outline-primary btn-sm">View All Students</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Enrollment</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentStudents)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">No student records found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recentStudents as $s): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($s['enrollment_no']); ?></strong></td>
                            <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo htmlspecialchars($s['course']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($s['year']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">System Audit Trail &amp; Activity Log</h5>
            <span class="badge bg-secondary">Security &amp; Operations</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>User</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($auditLogs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">No audit log records found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($auditLogs as $log): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($log['action_name']); ?></span></td>
                            <td><?php echo htmlspecialchars($log['entity_type'] . ($log['entity_id'] ? ' #' . $log['entity_id'] : '')); ?></td>
                            <td><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($log['details'] ?? '-'); ?></small></td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($log['created_at']); ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<footer class="bg-light text-center py-3 mt-auto border-top">
    <div class="container">
        <div class="mb-2">
            <a href="about.html" class="text-decoration-none text-muted mx-2">About</a> |
            <a href="contact.html" class="text-decoration-none text-muted mx-2">Contact</a> |
            <a href="../php/logout.php" class="text-decoration-none text-muted mx-2">Logout</a>
        </div>
        <p class="text-muted small mb-0">&copy; 2026 CHARUSAT Student Hub - Charotar University of Science and Technology</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/common.js"></script>
</body>
</html>
