<?php
require_once '../session_config.php';
require_once '../database.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['roles']) || !in_array('admin', $_SESSION['roles'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $sql = "SELECT a.id, a.full_name, a.email, a.status, a.applied_at, j.job_title
            FROM applications a
            LEFT JOIN jobs j ON a.job_id = j.id
            ORDER BY a.applied_at DESC";
    
    $result = $conn->query($sql);
    $applications = [];
    
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
    
    echo json_encode(['success' => true, 'applications' => $applications]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
