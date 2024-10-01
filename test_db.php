<?php
include 'db.php'; // Adjust the path as necessary
if ($conn) {
    echo "Connected to the database successfully!";
} else {
    echo "Failed to connect to the database.";
}

