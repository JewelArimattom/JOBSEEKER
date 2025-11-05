<?php
require_once 'session_config.php'; 
require_once 'database.php';

function show_error_message($message) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg max-w-md w-full text-center">
        
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-triangle fa-4x"></i>
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mb-2">
            Login Failed
        </h1>

        <p class="text-slate-600 mb-6">
            {$message}
        </p>

        <a href="../frontend/components/signUp.html" class="inline-block bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
            <i class="fas fa-redo-alt mr-2"></i> Try Again
        </a>

    </div>
</body>
</html>
HTML;
}

try {
    if (!isset($servername) || !isset($username) || !isset($password) || !isset($dbname)) {
        throw new Exception("Database configuration not loaded properly. Check database.php");
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception("Email and password are required.");
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Fetch user roles
                $stmt_roles = $conn->prepare(
                    "SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?"
                );
                $stmt_roles->bind_param("i", $user['id']);
                $stmt_roles->execute();
                $roles_result = $stmt_roles->get_result();
                
                $roles = [];
                while ($row = $roles_result->fetch_assoc()) {
                    $roles[] = $row['name'];
                }
                $stmt_roles->close();
                
                // Clear any existing session data
                $_SESSION = array();
                
                // Set new session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['roles'] = $roles;
                $_SESSION['loggedin'] = true;
                $_SESSION['login_time'] = time();
                
                // Force session save
                session_write_close();
                
                // Restart session to ensure data is available
                require_once 'session_config.php';
                
                // Redirect to the home page after successful login
                header("Location: ../index.html");
                exit();

            } else {
                // Incorrect password
                show_error_message("Invalid email or password.");
            }
        } else {
            // User not found
            show_error_message("Invalid email or password.");
        }
        $stmt->close();
    }
    $conn->close();

} catch (Exception $e) {
    // Show error message
    show_error_message("An error occurred during login. Please try again or contact support if the problem persists.");
}
?>
