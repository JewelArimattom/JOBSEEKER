<?php
// Include session configuration
require_once 'session_config.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Access-Control-Allow-Credentials: true');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in JSON response

try {
    // Check all required session variables
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true &&
        isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
        
        // Return full user state
        echo json_encode([
            'success' => true,
            'loggedin' => true,
            'user_id' => $_SESSION['user_id'],
            'full_name' => $_SESSION['full_name'],
            'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '',
            'roles' => isset($_SESSION['roles']) ? $_SESSION['roles'] : []
        ]);
    } else {
        // Return not logged in state
        echo json_encode([
            'success' => true,
            'loggedin' => false
        ]);
    }
} catch (Exception $e) {
    // Return error state
    echo json_encode([
        'success' => false,
        'loggedin' => false,
        'error' => $e->getMessage()
    ]);
}
?>