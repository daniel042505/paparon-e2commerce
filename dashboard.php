<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\Checkout;

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header('Location: login.php');
    exit();
}

$orders = new Checkout();

?>

<div class="container my-5">
    <h2>Recent Orders</h2>
    <p>Here are the most recent orders made on the site:</p>
    <a href="my-account.php" class="btn btn-primary mb-3">View My Profile</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($orders->getRecentOrders(10) as $order) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($order['id']) . '</td>';
                echo '<td>' . htmlspecialchars($order['user_name']) . '</td>';
                echo '<td>' . htmlspecialchars($order['product_name']) . '</td>';
                echo '<td>' . htmlspecialchars($order['quantity']) . '</td>';
                echo '<td>' . htmlspecialchars($order['total_price']) . '</td>';
                echo '<td>' . htmlspecialchars($order['order_date']) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php template('footer.php'); ?>