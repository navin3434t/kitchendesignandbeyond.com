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
function cvca_add_clever_countdown_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverCountDown_shortcode_atts', array(
            'date'     => '0',
            'hour'     => '0',
            'min'      => '0',
            'sec'      => '0',
            'link'     => '0',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverCountDown'
    );

    $html = cvca_get_shortcode_view( 'countdown', $atts, $content );

    return $html;
}
add_shortcode( 'CleverCountDown', 'cvca_add_clever_countdown_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_countdown_shortcode_with_vc()
{
    vc_map(array(
        'name' => esc_html__('Clever CountDown', 'cvca'),
        'base' => 'CleverCountDown',
        'category' => esc_html__('CleverSoft', 'cvca'),
        'icon' => '',
        "params" => array(
            array(
                'type' => 'cvca_datepicker',
                'heading' => esc_html__('Date', 'cvca'),
                'param_name' => 'date',
                'admin_panel' => true,
                'description' => esc_html__('Enter only number. Date End countdown', 'cvca')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Hour', 'cvca'),
                'param_name' => 'hour',
                'std'=>0,
                'admin_panel' => true,
                'description' => esc_html__('Hour End countdown', 'cvca')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Minutes', 'cvca'),
                'param_name' => 'min',
                'std'=>0,
                'admin_panel' => true,
                'description' => esc_html__('Minutes End countdown', 'cvca')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Second', 'cvca'),
                'param_name' => 'sec',
                'std'=>0,
                'admin_panel' => true,
                'description' => esc_html__('Second End countdown', 'cvca')
            ),
            array(
                'type' => 'vc_link',
                'heading' => esc_html__('Link', 'cvca'),
                'param_name' => 'link',
                'edit_field_class' => 'vc_col-xs-6',
                'description' => esc_html__('Apply only number.', 'cvca')
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Extra class name', 'cvca'),
                'param_name' => 'el_class',
                'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca')
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
add_action( 'vc_before_init', 'cvca_integrate_clever_countdown_shortcode_with_vc', 10, 0 );
