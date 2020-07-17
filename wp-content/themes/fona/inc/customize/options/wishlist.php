<?php
/**
 * Customize Wishlist Options
 */
return [
    [
        'type' => 'section',
        'name' => 'zoo_wishlist',
        'title' => esc_html__('Wishlist', 'fona'),
        'panel' => 'woocommerce',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_wishlist_general_settings',
        'type' => 'heading',
        'label' => esc_html__('General Settings', 'fona'),
        'section' => 'zoo_wishlist'
    ],
    [
        'name' => 'zoo_enable_wishlist',
        'type' => 'checkbox',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Enable Wishlist', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_shop_loop_wishlist',
        'type' => 'checkbox',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Enable Shop Wishlist', 'fona'),
        'checkbox_label' => esc_html__('Wishlist button will show in shop loop if checked.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_wishlist_redirect',
        'type' => 'checkbox',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Enable Wishlist Redirect Link', 'fona'),
        'checkbox_label' => esc_html__('Redirect to wishlist page when click to button browse wishlist.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 0
    ],
    [
        'name' => 'zoo_wishlist_page',
        'type' => 'select',
        'section' => 'zoo_wishlist',
        'title' => esc_html__('Wishlist page', 'fona'),
        'default' => '',
        'theme_supports' => 'woocommerce',
        'choices' => zoo_list_pages_by_slug(),
        'required' => ['zoo_enable_wishlist_redirect', '==', 1]
    ],
    [
        'name' => 'zoo_wishlist_add_to_wishlist_settings',
        'type' => 'heading',
        'label' => esc_html__('Add to Wishlist Settings', 'fona'),
        'section' => 'zoo_wishlist',
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_text_add_to_wishlist',
        'type' => 'text',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Label', 'fona'),
        'default' => esc_html__('Add to Wishlist', 'fona'),
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_icon_add_to_wishlist',
        'type' => 'icon',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Icon', 'fona'),
        'default' => [
            'type' => 'zoo-icon',
            'icon' => 'zoo-icon-heart-o'
        ],
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_wishlist_browse_to_wishlist_settings',
        'type' => 'heading',
        'label' => esc_html__('Browse to Wishlist Settings', 'fona'),
        'section' => 'zoo_wishlist',
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_text_browse_to_wishlist',
        'type' => 'text',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Label', 'fona'),
        'default' => esc_html__('Browse Wishlist', 'fona'),
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_icon_browse_to_wishlist',
        'type' => 'icon',
        'section' => 'zoo_wishlist',
        'label' => esc_html__('Icon', 'fona'),
        'default' => [
            'type' => 'zoo-icon',
            'icon' => 'zoo-icon-heart-o'
        ],
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_wishlist_style_settings',
        'type' => 'heading',
        'label' => esc_html__('Wishlist Style', 'fona'),
        'section' => 'zoo_wishlist',
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_wishlist_icon_size',
        'type' => 'slider',
        'label' => esc_html__('Font Size', 'fona'),
        'section' => 'zoo_wishlist',
        'min' => 8,
        'step' => 1,
        'max' => 100,
        'selector' => ".woocommerce ul.products li.product .zoo-wishlist-button>i",
        'css_format' => "font-size: {{value}};",
        'required' => ['zoo_enable_wishlist', '==', 1]
    ],
    [
        'name' => 'zoo_wishlist_shop_style',
        'type' => 'styling',
        'section' => 'zoo_wishlist',
        'title' => esc_html__('Wishlist style', 'fona'),
        'description' => esc_html__('Advanced styling for Wishlist in shop loop', 'fona'),
        'required' => ['zoo_enable_wishlist', '==', 1],
        'selector' => [
            'normal' => '.woocommerce ul.products li.product .zoo-wishlist-button',
            'hover' => '.woocommerce ul.products li.product .zoo-wishlist-button:hover',
        ],
        'css_format' => 'styling',
        'priority' => 11,
        'default' => [],
        'fields' => [
            'normal_fields' => [
                'link_color' => false,
                'margin' => false,
                'padding' => false,
                'bg_image' => false,
                'device_settings' => false,
                'link_hover_color'   => false,
            ],
            'hover_fields' => [
                'link_color' => false
            ]
        ]
    ]
];
