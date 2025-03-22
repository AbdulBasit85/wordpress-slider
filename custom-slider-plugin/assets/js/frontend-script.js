jQuery(document).ready(function($) {
    // Initialize the slider
    function initializeSlider() {
        var $slider = $('.custom-slider');
        var $slides = $slider.find('.slide');
        var totalSlides = $slides.length;
        var currentIndex = 0;

        // Show the first slide
        $slides.removeClass('active');
        $slides.eq(currentIndex).addClass('active');

        // Auto-rotate slides every 5 seconds
        setInterval(function() {
            $slides.eq(currentIndex).removeClass('active');
            currentIndex = (currentIndex + 1) % totalSlides;
            $slides.eq(currentIndex).addClass('active');
        }, 5000);
    }

    // Initialize the slider on page load
    initializeSlider();
});