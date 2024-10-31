<?php
session_start();
$showAlert = false;  
$showError = false;  
$exists = false; 

$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST values
    $username = $_POST['adminname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Create table if it doesn't exist
    $t = "CREATE TABLE IF NOT EXISTS admin (
        admin_id INT AUTO_INCREMENT PRIMARY KEY,
        adminname VARCHAR(100),
        email VARCHAR(100) UNIQUE, 
        password VARCHAR(255)
    )";
    mysqli_query($con, $t);

    // Check if username exists
    $s = "SELECT * FROM admin WHERE adminname=?";
    $stmt = mysqli_prepare($con, $s);
    mysqli_stmt_bind_param($stmt, "s", $adminname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) { // No matching username
        if ($password == $cpassword) {
            // Hash the password and insert into table
            
            $i = "INSERT INTO admin (adminname, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $i);
            mysqli_stmt_bind_param($stmt, "sss", $adminname, $email, $password);
            
            if (mysqli_stmt_execute($stmt)) {
                $showAlert = true;
            } else {
                $showError = "Error: " . mysqli_error($con);
            }
        } else {  
            $showError = "Passwords do not match";  
        }
    } else { 
        $exists = "name not available";  
    }

    mysqli_stmt_close($stmt); // Close statement
}
mysqli_close($con); // Close connection
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .register-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .register-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .register-container .form-group input {
            border-radius: 8px;
        }
        .register-container .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .register-container .btn:hover {
            background-color: #e64a19;
        }
        .register-container .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }
        .register-container .success {
            color: green;
            font-size: 0.9rem;
            text-align: center;
        }
        .register-container p {
            text-align: center;
            margin-top: 15px;
        }
        .register-container p a {
            color: #ff5722;
            text-decoration: none;
        }
        .register-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h3>Create an Account</h3>

    <!-- Display messages -->
    <?php
        if ($showError) {
            echo '<p class="error">' . $showError . '</p>';
        } elseif ($exists) {
            echo '<p class="error">' . $exists . '</p>';
        }
    ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="adminname" id="adminname" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm your password" required>
        </div>
        <button type="submit" class="btn btn-block">Register</button>
    </form>
    
    <p>Already have an account? <a href="AdminLogin.php">Login here</a></p>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
