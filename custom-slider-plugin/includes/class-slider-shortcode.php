<?php
if (!class_exists('Custom_Slider_Shortcode')) {
    class Custom_Slider_Shortcode {
        public function __construct() {
            add_shortcode('custom_slider', array($this, 'render_slider_shortcode'));
        }

        public function render_slider_shortcode($atts) {
            $atts = shortcode_atts(array(
                'id' => '',
            ), $atts);

            // Get slider ID
            $slider_id = $atts['id'];

            // Check if the template file exists
            $template_path = plugin_dir_path(__FILE__) . '../templates/slider-template.php';
            if (!file_exists($template_path)) {
                return '<p>Slider template not found.</p>';
            }

            // Start output buffering
            ob_start();

            // Include the template file
            include $template_path;

            // Return the buffered content
            return ob_get_clean();
        }
    }
    new Custom_Slider_Shortcode();
}