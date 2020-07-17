<?php

/**
 * One page box.
 *
 * @description: Work with clever one page box. This is static menu control, allow access page box.
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array $atts Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_one_page_box_control_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverOnePageBoxControl_shortcode_atts', array(
            'title' => '',
            'title_icon' => '',
            'title_color' => '',
            'title_bg' => '',
            'item_color' => '',
            'css' => ''
        )),
        $atts, 'CleverOnePageBoxControl'
    );

    $html = cvca_get_shortcode_view('one-page-box-control', $atts, $content);

    return $html;
}

add_shortcode('CleverOnePageBoxControl', 'cvca_add_clever_one_page_box_control_shortcode');

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_one_page_box_control_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever One Page Box Control', 'cvca'),
            'base' => 'CleverOnePageBoxControl',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Controller of One Page Box shortcode. Work only with One Page Box', 'cvca'),
            'params' => array(
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title", 'cvca'),
                    "param_name" => "title",
                    "admin_label" => true,
                    "description" => esc_html__("Heading of block.", 'cvca'),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => esc_html__('Icon Heading', 'cvca'),
                    'value' => '',
                    'param_name' => 'title_icon',
                    'description' => esc_html__('Class of icon font icon (Awesome font, or CleverSoft font) you want use', 'cvca'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Title color', 'cvca'),
                    'value' => '',
                    'param_name' => 'title_color',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Background Title', 'cvca'),
                    'value' => '',
                    "description" => esc_html__("Background for Heading.", 'cvca'),
                    'param_name' => 'title_bg',
                ), array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Item color', 'cvca'),
                    'value' => '',
                    "description" => esc_html__("Color of menu item.", 'cvca'),
                    'param_name' => 'item_color',
                ),
                array(
                    'type' => 'css_editor',
                    'counterup' => esc_html__('Css', 'cvca'),
                    'param_name' => 'css',
                    'group' => esc_html__('Design options', 'cvca'),
                ),
            )
        )
    );
}

add_action('vc_before_init', 'cvca_integrate_clever_one_page_box_control_shortcode_with_vc', 10, 0);