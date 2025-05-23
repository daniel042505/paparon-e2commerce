<?php
ob_start();
include 'helpers/functions.php';
template('header.php');

use Aries\MiniFrameworkStore\Models\User;

$user = new User();

if(isset($_POST['submit'])) {
    $user_info = $user->login([
        'email' => $_POST['email'],
    ]);

    if($user_info && password_verify($_POST['password'], $user_info['password'])) {
        $_SESSION['user'] = $user_info;
        if (isset($user_info['role_id']) && $user_info['role_id'] == 1) {
            header('Location: dashboard.php');
        } else {
            header('Location: my-account.php');
        }
        exit;
    } else {
        $message = 'Invalid username or password';
    }
}

if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: my-account.php');
    exit;
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg p-5" style="z-index: 1;">
                <h2 class="text-center text-primary mb-4 fw-bold">Login</h2>
                <?php if (isset($message)): ?>
                    <div class="alert alert-danger text-center mb-3" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input name="email" type="email" class="form-control form-control-lg border-primary rounded-pill" id="email" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-muted">Password</label>
                        <input name="password" type="password" class="form-control form-control-lg border-primary rounded-pill" id="password">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input border-primary rounded-pill" id="rememberMe">
                        <label class="form-check-label text-muted" for="rememberMe">Remember me</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg rounded-pill">Login</button>
                    </div>
                    <p class="mt-3 text-center text-muted">Don't have an account? <a href="register.php">Register here</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        position: relative; /* Add relative positioning to the navbar */
        z-index: 10; /* Ensure navbar is above most content */
    }

    .navbar-collapse {
        z-index: 11; /* Ensure dropdown is above other navbar elements */
    }

    .dropdown-menu {
        z-index: 12; /* Ensure dropdown is on top */
    }
    .card {
        border: none;
    }
    .form-control::placeholder {
        color: #aaa !important;
    }
</style>

<?php template('footer.php'); ?>
<?php ob_end_flush(); ?>

