<?php
/**
 * Slider Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$slides = get_post_meta($atts['id'], '_cas_slides', true);
$settings = get_post_meta($atts['id'], '_cas_slider_settings', true) ?: array();

$defaults = array(
    'autoplay' => true,
    'speed' => 5000,
    'duration' => 800,
    'animation' => 'slide',
    'loop' => true,
    'height' => '400px',
    'arrows' => true,
    'pause_on_hover' => true
);

$settings = wp_parse_args($settings, $defaults);

if (empty($slides)) {
    echo '<p class="cas-no-slides">No slides found</p>';
    return;
}
?>

<div class="cas-slider-wrapper" 
     data-settings='<?php echo htmlspecialchars(json_encode($settings), ENT_QUOTES, 'UTF-8'); ?>'
     style="height: <?php echo esc_attr($settings['height']); ?>">
     
    <div class="cas-slider-container">
        <?php if ($settings['arrows']) : ?>
            <button class="cas-slider-arrow cas-arrow-prev" aria-label="<?php esc_attr_e('Previous slide', 'custom-advanced-slider'); ?>">&#10094;</button>
            <button class="cas-slider-arrow cas-arrow-next" aria-label="<?php esc_attr_e('Next slide', 'custom-advanced-slider'); ?>">&#10095;</button>
        <?php endif; ?>
        
        <div class="cas-slider-track">
            <?php foreach ($slides as $index => $slide) : ?>
                <div class="cas-slide" style="background-color: <?php echo esc_attr($slide['bg_color'] ?? '#ffffff'); ?>;">
                    <?php if (!empty($slide['image'])) : ?>
                        <div class="cas-slide-bg">
                            <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['content'] ?? sprintf(__('Slide %d', 'custom-advanced-slider'), $index + 1)); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($slide['content'])) : ?>
                        <div class="cas-slide-content <?php echo esc_attr($slide['content_position'] ?? 'center'); ?>">
                            <?php echo wp_kses_post($slide['content']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>