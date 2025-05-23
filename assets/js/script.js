(function () {
    // Fade-in sections on scroll
    const fadeSections = document.querySelectorAll('.fade-in-section, .animate-card');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'none';
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        fadeSections.forEach(section => {
            observer.observe(section);
        });
    } else {
        // Fallback for old browsers
        fadeSections.forEach(section => {
            section.style.opacity = 1;
            section.style.transform = 'none';
        });
    }

    // Animate cart badge when item is added
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-productid');
            const quantity = this.getAttribute('data-quantity') || 1;
            const formData = new FormData();
            formData.append('productId', productId);
            formData.append('quantity', quantity);
            fetch('/cortes-framework-store/cart-process.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Animate cart badge
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.classList.add('animated');
                        setTimeout(() => cartCount.classList.remove('animated'), 500);
                    }
                    // Ripple effect
                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                    // Show success message
                    alert('Product added to cart successfully!');
                    location.reload();
                } else {
                    alert('Failed to add product to cart.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
})();

// Ripple effect CSS (injected for demo, move to CSS in production)
const rippleStyle = document.createElement('style');
rippleStyle.innerHTML = `.ripple {
    position: absolute;
    border-radius: 50%;
    transform: scale(0);
    animation: ripple 0.6s linear;
    background: rgba(79,140,255,0.25);
    pointer-events: none;
    left: 50%;
    top: 50%;
    width: 120px;
    height: 120px;
    margin-left: -60px;
    margin-top: -60px;
    z-index: 1;
}
@keyframes ripple {
    to {
        transform: scale(2.5);
        opacity: 0;
    }
}`;
document.head.appendChild(rippleStyle);
