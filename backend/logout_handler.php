<?php
// Start session to access it
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the homepage after logout
<<<<<<< HEAD
header("Location: /frontend/index.html");
=======
header("Location: ../index.html");
>>>>>>> 27563df3330c0a314502bac4c079e3f72fc17b54
exit;
?>