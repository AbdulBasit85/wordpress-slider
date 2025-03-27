<?php
if (!class_exists('CAS_Slider_Metaboxes')) {
    class CAS_Slider_Metaboxes {
        public function __construct() {
            add_action('add_meta_boxes', array($this, 'add_metaboxes'));
            add_action('save_post', array($this, 'save_metaboxes'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
            add_action('admin_init', array($this, 'register_settings'));
        }

        public function enqueue_admin_assets() {
            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('cas-slider-admin', plugins_url('../assets/js/admin-script.js', __FILE__), array('jquery', 'jquery-ui-sortable'), '1.0', true);
            
            global $post;
            if ($post) {
                wp_localize_script('cas-slider-admin', 'cas_slider_vars', array(
                    'post_id' => $post->ID
                ));
            }
        }

        public function register_settings() {
            register_setting('cas_slider_settings', 'cas_slider_options');
            
            add_settings_section(
                'cas_slider_main_section',
                'Main Settings',
                null,
                'cas_slider_settings'
            );
            
            add_settings_field(
                'default_autoplay',
                'Default Autoplay',
                array($this, 'render_default_autoplay_field'),
                'cas_slider_settings',
                'cas_slider_main_section'
            );
        }

        public function render_default_autoplay_field() {
            $options = get_option('cas_slider_options');
            ?>
            <input type="checkbox" name="cas_slider_options[default_autoplay]" value="1" <?php checked(isset($options['default_autoplay']) ? $options['default_autoplay'] : true); ?>>
            <?php
        }

        public function add_metaboxes() {
            add_meta_box(
                'cas_slider_content',
                'Slider Builder',
                array($this, 'render_slides_metabox'),
                'cas_slide',
                'normal',
                'high'
            );

            add_meta_box(
                'cas_slider_settings',
                'Slider Settings',
                array($this, 'render_settings_metabox'),
                'cas_slide',
                'side',
                'default'
            );
        }

        public function render_slides_metabox($post) {
            wp_nonce_field('cas_slider_nonce', 'cas_slider_nonce');
            $slides = get_post_meta($post->ID, '_cas_slides', true) ?: array();
            ?>
            <div class="cas-slider-builder">
                <div class="cas-main-slider-shortcode" style="background:#f5f5f5;padding:15px;margin-bottom:20px;border:1px solid #ddd;border-radius:4px;">
                    <h3 style="margin-top:0;">Main Slider Shortcode</h3>
                    <div class="cas-shortcode-input-wrapper">
                        <input type="text" readonly value="[cas_slider id='<?php echo $post->ID; ?>']" class="widefat" style="font-size:14px;font-weight:bold;">
                        <button class="cas-copy-shortcode button button-primary">Copy</button>
                    </div>
                    <p class="description">Use this shortcode in your pages to display the complete slider.</p>
                </div>

                <div class="cas-slides-container">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <div class="cas-slide" data-index="<?php echo $index; ?>">
                            <div class="cas-slide-header">
                                <h3>Slide <?php echo $index + 1; ?></h3>
                                <button type="button" class="cas-remove-slide button">Remove</button>
                            </div>
                            <div class="cas-slide-content">
                                <div class="cas-form-group">
                                    <label>Background Image</label>
                                    <div class="cas-image-upload">
                                        <input type="hidden" class="cas-image-url" name="cas_slides[<?php echo $index; ?>][image]" value="<?php echo esc_url($slide['image'] ?? ''); ?>">
                                        <button type="button" class="cas-upload-image button">Select Image</button>
                                        <button type="button" class="cas-remove-image button">Remove</button>
                                        <div class="cas-image-preview">
                                            <?php if (!empty($slide['image'])) : ?>
                                                <img src="<?php echo esc_url($slide['image']); ?>" style="max-width: 200px;">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cas-form-group">
                                    <label>Slide Content</label>
                                    <?php 
                                    wp_editor(
                                        $slide['content'] ?? '',
                                        'cas_slide_content_' . $index,
                                        array(
                                            'textarea_name' => 'cas_slides[' . $index . '][content]',
                                            'media_buttons' => true,
                                            'textarea_rows' => 5,
                                            'teeny' => true
                                        )
                                    );
                                    ?>
                                </div>
                                
                                <div class="cas-form-row">
                                    <div class="cas-form-group">
                                        <label>Background Color</label>
                                        <input type="text" class="cas-color-picker" name="cas_slides[<?php echo $index; ?>][bg_color]" value="<?php echo esc_attr($slide['bg_color'] ?? '#ffffff'); ?>">
                                    </div>
                                    
                                    <div class="cas-form-group">
                                        <label>Content Position</label>
                                        <select name="cas_slides[<?php echo $index; ?>][content_position]">
                                            <option value="left" <?php selected($slide['content_position'] ?? '', 'left'); ?>>Left</option>
                                            <option value="center" <?php selected($slide['content_position'] ?? '', 'center'); ?>>Center</option>
                                            <option value="right" <?php selected($slide['content_position'] ?? '', 'right'); ?>>Right</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" id="cas-add-slide" class="button button-primary">Add New Slide</button>
            </div>
            <?php
        }

        public function render_settings_metabox($post) {
            wp_nonce_field('cas_slider_settings_nonce', 'cas_slider_settings_nonce');
            $settings = get_post_meta($post->ID, '_cas_slider_settings', true) ?: array();
            
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
            ?>
            <div class="cas-settings-container">
                <div class="cas-setting-field">
                    <label>
                        <input type="checkbox" name="cas_settings[autoplay]" value="1" <?php checked($settings['autoplay'], true); ?>>
                        <?php _e('Autoplay', 'custom-advanced-slider'); ?>
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <?php _e('Speed (ms)', 'custom-advanced-slider'); ?>
                        <input type="number" name="cas_settings[speed]" value="<?php echo esc_attr($settings['speed']); ?>" min="1000" step="100">
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <?php _e('Transition Duration (ms)', 'custom-advanced-slider'); ?>
                        <input type="number" name="cas_settings[duration]" value="<?php echo esc_attr($settings['duration']); ?>" min="100" step="50">
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <?php _e('Animation Type', 'custom-advanced-slider'); ?>
                        <select name="cas_settings[animation]">
                            <option value="slide" <?php selected($settings['animation'], 'slide'); ?>><?php _e('Slide', 'custom-advanced-slider'); ?></option>
                            <option value="fade" <?php selected($settings['animation'], 'fade'); ?>><?php _e('Fade', 'custom-advanced-slider'); ?></option>
                        </select>
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <input type="checkbox" name="cas_settings[loop]" value="1" <?php checked($settings['loop'], true); ?>>
                        <?php _e('Infinite Loop', 'custom-advanced-slider'); ?>
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <?php _e('Slider Height', 'custom-advanced-slider'); ?>
                        <input type="text" name="cas_settings[height]" value="<?php echo esc_attr($settings['height']); ?>">
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <input type="checkbox" name="cas_settings[arrows]" value="1" <?php checked($settings['arrows'], true); ?>>
                        <?php _e('Show Navigation Arrows', 'custom-advanced-slider'); ?>
                    </label>
                </div>

                <div class="cas-setting-field">
                    <label>
                        <input type="checkbox" name="cas_settings[pause_on_hover]" value="1" <?php checked($settings['pause_on_hover'], true); ?>>
                        <?php _e('Pause on Hover', 'custom-advanced-slider'); ?>
                    </label>
                </div>
            </div>
            <?php
        }

        public function save_metaboxes($post_id) {
            if (!isset($_POST['cas_slider_nonce']) || !wp_verify_nonce($_POST['cas_slider_nonce'], 'cas_slider_nonce')) {
                return;
            }

            if (!isset($_POST['cas_slider_settings_nonce']) || !wp_verify_nonce($_POST['cas_slider_settings_nonce'], 'cas_slider_settings_nonce')) {
                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            if (isset($_POST['cas_slides'])) {
                $sanitized_slides = array();
                foreach ($_POST['cas_slides'] as $index => $slide) {
                    $sanitized_slides[$index] = array(
                        'image' => esc_url_raw($slide['image'] ?? ''),
                        'content' => wp_kses_post($slide['content'] ?? ''),
                        'bg_color' => sanitize_hex_color($slide['bg_color'] ?? '#ffffff'),
                        'content_position' => in_array($slide['content_position'] ?? '', ['left', 'center', 'right']) 
                            ? $slide['content_position'] 
                            : 'center'
                    );
                }
                update_post_meta($post_id, '_cas_slides', $sanitized_slides);
            }

            if (isset($_POST['cas_settings'])) {
                $settings = array(
                    'autoplay' => isset($_POST['cas_settings']['autoplay']),
                    'speed' => absint($_POST['cas_settings']['speed'] ?? 5000),
                    'duration' => absint($_POST['cas_settings']['duration'] ?? 800),
                    'animation' => sanitize_text_field($_POST['cas_settings']['animation'] ?? 'slide'),
                    'loop' => isset($_POST['cas_settings']['loop']),
                    'height' => sanitize_text_field($_POST['cas_settings']['height'] ?? '400px'),
                    'arrows' => isset($_POST['cas_settings']['arrows']),
                    'pause_on_hover' => isset($_POST['cas_settings']['pause_on_hover'])
                );
                update_post_meta($post_id, '_cas_slider_settings', $settings);
            }
        }
    }
    new CAS_Slider_Metaboxes();
}