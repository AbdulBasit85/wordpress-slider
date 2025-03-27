<?php
/**
 * Plugin Name: Custom Advanced Slider
 * Description: Professional slider plugin with drag-and-drop functionality
 * Version: 1.0
 * Author: Your Name
 */

defined('ABSPATH') || exit;

// Core includes
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-slider-shortcode.php';

// Enqueue assets
function cas_enqueue_assets() {
    // Admin assets
    if (is_admin()) {
        wp_enqueue_style(
            'cas-admin-css',
            plugins_url('assets/css/admin-style.css', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin-style.css')
        );
        wp_enqueue_script(
            'cas-admin-js',
            plugins_url('assets/js/admin-script.js', __FILE__),
            array('jquery', 'jquery-ui-sortable'),
            filemtime(plugin_dir_path(__FILE__) . 'assets/js/admin-script.js'),
            true
        );
    }
    
    // Frontend assets
    wp_enqueue_style(
        'cas-frontend-css',
        plugins_url('assets/css/frontend-style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/frontend-style.css')
    );
    wp_enqueue_script(
        'cas-frontend-js',
        plugins_url('assets/js/frontend-script.js', __FILE__),
        array('jquery'),
        filemtime(plugin_dir_path(__FILE__) . 'assets/js/frontend-script.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'cas_enqueue_assets');