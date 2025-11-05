<?php
// Configure session settings
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1); 
ini_set('session.cookie_secure', 0); // Set to 0 for local development without HTTPS
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

// Dynamic path detection
$project_folder = 'CareerBridge';
$cookie_path = '/'; 

// Set session cookie parameters
session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => $cookie_path,
    'domain' => '',
    'secure' => false, // Set to true in production with HTTPS
    'httponly' => true,
    'samesite' => 'Lax' 
]);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>