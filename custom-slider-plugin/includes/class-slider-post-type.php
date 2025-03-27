<?php
if (!class_exists('CAS_Slider_Post_Type')) {
    class CAS_Slider_Post_Type {
        public function __construct() {
            add_action('init', array($this, 'register_post_type'));
        }

        public function register_post_type() {
            $labels = array(
                'name' => 'Slides',
                'singular_name' => 'Slide',
                'menu_name' => 'Advanced Slider',
                'all_items' => 'All Slides',
                'add_new' => 'Add New Slide'
            );

            register_post_type('cas_slide', array(
                'labels' => $labels,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'supports' => array('title'),
                'menu_icon' => 'dashicons-slides',
                'has_archive' => false
            ));
        }
    }
    new CAS_Slider_Post_Type();
}