<?php
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/db.php';

$session = check_auth('admin');
$conn = get_db_connection();

$message = "";
$messageClass = "success";

function validate_and_upload_poster($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'path' => null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error code: ' . $file['error']];
    }

    $maxBytes = 2 * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        return ['success' => false, 'error' => 'Poster image size must not exceed 2MB.'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mime, $allowedMimes)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPEG, PNG, and WebP images are permitted.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        return ['success' => false, 'error' => 'Invalid file extension.'];
    }

    $uploadDir = __DIR__ . '/../uploads/posters/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $safeName = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowedMimes[$mime];
    $destination = $uploadDir . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Failed to save uploaded poster image.'];
    }

    return ['success' => true, 'path' => 'uploads/posters/' . $safeName];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        $venue = trim($_POST['venue'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $status = trim($_POST['status'] ?? 'upcoming');

        if ($title === '' || $description === '' || $eventDate === '' || $venue === '' || $category === '') {
            $message = "Please fill in all required event details.";
            $messageClass = "danger";
        } else {
            $uploadResult = validate_and_upload_poster($_FILES['poster'] ?? null);
            if (!$uploadResult['success']) {
                $message = $uploadResult['error'];
                $messageClass = "danger";
            } else {
                $posterPath = $uploadResult['path'];
                $adminId = (int)$session['user_id'];

                $stmt = mysqli_prepare($conn, "INSERT INTO events (title, description, event_date, venue, category, poster_path, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $eventDate, $venue, $category, $posterPath, $status, $adminId);
                if (mysqli_stmt_execute($stmt)) {
                    $newId = mysqli_insert_id($conn);
                    require_once __DIR__ . '/../php/audit.php';
                    record_audit_action($conn, $adminId, 'CREATE_EVENT', 'events', $newId, "Created event $title ($category)");
                    $message = "Event created successfully.";
                    $messageClass = "success";
                } else {
                    $message = "Database error while creating event.";
                    $messageClass = "danger";
                }
                mysqli_stmt_close($stmt);
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        $venue = trim($_POST['venue'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $status = trim($_POST['status'] ?? 'upcoming');

        if ($id <= 0 || $title === '' || $description === '' || $eventDate === '' || $venue === '' || $category === '') {
            $message = "Please fill in all required fields.";
            $messageClass = "danger";
        } else {
            $uploadResult = validate_and_upload_poster($_FILES['poster'] ?? null);
            if (!$uploadResult['success']) {
                $message = $uploadResult['error'];
                $messageClass = "danger";
            } else {
                if ($uploadResult['path'] !== null) {
                    $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, description = ?, event_date = ?, venue = ?, category = ?, poster_path = ?, status = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $eventDate, $venue, $category, $uploadResult['path'], $status, $id);
                } else {
                    $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, description = ?, event_date = ?, venue = ?, category = ?, status = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "ssssssi", $title, $description, $eventDate, $venue, $category, $status, $id);
                }

                if (mysqli_stmt_execute($stmt)) {
                    require_once __DIR__ . '/../php/audit.php';
                    record_audit_action($conn, (int)$session['user_id'], 'UPDATE_EVENT', 'events', $id, "Updated event $title ($category)");
                    $message = "Event updated successfully.";
                    $messageClass = "success";
                } else {
                    $message = "Failed to update event.";
                    $messageClass = "danger";
                }
                mysqli_stmt_close($stmt);
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $selStmt = mysqli_prepare($conn, "SELECT poster_path FROM events WHERE id = ?");
            mysqli_stmt_bind_param($selStmt, "i", $id);
            mysqli_stmt_execute($selStmt);
            $res = mysqli_stmt_get_result($selStmt);
            $ev = mysqli_fetch_assoc($res);
            mysqli_stmt_close($selStmt);

            $delStmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
            mysqli_stmt_bind_param($delStmt, "i", $id);
            if (mysqli_stmt_execute($delStmt)) {
                require_once __DIR__ . '/../php/audit.php';
                record_audit_action($conn, (int)$session['user_id'], 'DELETE_EVENT', 'events', $id, "Deleted event ID $id");
                if ($ev && !empty($ev['poster_path'])) {
                    $f = __DIR__ . '/../' . $ev['poster_path'];
                    if (file_exists($f) && is_file($f)) {
                        @unlink($f);
                    }
                }
                $message = "Event deleted successfully.";
                $messageClass = "success";
            } else {
                $message = "Failed to delete event.";
                $messageClass = "danger";
            }
            mysqli_stmt_close($delStmt);
        }
    }
}

$eventsRes = mysqli_query($conn, "SELECT id, title, description, event_date, venue, category, poster_path, status FROM events ORDER BY event_date ASC");
$events = [];
while ($row = mysqli_fetch_assoc($eventsRes)) {
    $events[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management CRUD - Student Hub</title>
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
                <li class="nav-item"><a class="nav-link" href="manage_students.php">Manage Students</a></li>
                <li class="nav-item"><a class="nav-link active" href="manage_events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link" href="events_api.php">Events API CRUD</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Student View</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-2 border-bottom">
        <div>
            <h2 class="h3 fw-bold text-primary mb-1">Event Management &amp; Posters</h2>
            <p class="text-muted mb-0">Total Events: <strong><?php echo count($events); ?></strong> recorded</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="events.html" class="btn btn-outline-secondary btn-sm" target="_blank">View Public Events</a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">+ Add Event</button>
        </div>
    </div>

    <?php if ($message !== ""): ?>
    <div class="alert alert-<?php echo htmlspecialchars($messageClass); ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Poster</th>
                            <th>Event Title</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No events found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($events as $ev): ?>
                        <tr>
                            <td style="width: 70px;">
                                <?php if (!empty($ev['poster_path']) && file_exists(__DIR__ . '/../' . $ev['poster_path'])): ?>
                                <img src="../<?php echo htmlspecialchars($ev['poster_path']); ?>" alt="Poster" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                <img src="../images/image.png" alt="Default Poster" class="rounded border p-1 bg-white" style="width: 50px; height: 50px; object-fit: contain;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($ev['title']); ?></strong>
                                <small class="text-muted d-block text-truncate" style="max-width: 280px;"><?php echo htmlspecialchars($ev['description']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($ev['event_date']); ?></td>
                            <td><?php echo htmlspecialchars($ev['venue']); ?></td>
                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($ev['category']); ?></span></td>
                            <td>
                                <span class="badge <?php echo $ev['status'] === 'upcoming' ? 'bg-success' : ($ev['status'] === 'completed' ? 'bg-secondary' : 'bg-danger'); ?>">
                                    <?php echo htmlspecialchars($ev['status']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 edit-event-btn"
                                    data-id="<?php echo (int)$ev['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($ev['title']); ?>"
                                    data-description="<?php echo htmlspecialchars($ev['description']); ?>"
                                    data-date="<?php echo htmlspecialchars($ev['event_date']); ?>"
                                    data-venue="<?php echo htmlspecialchars($ev['venue']); ?>"
                                    data-category="<?php echo htmlspecialchars($ev['category']); ?>"
                                    data-status="<?php echo htmlspecialchars($ev['status']); ?>"
                                    data-bs-toggle="modal" data-bs-target="#editEventModal">
                                    Edit
                                </button>
                                <form method="post" action="manage_events.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo (int)$ev['id']; ?>">
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

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="manage_events.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm" required placeholder="Event name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" required placeholder="Brief description of event"></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="Academic">Academic</option>
                                <option value="Club">Club</option>
                                <option value="Competition">Competition</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Social">Social</option>
                                <option value="Sports">Sports</option>
                                <option value="Workshop">Workshop</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-bold">Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue" class="form-control form-control-sm" required placeholder="Campus location">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Poster (JPEG, PNG, WebP &le; 2MB)</label>
                        <input type="file" name="poster" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="manage_events.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editEventId">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="editEventModalLabel">Edit Event Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="editDescription" class="form-control form-control-sm" rows="3" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" id="editDate" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="category" id="editCategory" class="form-select form-select-sm" required>
                                <option value="Academic">Academic</option>
                                <option value="Club">Club</option>
                                <option value="Competition">Competition</option>
                                <option value="Cultural">Cultural</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Social">Social</option>
                                <option value="Sports">Sports</option>
                                <option value="Workshop">Workshop</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-bold">Venue <span class="text-danger">*</span></label>
                            <input type="text" name="venue" id="editVenue" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" id="editStatus" class="form-select form-select-sm">
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Change Event Poster (optional)</label>
                        <input type="file" name="poster" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Event</button>
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
document.querySelectorAll(".edit-event-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
        document.getElementById("editEventId").value = this.dataset.id;
        document.getElementById("editTitle").value = this.dataset.title;
        document.getElementById("editDescription").value = this.dataset.description;
        document.getElementById("editDate").value = this.dataset.date;
        document.getElementById("editVenue").value = this.dataset.venue;
        document.getElementById("editCategory").value = this.dataset.category;
        document.getElementById("editStatus").value = this.dataset.status;
    });
});
</script>
</body>
</html>
