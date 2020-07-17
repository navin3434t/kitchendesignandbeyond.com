<?php

/**
 * Zoo_Customize_Builder_Element_Cart
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Cart extends Zoo_Customize_Builder_Element
{
    public function __construct()
    {
        $this->id = 'cart-icon';
        $this->title = esc_html__('WooCommerce Cart', 'fona');
        $this->width = 4;
        $this->section = 'header_cart';
    }

    public function get_builder_configs()
    {
        return [
            'name' => esc_html__('WooCommerce Cart', 'fona'),
            'id' => 'cart-icon',
            'width' => '3',
            'section' => 'header_cart'
        ];
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $prefix = 'header_cart';
        $section = $this->section;
        $config = [
            [
                'name' => $this->section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => esc_html__('WooCommerce Cart', 'fona'),
            ],
            /*General settings*/
            [
                'name' => $prefix . '_heading_general',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 0,
                'title' => esc_html__('General Settings', 'fona'),
            ],
            array(
                'name' => 'header_cart_icon_style',
                'type' => 'select',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'default' => 'style-1',
                'priority' => 0,
                'device_settings' => true,
                'title' => esc_html__('Cart Icon Style', 'fona'),
                'choices' => array(
                    'style-1' => esc_html__('Style 1', 'fona'),
                    'style-2' => esc_html__('Style 2', 'fona'),
                    'style-3' => esc_html__('Style 3', 'fona'),
                    'style-4' => esc_html__('Style 4', 'fona'),
                    'style-5' => esc_html__('Style 5', 'fona'),
                    'style-6' => esc_html__('Style 6', 'fona'),
                    'style-7' => esc_html__('Style 7', 'fona'),
                )
            ),
            [
                'name' => 'header_cart_icon',
                'type' => 'icon',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'priority' => 1,
                'title' => esc_html__('Cart icon', 'fona'),
                'default' => [
                    'type' => 'zoo-icon',
                    'icon' => 'zoo-icon-cart'
                ]
            ],
            [
                'name' => 'header_cart_show_title',
                'type' => 'checkbox',
                'section' => $this->section,
                'priority' => 2,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'title' => esc_html__('Show Title', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'device_settings' => true,
                'default' => [
                    'desktop' => 0,
                    'mobile' => 0,
                ]
            ],
            [
                'name' => 'header_cart_show_totals',
                'type' => 'checkbox',
                'section' => $this->section,
                'priority' => 3,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'title' => esc_html__('Show Totals', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'device_settings' => true,
                'default' => [
                    'desktop' => 0,
                    'mobile' => 0,
                ]
            ],
            [
                'name' => 'header_cart_display_style',
                'type' => 'select',
                'section' => $this->section,
                'title' => esc_html__('Display Style', 'fona'),
                'css_format' => 'html_class',
                'priority' => 4,
                'default' => 'drop-down',
                'choices' => [
                    'drop-down' => esc_html__('Drop down', 'fona'),
                    'off-canvas' => esc_html__('Off-Canvas Sidebar', 'fona'),
                    'link-only' => esc_html__('Link Only', 'fona'),
                ]
            ],
            [
                'name' => 'header_cart_enable_styling',
                'type' => 'checkbox',
                'section' => $this->section,
                'priority' => 5,
                'title' => esc_html__('Enable advance styling', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'default' => 0
            ],
            /*Header cart icon*/
            [
                'name' => $prefix . '_heading_icon_cart',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 6,
                'title' => esc_html__('Cart Icon Styling', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1]
            ],
            [
                'name' => $prefix . '_icon_size',
                'type' => 'slider',
                'section' => $section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'device_settings' => true,
                'priority' => 6,
                'render_callback' => [$this, 'render'],
                'css_format' => ".site-header .element-cart-icon .icon-element-cart{ font-size: {{value}};}",
                'label' => esc_html__('Icon cart Size', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1]
            ],
            [
                'name' => $prefix . '_icon_width',
                'type' => 'slider',
                'section' => $section,
                'min' => 15,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'priority' => 6,
                'device_settings' => true,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => ".site-header .element-cart-icon .icon-element-cart{ width: {{value}};height: {{value}};}",
                'label' => esc_html__('Cart Icon Width', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ],
            [
                'name' => $prefix . '_icon_styling',
                'type' => 'styling',
                'section' => $section,
                'title' => esc_html__('Icon Cart Styling', 'fona'),
                'description' => esc_html__('Advanced styling for icon cart', 'fona'),
                'selector' => array(
                    'normal' => '.site-header .element-cart-icon.style-1 .icon-element-cart,.site-header .element-cart-icon.style-7 .icon-element-cart,.site-header .element-cart-icon.style-2 .icon-element-cart, .element-cart-icon.style-3 .element-cart-link, .element-cart-icon.style-4 .element-cart-link, .element-cart-icon.style-5 .element-cart-link, .element-cart-icon.style-6 .element-cart-link',
                    'hover' => '.site-header .element-cart-icon.style-1:hover .icon-element-cart,.site-header .element-cart-icon.style-7:hover .icon-element-cart,.site-header .element-cart-icon.style-2:hover .icon-element-cart, .element-cart-icon.style-3:hover .element-cart-link, .element-cart-icon.style-4:hover .element-cart-link, .element-cart-icon.style-5:hover .element-cart-link, .element-cart-icon.style-6:hover .element-cart-link:hover',
                ),
                'css_format' => 'styling',
                'priority' => 7,
                'default' => array(),
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'link_hover_color' => false, // disable for special field.
                        'margin' => false,
                        'bg_image' => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false, // disable for special field.
                    )
                ),

                'required' => ['header_cart_enable_styling', '==', 1]
            ],
            /*Header cart count*/
            [
                'name' => $prefix . '_heading_cart_count',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 8,
                'title' => esc_html__('Cart Count Styling', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1]
            ],
            [
                'name' => 'header_cart_icon_count_position',
                'type' => 'select',
                'section' => $this->section,
                'priority' => 9,
                'css_format' => 'html_class',
                'title' => esc_html__('Cart count position', 'fona'),
                'default' => 'top-right',
                'device_settings' => true,
                'required' => ['header_cart_enable_styling', '==', 1],
                'choices' => [
                    'inside' => esc_html__('Inside cart icon', 'fona'),
                    'top-left' => esc_html__('Top left', 'fona'),
                    'top-right' => esc_html__('Top right', 'fona'),
                ]
            ],
            [
                'name' => $prefix . '_count_size',
                'type' => 'slider',
                'section' => $section,
                'min' => 8,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'device_settings' => true,
                'priority' => 10,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => ".element-cart-icon .element-cart-count{font-size: {{value}};}",
                'label' => esc_html__('Cart Count Size', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ], [
                'name' => $prefix . '_count_width',
                'type' => 'slider',
                'section' => $section,
                'min' => 15,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'priority' => 10,
                'device_settings' => true,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => ".element-cart-icon .element-cart-count{ min-width: {{value}};height: {{value}};}",
                'label' => esc_html__('Cart Count Width', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ],
            [
                'name' => $prefix . '_count_styling',
                'type' => 'styling',
                'section' => $section,
                'title' => esc_html__('Cart Count Styling', 'fona'),
                'description' => esc_html__('Advanced styling for cart count', 'fona'),
                'selector' => array(
                    'normal' => '.element-cart-icon .icon-element-cart .element-cart-count',
                    'hover' => '.element-cart-icon:hover .icon-element-cart .element-cart-count',
                ),
                'css_format' => 'styling',
                'priority' => 11,
                'default' => array(),
                'required' => ['header_cart_enable_styling', '==', 1],
                'fields' => array(
                    'normal_fields' => array(
                        'link_color' => false, // disable for special field.
                        'link_hover_color' => false, // disable for special field.
                        'margin' => false,
                        'bg_image' => false,
                    ),
                    'hover_fields' => array(
                        'link_color' => false, // disable for special field.
                    )
                ),
            ],
            /*Header cart title*/
            [
                'name' => $prefix . '_heading_cart_title',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 12,
                'title' => esc_html__('Cart Title Settings', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ],
            [
                'name' => $prefix . '_title_size',
                'type' => 'slider',
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'device_settings' => true,
                'priority' => 13,
                'css_format' => ".element-cart-icon .wrap-right-element-cart .title-element-cart{ font-size: {{value}};}",
                'label' => esc_html__('Font Size', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ],
            [
                'name' => $prefix . '_title_color',
                'type' => 'modal',
                'section' => $this->section,
                'css_format' => 'styling',
                'priority' => 14,
                'title' => esc_html__('Custom Color', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
                'fields' => [
                    'tabs' => [
                        'default' => esc_html__('Normal', 'fona'),
                        'hover' => esc_html__('Hover', 'fona'),
                    ],
                    'default_fields' => [
                        [
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Color', 'fona'),
                            'selector' => ".element-cart-icon .wrap-right-element-cart .title-element-cart",
                            'css_format' => 'color: {{value}};',
                        ],
                    ],
                    'hover_fields' => [
                        [
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Color', 'fona'),
                            'selector' => ".element-cart-icon:hover .wrap-right-element-cart .title-element-cart",
                            'css_format' => 'color: {{value}};',
                        ],
                    ]
                ]
            ],

            [
                'name' => $prefix . '_heading_cart_total',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 15,
                'title' => esc_html__('Cart Totals Settings', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
                'device_settings' => true,
            ],
            [
                'name' => $prefix . '_total_size',
                'type' => 'slider',
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => "format",
                'device_settings' => true,
                'priority' => 16,
                'css_format' => ".element-cart-icon .wrap-right-element-cart .total-element-cart{ font-size: {{value}};}",
                'label' => esc_html__('Font Size', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
            ],
            [
                'name' => $prefix . '_total_color',
                'type' => 'modal',
                'section' => $this->section,
                'selector' => ".element-cart-icon .total-element-cart",
                'css_format' => 'styling',
                'priority' => 17,
                'title' => esc_html__('Custom Color', 'fona'),
                'required' => ['header_cart_enable_styling', '==', 1],
                'fields' => [
                    'tabs' => [
                        'default' => esc_html__('Normal', 'fona'),
                        'hover' => esc_html__('Hover', 'fona'),
                    ],
                    'default_fields' => [
                        [
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Color', 'fona'),
                            'selector' => ".element-cart-icon .wrap-right-element-cart .total-element-cart",
                            'css_format' => 'color: {{value}};',
                        ],
                    ],
                    'hover_fields' => [
                        [
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Color', 'fona'),
                            'selector' => ".element-cart-icon:hover .wrap-right-element-cart .total-element-cart",
                            'css_format' => 'color: {{value}};',
                        ],
                    ]
                ]
            ]
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
        $atts['icon-style'] = zoo_customize_get_setting('header_cart_icon_style', $args[1]);
        $atts['enable-cart-styling'] = zoo_customize_get_setting('header_cart_enable_styling', $args[1]);
        $atts['show-title'] = zoo_customize_get_setting('header_cart_show_title', $args[1]);
        $atts['show-total'] = zoo_customize_get_setting('header_cart_show_totals', $args[1]);
        $atts['display-style'] = zoo_customize_get_setting('header_cart_display_style');
        $atts['cart-icon'] = zoo_customize_get_setting('header_cart_icon');
        $atts['display-count-position'] = zoo_customize_get_setting('header_cart_icon_count_position', $args[1]);

        $tpl = apply_filters('header/element/cart', ZOO_THEME_DIR . 'core/customize/templates/header/element-woocommerce-cart.php', $atts);
        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Cart());
