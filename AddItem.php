<?php
session_start();
include('AdminNav.php');
$con = mysqli_connect("localhost", "root", "", "quickbite");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if admin is logged in
// Uncomment if admin authentication is required
 if (!isset($_SESSION['admin_id'])) {
     header('Location: AdminLogin.php');
    exit();
 }

$c ="CREATE TABLE IF NOT EXISTS food_items (
    food_id INT AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255),
    status VARCHAR(100))";
 mysqli_query($con, $c);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $price = $_POST['price'];
    $status=$_POST['status'];
    $category = $_POST['category'];
    $target_dir = "image/";
    $item_image = basename($_FILES["item_image"]["name"]);
    $target_file = $target_dir . $item_image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    // Check if image file is valid
    $check = getimagesize($_FILES["item_image"]["tmp_name"]);
    if ($check !== false) {
        // Upload image
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            // Prepare and bind
            $stmt = $con->prepare("INSERT INTO food_items (food_name, category, description, price, image_path,status) VALUES (?, ?, ?, ?, ?,?)");
            $stmt->bind_param("sssdss", $item_name, $category, $item_description, $price, $target_file,$status);

            if ($stmt->execute()) {
                echo "New item added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "File is not an image.";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
       

        footer {
            background-color: #ff5722;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #ff5722;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Food Item</h2>
    <form action="AddItem.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" class="form-control" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" required>
        </div>
        <div class="form-group">
            <label for="item_description">Item Description</label>
            <textarea class="form-control" id="item_description" name="item_description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price (₹)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="item_image">Item Image</label>
            <input type="file" class="form-control-file" id="item_image" name="item_image" required>
        </div>
        <div class="form-group">
            <label for="item_image">Status</label>
            <input type="text" class="form-control-file" id="status" name="status" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</div>
<footer>
    <p>&copy; 2024 QUICKBITE | Fast. Delicious. Delivered.</p>
</footer>
</body>
</html>
