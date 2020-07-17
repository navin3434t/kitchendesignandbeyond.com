<?php
/**
 * Customize for Site
 */
return [
    [
        'name' => 'zoo_general',
        'type' => 'section',
        'label' => esc_html__('General', 'fona'),
        'priority'=>0
    ],
    [
        'name' => 'zoo_site_layout',
        'type' => 'select',
        'section' => 'zoo_general',
        'title' => esc_html__('Site Layout', 'fona'),
        'description' => esc_html__('Config Layout for site', 'fona'),
        'default' => 'normal',
        'choices' => [
            'normal' => esc_html__('Normal', 'fona'),
            'boxed' => esc_html__('Boxed', 'fona'),
            'full-width' => esc_html__('Full Width', 'fona'),
        ]
    ],[
        'name' => 'zoo_site_max_width',
        'type' => 'number',
        'section' => 'zoo_general',
        'title' => esc_html__('Site Max Width', 'fona'),
        'description' => esc_html__('Max width content of site. Leave it blank or 0, size max width will full width.', 'fona'),
        'default' => '1400',
    ],[
        'name' => 'zoo_disable_breadcrumbs',
        'type' => 'checkbox',
        'section' => 'zoo_general',
        'title' => esc_html__('Disable Breadcrumbs', 'fona'),
        'default' => 0,
        'checkbox_label' => esc_html__('Breadcrumbs will remove if checked.', 'fona'),
    ],[
        'name' => 'zoo_disable_emojis',
        'type' => 'checkbox',
        'section' => 'zoo_general',
        'title' => esc_html__('Disable Emojis', 'fona'),
        'default' => 1,
        'checkbox_label' => esc_html__('Emojis will remove if checked.', 'fona'),
    ],[
        'name' => 'zoo_enable_lazy_image',
        'type' => 'checkbox',
        'section' => 'zoo_general',
        'title' => esc_html__('Enable Lazy Load Images', 'fona'),
        'default' => 1,
        'checkbox_label' => esc_html__('Enable Lazy Load Images if checked.', 'fona'),
    ],[
        'name' => 'zoo_enable_site_meta',
        'type' => 'checkbox',
        'section' => 'zoo_general',
        'title' => esc_html__('Enable Site Meta', 'fona'),
        'default' => 0,
        'checkbox_label' => esc_html__('Show post thumbnail, title, description when share.', 'fona'),
    ],[
        'name' => 'zoo_enable_back_top_top',
        'type' => 'checkbox',
        'section' => 'zoo_general',
        'title' => esc_html__('Enable Back to Top', 'fona'),
        'default' => 1,
        'checkbox_label' => esc_html__('Show button back to top.', 'fona'),
    ],
];