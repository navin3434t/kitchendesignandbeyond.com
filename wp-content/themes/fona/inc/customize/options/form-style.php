<?php
/**
 * Customize for Form field and Button Style
 */
return [ 
    [
        'name' => 'zoo_form_field_style',
        'type' => 'section',
        'label' => esc_html__('Form Field and Button Style', 'fona'),
        'panel' => 'zoo_style',
    ],
    [
        'name' => 'zoo_form_field_heading_color',
        'type' => 'heading',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Form Field', 'fona'),
    ],
    [
        'name' => 'zoo_form_field_color',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Color', 'fona'),
        'selector' => '.text-field, input[type="text"], input[type="search"], input[type="password"], textarea, input[type="email"], input[type="tel"]',
        'css_format' => 'color: {{value}};',
    ],    [
        'name' => 'zoo_form_field_border_color',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Border Color', 'fona'),
        'selector' => '.text-field, input[type="text"], input[type="search"], input[type="password"], textarea, input[type="email"], input[type="tel"]',
        'css_format' => 'border-color: {{value}};',
    ],[
        'name' => 'zoo_form_field_border_color_active',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Border Color Active', 'fona'),
        'selector' => '.text-field:focus, input[type="text"]:focus, input[type="search"]:focus, input[type="password"]:focus, textarea:focus, input[type="email"]:focus, input[type="tel"]:focus',
        'css_format' => 'border-color: {{value}};',
    ],
    [
        'name' => 'zoo_form_field_background',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Background', 'fona'),
        'selector' => '.text-field, input[type="text"], input[type="search"], input[type="password"], textarea, input[type="email"], input[type="tel"]',
        'css_format' => 'background-color: {{value}};',
    ],
    [
        'name' => 'zoo_form_field_button_heading',
        'type' => 'heading',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Button', 'fona'),
    ],
    [
        'name' => 'zoo_button_color',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Color', 'fona'),
        'selector' => '.btn, input[type="submit"], .button, button',
        'css_format' => 'color: {{value}};',
    ], [
        'name' => 'zoo_button_background_color',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Background Color', 'fona'),
        'selector' => '.btn, input[type="submit"], .button, button',
        'css_format' => 'background-color: {{value}};',
    ],[
        'name' => 'zoo_button_color_hover',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Color hover', 'fona'),
        'selector' => '.btn:hover, input[type="submit"]:hover, .button:hover, button:hover',
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_button_background_color_hover',
        'type' => 'color',
        'section' => 'zoo_form_field_style',
        'title' => esc_html__('Background Color hover', 'fona'),
        'selector' => '.btn:hover, input[type="submit"]:hover, .button:hover, button:hover',
        'css_format' => 'background-color: {{value}};',
    ],
];
