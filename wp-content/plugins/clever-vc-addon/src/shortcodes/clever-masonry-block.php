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
function cvca_add_clever_masonry_group_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverMasonryGroup_shortcode_atts', array(
            'horizontal_order' => '',
            'gutter' => '',
            'css' => ''
        )),
        $atts, 'CleverMasonryGroup'
    );

    $html = cvca_get_shortcode_view('masonry-group', $atts, $content);

    return $html;
}

add_shortcode('CleverMasonryGroup', 'cvca_add_clever_masonry_group_shortcode');

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_masonry_group_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Masonry Group', 'cvca'),
            'base' => 'CleverMasonryGroup',
            'icon' => '',
            'is_container' => true,
            'js_view' => 'VcColumnView',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Masonry Group display content inside with masonry layout', 'cvca'),
            'show_settings_on_create' => false,
            'params' => array(
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Horizontal Order", 'cvca'),
                    'param_name' => 'horizontal_order',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Gutter', 'cvca'),
                    'param_name' => 'gutter'
                ),array(
                    'type' => 'css_editor',
                    'counterup' => __('Css', 'cvca'),
                    'param_name' => 'css',
                    'group' => __('Design options', 'cvca'),
                ),
            )
        )
    );
}

add_action('vc_before_init', 'cvca_integrate_clever_masonry_group_shortcode_with_vc', 10, 0);
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_CleverMasonryGroup extends WPBakeryShortCodesContainer
    {
    }
}