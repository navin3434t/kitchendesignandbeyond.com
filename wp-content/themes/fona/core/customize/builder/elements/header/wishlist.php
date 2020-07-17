<?php

/**
 * Zoo_Customize_Builder_Element_Wishlist
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Wishlist extends Zoo_Customize_Builder_Element
{
    public $id = 'header-wishlist';
    public $section = 'header_wishlist';
    public $selector = '#header-element-wishlist';

    public function get_builder_configs()
    {
        return [
            'name' => esc_html__('Wishlist', 'fona'),
            'id' => 'header-wishlist',
            'width' => '3',
            'section' => 'header_wishlist'
        ];
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $selector = '.element-header-wishlist';

        $config = [
            [
                'name' => $this->section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => esc_html__('Wishlist', 'fona'),
            ],
            [
                'name' => 'header_wishlist_general_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('General Settings', 'fona'),
            ],
            [
                'name' => 'header_wishlist_title',
                'type' => 'text',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'label' => esc_html__('Label', 'fona'),
                'description' => esc_html__('Leave it blank if don\'t want show', 'fona'),
                'default' => esc_html__('My Wishlist', 'fona'),
                'device_settings' => true,
            ],
            [
                'name' => 'header_wishlist_icon',
                'type' => 'icon',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Display Icon', 'fona'),
                'default' => [
                    'type' => 'zoo-icon',
                    'icon' => 'zoo-icon-heart'
                ]
            ],
            [
                'name' => 'header_wishlist_show_count',
                'type' => 'checkbox',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Show count', 'fona'),
                'checkbox_label' => esc_html__('Show count of wishlist.', 'fona'),
                'default' => 0
            ],[
                'name'            => 'header_wishlist_advanced_styling',
                'type'            => 'checkbox',
                'section'         => $this->section,
                'title'           => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label'  => esc_html__('Allow change style if checked.', 'fona'),
                'default'         => 0,
            ],
            [
                'name' => 'header_wishlist_style',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Style Settings', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1]
            ],
            [
                'name' => 'header_wishlist_icon_size',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} i{ font-size: {{value}};width: {{value}};height: {{value}}; }",
                'label' => esc_html__('Icon Size', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1]
            ],
            [
                'name' => 'header_wishlist_icon_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Icon Styling', 'fona'),
                'description' => esc_html__('Advanced styling for icon wishlist', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1],
                'selector' => array(
                    'normal' =>"{$selector} .wishlist-link .wishlist-icon",
                    'normal_link_color' =>"{$selector} .wishlist-link .wishlist-title",
                    'hover' => "{$selector} .wishlist-link:hover .wishlist-icon",
                    'hover_link_color' => "{$selector} .wishlist-link:hover .wishlist-title",
                ),
                'css_format' => 'styling',
                'default' => array(),
                'fields' => array(
                    'normal_fields' => array(
                        'margin' => false,
                        'bg_image' => false,
                        'link_hover_color'   => false,
                    ),
                    'hover_fields' => array(
                    )
                ),
            ],[
                'name' => 'header_wishlist_count_style',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Style Count Settings', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1],
            ],
            [
                'name' => 'header_wishlist_count_position',
                'type' => 'select',
                'section' => $this->section,
                'css_format' => 'html_class',
                'title' => esc_html__('Count position', 'fona'),
                'default' => 'top-right',
                'required' =>['header_wishlist_advanced_styling','==',1],
                'choices' => [
                    'inside' => esc_html__('Inside icon', 'fona'),
                    'top-left' => esc_html__('Top left', 'fona'),
                    'top-right' => esc_html__('Top right', 'fona'),
                ]
            ],
            [
                'name' => 'header_wishlist_count_size',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} .wishlist-counter{ font-size: {{value}}}",
                'label' => esc_html__('Count Size', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1]
            ],[
                'name' => 'header_wishlist_count_width',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} .wishlist-counter{ min-width:{{value}};height:{{value}} }",
                'label' => esc_html__('Count Width', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1]
            ],
            [
                'name' => 'header_wishlist_count_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Count Styling', 'fona'),
                'description' => esc_html__('Advanced styling for count wishlist', 'fona'),
                'required' =>['header_wishlist_advanced_styling','==',1],
                'selector' => array(
                    'normal' =>"{$selector} .wishlist-counter",
                    'hover' => "{$selector} .wishlist-counter",
                ),
                'css_format' => 'styling',
                'default' => array(),
                'fields' => array(
                    'normal_fields' => array(
                        'margin' => false,
                        'link_color' => false,
                        'bg_image' => false,
                        'link_hover_color'   => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false,
                    )
                ),
            ],
        ];
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    function render()
    {
        $atts = [];
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }

        $atts['device'] = $args[1];
        $atts['title'] = zoo_customize_get_setting('header_wishlist_title', $args[1]);
        $atts['show_count'] = zoo_customize_get_setting('header_wishlist_show_count',$args[1]);
        $atts['icon'] = zoo_customize_get_setting('header_wishlist_icon');
        $atts['page_enable'] = zoo_customize_get_setting('header_wishlist_dedicated_page_enable',$args[1]);
        $atts['count-position'] = zoo_customize_get_setting('header_wishlist_count_position');
        $tpl = apply_filters('header/element/wishlist', ZOO_THEME_DIR . 'core/customize/templates/header/element-wishlist.php', $atts);
        require $tpl;
    }
}

if (class_exists('WooCommerce', false)) {
    $self->add_element('header', new Zoo_Customize_Builder_Element_Wishlist());
}
