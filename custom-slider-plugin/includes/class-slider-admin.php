<?php
if (!class_exists('CAS_Slider_Admin')) {
    class CAS_Slider_Admin {
        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
        }

        public function add_admin_menu() {
            add_submenu_page(
                'edit.php?post_type=cas_slide',
                'Slider Settings',
                'Settings',
                'manage_options',
                'slider-settings',
                array($this, 'settings_page')
            );
        }

        public function register_settings() {
            register_setting('cas_slider_settings', 'cas_slider_options', array(
                'default' => array(
                    'autoplay' => '1',
                    'autoplay_speed' => '5000',
                    'animation_type' => 'slide',
                    'slide_speed' => '500',
                    'slide_duration' => '300',
                    'infinite_loop' => '1',
                    'slider_height' => '400px'
                )
            ));

            add_settings_section(
                'cas_slider_main',
                'Main Settings',
                array($this, 'section_callback'),
                'cas_slider_settings'
            );

            add_settings_field(
                'autoplay',
                'Autoplay',
                array($this, 'autoplay_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            add_settings_field(
                'autoplay_speed',
                'Autoplay Speed (ms)',
                array($this, 'autoplay_speed_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            add_settings_field(
                'animation_type',
                'Animation Type',
                array($this, 'animation_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            // New fields added below
            add_settings_field(
                'slide_speed',
                'Slide Speed (ms)',
                array($this, 'slide_speed_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            add_settings_field(
                'slide_duration',
                'Slide Duration (ms)',
                array($this, 'slide_duration_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            add_settings_field(
                'infinite_loop',
                'Infinite Loop',
                array($this, 'infinite_loop_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );

            add_settings_field(
                'slider_height',
                'Slider Height',
                array($this, 'slider_height_callback'),
                'cas_slider_settings',
                'cas_slider_main'
            );
        }

        public function section_callback() {
            echo '<p>Configure your slider settings below</p>';
        }

        public function autoplay_callback() {
            $options = get_option('cas_slider_options');
            echo '<input type="checkbox" name="cas_slider_options[autoplay]" value="1" ' . checked(1, $options['autoplay'] ?? false, false) . '>';
        }

        public function autoplay_speed_callback() {
            $options = get_option('cas_slider_options');
            echo '<input type="number" name="cas_slider_options[autoplay_speed]" value="' . esc_attr($options['autoplay_speed'] ?? 5000) . '" min="1000" step="500">';
        }

        public function animation_callback() {
            $options = get_option('cas_slider_options');
            $animations = array(
                'slide' => 'Slide',
                'fade' => 'Fade'
            );
            
            echo '<select name="cas_slider_options[animation_type]">';
            foreach ($animations as $value => $label) {
                echo '<option value="' . esc_attr($value) . '" ' . selected($options['animation_type'] ?? 'slide', $value, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }

        // New callback functions
        public function slide_speed_callback() {
            $options = get_option('cas_slider_options');
            echo '<input type="number" name="cas_slider_options[slide_speed]" value="' . esc_attr($options['slide_speed'] ?? 500) . '" min="100" max="2000" step="100">';
            echo '<p class="description">Time between slides in milliseconds</p>';
        }

        public function slide_duration_callback() {
            $options = get_option('cas_slider_options');
            echo '<input type="number" name="cas_slider_options[slide_duration]" value="' . esc_attr($options['slide_duration'] ?? 300) . '" min="100" max="2000" step="50">';
            echo '<p class="description">Transition animation duration in milliseconds</p>';
        }

        public function infinite_loop_callback() {
            $options = get_option('cas_slider_options');
            echo '<label><input type="checkbox" name="cas_slider_options[infinite_loop]" value="1" ' . checked(1, $options['infinite_loop'] ?? false, false) . '> Enable continuous looping</label>';
        }

        public function slider_height_callback() {
            $options = get_option('cas_slider_options');
            echo '<input type="text" name="cas_slider_options[slider_height]" value="' . esc_attr($options['slider_height'] ?? '400px') . '" placeholder="e.g. 400px or 50vh">';
            echo '<p class="description">Set slider height (px, vh, or other CSS units)</p>';
        }

        public function settings_page() {
            ?>
            <div class="wrap">
                <h1>Slider Settings</h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('cas_slider_settings');
                    do_settings_sections('cas_slider_settings');
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }
    }
    new CAS_Slider_Admin();
}