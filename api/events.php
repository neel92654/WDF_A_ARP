<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';

header('Content-Type: application/json; charset=utf-8');

$conn = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? 'all');
    $sort = trim($_GET['sort'] ?? 'dateAsc');

    $sql = "SELECT id, title, description, event_date, venue, category, poster_path, status FROM events WHERE 1=1";
    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " AND (title LIKE ? OR venue LIKE ? OR description LIKE ?)";
        $like = "%" . $search . "%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types .= "sss";
    }

    if ($category !== '' && $category !== 'all') {
        $sql .= " AND category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if ($sort === 'name') {
        $sql .= " ORDER BY title ASC";
    } elseif ($sort === 'dateDesc') {
        $sql .= " ORDER BY event_date DESC";
    } else {
        $sql .= " ORDER BY event_date ASC";
    }

    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    mysqli_stmt_close($stmt);

    echo json_encode([
        'success' => true,
        'count' => count($events),
        'data' => $events
    ]);
    exit;
}

if ($method === 'POST') {
    start_session_safe();

    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Unauthorized. Administrator access required.'
        ]);
        exit;
    }

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?? [];
    } else {
        $input = $_POST;
    }

    $action = $input['action'] ?? 'create';

    if ($action === 'create') {
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $eventDate = trim($input['event_date'] ?? '');
        $venue = trim($input['venue'] ?? '');
        $category = trim($input['category'] ?? '');
        $status = trim($input['status'] ?? 'upcoming');
        $posterPath = trim($input['poster_path'] ?? 'uploads/posters/techfest.png');
        $adminId = (int)$_SESSION['user_id'];

        if ($title === '' || $description === '' || $eventDate === '' || $venue === '' || $category === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Please provide title, description, date, venue, and category.'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO events (title, description, event_date, venue, category, poster_path, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $eventDate, $venue, $category, $posterPath, $status, $adminId);
        $executed = mysqli_stmt_execute($stmt);
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        if ($executed && $newId) {
            require_once __DIR__ . '/../php/audit.php';
            record_audit_action($conn, $adminId, 'API_CREATE_EVENT', 'events', $newId, "Created event $title via Fetch API");
            echo json_encode([
                'success' => true,
                'message' => 'Event created successfully.',
                'id' => $newId
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to insert event into database.'
            ]);
        }
        exit;
    }

    if ($action === 'update') {
        $id = (int)($input['id'] ?? 0);
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $eventDate = trim($input['event_date'] ?? '');
        $venue = trim($input['venue'] ?? '');
        $category = trim($input['category'] ?? '');
        $status = trim($input['status'] ?? 'upcoming');

        if ($id <= 0 || $title === '' || $description === '' || $eventDate === '' || $venue === '' || $category === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Missing required fields for update.'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, description = ?, event_date = ?, venue = ?, category = ?, status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssssi", $title, $description, $eventDate, $venue, $category, $status, $id);
        $executed = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($executed) {
            require_once __DIR__ . '/../php/audit.php';
            record_audit_action($conn, (int)$_SESSION['user_id'], 'API_UPDATE_EVENT', 'events', $id, "Updated event $title via Fetch API");
            echo json_encode([
                'success' => true,
                'message' => 'Event updated successfully.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to update event in database.'
            ]);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid event id for deletion.'
            ]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $executed = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($executed) {
            require_once __DIR__ . '/../php/audit.php';
            record_audit_action($conn, (int)$_SESSION['user_id'], 'API_DELETE_EVENT', 'events', $id, "Deleted event ID $id via Fetch API");
            echo json_encode([
                'success' => true,
                'message' => 'Event deleted successfully.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to delete event from database.'
            ]);
        }
        exit;
    }
}

http_response_code(405);
echo json_encode([
    'success' => false,
    'error' => 'Method not allowed.'
]);
