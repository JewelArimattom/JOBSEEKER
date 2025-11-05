<?php
session_start();
header('Content-Type: application/json');
<<<<<<< HEAD

// --- DATABASE CONFIGURATION ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "careerbridge";

// Default response structure
=======
require_once 'database.php';

>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
$response = [
    'success' => false,
    'user' => null,
    'applications' => [],
    'message' => ''
];

try {
<<<<<<< HEAD
    // Check for user authentication
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not authenticated. Please log in.");
    }
    $user_id = (int)$_SESSION['user_id'];

<<<<<<< HEAD
    // Establish database connection
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

<<<<<<< HEAD
    // --- Get User Details ---
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    $stmt_user = $conn->prepare("SELECT id, full_name, email FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    if ($user_result->num_rows > 0) {
        $response['user'] = $user_result->fetch_assoc();
    } else {
        throw new Exception("User profile not found.");
    }
    $stmt_user->close();

<<<<<<< HEAD
    // --- Get Applied Jobs (Corrected Query) ---
    // This query now uses the exact column names required by the HTML front-end.
=======
    
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    $stmt_apps = $conn->prepare("
        SELECT 
            j.job_title,
            j.company_name,
            j.location,
            a.status,
            DATE_FORMAT(a.application_date, '%d %b %Y') as applied_date_formatted
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
<<<<<<< HEAD
    // If any error occurs, capture the message
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    http_response_code(400);
    $response['message'] = $e->getMessage();
}

<<<<<<< HEAD
// Send the final JSON response
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
echo json_encode($response);
?>
