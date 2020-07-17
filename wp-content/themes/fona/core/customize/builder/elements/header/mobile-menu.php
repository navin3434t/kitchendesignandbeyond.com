<?php

/**
 * Zoo_Customize_Builder_Element_Offcanvas_Menu
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Offcanvas_Menu extends Zoo_Customize_Builder_Element
{
    function __construct()
    {
        $this->id = 'mobile-menu';
        $this->title = esc_html__('Mobile Menu', 'fona');
        $this->width = 4;
        $this->section = 'header_menu_mobile';
        $this->panel = 'header_settings';
    }

    function get_builder_configs()
    {
        return array(
            'name' => $this->title,
            'id' => $this->id,
            'width' => $this->width,
            'devices' => ['desktop', 'mobile'],
            'section' => $this->section // Customizer section to focus when click settings
        );
    }

    function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $selector = '.mobile-menu.element-mobile-menu>ul';
        $config = array(
            array(
                'name' => $this->section,
                'type' => 'section',
                'panel' => $this->panel,
                'theme_supports' => '',
                'title' => $this->title,
                'description' => sprintf(__('Assign <a href="#menu_locations"  class="zoo-customize-focus-button">Menu Location</a> for %1$s', 'fona'), $this->title)
            ),

            array(
                'name' => 'header_mobile_menu_style',
                'type' => 'image_select',
                'section' => $this->section,
                'selector' => '#element-mobile-menu',
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Menu Preset', 'fona'),
                'default' => 'style-plain',
                'css_format' => 'html_class',
                'choices' => array(
                    'style-plain' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_1.svg',
                    ),
                    'style-border-bottom' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_2.svg',
                    ),
                    'style-border-top' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_3.svg',
                    ),
                )
            ),

            array(
                'name' => 'header_mobile_menu__hide-arrow',
                'type' => 'checkbox',
                'section' => $this->section,
                'selector' => '.row-item-' . $this->id . " .{$this->id}",
                'checkbox_label' => esc_html__('Hide menu dropdown arrow', 'fona'),
                'css_format' => 'html_class',
            ),

            array(
                'name' => 'header_mobile_menu_arrow_size',
                'type' => 'slider',
                'devices_setting' => true,
                'section' => $this->section,
                'selector' => $this->selector . " .row-item-{$this->id} .nav-icon-angle",
                'max' => 20,
                'checkbox_label' => esc_html__('Arrow icon size', 'fona'),
                'css_format' => "width: {{value}}; height: {{value}};",
                'required' => array('header_mobile_menu__hide-arrow', '!=', 1)
            ),
            array(
                'name' => 'header_mobile_menu_advanced_styling',
                'type' => 'checkbox',
                'section' => $this->section,
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Allow edit style if checked.', 'fona'),
            ),

            array(
                'name' => 'header_mobile_menu_top_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Menu Bar', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1]
            ),

            array(
                'name' => 'header_mobile_menu_item_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Menu Bar Items Styling', 'fona'),
                'description' => esc_html__('Styling for top level menu items', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1],
                'selector' => array(
                    'normal' => "{$selector} > li",
                    'normal_text_color' => "{$selector} > li > a, {$selector} > li > .menu-toggle",
                    'normal_margin' => "{$selector} > li",
                    'normal_border_radius' => "{$selector}",
                    'normal_box_shadow' => "{$selector}",
                    'normal_padding' => "{$selector}> li, {$selector} > li > .menu-toggle",
                    'hover' => "{$selector} > li:hover, {$selector} > li.current-menu-item, {$selector} > li.current-menu-ancestor, {$selector} > li.current-menu-parent",
                    'hover_text_color' => "{$selector} > li:hover > a, {$selector} > li > a:focus, {$selector} > li.current-menu-item > a, {$selector} > li.current-menu-ancestor > a, {$selector} > li.current-menu-parent > a , {$selector} > li.current-menu-parent >.menu-toggle, {$selector} > li:hover > .menu-toggle,{$selector} > li.current-menu-item > .menu-toggle, {$selector} > li.current-menu-ancestor > .menu-toggle",
                    'hover_border_radius' => "{$selector}",
                    'hover_box_shadow' => "{$selector}",
                ),
                'css_format' => 'styling',
                'fields' => array(
                    'tabs' => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover' => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        //'padding' => false // disable for special field.
                        'link_color' => false,
                        'bg_cover' => false,
                        'bg_image' => false,
                        'bg_repeat' => false,
                        'bg_attachment' => false,
                        'bg_position' => false,
                        'link_hover_color' => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false,
                        'bg_cover' => false,
                        'bg_image' => false,
                        'bg_repeat' => false,
                        'bg_attachment' => false,
                        'bg_position' => false,
                        'border_width' => false,
                        'border_style' => false,
                        'border_radius' => false,
                    ), // disable hover tab and all fields inside.
                )
            ),

            array(
                'name' => 'header_mobile_menu_typography',
                'type' => 'typography',
                'section' => $this->section,
                'title' => esc_html__('Top Menu Items Typography', 'fona'),
                'description' => esc_html__('Typography for menu', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1],
                'selector' => "{$selector}, {$selector} > li > a",
                'css_format' => 'typography',
            ), array(
                'name' => 'header_mobile_menu_sub_menu_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Sub Menu', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1],
            ),

            array(
                'name' => 'header_mobile_menu_sub_menu_item_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Sub Menu Items Styling', 'fona'),
                'description' => esc_html__('Styling for sub menu items', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1],
                'selector' => array(
                    'normal' => "{$selector} ul li",
                    'normal_text_color' => "{$selector} ul li a, {$selector} ul li .menu-toggle",
                    'normal_margin' => "{$selector} ul li",
                    'normal_border_radius' => "{$selector}  ul",
                    'normal_border_style' => "{$selector}  ul",
                    'normal_border_width' => "{$selector}  ul",
                    'normal_border_color' => "{$selector}  ul",
                    'normal_box_shadow' => "{$selector} ul",
                    'normal_padding' => "{$selector} ul li, {$selector} ul li .menu-toggle",
                    'hover' => "{$selector}  ul li:hover",
                    'hover_text_color' => "{$selector} ul li:hover > a, {$selector} ul li:hover> .menu-toggle",
                    'hover_border_radius' => "{$selector}",
                    'hover_box_shadow' => "{$selector}",
                ),
                'css_format' => 'styling',
                'fields' => array(
                    'tabs' => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover' => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        //'padding' => false // disable for special field.
                        'link_color' => false,
                        'bg_cover' => false,
                        'bg_image' => false,
                        'bg_repeat' => false,
                        'bg_attachment' => false,
                        'bg_position' => false,
                        'link_hover_color' => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false,
                        'bg_cover' => false,
                        'bg_image' => false,
                        'bg_repeat' => false,
                        'bg_attachment' => false,
                        'bg_position' => false,
                        'border_width' => false,
                        'border_style' => false,
                        'border_radius' => false,
                    ), // disable hover tab and all fields inside.
                )
            ),

            array(
                'name' => 'header_mobile_menu_sub_menu_typography',
                'type' => 'typography',
                'section' => $this->section,
                'title' => esc_html__('Sub Menu Items Typography', 'fona'),
                'description' => esc_html__('Typography for sub menu', 'fona'),
                'required'=>['header_mobile_menu_advanced_styling','==',1],
                'selector' => "{$selector} > li > a",
                'css_format' => 'typography',
            ),

        );

        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    function menu_fallback_cb()
    {
        $pages = get_pages(array(
            'child_of' => 0,
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order, post_title',
            'hierarchical' => 0,
            'parent' => 0,
            'exclude_tree' => array(),
            'number' => 10,
        ));


        echo '<ul class="' . $this->id . '-ul menu nav-menu menu--pages">';
        foreach (( array )$pages as $p) {
            $class = '';
            if (is_page($p)) {
                $class = 'current-menu-item';
            }

            echo '<li id="menu-item-' . esc_attr($p->ID) . '" class="menu-item menu-item-type--page  menu-item-' . esc_attr($p->ID . ' ' . $class) . '"><a href="' . esc_url(get_the_permalink($p)) . '"><span class="link-before">' . apply_filters('', $p->post_title) . '</span></a></li>';
        }
        echo '</ul>';
    }


    function render()
    {
        $atts = [];
        $style = sanitize_text_field(zoo_customize_get_setting('header_mobile_menu_style'));
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($style) {
            $style = sanitize_text_field($style);
        }
        $hide_arrow = sanitize_text_field(zoo_customize_get_setting('header_mobile_menu__hide-arrow'));
        if ($hide_arrow) {
            $style .= ' hide-arrow-active';
        }
        $atts['style'] = $style;
        $tpl = apply_filters('header/element/mobile-menu', ZOO_THEME_DIR . 'core/customize/templates/header/element-mobile-menu.php', $atts);

        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Offcanvas_Menu());
