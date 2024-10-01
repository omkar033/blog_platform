<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Check if the connection was established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Fetch posts from the database
$query = "SELECT * FROM posts WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY created_at DESC"; // Fetch latest posts first
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Blog</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include external CSS -->
    <style>
        /* Global background image for the entire page */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('bgdash.jpeg'); /* Replace with your image path */
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Keeps the background image fixed when scrolling */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Make header and buttons fixed at the top with transparency */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Transparent black background */
            text-align: center;
            color: white;
            z-index: 1000; /* Make sure it stays above other elements */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Styling for navigation buttons */
        .button-container {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .button-container a {
            padding: 10px 20px;
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent button color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: rgba(0, 123, 255, 1); /* Solid color on hover */
        }

        .btn-logout {
            background-color: rgba(255, 75, 92, 0.7);
        }

        .btn-logout:hover {
            background-color: rgba(255, 75, 92, 1);
        }

        /* Styling for the blog posts section with a visible container */
        .container {
            margin: 170px auto 0; /* Offset for the fixed header */
            padding: 20px;
            width: 90%; /* Adjusted width */
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.5); /* Light semi-transparent background */
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Blog posts styling with semi-transparent background */
        .post {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light semi-transparent background */
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .post h2 {
            margin-top: 0;
        }

        .post small {
            display: block;
            margin-top: 5px;
            color: #555;
        }

        /* Edit and Delete buttons */
        .post-options {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .post-options a,
        .post-options .btn-delete {
            margin-left: 10px;
            padding: 5px 10px;
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent blue */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .post-options a:hover,
        .post-options .btn-delete:hover {
            background-color: rgba(0, 123, 255, 1); /* Solid blue on hover */
        }

        .post-options .btn-delete {
            background-color: rgba(255, 75, 92, 0.7); /* Semi-transparent red for Delete */
        }

        .post-options .btn-delete:hover {
            background-color: rgba(255, 75, 92, 1);
        }

        /* Footer styling, fixed at the bottom with transparency */
        footer {
            text-align: center;
            color: white;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent footer */
            padding: 10px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Welcome to Your Dashboard</h1>
        <div class="button-container">
            <a href="create_post.php">Create New Post</a>
            <a href="index.php">View All Posts</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <div class="container">
        <h2>Your Blog Posts</h2>
        
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="post" id="post-<?php echo $row['id']; ?>">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <small>Tags: <?php echo htmlspecialchars($row['tags']); ?></small>
                    <small>Posted on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>

                    <!-- Post options for Edit/Delete -->
                    <div class="post-options">
                        <a href="editPost.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <button class="btn-delete" onclick="deletePost(<?php echo $row['id']; ?>)">Delete</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available. <a href="create_post.php">Create your first post</a>!</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> My Blog. All rights reserved.</p>
    </footer>

    <script>
    function deletePost(postId) {
        if (confirm('Are you sure you want to delete this post?')) {
            fetch('deletePost.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    document.getElementById('post-' + postId).remove(); // Remove post from DOM
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the post.');
            });
        }
    }
    </script>
</body>
</html>
