<?php
// Include your DB connection
include('db.php');

// Fetch search query if present
$searchQuery = $_GET['search'] ?? '';

// Modify query to include search functionality
$query = "SELECT * FROM posts WHERE title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%' OR tags LIKE '%$searchQuery%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
    <title>My Blog</title>
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

        /* Header styling */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Header h1 */
        header h1 {
            margin: 0;
            font-size: 1.5em;
        }

        /* Auth Links Styling */
        .auth-links a {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent buttons */
            color: white;
            margin-left: 10px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .auth-links a:hover {
            background-color: rgba(0, 123, 255, 1); /* Full opacity on hover */
        }

        /* Container styling */
        .container {
            margin: 150px auto 0; /* Margin for the fixed header */
            padding: 20px;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%; /* Adjust as per your layout */
        }

        /* Search Bar Styling */
        form input[type="text"] {
            width: 500px; /* Increased width for the search bar */
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Blog post styling */
        .post {
            margin-bottom: 20px;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .post-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .post-meta {
            color: #555;
            margin-bottom: 10px;
        }

        .read-more {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        /* Footer styling */
        footer {
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            color: #333;
            padding: 20px;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>
    <!-- Header Section with Auth Links -->
    <header>
        <h1>Welcome to My Blog</h1>
        <div class="auth-links">
            <a href="login.php">Sign In</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </header>

    <!-- Search Form -->
    <div class="container">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Blog Posts Container -->
    <div class="container">
        <h2>Latest Blog Posts</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($post = mysqli_fetch_assoc($result)): ?>
                <div class="post">
                    <h3 class="post-title"><?= htmlspecialchars($post['title']); ?></h3>
                    <p class="post-meta">Posted on <?= date('F j, Y', strtotime($post['created_at'])); ?></p>
                    <p><?= htmlspecialchars(substr($post['content'], 0, 150)); ?>...</p>
                    <a href="post.php?id=<?= $post['id']; ?>" class="read-more">Read More</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No blog posts found.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y'); ?> My Blog</p>
    </footer>
</body>
</html>
