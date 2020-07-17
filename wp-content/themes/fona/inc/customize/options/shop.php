<?php
/**
 * Customize for Shop loop product
 */
return [
	[
		'type'           => 'section',
		'name'           => 'zoo_shop',
		'title'          => esc_html__( 'Shop Page', 'fona' ),
		'panel'          => 'woocommerce',
		'theme_supports' => 'woocommerce'
	],
	[
		'name'           => 'zoo_shop_general_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'General Settings', 'fona' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
	[
		'type'        => 'number',
		'name'        => 'zoo_products_number_items',
		'label'       => esc_html__( 'Product Per Page', 'fona' ),
		'section'     => 'zoo_shop',
		'description' => esc_html__( 'Number product display per page.', 'fona' ),
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 100,
			'class' => 'zoo-range-slider'
		),
		'default'     => 9
	],
	[
		'name'           => 'zoo_enable_catalog_mod',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Catalog Mod', 'fona' ),
		'checkbox_label' => esc_html__( 'Will be enabled if checked.', 'fona' ),
		'theme_supports' => 'woocommerce',
		'default'        => 0
	],
	[
		'name'           => 'zoo_enable_free_shipping_notice',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Free Shipping Notice', 'fona' ),
		'checkbox_label' => esc_html__( 'Free shipping thresholds will show in cart if checked.', 'fona' ),
		'theme_supports' => 'woocommerce',
		'default'        => 1,
	],
	[
		'name'           => 'zoo_enable_shop_heading',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Shop Heading', 'fona' ),
		'checkbox_label' => esc_html__( 'Display product archive title and description.', 'fona' ),
		'theme_supports' => 'woocommerce',
		'default'        => 1,
	],
	[
		'name'        => 'zoo_shop_banner',
		'type'        => 'image',
		'section'     => 'zoo_shop',
		'title'       => esc_html__( 'Shop banner', 'fona' ),
		'description' => esc_html__( 'Banner image display at top Products page. It will override by Category image.', 'fona' ),
		'required'    => [ 'zoo_enable_shop_heading', '==', '1' ],
	],
	[
		'name'           => 'zoo_shop_layout_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'Layout Settings', 'fona' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
	[
		'name'    => 'zoo_shop_sidebar',
		'type'    => 'select',
		'section' => 'zoo_shop',
		'title'   => esc_html__( 'Shop Sidebar', 'fona' ),
		'default' => 'left',
		'choices' => [
			'top'        => esc_html__( 'Top (Horizontal)', 'fona' ),
			'left'       => esc_html__( 'Left', 'fona' ),
			'right'      => esc_html__( 'Right', 'fona' ),
			'off-canvas' => esc_html__( 'Off canvas', 'fona' ),
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_shop_full_width',
		'label'          => esc_html__( 'Enable Shop Full Width', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Shop layout will full width if enabled.', 'fona' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_loop_item_border',
		'label'          => esc_html__( 'Enable Product border', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Enable border for product item.', 'fona' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_highlight_featured_product',
		'label'          => esc_html__( 'Enable High light Featured Product', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Featured product will display bigger more than another product.', 'fona' ),
	],
	[
		'type'        => 'number',
		'name'        => 'zoo_shop_loop_item_gutter',
		'label'       => esc_html__( 'Product Gutter', 'fona' ),
		'section'     => 'zoo_shop',
		'description' => esc_html__( 'White space between product item.', 'fona' ),
		'input_attrs' => array(
			'min'   => 0,
			'max'   => 100,
			'class' => 'zoo-range-slider'
		),
		'default'     => 30
	],
	[
		'name'        => 'zoo_shop_cols_desktop',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns', 'fona' ),
		'description' => esc_html__( 'Number product per row in shop page.', 'fona' ),
		'section'     => 'zoo_shop',
		'input_attrs' => array(
			'min'   => 2,
			'max'   => 6,
			'class' => 'zoo-range-slider'
		),
		'default'     => 4
	],
	[
		'name'        => 'zoo_shop_cols_tablet',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns on Tablet', 'fona' ),
		'section'     => 'zoo_shop',
		'unit'        => false,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 4,
			'class' => 'zoo-range-slider'
		),
		'default'     => 2,
	],
	[
		'name'        => 'zoo_shop_cols_mobile',
		'type'        => 'number',
		'label'       => esc_html__( 'Shop loop columns on Mobile', 'fona' ),
		'section'     => 'zoo_shop',
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 2,
			'class' => 'zoo-range-slider'
		),
		'default'     => 2,
	],
	[
		'name'           => 'zoo_shop_product_item_settings',
		'type'           => 'heading',
		'label'          => esc_html__( 'Product Item Settings', 'fona' ),
		'section'        => 'zoo_shop',
		'theme_supports' => 'woocommerce'
	],
    [
        'name' => 'zoo_product_hover_effect',
        'type' => 'select',
        'section' => 'zoo_shop',
        'title' => esc_html__('Hover Effect', 'fona'),
        'description' => esc_html__('Hover Effect of product item when hover.', 'fona'),
        'default' => 'default',
        'choices' => [
            'default' => esc_html__('Default', 'fona'),
            'style-2' => esc_html__('Style 2', 'fona'),
            'style-3' => esc_html__('Style 3', 'fona'),
            'style-4' => esc_html__('Style 4', 'fona'),
            'style-5' => esc_html__('Style 5', 'fona'),
            'style-6' => esc_html__('Style 6', 'fona'),
        ]
    ],
	[
		'name'           => 'zoo_enable_shop_loop_cart',
		'type'           => 'checkbox',
		'section'        => 'zoo_shop',
		'label'          => esc_html__( 'Enable Shop Loop Cart', 'fona' ),
		'checkbox_label' => esc_html__( 'Button Add to cart will show if checked.', 'fona' ),
		'theme_supports' => 'woocommerce',
		'default'        => 0,
		'required'       => [ 'zoo_enable_catalog_mod', '!=', 1 ],
	],
	[
		'name'    => 'zoo_shop_cart_icon',
		'type'    => 'icon',
		'section' => 'zoo_shop',
		'title'   => esc_html__( 'Cart icon', 'fona' ),
		'default' => [
			'type' => 'zoo-icon',
			'icon' => 'zoo-icon-cart'
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_alternative_images',
		'label'          => esc_html__( 'Enable Alternative Image', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Alternative Image will show if checked.', 'fona' ),
	],
	[
		'type'    => 'select',
		'name'    => 'zoo_sale_type',
		'label'   => esc_html__( 'Sale label type display', 'fona' ),
		'section' => 'zoo_shop',
		'default' => 'text',
		'choices' => [
			'numeric' => esc_html__( 'Numeric', 'fona' ),
			'text'    => esc_html__( 'Text', 'fona' ),
		]
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_new_label',
		'label'          => esc_html__( 'Show New Label', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Stock New will show if checked.', 'fona' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_stock_label',
		'label'          => esc_html__( 'Show Stock Label', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Stock label will show if checked.', 'fona' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_quick_view',
		'label'          => esc_html__( 'Enable Quick View', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Button quick view will show if checked.', 'fona' ),
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_enable_shop_loop_rating',
		'label'          => esc_html__( 'Show rating', 'fona' ),
		'section'        => 'zoo_shop',
		'default'        => '1',
		'checkbox_label' => esc_html__( 'Show rating in product item if checked.', 'fona' ),
	],
	/*Product image thumb for gallery*/
	[
		'name'           => 'zoo_gallery_thumbnail_heading',
		'type'           => 'heading',
		'label'          => esc_html__( 'Gallery Thumbnail', 'fona' ),
		'section'        => 'woocommerce_product_images',
		'theme_supports' => 'woocommerce'
	],
	[
		'type'           => 'number',
		'name'           => 'zoo_gallery_thumbnail_width',
		'label'          => esc_html__( 'Gallery Thumbnail Width', 'fona' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '120',
		'description' => esc_html__( 'Max width of image for gallery thumbnail.', 'fona' ),
	],
	[
		'type'           => 'number',
		'name'           => 'zoo_gallery_thumbnail_height',
		'label'          => esc_html__( 'Gallery Thumbnail Height', 'fona' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '120',
	],
	[
		'type'           => 'checkbox',
		'name'           => 'zoo_gallery_thumbnail_crop',
		'label'          => esc_html__( 'Crop', 'fona' ),
		'section'        => 'woocommerce_product_images',
		'default'        => '0',
		'checkbox_label' => esc_html__( 'Crop Gallery Thumbnail.', 'fona' ),
	],
];
