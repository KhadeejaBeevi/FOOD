<?php
session_start();
include('UserNav.php');

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "quickbite");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT p.payment_id, p.order_id, p.user_id, p.payment_method, p.payment_status, p.created_at AS payment_date, p.total_price, o.total_price AS order_total
          FROM payments p
          JOIN orders o ON p.order_id = o.order_id
          ORDER BY p.created_at DESC";

$result = $con->query($query);

if (!$result) {
    die("Query failed: " . $con->error); // Output the error message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - QUICKBITE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Order Payment Details</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
                <th>Total Price</th>
                <th>Order Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['payment_id']) ?></td>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td><?= htmlspecialchars($row['payment_status']) ?></td>
                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                    <td>₹<?= htmlspecialchars($row['total_price']) ?></td>
                    <td>₹<?= htmlspecialchars($row['order_total']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
