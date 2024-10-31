<?php
// Start session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['admin'])) {
    header('Location: Welcome.php');
    exit();
} elseif (isset($_SESSION['user'])) {
    header('Location: Welcome.php'); // Redirect to user home page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            
            background-image: url("image/foodd.webp"); /* Add your own image */
            background-size: cover;
            background-position: center;
            height: 450px;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
           text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
        
            
           /*background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;*/
        }
        .welcome-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            margin: 10px;
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>

<div class="welcome-container">
    <h1>Welcome to QUICKBITE</h1>
    <p>Please choose your role to continue:</p>
    <a href="AdminLogin.php" class="btn btn-primary">Admin</a>
    <a href="UserLogin.php" class="btn btn-secondary">User</a>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
