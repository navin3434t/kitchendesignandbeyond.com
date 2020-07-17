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
function cvca_add_clever_demo_box_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverDemoBox_shortcode_atts', array(
            'type'=>'text',
            'style'=>'standard',
            'icon'=>'',
            'image' => '',
            'title' => '',
            'description' => '',
            'custom_icon'=>'',
            'box_shadow'=>'',
            'mask_color'=>'rgba(0,0,0,0.5)',
            'link' => '#',
            'animation_type'=>'none',
            'new_label'=>'',
            'hot_label'=>'',
            'coming_label'=>'',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverDemoBox'
    );

    $html = cvca_get_shortcode_view( 'demo-box', $atts, $content );

    return $html;
}
add_shortcode( 'CleverDemoBox', 'cvca_add_clever_demo_box_shortcode' );

/**
 * Integrate with Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_demo_box_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Demo Box', 'cvca'),
            'base' => 'CleverDemoBox',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Box demo. Display feature of themes, with images or icons', 'cvca'),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Type', 'cvca'),
                    'value' => array(
                        esc_html__('Text', 'cvca') => 'text',
                        esc_html__('Image', 'cvca') => 'image',
                    ),
                    'std' => 'text',
                    'param_name' => 'type',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Style', 'cvca'),
                    'value' => array(
                        esc_html__('Standard', 'cvca') => 'standard',
                        esc_html__('Inline', 'cvca') => 'inline',
                        esc_html__('Inline 2', 'cvca') => 'inline-2',
                    ),
                    'std' => 'standard',
                    'param_name' => 'style',
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__('Image', 'cvca'),
                    'value' => '',
                    'param_name' => 'image',
                    'description' => esc_html__('Image demo of box', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('image')),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Custom icon', 'cvca'),
                    'description' => esc_html__('Custom class of font icon', 'cvca'),
                    'value' => '',
                    "admin_label" => true,
                    'param_name' => 'custom_icon',
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => esc_html__('Icon Class', 'cvca'),
                    'value' => '',
                    'param_name' => 'icon',
                    'description' => esc_html__('Class of icon font icon (Awesome font, or CleverSoft font) you want use', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('text')),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    "admin_label" => true,
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'textarea',
                    'heading' => esc_html__('Description', 'cvca'),
                    'value' => '',
                    'param_name' => 'description',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Mask color', 'cvca'),
                    'value' => 'rgba(0,0,0,0.5)',
                    'param_name' => 'mask_color',
                    'description' => esc_html__('Mask color background for image. Work only with style Standard', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('image')),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Box shadow', 'cvca'),
                    'value' => 'true',
                    'std'=>'',
                    'param_name' => 'box_shadow',
                    'description' => esc_html__('If check, box shadow will visible.', 'cvca'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('New label', 'cvca'),
                    'value' => '',
                    'param_name' => 'new_label',
                    'description' => esc_html__('Work only with style Standard', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('image')),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Hot label', 'cvca'),
                    'value' => '',
                    'param_name' => 'hot_label',
                    'description' => esc_html__('Work only with style Standard', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('image')),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Coming soon label', 'cvca'),
                    'value' => '',
                    'param_name' => 'coming_label',
                    'description' => esc_html__('Work only with style Standard', 'cvca'),
                    'dependency' => Array('element' => 'type', 'value' => array('image')),
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => esc_html__('Link', 'cvca'),
                    'value' => '#',
                    'param_name' => 'link',
                ),
                array(
                    'type' => 'cvca_animation_type',
                    'heading' => esc_html__('Animation Style', 'cvca'),
                    'value' => 'none',
                    'param_name' => 'animation_type',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
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
add_action( 'vc_before_init', 'cvca_integrate_clever_demo_box_shortcode_with_vc', 10, 0 );
