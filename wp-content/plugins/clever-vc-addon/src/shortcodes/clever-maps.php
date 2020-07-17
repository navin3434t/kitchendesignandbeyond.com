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
function cvca_add_clever_maps_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverMaps_shortcode_atts',array(
            'key'                => 'AIzaSyDAUtmybJLXT4re2UPh9-1-S3ZBUMyWi_s',
            'title'              => '',
            'latitude'           => '40.712775',
            'longitude'          => '-74.005973',
            'zoom'               => '',
            'icon'               => '',
            'scroll_wheel'       => '',
            'height'             => '',
            'style'              => '',
            'el_class'           => '',
            'css'                => ''
        )),
        $atts, 'CleverMaps'
    );

    $html = cvca_get_shortcode_view( 'maps', $atts, $content );

    return $html;
}
add_shortcode( 'CleverMaps', 'cvca_add_clever_maps_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_maps_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Maps', 'cvca'),
            'base' => 'CleverMaps',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display maps items', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Google map key', 'cvca' ),
                    'param_name' => 'title',
                    'description' => esc_html__('To get google map key. Go to: https://developers.google.com/maps/documentation/javascript/get-api-key', 'cvca'),
                    'value' => 'AIzaSyDAUtmybJLXT4re2UPh9-1-S3ZBUMyWi_s',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca' ),
                    'param_name' => 'title',
                    'description' => esc_html__('', 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Latitude', 'cvca'),
                    'value' => '40.712775',
                    'param_name' => 'latitude',
                    "description" => esc_html__( 'Visit ' , 'cvca' ) .'<a href="https://www.latlong.net/" target="_blank">Get Latitude and Longitude</a>'. esc_html( " enter Place Name and copy Latitude here", 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Longitude', 'cvca'),
                    'value' => '-74.005973',
                    'param_name' => 'longitude',
                    "description" => esc_html__( 'Visit ' , 'cvca' ) .'<a href="https://www.latlong.net/" target="_blank">Get Latitude and Longitude</a>'. esc_html( " enter Place Name and copy Latitude here", 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Zoom', 'cvca'),
                    'value' => '',
                    'param_name' => 'zoom',
                    "description" => ''
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__('Icon Marker', 'cvca'),
                    'value' => '',
                    'param_name' => 'icon',
                    "description" => ''
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Scroll Wheel", 'cvca'),
                    "param_name" => "scroll_wheel",
                    'std' => false,
                    "value" => array(
                        esc_html__('No', 'cvca' ) => false,
                        esc_html__('Yes', 'cvca' ) => true,
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Height (px)', 'cvca' ),
                    'param_name' => 'height',
                    'description' => esc_html__( 'Height of mapper.', 'cvca' )
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Style", 'cvca'),
                    "param_name" => "style",
                    'std' => 'default',
                    "value" => array(
                        esc_html__('Default', 'cvca' ) => 'default',
                        esc_html__('Ultra Light with Labels', 'cvca' ) => '1',
                        esc_html__('Shades of Grey', 'cvca' ) => '2',
                        esc_html__('Apple Maps-esque', 'cvca' ) => '3',
                        esc_html__('Pale Dawn', 'cvca' ) => '4',
                        esc_html__('Yellow water', 'cvca' ) => '5',
                    )
                ),
                array(
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
add_action( 'vc_before_init', 'cvca_integrate_clever_maps_shortcode_with_vc', 10, 0 );
