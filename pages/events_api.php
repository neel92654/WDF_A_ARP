<?php
require_once __DIR__ . '/../php/auth.php';

$session = check_auth('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events API CRUD (Fetch API) - Student Hub</title>
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
                <li class="nav-item"><a class="nav-link" href="manage_events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link active" href="events_api.php">Events API CRUD</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Student View</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-2 border-bottom">
        <div>
            <h2 class="h3 fw-bold text-primary mb-1">Live Events API CRUD</h2>
            <p class="text-muted mb-0">Asynchronous database operations using Fetch API without page reload</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#apiAddModal">+ Add Event via API</button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefresh">Refresh</button>
        </div>
    </div>

    <div id="apiAlert"></div>

    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <input type="text" id="apiSearch" class="form-control form-control-sm" placeholder="Live search title, venue or description...">
                </div>
                <div class="col-6 col-md-4">
                    <select id="apiCategory" class="form-select form-select-sm">
                        <option value="all">All Categories</option>
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
                <div class="col-6 col-md-2">
                    <select id="apiSort" class="form-select form-select-sm">
                        <option value="dateAsc">Date: Earliest</option>
                        <option value="dateDesc">Date: Latest</option>
                        <option value="name">Title: A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <span class="small fw-semibold text-muted" id="apiStatus">Loading events from API...</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="apiTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                Fetching events from backend database...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="apiAddModal" tabindex="-1" aria-labelledby="apiAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAddEvent">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="apiAddModalLabel">Add Event (Fetch API)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" id="addTitle" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea id="addDescription" class="form-control form-control-sm" rows="3" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" id="addDate" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                            <select id="addCategory" class="form-select form-select-sm" required>
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
                            <input type="text" id="addVenue" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Status</label>
                            <select id="addStatus" class="form-select form-select-sm">
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnAddSubmit">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="apiEditModal" tabindex="-1" aria-labelledby="apiEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditEvent">
                <input type="hidden" id="editId">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6" id="apiEditModalLabel">Edit Event (Fetch API)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Title <span class="text-danger">*</span></label>
                        <input type="text" id="editTitle" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea id="editDescription" class="form-control form-control-sm" rows="3" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" id="editDate" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category <span class="text-danger">*</span></label>
                            <select id="editCategory" class="form-select form-select-sm" required>
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
                            <input type="text" id="editVenue" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Status</label>
                            <select id="editStatus" class="form-select form-select-sm">
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnEditSubmit">Update Event</button>
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
var currentEvents = [];

function showAlert(message, type) {
    var box = document.getElementById("apiAlert");
    box.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
        message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

function loadApiEvents() {
    var search = document.getElementById("apiSearch").value.trim();
    var category = document.getElementById("apiCategory").value;
    var sort = document.getElementById("apiSort").value;

    var url = "../api/events.php?search=" + encodeURIComponent(search) +
        "&category=" + encodeURIComponent(category) +
        "&sort=" + encodeURIComponent(sort);

    document.getElementById("apiStatus").textContent = "Fetching events from API...";

    fetch(url)
        .then(function (response) {
            if (!response.ok) throw new Error("API returned status " + response.status);
            return response.json();
        })
        .then(function (res) {
            if (!res.success) throw new Error(res.error || "Failed to load events");
            currentEvents = res.data || [];
            renderApiTable(currentEvents);
            document.getElementById("apiStatus").textContent = currentEvents.length + " event(s) loaded from MySQL API.";
        })
        .catch(function (err) {
            document.getElementById("apiStatus").textContent = "Error: " + err.message;
            var tbody = document.getElementById("apiTableBody");
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">' + err.message + '</td></tr>';
        });
}

function renderApiTable(events) {
    var tbody = document.getElementById("apiTableBody");
    tbody.innerHTML = "";

    if (events.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No matching events found.</td></tr>';
        return;
    }

    events.forEach(function (ev) {
        var tr = document.createElement("tr");

        var badgeClass = ev.status === "upcoming" ? "bg-success" : (ev.status === "completed" ? "bg-secondary" : "bg-danger");

        tr.innerHTML = '<td><strong>' + escapeHtml(ev.title) + '</strong><small class="text-muted d-block text-truncate" style="max-width:300px;">' + escapeHtml(ev.description) + '</small></td>' +
            '<td>' + escapeHtml(ev.event_date) + '</td>' +
            '<td>' + escapeHtml(ev.venue) + '</td>' +
            '<td><span class="badge bg-primary">' + escapeHtml(ev.category) + '</span></td>' +
            '<td><span class="badge ' + badgeClass + '">' + escapeHtml(ev.status) + '</span></td>' +
            '<td class="text-end">' +
                '<button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 me-1" onclick="openEditModal(' + ev.id + ')">Edit</button>' +
                '<button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="deleteApiEvent(' + ev.id + ')">Delete</button>' +
            '</td>';

        tbody.appendChild(tr);
    });
}

function escapeHtml(text) {
    if (!text) return "";
    var div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function openEditModal(id) {
    var ev = currentEvents.find(function (e) { return Number(e.id) === Number(id); });
    if (!ev) return;

    document.getElementById("editId").value = ev.id;
    document.getElementById("editTitle").value = ev.title;
    document.getElementById("editDescription").value = ev.description;
    document.getElementById("editDate").value = ev.event_date;
    document.getElementById("editVenue").value = ev.venue;
    document.getElementById("editCategory").value = ev.category;
    document.getElementById("editStatus").value = ev.status;

    var modal = new bootstrap.Modal(document.getElementById("apiEditModal"));
    modal.show();
}

function deleteApiEvent(id) {
    if (!confirm("Are you sure you want to delete this event via Fetch API?")) return;

    fetch("../api/events.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "delete", id: id })
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        if (data.success) {
            showAlert("Event deleted successfully via API.", "success");
            loadApiEvents();
        } else {
            showAlert(data.error || "Failed to delete event.", "danger");
        }
    })
    .catch(function (err) {
        showAlert("Network or server error: " + err.message, "danger");
    });
}

document.getElementById("formAddEvent").addEventListener("submit", function (e) {
    e.preventDefault();
    var btn = document.getElementById("btnAddSubmit");
    btn.disabled = true;
    btn.textContent = "Saving...";

    var payload = {
        action: "create",
        title: document.getElementById("addTitle").value.trim(),
        description: document.getElementById("addDescription").value.trim(),
        event_date: document.getElementById("addDate").value,
        category: document.getElementById("addCategory").value,
        venue: document.getElementById("addVenue").value.trim(),
        status: document.getElementById("addStatus").value
    };

    fetch("../api/events.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        btn.disabled = false;
        btn.textContent = "Save Event";

        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById("apiAddModal")).hide();
            document.getElementById("formAddEvent").reset();
            showAlert("Event added successfully via Fetch API without page reload.", "success");
            loadApiEvents();
        } else {
            showAlert(data.error || "Failed to add event.", "danger");
        }
    })
    .catch(function (err) {
        btn.disabled = false;
        btn.textContent = "Save Event";
        showAlert("Request error: " + err.message, "danger");
    });
});

document.getElementById("formEditEvent").addEventListener("submit", function (e) {
    e.preventDefault();
    var btn = document.getElementById("btnEditSubmit");
    btn.disabled = true;
    btn.textContent = "Updating...";

    var payload = {
        action: "update",
        id: document.getElementById("editId").value,
        title: document.getElementById("editTitle").value.trim(),
        description: document.getElementById("editDescription").value.trim(),
        event_date: document.getElementById("editDate").value,
        category: document.getElementById("editCategory").value,
        venue: document.getElementById("editVenue").value.trim(),
        status: document.getElementById("editStatus").value
    };

    fetch("../api/events.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        btn.disabled = false;
        btn.textContent = "Update Event";

        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById("apiEditModal")).hide();
            showAlert("Event updated successfully via Fetch API.", "success");
            loadApiEvents();
        } else {
            showAlert(data.error || "Failed to update event.", "danger");
        }
    })
    .catch(function (err) {
        btn.disabled = false;
        btn.textContent = "Update Event";
        showAlert("Request error: " + err.message, "danger");
    });
});

document.getElementById("apiSearch").addEventListener("input", loadApiEvents);
document.getElementById("apiCategory").addEventListener("change", loadApiEvents);
document.getElementById("apiSort").addEventListener("change", loadApiEvents);
document.getElementById("btnRefresh").addEventListener("click", loadApiEvents);

document.addEventListener("DOMContentLoaded", loadApiEvents);
</script>
</body>
</html>
