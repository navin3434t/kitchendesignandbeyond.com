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

if (class_exists('Tribe__Events__Main')) {
    function cvca_add_clever_events_shortcode( $atts, $content = null )
    {
        $atts = shortcode_atts(
            apply_filters('CleverEvents_shortcode_atts',array(
                'title'         => '',
                'items'         => '3',
                'show_image'    => 'yes',
                'show_date'     => 'start',
                'show_time'     => 'yes',
                'show_category' => 'yes',
                'show_desc'     => 'yes',
                'desc_length'   => '12',
                'show_view_all' => 'yes',
                'el_class'      => '',
                'css'           => ''
            )),
            $atts, 'CleverEvents'
        );

        $html = cvca_get_shortcode_view( 'events', $atts, $content );

        return $html;
    }
    add_shortcode( 'CleverEvents', 'cvca_add_clever_events_shortcode' );

    /**
     * Integrate to Visual Composer
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     */
    function cvca_integrate_clever_events_shortcode_with_vc()
    {
        vc_map(
            array(
                'name' => esc_html__('Clever Events', 'cvca'),
                'base' => 'CleverEvents',
                'icon' => '',
                'category' => esc_html__('CleverSoft', 'cvca'),
                'description' => esc_html__('Display your banner image', 'cvca'),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'cvca' ),
                        'param_name' => 'title',
                        'description' => esc_html__('', 'cvca'),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Items show', 'cvca' ),
                        'param_name' => 'items',
                        'std'        => '3',
                        'description' => esc_html__('', 'cvca'),
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => esc_html__('Show date', 'cvca'),
                        'param_name'    => 'show_date',
                        'value'         => array(
                            esc_html__('None', 'cvca' ) => 'none',
                            esc_html__('Start date', 'cvca' ) => 'start',
                            esc_html__('End date', 'cvca' ) => 'end',
                        ),
                        'description' => esc_html__('Show date is start or end date.', 'cvca'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Image", 'cvca'),
                        'param_name' => 'show_image',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Time", 'cvca'),
                        'param_name' => 'show_time',
                        'std' => '',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Category", 'cvca'),
                        'param_name' => 'show_category',
                        'std' => '',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Course Description", 'cvca'),
                        'param_name' => 'show_desc',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__('Course Description Length', 'cvca'),
                        'param_name'    => 'desc_length',
                        'value'         => '7',
                        'dependency' => array('element' => 'show_desc', 'value' => array('yes')),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show View All Link", 'cvca'),
                        'param_name' => 'show_view_all',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
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
    add_action( 'vc_before_init', 'cvca_integrate_clever_events_shortcode_with_vc', 10, 0 );
}
