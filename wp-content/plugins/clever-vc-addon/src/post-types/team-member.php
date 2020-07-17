<?php
/**
 * Team member post type
 */

/**
 * Register portfolio post type
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_register_team_member_post_type()
{
    if (!current_theme_supports('team-member-post-type')) {
        return;
    }

    $labels = array(
        'name' => esc_html__('Team Member', 'cvca'),
        'singular_name' => esc_html__('Team Member', 'cvca'),
        'add_new' => esc_html__('Add New', 'cvca'),
        'add_new_item' => esc_html__('Add New Testimonial', 'cvca'),
        'edit_item' => esc_html__('Edit Team Member', 'cvca'),
        'new_item' => esc_html__('New Team Member', 'cvca'),
        'view_item' => esc_html__('View Team Member', 'cvca'),
        'search_items' => esc_html__('Search Team Members', 'cvca'),
        'not_found' =>  esc_html__('No team members have been added yet', 'cvca'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'cvca'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'menu_icon'=> 'dashicons-groups',
        'rewrite' => array(
            'slug' => 'team-member',
            'with_front' => false,
            'hierarchical' => true,
            'ep_mask'=>'true'
        ),
        'supports' => array('title', 'editor', 'team_category','thumbnail'),
        'has_archive' => true,
    );

    register_post_type( 'team' , $args );

    $args = array(
        "label" 						=> esc_html__('Team Categories', 'cvca'),
        "singular_label" 				=> esc_html__('Team Category', 'cvca'),
        'public'                        => true,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite' => array(
            'slug' => 'team_category',
            'with_front' => false,
            'hierarchical' => true,
            'ep_mask'=>'true'
        ),
        'query_var'        => true
    );

    register_taxonomy( 'team_category', 'team', $args );
}
add_action( 'init', 'cvca_register_team_member_post_type', 10, 0 );

/**
 * Do notification
 *
 * @internal  Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @see  https://developer.wordpress.org/reference/hooks/post_updated_messages/
 */
function cvca_team_member_post_type_notify($messages)
{
    if (!current_theme_supports('team-member-post-type')) {
        return;
    }

    $messages['team'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Team member updated.', 'cvca'),
        2  => esc_html__('Custom field updated.', 'cvca'),
        3  => esc_html__('Custom field deleted.', 'cvca'),
        4  => esc_html__('Team member updated.', 'cvca'),
        5  => isset($_GET['revision']) ? esc_html__('Team member restored to revision from', 'cvca') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Team member published.', 'cvca'),
        7  => esc_html__('Team member saved.', 'cvca'),
        8  => esc_html__('Team member submitted.', 'cvca'),
        9  => esc_html__('Team member scheduled.', 'cvca'),
        10 => esc_html__('Team member draft updated.', 'cvca')
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'cvca_team_member_post_type_notify' );
