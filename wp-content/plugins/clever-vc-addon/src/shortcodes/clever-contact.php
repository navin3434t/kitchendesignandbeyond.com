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
function cvca_add_clever_contact_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverContact_shortcode_atts', array(
            'image'    => '',
            'title'    =>'',
            'style'    =>'style-1',
            'contact'  =>'',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverContact'
    );

    $html = cvca_get_shortcode_view( 'contact', $atts, $content );

    return $html;
}
add_shortcode( 'CleverContact', 'cvca_add_clever_contact_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_contact_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Contact', 'cvca'),
            'base' => 'CleverContact',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display your contact with icon', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca' ),
                    'param_name' => 'title',
                    'description' => esc_html__('', 'cvca'),
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__( 'Image', 'cvca' ),
                    'param_name' => 'image',
                    'description' => esc_html__( 'Image of contact', 'cvca' )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Style", 'cvca'),
                    "param_name" => "style",
                    'std' => 'style-1',
                    "value" => array(
                        esc_html__('Style 1', 'cvca' ) => 'style-1',
                        esc_html__('Style 2', 'cvca' ) => 'style-2',
                    ),
                ),
                array(
                    "type" => "param_group",
                    "heading" => esc_html__("Contact info", 'cvca'),
                    'value' => '',
                    'param_name' => 'contact',
                    'description' => esc_html__('Click to show more options, and starting add content.', 'cvca'),
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Icon', 'cvca' ),
                            'param_name' => 'icon',
                            'description' => esc_html__('Put class font icon you use.', 'cvca'),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => esc_html__('Text', 'cvca' ),
                            'param_name' => 'text_val',
                            'description' => esc_html__('', 'cvca'),
                        ),
                    )
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
add_action( 'vc_before_init', 'cvca_integrate_clever_contact_shortcode_with_vc', 10, 0 );
