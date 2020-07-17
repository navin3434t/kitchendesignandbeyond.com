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
function cvca_add_clever_banner_slider_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverBannerSlider_shortcode_atts',array(
            'show_pag' => '',
            'show_nav' => '',
            'columns'   => '1',
            'banners'    => '',
            'el_class'  => ''
        )),
        $atts, 'CleverBannerSlider'
    );

    $html = cvca_get_shortcode_view( 'banner-slider', $atts, $content );

    return $html;
}
add_shortcode( 'CleverBannerSlider', 'cvca_add_clever_banner_slider_shortcode' );


/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_banner_slider_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Banner Slider', 'cvca'),
            'base' => 'CleverBannerSlider',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Simple banner slider', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Columns', 'cvca'),
                    'description' => esc_html__('Number columns of layout', 'cvca'),
                    'std' => '1',
                    'param_name' => 'columns',
                    "admin_label" => true,
                ),
                array(
                    "type" => "param_group",
                    "heading" => esc_html__("Banners", 'cvca'),
                    'value' => '',
                    'param_name' => 'banners',
                    'description' => esc_html__('Click to show more options, and starting add content.', 'cvca'),
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__('Image', 'cvca'),
                            'value' => '',
                            'param_name' => 'image',
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => esc_html__( 'Link', 'cvca' ),
                            'param_name' => 'link',
                            'description' => esc_html__( 'Link of Image', 'cvca' ),
                            'std'=>''
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__( 'Title', 'cvca' ),
                            'param_name' => 'title',
                            'description' => esc_html__( 'Primary content', 'cvca' ),
                            'std'=>''
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__( 'Descriptions', 'cvca' ),
                            'param_name' => 'desc',
                            'description' => esc_html__( 'description content', 'cvca' ),
                            'std'=>''
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Box Background Color', 'cvca'),
                            'description' => esc_html__( 'Background of block text content', 'cvca' ),
                            'value' => '',
                            'param_name' => 'bg_color',
                            'std'=>''
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Text Color', 'cvca'),
                            'description' => esc_html__( 'Color of text content', 'cvca' ),
                            'value' => '#fff',
                            'param_name' => 'text_color',
                        ),
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show pagination', 'cvca'),
                    'param_name' => 'show_pag',
                    'description' => esc_html__('If check, pagination of gallery will show', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show navigation', 'cvca'),
                    'param_name' => 'show_nav',
                    'description' => esc_html__('If check, navigation of gallery will show', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'cvca'),
                    'param_name' => 'el_class',
                    'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca')
                )
                )
            )
        );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_banner_slider_shortcode_with_vc', 10, 0 );
