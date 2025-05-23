<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\Category;
use Aries\MiniFrameworkStore\Models\Product;
use Carbon\Carbon;

$categories = new Category();
$product = new Product();

if (isset($_POST['submit'])) {
    if (!isLoggedIn()) {
        $message = "Guests are not allowed to add products. Please log in.";
    } else {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_FILES['image'];

        $targetFile = null;
        if ($image['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $imageName = uniqid() . '_' . basename($image["name"]);
            $targetFile = $targetDir . $imageName;
            move_uploaded_file($image["tmp_name"], $targetFile);
        }

        $product->insert([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'image_path' => $targetFile,
            'category_id' => $category,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now()
        ]);

        $message = "Product added successfully!";
    }
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg p-4">
                <h2 class="text-center mb-4 text-primary">Add New Product</h2>
                <p class="text-center text-muted mb-4">Share your amazing products with the world.</p>
                <?php if (isset($message)): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form action="add-product.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="product-name" class="form-label text-muted">Product Name</label>
                        <input type="text" class="form-control form-control-lg border-primary rounded-pill" id="product-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-description" class="form-label text-muted">Description</label>
                        <textarea class="form-control form-control-lg border-primary rounded-lg" id="product-description" name="description" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label text-muted">Price</label>
                        <div class="input-group">
                            <span class="input-group-text border-primary rounded-pill">₱</span>
                            <input type="number" class="form-control form-control-lg border-primary rounded-pill" id="product-price" name="price" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="product-category" class="form-label text-muted">Category</label>
                        <select class="form-select form-select-lg border-primary rounded-pill" id="product-category" name="category" required>
                            <option value="" selected disabled>Select Category</option>
                            <?php foreach($categories->getAll() as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="formFile" class="form-label text-muted">Product Image</label>
                        <input class="form-control form-control-lg border-primary rounded-pill" type="file" id="formFile" name="image" accept="image/*">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg rounded-pill" type="submit" name="submit">Add Product</button>
                    </div>
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        position: relative; /* Ensure card is positioned for potential z-index */
        z-index: 1; /* Ensure card is below the dropdown */
    }
    .form-control::placeholder,
    .form-select option {
        color: #aaa !important;
    }
</style>

<?php template('footer.php'); ?>