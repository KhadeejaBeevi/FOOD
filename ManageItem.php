<?php
// Start session
session_start();
include('AdminNav.php');
// Include database configuration
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

// Check if the user is logged in as admin/staff
if (!isset($_SESSION['admin_id'])) {
    header('Location: Disable.php');
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

// Handle enable/disable request
if (isset($_GET['toggle_id'])) {
    $toggle_id = intval($_GET['toggle_id']);
    $query = "UPDATE food_items SET status = NOT status WHERE food_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $toggle_id);

    if ($stmt->execute()) {
        $success = "Food item status updated successfully!";
    } else {
        $error = "Error updating the food item status. Please try again.";
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
        .status-enabled {
            color: green;
            font-weight: bold;
        }
        .status-disabled {
            color: red;
            font-weight: bold;
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
                <th>Status</th>
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
                    <td class="<?php echo $row['status'] ? 'status-enabled' : 'status-disabled'; ?>">
                        <?php echo $row['status'] ? 'Available' : 'Not Available'; ?>
                    </td>
                    <td>
                        <a href="EditItem.php?food_id=<?php echo $row['food_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete_id=<?php echo $row['food_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        <a href="?toggle_id=<?php echo $row['food_id']; ?>" class="btn btn-sm <?php echo $row['status'] ? 'btn-secondary' : 'btn-success'; ?>">
                            <?php echo $row['status'] ? 'Not Available' : 'Available'; ?>
                        </a>
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
