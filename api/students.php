<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';

header('Content-Type: application/json; charset=utf-8');

$conn = get_db_connection();
$search = trim($_GET['search'] ?? '');
$course = trim($_GET['course'] ?? 'all');

$sql = "SELECT id, enrollment_no, full_name, email, mobile, course, year, gender FROM students WHERE 1=1";
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

if ($course !== '' && $course !== 'all') {
    $sql .= " AND course = ?";
    $params[] = $course;
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

echo json_encode([
    'success' => true,
    'count' => count($students),
    'data' => $students
]);
