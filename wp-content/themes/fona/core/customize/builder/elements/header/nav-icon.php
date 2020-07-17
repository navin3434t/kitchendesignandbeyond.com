<?php

/**
 * Zoo_Builder_Element_Nav_Icon
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Builder_Element_Nav_Icon extends Zoo_Customize_Builder_Element
{
    public $id = 'nav-icon';
    public $section = 'header_menu_icon';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Menu Icon', 'fona'),
            'id' => $this->id,
            'width' => '3',
            'devices' => ['desktop', 'mobile'],
            'section' => $this->section // Customizer section to focus when click settings
        );
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $section = $this->section;
        $fn = array($this, 'render');
        $selector = '.site-header .builder-item .element-nav-icon';
        $config = array(
            array(
                'name' => $section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => esc_html__('Menu Icon', 'fona'),
            ),
            array(
                'name' => 'header_nav_icon_style',
                'type' => 'select',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => 'menu',
                'device_settings' => true,
                'title' => esc_html__('Nav Icon Style', 'fona'),
                'choices' => array(
                    'menu' => esc_html__('Menu', 'fona'),
                    'dots' => esc_html__('Dots', 'fona'),
                    'filter' => esc_html__('Filter', 'fona'),
                )
            ),
            array(
                'name' => 'header_nav_icon_text',
                'type' => 'text',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => esc_html__('Menu', 'fona'),
                'title' => esc_html__('Label', 'fona'),
            ),

            array(
                'name' => 'header_nav_icon_show_text',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'title' => esc_html__('Label Settings', 'fona'),
                'device_settings' => true,
                'default' => array(
                    'desktop' => 1,
                    'mobile' => 0,
                ),
                'checkbox_label' => esc_html__('Show Label', 'fona'),
            ),

            array(
                'name' => 'header_nav_icon_size',
                'type' => 'slider',
                'section' => $section,
                'min' => 8,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => ".element-nav-icon .nav-icon-label{font-size: {{value}};}",
                'label' => esc_html__('Label Size', 'fona'),
            ),

            array(
                'name' => 'header_nav_icon_item_color',
                'type' => 'color',
                'section' => $section,
                'title' => esc_html__('Color', 'fona'),
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => "{$selector}{color: {{value}};}",
                'selector' => "format",

            ),

            array(
                'name' => 'header_nav_icon_item_color_hover',
                'type' => 'color',
                'section' => $section,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => "{$selector}:hover{color: {{value}};}",
                'selector' => "format",
                'title' => esc_html__('Color Hover', 'fona'),
            ),
        );

        // Item Layout
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    public function render()
    {
        $atts = [];
        $args = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id . '_' . $this->id . '_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }

        $atts['icon_style'] = sanitize_text_field(zoo_customize_get_setting('header_nav_icon_style'));
        $atts['label'] = sanitize_text_field(zoo_customize_get_setting('header_nav_icon_text'));
        $atts['show_label'] = zoo_customize_get_setting('header_nav_icon_show_text', 'all');
        $atts['style'] = sanitize_text_field(zoo_customize_get_setting('header_nav_icon_style'));
        $atts['sizes'] = zoo_customize_get_setting('header_nav_icon_size', 'all');

        $tpl = apply_filters('header/element/nav-icon', ZOO_THEME_DIR . 'core/customize/templates/header/element-nav-icon.php', $atts);

        require $tpl;
    }
}

$self->add_element('header', new Zoo_Builder_Element_Nav_Icon());
