<?php
/**
 * Zoo_Customize_Builder_Element_Html2
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Html2 extends Zoo_Customize_Builder_Element
{
    /**
     * Optional construct
     *
     * Zoo_Builder_Item_HTML constructor.
     */
    public function __construct()
    {
        $this->id = 'html2';
        $this->title = esc_html__('HTML 2', 'fona');
        $this->width = 4;
        $this->section = 'header_html2';
        $this->panel = 'header_settings';
    }

    /**
     * Register Builder item
     * @return array
     */
    public function get_builder_configs()
    {
        return array(
            'name' => $this->title,
            'id' => $this->id,
            'col' => 0,
            'width' => $this->width,
            'section' => $this->section // Customizer section to focus when click settings
        );
    }

    /**
     * Optional, Register customize section and panel.
     *
     * @return array
     */
    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $selector='.element-' . $this->id;

        $config = array(
            array(
                'name' => $this->section,
                'type' => 'section',
                'panel' => $this->panel,
                'title' => $this->title,
            ),
            array(
                'name' => $this->section,
                'type' => 'textarea',
                'section' => $this->section,
                'selector' => $selector,
                'render_callback' => [$this, 'render'],
                'theme_supports' => '',
                'default' => esc_html__('Add custom text here or remove it', 'fona'),
                'title' => esc_html__('HTML Code', 'fona'),
                'description' => esc_html__('Make sure your code is secure because we can&#8217;t guarantee it will be sanitized properly.', 'fona'),
            ),
            array(
                'name'        => $this->section.'_styling',
                'type'        => 'styling',
                'section'     => $this->section,
                'title'       => esc_html__('Styling', 'fona'),
                'description' => esc_html__('Styling for html block items', 'fona'),
                'selector'    => array(
                    'normal'           => "{$selector}",
                    'normal_link_color'=> "{$selector} a",
                    'hover_link_color' => "{$selector} a:hover",
                ),
                'css_format'  => 'styling',
                'fields'      => array(
                    'tabs'          => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover'  => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        'margin' => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                        'bg_heading'   => false,
                        'bg_color'   => false,
                        'box_shadow'   => false,
                        'link_hover_color'   => false,
                    ),
                    'hover_fields'  => array(
                        'text_color'      => false,
                        'bg_heading'      => false,
                        'bg_color'      => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                        'border_heading'   => false,
                        'border_width'   => false,
                        'border_style'   => false,
                        'border_radius'   => false,
                        'box_shadow'   => false,
                    ), // disable hover tab and all fields inside.
                )
            )
        );

        // Item Layout
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    /**
     * Optional. Render item content
     */
    public function render()
    {
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
        }

        $atts = zoo_customize_get_setting($this->section, $args[1]);
        $tpl = apply_filters('header/element/html', ZOO_THEME_DIR . 'core/customize/templates/header/element-html.php', $atts);
        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Html2());
