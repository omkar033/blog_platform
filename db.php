<?php
$servername = "localhost";
$username = "root"; // Change if you have a different MySQL username
$password = ""; // Leave empty if you have no password for root
$dbname = "blog_platform"; // Ensure this matches your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
