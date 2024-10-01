<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $query = "SELECT * FROM posts WHERE id='$post_id' AND user_id='$user_id'"; // Ensure user owns the post
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result);
    } else {
        echo "Post not found.";
        exit();
    }
} else {
    echo "No post ID provided.";
    exit();
}

// Update the post if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $query = "UPDATE posts SET title='$title', content='$content' WHERE id='$post_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Post updated successfully.";
        header('Location: dashboard.php'); // Redirect to dashboard after update
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link your CSS file -->
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
            justify-content: center;
            align-items: center;
        }

        /* Styling for the form container */
        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%; /* Adjusted width */
            max-width: 600px; /* Max width for larger screens */
            box-sizing: border-box;
            margin-top: 20px; /* Space above the container */
        }

        h2 {
            text-align: center;
            color: #333; /* Dark text for visibility */
        }

        /* Styling for form elements */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333; /* Dark text for visibility */
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Ensure padding is included in the width */
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: rgba(0, 123, 255, 0.9); /* Blue border on focus */
            outline: none; /* Remove default outline */
        }

        button.btn-submit {
            width: 100%;
            padding: 10px;
            background-color: rgba(0, 123, 255, 0.9); /* Semi-transparent blue */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        button.btn-submit:hover {
            background-color: rgba(0, 123, 255, 1); /* Solid blue on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit" class="btn-submit">Update Post</button>
        </form>
    </div>
</body>
</html>
