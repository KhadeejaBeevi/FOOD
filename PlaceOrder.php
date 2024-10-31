<?php
session_start();
include('UserNav.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: Userlogin.php");
    exit();
}
// Connect to the database
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables for messages
$order_success = '';
$order_error = '';

// Ensure the cart is initialized
echo "<pre>";
print_r($_SESSION['cart_id']);
echo "</pre>";

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the cart exists and is an array
    if (is_array($_SESSION['cart_id']) && count($_SESSION['cart_id']) > 0) {
        $cart = $_SESSION ['cart_id'];
        $payment_method = $_POST['payment_method'];
        $delivery_address = $_POST['delivery_address'];
        $contact = $_POST['contact'];

        // Calculate total price
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Debugging: Print total price
        echo "Total Price: " . $total_price;

        // Insert order details into the database
        $query = "INSERT INTO orders (user_id, total_price, delivery_address, payment_method, contact_number, order_status) VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $con->prepare($query);
        if ($stmt) {
            $user_id = $_SESSION['user_id'];
            $stmt->bind_param("idsss", $user_id, $total_price, $delivery_address, $payment_method, $contact);
            
            if ($stmt->execute()) {
                $order_id = $stmt->insert_id;
                
                // Insert each item in the cart into the order_items table
                foreach ($cart as $item) {
                    $item_query = "INSERT INTO orders (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
                    $item_stmt = $con->prepare($item_query);
                    if ($item_stmt) {
                        $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                        $item_stmt->execute();
                        $item_stmt->close();
                    }
                }

                $order_success = "Order placed successfully!";
                unset($_SESSION['cart_id']); // Clear cart session
            } else {
                $order_error = "Failed to place order: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $order_error = "Failed to prepare the statement: " . mysqli_error($con);
        }
    } else {
        $order_error = "Your cart is empty. Please add items before placing an order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f9f9f9; font-family: Arial, sans-serif; }
        .order-container { max-width: 1100px; margin: 50px auto; padding: 20px; background-color: white; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        .order-title { text-align: center; color: #ff5722; margin-bottom: 20px; font-weight: bold; }
        .form-group label { font-weight: bold; }
        .btn-primary { background-color: #ff5722; border: none; }
        .btn-primary:hover { background-color: #e64a19; }
        footer { background-color: #ff5722; color: white; text-align: center; padding: 15px 0; margin-top: 50px; }
    </style>
</head>
<body>

<div class="order-container">
    <h3 class="order-title">Place Order</h3>
    
    <?php if ($order_success): ?>
        <p class='alert alert-success'><?= $order_success ?></p>
    <?php endif; ?>
    
    <?php if ($order_error): ?>
        <p class='alert alert-danger'><?= $order_error ?></p>
    <?php endif; ?>

    <?php if (count($_SESSION['cart_id']) > 0): ?>
        <h4>Your Cart:</h4>
        <ul class="list-group">
            <?php foreach ($_SESSION['cart_id'] as $item): ?>
                <li class="list-group-item">
                    Item ID: <?= htmlspecialchars($item['id']) ?> | Quantity: <?= htmlspecialchars($item['quantity']) ?> | Price: ₹<?= number_format($item['price'], 2) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <form method="POST" class="container mt-4">
            <div class="form-group">
                <label for="delivery_address">Delivery Address:</label>
                <input type="text" name="delivery_address" id="delivery_address" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" name="contact" id="contact" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="Cash">Cash on Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="UPI">UPI</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add items before placing an order.</p>
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
