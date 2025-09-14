<?php

// Configure session settings before starting the session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // Secure in HTTPS

// Set session cookie path to the root of your application
$path = dirname(dirname($_SERVER['PHP_SELF']));
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $path,
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true
]);

session_start();
header('Content-Type: application/json');
require_once 'database.php'; // Assuming this contains your DB credentials

// Response structure
$response = [
    'success' => false,
    'user' => null,
    'applications' => []
];

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not authenticated.");
    }
    $user_id = (int)$_SESSION['user_id'];

    // Assuming your database.php provides $servername, $username, $password, $dbname
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // --- GET USER DETAILS ---
    $stmt_user = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    if ($user_result->num_rows > 0) {
        $response['user'] = $user_result->fetch_assoc();
    }
    $stmt_user->close();

    // --- GET APPLIED JOBS ---
    // The query is corrected here to include the application ID
    $stmt_apps = $conn->prepare("
        SELECT 
            a.id AS application_id, -- <<< THE CRITICAL FIX IS HERE
            j.job_title AS title,
            j.company_name AS company,
            j.location,
            j.company_logo_path AS logo,
            a.status,
            DATE_FORMAT(a.application_date, '%d %b %Y') as application_date_formatted
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE a.user_id = ?
        ORDER BY a.application_date DESC
    ");
    $stmt_apps->bind_param("i", $user_id);
    $stmt_apps->execute();
    $apps_result = $stmt_apps->get_result();
    
    while ($row = $apps_result->fetch_assoc()) {
        $response['applications'][] = $row;
    }
    $stmt_apps->close();

    $response['success'] = true;
    $conn->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>