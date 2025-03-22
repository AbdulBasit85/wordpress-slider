<?php
if (!class_exists('Custom_Slider_Post_Type')) {
    class Custom_Slider_Post_Type {
        public function __construct() {
            add_action('init', array($this, 'register_slider_post_type'));
        }

        public function register_slider_post_type() {
            $labels = array(
                'name'               => __('Sliders'),
                'singular_name'      => __('Slider'),
                'menu_name'          => __('Custom Advanced Slider'),
                'name_admin_bar'     => __('Slider'),
                'add_new'            => __('Add New'),
                'add_new_item'      => __('Add New Slider'),
                'new_item'           => __('New Slider'),
                'edit_item'          => __('Edit Slider'),
                'view_item'          => __('View Slider'),
                'all_items'          => __('All Sliders'),
                'search_items'       => __('Search Sliders'),
                'parent_item_colon'  => __('Parent Sliders:'),
                'not_found'          => __('No sliders found.'),
                'not_found_in_trash' => __('No sliders found in Trash.')
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => 'custom-advanced-slider', // Group under the parent menu
                'query_var'          => true,
                'rewrite'            => array('slug' => 'custom-slider'),
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => 6, // Position in the admin menu
                'menu_icon'          => 'dashicons-slides',
                'supports'           => array('title')
            );

            register_post_type('custom_slider', $args);
        }
    }
    new Custom_Slider_Post_Type();
}