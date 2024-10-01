<?php
session_start();
include 'db.php'; // Ensure the path to db.php is correct

// Generate CSRF token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the CSRF token is valid
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid CSRF token.";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert the new user using prepared statements
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful, you can redirect or display a message
            header('Location: login.php'); // Redirect to login page
            exit();
        } else {
            // Registration failed, show error message (e.g., if username or email is taken)
            $error_message = "Registration failed. The username or email may already be taken.";
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
    <title>Sign Up</title>
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
            text-align: center; /* Align text to the left */
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
            background-color: #0056b3;
        }

        /* Auth link styling */
        .auth-links {
            display: flex; /* Use flexbox for alignment */
            justify-content: flex-start; /* Align items to the start (left) */
            margin-top: 10px; /* Adjust margin to provide some space above */
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
        <h1>My Blog - Sign Up</h1>
        <div class="auth-links">
            <a href="index.php" class="btn">Back to Home</a>
            <a href="login.php" class="btn">Sign In</a>
        </div>
    </header>

    <div class="container">
        <h2>Create an Account</h2>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> My Blog</p>
    </footer>
</body>
</html>
