<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset the session variable for the user ID
    unset($_SESSION['user_id']); 
    
    // Destroy the entire session
    session_destroy(); 
}

// Redirect to the login page
header('Location: login.php'); // Change this to your desired page
exit(); // Ensure no further code is executed
?>
