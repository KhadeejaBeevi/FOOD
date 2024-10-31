<?php
// Start session
session_start();
include('AdminNav.php');
// Check if user is logged in and has admin access
if (!isset($_SESSION['admin_id'])) {
    header("Location: AdminLogin.php");
    exit();
}

// Database connection
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize error and success messages
$error = '';
$success = '';

// Check if the food item ID is provided
if (isset($_GET['food_id'])) {
    $foodId = intval($_GET['food_id']); // Ensure it's an integer

    // Fetch current food item details
    $stmt = $con->prepare("SELECT * FROM food_items WHERE food_id = ?");
    $stmt->bind_param("i", $foodId);
    $stmt->execute();
    $result = $stmt->get_result();
    $foodItem = $result->fetch_assoc();

    // If food item not found, show an error
    if (!$foodItem) {
        $error = "Food item not found!";
    }

    // Update food item details
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $foodName = $_POST['food_name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        $imagePath = $_POST['image_path']; // Assume image path is provided as a text field

        if (!empty($foodName) && !empty($price) && !empty($description) && !empty($category) && !empty($status) && !empty($imagePath)) {
            // Update query
            $updateStmt = $con->prepare("UPDATE food_items SET food_name = ?, price = ?, description = ?, category = ?, status = ?, image_path = ? WHERE food_id = ?");
            $updateStmt->bind_param("sdssssi", $foodName, $price, $description, $category, $status, $imagePath, $foodId);

            if ($updateStmt->execute()) {
                $success = "Food item updated successfully!";
                header("Location: AdminMenu.php"); // Redirect to food items list page
                exit();
            } else {
                $error = "Failed to update food item.";
            }
        } else {
            $error = "Please fill in all fields!";
        }
    }

    $stmt->close(); // Close the prepared statement
} else {
    $error = "No food item selected.";
}

mysqli_close($con); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Food Item - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .edit-container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .edit-container h3 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .edit-container .btn {
            background-color: #ff5722;
            color: white;
            font-weight: bold;
        }
        .edit-container .btn:hover {
            background-color: #e64a19;
        }
        .message {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }
        .success-message {
            color: green;
            font-size: 0.9rem;
            text-align: center;
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
<div class="edit-container">
    <h3>Edit Food Item</h3>

    <!-- Display messages if they exist -->
    <?php if ($error): ?>
        <div class="message"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($success): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="EditItem.php?food_id=<?php echo $foodId; ?>">
        <div class="form-group">
            <label for="food_name">Food Name</label>
            <input type="text" name="food_name" id="food_name" class="form-control" value="<?php echo htmlspecialchars($foodItem['food_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price (₹)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo htmlspecialchars($foodItem['price']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" required><?php echo htmlspecialchars($foodItem['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <textarea name="category" id="category" class="form-control" rows="2" required><?php echo htmlspecialchars($foodItem['category']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <textarea name="status" id="status" class="form-control" rows="2" required><?php echo htmlspecialchars($foodItem['status']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image_path">Image Path (URL)</label>
            <input type="text" name="image_path" id="image_path" class="form-control" value="<?php echo htmlspecialchars($foodItem['image_path']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-block">Update Food Item</button>
    </form>
</div>
<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
