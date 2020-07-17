<?php
/**
 * Customize for Shop loop product
 */
return [
    [
        'type' => 'section',
        'name' => 'zoo_single_product',
        'title' => esc_html__('Product Page', 'fona'),
        'panel' => 'woocommerce',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_single_product_general_settings',
        'type' => 'heading',
        'label' => esc_html__('General Settings', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_enable_product_share',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Product Share', 'fona'),
        'checkbox_label' => esc_html__('Show product share if checked.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_sticky_add_to_cart',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Sticky Add to Cart', 'fona'),
        'checkbox_label' => esc_html__('Block sticky add to cart will show if checked.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 1,
    ],[
        'name' => 'zoo_enable_buy_now',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Buy Now', 'fona'),
        'checkbox_label' => esc_html__('Buy now button allow use auto redirect to cart page after product added to cart.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 0,
    ],[
        'name' => 'zoo_enable_term_buy_now',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Term Buy Now', 'fona'),
        'checkbox_label' => esc_html__('Label Agree with term with show.', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 1,
        'required' => ['zoo_enable_buy_now', '==', '1'],
    ],[
        'name' => 'zoo_enable_single_product_nav',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Next & Previous product', 'fona'),
        'theme_supports' => 'woocommerce',
        'default' => 0,
        'checkbox_label' => esc_html__('Show next and previous products navigation.', 'fona'),

    ],
    [
        'name' => 'zoo_single_product_layout_settings',
        'type' => 'heading',
        'label' => esc_html__('Layout & Gallery Settings', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_single_product_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Layout', 'fona'),
        'default' => 'vertical-thumb',
        'choices' => [
            'vertical-thumb' => esc_html__('Product V1', 'fona'),
            'horizontal-thumb' => esc_html__('Product V2', 'fona'),
            'carousel' => esc_html__('Product V3', 'fona'),
            'grid-thumb' => esc_html__('Product V4', 'fona'),
            'sticky-1' => esc_html__('Product V5', 'fona'),
            'sticky-2' => esc_html__('Product V6', 'fona'),
            'sticky-3' => esc_html__('Product V7', 'fona'),
            //'accordion' => esc_html__('Accordion', 'fona'),
            'custom' => esc_html__('Custom', 'fona'),
        ]
    ],[
        'name' => 'zoo_single_product_content_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Content Layout', 'fona'),
        'default' => 'right_content',
        'required' => ['zoo_single_product_layout', '==', 'custom'],
        'choices' => [
            'right_content' => esc_html__('Right Content', 'fona'),
            'left_content' => esc_html__('Left Content', 'fona'),
            'full_content' => esc_html__('Full width Content', 'fona'),
            'sticky_content' => esc_html__('Sticky Content', 'fona'),
        ]
    ],[
        'name' => 'zoo_product_gallery_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Gallery Type', 'fona'),
        'default' => 'vertical-left',
        'required' => ['zoo_single_product_layout', '==', 'custom'],
        'choices' => [
            'vertical-left' => esc_html__('Vertical Left Thumb', 'fona'),
            'vertical-right' => esc_html__('Vertical Right Thumb', 'fona'),
            'horizontal' => esc_html__('Horizontal', 'fona'),
            'slider' => esc_html__('Slider', 'fona'),
            'grid' => esc_html__('Grid', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_product_thumb_cols',
        'type' => 'number',
        'label' => esc_html__('Thumbnail columns', 'fona'),
        'description' => esc_html__('Number thumbnail gallery display same time.', 'fona'),
        'section' => 'zoo_single_product',
        'input_attrs' => array(
            'min' => 2,
            'max' => 6,
            'class'=>'zoo-range-slider'
        ),
        'default' => 4,
    ],
    [
        'name' => 'zoo_product_tabs_setting',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Tabs Type', 'fona'),
        'default' => 'tabs',
        'required' => ['zoo_single_product_layout', '==', 'custom'],
        'choices' => [
            'tabs' => esc_html__('Tabs', 'fona'),
            'accordion' => esc_html__('Accordion', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_enable_product_zoom',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Product Gallery Zoom', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Enable product zoom for product gallery.', 'fona'),
        'default' => 1,
    ],[
        'name' => 'zoo_enable_product_lb',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Product Gallery Light Box', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Enable light box for product gallery.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_single_product_cart_extend_settings',
        'type' => 'heading',
        'label' => esc_html__('Cart Extend Feature', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_single_product_cart_size_guide',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Size Guide', 'fona'),
        'description' => esc_html__('Disable by set None. Label display in product page will apply follow page title.', 'fona'),
        'default' => '0',
        'choices' => zoo_get_pages()
    ],[
        'name' => 'zoo_single_product_cart_delivery',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Delivery & Return', 'fona'),
        'description' => esc_html__('Disable by set None. Label display in product page will apply follow page title.', 'fona'),
        'default' => '0',
        'choices' => zoo_get_pages()
    ],[
        'name' => 'zoo_single_product_cart_ask_product',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Ask about this product', 'fona'),
        'description' => esc_html__('Disable by set None. Label display in product page will apply follow page title.', 'fona'),
        'default' => '0',
        'choices' => zoo_get_pages()
    ],
    [
        'name' => 'zoo_single_product_extend_settings',
        'type' => 'heading',
        'label' => esc_html__('Extend Feature', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_product_enable_sold_per_day',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Show sold on this day', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Show number products sold inside 24h.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_product_sold_fake_data',
        'type' => 'number',
        'label' => esc_html__('Minimum sold', 'fona'),
        'description' => esc_html__('Fake sold current product, auto disable if set 0.', 'fona'),
        'section' => 'zoo_single_product',
        'default' => 10,
    ],[
        'name' => 'zoo_product_sold_fake_max_data',
        'type' => 'number',
        'label' => esc_html__('Maximum sold', 'fona'),
        'section' => 'zoo_single_product',
        'default' => 50,
    ],
    [
        'name' => 'zoo_product_stock_countdown',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Stock Countdown', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Show Stock Countdown of deal product.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_product_get_order',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Get Order time', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Show expected day will deliver.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_product_get_order_day',
        'type' => 'number',
        'label' => esc_html__('Order Day', 'fona'),
        'description' => esc_html__('Expected day will get order.', 'fona'),
        'section' => 'zoo_single_product',
        'default' => 3,
    ],
    [
        'name' => 'zoo_product_get_visitor',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Show Visitor product', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Show visitor on product.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_product_visitor_fake_data',
        'type' => 'number',
        'label' => esc_html__('Minimum visitor', 'fona'),
        'description' => esc_html__('Fake visitor current product, auto disable if current visitor more than this value .', 'fona'),
        'section' => 'zoo_single_product',
        'default' => 100,
    ],[
        'name' => 'zoo_product_visitor_fake_max_data',
        'type' => 'number',
        'label' => esc_html__('Maximum visitor', 'fona'),
        'section' => 'zoo_single_product',
        'default' => 200,
    ],
    [
        'name' => 'zoo_product_free_shipping_notice',
        'type' => 'checkbox',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Enable Free shipping amount notice', 'fona'),
        'theme_supports' => 'woocommerce',
        'checkbox_label' => esc_html__('Show Free shipping amount in product summary.', 'fona'),
        'default' => 1,
    ],
    [
        'name' => 'zoo_product_guarantee_safe_checkout',
        'type' => 'image',
        'section' => 'zoo_single_product',
        'label' => esc_html__('Guarantee Safe Checkout Logo', 'fona'),
        'theme_supports' => 'woocommerce',
    ],
    [
        'name' => 'zoo_single_product_upsell_settings',
        'type' => 'heading',
        'label' => esc_html__('Upsell Product', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_upsell_products_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Layout', 'fona'),
        'default' => 'grid',
        'choices' => [
            'grid' => esc_html__('Grid', 'fona'),
            'carousel' => esc_html__('Carousel', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_upsell_products_cols',
        'type' => 'number',
        'label' => esc_html__('Columns', 'fona'),
        'description' => esc_html__('Number products per row.', 'fona'),
        'section' => 'zoo_single_product',
        'input_attrs' => array(
            'min' => 1,
            'max' => 10,
            'class'=>'zoo-range-slider'
        ),
        'default' => 4,
    ],
    [
        'name' => 'zoo_single_product_recent_settings',
        'type' => 'heading',
        'label' => esc_html__('Recently Viewed Product', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'type' => 'checkbox',
        'name' => 'zoo_enable_recently_viewed_product',
        'label' => esc_html__('Enable Recently Viewed Products', 'fona'),
        'section' => 'zoo_single_product',
        'default' => '1',
        'checkbox_label' => esc_html__('Recently Viewed Products will show if enabled.', 'fona'),
    ],
    [
        'name' => 'zoo_recently_viewed_product_number',
        'type' => 'number',
        'label' => esc_html__('Maximum recently viewed product', 'fona'),
        'description' => esc_html__('Total number recently viewed product.', 'fona'),
        'section' => 'zoo_single_product',
        'required' => ['zoo_enable_recently_viewed_product', '==', '1'],
        'input_attrs' => array(
            'min' => 1,
            'max' => 20,
            'class'=>'zoo-range-slider'
        ),
        'default' => 8,
    ],

    [
        'name' => 'zoo_recently_viewed_product_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Layout', 'fona'),
        'default' => 'grid',
        'required' => ['zoo_enable_recently_viewed_product', '==', '1'],
        'choices' => [
            'grid' => esc_html__('Grid', 'fona'),
            'carousel' => esc_html__('Carousel', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_recently_viewed_product_cols',
        'type' => 'number',
        'label' => esc_html__('Columns', 'fona'),
        'description' => esc_html__('Number products per row.', 'fona'),
        'section' => 'zoo_single_product',
        'required' => ['zoo_enable_recently_viewed_product', '==', '1'],
        'input_attrs' => array(
            'min' => 1,
            'max' => 10,
            'class'=>'zoo-range-slider'
        ),
        'default' => 4,
    ],    [
        'name' => 'zoo_single_product_related_settings',
        'type' => 'heading',
        'label' => esc_html__('Related Product', 'fona'),
        'section' => 'zoo_single_product',
        'theme_supports' => 'woocommerce'
    ],
    [
        'type' => 'checkbox',
        'name' => 'zoo_enable_related_products',
        'label' => esc_html__('Enable Related Products', 'fona'),
        'section' => 'zoo_single_product',
        'default' => '1',
        'checkbox_label' => esc_html__('Related Products will show if enabled.', 'fona'),
    ],
    [
        'name' => 'zoo_related_products_number',
        'type' => 'number',
        'label' => esc_html__('Maximum related product', 'fona'),
        'description' => esc_html__('Total number related product.', 'fona'),
        'section' => 'zoo_single_product',
        'required' => ['zoo_enable_related_products', '==', '1'],
        'input_attrs' => array(
            'min' => 1,
            'max' =>20,
            'class'=>'zoo-range-slider'
        ),
        'default' => 8,
    ],

    [
        'name' => 'zoo_related_products_layout',
        'type' => 'select',
        'section' => 'zoo_single_product',
        'title' => esc_html__('Layout', 'fona'),
        'default' => 'grid',
        'required' => ['zoo_enable_related_products', '==', '1'],
        'choices' => [
            'grid' => esc_html__('Grid', 'fona'),
            'carousel' => esc_html__('Carousel', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_related_products_cols',
        'type' => 'number',
        'label' => esc_html__('Columns', 'fona'),
        'description' => esc_html__('Number products per row.', 'fona'),
        'section' => 'zoo_single_product',
        'required' => ['zoo_enable_related_products', '==', '1'],
        'input_attrs' => array(
            'min' => 1,
            'max' => 10,
            'class'=>'zoo-range-slider'
        ),
        'default' => 4,
    ],
];