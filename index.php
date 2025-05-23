<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Aries\MiniFrameworkStore\Models\Product;

$products = new Product();

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

?>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-4 fw-bold text-primary mb-3">Find Here The Most Amazing and Limited Products</h1>
            <p class="lead text-muted">Find everything you need in our collection</p>
            <a href="#" class="btn btn-primary btn-lg rounded-pill mt-3">Shop Now</a>
        </div>
        <div class="col-md-4">
            <img src="assets/images/hero-image.svg" alt="Online Shopping" class="img-fluid rounded-lg shadow-sm">
        </div>
    </div>

    <section class="py-5 fade-in-section">
        <h2 class="mb-4 fw-semibold text-primary">Featured Products</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach($products->getAll() as $product): ?>
                <div class="col d-flex align-items-stretch">
                    <div class="card shadow-sm rounded-lg animate-card">
                        <img src="<?php echo htmlspecialchars($product['image_path']) ?>" class="card-img-top rounded-top" alt="<?php echo htmlspecialchars($product['name']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold text-dark mb-2"><?php echo htmlspecialchars($product['name']) ?></h5>
                            <h6 class="card-subtitle mb-3 text-success"><?php echo $formattedAmount = $pesoFormatter->formatCurrency($product['price'], 'PHP') ?></h6>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                            <div class="mt-auto d-grid gap-2">
                                <a href="product.php?id=<?php echo htmlspecialchars($product['id']) ?>" class="btn btn-outline-primary rounded-pill">View Details</a>
                                <button class="btn btn-success rounded-pill add-to-cart" data-productid="<?php echo htmlspecialchars($product['id']) ?>" data-quantity="1">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
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

    .hero-image {
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: scale(1.02);
    }

    .card-img-top {
        height: 200px; /* Adjust as needed */
        object-fit: cover;
        border-bottom: 1px solid #e9ecef;
    }

    .btn-primary,
    .btn-success,
    .btn-outline-primary {
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #1e7e34;
        border-color: #1e7e34;
    }

    .btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }

    .fade-in-section {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }

    .fade-in-section.visible {
        opacity: 1;
        transform: translateY(0);
    }

    @keyframes cardAnimation {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-card {
        animation: cardAnimation 0.4s ease-out forwards;
        opacity: 0; /* Initially hidden for the animation */
    }

    /* Optional: Add a slight delay to each card animation for a staggered effect */
    .row-cols-md-3 .col:nth-child(1) .animate-card {
        animation-delay: 0.1s;
    }
    .row-cols-md-3 .col:nth-child(2) .animate-card {
        animation-delay: 0.2s;
    }
    .row-cols-md-3 .col:nth-child(3) .animate-card {
        animation-delay: 0.3s;
    }
    /* Add more delays for more cards */
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fadeInSections = document.querySelectorAll('.fade-in-section');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15 // Adjust as needed
        });

        fadeInSections.forEach(section => {
            observer.observe(section);
        });
    });
</script>

<?php template('footer.php'); ?>