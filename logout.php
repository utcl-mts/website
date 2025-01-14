<?php

// Start the session
session_start();

// Destroy the session
session_unset();
session_destroy();

// Clear session cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to the login page
header("Location: index.html");
exit();