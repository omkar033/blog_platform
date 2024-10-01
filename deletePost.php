<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to delete a post.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];

    // Escape user input to prevent SQL injection
    $post_id = mysqli_real_escape_string($conn, $post_id);

    $query = "DELETE FROM posts WHERE id='$post_id' AND user_id=" . $_SESSION['user_id']; // Ensure the user owns the post

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Post deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
