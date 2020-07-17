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
function cvca_add_clever_video_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverVideo_shortcode_atts',array(
            'source_video'  => '',
            'title'         => '',
            'content'       => '',
            'thumbnail'     => '',
            'overlay_color' => '',
            'btn_color'     => '',
            'btn_bg_color'     => '',
            'width'         => '',
            'height'        => '',
            'el_class'      => '',
            'css'           => ''
        )),
        $atts, 'CleverVideo'
    );

    $html = cvca_get_shortcode_view( 'video', $atts, $content );

    return $html;
}
add_shortcode( 'CleverVideo', 'cvca_add_clever_video_shortcode' );


/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_video_shortcode_with_vc()
{
    vc_map(
        array(
            'name'        => esc_html__('Clever Video', 'cvca'),
            'base'        => 'CleverVideo',
            'icon'        => '',
            'category'    => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display banner items', 'cvca'),
            'params'      => array(
                array(
                    'type' => 'textfield',
                    "heading" => esc_html__( 'Video Link', 'cvca'),
                    'description' => '',
                    "param_name" => "source_video",
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Title', 'cvca' ),
                    'param_name' => 'title',
                    'description' => ''
                ),
                array(
                    'type' => 'textarea_html',
                    'holder' => 'div',
                    'class' => '',
                    'heading' => esc_html__( 'Content', 'cvca' ),
                    'param_name' => 'content',
                    'value' => '',
                    'description' => esc_html__( 'Enter your content.', 'cvca' )
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__( 'Video Thumbnail', 'cvca' ),
                    'param_name' => 'thumbnail',
                    'description' => ''
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Image overlay color', 'cvca'),
                    'value' => '',
                    'param_name' => 'overlay_color',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Button color', 'cvca'),
                    'value' => '',
                    'param_name' => 'btn_color',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Button background color', 'cvca'),
                    'value' => '',
                    'param_name' => 'btn_bg_color',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Video Popup width', 'cvca' ),
                    'param_name' => 'width',
                    'description' => esc_html__('Width of Video popup in desktop', 'cvca' )
                ), array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Video Popup height', 'cvca' ),
                    'param_name' => 'height',
                    'description' => esc_html__( 'Height of Video popup in desktop', 'cvca' )
                ),array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                ),
                // Design option tab
                array(
                    'type'       => 'css_editor',
                    'counterup'  => __( 'Css', 'cvca' ),
                    'param_name' => 'css',
                    'group'      => __( 'Design options', 'cvca' ),
                ),
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_video_shortcode_with_vc', 10, 0 );
