<?php
/**
 * Testimonial post type
 */

/**
 * Register portfolio post type
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_register_testimonial_post_type()
{
    if (!current_theme_supports('testimonial-post-type')) {
        return;
    }

    $labels = array(
        'name' => esc_html__('Testimonials', 'cvca'),
        'singular_name' => esc_html__('Testimonial', 'cvca'),
        'add_new' => esc_html__('Add New', 'cvca'),
        'add_new_item' => esc_html__('Add New Testimonial', 'cvca'),
        'edit_item' => esc_html__('Edit Testimonial', 'cvca'),
        'new_item' => esc_html__('New Testimonial', 'cvca'),
        'view_item' => esc_html__('View Testimonial', 'cvca'),
        'search_items' => esc_html__('Search Testimonials', 'cvca'),
        'not_found' =>  esc_html__('No testimonials have been added yet', 'cvca'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'cvca'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'menu_icon'=> 'dashicons-format-quote',
        'rewrite' => false,
        'supports' => array('title', 'editor'),
        'has_archive' => true,
    );

    register_post_type( 'testimonial' , $args );

    $args = array(
        "label" 						=> esc_html__('Testimonial Categories', 'cvca'),
        "singular_label" 				=> esc_html__('Testimonial Category', 'cvca'),
        'public'                        => true,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite' => array(
            'slug' => 'testimonial_category',
            'with_front' => false,
            'hierarchical' => true,
        ),
        'query_var'                     => true
    );

    register_taxonomy( 'testimonial_category', 'testimonial', $args );
}
add_action( 'init', 'cvca_register_testimonial_post_type', 10, 0 );

/**
 * Do notification
 *
 * @internal  Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @see  https://developer.wordpress.org/reference/hooks/post_updated_messages/
 */
function cvca_testimonial_post_type_notify($messages)
{
    if (!current_theme_supports('testimonial-post-type')) {
        return;
    }
            
    $messages['testimonial'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Testimonial updated.', 'cvca'),
        2  => esc_html__('Custom field updated.', 'cvca'),
        3  => esc_html__('Custom field deleted.', 'cvca'),
        4  => esc_html__('Testimonial updated.', 'cvca'),
        5  => isset($_GET['revision']) ? esc_html__('Testimonial restored to revision from', 'cvca') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Testimonial published.', 'cvca'),
        7  => esc_html__('Testimonial saved.', 'cvca'),
        8  => esc_html__('Testimonial submitted.', 'cvca'),
        9  => esc_html__('Testimonial scheduled.', 'cvca'),
        10 => esc_html__('Testimonial draft updated.', 'cvca')
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'cvca_testimonial_post_type_notify' );
