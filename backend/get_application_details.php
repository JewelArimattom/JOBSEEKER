<?php
// backend/get_application_details.php

require_once 'session_config.php'; 
header('Content-Type: application/json');
require_once 'database.php';

// --- 1. Security Check: Verify User is Logged In ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// --- 2. Input Validation: Check for Application ID ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid or missing Application ID.']);
    exit;
}

$application_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

try {
    // Assuming database.php provides credentials ($servername, $username, etc.)
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // --- 3. Database Query: Fetch Application Details ---
    $sql = "SELECT 
                a.id AS application_id,
                j.id AS job_id,             
                a.status,
                a.application_date,
                a.resume_path,
                j.job_title AS title,
                j.company_name AS company,
                j.location
            FROM 
                applications a
            JOIN 
                jobs j ON a.job_id = j.id
            WHERE 
                a.id = ? AND a.user_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Database error: Failed to prepare statement.');
    }

    $stmt->bind_param("ii", $application_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $application = $result->fetch_assoc();
        $date = new DateTime($application['application_date']);
        $application['application_date_formatted'] = $date->format('d M Y');

        echo json_encode(['success' => true, 'application' => $application]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'message' => 'Application not found or you do not have permission.']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>