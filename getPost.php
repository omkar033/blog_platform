<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $query = "SELECT * FROM posts WHERE id='$post_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $post = mysqli_fetch_assoc($result);
        echo json_encode($post);
    } else {
        echo "Error fetching post: " . mysqli_error($conn);
    }
}
?>
