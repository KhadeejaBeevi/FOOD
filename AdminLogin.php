<?php
// Start session
session_start();



// Database connection
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize error message
$error = '';

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Check if email and password are provided
    if (!empty($email) && !empty($password)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        
        // Verify the user and password
        if ($admin && $admin['password'] === $password) {  // Assuming password is stored in plain text. Ideally, use password_hash() and password_verify().
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['adminname'] = $admin['adminname'];
            $_SESSION['email'] = $admin['email'];
            
            header("Location: AdminHome.php"); // Redirect to user home
            exit;
        } else {
            $error = "Invalid email or password!";
        }
        
        $stmt->close(); // Close statement
    } else {
        $error = "Please fill in both fields!";
    }
}

mysqli_close($con); // Close connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .login-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .login-container .form-group input {
            border-radius: 8px;
        }
        .login-container .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .login-container .btn:hover {
            background-color: #e64a19;
        }
        .login-container .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }
        .login-container p {
            text-align: center;
            margin-top: 15px;
        }
        .login-container p a {
            color: #ff5722;
            text-decoration: none;
        }
        .login-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h3>Login to QUICKBITE</h3>

    <!-- Display error message if exists -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="Adminlogin.php">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-block">Login</button>
    </form>
    
    
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
