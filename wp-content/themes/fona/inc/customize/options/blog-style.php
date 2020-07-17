<?php
/**
 * Customize for General Style
 */
return [ 
    [
        'name' => 'zoo_blog_style',
        'type' => 'section',
        'label' => esc_html__('Blog Style', 'fona'),
        'panel' => 'zoo_style',
    ],
    [
        'name' => 'zoo_blog_archive_heading_color',
        'type' => 'heading',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Blog Archive', 'fona'),
    ],
    [
        'name' => 'zoo_blog_title_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title Color', 'fona'),
        'selector' => ".post-loop-item .entry-title",
        'css_format' => 'color: {{value}};',
    ],    [
        'name' => 'zoo_site_title_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title Color Hover', 'fona'),
        'selector' => ".post-loop-item .entry-title:hover",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_blog_date_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post date color', 'fona'),
        'selector' => ".post-loop-item .post-date",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_excerpt_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Excerpt color', 'fona'),
        'selector' => ".post-loop-item .entry-content",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_readmore_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Read More color', 'fona'),
        'selector' => ".post-loop-item .readmore",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_readmore_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Link color hover', 'fona'),
        'selector' => ".post-loop-item .readmore:hover",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_blog_single_heading_color',
        'type' => 'heading',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Blog Single', 'fona'),
    ],
    [
        'name' => 'zoo_blog_single_title_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title post', 'fona'),
        'selector' => ".post-loop-item .readmore:hover",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_single_info_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post Information', 'fona'),
        'description' => esc_html__('Color of block date post, categories, author.', 'fona'),
        'selector' => "post-detail .post-info",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_single_info_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post Information Link hover', 'fona'),
        'description' => esc_html__('Color hover link of block date post, categories, author.', 'fona'),
        'selector' => ".post-detail .post-info a:hover",
        'css_format' => 'color: {{value}};',
    ],
];
