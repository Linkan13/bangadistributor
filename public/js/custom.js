// Global variables
let currentStep = 0;
let cart = [];
let wishlist = [];

// DOM elements
const searchInput = document.getElementById('search-input');
const searchSuggestions = document.getElementById('search-suggestions');
const faqItems = document.querySelectorAll('.faq-item');
const contactForm = document.getElementById('contact-form');
const backToTopBtn = document.getElementById('back-to-top');
const categoryTabs = document.querySelectorAll('.tab');
const productsGrid = document.getElementById('products-grid');
const addToCartButtons = document.querySelectorAll('.add-to-cart');
const cartCountElement = document.getElementById('cart-count');
const wishlistCountElement = document.getElementById('wishlist-count');

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    setupSmoothScrolling();
    setupFAQToggle();
    setupFormValidation();
    setupBackToTop();
    setupCategoryTabs();
    setupProductInteractions();
    setupCarousel();
    setupSearchFunctionality();
    setupScrollAnimations();
    updateCartCount();
    updateWishlistCount();
}

// Smooth scrolling functionality
function setupSmoothScrolling() {
    const scrollLinks = document.querySelectorAll('[data-scroll-to]');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-scroll-to');
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// FAQ toggle functionality
function setupFAQToggle() {
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');

        question.addEventListener('click', function() {
            const isExpanded = item.classList.contains('expanded');

            // Close all other FAQ items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('expanded');
                    const otherIcon = otherItem.querySelector('.faq-icon');
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    otherIcon.textContent = '+';
                    otherAnswer.style.maxHeight = '0';
                }
            });

            // Toggle current FAQ item
            if (isExpanded) {
                item.classList.remove('expanded');
                icon.textContent = '+';
                answer.style.maxHeight = '0';
            } else {
                item.classList.add('expanded');
                icon.textContent = '-';
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });
}

// Form validation functionality
function setupFormValidation() {
    const nameInput = document.getElementById('contact-name');
    const emailInput = document.getElementById('contact-email');
    const mobileInput = document.getElementById('contact-mobile');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');

    // Real-time validation
    nameInput.addEventListener('blur', () => validateName(nameInput));
    emailInput.addEventListener('blur', () => validateEmail(emailInput));
    mobileInput.addEventListener('blur', () => validateMobile(mobileInput));

}

function validateName(input) {
    const value = input.value.trim();
    const errorElement = document.getElementById('name-error');

    if (value.length < 2) {
        showError(input, errorElement, 'Name must be at least 2 characters long');
        return false;
    }

    hideError(input, errorElement);
    return true;
}

function validateEmail(input) {
    const value = input.value.trim();
    const errorElement = document.getElementById('email-error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(value)) {
        showError(input, errorElement, 'Please enter a valid email address');
        return false;
    }

    hideError(input, errorElement);
    return true;
}

function validateMobile(input) {
    const value = input.value.trim();
    const errorElement = document.getElementById('mobile-error');
    const mobileRegex = /^[\+]?[1-9][\d]{0,15}$/;

    if (!mobileRegex.test(value)) {
        showError(input, errorElement, 'Please enter a valid mobile number');
        return false;
    }

    hideError(input, errorElement);
    return true;
}

function showError(input, errorElement, message) {
    input.classList.add('error');
    errorElement.textContent = message;
    errorElement.classList.add('show');
}

function hideError(input, errorElement) {
    input.classList.remove('error');
    errorElement.textContent = '';
    errorElement.classList.remove('show');
}

function submitForm(submitBtn, successMessage) {
    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        // Hide loading state
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;

        // Show success message
        successMessage.classList.add('show');

        // Reset form
        contactForm.reset();

        // Hide success message after 5 seconds
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 5000);

        // Show notification
        showNotification('Thank you! Your message has been sent successfully.', 'success');

    }, 2000);
}

// Back to top functionality
function setupBackToTop() {
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });

    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Category tabs functionality
function setupCategoryTabs() {
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.getAttribute('data-category');

            // Update active tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Filter products
            filterProducts(category);
        });
    });
}

function filterProducts(category) {
    const productItems = document.querySelectorAll('.product-item');

    productItems.forEach(item => {
        const productCategory = item.getAttribute('data-category');

        if (category === 'womens' || productCategory === category) {
            item.style.display = 'flex';
            item.style.animation = 'fadeInUp 0.6s ease-out forwards';
        } else {
            item.style.display = 'none';
        }
    });
}

// Product interactions (add to cart, wishlist)
function setupProductInteractions() {
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.getAttribute('data-product');
            const productPrice = parseFloat(this.getAttribute('data-price'));

            addToCart(productName, productPrice);

            // Visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

function addToCart(name, price) {
    const existingItem = cart.find(item => item.name === name);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
            quantity: 1
        });
    }

    updateCartCount();
    showNotification(`${name} added to cart!`, 'success');
}

function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCountElement.textContent = totalItems;
}

function updateWishlistCount() {
    wishlistCountElement.textContent = wishlist.length;
}

// Carousel functionality
function setupCarousel() {
    const prevBtn = document.getElementById('prev-step');
    const nextBtn = document.getElementById('next-step');
    const dots = document.querySelectorAll('.dot');
    const stepImages = document.querySelectorAll('.step-image');
    const stepTexts = document.querySelectorAll('.step');

    // Auto-advance carousel
    setInterval(() => {
        nextStep();
    }, 5000);

    prevBtn.addEventListener('click', prevStep);
    nextBtn.addEventListener('click', nextStep);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToStep(index));
    });

    function nextStep() {
        currentStep = (currentStep + 1) % 3;
        updateCarousel();
    }

    function prevStep() {
        currentStep = (currentStep - 1 + 3) % 3;
        updateCarousel();
    }

    function goToStep(step) {
        currentStep = step;
        updateCarousel();
    }

    function updateCarousel() {
        // Update images
        stepImages.forEach((img, index) => {
            img.classList.toggle('active', index === currentStep);
        });

        // Update texts
        stepTexts.forEach((text, index) => {
            text.classList.toggle('active', index === currentStep);
        });

        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentStep);
        });
    }
}

// Search functionality
function setupSearchFunctionality() {
    const searchProducts = [
        'Leon Dress', 'Summer Dress', 'Classic Shirt', 'Casual Wear',
        'Sports Wear', 'Kids Fashion', 'Uniforms', 'Women\'s Fashion',
        'Men\'s Fashion', 'Accessories'
    ];

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();

        if (query.length > 0) {
            const filteredProducts = searchProducts.filter(product =>
                product.toLowerCase().includes(query)
            );

            showSearchSuggestions(filteredProducts);
        } else {
            hideSearchSuggestions();
        }
    });

    searchInput.addEventListener('blur', function() {
        // Delay hiding to allow click on suggestions
        setTimeout(hideSearchSuggestions, 200);
    });

    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            performSearch(query);
        }
    });
}

function showSearchSuggestions(suggestions) {
    searchSuggestions.innerHTML = '';

    if (suggestions.length > 0) {
        suggestions.slice(0, 5).forEach(suggestion => {
            const suggestionElement = document.createElement('div');
            suggestionElement.className = 'search-suggestion';
            suggestionElement.textContent = suggestion;
            suggestionElement.addEventListener('click', () => {
                searchInput.value = suggestion;
                performSearch(suggestion);
                hideSearchSuggestions();
            });
            searchSuggestions.appendChild(suggestionElement);
        });

        searchSuggestions.style.display = 'block';
    } else {
        hideSearchSuggestions();
    }
}

function hideSearchSuggestions() {
    searchSuggestions.style.display = 'none';
}

function performSearch(query) {
    showNotification(`Searching for "${query}"...`, 'info');
    // In a real application, this would trigger a search API call
    console.log('Searching for:', query);
}

// Scroll animations
function setupScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe sections for animation
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        observer.observe(section);
    });

    // Observe product items
    const productItems = document.querySelectorAll('.product-item');
    productItems.forEach(item => {
        observer.observe(item);
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add some interactive elements on hover
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s ease';
        });

        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add pulse animation to CTA buttons periodically
    const ctaButtons = document.querySelectorAll('.cta-button, .hero-cta');
    setInterval(() => {
        ctaButtons.forEach(btn => {
            btn.classList.add('pulse');
            setTimeout(() => {
                btn.classList.remove('pulse');
            }, 2000);
        });
    }, 10000);
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    // ESC key closes search suggestions
    if (e.key === 'Escape') {
        hideSearchSuggestions();
    }

    // Enter key on FAQ questions
    if (e.key === 'Enter' && e.target.classList.contains('faq-question')) {
        e.target.click();
    }
});

// Add loading states for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img');

    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });

        img.addEventListener('error', function() {
            this.alt = 'Image could not be loaded';
            this.style.opacity = '0.5';
        });
    });
});

// Mobile menu functionality (if needed)
function setupMobileMenu() {
    const categoriesBtn = document.getElementById('categories-btn');

    categoriesBtn.addEventListener('click', function() {
        // This would toggle a mobile menu in a real implementation
        showNotification('Category menu would open here', 'info');
    });
}

// Initialize mobile-specific features
if (window.innerWidth <= 768) {
    setupMobileMenu();
}

// Handle window resize
window.addEventListener('resize', debounce(function() {
    // Adjust layout for different screen sizes
    if (window.innerWidth <= 768) {
        setupMobileMenu();
    }
}, 250));

// Performance optimization: Lazy loading for images
function setupLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('loading');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}

// Add some interactive features for better UX
document.addEventListener('mousemove', debounce(function(e) {
    // Subtle parallax effect for hero section
    const hero = document.querySelector('.hero-background');
    if (hero) {
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        hero.style.transform = `translate(${x * 10}px, ${y * 10}px)`;
    }
}, 16));

// Add custom context menu for products (right-click)
document.addEventListener('contextmenu', function(e) {
    if (e.target.closest('.product-item')) {
        e.preventDefault();
        const productName = e.target.closest('.product-item').querySelector('.product-name').textContent;
        showNotification(`Quick actions for ${productName} would appear here`, 'info');
    }
});

console.log('🎉 BANGA Website fully loaded with interactive features!');