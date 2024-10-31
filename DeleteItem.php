<?php
// Start session
session_start();

// Include database configuration
$con=mysqli_connect("localhost","root","","quickbite");
if(!$con)
{
echo"not connected";
}

// Check if the user is logged in as admin/staff (add your authentication logic)
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$error = '';
$success = '';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM food_items WHERE food_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        $success = "Food item deleted successfully!";
    } else {
        $error = "Error deleting the food item. Please try again.";
    }
}

// Fetch all food items
$query = "SELECT * FROM food_items";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Items - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .manage-food-container {
            max-width: 900px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .manage-food-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
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
                <a class="nav-link" href="AdminHome.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ViewUser.php">User Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="AddItem.php">Add Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="DeleteItem.php">Delete Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ManageItem.php">Manage Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ViewOrder.php">View Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ViewFeedback.php">View Feedback</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ViewPayment.php">View Payment</a>
            </li>
            <?php if (!isset($_SESSION['admin_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Adminlogin.php">Login</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="Adminlogout.php">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="manage-food-container">
    <h3>Manage Food Items</h3>

    <!-- Error message -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Food ID</th>
                <th>Food Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price (₹)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['food_id']; ?></td>
                    <td><?php echo $row['food_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="edit_food_item.php?id=<?php echo $row['food_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete_id=<?php echo $row['food_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
