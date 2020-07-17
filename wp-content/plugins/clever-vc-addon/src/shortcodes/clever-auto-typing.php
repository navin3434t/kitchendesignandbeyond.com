<?php
/**
 * cvca_add_auto_typing_shortcode
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array    $atts    Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_auto_typing_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverAutoTyping_shortcode_atts', array(
            'fixed-text'       =>'',
            'text'             => '',
            'font-size'        => '',
            'text-transform'   => '',
            'typeSpeed'        => 100,
            'delay_time'       => 0,
            'fixed_text_color' =>'#000',
            'text_color'       =>'#000',
            'show_cursor'      =>'yes',
            'el_class'         => '',
            'css'         => ''
        )),
        $atts, 'CleverAutoTyping'
    );

    $html = cvca_get_shortcode_view( 'auto-typing', $atts, $content );

    return $html;
}
add_shortcode( 'CleverAutoTyping', 'cvca_add_clever_auto_typing_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_auto_typing_shortcode_with_vc()
{
    vc_map( array(
        'name'     => esc_html__('Clever Auto Typing', 'cvca'),
        'base'     => 'CleverAutoTyping',
        'category' => esc_html__('CleverSoft', 'cvca'),
        'icon'     => '',
        'params'   => array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Fixed text', 'cvca' ),
                'param_name' => 'fixed-text',
                'description' => esc_html__( 'This text is fixed, not has effect.', 'cvca' )
            ),
            array(
                "type" => "param_group",
                "heading" => esc_html__("Text", 'cvca'),
                'value' => '',
                'param_name' => 'text',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'value' => '',
                        'heading' => esc_html__('Text Item', 'cvca'),
                        'param_name' => 'text-item',
                    ),
                )
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Font size', 'cvca' ),
                'param_name' => 'font-size',
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Apply only number.', 'cvca' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Text transform', 'cvca' ),
                'param_name' => 'text-transform',
                "value" => array(
                    esc_html__('None', 'cvca' ) => 'none',
                    esc_html__('Uppercase', 'cvca' ) => 'Uppercase',
                    esc_html__('Lowercase', 'cvca' ) => 'Lowercase',
                    esc_html__('Inherit', 'cvca' ) => 'Inherit',
                    esc_html__('Full width', 'cvca' ) => 'full-width',
                    esc_html__('Capitalize', 'cvca' ) => 'capitalize',
                ),
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Text transform style.', 'cvca' )
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Type Speed', 'cvca' ),
                'param_name' => 'typeSpeed',
                'std'=>'100',
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Apply only number.', 'cvca' )
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Delay time', 'cvca' ),
                'param_name' => 'delay_time',
                'std'=>'0',
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Apply only number.', 'cvca' )
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__( 'Fixed text color', 'cvca' ),
                'param_name' => 'fixed_text_color',
                'std'=>'#000',
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Color of fixed text.', 'cvca' )
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__( 'Text color', 'cvca' ),
                'param_name' => 'text_color',
                'std'=>'#000',
                'edit_field_class'=>'vc_col-xs-6',
                'description' => esc_html__( 'Color of text.', 'cvca' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_html__( 'Show Cursor', 'cvca' ),
                'param_name' => 'show_cursor',
                'std'=>'yes',
                'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                'edit_field_class'=>'vc_col-xs-6'
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
    ) );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_auto_typing_shortcode_with_vc', 10, 0 );
