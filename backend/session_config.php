<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1); 
ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); 


$cookie_path = '/careerbridge'; 

session_set_cookie_params([
    'lifetime' => 0, 
    'path' => $cookie_path,
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax' 
]);

session_start();
?>