<?php
<<<<<<< HEAD
// --- DATABASE CONFIGURATION ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "careerbridge"; // Corrected database name

// Set the header to output JSON
header('Content-Type: application/json');

// Set the timezone to India Standard Time
date_default_timezone_set('Asia/Kolkata');

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // SQL query to fetch all jobs, ordered by the newest first
    $sql = "SELECT id, job_title, company_name, location, salary_min, salary_max, salary_unit, job_type, experience_level, posted_at, company_logo_path, skills FROM jobs ORDER BY posted_at DESC";
    
    $result = $conn->query($sql);

    $jobs = [];
    if ($result->num_rows > 0) {
        // Fetch all results into an associative array
        while($row = $result->fetch_assoc()) {
            // Format the data to match the structure expected by the JavaScript frontend
            $formatted_job = [
                'id' => $row['id'],
                'title' => $row['job_title'],
                'company' => $row['company_name'],
                'location' => $row['location'],
                'salary' => '₹' . $row['salary_min'] . ' - ₹' . $row['salary_max'] . ' ' . $row['salary_unit'],
                'type' => $row['job_type'],
                'experience' => $row['experience_level'],
                'posted' => time_ago($row['posted_at']),
                'logo' => '/backend/' . $row['company_logo_path'], // Assuming logos are in backend/uploads
=======
require_once 'database.php';

header('Content-Type: application/json');

try {
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    
    if ($limit < 1) $limit = 10;
    
    $sql = "SELECT id, job_title, company_name, location, salary_min, salary_max, salary_unit, job_type, experience_level, posted_at, company_logo_path, skills FROM jobs ORDER BY posted_at DESC LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $salary = "Not Disclosed";
            if (!empty($row['salary_min']) || !empty($row['salary_max'])) {
                $salary = '₹' . number_format($row['salary_min']) . ' - ₹' . number_format($row['salary_max']) . ' ' . $row['salary_unit'];
            }
            
            $formatted_job = [
                'id' => intval($row['id']),
                'title' => htmlspecialchars($row['job_title']),
                'company' => htmlspecialchars($row['company_name']),
                'location' => htmlspecialchars($row['location']),
                'salary' => $salary,
                'type' => htmlspecialchars($row['job_type']),
                'experience' => htmlspecialchars($row['experience_level']),
                'posted' => time_ago($row['posted_at']),
                'logo' => !empty($row['company_logo_path']) ? '/CareerBridge/backend/' . htmlspecialchars($row['company_logo_path']) : null,
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
                'tags' => !empty($row['skills']) ? array_map('trim', explode(',', $row['skills'])) : []
            ];
            $jobs[] = $formatted_job;
        }
    }

<<<<<<< HEAD
    // Encode the array of jobs into JSON and output it
=======
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
    echo json_encode($jobs);

    $conn->close();

} catch (Exception $e) {
<<<<<<< HEAD
    // If there's an error, return an error message in JSON format
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => $e->getMessage()]);
}

// Helper function to create a "time ago" string
=======
    
    http_response_code(500); 
    echo json_encode(['error' => $e->getMessage()]);
}

>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes      = round($seconds / 60 );
    $hours           = round($seconds / 3600);
    $days          = round($seconds / 86400 );
    $weeks          = round($seconds / 604800);
    $months      = round($seconds / 2629440);
    $years          = round($seconds / 31553280);
    if($seconds <= 60) {
        return "Just Now";
    } else if($minutes <=60) {
        return ($minutes==1) ? "one minute ago" : "$minutes minutes ago";
    } else if($hours <=24) {
        return ($hours==1) ? "an hour ago" : "$hours hrs ago";
    } else if($days <= 7) {
        return ($days==1) ? "yesterday" : "$days days ago";
<<<<<<< HEAD
    } else if($weeks <= 4.3) { // 4.3 weeks in a month
=======
    } else if($weeks <= 4.3) { 
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
        return ($weeks==1) ? "a week ago" : "$weeks weeks ago";
    } else if($months <=12) {
        return ($months==1) ? "a month ago" : "$months months ago";
    } else {
        return ($years==1) ? "one year ago" : "$years years ago";
    }
}
?>
