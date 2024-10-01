<?php
session_start();
include 'db.php'; // Include your database connection

// Generate CSRF token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the CSRF token is valid
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid CSRF token.";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use prepared statements for enhanced security
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                header('Location: dashboard.php'); // Redirect to dashboard
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('signin.webp'); /* Update with your image path */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            color: #333;
            padding: 20px;
            text-align: center; 
            z-index: 1000; /* Ensure it is above other content */
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            color: #333;
            padding: 20px;
            text-align: center;
            z-index: 1000; /* Ensure it is above other content */
        }

        .container {
            max-width: 800px;
            margin: 130px auto; /* Margin to account for fixed header */
            padding: 40px;
            background: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Adjust shadow for better visibility */
            border-radius: 8px;
            text-align: center;
            position: relative; /* Set position relative for proper layout */
            z-index: 10; /* Ensure the container is above other elements */
        }

        /* Error message styling */
        .error {
            color: red;
            margin-bottom: 15px;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Button styling */
        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: yellowgreen;
        }

        /* Auth link styling */
        .auth-links {
            display: flex; /* Use flexbox for alignment */
            justify-content: flex-start; /* Align items to the start (left) */
            margin-top: 15px; /* Adjust margin to provide some space above */
        }

        .auth-links .btn {
            padding: 8px 16px;
            background-color: #007bff; /* Button background color */
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px; /* Space between buttons */
        }

        .auth-links .btn:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        /* Style for links */
        .auth-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px; /* Space between links */
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Blog - Login</h1>
        <div class="auth-links">
            <a href="index.php" class="btn">Back to Home</a>
            <a href="signup.php" class="btn">Sign Up</a>
        </div>
    </header>

    <div class="container">
        <h2>Login to Your Account</h2>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> My Blog</p>
    </footer>
</body>
</html>
