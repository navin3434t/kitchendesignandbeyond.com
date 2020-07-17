<?php

/**
 * Zoo_Customize_Header_Builder
 *
 * @package  Zoo_Theme\Core\Customize\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
class Zoo_Customize_Header_Builder extends Zoo_Customize_Builder_Block
{
    /**
     * ID
     *
     * @var  string
     */
    const ID = 'header';

    function get_config()
    {
        return [
            'id' => self::ID,
            'title' => esc_html__('Header Builder', 'fona'),
            'control_id' => 'header_builder_panel',
            'panel' => 'header_settings',
            'section' => 'header_builder_panel',
            'devices' => [
                'desktop' => esc_html__('Desktop', 'fona'),
                'mobile' => esc_html__('Mobile/Tablet', 'fona'),
            ],
        ];
    }

    function get_rows_config()
    {
        return [
            'top' => esc_html__('Header Top', 'fona'),
            'main' => esc_html__('Header Main', 'fona'),
            'bottom' => esc_html__('Header Bottom', 'fona'),
            'sidebar' => esc_html__('Off Canvas Sidebar', 'fona'),
        ];
    }

    function customize()
    {
        return [
            [
                'name' => 'header_builder_panel',
                'type' => 'js_raw',
                'section' => 'header_builder_panel',
                'theme_supports' => '',
                'title' => esc_html__('Header Builder', 'fona'),
                'selector' => '#site-header',
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'container_inclusive' => true
            ],
        ];
    }

    function row_config($section = false, $section_name = false)
    {
        $selector = '.site-header .' . str_replace('_', '-', $section);

        return [
            [
                'name' => $section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => $section_name,
            ],
            [
                'name' => $section . '_enable',
                'type' => 'checkbox',
                'section' => $section,
                'selector' => $selector,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'title' => esc_html__('Show/Hide', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'device_settings' => true,
                'default' => [
                    'desktop' => 1,
                    'mobile' => 1,
                ]
            ],
            [
                'name' => $section . '_layout',
                'type' => 'select',
                'section' => $section,
                'title' => esc_html__('Layout', 'fona'),
                'selector' => $selector,
                'css_format' => 'html_class',
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'default'         => 'full-width-contained',
                'choices'         => [
                    'contained' => esc_html__('Contained', 'fona'),
                    'full-width' => esc_html__('Full Width', 'fona'),
                    'full-width-contained' => esc_html__('Full width - Contained', 'fona'),
                ]
            ],
            [
                'name' => $section . '_noti_layout',
                'type' => 'custom_html',
                'section' => $section,
                'title' => '',
                'description' => esc_html__("Layout <code>Full width - Contained</code> and <code>Full Width</code> will not fit browser width because you've selected <a class='focus-control' data-id='site_layout' href='#'>Site Layout</a> as <code>Boxed</code> or <code>Framed</code>", 'fona'),
                'required' => [
                    ['site_layout', '=', ['site-boxed', 'site-framed']],
                ]
            ],
            [
                'name' => $section . '_height',
                'type' => 'slider',
                'section' => $section,
                'device_settings' => true,
                'max'             => 250,
                'selector'        => $selector . " .wrap-builder-items>.row",
                'css_format'      => 'height: {{value}};',
                'title'           => esc_html__('Height', 'fona'),
            ],
            [
                'name' => $section . '_font_size',
                'type' => 'slider',
                'section' => $section,
                'min' => 0,
                'max' => 100,
                'device_settings' => true,
                'title' => esc_html__('Font Size', 'fona'),
                'selector'        => "{$selector}",
                'css_format' => 'font-size: {{value}};'
            ],
            [
                'name' => $section . '_styling',
                'type' => 'styling',
                'section' => $section,
                'title' => esc_html__('Styling', 'fona'),
                'description' => sprintf(__('Advanced styling for %s', 'fona'), $section_name),
                'live_title_field' => 'title',
                'field_class'=>'no-hide',
                'selector' => [
                    'normal' => "{$selector}",
                    'normal_link_color' => "{$selector} a,  {$selector} .nav-menu>li>a",
                    'normal_border_style' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_border_width' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_border_color' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_link_hover_color' => "{$selector} a:hover, {$selector} .nav-menu>.current-menu-item>a, {$selector} .nav-menu>li:hover>a, {$selector} ul.nav-menu > li.active > a",
                ],
                'css_format' => 'styling',
                'fields' => [
                    'normal_fields' => [
                        'margin' => false
                    ],
                    'hover_fields' => false
                ]
            ],
        ];
    }

    function row_sidebar_config($section, $section_name)
    {

        $selector = '.header-off-canvas-sidebar .wrap-header-off-canvas';

        $config = array(
            array(
                'name' => $section,
                'type' => 'section',
                'panel' => 'header_settings',
                'theme_supports' => '',
                'title' => $section_name,
            ),

            array(
                'name' => $section . '_animate',
                'type' => 'select',
                'section' => $section,
                'selector' => 'body',
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'css_format' => 'html_class',
                'title' => esc_html__('Animation', 'fona'),
                'default' => 'off-canvas-slide-left',
                'device_settings' => true,
                'choices' => array(
                    'off-canvas-slide-left' => esc_html__('Slide From Left', 'fona'),
                    'off-canvas-slide-right' => esc_html__('Slide From Right', 'fona'),
                    'off-canvas-full-screen' => esc_html__('Full-screen Overlay', 'fona'),
                    'off-canvas-dropdown' => esc_html__('Toggle Dropdown', 'fona'),
                )
            ),


            array(
                'name' => $section . '_text_mode',
                'type' => 'image_select',
                'section' => $section,
                'selector' => '#header-off-canvas-sidebar, .close-sidebar-panel',
                'css_format' => 'html_class',
                'title' => esc_html__('Text Mode Color', 'fona'),
                'default' => 'is-text-dark',
                'device_settings' => true,
                'choices' => array(
                    'is-text-dark' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/text_mode_dark.svg',
                    ),
                    'is-text-light' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/text_mode_light.svg',
                    ),
                )
            ),
            array(
                'name' => $section . '_styling',
                'type' => 'styling',
                'section' => $section,
                'title' => esc_html__('Styling', 'fona'),
                'description' => sprintf(__('Advanced styling for %s', 'fona'), $section_name),
                'live_title_field' => 'title',
                'selector' => array(
                    'normal' => $selector,
                    'normal_link_color' => "{$selector} a",
                    'hover_link_color' => "{$selector} a:hover",
                    'normal_bg_color' => "{$selector}",
                    'normal_bg_image' => "{$selector}",
                    'normal_bg_attachment' => "{$selector}",
                    'normal_bg_cover' => "{$selector}",
                    'normal_bg_repeat' => "{$selector}",
                    'normal_bg_position' => "{$selector}",
                    'normal_box_shadow' => "{$selector}",
                ),
                'css_format' => 'styling', // styling
                'fields' => array(
                    'normal_fields' => array(
                        'link_hover_color' => false, // disable for special field.
                        'border_color' => false,
                        'border_radius' => false,
                        'border_width' => false,
                        'border_style' => false,
                    ),
                    'hover_fields' => array(
                        'text_color' => false,
                        'padding' => false,
                        'bg_color' => false,
                        'bg_heading' => false,
                        'bg_cover' => false,
                        'bg_image' => false,
                        'bg_repeat' => false,
                        'border_heading' => false,
                        'border_color' => false,
                        'border_radius' => false,
                        'border_width' => false,
                        'border_style' => false,
                        'box_shadow' => false,
                    ), // disable hover tab and all fields inside.
                )
            ),


        );
        return $config;
    }

    /**
     * @internal  Used as a callback
     */
    static function render()
    {
        $html_class       = ['site-header'];
        $active_template  = get_theme_mod('active_header_template');
        $layout_builder   = Zoo_Customize_Builder::get_instance();
        $frontend_builder = Zoo_Customize_Frontend_Builder::get_instance();

        $frontend_builder->set_config_items($layout_builder->get_builder_items(self::ID));

        $abs_header = zoo_customize_get_setting('header_absolute_enable');

        if ($abs_header) {
            $html_class[] = 'header-absolute';
        }

        if ($active_template) {
            $html_class[] = preg_replace('/(-{1}[0-9]+)/', '', $active_template);
        }
        $style = zoo_customize_get_setting('header_builder_style_preset');
        if($style!=''&&$style!='none'){
            $html_class[] = 'header-style-'.$style;
        }
        ?>
        <header id="site-header" class="<?php echo esc_attr(join($html_class, ' ')) ?>">
            <?php $frontend_builder->render(); ?>
        </header>
        <?php
    }
}
