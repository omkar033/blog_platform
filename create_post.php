<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';

    // Check if title and content are not empty
    if (empty($title) || empty($content)) {
        $error_message = "Both title and content are required.";
    } else {
        // Prepare SQL query to insert the new post
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, tags) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $user_id, $title, $content, $tags);
        
        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the dashboard with a success message
            $_SESSION['success_message'] = "Post created successfully!";
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Error creating post: " . $stmt->error;
        }

        $stmt->close(); // Close the prepared statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('cp.webp'); /* Update with your image path */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Slightly more transparent */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group textarea {
            height: 150px;
            resize: none;
        }

        .btn-submit {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .message,
        .error-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Blog Post</h2>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" required></textarea>
            </div>
            <div class="form-group">
                <label for="tags">Tags (optional):</label>
                <input type="text" name="tags" id="tags" placeholder="Separate tags with commas">
            </div>
            <button type="submit" class="btn-submit">Create Post</button>
        </form>
    </div>
</body>
</html>
