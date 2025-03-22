<?php
if (!class_exists('Custom_Slider_Post_Type')) {
    class Custom_Slider_Post_Type {
        public function __construct() {
            add_action('init', array($this, 'register_slider_post_type'));
        }

        public function register_slider_post_type() {
            register_post_type('custom_slider',
                array(
                    'labels' => array(
                        'name' => __('Sliders'),
                        'singular_name' => __('Slider')
                    ),
                    'public' => true,
                    'has_archive' => false,
                    'supports' => array('title'),
                    'menu_icon' => 'dashicons-slides',
                )
            );
        }
    }
    new Custom_Slider_Post_Type();
}