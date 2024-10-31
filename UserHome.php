<?php
// Start session to check if user is logged in
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUICKBITE - Canteen to Room Delivery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS for further styling -->
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
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
        .hero-section {
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
        }
        .hero-section h1 {
            font-size: 4rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.5rem;
            margin-top: 15px;
        }
        .menu-section {
            padding: 50px 0;
            background-color: #fff;
        }
        .menu-section h2 {
            color: #ff5722;
            text-align: center;
            font-weight: bold;
            margin-bottom: 40px;
        }
        .menu-item {
            text-align: center;
            transition: transform 0.3s ease;
        }
        .menu-item:hover {
            transform: scale(1.05);
        }
        .menu-item img {
            width: 100%;
            border-radius: 10px;
        }
        .menu-item h4 {
            margin-top: 15px;
            color: #333;
        }
        .menu-item p {
            color: #777;
        }
        .menu-item .btn {
            background-color: #ff5722;
            color: white;
        }
        .menu-item .btn:hover {
            background-color: #e64a19;
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

<!-- Navigation Bar -->
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

<!-- Hero Section -->
<div class="hero-section">
    <h1>Welcome to QUICKBITE</h1>
    <p>Delicious food delivered straight to your room</p>
    <a href="menu.php" class="btn btn-lg btn-light">Order Now</a>
</div>

<!-- Menu Section -->
<div class="container menu-section">
    <h2>What's On Your Mind</h2>
    <div class="row">
        <!-- Dynamic Menu Items (example) -->
        <div class="col-md-4 menu-item">
            <img src="image\meals.png" alt="Meals">
            <h4>Meals</h4>
            <p>Vegetarian Meals</p>
             <p><strong>80.00/-</strong></p>
             <a href="order.php?item=meals" class="btn btn-primary">Order Now</a>
        </div>
        <div class="col-md-4 menu-item">
            <img src="image/pazhampori.jpg" alt="pazhampori">
            <h4>Pazham Pori</h4>
            <p></p>
            <p><strong>12.00/-</strong></p>
            <a href="order.php?item=pazhampori" class="btn btn-primary">Order Now</a>
        </div>
        <div class="col-md-4 menu-item">
            <img src="image/kanji.jpg" alt="kanji">
            <h4>Kanji</h4>
            <p>Hot Kanji With Chammanthi,Achar and Pappadam</p>
            <p><strong>100.00/-</strong></p>
            <a href="order.php?item=kanji" class="btn btn-primary">Order Now</a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
