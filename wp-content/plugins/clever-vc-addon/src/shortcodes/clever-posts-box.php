<?php
/**
 * Add shortcode
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array    $atts    Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_posts_box_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverPostsBox_shortcode_atts',array(
            'title' => '',
            'excerpt' => 'yes',
            'excerpt_length' => 40,
            'readmore' => 'yes',
            'layout'   => 'grid',
            'columns'  => 3,
            'pagi_type'     => 'default',
            'order'        => 'DESC',
            'order_by'      => 'date',
            'animation_stype' => 'fadeIn',
            'animation_speed' => 300,
            'taxonomies'   => 'category',
            'custom_query'  => '',
            'post_slugs'    => '',
            'default_label' => esc_html__('Load More', 'cvca'),
            'loading_label' => esc_html__('Loading...', 'cvca'),
            'nomore_label'  => esc_html__('No More to Load', 'cvca'),
            'item_class'    => '.hentry',
            'items_container' => '.container',
            'template'     => '',
            'post_type'     => 'post',
            'meta_key'      => '',
            'post_author'   => '',
            'post_offset'  => 0,
            'post_exclude' => '',
            'posts_per_page' => get_option('posts_per_page'),
            'scroll_offset' => 50,
            'images_loaded' => true,
            'el_class'     => '',
        )),
        $atts, 'CleverPostsBox'
    );

    $html = cvca_get_shortcode_view('posts-box', $atts, $content);

    return $html;
}
add_shortcode( 'CleverPostsBox', 'cvca_add_clever_posts_box_shortcode' );
/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_posts_box_shortcode_with_vc()
{
    global $wpdb, $wp_taxonomies, $wp_post_types;

    $authors = array();
    $post_types = array();
    $taxonomies_list = array();
    $post_types_list = array();

    foreach ($wp_post_types as $post_type => $post_type_object) {
        if (isset($post_type_object->public) && $post_type_object->public) {
            $post_types[] = $post_type;
            $post_types_list[(string)$post_type_object->label] = $post_type;
        }
    }

    $post_types[] = 'slugs';
    $post_types[] = 'custom';
    $post_types_list[esc_html__('Custom posts', 'cvca')] = 'slugs';
    $post_types_list[esc_html__('Custom query', 'cvca')] = 'custom';

    foreach ($wp_taxonomies as $taxonomy => $taxonomy_object) {
        if ('post_format' === $taxonomy) {
            continue;
        }
        if (isset($taxonomy_object->public) && $taxonomy_object->public) {
            $taxonomies_list[] = array('value' => $taxonomy, 'label' => $taxonomy_object->label);
        }
    }

    $author_ids = $wpdb->get_results("SELECT DISTINCT post_author FROM $wpdb->posts");

    vc_map(
        array(
            'name' => esc_html__('Clever Posts Box', 'cvca'),
            'base' => 'CleverPostsBox',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display posts with pagination.', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Box title', 'cvca'),
                    'param_name' => 'title',
                    'admin_label' => true,
                ),
                array(
					'type' => 'dropdown',
					'heading' => esc_html__('Content type', 'cvca'),
					'param_name' => 'post_type',
					'value' => $post_types_list,
					'save_always' => true,
					'description' => esc_html__('Select a content type which you wish to display.', 'cvca'),
					'admin_label' => true,
				),
                array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include posts', 'cvca' ),
					'param_name' => 'post_slugs',
					'description' => esc_html__('Add specific posts to display.', 'cvca' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'groups' => true,
					),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array('slugs'),
					),
				),
                array(
					'type' => 'autocomplete',
					'heading' => __( 'Exclude posts', 'cvca' ),
					'param_name' => 'post_exclude',
					'description' => __( 'Specify posts which will be excluded from displaying.', 'cvca' ),
					'settings' => array(
						'multiple' => true,
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('slugs', 'custom'),
						'callback' => 'vc_grid_exclude_dependency_callback',
					),
				),
                array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Custom query', 'cvca' ),
					'param_name' => 'custom_query',
					'description' => esc_html__( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'cvca' ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array( 'custom' ),
					),
				),
                array(
					'type' => 'autocomplete',
					'heading' => esc_html__('Taxonomies', 'cvca'),
					'param_name' => 'taxonomies',
					'settings' => array(
                        'values' => $taxonomies_list,
						'multiple' => true,
						'min_length' => 1,
						'groups' => true,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 300,
						'auto_focus' => true,
					),
					'param_holder_class' => 'vc_not-for-custom',
					'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'cvca' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('slugs', 'custom'),
					),
                    'admin_label' => true,
				),
                array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order', 'cvca' ),
					'param_name' => 'order',
					'value' => array(
						esc_html__( 'Descending', 'cvca' ) => 'DESC',
						esc_html__( 'Ascending', 'cvca' ) => 'ASC',
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'description' => __( 'Select sorting order.', 'cvca' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('slugs', 'custom'),
					),
				),
                array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'cvca' ),
					'param_name' => 'order_by',
					'value' => array(
						esc_html__( 'Date', 'cvca' ) => 'date',
						esc_html__( 'Order by post ID', 'cvca' ) => 'ID',
						esc_html__( 'Author', 'cvca' ) => 'author',
						esc_html__( 'Title', 'cvca' ) => 'title',
						esc_html__( 'Last modified date', 'cvca' ) => 'modified',
						esc_html__( 'Parent ID', 'cvca' ) => 'parent',
						esc_html__( 'Number of comments', 'cvca' ) => 'comment_count',
						esc_html__( 'Menu order', 'cvca' ) => 'menu_order',
						esc_html__( 'Meta value', 'cvca' ) => 'meta_value',
						esc_html__( 'Meta value number', 'cvca' ) => 'meta_value_num',
						esc_html__( 'Random order', 'cvca' ) => 'rand',
					),
					'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'cvca' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('slugs','custom'),
					),
				),
                array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta key', 'cvca' ),
					'param_name' => 'meta_key',
					'description' => esc_html__( 'Input meta key for grid ordering.', 'cvca' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'order_by',
						'value' => array('meta_value', 'meta_value_num'),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Offset', 'cvca' ),
					'param_name' => 'post_offset',
					'description' => esc_html__( 'Number of posts to displace or pass over.', 'cvca' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('slugs', 'custom'),
					),
				),
                array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination Type', 'cvca' ),
					'param_name' => 'pagi_type',
					'value' => array(
						esc_html__( 'None', 'cvca' ) => 'all',
						esc_html__( 'Numeric', 'cvca' ) => 'numeric',
						esc_html__( 'Infinite Scroll', 'cvca' ) => 'scroll',
						esc_html__( 'Load More Button', 'cvca' ) => 'loadmore',
					),
                    'description' => esc_html__('"None" will display all queried posts without pagination.', 'cvca'),
                    'group' => esc_html__( 'Pagination', 'cvca' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array('custom'),
					),
					'edit_field_class' => 'vc_col-sm-6',
					'description' => __( 'Select display style for grid.', 'cvca' ),
				),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Load more button default label', 'cvca'),
                    'param_name' => 'default_label',
                    'value' => esc_html__('Load More', 'cvca'),
                    'group' => esc_html__( 'Pagination', 'cvca' ),
                    'dependency' => array(
                        'element' => 'pagi_type',
                        'value' => array('loadmore', 'scroll'),
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Load more button label when loading posts', 'cvca'),
                    'param_name' => 'loading_label',
                    'value' => esc_html__('Loading...', 'cvca'),
                    'group' => esc_html__( 'Pagination', 'cvca' ),
                    'dependency' => array(
                        'element' => 'pagi_type',
                        'value' => array('loadmore', 'scroll'),
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Load more button label when no more posts to load', 'cvca'),
                    'param_name' => 'nomore_label',
                    'value' => esc_html__('No More to Load', 'cvca'),
                    'group' => esc_html__( 'Pagination', 'cvca' ),
                    'dependency' => array(
                        'element' => 'pagi_type',
                        'value' => array('loadmore', 'scroll'),
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Scroll offset', 'cvca'),
                    'param_name' => 'scroll_offset',
                    'value' => 50,
                    'description' => esc_html__('The distance from the load more button to the scroll bar to trigger loading of posts. (Default is 50)'),
                    'group' => esc_html__( 'Pagination', 'cvca' ),
                    'dependency' => array(
                        'element' => 'pagi_type',
                        'value' => array('scroll'),
                    ),
                ),
                array(
					'type' => 'textfield',
					'heading' => __( 'Posts per Page', 'cvca' ),
					'param_name' => 'posts_per_page',
					'description' => __( 'Number of posts to show per page.', 'cvca' ),
					'value' => get_option('posts_per_page'),
					'dependency' => array(
						'element' => 'pagi_type',
						'value' => array('numeric', 'loadmore', 'scroll'),
					),
					'edit_field_class' => 'vc_col-sm-6',
				),
                array(
					'type' => 'checkbox',
					'heading' => __( 'Images Loaded', 'cvca' ),
					'param_name' => 'images_loaded',
					'value' => array( esc_html__( 'Yes', 'cvca' ) => 'yes' ),
					'description' => esc_html__( 'Whether to wait for post thumbnail to be loaded before display entire post or not.', 'cvca' ),
                    'group' => __( 'Pagination', 'cvca' ),
				),
                array(
					'type' => 'textfield',
					'heading' => __( 'Item CSS selector', 'cvca' ),
					'param_name' => 'item_class',
                    'group' => __( 'Pagination', 'cvca' ),
                    'description' => esc_html__('A CSS selector of each post. Used to add custom styles and animation.', 'cvca'),
                    'dependency' => array(
						'element' => 'pagi_type',
						'value' => array('loadmore', 'scroll'),
					),
				),
                array(
					'type' => 'textfield',
					'heading' => __( 'Container CSS selector', 'cvca' ),
					'param_name' => 'items_container',
                    'group' => __( 'Pagination', 'cvca' ),
                    'description' => esc_html__('A CSS selector for the container where new rendered posts will be appended to.', 'cvca'),
                    'dependency' => array(
						'element' => 'pagi_type',
						'value' => array('loadmore', 'scroll'),
					),
				),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Layout', 'cvca'),
                    'param_name' => 'layout',
                    'std' => 'grid',
                    'value' => array(
                        esc_html__('Grid', 'cvca' ) => 'grid',
                        esc_html__('List', 'cvca' ) => 'list',
                    ),
                    'group' => esc_html__('Design','cvca'),
                    'description' => esc_html__('Layout of rendered posts.', 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Custom template', 'cvca'),
                    'param_name' => 'template',
                    'admin_label' => true,
                    'description' => esc_html__('Enter a custom template (relative to your theme folder, e.g. "templates/masonry-posts-box.php") used to rendered queried posts.', 'cvca'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Columns', 'cvca'),
                    'param_name' => 'columns',
                    'std' => '1',
                    'value' => array(
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '6' => 6
                    ),
                    'dependency' => array('element' => 'layout', 'value' => array('grid')),
                    'group' => esc_html__('Design','cvca'),
                    'description' => esc_html__('Display posts with a specific number of columns.', 'cvca'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show excerpt', 'cvca'),
                    'param_name' => 'excerpt',
                    'group' => esc_html__('Design','cvca'),
                    'std' => 'yes',
                    'value' => array(esc_html__('Yes', 'cvca') => 'yes', esc_html__('No', 'cvca') => 'no'),
                    'description' => esc_html__('Whether to show "Read more" button for each post or not.', 'cvca'),
                ),
                array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Excerpt length', 'cvca' ),
					'param_name' => 'excerpt_length',
                    'group' => esc_html__( 'Design', 'cvca' ),
                    'value' => 40,
                    'dependency' => array(
						'element' => 'excerpt',
						'value' => array('yes'),
					),
				),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show "Read More" button', 'cvca'),
                    'param_name' => 'readmore',
                    'group' => esc_html__('Design','cvca'),
                    'std' => 'yes',
                    'value' => array(esc_html__('Yes', 'cvca') => 'yes', esc_html__('No', 'cvca') => 'no'),
                    'description' => esc_html__('Whether to show "Read more" button for each post or not.', 'cvca'),
                ),
                array(
                    'type' => 'animation_style',
                    'heading' => esc_html__('Animation stype', 'cvca'),
                    'param_name' => 'animation_stype',
                    'group' => esc_html__('Design','cvca'),
                    'admin_label' => true,
                    'description' => esc_html__('Select an animation type.', 'cvca'),
                    'dependency' => array(
						'element' => 'pagi_type',
						'value' => array('loadmore', 'scroll'),
					),
                ),
                array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation speed', 'cvca' ),
					'param_name' => 'animation_speed',
                    'group' => esc_html__( 'Design', 'cvca' ),
                    'value' => 300,
                    'dependency' => array(
						'element' => 'pagi_type',
						'value' => array('loadmore', 'scroll'),
					),
				),
                array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class to add to each post', 'cvca' ),
					'param_name' => 'el_class',
                    'group' => esc_html__( 'Design', 'cvca' ),
				),
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_posts_box_shortcode_with_vc', 10, 0 );
