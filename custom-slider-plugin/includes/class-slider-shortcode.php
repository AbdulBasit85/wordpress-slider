<?php
if (!class_exists('CAS_Slider_Shortcode')) {
    class CAS_Slider_Shortcode {
        public function __construct() {
            add_shortcode('cas_slider', array($this, 'render_slider'));
            add_shortcode('cas_slide', array($this, 'render_single_slide'));
        }

        public function render_slider($atts) {
            $atts = shortcode_atts(array(
                'id' => '',
            ), $atts, 'cas_slider');

            if (empty($atts['id'])) {
                return '<div class="cas-slider-error">' . __('Please provide a slider ID', 'custom-advanced-slider') . '</div>';
            }

            $slider_data = $this->get_slider_data($atts['id']);
            if (empty($slider_data)) {
                return '<div class="cas-slider-error">' . __('Slider not found', 'custom-advanced-slider') . '</div>';
            }

            $template_path = plugin_dir_path(__FILE__) . '../templates/slider-template.php';
            if (!file_exists($template_path)) {
                return '<div class="cas-slider-error">' . __('Slider template missing', 'custom-advanced-slider') . '</div>';
            }

            ob_start();
            // Pass the complete slider data to template
            $slider_id = $atts['id'];
            include $template_path;
            return ob_get_clean();
        }

        public function render_single_slide($atts) {
            $atts = shortcode_atts(array(
                'id' => '',
                'slide' => 0
            ), $atts, 'cas_slide');

            if (empty($atts['id']) || !is_numeric($atts['slide'])) {
                return '<div class="cas-slide-error">' . __('Invalid slide parameters', 'custom-advanced-slider') . '</div>';
            }

            $slides = $this->get_slider_data($atts['id']);
            $slide_index = absint($atts['slide']);

            if (!isset($slides[$slide_index])) {
                return '<div class="cas-slide-error">' . __('Slide not found', 'custom-advanced-slider') . '</div>';
            }

            $slide = $slides[$slide_index];
            ob_start();
            ?>
            <div class="cas-single-slide" style="background-color: <?php echo esc_attr($slide['bg_color'] ?? '#ffffff'); ?>;">
                <?php if (!empty($slide['image'])) : ?>
                    <div class="cas-slide-bg">
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="" class="cas-slide-image">
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($slide['content'])) : ?>
                    <div class="cas-slide-content <?php echo esc_attr($slide['content_position'] ?? 'center'); ?>">
                        <?php echo wp_kses_post(wpautop($slide['content'])); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Helper function to get raw slider data without processing shortcodes
         */
        private function get_slider_data($slider_id) {
            $slides = get_post_meta($slider_id, '_cas_slides', true);
            
            if (is_array($slides)) {
                foreach ($slides as &$slide) {
                    if (isset($slide['content'])) {
                        $slide['content'] = strip_shortcodes($slide['content']);
                    }
                }
            }
            
            return is_array($slides) ? $slides : array();
        }
    }
    new CAS_Slider_Shortcode();
}