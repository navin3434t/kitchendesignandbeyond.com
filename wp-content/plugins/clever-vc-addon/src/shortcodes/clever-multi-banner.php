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
function cvca_add_clever_multi_banner_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverMultiBanner_shortcode_atts', array(
            'gutter' => '0',
            'layout' => 'masonry',
            'group_banner' => '',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverMultiBanner'
    );

    $html = cvca_get_shortcode_view('multi-banner', $atts, $content);

    return $html;
}

add_shortcode('CleverMultiBanner', 'cvca_add_clever_multi_banner_shortcode');

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_multi_banner_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Multi Banner', 'cvca'),
            'base' => 'CleverMultiBanner',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display your list banner image with multi layout', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Gutter', 'cvca'),
                    'value' => '0',
                    "admin_label" => true,
                    'description' => esc_html__('White space between each banner item', 'cvca'),
                    'param_name' => 'gutter',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Layout', 'cvca'),
                    'param_name' => 'layout',
                    'std' => 'masonry',
                    "admin_label" => true,
                    'value' => array(
                        esc_html__('Masonry', 'cvca') => 'masonry',
                        esc_html__('Fit Rows', 'cvca') => 'fitRows',
                        esc_html__('vertical', 'cvca') => 'vertical',
                        esc_html__('Packery', 'cvca') => 'packery',
                        esc_html__('Fit Columns', 'cvca') => 'fitColumns',
                        esc_html__('Horizontal', 'cvca') => 'horiz',
                    ),
                ),
                array(
                    'type' => 'param_group',
                    'value' => '',
                    'param_name' => 'group_banner',
                    'description' => esc_html__('Click arrow to show field content', 'cvca'),
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__('Image', 'cvca'),
                            'param_name' => 'image',
                            'description' => esc_html__('Image of banner', 'cvca')
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Image overlay color', 'cvca'),
                            'value' => '',
                            'param_name' => 'overlay_color',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Columns', 'cvca'),
                            'description' => esc_html__('Number columns banner item will take. Value from 0 to 12, if set 0, column will resize follow image size.', 'cvca'),
                            'param_name' => 'col',
                            'std' => '6',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Content Box Align', 'cvca'),
                            'param_name' => 'content_align',
                            'std' => 'center-center',
                            'value' => array(
                                esc_html__('Top Left', 'cvca') => 'top-left',
                                esc_html__('Top Right', 'cvca') => 'top-right',
                                esc_html__('Top Center', 'cvca') => 'top-center',
                                esc_html__('Center Left', 'cvca') => 'center-left',
                                esc_html__('Center Right', 'cvca') => 'center-right',
                                esc_html__('Center Center', 'cvca') => 'center-center',
                                esc_html__('Bottom Left', 'cvca') => 'bottom-left',
                                esc_html__('Bottom Right', 'cvca') => 'bottom-right',
                                esc_html__('Bottom Center', 'cvca') => 'bottom-center',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Title', 'cvca'),
                            'param_name' => 'title',
                            'description' => esc_html__('', 'cvca'),
                        ),
                        array(
                            'type' => 'textarea',
                            'class' => '',
                            'heading' => esc_html__('Content', 'cvca'),
                            'param_name' => 'content', // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
                            'value' => '',
                            'description' => esc_html__('Enter your content.', 'cvca')
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => esc_html__('Link', 'cvca'),
                            'value' => '#',
                            'param_name' => 'link',
                        ),
                    )),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'cvca'),
                    'param_name' => 'el_class',
                    'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca')
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('Css', 'cvca'),
                    'param_name' => 'css',
                    'group' => __('Design options', 'cvca'),
                ),
            )
        )
    );
}

add_action('vc_before_init', 'cvca_integrate_clever_multi_banner_shortcode_with_vc', 10, 0);
