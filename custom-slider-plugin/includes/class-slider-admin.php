<?php
if (!class_exists('Custom_Slider_Admin')) {
    class Custom_Slider_Admin {
        public function __construct() {
            // Add admin-specific functionality here
            add_action('admin_menu', array($this, 'add_admin_menu'));
        }

        public function add_admin_menu() {
            // Add the parent menu
            add_menu_page(
                'Custom Advanced Slider',
                'Custom Advanced Slider',
                'manage_options',
                'custom-advanced-slider',
                array($this, 'render_settings_page'),
                'dashicons-slides',
                6
            );

            // Add submenus
            add_submenu_page(
                'custom-advanced-slider', // Parent slug
           
                'All Sliders', // Menu title
                'manage_options', // Capability
                'edit.php?post_type=custom_slider', // Menu slug
                null // Callback function (null for default behavior)
            );

            add_submenu_page(
                'custom-advanced-slider', // Parent slug
                'Add New Slider', // Page title
                'Add New Slider', // Menu title
                'manage_options', // Capability
                'post-new.php?post_type=custom_slider', // Menu slug
                null // Callback function (null for default behavior)
            );

            add_submenu_page(
                'custom-advanced-slider', // Parent slug
                'Slider Settings', // Page title
                'Settings', // Menu title
                'manage_options', // Capability
                'custom-slider-settings', // Menu slug
                array($this, 'render_settings_page') // Callback function
            );
        }

        public function render_settings_page() {
            ?>
            <div class="wrap">
                <h1>Custom Slider Settings</h1>
                <p>Configure your slider settings here.</p>
            </div>
            <?php
        }
    }
    new Custom_Slider_Admin();
}