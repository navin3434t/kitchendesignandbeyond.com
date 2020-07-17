<?php
/**
 * Zoo_Customize_Builder_Element_Products_Compare
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Products_Compare extends Zoo_Customize_Builder_Element
{
    public $id = 'header-products-compare';
    public $section = 'header_products_compare';
    public $selector = '#header-element-products-compare';

    public function get_builder_configs()
    {
        return [
            'name'    => esc_html__('Products Compare', 'fona'),
            'id'      => $this->id,
            'width'   => '2',
            'section' => $this->section
        ];
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $selector = '.element-header-products-compare';

        $config = [
            [
                'name' => $this->section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => esc_html__('Compare', 'fona'),
            ],
            [
                'name' => 'header_compare_general_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('General Settings', 'fona'),
            ],
            [
                'name' => 'header_compare_title',
                'type' => 'text',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'device_settings' => true,
                'label' => esc_html__('Label', 'fona'),
                'description' => esc_html__('Leave it blank if don\'t want show', 'fona'),
                'default' => esc_html__('My compare', 'fona')
            ],
            [
                'name' => 'header_compare_icon',
                'type' => 'icon',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Display Icon', 'fona'),
                'default' => [
                    'type' => 'zoo-icon',
                    'icon' => 'zoo-icon-refresh'
                ]
            ],
            [
                'name' => 'header_compare_show_count',
                'type' => 'checkbox',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Show count', 'fona'),
                'checkbox_label' => esc_html__('Show count of compare.', 'fona'),
                'default' => 0
            ],[
                'name'            => 'header_compare_advanced_styling',
                'type'            => 'checkbox',
                'section'         => $this->section,
                'title'           => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label'  => esc_html__('Allow change style if checked.', 'fona'),
                'default'         => 0,
            ],
            [
                'name' => 'header_compare_style',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Style Settings', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1]
            ],
            [
                'name' => 'header_compare_icon_size',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} i{ font-size: {{value}};width: {{value}};height: {{value}}; }",
                'label' => esc_html__('Icon Size', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1]
            ],
            [
                'name' => 'header_compare_icon_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Icon Styling', 'fona'),
                'description' => esc_html__('Advanced styling for icon compare', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1],
                'selector' => array(
                    'normal' =>"{$selector} .products-compare-link .products-compare-icon",
                    'normal_link_color' =>"{$selector} .products-compare-link .products-compare-title",
                    'hover' => "{$selector} .products-compare-link:hover .products-compare-icon",
                    'hover_link_color' => "{$selector} .products-compare-link:hover .products-compare-title",
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
                'name' => 'header_compare_count_style',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Style Count Settings', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1]
            ],
            [
                'name' => 'header_compare_count_position',
                'type' => 'select',
                'section' => $this->section,
                'css_format' => 'html_class',
                'title' => esc_html__('Count position', 'fona'),
                'default' => 'top-right',
                'required'=>['header_compare_advanced_styling','==',1],
                'choices' => [
                    'inside' => esc_html__('Inside icon', 'fona'),
                    'top-left' => esc_html__('Top left', 'fona'),
                    'top-right' => esc_html__('Top right', 'fona'),
                ]
            ],
            [
                'name' => 'header_compare_count_size',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} .products-compare-counter{ font-size: {{value}}}",
                'label' => esc_html__('Count Size', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1]
            ],[
                'name' => 'header_compare_count_width',
                'type' => 'slider',
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$selector} .products-compare-counter{ min-width:{{value}};height:{{value}} }",
                'label' => esc_html__('Count Width', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1]
            ],
            [
                'name' => 'header_compare_count_styling',
                'type' => 'styling',
                'section' => $this->section,
                'title' => esc_html__('Count Styling', 'fona'),
                'description' => esc_html__('Advanced styling for count compare', 'fona'),
                'required'=>['header_compare_advanced_styling','==',1],
                'selector' => array(
                    'normal' =>"{$selector} .products-compare-counter",
                    'hover' => "{$selector}:hover .products-compare-counter",
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
        $atts['title'] = zoo_customize_get_setting('header_compare_title',$args[1]);
        $atts['show_count'] = zoo_customize_get_setting('header_compare_show_count',$args[1]);
        $atts['icon'] = zoo_customize_get_setting('header_compare_icon');
        $atts['count_position'] = zoo_customize_get_setting('header_compare_count_position');
        $atts['page_enable'] = zoo_customize_get_setting('header_compare_dedicated_page_enable',$args[1]);
        $tpl = apply_filters('header/element/products-compare', ZOO_THEME_DIR . 'core/customize/templates/header/element-products-compare.php', $atts);
        require $tpl;
    }
}

if (class_exists('WooCommerce', false)) {
    $self->add_element('header', new Zoo_Customize_Builder_Element_Products_Compare());
}
