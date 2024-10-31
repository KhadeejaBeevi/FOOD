<html>
    <head>
        <style>
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
                <a class="nav-link" href="ManageItem.php">Manage Item</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="OrderDetails.php">View Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="OrderStatusChange.php">Update Status</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="ViewPayment.php">View Payment</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Viewfeedback.php">View Feedback</a>
            </li>
            <?php if (!isset($_SESSION['admin_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="AdminLogin.php">Login</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="AdminLogout.php">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>



