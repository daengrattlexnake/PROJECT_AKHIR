
// Smooth Scroll for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            // Close mobile menu if open
            if (isMenuOpen) {
                navLinks.style.display = 'none';
                isMenuOpen = false;
            }
        }
    });
});

// Navbar Background Change on Scroll
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.style.backgroundColor = '#0A1A2F';
        navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.3)';
    } else {
        navbar.style.backgroundColor = '#0D2444';
        navbar.style.boxShadow = 'none';
    }
});

// Animate Stats Counter
const statsSection = document.querySelector('.stats');
const statNumbers = document.querySelectorAll('.stat-number');
let animated = false;

const animateStats = () => {
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const updateCount = () => {
            if (current < target) {
                current += increment;
                stat.textContent = Math.ceil(current) + '+';
                requestAnimationFrame(updateCount);
            } else {
                stat.textContent = target + '+';
            }
        };
        updateCount();
    });
};

// Animate stats when scrolled into view
window.addEventListener('scroll', () => {
    if (isElementInViewport(statsSection) && !animated) {
        animateStats();
        animated = true;
    }
});

// Utility function to check if element is in viewport
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Feature Cards Hover Animation
const featureCards = document.querySelectorAll('.feature-card');
featureCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px)';
        card.style.transition = 'transform 0.3s ease';
        card.style.backgroundColor = '#102544';
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.backgroundColor = '#0A1A2F';
    });
});

// Add Loading Animation
window.addEventListener('load', () => {
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});

// Form Validation (if you add a contact form)
function validateForm(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address');
        return false;
    }
    // Add more validation as needed
    return true;
}

// Lazy Loading for Images
document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});

// Add this to your existing script.js
document.querySelectorAll('.btn-like, .btn-dislike').forEach(button => {
    button.addEventListener('click', function() {
        // Get the other button in the same review card
        const reviewCard = this.closest('.review-card');
        const otherButton = button.classList.contains('btn-like') 
            ? reviewCard.querySelector('.btn-dislike')
            : reviewCard.querySelector('.btn-like');
        
        // If the other button is active, remove its active state
        if (otherButton.classList.contains('active')) {
            otherButton.classList.remove('active');
        }
        
        // Toggle active state of clicked button
        this.classList.toggle('active');
        
        // Optional: Update the count when button is clicked
        const countElement = this.querySelector('.count');
        const currentCount = parseInt(countElement.textContent.replace(/,/g, ''));
        
        if (this.classList.contains('active')) {
            // Increment count when activated
            countElement.textContent = (currentCount + 1).toLocaleString();
        } else {
            // Decrement count when deactivated
            countElement.textContent = (currentCount - 1).toLocaleString();
        }
    });
});