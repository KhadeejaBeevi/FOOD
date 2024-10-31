<?php
// Start session
session_start();
include('UserNav.php');
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

// Fetch menu items from the database
$query = "SELECT * FROM food_items";
$result = $con->query($query);

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = []; // Initialize as an empty array
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_status = $_POST['item_status'];
    $item_description = $_POST['item_Description'];
    $item_quantity = 1; // Default quantity

    // Check if the item is already in the cart
    $found = false;
    foreach ($_SESSION['cart_id'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] += $item_quantity; // Increase quantity if item already exists
            $found = true;
            break;
        }
    }

    // If the item is not found, add it to the cart
    if (!$found) {
        $_SESSION['cart_id'][] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'status' => $item_status,
            'description' => $item_description,
            'quantity' => $item_quantity
        ];
    }

    // Redirect to the menu page
    header("Location: Menu.php");
    exit();
}
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
                    Description: <?php echo $item['description']; ?>
                </div>
                <div>
                    Status: <?php echo $item['status'] == 0 ? "Unavailable" : "Available"; ?>
                </div>
                <div>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['food_id']); ?>">
                        <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($item['food_name']); ?>">
                        <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($item['price']); ?>">
                        <input type="hidden" name="item_status" value="<?php echo htmlspecialchars($item['status']); ?>">
                        <input type="hidden" name="item_Description" value="<?php echo htmlspecialchars($item['description']); ?>">
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
