<?php
/**
 * Zoo_Customize_Footer_Builder
 *
 * @package  Zoo_Theme\Core\Customize\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Footer_Builder extends Zoo_Customize_Builder_Block
{
    const ID = 'footer';

    public function get_config()
    {
        return [
            'id' => self::ID,
            'title' => esc_html__('Footer Builder', 'fona'),
            'control_id' => 'footer_builder_panel',
            'panel' => 'footer_settings',
            'section' => 'footer_builder_panel',
            'devices' => [
                'desktop' => esc_html__('Footer Layout', 'fona')
            ]
        ];
    }

    public function get_rows_config()
    {
        return [
            'top' => esc_html__('Footer Top', 'fona'),
            'main' => esc_html__('Footer Main', 'fona'),
            'bottom' => esc_html__('Footer Bottom', 'fona'),
        ];
    }

    public function customize()
    {
        $config = [
            [
                'name' => 'footer_builder_panel',
                'type' => 'section',
                'panel' => 'footer_settings',
                'title' => esc_html__('Footer Builder', 'fona'),
            ],
            [
                'name' => 'footer_builder_panel',
                'type' => 'js_raw',
                'section' => 'footer_builder_panel',
                'theme_supports' => '',
                'title' => esc_html__('Footer Builder', 'fona'),
                'selector' => '#site-footer',
                'render_callback' => 'Zoo_Customize_Footer_Builder::render',
                'container_inclusive' => true
            ]
        ];

        return $config;
    }

    public function row_config($section = false, $section_name = false)
    {
        if (!$section) {
            $section  = 'footer_top';
        }

        if (!$section_name) {
            $section_name = esc_html__('Footer Top', 'fona');
        }

        $selector = '#site-footer .'.str_replace('_', '-', $section);

        $config  = [
            [
                'name' => $section,
                'type' => 'section',
                'panel' => 'footer_settings',
                'theme_supports' => '',
                'title' => $section_name,
            ],
            [
                'name'            => $section . '_enable',
                'type'            => 'checkbox',
                'section'         => $section,
                'selector'        => $selector,
                'render_callback' => 'Zoo_Customize_Footer_Builder::render',
                'title'           => esc_html__('Show/Hide', 'fona'),
                'checkbox_label'  => esc_html__('Will be showed if checked.', 'fona'),
                'default'         => 1
            ],
            [
                'name' => $section.'_layout',
                'type' => 'select',
                'section' => $section,
                'title' => esc_html__('Layout', 'fona'),
                'selector' => $selector,
                'render_callback' => 'Zoo_Customize_Footer_Builder::render',
                'css_format' => 'html_class',
                'default' => 'full-width-contained',
                'choices' => [
                    'contained' => esc_html__('Contained', 'fona'),
                    'full-width' => esc_html__('Full Width', 'fona'),
                    'full-width-contained' => esc_html__('Full width - Contained', 'fona')
                ]
            ],
            [
                'name' => $section.'_noti_layout',
                'type' => 'custom_html',
                'section' => $section,
                'title' => '',
                'description' => esc_html__("Layout <code>Full width - Contained</code> and <code>Full Width</code> will not fit browser width because you've selected <a class='focus-control' data-id='site_layout' href='#'>Site Layout</a> as <code>Boxed</code> or <code>Framed</code>", 'fona'),
                'required' => [
                    ['site_layout', '=', ['site-boxed', 'site-framed']],
                ]
            ],
            [
                'name' => $section.'_heading',
                'type' => 'color',
                'section' => $section,
                'selector' => join(', ', [$selector.' .widget-title', $selector.' h1', $selector.' h2',  $selector.' h3',  $selector.' h4']),
                'css_format' =>'color: {{value}};',
                'title' => esc_html__('Widget Title Color', 'fona'),
            ],
            [
                'name' => $section.'_widget_align',
                'type' => 'text_align_no_justify',
                'section' => $section,
                'device_settings' => true,
                'selector' => "{$selector} .widget-area",
                'css_format' => 'text-align: {{value}};',
                'title' => esc_html__('Widget Text Align', 'fona'),
            ],
            [
                'name' => $section.'_styling',
                'type' => 'styling',
                'section' => $section,
                'title'  => esc_html__('Styling', 'fona'),
                'description'  => sprintf(__('Advanced styling for %s', 'fona'), $section_name),
                'selector' => [
                    'normal' => "{$selector}",
                    'normal_link_color' => "{$selector} a,{$selector}.footer-row .widget_nav_menu li a",
                    'normal_border_style' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_border_width' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_border_color' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_padding' => "{$selector}.contained .wrap-builder-items, {$selector}:not(.contained)",
                    'normal_link_hover_color' => "{$selector} a:hover,{$selector}.footer-row .widget_nav_menu li a:hover"
                ],
                'field_class'=>'no-hide',
                'css_format' => 'styling', // styling
                'fields' => [
                    'normal_fields' => [
                        'box_shadow' => false,
                        'border_radius' => false,
                    ],
                    'hover_fields' => false
                ]
            ],
        ];

        return $config;
    }

    /**
     * @internal  Used as a callback
     */
    public static function render()
    {
        $html_class       = ['site-footer'];
        $active_template  = get_theme_mod('active_footer_template');
        $layout_builder = Zoo_Customize_Builder::get_instance();
        $frontend_builder = Zoo_Customize_Frontend_Builder::get_instance();

        $frontend_builder->set_id(self::ID);
        $frontend_builder->set_control_id('footer_builder_panel');
        $frontend_builder->set_config_items($layout_builder->get_builder_items(self::ID));

        if ($active_template) {
            $html_class[] = preg_replace('/(-{1}[0-9]+)/', '', $active_template);
        }
        $style = zoo_customize_get_setting('footer_builder_style_preset');
        if($style!=''&&$style!='none'){
            $html_class[] = 'footer-style-'.$style;
        }
        ?>
        <footer id="site-footer" class="<?php echo esc_attr(join($html_class, ' ')) ?>">
            <?php $frontend_builder->render(); ?>
        </footer>
        <?php
    }
}
