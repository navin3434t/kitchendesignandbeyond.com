<?php

/**
 * Zoo_Customize_Builder_Element_Logo
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Logo extends Zoo_Customize_Builder_Element
{
    function __construct()
    {
        $this->id = 'logo';
        $this->title = esc_html__('Site Identity', 'fona');
        $this->width = 3;
        $this->selector = '.site-branding';
        $this->section = 'title_tagline';
        $this->panel = 'header_settings';
    }

    function get_builder_configs()
    {
        return [
            'name' => $this->title,
            'id' => $this->id,
            'width' => $this->width,
            'section' => $this->section
        ];
    }

    function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $config = [
            [
                'name' => 'header_logo_max_width',
                'type' => 'slider',
                'section' => $this->section,
                'default' => [],
                'max' => 400,
                'title' => esc_html__('Logo Max Height', 'fona'),
                'selector' => $this->selector . ' .wrap-logo .site-logo',
                'css_format' => 'max-height: {{value}};',
                'device_settings' => true
            ],
            [
                'name' => 'header_logo_name',
                'type' => 'radio_group',
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Show Site Title', 'fona'),
                'default' => 'yes',
                'choices' => [
                    'no' => esc_html__('No', 'fona'),
                    'yes' => esc_html__('Yes', 'fona'),
                ],
                'render_callback' => [$this, 'render']
            ],
            [
                'name' => 'header_logo_text_color',
                'type' => 'color',
                'section' => $this->section,
                'title' => esc_html__('Site Title Color', 'fona'),
                'css_format' => '.site-branding .site-name a {color: {{value}};}',
                'selector' => 'format',
                'required' => ['header_logo_name', '==', 'yes']
            ],
            [
                'name' => 'header_logo_desc',
                'type' => 'radio_group',
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Show Site Tagline', 'fona'),
                'default' => 'no',
                'choices' => [
                    'no' => esc_html__('No', 'fona'),
                    'yes' => esc_html__('Yes', 'fona'),
                ],
                'render_callback' => [$this, 'render']
            ],[
                'name' => 'header_logo_enable_svg',
                'type' => 'radio_group',
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Enable SVG logo', 'fona'),
                'default' => 'no',
                'choices' => [
                    'no' => esc_html__('No', 'fona'),
                    'yes' => esc_html__('Yes', 'fona'),
                ],
                'render_callback' => [$this, 'render']
            ],
            [
                'name' => 'header_logo_svg_code_secure',
                'type' => 'checkbox',
                'section' => $this->section,
                'label' => esc_html__('Is your SVG code secure?', 'fona'),
                'description' => esc_html__('Arbitrary code is risky but it&#8217;s up to you.', 'fona'),
                'checkbox_label' => esc_html__('Yes if checked.', 'fona'),
                'device_settings' => false,
                'default' => 0,
                'required' => ['header_logo_enable_svg', '==', 'yes'],
            ],
            [
                'name' => 'header_logo_svg',
                'type' => 'textarea',
                'section' => $this->section,
                'title' => esc_html__('Code of SVG logo', 'fona'),
                'default' => '',
                'required' => ['header_logo_svg_code_secure', '==', 1],
                'render_callback' => [$this, 'render']
            ],
        ];

        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    /**
     * Render
     *
     * @internal  Used as a callback.
     */
    public function render()
    {
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        $atts = [
            'logo_img' => '',
            'site_name' => ('yes' == zoo_customize_get_setting('header_logo_name')) ? get_bloginfo('name') : '',
            'site_desc' => ('yes' == zoo_customize_get_setting('header_logo_desc')) ? get_bloginfo('description') : '',
            'header_logo_enable_svg' => zoo_customize_get_setting('header_logo_enable_svg'),
            'header_logo_svg' => zoo_customize_get_setting('header_logo_svg'),
        ];
        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }

        $atts['device'] = $args[1];

        $logo_id = zoo_customize_get_setting('custom_logo');
        $sticky_logo_id = zoo_customize_get_setting('header_sticky_logo');

        if ($logo_id) {
            $atts['logo_img'] = wp_get_attachment_url($logo_id, 'full');
        }
        $atts['sticky_logo_img'] = !empty($sticky_logo_id['url']) ? esc_url($sticky_logo_id['url']) : false;

        $tpl = apply_filters('header/element/logo', ZOO_THEME_DIR . 'core/customize/templates/header/element-logo.php', $atts);

        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Logo());
