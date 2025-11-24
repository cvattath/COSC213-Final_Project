<?php

    session_start();

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function logout() {
    session_start();
    session_unset();    // Remove all session variables
    session_destroy();  // Destroy the session
    header("Location: login.php");
    exit();
}
?>