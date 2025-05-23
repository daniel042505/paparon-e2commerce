<?php

require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\Product;

session_start();


try {
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';

    if ($action === 'remove') {
        if (!isset($_POST['productId'])) {
            throw new Exception('Product ID is missing.');
        }
        $product_id = intval($_POST['productId']);
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        // Calculate total quantity in cart
        $totalQuantity = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalQuantity += $item['quantity'];
        }
        echo json_encode(['status' => 'success', 'message' => 'Product removed from cart', 'cartCount' => $totalQuantity]);
        exit;
    }

    if (!isset($_POST['productId'])) {
        throw new Exception('Product ID is missing.');
    }

    $product_id = intval($_POST['productId']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($quantity < 1) {
        throw new Exception('Quantity must be at least 1.');
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product = new Product();
    $productDetails = $product->getById($product_id);

    if (!$productDetails) {
        throw new Exception('Product not found.');
    }

    // Add or update product in cart
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'name' => $productDetails['name'],
        'price' => $productDetails['price'],
        'image_path' => $productDetails['image_path'],
        'total' => $productDetails['price'] * $quantity
    ];

    // Calculate total quantity in cart
    $totalQuantity = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalQuantity += $item['quantity'];
    }
    echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cartCount' => $totalQuantity]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>