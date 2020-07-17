<?php

/**
 * Zoo_Customize_Builder_Element_Account
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Account extends Zoo_Customize_Builder_Element
{
    function __construct()
    {
        $this->id = 'header-account';
        $this->title = esc_html__('Account', 'fona');
        $this->width = 2;
        $this->selector = '.element-header-account';
        $this->section = 'header_account';
        $this->panel = 'header_settings';
    }

    public function get_builder_configs()
    {
        return [
            'name' => $this->title,
            'id' => $this->id,
            'width' => $this->width,
            'section' => $this->section
        ];
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $config =  [
            [
                'name' => $this->section,
                'type' => 'section',
                'panel' => $this->panel,
                'title' => $this->title,
            ],
            [
                'name' => 'header_account_general_heading',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 0,
                'title' => esc_html__('General Settings', 'fona'),
            ],
            [
                'name'            => 'header_account_type',
                'type'            => 'select',
                'priority' => 0,
                'section'         => $this->section,
                'selector'        => $this->selector,
                'render_callback' => [$this, 'render'],
                'default'         => 'link',
                'title'           => esc_html__('Layout Type', 'fona'),
                'choices'         => [
                    'link' => esc_html__('Link', 'fona'),
                    'modal'  => esc_html__('Modal', 'fona'),
                    'off-canvas'  => esc_html__('Off Canvas', 'fona'),
                ]
            ],
            [
                'name'            => 'header_account_style',
                'type'            => 'select',
                'priority' => 0,
                'section'         => $this->section,
                'selector'        => $this->selector,
                'render_callback' => [$this, 'render'],
                'default'         => 'normal',
                'device_settings' => true,
                'title'           => esc_html__('Style', 'fona'),
                'choices'         => [
                    'normal' => esc_html__('Normal', 'fona'),
                    'button'  => esc_html__('Button', 'fona'),
                ]
            ],
            [
                'name' => 'header_account_show_label',
                'type' => 'checkbox',
                'section' => $this->section,
                'priority' => 1,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Show Label', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'device_settings' => true,
                'default' => [
                    'desktop' => 0,
                    'mobile' => 0,
                ]
            ],
            [
                'name' => 'header_account_label_text',
                'type' => 'text',
                'priority' => 2,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Label Text', 'fona'),
                'default' => '',
                'device_settings' => false,
                'required' => ['header_account_show_label', '==', 1]
            ],
            [
                'name' => 'header_account_icon',
                'type' => 'icon',
                'section' => $this->section,
                'selector' => $this->selector,
                'render_callback' => [$this, 'render'],
                'priority' => 3,
                'title' => esc_html__('Display Icon', 'fona'),
                'default' => [
                    'type' => 'zoo-icon',
                    'icon' => 'zoo-icon-user'
                ]
            ],
            [
                'name' => 'header_account_custom_login_url',
                'type' => 'text',
                'priority' => 4,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('User Login URL', 'fona'),
                'default' => '#',
                'device_settings' => false
            ],
            [
                'name' => 'header_account_custom_register_url',
                'type' => 'text',
                'priority' => 5,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('User Registration URL', 'fona'),
                'default' => '#',
                'device_settings' => false
            ],
            [
                'name' => 'header_account_custom_dashboard_url',
                'type' => 'text',
                'priority' => 6,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('User Dashboard URL', 'fona'),
                'default' => '#',
                'device_settings' => false
            ],
            [
                'name' => 'header_account_extra_modal_links',
                'type' => 'heading',
                'description' => esc_html__('NOTICE: These links will be showed for logged in WordPress users only!', 'fona'),
                'section' => $this->section,
                'priority' => 8,
                'title' => esc_html__('Extra Modal Links', 'fona'),
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_1_label',
                'type' => 'text',
                'priority' => 9,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 1 Label', 'fona'),
                'default' => esc_html__('Link 1', 'fona'),
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_1_url',
                'type' => 'text',
                'priority' => 10,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 1 URL', 'fona'),
                'default' => '#',
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_2_label',
                'type' => 'text',
                'priority' => 11,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 2 Label', 'fona'),
                'default' => esc_html__('Link 2', 'fona'),
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_2_url',
                'type' => 'text',
                'priority' => 12,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 2 URL', 'fona'),
                'default' => '#',
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_3_label',
                'type' => 'text',
                'priority' => 13,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 3 Label', 'fona'),
                'default' => esc_html__('Link 3', 'fona'),
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_modal_link_3_url',
                'type' => 'text',
                'priority' => 14,
                'section' => $this->section,
                'selector' => $this->selector,
                'title' => esc_html__('Link 3 URL', 'fona'),
                'default' => '#',
                'device_settings' => false,
                'required' => ['header_account_popup_modal', '==', 1]
            ],
            [
                'name' => 'header_account_style_heading',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 16,
                'title' => esc_html__('Style Settings', 'fona'),
                'required'=>['header_account_advanced_styling','==',1]
            ],
            [
                'name' => 'header_account_advanced_styling',
                'type' => 'checkbox',
                'section' => $this->section,
                'priority' => 16,
                'render_callback' => [$this, 'render'],
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'default' => 0
            ],
            [
                'name' => 'header_account_icon_size',
                'type' => 'slider',
                'priority' => 17,
                'device_settings' => true,
                'section' => $this->section,
                'min' => 10,
                'step' => 1,
                'max' => 100,
                'selector' => 'format',
                'css_format' => "{$this->selector} .account-icon i{ font-size: {{value}};width: {{value}};height: {{value}}; }",
                'label' => esc_html__('Icon Size', 'fona'),
                'required'=>['header_account_advanced_styling','==',1]
            ],
            [
                'name' => 'header_account_padding',
                'type' => 'slider',
                'priority' => 17,
                'device_settings' => true,
                'section' => $this->section,
                'min' => 0,
                'step' => 1,
                'max' => 20,
                'selector' => 'format',
                'css_format' => "{$this->selector} .account-icon { padding: {{value}}; }",
                'label' => esc_html__('Icon Padding', 'fona'),
                'required'=>['header_account_advanced_styling','==',1],
            ],
            [
                'name'            => 'header_account_link_pos',
                'type'            => 'select',
                'priority' => 18,
                'section'         => $this->section,
                'selector'        => $this->selector,
                'render_callback' => [$this, 'render'],
                'default'         => 'left',
                'device_settings' => true,
                'title'           => esc_html__('Drop down list link account position', 'fona'),
                'required'=>['header_account_advanced_styling','==',1],
                'choices'         => [
                    'left' => esc_html__('Left', 'fona'),
                    'right'  => esc_html__('Right', 'fona'),
                ]
            ],
            [
                'name' => 'header_account_icon_styling',
                'type' => 'styling',
                'priority' => 20,
                'section' => $this->section,
                'title' => esc_html__('Custom Styling', 'fona'),
                'description' => esc_html__('Advanced styling for Account element', 'fona'),
                'selector' => array(
                    'normal' => "{$this->selector}.custom-color-style .account-element-link, {$this->selector}.custom-color-style.button-style .account-element-link",
                    'normal_link_color' => "{$this->selector}.custom-color-style .wrap-dashboard-form a, {$this->selector}.custom-color-style.button-style .account-element-link",
                    'normal_bg_color' => "{$this->selector}.custom-color-style .wrap-dashboard-form, {$this->selector}.custom-color-style.button-style .account-element-link",
                    'normal_box_shadow' => "{$this->selector}.custom-color-style .wrap-dashboard-form, {$this->selector}.custom-color-style.button-style .account-element-link",
                    'hover' => "{$this->selector}.custom-color-style .account-element-link:hover, {$this->selector}.custom-color-style.button-style .account-element-link:hover",
                    'hover_link_color' => "{$this->selector}.custom-color-style .wrap-dashboard-form a:hover, {$this->selector}.custom-color-style.button-style .account-element-link:hover",
                ),
                'css_format' => 'styling',
                'default' => array(),
                'required' => ['header_account_advanced_styling','==',1],
                'fields' => array(
                    'normal_fields' => array(
                        'link_hover_color' => false, // disable for special field.
                        'margin' => false,
                        'bg_image' => false,
                        'border_heading' => false,
                        'border_style' => false,
                        'border_width' => false,
                        'border_color' => false,
                        'border_radius' => false,
                    ),'hover_fields' => array(
                        'bg_heading' => false,
                        'border_style' => false,
                        'border_width' => false,
                        'border_color' => false,
                        'border_radius' => false,
                    ),
                ),
            ],
            ['name' => 'header_account_icon_border',
                'type' => 'modal',
                'priority' => 21,
                'section' => $this->section,
                'selector' => "{$this->selector} .account-icon",
                'css_format' => 'styling',
                'title' => esc_html__('Border', 'fona'),
                'description' => esc_html__('Border & border radius', 'fona'),
                'required'=>['header_account_advanced_styling','==',1],
                'fields' => [
                    'tabs' => [
                        'default' => '_',
                    ],
                    'default_fields' => [
                        [
                            'name' => 'border_style',
                            'type' => 'select',
                            'class' => 'clear',
                            'label' => esc_html__('Border Style', 'fona'),
                            'default' => 'none',
                            'choices' => array(
                                '' => esc_html__('Default', 'fona'),
                                'none' => esc_html__('None', 'fona'),
                                'solid' => esc_html__('Solid', 'fona'),
                                'dotted' => esc_html__('Dotted', 'fona'),
                                'dashed' => esc_html__('Dashed', 'fona'),
                                'double' => esc_html__('Double', 'fona'),
                                'ridge' => esc_html__('Ridge', 'fona'),
                                'inset' => esc_html__('Inset', 'fona'),
                                'outset' => esc_html__('Outset', 'fona'),
                            ),
                            'device_settings' => true,
                            'css_format' => 'border-style: {{value}};',
                            'selector' => "$this->selector .account-icon, {$this->selector}.custom-color-style.button-style .account-element-link",
                        ],

                        [
                            'name' => 'border_width',
                            'type' => 'css_rule',
                            'label' => esc_html__('Border Width', 'fona'),
                            'required' => array('border_style', '!=', 'none'),
                            'selector' => "$this->selector .account-icon, {$this->selector}.custom-color-style.button-style .account-element-link",
                            'device_settings' => true,
                            'css_format' => array(
                                'top' => 'border-top-width: {{value}};',
                                'right' => 'border-right-width: {{value}};',
                                'bottom' => 'border-bottom-width: {{value}};',
                                'left' => 'border-left-width: {{value}};'
                            ),
                        ],
                        [
                            'name' => 'border_color',
                            'type' => 'color',
                            'label' => esc_html__('Border Color', 'fona'),
                            'required' => array('border_style', '!=', 'none'),
                            'selector' => "$this->selector .account-icon, {$this->selector}.custom-color-style.button-style .account-element-link",
                            'css_format' => 'border-color: {{value}};',
                            'device_settings' => true,
                        ],
                        [
                            'name' => 'border_radius',
                            'type' => 'slider',
                            'device_settings' => true,
                            'label' => esc_html__('Border Radius', 'fona'),
                            'selector' => "$this->selector .account-icon, {$this->selector}.custom-color-style.button-style .account-element-link",
                            'css_format' => 'border-radius: {{value}};',
                        ],
                    ]
                ]
            ],
            [
                'name' => 'header_account_register_styling',
                'type' => 'styling',
                'priority' => 22,
                'section' => $this->section,
                'title' => esc_html__('Register button Styling', 'fona'),
                'description' => esc_html__('Advanced styling for Account element', 'fona'),
                'selector' => array(
                    'normal' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url",
                    'normal_link_color' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url",
                    'normal_bg_color' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url",
                    'normal_box_shadow' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url",
                    'hover' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url:hover",
                    'hover_link_color' => "{$this->selector}.custom-color-style.button-style .account-element-link.account-register-url:hover",
                ),
                'css_format' => 'styling',
                'default' => array(),
                'required'=>['header_account_advanced_styling','==',1],
                'fields' => array(
                    'normal_fields' => array(
                        'link_hover_color' => false, // disable for special field.
                        'margin' => false,
                        'bg_image' => false,
                        'border_heading' => false,
                    ),'hover_fields' => array(
                        'bg_heading' => false,
                    ),
                ),
            ],
        ];
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    function render()
    {
        $atts = [
            'label' => zoo_customize_get_setting('header_account_label_text'),
            'custom_login_url' => zoo_customize_get_setting('header_account_custom_login_url'),
            'custom_register_url' => zoo_customize_get_setting('header_account_custom_register_url'),
            'custom_dashboard_url' => zoo_customize_get_setting('header_account_custom_dashboard_url'),
            'link_1' => [],
            'link_2' => [],
            'link_3' => []
        ];
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }

        $atts['device'] = $args[1];
        $atts['advanced-styling'] = zoo_customize_get_setting('header_account_advanced_styling', $args[1]);
        $atts['icon'] = zoo_customize_get_setting('header_account_icon');
        $atts['style'] = zoo_customize_get_setting('header_account_style');
        $atts['show-label'] = zoo_customize_get_setting('header_account_show_label', $args[1]);
        $atts['layout-type'] = zoo_customize_get_setting('header_account_type');
        $atts['links-position'] = zoo_customize_get_setting('header_account_link_pos', $args[1]);

        if ($atts['layout-type']!='link') {
            $link_1_url = zoo_customize_get_setting('header_account_modal_link_1_url');
            $link_2_url = zoo_customize_get_setting('header_account_modal_link_2_url');
            $link_3_url = zoo_customize_get_setting('header_account_modal_link_3_url');
            if ($link_1_url) {
                $atts['link_1']['url'] = $link_1_url;
                $atts['link_1']['label'] = zoo_customize_get_setting('header_account_modal_link_1_label');
            }
            if ($link_2_url) {
                $atts['link_2']['url'] = $link_2_url;
                $atts['link_2']['label'] = zoo_customize_get_setting('header_account_modal_link_2_label');
            }
            if ($link_3_url) {
                $atts['link_3']['url'] = $link_3_url;
                $atts['link_3']['label'] = zoo_customize_get_setting('header_account_modal_link_3_label');
            }
        }

        $tpl = apply_filters('header/element/account', ZOO_THEME_DIR . 'core/customize/templates/header/element-account.php', $atts);
        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Account());
