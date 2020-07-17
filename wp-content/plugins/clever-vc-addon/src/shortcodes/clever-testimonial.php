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
function cvca_add_clever_testimonial_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        apply_filters('CleverTestimonial_shortcode_atts', array(
            'title' => '',
            'category' => '',
            'order_by' => 'date',
            'item_count' => '3',
            'output_type' => 'excerpt',
            'preset_style' => 'default',
            'excerpt_length' => '20',
            'columns' => '1',
            'style' => 'normal',
            'hide_avatar' => '',
            'carousel_nav' => 'no',
            'carousel_pag' => 'no',
            'overlay_color' => '',
            'el_class' => '',
            'css' => ''
        )),
        $atts, 'CleverTestimonial'
    );

    $html = cvca_get_shortcode_view('testimonial', $atts, $content);

    return $html;
}

add_shortcode('CleverTestimonial', 'cvca_add_clever_testimonial_shortcode');

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_testimonial_shortcode_with_vc()
{
    vc_map(array(
        "name" => esc_html__("Clever Testimonial", 'cvca'),
        "base" => "CleverTestimonial",
        "class" => "",
        "icon" => "",
        "wrapper_class" => "clearfix",
        "controls" => "full",
        'category' => esc_html__('CleverSoft', 'cvca'),
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => esc_html__("Title", 'cvca'),
                "param_name" => "title",
                "value" => "",
                "description" => esc_html__("Heading text. Leave it empty if not needed.", 'cvca')
            ),
            array(
                "type" => "cvca_testimonial_categories",
                "heading" => esc_html__("Testimonials category", 'cvca'),
                "param_name" => "category",
                "description" => esc_html__("Choose the category for the testimonials.", 'cvca')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Testimonials Order", 'cvca'),
                "param_name" => "order_by",
                "value" => array(
                    esc_html__('Random', 'cvca') => "rand",
                    esc_html__('Latest', 'cvca') => "date"
                ),
                "description" => esc_html__("Choose the order of the testimonials.", 'cvca')
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => esc_html__("Number of items", 'cvca'),
                "param_name" => "item_count",
                "value" => "3",
                "description" => esc_html__("The number of testimonials display. Leave blank to show ALL testimonials.", 'cvca')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Content display", 'cvca'),
                "param_name" => "output_type",
                'std' => 1,
                "value" => array(
                    esc_html__('Excerpt', 'cvca') => 'excerpt',
                    esc_html__('Full content', 'cvca') => 'content',
                ),
                'description' => esc_html__('Select type of content', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Excerpt lenght", 'cvca'),
                "param_name" => "excerpt_length",
                'dependency' => Array('element' => 'output_type', 'value' => array('excerpt')),
                "description" => esc_html__("Total character display of excerpt.", 'cvca'),
                "value" => '20'
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Testimonials Style", 'cvca'),
                "param_name" => "style",
                "value" => array(
                    esc_html__('Normal', 'cvca') => "normal",
                    esc_html__('Carousel', 'cvca') => "carousel"
                ),
                'std' => 'normal',
                'group' => esc_html__('Style', 'cvca'),
                "description" => esc_html__("Choose style display.", 'cvca')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Preset Style", 'cvca'),
                "param_name" => "preset_style",
                "value" => array(
                    esc_html__('Default', 'cvca') => "default",
                    esc_html__('Style 1', 'cvca') => "style-1",
                ),
                'std' => 'default',
                'group' => esc_html__('Style', 'cvca'),
                "description" => esc_html__("Choose preset style display.", 'cvca')
            ),
            array(
                "type" => "checkbox",
                "heading" => esc_html__("Hide avatar author", 'cvca'),
                "param_name" => "hide_avatar",
                'value' => array(esc_html__('Yes', 'cvca') => 'true'),
                'group' => esc_html__('Style', 'cvca'),
                "description" => esc_html__("Hide avatar author.", 'cvca')
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Columns", 'cvca'),
                "param_name" => "columns",
                'group' => esc_html__('Style', 'cvca'),
                'std' => '1',
                "value" => array(
                    esc_html__('1', 'cvca') => 1,
                    esc_html__('2', 'cvca') => 2,
                    esc_html__('3', 'cvca') => 3,
                    esc_html__('4', 'cvca') => 4,
                    esc_html__('6', 'cvca') => 6
                ),
                'description' => esc_html__('Display testimonial with the number of columns', 'cvca'),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Enable Carousel navigation", 'cvca'),
                "param_name" => "carousel_nav",
                "value" => array(
                    esc_html__('Yes', 'cvca') => "yes",
                    esc_html__('No', 'cvca') => "no"
                ),
                'std' => 'no',
                'group' => esc_html__('Style', 'cvca'),
                "dependency" => Array('element' => 'style', 'value' => array('carousel')),
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Enable Carousel pagination", 'cvca'),
                "param_name" => "carousel_pag",
                "value" => array(
                    esc_html__('Yes', 'cvca') => "yes",
                    esc_html__('No', 'cvca') => "no"
                ),
                'std' => 'no',
                'group' => esc_html__('Style', 'cvca'),
                "dependency" => Array('element' => 'style', 'value' => array('carousel')),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__('Image overlay color', 'cvca'),
                'value' => '',
                'param_name' => 'overlay_color',
                'group' => esc_html__('Style', 'cvca'),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Extra class name", 'cvca'),
                "param_name" => "el_class",
                "value" => "",
                "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'cvca')
            ),
            array(
                'type' => 'css_editor',
                'heading' => __('Css', 'cvca'),
                'param_name' => 'css',
                'group' => __('Design options', 'cvca'),
            ),
        )
    ));
}

add_action('vc_before_init', 'cvca_integrate_clever_testimonial_shortcode_with_vc', 10, 0);