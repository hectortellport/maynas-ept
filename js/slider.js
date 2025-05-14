const slider = document.querySelector('.slider');
const slides = document.querySelectorAll('.slide');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
const dots = document.querySelectorAll('.slider-controls span');
let currentIndex = 0;
let autoSlideInterval;

function showSlide(index) {
    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;
    
    slider.style.transform = `translateX(-${index * 100}%)`;
    currentIndex = index;
    
    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');
    
    // Reiniciar el autoplay al cambiar manualmente
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(() => showSlide(currentIndex + 1), 5000);
}

// Autoplay cada 5 segundos
autoSlideInterval = setInterval(() => showSlide(currentIndex + 1), 5000);

// Event listeners
prevBtn.addEventListener('click', () => showSlide(currentIndex - 1));
nextBtn.addEventListener('click', () => showSlide(currentIndex + 1));

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => showSlide(index));
});