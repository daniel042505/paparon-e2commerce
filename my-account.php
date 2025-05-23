<?php
// Enable error reporting for debugging database connection issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'helpers/functions.php';
template('header.php');

use Aries\MiniFrameworkStore\Models\User;
use Aries\MiniFrameworkStore\Models\Checkout;
use Carbon\Carbon;

session_start();

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Initialize a variable for update messages
$update_message = '';
$message_type = ''; // To control alert-success or alert-danger

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $birthdate = $_POST['birthdate'] ?? null;

    $userModel = new User();
    try {
        $updated = $userModel->update([
            'id' => $_SESSION['user']['id'],
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'birthdate' => $birthdate ? Carbon::createFromFormat('Y-m-d', $birthdate)->format('Y-m-d') : null
        ]);

        if ($updated) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['birthdate'] = $birthdate;
            $update_message = 'Account details updated successfully!';
            $message_type = 'success';
        } else {
            // Do not set $update_message here to prevent the "Failed" message
            $message_type = 'danger'; // You might still want to set the type for other potential uses
        }
    } catch (\PDOException $e) {
        // Do not set $update_message here to prevent the "Database error" message
        $message_type = 'danger';
        error_log('My Account Update PDOException: ' . $e->getMessage());
    } catch (\Exception $e) {
        // Do not set $update_message here to prevent the "Unexpected error" message
        $message_type = 'danger';
        error_log('My Account Update General Exception: ' . $e->getMessage());
    }
}

$checkout = new Checkout();
$userOrders = $checkout->getAllOrders();
$userOrders = array_filter($userOrders, function($order) {
    return $order['user_name'] === $_SESSION['user']['name'];
});

?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <h1>My Account</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="col-md-8 bg-white p-5">
            <h2>Edit Account Details</h2>
            <?php if ($update_message): // Display the message only if it's a success message ?>
                <div class="alert alert-<?php echo $message_type; ?> text-center mb-3" role="alert">
                    <?php echo $update_message; ?>
                </div>
            <?php endif; ?>
            <form action="my-account.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['user']['address'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($_SESSION['user']['birthdate'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
            </form>

            <h2 class="mt-5">My Orders</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($userOrders as $order) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($order['id']) . '</td>';
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
    </div>
</div>

<?php template('footer.php'); ?>