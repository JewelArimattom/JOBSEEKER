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
    $stats = [];
    
    // Get total users
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $stats['users'] = $result->fetch_assoc()['count'];
    
    // Get total jobs
    $result = $conn->query("SELECT COUNT(*) as count FROM jobs");
    $stats['jobs'] = $result->fetch_assoc()['count'];
    
    // Get total applications
    $result = $conn->query("SELECT COUNT(*) as count FROM applications");
    $stats['applications'] = $result->fetch_assoc()['count'];
    
    // Get total messages
    $result = $conn->query("SELECT COUNT(*) as count FROM messages");
    $stats['messages'] = $result->fetch_assoc()['count'];
    
    echo json_encode(['success' => true] + $stats);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
