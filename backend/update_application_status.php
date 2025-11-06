<?php
session_start();
header('Content-Type: application/json');
require_once 'database.php';

// Check if user is logged in as employer
if (!isset($_SESSION['user_id']) || !in_array('employer', $_SESSION['roles'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied. You must be logged in as an employer.']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['application_id']) || !isset($input['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Application ID and status are required.']);
    exit;
}

$application_id = filter_var($input['application_id'], FILTER_SANITIZE_NUMBER_INT);
$status = filter_var($input['status'], FILTER_SANITIZE_STRING);
$employer_id = $_SESSION['user_id'];

// Validate status value
$valid_statuses = ['Applied', 'Reviewed', 'Shortlisted', 'Interview Scheduled', 'Rejected', 'Accepted'];
if (!in_array($status, $valid_statuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

try {
    // Verify that the application belongs to a job posted by this employer
    $verify_stmt = $conn->prepare(
        "SELECT a.id, a.user_id, j.job_title 
         FROM applications a
         JOIN jobs j ON a.job_id = j.id
         WHERE a.id = ? AND j.employer_id = ?"
    );
    $verify_stmt->bind_param("ii", $application_id, $employer_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Application not found or you do not have permission to update it.']);
        exit;
    }

    $app_data = $verify_result->fetch_assoc();
    $verify_stmt->close();

    // Update the application status
    $update_stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $status, $application_id);
    
    if ($update_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Application status updated successfully.',
            'new_status' => $status
        ]);
    } else {
        throw new Exception('Failed to update application status.');
    }

    $update_stmt->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} finally {
    if ($conn) {
        $conn->close();
    }
}
?>
