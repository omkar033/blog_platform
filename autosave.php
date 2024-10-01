<?php
session_start();
include('db.php'); // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to save drafts.']);
    exit();
}

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    // Check if title or content is empty
    if (empty($title) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Title and content cannot be empty.']);
        exit();
    }

    // Escape user input to prevent SQL injection
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);

    // Save draft to the database
    $sql = "INSERT INTO drafts (user_id, title, content) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE title=?, content=?";

    $stmt = $conn->prepare($sql);

    // Check if the statement was successfully prepared
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
        exit();
    }

    // Bind parameters and execute statement
    $stmt->bind_param("issss", $userId, $title, $content, $title, $content);
    $stmt->execute();

    // Check if the draft was inserted/updated
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Draft saved successfully']);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No changes made to the draft.']);
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>
