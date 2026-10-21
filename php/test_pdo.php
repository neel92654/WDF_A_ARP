<?php
require_once __DIR__ . '/db_pdo.php';

$pdo = get_pdo_connection();
$stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE role = :role");
$stmt->execute([':role' => 'admin']);
$admin = $stmt->fetch();

$countStmt = $pdo->query("SELECT COUNT(*) AS total_students FROM students");
$studentCount = $countStmt->fetchColumn();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO Connectivity Test - Student Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">PDO Database Connection Test</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <strong>Success:</strong> Connected to MySQL database via PHP PDO.
                </div>
                <table class="table table-bordered mb-3">
                    <tbody>
                        <tr>
                            <th>Database Name</th>
                            <td>studenthub_db</td>
                        </tr>
                        <tr>
                            <th>PDO Driver</th>
                            <td><?php echo htmlspecialchars($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)); ?></td>
                        </tr>
                        <tr>
                            <th>Server Version</th>
                            <td><?php echo htmlspecialchars($pdo->getAttribute(PDO::ATTR_SERVER_VERSION)); ?></td>
                        </tr>
                        <tr>
                            <th>Total Students Seeded</th>
                            <td><?php echo htmlspecialchars((string)$studentCount); ?></td>
                        </tr>
                        <tr>
                            <th>Admin Account Found</th>
                            <td><?php echo htmlspecialchars($admin['email'] ?? 'Not found'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex gap-2">
                    <a href="../pages/dashboard.html" class="btn btn-outline-primary btn-sm">Home</a>
                    <a href="../pages/login.html" class="btn btn-primary btn-sm">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
