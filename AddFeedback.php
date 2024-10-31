<?php
// Start session
session_start();

// Include database configuration
// Ensure the connection is successful
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize variables
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  // Ensure this session variable is set
    $order_id = trim($_POST['order_id']);
    $rating = trim($_POST['rating']);
    $comment = trim($_POST['comment']);

    // Validate form input
    if (!empty($order_id) && !empty($rating) && !empty($comment)) {
        // Use a prepared statement to insert feedback into the database
        $stmt = $con->prepare("INSERT INTO feedback (user_id, order_id, rating, comment) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("iiis", $user_id, $order_id, $rating, $comment);
            
            if ($stmt->execute()) {
                $success = "Thank you for your feedback!";
            } else {
                $error = "Error submitting your feedback: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $error = "Failed to prepare the statement: " . $con->error;
        }
    } else {
        $error = "Please fill in all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .feedback-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .feedback-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            border-radius: 8px;
        }
        .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e64a19;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }
        .success {
            color: green;
            font-size: 0.9rem;
            text-align: center;
        }
        .navbar {
            background-color: #ff5722;
        }
        .navbar-brand {
            color: #f9f9f9;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-size: 1.2rem;
        }
        footer {
            background-color: #ff5722;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">QUICKBITE</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="UserHome.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Menu.php">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Cart.php">Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Payment.php">Payment</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="TrackOrder.php">Track Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="AddFeedback.php">Feedback</a>
            </li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Userlogin.php">Login</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="Userlogout.php">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="feedback-container">
    <h3>Submit Your Feedback</h3>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Feedback Form -->
    <form method="POST" action="AddFeedback.php">
        <div class="form-group">
            <label for="order_id">Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control" placeholder="Enter your Order ID" required>
        </div>
        <div class="form-group">
            <label for="rating">Rating (1 to 5)</label>
            <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" placeholder="Rate from 1 to 5" required>
        </div>
        <div class="form-group">
            <label for="comment">Your Feedback</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Enter your feedback" required></textarea>
        </div>
        <button type="submit" class="btn btn-block">Submit Feedback</button>
    </form>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
