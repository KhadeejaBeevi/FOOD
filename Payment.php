<?php
// Start session
session_start();
include("UserNav.php");
// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed";
}

// Initialize variables
$error = '';
$success = '';

// Check if order_id is set
if (!isset($_SESSION['order_id'])) {
    $error = "No order found. Please place an order before proceeding.";
} else {
    $order_id = $_SESSION['order_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process payment
        $payment_method = $_POST['payment_method'];
        
        // Here you can handle the payment processing logic
        // This is just a simulation of success
        $success = "Payment for order #$order_id has been successfully processed using $payment_method.";
        unset($_SESSION['order_id']); // Clear order ID after processing
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
            font-family: Arial, sans-serif;
        }
        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .payment-title {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
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

<div class="payment-container">
    <h3 class="payment-title">Payment Page</h3>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php else: ?>
        <form method="POST" action="Payment.php">
            <div class="form-group">
                <label for="payment_method">Choose Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="">Select</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Net Banking">Net Banking</option>
                    <option value="UPI">UPI</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
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
