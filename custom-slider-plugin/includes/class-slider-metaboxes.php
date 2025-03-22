<?php
if (!class_exists('Custom_Slider_Metaboxes')) {
    class Custom_Slider_Metaboxes {
        public function __construct() {
            add_action('add_meta_boxes', array($this, 'add_slider_metaboxes'));
            add_action('save_post', array($this, 'save_slider_metaboxes'));
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
            ?>
            <div id="slider-builder">
                <div class="slides-container">
                    <?php if (is_array($slides)) : ?>
                        <?php foreach ($slides as $index => $slide) : ?>
                            <div class="slide-item" data-index="<?php echo $index; ?>">
                                <textarea name="slides[<?php echo $index; ?>][content]"><?php echo esc_textarea($slide['content']); ?></textarea>
                                <input type="text" name="slides[<?php echo $index; ?>][image]" value="<?php echo esc_url($slide['image']); ?>" placeholder="Image URL">
                                <button class="remove-slide">Remove Slide</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button id="add-slide">Add Slide</button>
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
    }
    new Custom_Slider_Metaboxes();
}