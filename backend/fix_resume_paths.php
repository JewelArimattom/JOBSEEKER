<?php
// Script to update existing resume paths in the database
// Run this once to fix existing applications

require_once 'database.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Updating existing resume paths...\n";

// Get all applications with resume paths
$result = $conn->query("SELECT id, resume_path FROM applications WHERE resume_path LIKE '../frontend/resumes/%'");

$updated = 0;
while ($row = $result->fetch_assoc()) {
    $old_path = $row['resume_path'];
    $filename = basename($old_path); // Extract just the filename

    // Update the record
    $stmt = $conn->prepare("UPDATE applications SET resume_path = ? WHERE id = ?");
    $stmt->bind_param("si", $filename, $row['id']);

    if ($stmt->execute()) {
        $updated++;
        echo "Updated application ID {$row['id']}: {$old_path} -> {$filename}\n";
    } else {
        echo "Failed to update application ID {$row['id']}\n";
    }

    $stmt->close();
}

echo "Updated {$updated} applications.\n";
$conn->close();
echo "Done!\n";
?>