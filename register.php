<?php
// Enable error reporting for debugging database connection issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'helpers/functions.php';
template('header.php');

use Aries\MiniFrameworkStore\Models\User;
use Carbon\Carbon;

$user = new User();
$registration_message = ''; // Initialize a variable for registration messages

if(isset($_POST['submit'])) {
    try {
        // Attempt to register the user
        $registered = $user->register([
            'name' => $_POST['full-name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'], // Password should be hashed inside the User model's register method
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        if ($registered) {
            $registration_message = 'You have successfully registered! You may now <a href="login.php">login</a>';
            // Optionally, log the user in immediately or redirect
            // For now, we'll just show the message.
        } else {
            // This 'else' block would be hit if register() returns false or null
            $registration_message = 'Registration failed. Please try again.';
        }
    } catch (\PDOException $e) {
        // Catch database-related errors
        $registration_message = 'Database error during registration: ' . $e->getMessage();
        error_log('Registration PDOException: ' . $e->getMessage()); // Log the error for server-side debugging
    } catch (\Exception $e) {
        // Catch any other general exceptions
        $registration_message = 'An unexpected error occurred: ' . $e->getMessage();
        error_log('Registration General Exception: ' . $e->getMessage()); // Log the error
    }
}

if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg p-5">
                <h2 class="text-center text-primary mb-4 fw-bold">Register</h2>
                <?php if ($registration_message): ?>
                    <div class="alert <?php echo strpos($registration_message, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?> text-center mb-3" role="alert">
                        <?php echo $registration_message; ?>
                    </div>
                <?php endif; ?>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="full-name" class="form-label text-muted">Full Name</label>
                        <input name="full-name" type="text" class="form-control form-control-lg border-primary rounded-pill" id="full-name" aria-describedby="fullNameHelp" required>
                        <div id="fullNameHelp" class="form-text text-muted">Enter your full name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input name="email" type="email" class="form-control form-control-lg border-primary rounded-pill" id="email" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-muted">Password</label>
                        <input name="password" type="password" class="form-control form-control-lg border-primary rounded-pill" id="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg rounded-pill">Register</button>
                    </div>
                    <p class="mt-3 text-center text-muted">Already have an account? <a href="login.php">Login here</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
    }
    .form-control::placeholder {
        color: #aaa !important;
    }
</style>

<?php template('footer.php'); ?>