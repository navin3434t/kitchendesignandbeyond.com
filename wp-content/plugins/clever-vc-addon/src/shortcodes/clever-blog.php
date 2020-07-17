<?php

/**
 * Get posts
 */
function cvca_get_blog_posts_data()
{
    $args = array(
        'suppress_filters' => true,
        'posts_per_page' => -1,
        'no_found_rows' => true,
    );
    $the_query = new \WP_Query($args);
    $results = array();

    if ($the_query->have_posts()):
        while ($the_query->have_posts()): $the_query->the_post();
            $data = array();
            $data['value'] = get_the_ID();
            $data['label'] = get_the_title();
            $results[] = $data;
        endwhile;
    endif;

    wp_reset_postdata();

    return $results;
}

/**
 * Add shortcode
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array    $atts    Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_blog_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverBlog_shortcode_atts', array(
            'title' => '',
            'layout'=> 'grid',
            'columns' => 1,
            'cat' => '',
            'parent'=>1,
            'post_in' => '',
            'number' => 8,
            'blog_img_size'=>'medium',
            'pagination'=>'none',
            'output_type'=>'yes',
            'post_info'=>'yes',
            'excerpt_length'=>40,
            'view_more' => 'yes',
            'animation_type' => '',
            'el_class' => ''
        )),
        $atts, 'CleverBlog'
    );

    $html = cvca_get_shortcode_view( 'blog', $atts, $content );

    return $html;
}
add_shortcode( 'CleverBlog', 'cvca_add_clever_blog_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_blog_shortcode_with_vc()
{
    vc_map(array(
        'name' => esc_html__('Clever Blog', 'cvca'),
        'base' => 'CleverBlog',
        'category' => esc_html__('CleverSoft', 'cvca'),
        'icon' => '',
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title", 'cvca'),
                "param_name" => "title",
                "admin_label" => true,
                'description' => esc_html__('Enter text used as shortcode title (Note: located above content element)', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout", 'cvca'),
                "param_name" => "layout",
                'std' => 'grid',
                "value" => array(
                    esc_html__('Grid', 'cvca' ) => 'grid',
                    esc_html__('List', 'cvca' ) => 'list',
                ),
                'group'=>esc_html__('Layout','cvca'),
                'description' => esc_html__('Layout of posts', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns", 'cvca'),
                "param_name" => "columns",
                'std' => '1',
                "value" => array(
                    esc_html__('1', 'cvca' ) => 1,
                    esc_html__('2', 'cvca' ) => 2,
                    esc_html__('3', 'cvca' ) => 3,
                    esc_html__('4', 'cvca' ) => 4,
                    esc_html__('6', 'cvca' ) => 6
                ),
                'dependency' => array('element' => 'layout', 'value' => array('grid','grid-no-thumb')),
                'group'=>esc_html__('Layout','cvca'),
                'description' => esc_html__('Display post with the number of column', 'cvca'),
            ),
            array(
                "type" => "cvca_post_categories",
                "heading" => esc_html__("Category IDs", 'cvca'),
                "param_name" => "cat",
                "admin_label" => true,
                'description' => esc_html__('Select category which you want to get post in', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Get posts in children of categories", 'cvca'),
                "param_name" => "parent",
                'std' => 1,
                "value" => array(
                    esc_html__('No', 'cvca' ) => 0,
                    esc_html__('Yes', 'cvca' ) => 1,
                ),
                'description' => esc_html__('Yes, If you want to get post in all children categories', 'cvca'),
            ),
            array(
                "type" => "autocomplete",
                "heading" => esc_html__("Post IDs", 'cvca'),
                "description" => esc_html__("comma separated list of post ids", 'cvca'),
                "param_name" => "post_in",
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                    'min_length' => 0,
                    'no_hide' => true, // In UI after select doesn't hide an select list
                    'groups' => true, // In UI show results grouped by groups
                    'unique_values' => true, // 0In UI show results except selected. NB! You should manually check values in backend
                    'display_inline' => true, // In UI show results inline view
                    'values' => cvca_get_blog_posts_data(),
                ),
            ),
            array(
                'type' => 'cvca_image_size',
                'heading' => esc_html__('Image size', 'cvca'),
                'group'=>esc_html__('Layout','cvca'),
                'std' => 'medium',
                'param_name' => 'blog_img_size',
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Posts Count", 'cvca'),
                "param_name" => "number",
                "value" => '8',
                'description' => esc_html__('Number of post showing', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Pagination", 'cvca'),
                "param_name" => "pagination",
                'std' => 'none',
                "value" => array(
                    esc_html__('Standard', 'cvca' ) => 'standard',
                    esc_html__('None', 'cvca' ) => 'none',
                ),
                'description' => esc_html__('Select pagination type', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Content display", 'cvca'),
                "param_name" => "output_type",
                'std' => 1,
                'group'=>esc_html__('Layout','cvca'),
                "value" => array(
                    esc_html__('None', 'cvca' ) => 'no',
                    esc_html__('Excerpt', 'cvca' ) => 'excerpt',
                    esc_html__('Full content', 'cvca' ) => 'content',
                ),
                'description' => esc_html__('Select type of content', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Excerpt length", 'cvca'),
                "param_name" => "excerpt_length",
                'group'=>esc_html__('Layout','cvca'),
                'dependency' => array('element' => 'output_type', 'value' => array('excerpt')),
                "description" => esc_html__("Total character display of excerpt.", 'cvca'),
                "value" => '40'
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Show Post information", 'cvca'),
                'param_name' => 'post_info',
                'group'=>esc_html__('Layout','cvca'),
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                'description' => esc_html__('Show category and date post', 'cvca'),
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Show View More", 'cvca'),
                'param_name' => 'view_more',
                'group'=>esc_html__('Layout','cvca'),
                'std' => 'yes',
                'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                'description' => esc_html__('Yes, If you want to show button "Read more"', 'cvca'),
            ),
            array(
                "type" => 'cvca_animation_type',
                "heading" => esc_html__("Animation Type", 'cvca'),
                "param_name" => "animation_type",
                'group'=>esc_html__('Layout','cvca'),
                "admin_label" => true,
                'description' => esc_html__('Select animation type', 'cvca'),
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Extra class name', 'cvca' ),
                'param_name' => 'el_class',
                'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
            )
        )
    ));
}
add_action( 'vc_before_init', 'cvca_integrate_clever_blog_shortcode_with_vc', 10, 0 );
