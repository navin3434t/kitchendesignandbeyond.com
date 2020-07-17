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
if (current_theme_supports('team-member-post-type')) {
    function cvca_add_clever_tm_shortcode($atts, $content = null)
    {
        $atts = shortcode_atts(
            apply_filters('CleverTeamMember_shortcode_atts', array(
                'title' => '',
                'category' => '',
                'order_by' => 'date',
                'item_count' => '6',
                'output_type' => 'description',
                'layout' => 'grid',
                'img_size' => 'large',
                'columns' => '3',
                'show_social' => '1',
                'show_view_more' => '',
                'view_more_text' => esc_html__("View Profile", 'cvca'),
                'el_class' => '',
                'css' => ''
            )),
            $atts, 'CleverTeamMember'
        );

        $html = cvca_get_shortcode_view('team-member', $atts, $content);

        return $html;
    }

    add_shortcode('CleverTeamMember', 'cvca_add_clever_tm_shortcode');

    /**
     * Integrate to Visual Composer
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     */
    function cvca_integrate_clever_tm_shortcode_with_vc()
    {
        vc_map(array(
            "name" => esc_html__("Clever Team member", 'cvca'),
            "base" => "CleverTeamMember",
            "class" => "",
            "icon" => "",
            "wrapper_class" => "clearfix",
            "controls" => "full",
            "description" => esc_html__("Display team member with multi options.", 'cvca'),
            'category' => esc_html__('CleverSoft', 'cvca'),
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title", 'cvca'),
                    "param_name" => "title",
                    "value" => "",
                    "admin_label" => true,
                    "description" => esc_html__("Heading text. Leave it empty if not needed.", 'cvca')
                ),
                array(
                    "type" => "cvca_team_member_categories",
                    "heading" => esc_html__("team members category", 'cvca'),
                    "param_name" => "category",
                    "admin_label" => true,
                    "description" => esc_html__("Choose the category for the team members.", 'cvca')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Order", 'cvca'),
                    "param_name" => "order_by",
                    "value" => array(
                        esc_html__('Random', 'cvca') => "rand",
                        esc_html__('Latest', 'cvca') => "date"
                    ),
                    "description" => esc_html__("Choose the order of the team members.", 'cvca')
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => esc_html__("Number of items", 'cvca'),
                    "param_name" => "item_count",
                    "admin_label" => true,
                    "value" => "6",
                    "description" => esc_html__("The number of team members display. Leave blank to show ALL team members.", 'cvca')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Content display", 'cvca'),
                    "param_name" => "output_type",
                    "admin_label" => true,
                    'std' => 1,
                    "value" => array(
                        esc_html__('Short description', 'cvca') => 'description',
                        esc_html__('Full content', 'cvca') => 'content',
                        esc_html__('None', 'cvca') => 'none',
                    ),
                    'description' => esc_html__('Select type of content', 'cvca'),
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("team members Style", 'cvca'),
                    "param_name" => "layout",
                    "admin_label" => true,
                    "value" => array(
                        esc_html__('Grid', 'cvca') => "grid",
                        esc_html__('List', 'cvca') => "list"
                    ),
                    'std' => 'normal',
                    'group' => esc_html__('Style', 'cvca'),
                    "description" => esc_html__("Choose layout display.", 'cvca')
                ),
                array(
                    "type" => "dropdown",
                    "heading" => esc_html__("Columns", 'cvca'),
                    "param_name" => "columns",
                    'group' => esc_html__('Style', 'cvca'),
                    'std' => '1',
                    "admin_label" => true,
                    "value" => array(
                        esc_html__('1', 'cvca') => 1,
                        esc_html__('2', 'cvca') => 2,
                        esc_html__('3', 'cvca') => 3,
                        esc_html__('4', 'cvca') => 4,
                        esc_html__('6', 'cvca') => 6
                    ),
                    'dependency' => array('element' => 'layout', 'value' => 'grid'),
                    'description' => esc_html__('Display team member with the number of columns', 'cvca'),
                ),
                array(
                    'type' => 'cvca_image_size',
                    "heading" => esc_html__("Image size", 'cvca'),
                    "param_name" => "img_size",
                    'group' => esc_html__('Style', 'cvca'),
                ),
                array(
                    "type" => "checkbox",
                    "heading" => esc_html__("Show social profile", 'cvca'),
                    "param_name" => "show_social",
                    'value' => array(esc_html__('Yes', 'cvca') => 'true'),
                    'group' => esc_html__('Style', 'cvca'),
                    "description" => esc_html__("Show Social profile link of team member.", 'cvca')
                ),
                array(
                    "type" => "checkbox",
                    "heading" => esc_html__("Show view more", 'cvca'),
                    "param_name" => "show_view_more",
                    'value' => array(esc_html__('Yes', 'cvca') => 'true'),
                    'group' => esc_html__('Style', 'cvca'),
                    "description" => esc_html__("Show button view more or not.", 'cvca')
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Text of button view more", 'cvca'),
                    "param_name" => "view_more_text",
                    'group' => esc_html__('Style', 'cvca'),
                    'dependency' => array('element' => 'show_view_more', 'value' => 'true'),
                    "value" => esc_html__("View Profile", 'cvca'),
                    "description" => esc_html__("", 'cvca')
                ), array(
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

    add_action('vc_before_init', 'cvca_integrate_clever_tm_shortcode_with_vc', 10, 0);
}