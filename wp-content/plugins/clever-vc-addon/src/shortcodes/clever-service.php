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
function cvca_add_clever_service_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverService_shortcode_atts',array(
            'title' => '',
            'columns' => 3,
            'post_in' => '',
            'number' => 8,
            'layout' => 'grid',
            'style' => 'style-1',
            'set_height'=>'',
            'height'=>'500',
            'blog_img_size'=>'medium',
            'pagination'=>'standard',
            'output_type'=>'no',
            'excerpt_length'=>40,
            'view_more' => false,
            'el_class' => ''
        )),
        $atts, 'CleverService'
    );

    $html = cvca_get_shortcode_view( 'service', $atts, $content );

    return $html;
}
add_shortcode( 'CleverService', 'cvca_add_clever_service_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_service_shortcode_with_vc()
{
    vc_map(array(
        'name' => esc_html__('Clever Service', 'cvca'),
        'base' => 'CleverService',
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
                "admin_label" => true,
                'group'=>'layout',
                "value" => array(
                    esc_html__('Grid', 'cvca' ) => 'grid',
                    esc_html__('Carousel', 'cvca' ) => 'carousel',
                ),
                'description' => esc_html__('Layout for display Services', 'cvca'),
            ),array(
                "type" => "dropdown",
                "heading" => esc_html__("Style", 'cvca'),
                "param_name" => "style",
                'std' => 'style-1',
                "admin_label" => true,
                'group'=>'layout',
                "value" => array(
                    esc_html__('Style 1', 'cvca' ) => 'style-1',
                    esc_html__('Style 2', 'cvca' ) => 'style-2',
                ),
                'description' => esc_html__('Style for display', 'cvca'),
            ), array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns", 'cvca'),
                "param_name" => "columns",
                'std' => '3',
                'group'=>'layout',
                "value" => array(
                    esc_html__('1', 'cvca' ) => 1,
                    esc_html__('2', 'cvca' ) => 2,
                    esc_html__('3', 'cvca' ) => 3,
                    esc_html__('4', 'cvca' ) => 4,
                    esc_html__('6', 'cvca' ) => 6
                ),
                'description' => esc_html__('Display post with the number of column', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Post IDs", 'cvca'),
                "description" => esc_html__("comma separated list of post ids", 'cvca'),
                "param_name" => "post_in"
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Posts Count", 'cvca'),
                "param_name" => "number",
                "value" => '8',
                'description' => esc_html__('Number of post showing', 'cvca'),
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Set Height", 'cvca'),
                'param_name' => 'set_height',
                'group'=>'layout',
                'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                'dependency' => array('element' => 'layout', 'value' => 'carousel'),
                'description' => esc_html__('Yes, If you want fix height for carousel block. if not check height will follow image size.', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Carousel Height", 'cvca'),
                "param_name" => "height",
                'group'=>'layout',
                "value" => '500',
                'description' => esc_html__('Height for item of carousel', 'cvca'),
                'dependency' => array('element' => 'set_height', 'value' => 'yes'),
            ),
            array(
                'type' => 'cvca_image_size',
                'heading' => esc_html__('Image size', 'cvca'),
                'group'=>'layout',
                'param_name' => 'blog_img_size',
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Pagination", 'cvca'),
                "param_name" => "pagination",
                'std' => 'standard',
                "value" => array(
                    esc_html__('Standard', 'cvca' ) => 'standard',
                    esc_html__('None', 'cvca' ) => 'none',
                ),
                'dependency' => array('element' => 'layout', 'value' => 'grid'),
                'description' => esc_html__('Select pagination type', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Content display", 'cvca'),
                "param_name" => "output_type",
                'std' => 1,
                "value" => array(
                    esc_html__('None', 'cvca' ) => 'no',
                    esc_html__('Excerpt', 'cvca' ) => 'excerpt',
                    esc_html__('Full content', 'cvca' ) => 'content',
                ),
                'description' => esc_html__('Select type of content', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Excerpt lenght", 'cvca'),
                "param_name" => "excerpt_length",
                'dependency' => Array('element' => 'output_type', 'value' => array('excerpt')),
                "description" => esc_html__("Total character display of excerpt.", 'cvca'),
                "value" => '40'
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__("Show View More", 'cvca'),
                'param_name' => 'view_more',
                'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                'description' => esc_html__('Yes, If you want to show button "Read more"', 'cvca'),
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Extra class name', 'cvca' ),
                'param_name' => 'el_class',
                "admin_label" => true,
                'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
            )
        )
    ));
}
add_action( 'vc_before_init', 'cvca_integrate_clever_service_shortcode_with_vc', 10, 0 );
