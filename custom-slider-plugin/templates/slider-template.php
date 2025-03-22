<?php
/**
 * Slider Template
 *
 * This file is used to display the slider on the frontend.
 *
 * @package Custom_Advanced_Slider
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get slider ID from shortcode attributes
$slider_id = isset($atts['id']) ? intval($atts['id']) : 0;

// Get slides from post meta
$slides = get_post_meta($slider_id, '_custom_slider_slides', true);

// If no slides found, display a message
if (empty($slides)) {
    echo '<p>No slides found.</p>';
    return;
}
?>

<div class="custom-slider">
    <?php foreach ($slides as $slide) : ?>
        <div class="slide">
            <?php if (!empty($slide['image'])) : ?>
                <img src="<?php echo esc_url($slide['image']); ?>" alt="Slide Image">
            <?php endif; ?>
            <?php if (!empty($slide['content'])) : ?>
                <div class="slide-content">
                    <?php echo wp_kses_post($slide['content']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>