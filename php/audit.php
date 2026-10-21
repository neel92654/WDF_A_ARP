<?php
require_once __DIR__ . '/db.php';

function record_audit_action($conn, $userId, $actionName, $entityType, $entityId, $details = '') {
    $stmt = mysqli_prepare($conn, "INSERT INTO audit_logs (user_id, action_name, entity_type, entity_id, details) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $uId = $userId !== null ? (int)$userId : null;
        $eId = $entityId !== null ? (int)$entityId : null;
        mysqli_stmt_bind_param($stmt, "issis", $uId, $actionName, $entityType, $eId, $details);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
