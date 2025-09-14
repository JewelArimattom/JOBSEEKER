<?php
require_once 'session_config.php';
header('Content-Type: application/json');
require_once 'database.php';

$response = ['success' => false];

try {
    // Authentication & Role Check
    if (!isset($_SESSION['user_id']) || !in_array('employer', $_SESSION['roles'] ?? [])) {
        throw new Exception("Access Denied.");
    }
    $employer_id = (int)$_SESSION['user_id'];

    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($data['job_id']) || empty($data['job_title']) || empty($data['company_name']) || empty($data['location'])) {
        throw new Exception("Required fields (Job Title, Company Name, Location) are missing.");
    }

    // Assign all variables from the form data, handling potential nulls
    $job_id = (int)$data['job_id'];
    $job_title = trim($data['job_title']);
    $company_name = trim($data['company_name']);
    $job_category = trim($data['job_category'] ?? null);
    $location = trim($data['location']);
    $job_type = trim($data['job_type'] ?? null);
    $experience_level = trim($data['experience_level'] ?? null);
    $openings = !empty($data['openings']) ? (int)$data['openings'] : null;
    $application_deadline = !empty($data['application_deadline']) ? trim($data['application_deadline']) : null;
    $salary_min = !empty($data['salary_min']) ? (int)$data['salary_min'] : null;
    $salary_max = !empty($data['salary_max']) ? (int)$data['salary_max'] : null;
    $salary_unit = trim($data['salary_unit'] ?? null);
    $description = trim($data['description'] ?? null);
    $skills = trim($data['skills'] ?? null);
    $recruiter_name = trim($data['recruiter_name'] ?? null);
    $recruiter_email = trim($data['recruiter_email'] ?? null);

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed.");
    }

    // UPDATED: Query now includes all the columns from your table
    $stmt = $conn->prepare("
        UPDATE jobs 
        SET 
            job_title = ?, company_name = ?, job_category = ?, location = ?, job_type = ?, 
            experience_level = ?, openings = ?, application_deadline = ?, salary_min = ?, 
            salary_max = ?, salary_unit = ?, description = ?, skills = ?, 
            recruiter_name = ?, recruiter_email = ?
        WHERE 
            id = ? AND employer_id = ?
    ");
    
    // UPDATED: Bind parameters now matches all columns (s = string, i = integer)
    $stmt->bind_param(
        "sssssisiiisssssii",
        $job_title, $company_name, $job_category, $location, $job_type,
        $experience_level, $openings, $application_deadline, $salary_min,
        $salary_max, $salary_unit, $description, $skills,
        $recruiter_name, $recruiter_email,
        $job_id, $employer_id
    );
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Job updated successfully.';
    } else {
        throw new Exception("Failed to execute update.");
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>