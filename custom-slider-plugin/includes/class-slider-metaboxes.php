<?php
if (!class_exists('Custom_Slider_Metaboxes')) {
    class Custom_Slider_Metaboxes {
        public function __construct() {
            add_action('add_meta_boxes', array($this, 'add_slider_metaboxes'));
            add_action('save_post', array($this, 'save_slider_metaboxes'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        }

        public function add_slider_metaboxes() {
            add_meta_box(
                'custom_slider_slides',
                __('Slides'),
                array($this, 'render_slider_metabox'),
                'custom_slider',
                'normal',
                'default'
            );
        }

        public function render_slider_metabox($post) {
            wp_nonce_field('custom_slider_meta_box', 'custom_slider_meta_box_nonce');
            $slides = get_post_meta($post->ID, '_custom_slider_slides', true);
            $slides = is_array($slides) ? $slides : array();
            ?>
            <div id="slider-builder">
                <div class="slides-container">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <div class="slide-item" data-index="<?php echo $index; ?>">
                            <div class="slide-header">
                                <span class="slide-title">Slide <?php echo $index + 1; ?></span>
                                <button class="remove-slide">Remove Slide</button>
                            </div>
                            <div class="slide-content">
                                <label for="slide-image-<?php echo $index; ?>">Image URL:</label>
                                <input type="text" id="slide-image-<?php echo $index; ?>" name="slides[<?php echo $index; ?>][image]" value="<?php echo esc_url($slide['image']); ?>" placeholder="Enter image URL">
                                <label for="slide-content-<?php echo $index; ?>">Content:</label>
                                <textarea id="slide-content-<?php echo $index; ?>" name="slides[<?php echo $index; ?>][content]" placeholder="Enter slide content"><?php echo esc_textarea($slide['content']); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button id="add-slide" class="button button-primary">Add New Slide</button>
            </div>
            <?php
        }

        public function save_slider_metaboxes($post_id) {
            if (!isset($_POST['custom_slider_meta_box_nonce'])) {
                return;
            }
            if (!wp_verify_nonce($_POST['custom_slider_meta_box_nonce'], 'custom_slider_meta_box')) {
                return;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (isset($_POST['slides'])) {
                update_post_meta($post_id, '_custom_slider_slides', $_POST['slides']);
            }
        }

        public function enqueue_admin_scripts() {
            wp_enqueue_style('custom-slider-admin-style', plugins_url('assets/css/admin-style.css', __FILE__));
            wp_enqueue_script('custom-slider-admin-script', plugins_url('assets/js/admin-script.js', __FILE__), array('jquery', 'jquery-ui-sortable'), null, true);
        }
    }
    new Custom_Slider_Metaboxes();
}