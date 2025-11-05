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
    $sql = "SELECT id, job_title, company_name, location, posted_at, job_type, experience_level
            FROM jobs
            ORDER BY posted_at DESC";
    
    $result = $conn->query($sql);
    $jobs = [];
    
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    
    echo json_encode(['success' => true, 'jobs' => $jobs]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
