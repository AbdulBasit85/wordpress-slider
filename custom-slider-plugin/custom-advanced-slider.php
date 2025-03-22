<?php
/*
Plugin Name: Custom Advanced Slider
Description: A professional WordPress slider plugin with drag-and-drop functionality and rich design options.
Version: 1.0
Author: Abdul Basit
*/

if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-admin.php';

// Enqueue scripts and styles
function custom_slider_enqueue_scripts() {
    // Admin scripts and styles
    if (is_admin()) {
        wp_enqueue_style('custom-slider-admin-style', plugins_url('assets/css/admin-style.css', __FILE__));
        wp_enqueue_script('custom-slider-admin-script', plugins_url('assets/js/admin-script.js', __FILE__), array('jquery', 'jquery-ui-sortable'), null, true);
    }

    // Frontend scripts and styles
    wp_enqueue_style('custom-slider-frontend-style', plugins_url('assets/css/frontend-style.css', __FILE__));
    wp_enqueue_script('custom-slider-frontend-script', plugins_url('assets/js/frontend-script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_slider_enqueue_scripts');