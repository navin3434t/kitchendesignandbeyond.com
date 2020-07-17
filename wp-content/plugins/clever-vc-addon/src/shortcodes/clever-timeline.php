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
function cvca_add_clever_timeline_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverTimeline_shortcode_atts',array(
            'title'     => '',
            'time'      => '',
            'timelines' => '',
            'el_class'  => '',
            'css'  => '',
        )),
        $atts, 'CleverTimeline'
    );

    $html = cvca_get_shortcode_view( 'timeline', $atts, $content );

    return $html;
}
add_shortcode( 'CleverTimeline', 'cvca_add_clever_timeline_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_timeline_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Timeline', 'cvca'),
            'base' => 'CleverTimeline',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Time line block', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    'param_name' => 'title',
                    'admin_label'=>true
                ),
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'param_name' => 'timelines',
                    'description' => esc_html__('Click arrow to show text field', 'cvca'),
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'value' => '',
                            'heading' => esc_html__('Date', 'cvca'),
                            'param_name' => 'date-block',
                        ),
                        array(
                            'type' => 'textfield',
                            'value' => '',
                            'heading' => esc_html__('Title time line', 'cvca'),
                            'param_name' => 'title-block',
                        ),
                        array(
                            'type' => 'textarea',
                            'value' => '',
                            'heading' => esc_html__('Description', 'cvca'),
                            'param_name' => 'des-block',
                        )
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
add_action( 'vc_before_init', 'cvca_integrate_clever_timeline_shortcode_with_vc', 10, 0 );
