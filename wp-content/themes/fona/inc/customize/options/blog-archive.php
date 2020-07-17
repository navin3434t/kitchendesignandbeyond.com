<?php
/**
 * Customize for Shop loop product
 */
return [
    [
        'name' => 'zoo_blog',
        'type' => 'panel',
        'label' => esc_html__('Blog', 'fona'),
    ],[
        'name' => 'zoo_blog_archive',
        'type' => 'section',
        'label' => esc_html__('Blog Archive', 'fona'),
        'panel' => 'zoo_blog',
    ],
    [
        'name' => 'zoo_blog_general_settings',
        'type' => 'heading',
        'label' => esc_html__('General Settings', 'fona'),
        'section' => 'zoo_blog_archive',
    ],
    [
        'name' => 'zoo_blog_layout',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Layout', 'fona'),
        'default' => 'list',
        'choices' => [
            'list' => esc_html__('List', 'fona'),
            'grid' => esc_html__('Grid', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_blog_grid_img_size',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Image size', 'fona'),
        'description' => esc_html__('Select image size fit with layout you want use for improve performance.', 'fona'),
        'default' => 'medium',
        'required' => ['zoo_blog_layout', '!=', 'list'],
	    'choices'=>zoo_get_image_sizes()

    ],
	[
        'name' => 'zoo_blog_img_size',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Image size', 'fona'),
        'description' => esc_html__('Select image size fit with layout you want use for improve performance.', 'fona'),
        'default' => 'full',
        'required' => ['zoo_blog_layout', '==', 'list'],
	    'choices'=>zoo_get_image_sizes()

    ],
	[
        'name' => 'zoo_blog_cols',
        'type' => 'number',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Columns', 'fona'),
        'default' => 3,
        'required' => ['zoo_blog_layout', '!=', 'list'],
        'input_attrs' => array(
            'min' => 1,
            'max' => 6,
            'class' => 'zoo-range-slider'
        ),
    ],
    [
        'name' => 'zoo_enable_blog_cover',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Blog Cover', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 0
    ],
    [
        'name' => 'zoo_blog_cover',
        'type' => 'styling',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Blog cover style', 'fona'),
        'description' => esc_html__('Styling for categories page', 'fona'),
        'required' => ['zoo_enable_blog_cover', '==', '1'],
        'selector' => [
            'normal' => '.wrap-blog-cover',
        ],
        'css_format' => 'styling',
        'default' => [],
        'fields' => [
            'normal_fields' => [
                'margin' => false,
                'link_color' => false,
                'border_style' => false,
                'border_heading' => false,
                'border_radius' => false,
                'box_shadow' => false,
                'link_hover_color'   => false,
            ],
            'hover_fields' => false
        ]
    ],
    [
        'name' => 'zoo_blog_sidebar_settings',
        'type' => 'heading',
        'label' => esc_html__('Sidebar Settings', 'fona'),
        'section' => 'zoo_blog_archive'
    ],
    [
        'name' => 'zoo_blog_sidebar_config',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Sidebar layout', 'fona'),
        'default' => 'left',
        'choices' => [
            'none' => esc_html__('None', 'fona'),
            'left' => esc_html__('Left', 'fona'),
            'right' => esc_html__('Right', 'fona'),
        ]
    ],[
        'name' => 'zoo_blog_sidebar',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Sidebar', 'fona'),
        'required' => ['zoo_blog_sidebar_config', '!=', 'none'],
        'choices' => zoo_get_registered_sidebars()
    ],
    [
        'name' => 'zoo_blog_item_settings',
        'type' => 'heading',
        'label' => esc_html__('Blog Item', 'fona'),
        'section' => 'zoo_blog_archive',
    ],
    [
        'name' => 'zoo_blog_loop_post_info_style',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Post info style', 'fona'),
        'default' => 'icon',
        'choices' => [
            'icon' => esc_html__('icon', 'fona'),
            'text' => esc_html__('Text', 'fona'),
        ]
    ],
    [
        'name' => 'zoo_enable_loop_author_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Author Post', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_loop_date_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Date Post', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_loop_cat_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Post Categories', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_loop_excerpt',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Blog Excerpt', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 0
    ],
    [
        'name' => 'zoo_loop_excerpt_length',
        'type' => 'number',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Blog Excerpt length', 'fona'),
        'default' => 30,
        'required' => ['zoo_enable_loop_excerpt', '==', 1],
        'input_attrs' => array(
            'min' => 1,
            'max' => 256,
            'class' => 'zoo-range-slider'
        ),
    ],
    [
        'name' => 'zoo_enable_loop_readmore',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Read more', 'fona'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'fona'),
        'default' => 1
    ]
];