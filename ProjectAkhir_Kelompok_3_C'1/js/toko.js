let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    
    if (n > slides.length) {
        slideIndex = 1;
    }    
    if (n < 1) {
        slideIndex = slides.length;
    }
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex-1].style.display = "block";  
    dots[slideIndex-1].className += " active";
}

// Auto slideshow
setInterval(function() {
    plusSlides(1);
}, 5000); // Change slides every 5 seconds

// Mobile Menu Toggle
const menuBtn = document.querySelector('.menu-btn');
const closeMenuBtn = document.querySelector('.close-menu-btn');
const navLinks = document.querySelector('.nav-links');
let isMenuOpen = false;

function openMenu() {
    navLinks.classList.add('active');
    document.body.style.overflow = 'hidden';
    isMenuOpen = true;
}

function closeMenu() {
    navLinks.classList.remove('active');
    document.body.style.overflow = '';
    isMenuOpen = false;
}

menuBtn.addEventListener('click', () => {
    if (!isMenuOpen) {
        openMenu();
    }
});

closeMenuBtn.addEventListener('click', closeMenu);

// Close mobile menu when clicking a link
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', closeMenu);
});

// Close menus when clicking outside
document.addEventListener('click', (e) => {
    if (isMenuOpen && 
        !e.target.closest('.nav-links') && 
        !e.target.closest('.menu-btn')) {
        closeMenu();
    }
});

// Close menus when clicking outside
document.addEventListener('click', (e) => {
    if (isMenuOpen && 
        !e.target.closest('.nav-links') && 
        !e.target.closest('.menu-btn') &&
        !e.target.closest('.close-menu-btn')) {
        closeMenu();
    }
});