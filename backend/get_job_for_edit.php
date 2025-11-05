<?php
require_once 'session_config.php';
header('Content-Type: application/json');
require_once 'database.php';

$response = ['success' => false];

try {
    if (!isset($_SESSION['user_id']) || !in_array('employer', $_SESSION['roles'] ?? [])) {
        throw new Exception("Access Denied.");
    }
    $employer_id = (int)$_SESSION['user_id'];

    if (!isset($_GET['job_id'])) {
        throw new Exception("Job ID is missing.");
    }
    $job_id = (int)$_GET['job_id'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed.");
    }

    $stmt = $conn->prepare("
        SELECT 
            job_title, job_category, location, job_type, experience_level, 
            openings, application_deadline, salary_min, salary_max, salary_unit, 
            description, skills, company_name, company_website, 
            recruiter_name, recruiter_email
        FROM jobs 
        WHERE id = ? AND employer_id = ?
    ");
    $stmt->bind_param("ii", $job_id, $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $response['job'] = $result->fetch_assoc();
        $response['success'] = true;
    } else {
        throw new Exception("Job not found or you do not have permission to edit it.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>