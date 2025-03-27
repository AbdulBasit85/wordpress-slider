jQuery(document).ready(function($) {
    $('.cas-slider-wrapper').each(function() {
        const $wrapper = $(this);
        const $track = $wrapper.find('.cas-slider-track');
        let $slides = $wrapper.find('.cas-slide');
        let slideCount = $slides.length;
        
        if (slideCount === 0) return;

        // Parse settings with proper type conversion
        const rawSettings = typeof $wrapper.data('settings') === 'string' ? 
                         JSON.parse($wrapper.data('settings')) : 
                         $wrapper.data('settings') || {};
        
        const settings = {
            autoplay: rawSettings.autoplay !== undefined ? Boolean(rawSettings.autoplay) : true,
            speed: rawSettings.speed ? Math.max(parseInt(rawSettings.speed), 1000) : 5000,
            duration: rawSettings.duration ? Math.max(parseInt(rawSettings.duration), 100) : 800,
            animation: rawSettings.animation || 'slide',
            loop: rawSettings.loop !== undefined ? Boolean(rawSettings.loop) : true,
            height: rawSettings.height || '400px',
            arrows: rawSettings.arrows !== undefined ? Boolean(rawSettings.arrows) : true,
            pause_on_hover: rawSettings.pause_on_hover !== undefined ? Boolean(rawSettings.pause_on_hover) : true
        };

        // Hide arrows if disabled
        if (!settings.arrows) {
            $wrapper.find('.cas-slider-arrow').hide();
        }

        let currentIndex = 0;
        let interval = null;
        let isAnimating = false;
        let clonesAdded = false;
        let realSlideCount = slideCount;

        function initSlider() {
            $wrapper.css('height', settings.height);

            // Only clone slides if loop is enabled
            if (settings.loop && settings.animation !== 'fade' && !clonesAdded && slideCount > 1) {
                const $firstClone = $slides.first().clone(true).addClass('cas-cloned');
                const $lastClone = $slides.last().clone(true).addClass('cas-cloned');
                
                $track.append($firstClone).prepend($lastClone);
                $slides = $track.find('.cas-slide');
                clonesAdded = true;
                currentIndex = 1;
                slideCount = $slides.length;
            }

            realSlideCount = settings.loop && clonesAdded ? slideCount - 2 : slideCount;
            const slideWidth = 100 / realSlideCount;
            
            $slides.css('width', slideWidth + '%');
            $track.css('width', (slideCount * 100) + '%');
            
            $track.css('transition', 'none');
            $track.css('transform', `translateX(-${currentIndex * slideWidth}%)`);
            
            setTimeout(enableTransitions, 50);
        }

        function enableTransitions() {
            $track.css('transition', `transform ${settings.duration}ms cubic-bezier(0.4, 0, 0.2, 1)`);
        }

        function disableTransitions() {
            $track.css('transition', 'none');
        }

        function goToSlide(index) {
            if (isAnimating || slideCount === 0) return;
            isAnimating = true;

            const slideWidth = 100 / realSlideCount;

            if (settings.loop && settings.animation !== 'fade' && clonesAdded) {
                if (index >= realSlideCount + 1) {
                    enableTransitions();
                    $track.css('transform', `translateX(-${(realSlideCount + 1) * slideWidth}%)`);
                    
                    setTimeout(() => {
                        disableTransitions();
                        currentIndex = 1;
                        $track.css('transform', `translateX(-${currentIndex * slideWidth}%)`);
                        setTimeout(() => {
                            isAnimating = false;
                            enableTransitions();
                        }, 50);
                    }, settings.duration);
                    return;
                } 
                else if (index <= 0) {
                    enableTransitions();
                    $track.css('transform', 'translateX(0%)');
                    
                    setTimeout(() => {
                        disableTransitions();
                        currentIndex = realSlideCount;
                        $track.css('transform', `translateX(-${currentIndex * slideWidth}%)`);
                        setTimeout(() => {
                            isAnimating = false;
                            enableTransitions();
                        }, 50);
                    }, settings.duration);
                    return;
                }
            } 
            else {
                if (index >= realSlideCount) index = 0;
                if (index < 0) index = realSlideCount - 1;
            }
            
            currentIndex = index;
            enableTransitions();
            $track.css('transform', `translateX(-${currentIndex * slideWidth}%)`);
            
            setTimeout(() => {
                isAnimating = false;
            }, settings.duration);
        }

        function startAutoplay() {
            if (settings.autoplay) {
                clearInterval(interval);
                interval = setInterval(() => {
                    if (!isAnimating) {
                        goToSlide(currentIndex + 1);
                    }
                }, settings.speed);
            } else {
                clearInterval(interval);
            }
        }

        // Initialize slider
        initSlider();
        
        // Start autoplay only if enabled
        if (settings.autoplay) {
            startAutoplay();
        }

        // Navigation controls
        $wrapper.on('click', '.cas-arrow-next', function(e) {
            e.preventDefault();
            if (!isAnimating) {
                clearInterval(interval);
                goToSlide(currentIndex + 1);
                if (settings.autoplay) startAutoplay();
            }
        });

        $wrapper.on('click', '.cas-arrow-prev', function(e) {
            e.preventDefault();
            if (!isAnimating) {
                clearInterval(interval);
                goToSlide(currentIndex - 1);
                if (settings.autoplay) startAutoplay();
            }
        });

        // Pause on hover if enabled
        if (settings.pause_on_hover) {
            $wrapper.hover(
                function() { 
                    if (settings.autoplay) clearInterval(interval); 
                },
                function() { 
                    if (settings.autoplay) startAutoplay(); 
                }
            );
        }

        // Handle window resize
        $(window).on('resize', function() {
            disableTransitions();
            const slideWidth = 100 / realSlideCount;
            $track.css('transform', `translateX(-${currentIndex * slideWidth}%)`);
            setTimeout(enableTransitions, 50);
        });
    });
});