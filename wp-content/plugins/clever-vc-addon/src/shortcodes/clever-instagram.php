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
function cvca_add_clever_instagram_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverInstagram_shortcode_atts', array(
            'image'         => '',
            'username'      => '',
            'title'         => '',
            'number'        => '6',
            'columns'       => '6',
            'center_mode'   => '',
            'center_padding'   => '',
            'img_size'      => 'small',
            'show_likes'    => '1',
            'show_comments' => '1',
            'show_type'     => '1',
            'show_time'    => '1',
            'time_layout'   => 'elapsed',
            'date_format'   => '',
            'show_pag'      => '',
            'show_nav'      => '1',
            'link_target'   => '_blank',
            'link_text'     => '',
            'el_class'      => '',
            'css'           => ''
        )),
        $atts, 'CleverInstagram'
    );

    $html = cvca_get_shortcode_view( 'instagram', $atts, $content );

    return $html;
}
add_shortcode( 'CleverInstagram', 'cvca_add_clever_instagram_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_instagram_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Instagram', 'cvca'),
            'base' => 'CleverInstagram',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display your instagram.', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca' ),
                    'param_name' => 'title',
                    'description' => esc_html__('', 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Username', 'cvca' ),
                    'param_name' => 'username',
                    'description' => esc_html__('', 'cvca'),
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Photos count", 'cvca'),
                    "param_name" => "number",
                    "value" => '6',
                    'description' => esc_html__('Number of photos showing', 'cvca'),
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Columns", 'cvca'),
                    "param_name" => "columns",
                    "value" => '6',
                    'description' => esc_html__('Display photos with the number of column', 'cvca'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Center Mode', 'cvca'),
                    'param_name' => 'center_mode',
                    'std' => '',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Center Padding", 'cvca'),
                    "param_name" => "center_padding",
                    "value" => '',
                    'dependency' => array('element' => 'center_mode', 'value' => array('1')),
                    'description' => esc_html__('Padding of items in carousel', 'cvca'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Image size', 'cvca' ),
                    'param_name' => 'img_size',
                    'std' => 'small',
                    'value' => array(
                        esc_html__('Thumbnail', 'cvca' )    => 'thumbnail',
                        esc_html__('Small', 'cvca' )        => 'small',
                        esc_html__('Large', 'cvca' )        => 'large',
                        esc_html__('Original', 'cvca' )     => 'original',
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show likes count', 'cvca'),
                    'param_name' => 'show_likes',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show comments count', 'cvca'),
                    'param_name' => 'show_comments',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show icons type( image/video )', 'cvca'),
                    'param_name' => 'show_type',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show time', 'cvca'),
                    'param_name' => 'show_time',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Time layout', 'cvca' ),
                    'param_name' => 'time_layout',
                    'std' => 'elapsed',
                    'value' => array(
                        esc_html__('Time elapsed', 'cvca' )     => 'elapsed',
                        esc_html__('Date', 'cvca' )             => 'date',
                    ),
                    'dependency' => Array('element' => 'show_time', 'value' => array('1')),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Date format', 'cvca' ),
                    'param_name' => 'date_format',
                    'description' => esc_html__('Ex: F j, Y .Note: Default is date format in WordPress settings if this value is blank.', 'cvca'),
                    'dependency' => Array('element' => 'time_layout', 'value' => array('date')),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show carousel pagination', 'cvca'),
                    'param_name' => 'show_pag',
                    'std' => '',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show carousel navigation', 'cvca'),
                    'param_name' => 'show_nav',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Target links', 'cvca'),
                    'param_name' => 'link_target',
                    'std' => '_blank',
                    'value' => array(
                        esc_html__('Current window (_self)', 'cvca' )      => '_self',
                        esc_html__('New window (_blank)', 'cvca' )     => '_blank',
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Link text', 'cvca' ),
                    'param_name' => 'link_text',
                    'description' => esc_html__('', 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'cvca' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'cvca' ),
                ),
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_instagram_shortcode_with_vc', 10, 0 );
