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
function cvca_add_clever_parallax_box_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverParallaxBox_shortcode_atts',array(
			'add_nav'   =>'',
			'title_nav' =>'',
			'img_bg'    =>'',
			'offset'     =>'-40',
			'layout'    =>'normal',
			'container'    =>'',
            'css'=>''
        )),
        $atts, 'CleverParallaxBox'
    );

    $html = cvca_get_shortcode_view( 'parallax-box', $atts, $content );

    return $html;
}
add_shortcode( 'CleverParallaxBox', 'cvca_add_clever_parallax_box_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_parallax_box_shortcode_with_vc()
{
	vc_map(
		array(
			'name' => esc_html__('Clever Parallax Box', 'cvca'),
			'base' => 'CleverParallaxBox',
			'icon' => '',
			'is_container' => true,
			'js_view'      => 'VcColumnView',
			'category' => esc_html__('CleverSoft', 'cvca'),
			'description' => esc_html__('Box demo. Display feature of themes, with images or icons', 'cvca'),
			'params' => array(
				array(
					'type' => 'checkbox',
					'heading' => esc_html__("Add to Parallax navigation", 'cvca'),
					'param_name' => 'add_nav',
					'std' => '',
					'value' => array(esc_html__('Yes', 'cvca') => '1'),
				),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Keep content in container", 'cvca'),
                    'param_name' => 'container',
                    'std' => '',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Title", 'cvca'),
					"param_name" => "title_nav",
					'dependency' => array('element' => 'add_nav', 'value' => array('1')),
					"description" => esc_html__("Title display at navigation.", 'cvca'),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__('Image Background', 'cvca'),
					'std' => '',
					'param_name' => 'img_bg',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Offset', 'cvca'),
					'std' => '-40',
					'param_name' => 'offset',
					'description' => esc_html__('Accept only negative value', 'cvca'),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Layout", 'cvca'),
					"param_name" => "layout",
					'std' => 'normal',
					"value" => array(
						esc_html__('Normal', 'cvca' ) => 'normal',
						esc_html__('On Screen', 'cvca' ) => 'on-screen',
						esc_html__('Full Screen', 'cvca' ) => 'full-screen',
					)
				),
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
add_action( 'vc_before_init', 'cvca_integrate_clever_parallax_box_shortcode_with_vc', 10, 0 );
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_CleverParallaxBox extends WPBakeryShortCodesContainer {
    }
}