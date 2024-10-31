
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
            <a href="Checkout.php" class="btn btn-primary">Proceed to Checkout</a>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/boots
    </body>
    </html>
    <?php
$stmt->close(); // Close the prepared statement
mysqli_close($con); // Close database connection
?>


...............................menu.................................. 
<?php
// Start session
session_start();

// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Fetch menu items from the database
$query = "SELECT * FROM food_items";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .menu-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .menu-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .menu-item:last-child {
            border-bottom: none;
        }
        .menu-item h5 {
            margin: 0;
            color: #333;
        }
        .menu-item p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        .menu-item .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #ff5722;
        }
        .menu-item .btn {
            background-color: #ff5722;
            color: white;
        }
        .menu-item .btn:hover {
            background-color: #e64a19;
        }
        .menu-item img {
            max-width: 100px;
            max-height: 80px;
            margin-right: 20px;
            border-radius: 5px;
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

<div class="menu-container">
    <h3 class="menu-title">Our Menu</h3>
    
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="menu-item">
                <div>
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['food_name']; ?>">
                </div>
                <div>
                    <h5><?php echo $item['food_name']; ?></h5>
                    <p><?php echo $item['description']; ?></p>
                </div>
                <div class="price">
                    ₹<?php echo $item['price']; ?>
                </div>
                <div>
                    <form method="POST" action="Cart.php">
                    
    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['food_id']); ?>">
    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($item['food_name']); ?>">
    <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($item['price']); ?>">
    <button type="submit" class="btn btn-sm">Add to Cart</button>
</form>

                    
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>  
        <p>No menu items available at the moment.</p>
    <?php endif; ?>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>


..........................................payment......................................  
<?php
// Start session
session_start();

// Include database configuration
$con=mysqli_connect("localhost","root","","quickbite");
if(!$con){
    echo "connection failed";
}

// Initialize variables
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_SESSION['order_id'];
    $payment_method = $_POST['payment_method'];
    $card_number = trim($_POST['card_number']);
    $expiry_date = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);
    
    // Check if a payment method is selected
    if (!empty($payment_method)) {
        if ($payment_method == 'cash') {
            // Handle cash payment
            $query = "UPDATE orders SET payment_method = 'Cash on Delivery', payment_status = 'Pending' WHERE order_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $order_id);
            if ($stmt->execute()) {
                $success = "Your order has been placed successfully!";
                unset($_SESSION['cart']);
            } else {
                $error = "Error processing the payment. Please try again.";
            }
        } else {
            // Validate card details for card payments
            if (!empty($card_number) && !empty($expiry_date) && !empty($cvv)) {
                // Insert payment information into database (In real-world, use payment gateway)
                $query = "UPDATE orders SET payment_method = ?, payment_status = 'Paid' WHERE order_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ss', $payment_method, $order_id);
                if ($stmt->execute()) {
                    $success = "Payment successful! Your order is being processed.";
                    unset($_SESSION['cart']);
                } else {
                    $error = "Error processing the payment. Please try again.";
                }
            } else {
                $error = "Please provide valid card details.";
            }
        }
    } else {
        $error = "Please select a payment method.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .payment-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .payment-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-group input {
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
                    <a class="nav-link" href="Userlogout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="Userlogout.php">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="payment-container">
    <h3>Choose Payment Method</h3>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Payment Form -->
    <form method="POST" action="payment.php">
        <div class="form-group">
            <label for="payment_method">Select Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="">-- Select Payment Method --</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="cash">Cash on Delivery</option>
            </select>
        </div>

        <!-- Card Details -->
        <div id="card-details" style="display: none;">
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Enter card number" maxlength="16">
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date (MM/YY)</label>
                <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY" maxlength="5">
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" class="form-control" placeholder="Enter CVV" maxlength="3">
            </div>
        </div>

        <button type="submit" class="btn btn-block">Submit Payment</button>
    </form>
</div>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript for showing card details based on payment method -->
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        var paymentMethod = this.value;
        var cardDetails = document.getElementById('card-details');
        
        if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
            cardDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
        }
    });
</script>

</body>
</html>
...........................................menu....................................... 
<?php
// Start session
session_start();
include('UserNav.php');
// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Fetch menu items from the database
$query = "SELECT * FROM food_items";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .menu-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .menu-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .menu-item:last-child {
            border-bottom: none;
        }
        .menu-item h5 {
            margin: 0;
            color: #333;
        }
        .menu-item img {
            max-width: 100px;
            max-height: 80px;
            margin-right: 20px;
            border-radius: 5px;
        }
        .menu-item .price {
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


<div class="menu-container">
    <h3 class="menu-title">Menu</h3>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($item = mysqli_fetch_array($result)): ?>
            <div class="menu-item">
                <div>
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['food_name']; ?>">
                </div>
                <div>
                    <h5><?php echo $item['food_name']; ?></h5>
                    <p>Price: ₹<?php echo $item['price']; ?></p>
                    Description:<?php echo $item['description'];?>
        </div>
        <div>
                    Status:<?php if($item['status']==0)
                    {
                        echo "Un Available";
                    }
                    else{
                        echo "Available";
                    }?>
                </div>
                <div>
                    <form method="POST" action="Cart.php">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['food_id']); ?>">
                        <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($item['food_name']); ?>">
                        <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($item['price']); ?>">
                        <input type="hidden" name="item_status" value="<?php echo htmlspecialchars($item['status']);?>">
                        <input type="hidden" name="item_Description" value="<?php echo htmlspecialchars($item['description']);?>">
                        <button type="submit" class="btn btn-primary btn-sm">Add To Cart</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No items available in the menu.</p>
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
