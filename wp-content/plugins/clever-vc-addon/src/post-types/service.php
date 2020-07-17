<?php
/**
 * Service post type
 */

/**
 * Register portfolio post type
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_register_service_post_type()
{
    if (!current_theme_supports('service-post-type')) {
        return;
    }

    $labels = array(
        'name' => esc_html__('Services', 'cvca'),
        'singular_name' => esc_html__('Service Item', 'cvca'),
        'add_new' => esc_html__('Add New', 'cvca'),
        'add_new_item' => esc_html__('Add New Service Item', 'cvca'),
        'edit_item' => esc_html__('Edit Service Item', 'cvca'),
        'new_item' => esc_html__('New Service Item', 'cvca'),
        'view_item' => esc_html__('View Service Item', 'cvca'),
        'search_items' => esc_html__('Search Service', 'cvca'),
        'not_found' => esc_html__('No Service items have been added yet', 'cvca'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'cvca'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'menu_icon' => 'dashicons-admin-tools',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'service'
        ),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'revisions'
        ),
        'has_archive' => true,
    );

    register_post_type('service', $args);
}
add_action( 'init', 'cvca_register_service_post_type', 10, 0);

/**
 * Do notification
 *
 * @internal  Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @see  https://developer.wordpress.org/reference/hooks/post_updated_messages/
 */
function cvca_service_post_type_notify($messages)
{
    if (!current_theme_supports('service-post-type')) {
        return;
    }

    $messages['service'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Service updated.', 'cvca'),
        2  => esc_html__('Custom field updated.', 'cvca'),
        3  => esc_html__('Custom field deleted.', 'cvca'),
        4  => esc_html__('Service updated.', 'cvca'),
        5  => isset($_GET['revision']) ? esc_html__('Service restored to revision from', 'cvca') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Service published.', 'cvca'),
        7  => esc_html__('Service saved.', 'cvca'),
        8  => esc_html__('Service submitted.', 'cvca'),
        9  => esc_html__('Service scheduled.', 'cvca'),
        10 => esc_html__('Service draft updated.', 'cvca')
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'cvca_service_post_type_notify' );
