<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Check if the post ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php'); // Redirect to the index page if no ID is provided
    exit();
}

// Get the post ID from the URL and sanitize it
$post_id = intval($_GET['id']);

// Fetch the post from the database
$query = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Post not found.";
    exit();
}

$post = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-image: url('in.webp'); /* Replace with your image */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        /* Semi-transparent header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            color: #333;
            padding: 20px;
            text-align: left;
            z-index: 1000;
        }

        /* Container styling */
        .container {
            max-width: 800px;
            margin: 150px auto; /* Margin for the header */
            padding: 20px;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left;
        }

        h1 {
            margin-top: 0;
        }

        .post-meta {
            color: #555;
            margin-bottom: 20px;
        }

        /* Back to Blog button */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: rgba(0, 123, 255, 1); /* Full opacity on hover */
        }

        /* Footer styling */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            color: #333;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Semi-transparent Header -->
    <header>
        <h1>My Blog</h1>
    </header>

    <!-- Post Content -->
    <div class="container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="post-meta">Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
        <div>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        </div>
        <a href="index.php" class="back-link">Back to Blog</a>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y'); ?> My Blog</p>
    </footer>

</body>
</html>
