<?php
require_once '../session_config.php';
require_once '../database.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['roles']) || !in_array('admin', $_SESSION['roles'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? null;
$fullName = $input['full_name'] ?? null;
$email = $input['email'] ?? null;
$roles = $input['roles'] ?? [];

if (!$userId || !$fullName || !$email) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

try {
    $conn->begin_transaction();
    
    // Update user
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $fullName, $email, $userId);
    $stmt->execute();
    
    // Delete existing roles
    $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Insert new roles
    foreach ($roles as $roleName) {
        $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role_id) 
                                SELECT ?, id FROM roles WHERE name = ?");
        $stmt->bind_param("is", $userId, $roleName);
        $stmt->execute();
    }
    
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
