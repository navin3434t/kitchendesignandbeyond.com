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
function cvca_add_clever_countup_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverCountUp_shortcode_atts', array(
            'start_number' => '0',
            'end_number' => '0',
            'text_color'=>'#7d7d7d',
            'number_color'=>'#00aeef',
            'decimals'=>'0',
            'duration'=>'0',
            'title' => '',
            'title_size' => '15',
            'number_size' => '30',
            'el_class' => '',
            'css' => '',
        )),
        $atts, 'CleverCountUp'
    );

    $html = cvca_get_shortcode_view( 'countup', $atts, $content );

    return $html;
}
add_shortcode( 'CleverCountUp', 'cvca_add_clever_countup_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_countup_shortcode_with_vc()
{
    vc_map(array(
        'name' => esc_html__('Clever CountUp', 'cvca'),
        'base' => 'CleverCountUp',
        'category' => esc_html__('CleverSoft', 'cvca'),
        'icon' => '',
        "params" => array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Title', 'cvca'),
                'value' => '',
                'param_name' => 'title',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Font size title', 'cvca'),
                'value' => '15',
                'param_name' => 'title_size',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Font size number', 'cvca'),
                'value' => '30',
                'param_name' => 'number_size',
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Text color', 'cvca'),
                'std'=>'#7d7d7d',
                'param_name' => 'text_color',
                'value' => '',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Number color', 'cvca'),
                'std'=>'#00aeef',
                'param_name' => 'number_color',
                'value' => '',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Star number', 'cvca'),
                'std'=>0,
                'value' => '',
                'param_name' => 'start_number',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('End number', 'cvca'),
                'std'=>0,
                'value' => '',
                'param_name' => 'end_number',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Decimals', 'cvca'),
                'std'=>0,
                'value' => '',
                'param_name' => 'decimals',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Duration', 'cvca'),
                'std'=>0,
                'value' => '',
                'param_name' => 'duration',
                'edit_field_class'=>'vc_col-xs-6 vc_column-with-padding '
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
    ));
}
add_action( 'vc_before_init', 'cvca_integrate_clever_countup_shortcode_with_vc', 10, 0 );
