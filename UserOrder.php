<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Userlogin.php");
    exit();
}

// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Validate order_id parameter
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "Order ID is missing.";
    exit();
}

$orderId = $_GET['order_id'];
$userId = $_SESSION['user_id'];

// Fetch order information
$orderQuery = "SELECT * FROM orders WHERE order_id = '$orderId' AND user_id = '$userId'";
$orderResult = $con->query($orderQuery);

// Check if the order exists
if ($orderResult->num_rows === 0) {
    echo "Order not found.";
    exit();
}

$order = $orderResult->fetch_assoc();

// Fetch ordered items
$itemsQuery = "SELECT order_items.*, food_items.food_name, food_items.price 
               FROM order_items 
               JOIN food_items ON order_items.food_id = food_items.food_id 
               WHERE order_items.order_id = '$orderId'";
$itemsResult = $con->query($itemsQuery);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .order-details-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .order-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item h5 {
            margin: 0;
            color: #333;
        }
        .order-item .quantity, .order-item .price {
            color: #666;
            font-weight: bold;
        }
        .total-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #ff5722;
            text-align: right;
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #ff5722;
            border: none;
        }
        .btn-primary:hover {
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
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ff5722;">
    <a class="navbar-brand" href="#">QUICKBITE</a>
    <div class="navbar-nav ml-auto">
        <a class="nav-link" href="UserHome.php">Home</a>
        <a class="nav-link" href="Menu.php">Menu</a>
        <a class="nav-link" href="Cart.php">Cart</a>
        <a class="nav-link" href="Order.php">Orders</a>
        <a class="nav-link" href="Userlogout.php">Logout</a>
    </div>
</nav>

<div class="order-details-container">
    <h3 class="order-title">Order Details</h3>
    <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
    
    <?php if ($itemsResult && $itemsResult->num_rows > 0): ?>
        <?php while ($item = $itemsResult->fetch_assoc()): ?>
            <div class="order-item">
                <div>
                    <h5><?php echo $item['food_name']; ?></h5>
                    <p class="quantity">Quantity: <?php echo $item['quantity']; ?></p>
                </div>
                <div class="price">
                    ₹<?php echo $item['price'] * $item['quantity']; ?>
                </div>
            </div>
            <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endwhile; ?>
        
        <div class="total-price">
            Total: ₹<?php echo $total; ?>
        </div>
    <?php else: ?>
        <p>No items found for this order.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
