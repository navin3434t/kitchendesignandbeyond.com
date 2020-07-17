<?php

/**
 * Zoo_Customize_Builder_Element_Header_Sticky
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
add_filter('zoo/customizer/config', function(array $configs = [])
{
    $config = [
        [
            'name' => 'header_sticky',
            'type' => 'section',
            'panel' => 'header_settings',
            'title' => esc_html__('Header Sticky', 'fona'),
        ],
        [
            'name' => 'header_absolute_enable',
            'type' => 'checkbox',
            'section' => 'header_sticky',
            'render_callback' => 'Zoo_Customize_Header_Builder::render',
            'title' => esc_html__('Absolute Header', 'fona'),
            'checkbox_label' => esc_html__('If checked, header will overlap content. Helpful for transparent headers.', 'fona')
        ],
        [
            'name' => 'header_top_sticky_enable',
            'type' => 'checkbox',
            'section' => 'header_sticky',
            'render_callback' => 'Zoo_Customize_Header_Builder::render',
            'title' => esc_html__('Enable Header Top Sticky', 'fona'),
            'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
            'device_settings' => true,
            'default' => [
                'desktop' => 0,
                'mobile' => 0,
            ]
        ],
        [
            'name' => 'header_main_sticky_enable',
            'type' => 'checkbox',
            'section' => 'header_sticky',
            'render_callback' => 'Zoo_Customize_Header_Builder::render',
            'title' => esc_html__('Enable Header Main Sticky', 'fona'),
            'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
            'device_settings' => true,
            'default' => [
                'desktop' => 0,
                'mobile' => 0,
            ]
        ],
        [
            'name' => 'header_bottom_sticky_enable',
            'type' => 'checkbox',
            'section' => 'header_sticky',
            'render_callback' => 'Zoo_Customize_Header_Builder::render',
            'title' => esc_html__('Enable Header Bottom Sticky', 'fona'),
            'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
            'device_settings' => true,
            'default' => [
                'desktop' => 0,
                'mobile' => 0,
            ]
        ],
        [
            'name'    => 'header_top_sticky_height',
            'type'    => 'slider',
            'section' => 'header_sticky',
            'min'     => 40,
            'max'     => 100,
            'device_settings' => true,
            'title' => esc_html__('Max Header Top Sticky Height', 'fona'),
            'selector'  => '.site-header .is-sticky.header-top.sticker, .is-sticky.header-row.header-top  .wrap-builder-items>.row',
            'css_format' => 'max-height: {{value}};height: {{value}};min-height:auto'
        ],
        [
            'name'    => 'header_main_sticky_height',
            'type'    => 'slider',
            'section' => 'header_sticky',
            'min'     => 40,
            'max'     => 400,
            'device_settings' => true,
            'title' => esc_html__('Max Header Main Sticky Height', 'fona'),
            'selector'  => '.site-header .is-sticky.header-main.sticker, .is-sticky.header-row.header-main  .wrap-builder-items>.row',
            'css_format' => 'max-height: {{value}};height: {{value}};min-height:auto'
        ],
        [
            'name'    => 'header_bottom_sticky_height',
            'type'    => 'slider',
            'section' => 'header_sticky',
            'min'     => 40,
            'max'     => 100,
            'device_settings' => true,
            'title' => esc_html__('Max Header Bottom Sticky Height', 'fona'),
            'selector'  => '.site-header .is-sticky.header-bottom.sticker, .is-sticky.header-row.header-bottom .wrap-builder-items>.row',
            'css_format' => 'max-height: {{value}};height: {{value}};min-height:auto'
        ],
        [
            'name'            => 'header_sticky_animation',
            'type'            => 'select',
            'section'         => 'header_sticky',
            'title'           => esc_html__('Sticky Animation', 'fona'),
            'selector'        => '.site-header .is-sticky',
            'css_format'      => 'html_class',
            'default'         => 'none',
            'choices'         => [
                'none' => esc_html__('None', 'fona'),
                'jump-down' => esc_html__('Jump Down', 'fona')
            ]
        ],
        [
            'name' => 'header_sticky_logo',
            'type' => 'image',
            'section' => 'header_sticky',
            'device_settings' => false,
            'render_callback' => 'Zoo_Customize_Header_Builder::render',
            'title' => esc_html__('Custom Sticky Logo', 'fona')
        ],
        [
            'name'    => 'header_sticky_logo_height',
            'type'    => 'slider',
            'section' => 'header_sticky',
            'min'     => 40,
            'max'     => 100,
            'device_settings' => true,
            'title' => esc_html__('Max Height of Logo Sticky', 'fona'),
            'selector'  => '.item-block-logo .sticky-logo, .is-sticky .site-branding .wrap-logo .site-logo',
            'css_format' => 'max-height: {{value}}'
        ],
        [
            'name' => 'header_sticky_styling',
            'type' => 'styling',
            'section' => 'header_sticky',
            'title' => esc_html__('Sticky Styling', 'fona'),
            'live_title_field' => 'title',
            'selector' => [
                'normal' => ".site-header .is-sticky.header-row.sticker",
                'normal_box_shadow' => ".site-header .is-sticky.header-row.sticker",
                'normal_text_color' => ".site-header .is-sticky.header-row.sticker",
                'normal_link_color' => ".site-header .is-sticky.header-row.sticker a",
                'normal_link_hover_color' => ".site-header .is-sticky.header-row.sticker a:hover, .site-header .is-sticky.header-row .nav-menu > .current-menu-item > a, .site-header .is-sticky.header-row .nav-menu > li > a:hover, .site-header .is-sticky.header-row .nav-menu > li:hover > a, .site-header .is-sticky.header-row .nav-menu > li.current_page_parent > a",
            ],
            'field_class' => 'no-hide',
            'css_format' => 'styling', // styling
            'fields' => [
                'normal_fields' => [
                    'padding' => false, // disable for special field.
                    'margin' => false, // disable for special field.
                    'bg_image' => false, // disable for special field.
                    'border_heading' => false, // disable for special field.
                    'border_style' => false, // disable for special field.
                    'border_color' => false, // disable for special field.
                    'border_radius' => false // disable for special field.
                ],
                'hover_fields' => false
            ]
        ]
    ];

    return $config;
});
