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

// Fetch cart items for the logged-in user
$userId = $_SESSION['user_id'];
$result = $con->query("SELECT cart.*, food_items.food_name, food_items.price, food_items.image_path 
          FROM cart 
          JOIN food_items ON cart.food_id = food_items.food_id 
          WHERE cart.user_id = $userId");

// Handle item removal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item_id'])) {
    $removeItemId = $_POST['remove_item_id'];
    $deleteStmt = $con->query("DELETE FROM cart WHERE food_id = $removeItemId AND user_id = $userId");
    header("Location: Cart.php");
    exit();
}

// Handle item addition to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $itemPrice = $_POST['item_price'];

    // Check if the item is already in the cart
    $checkQuery = $con->query("SELECT * FROM cart WHERE food_id = $itemId AND user_id = $userId");
    if ($checkQuery->num_rows > 0) {
        // Item already in cart; you can choose to update quantity here if desired
        $con->query("UPDATE cart SET quantity = quantity + 1 WHERE food_id = $itemId AND user_id = $userId");
    } else {
        // Item not in cart; add it
        $insertQuery = $con->query("INSERT INTO cart (user_id, food_id, quantity) VALUES ($userId, $itemId, 1)");
    }

    // Redirect to the cart page
    header("Location: Cart.php");
    exit();
}

// Calculate total
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .cart-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .cart-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item h5 {
            margin: 0;
            color: #333;
        }
        .cart-item img {
            max-width: 100px;
            max-height: 80px;
            margin-right: 20px;
            border-radius: 5px;
        }
        .cart-item .price {
            color: #ff5722;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ff5722;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e64a19;
        }
        .total-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #ff5722;
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

<div class="cart-container">
    <h3 class="cart-title">Your Cart</h3>
    
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($item = mysqli_fetch_array($result)): ?>
            <?php $total += $item['price'] * $item['quantity']; ?>
            <div class="cart-item">
                <div>
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['food_name']; ?>">
                </div>
                <div>
                    <h5><?php echo $item['food_name']; ?></h5>
                    <p>Price: ₹<?php echo $item['price']; ?></p>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                </div>
                <div>
                    <form method="POST" action="Cart.php">
                        <input type="hidden" name="remove_item_id" value="<?php echo $item['food_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
        

        <div class="total-price">
            Total: ₹<?php echo $total; ?>
        </div>
        <div class="text-right mt-3">
        <a href="Menu.php" class ="btn btn-primary">Continue Shopping</a>
            <a href="PlaceOrder.php" class="btn btn-primary">Place Order</a>
           
        </div>
    <?php else: ?>
        <p>Your cart is empty. Go to the <a href="Menu.php">Menu</a> to add items.</p>
    <?php endif; ?>
</div>
<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
mysqli_close($con); // Close database connection
?>
