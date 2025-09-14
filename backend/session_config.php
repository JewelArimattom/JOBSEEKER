<?php
// Set secure session cookie parameters before starting the session.
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1); // Disallow JavaScript access to the session cookie.
ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // Only send cookie over HTTPS.

// Define a consistent cookie path for the entire application.
// This ensures the session is available everywhere under /careerbridge/.
$cookie_path = '/careerbridge'; 

session_set_cookie_params([
    'lifetime' => 0, // Session lasts until the browser is closed.
    'path' => $cookie_path,
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax' // Helps prevent CSRF attacks.
]);

// Start the session.
session_start();
?>