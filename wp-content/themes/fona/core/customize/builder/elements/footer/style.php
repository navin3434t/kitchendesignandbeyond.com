<?php
/**
 * Zoo_Customize_Builder_Element_Footer_Style
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 * @license  GPL v3+
 */
final class Zoo_Customize_Builder_Element_Footer_Style extends Zoo_Customize_Builder_Element
{
    public $id = 'footer_builder_style';
    public $selector = '#site-footer';

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $fields = [
            [
                'name'     => $this->id,
                'type'     => 'section',
                'panel'    => 'footer_settings',
                'priority' => 9999998,
                'title'    => esc_html__('Style', 'fona'),
            ],
            [
                'name' => 'footer_preset_heading',
                'type' => 'heading',
                'section' => $this->id,
                'title' => esc_html__('General Preset', 'fona'),
            ],
            [
                'name' => $this->id . '_preset',
                'type' => 'select',
                'section' => $this->id,
                'title' => esc_html__('Preset', 'fona'),
                'description' => esc_html__('General preset prebuilt of theme', 'fona'),
                'render_callback' => 'Zoo_Customize_Footer_Builder::render',
                'css_format' => 'html_class',
                'default' => 'none',
                'choices' => apply_filters('zoo_footer_builder_styles', [
                    'none' => esc_html__('--Select--', 'fona'),
                    'custom' => esc_html__('Custom', 'fona')
                ])
            ],
            [
                'name' => 'footer_style_heading',
                'type' => 'heading',
                'section' => $this->id,
                'title' => esc_html__('General Style', 'fona'),
            ],
            [
                'name' => 'footer_styling',
                'type' => 'styling',
                'priority' => 20,
                'section' => $this->id,
                'title' => esc_html__('Custom Styling', 'fona'),
                'description' => esc_html__('Advanced styling for Footer', 'fona'),
                'selector' => array(
                    'normal' => "{$this->selector}",
                    'normal_link_color' => "{$this->selector} a",
                    'hover_link_color' => "{$this->selector} a:hover",
                ),
                'css_format' => 'styling',
                'default' => array(),
                'fields' => array(
                    'normal_fields' => array(
                        'margin' => false,
                        'border_heading' => false,
                        'border_style' => false,
                        'border_width' => false,
                        'border_color' => false,
                        'border_radius' => false,
                    ),'hover_fields' => false
                ),
            ],
        ];
        return $fields;
    }

    public function get_builder_configs()
    {
        return [];
    }

    public function render()
    {
    }
}
$self->add_element('footer', new Zoo_Customize_Builder_Element_Footer_Style());
