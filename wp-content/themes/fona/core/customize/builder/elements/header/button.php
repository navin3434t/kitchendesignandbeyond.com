<?php

/**
 * Zoo_Customize_Builder_Element_Button
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Button extends Zoo_Customize_Builder_Element
{
    public function __construct()
    {
        $this->id = 'button';
        $this->title = esc_html__('Button', 'fona');
        $this->width = 4;
        $this->section = 'header_button';
    }

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Button', 'fona'),
            'id' => 'button',
            'col' => 0,
            'width' => '4',
            'section' => 'header_button' // Customizer section to focus when click settings
        );
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $section = 'header_button';
        $prefix = 'header_button';
        $fn = array($this, 'render');
        $selector = '.builder-item .element-button.button';
        $config = array(
            array(
                'name' => $section,
                'type' => 'section',
                'panel' => 'header_settings',
                'title' => esc_html__('Button', 'fona'),
            ),

            array(
                'name' => $prefix . '_text',
                'type' => 'text',
                'section' => $section,
                'theme_supports' => '',
                'selector' => $selector,
                'render_callback' => $fn,
                'title' => esc_html__('Text', 'fona'),
                'default' => esc_html__('Button', 'fona'),
                'device_settings' => false,
            ),

            array(
                'name' => $prefix . '_icon',
                'type' => 'icon',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'theme_supports' => '',
                'device_settings' => true,
                'title' => esc_html__('Icon', 'fona'),
            ),

            array(
                'name' => $prefix . '_position',
                'type' => 'select',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'default' => 'before',
                'device_settings' => true,
                'title' => esc_html__('Icon Position', 'fona'),
                'choices' => array(
                    'before' => esc_html__('Before', 'fona'),
                    'after' => esc_html__('After', 'fona'),
                )
            ),

            array(
                'name' => $prefix . '_link',
                'type' => 'text',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'device_settings' => true,
                'title' => esc_html__('Link', 'fona'),
            ),

            array(
                'name' => $prefix . '_target',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => $fn,
                'device_settings' => true,
                'checkbox_label' => esc_html__('Open link in a new tab.', 'fona'),
            ),
            [
                'name' => $prefix . '_heading_styling',
                'type' => 'heading',
                'section' => $section,
                'title' => esc_html__('Styling', 'fona'),
            ],
            [
                'name' => $prefix . '_advanced_styling',
                'type' => 'checkbox',
                'section' => $section,
                'render_callback' => $fn,
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'default' => 0
            ],
            array(
                'name' => $prefix . '_typography',
                'type' => 'typography',
                'section' => $section,
                'title' => esc_html__('Typography', 'fona'),
                'description' => esc_html__('Advanced typography for button', 'fona'),
                'selector' => $selector,
                'css_format' => 'typography',
                'default' => array(),
                'required'=>[$prefix . '_advanced_styling','==',1]
            ),

            array(
                'name' => $prefix . '_styling',
                'type' => 'styling',
                'section' => $section,
                'title' => esc_html__('Styling', 'fona'),
                'description' => esc_html__('Advanced styling for button', 'fona'),
                'selector' => array(
                    'normal' => $selector,
                    'hover' => $selector . ':hover',
                ),
                'css_format' => 'styling',
                'required'=>[$prefix . '_advanced_styling','==',1],
                'default' => array(),
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'link_hover_color' => false, // disable for special field.
                        'margin' => false,
                        'bg_image' => false,
                        'bg_cover' => false,
                        'bg_position' => false,
                        'bg_repeat' => false,
                        'bg_attachment' => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false, // disable for special field.
                    )
                ),
            ),

        );

        // Item Layout
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }


    public function render()
    {
        $atts  = [];
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }

        $atts['text'] = zoo_customize_get_setting('header_button_text', $args[1]);
        $atts['icon'] = zoo_customize_get_setting('header_button_icon', $args[1]);
        $atts['target'] = zoo_customize_get_setting('header_button_target', $args[1]);
        $atts['link'] = zoo_customize_get_setting('header_button_link', $args[1]);
        $atts['icon_position'] = zoo_customize_get_setting('header_button_position', $args[1]);

        $tpl = apply_filters('header/element/button', ZOO_THEME_DIR . 'core/customize/templates/header/element-button.php', $atts);
        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Button());
