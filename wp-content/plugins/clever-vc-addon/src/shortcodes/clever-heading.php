<?php

/**
 * Add shortcode
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array $atts Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_heading_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverHeading_shortcode_atts', array(
            'title' => '',
            'use_theme_fonts' => '',
            'letter_spacing' => '',
            'text_underline' => '',
            'font_container' => '',
            'underline_height' => '',
            'underline_color' => '',
            'underline_width' => '',
            'font' => '',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverBoxes'
    );

    $html = cvca_get_shortcode_view('heading', $atts, $content);

    return $html;
}

add_shortcode('CleverHeading', 'cvca_add_clever_heading_shortcode');

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_heading_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Heading', 'cvca'),
            'base' => 'CleverHeading',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Custom heading with multi options.', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    'admin_label' => true,
                    'param_name' => 'title',
                ),
                array(
                    'type' => 'font_container',
                    'param_name' => 'font_container',
                    'value' => '',
                    'settings' => array(
                        'fields' => array(
                            'text_align',
                            'font_size',
                            'line_height',
                            'color',
                            'text_align_description' => esc_html__('Select text alignment.', 'cvca'),
                            'font_size_description' => esc_html__('Enter font size.', 'cvca'),
                            'line_height_description' => esc_html__('Enter line height.', 'cvca'),
                            'color_description' => esc_html__('Select color for your element.', 'cvca'),
                        ),
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Letter Spacing', 'cvca'),
                    'value' => '',
                    'param_name' => 'letter_spacing',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Text underline?', 'cvca'),
                    'param_name' => 'text_underline',
                    'value' => array(__('Yes', 'js_composer') => 'yes'),
                    'description' => __('Add border bottom for text.', 'cvca'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Underline width', 'cvca'),
                    'description' => __('Leave it blank if want set width auto.', 'cvca'),
                    'value' => '',
                    'param_name' => 'underline_width',
                    "dependency" => Array('element' => 'text_underline', 'value' => array('yes')),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Underline height', 'cvca'),
                    'value' => '',
                    'param_name' => 'underline_height',
                    "dependency" => Array('element' => 'text_underline', 'value' => array('yes')),
                ), array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Underline color', 'cvca'),
                    'value' => '',
                    'param_name' => 'underline_color',
                    "dependency" => Array('element' => 'text_underline', 'value' => array('yes')),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Yes theme default font family?', 'cvca'),
                    'param_name' => 'use_theme_fonts',
                    'value' => array(__('Yes', 'js_composer') => 'yes'),
                    'description' => __('Yes font family from the theme.', 'cvca'),
                ),
                array(
                    'type' => 'google_fonts',
                    'param_name' => 'font',
                    'value' => '',
                    'settings' => array(
                        'fields' => array(
                            'font_family_description' => __('Select font family.', 'cvca'),
                            'font_style_description' => __('Select font styling.', 'cvca'),
                        ),
                    ),
                    'dependency' => array(
                        'element' => 'use_theme_fonts',
                        'value_not_equal_to' => 'yes',
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'cvca'),
                    'param_name' => 'el_class',
                    'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca')
                ),
                array(
                    'type' => 'css_editor',
                    'counterup' => __('Css', 'cvca'),
                    'param_name' => 'css',
                    'group' => __('Design options', 'cvca'),
                ),
            )
        )
    );
}

add_action('vc_before_init', 'cvca_integrate_clever_heading_shortcode_with_vc', 10, 0);
