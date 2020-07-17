<?php
/**
 * Customize for WooCommerce Style
 */
return [ 
    [
        'name' => 'zoo_woo_style',
        'type' => 'section',
        'label' => esc_html__('Woocommerce Style', 'fona'),
        'panel' => 'zoo_style',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_woo_general_style',
        'type' => 'heading',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('General Style', 'fona'),
    ],
    [
        'name' => 'zoo_woo_rating_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Rating color', 'fona'),
        'selector' => '.woocommerce .star-rating span::before, .comment-form-rating p.stars:hover a::before, .comment-form-rating p.stars a:hover, .comment-form-rating p.stars a.active::before',
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_woo_price_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Price color', 'fona'),
        'selector' => '.total .amount, .woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price, .woocommerce ul.products li.product .price, .price, .amount',
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_woo_sale_price_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Sale Price color', 'fona'),
        'selector' => '.price ins, .woocommerce ul.products li.product .price ins',
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_woo_regular_price_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Regular Price color', 'fona'),
        'selector' => '.price del,.woocommerce ul.products li.product .price del, .woocommerce div.product .summary p.price del, .woocommerce div.product .summary span.price del',
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_woo_shop_loop_style',
        'type' => 'heading',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Shop Loop Item Style', 'fona'),
        'description' => esc_html__('Style of product item in shop loop', 'fona'),
    ],
    [
        'name' => 'zoo_woo_shop_loop_title_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Title color', 'fona'),
        'selector' => '.product .product-loop-title, .products h2.woocommerce-loop-category__title, .woocommerce-cart table.cart .product-name a',
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_woo_shop_loop_title_color_hover',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Title color hover', 'fona'),
        'selector' => '.product .product-loop-title:hover, .products h2.woocommerce-loop-category__title:hover, .woocommerce-cart table.cart .product-name a:hover',
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_woo_shop_loop_cart_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Cart button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for button cart', 'fona'),
        'selector' => array(
            'normal' => 'li.product .wrap-product-img > .button, li.product .wrap-product-img > .added_to_cart',
            'hover' => 'li.product .wrap-product-img > .button:hover, li.product .wrap-product-img > .added_to_cart:hover',
        ),
        'css_format' => 'styling',
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
    ],[
        'name' => 'zoo_woo_shop_loop_quick_view_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Quick View button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for button quickview', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce li.product .btn-quick-view',
            'hover' => '.woocommerce li.product .btn-quick-view:hover',
        ),
        'css_format' => 'styling',
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
    ],[
        'name' => 'zoo_woo_shop_loop_sale_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Sale Label Styling', 'fona'),
        'description' => esc_html__('Advanced styling for sale label.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce ul.products li.product .onsale',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),
            'hover_fields' => false
        ),
    ],[
        'name' => 'zoo_woo_shop_loop_sale_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Sale Label Styling', 'fona'),
        'description' => esc_html__('Advanced styling for sale label.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce ul.products li.product .onsale',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),
            'hover_fields' => false
        ),
    ],[
        'name' => 'zoo_woo_shop_loop_out_stock_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Out of Stock Styling', 'fona'),
        'description' => esc_html__('Advanced styling for Out of Stock label.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce ul.products li.product .out-stock-label',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),
            'hover_fields' => false
        ),
    ],[
        'name' => 'zoo_woo_shop_loop_low_stock_style',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Low Stock Styling', 'fona'),
        'description' => esc_html__('Advanced styling for Low Stock label.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce ul.products li.product .low-stock-label',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),
            'hover_fields' => false
        ),
    ],
    [
        'name' => 'zoo_woo_single_product_style',
        'type' => 'heading',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Single Product Style', 'fona'),
        'description' => esc_html__('Style of single product', 'fona'),
    ],
    [
        'name' => 'zoo_woo_single_product_title_color',
        'type' => 'color',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Title color', 'fona'),
        'selector' => '.woocommerce div.product .product_title',
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_woo_single_product_cart_button_styling',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Cart button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for cart button.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce div.product form.cart .button.single_add_to_cart_button',
            'hover' => '.woocommerce div.product form.cart .button.single_add_to_cart_button:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],[
        'name' => 'zoo_woo_single_product_buy_now_button_styling',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Buy now button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for cart buy now button.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce div.product form.cart .button.single_add_to_cart_button.zoo-buy-now',
            'hover' => '.woocommerce div.product form.cart .button.single_add_to_cart_button.zoo-buy-now:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],[
        'name' => 'zoo_woo_single_product_button_styling',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for button in single product.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce div.product form.cart .button',
            'hover' => '.woocommerce div.product form.cart .button:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],[
        'name' => 'zoo_woo_single_product_tab_styling',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Tabs Styling', 'fona'),
        'description' => esc_html__('Advanced styling for tabs control in single product.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce div.product .woocommerce-tabs ul.tabs li, .tab-heading',
            'hover' => '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .accordion-active .tab-heading, .tab-heading:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],
    [
        'name' => 'zoo_woo_cart_checkout_style',
        'type' => 'heading',
        'section' => 'zoo_woo_style',
        'title' => esc_html__('Cart & Check out Style', 'fona'),
        'description' => esc_html__('Style of Cart and Checkout page', 'fona'),
    ],
    [
        'name' => 'zoo_woo_cart_checkout_primary_button',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Primary Button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for Primary Button.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
            'hover' => '.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],[
        'name' => 'zoo_woo_cart_checkout_primary_button',
        'type' => 'styling',
        'section' =>'zoo_woo_style',
        'title' => esc_html__('Second Button Styling', 'fona'),
        'description' => esc_html__('Advanced styling for Second Button.', 'fona'),
        'selector' => array(
            'normal' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button',
            'hover' => '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover',
        ),
        'css_format' => 'styling',
        'fields' => array(
            'normal_fields' => array(
                'link_color' => false, // disable for special field.
                'link_hover_color' => false, // disable for special field.
                'margin' => false,
                'bg_image' => false,
            ),'hover_fields' => array(
                'link_color' => false, // disable for special field.
            )
        ),
    ],
];
