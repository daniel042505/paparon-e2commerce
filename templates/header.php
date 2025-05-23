<?php

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', '0');

require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\Category;

$categories = new Category();

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Custom Styles for Navigation Bar */
        .navbar {
            background-color: #2c3e50 !important; /* Dark slate gray */
            padding: 1rem 0;
        }

        .navbar-brand {
            color: #ecf0f1 !important; /* Light gray */
            font-size: 2rem;
        }

        .navbar-toggler-icon {
            background-color: #ecf0f1;
        }

        .navbar-nav .nav-link {
            color: #ecf0f1 !important;
            padding: 0.75rem 1rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #f39c12 !important; /* Orange */
        }

        .navbar-nav .active > .nav-link {
            color: #f39c12 !important;
            font-weight: bold;
        }

        .dropdown-menu {
            background-color: #34495e; /* Darker slate gray */
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            color: #ecf0f1;
            transition: background-color 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f39c12;
            color: #2c3e50;
        }

        .icon-link svg {
            fill: #ecf0f1 !important;
            transition: fill 0.3s ease;
        }

        .icon-link:hover svg {
            fill: #f39c12 !important;
        }

        .badge-danger {
            background-color: #e74c3c; /* Red */
        }

        .fade-in-section {
            opacity: 1; /* Ensure it's visible */
            transition: opacity 0.5s ease-in-out; /* Optional fade-in */
        }
    </style>
</head>
<body class="store-body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fade-in-section">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 logo-animate" href="index.php">The Limited Edition Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active px-3" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="add-product.php">Add Product</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categories
                    </a>
                <ul class="dropdown-menu animate-dropdown">
                    <?php foreach($categories->getAll() as $category): ?>
                        <li><a class="dropdown-item" href="category.php?name=<?php echo $category['name'];  ?>"><?php echo $category['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                </li>
            </ul>
            <a class="icon-link position-relative me-3" href="cart.php" title="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" version="1.1" id="Capa_1" width="24px" height="24px" viewBox="0 0 902.86 902.86" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z     M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z"/>
                            <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717     c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744     c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742     C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744     c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z     M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742     S619.162,694.432,619.162,716.897z"/>
                        </g>
                    </g>
                </svg>
                <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?php echo countCart(); ?></span>
            </a>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Hello, <?php echo isset($_SESSION['user']) ? $_SESSION['user']['name'] : 'Guest'; ?>
                    </a>
                    <ul class="dropdown-menu animate-dropdown dropdown-menu-end">
                    <?php if (isLoggedIn()): ?>
                        <li><a class="dropdown-item" href="my-account.php">My Account</a></li>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 1): ?>
                            <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                        <li><a class="dropdown-item" href="register.php">Register</a></li>
                    <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    </nav>