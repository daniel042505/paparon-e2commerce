<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\Product;

$products = new Product();

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

?>
<div class="container">
    
</div>

<div class="container my-5 fade-in-section">
    <div class="row align-items-center mb-4">
        <div class="col-md-12">
            <h1 class="text-center display-4 fw-bold mb-3">Find here the most limited items for sale</h1>
            <p class="text-center lead">You can choose anything from our collection</p>
        </div>
    </div>
    <div class="row">
        <h2 class="mb-4 fw-semibold text-primary">Products</h2>
        <?php foreach($products->getAll() as $product): ?>
        <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex align-items-stretch">
            <div class="card w-100 animate-card">
                <img src="<?php echo $product['image_path'] ?>" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2"><?php echo $product['name'] ?></h5>
                    <h6 class="card-subtitle mb-2 text-success"><?php echo $formattedAmount = $pesoFormatter->formatCurrency($product['price'], 'PHP') ?></h6>
                    <p class="card-text flex-grow-1"><?php echo $product['description'] ?></p>
                    <div class="mt-3 d-flex gap-2">
                        <a href="product.php?id=<?php echo $product['id'] ?>" class="btn btn-primary flex-fill">View Product</a>
                        <a href="#" class="btn btn-success add-to-cart flex-fill" data-productid="<?php echo $product['id'] ?>" data-quantity="1">Add to Cart</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php template('footer.php'); ?>