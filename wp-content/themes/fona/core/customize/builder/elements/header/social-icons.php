<?php
/**
 * Zoo_Customize_Builder_Element_Social_Icons
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
class Zoo_Customize_Builder_Element_Social_Icons extends Zoo_Customize_Builder_Element
{
    public $id = 'social-icons';
    public $section = 'header_social_icons';
    public $class = 'header-social-icons';
    public $selector = '';
    public $panel = 'header_settings';

    public function __construct()
    {
        $this->selector = '.'.$this->class;
    }

    public function get_builder_configs()
    {
        return array(
            'name'    => esc_html__('Social Icons', 'fona'),
            'id'      => $this->id,
            'col'     => 0,
            'width'   => 3,
            'section' =>  $this->section // Customizer section to focus when click settings
        );
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $section = $this->section;
        $prefix  = $this->section;
        $fn      = array( $this, 'render' );
        $selector = "{$this->selector}.element-social-icons";
        $config  = array(
            array(
                'name'           => $section,
                'type'           => 'section',
                'panel'          => $this->panel,
                'theme_supports' => '',
                'title'          => esc_html__('Social Icons', 'fona'),
            ),
            [
                'name' => $prefix . '_heading_general',
                'type' => 'heading',
                'section' => $this->section,
                'priority' => 0,
                'title' => esc_html__('General Settings', 'fona'),
            ],
            array(
                'name'             => $prefix . '_items',
                'type'             => 'repeater',
                'section'          => $section,
                'selector'         => $this->selector,
                'render_callback'  => $fn,
                'title'            => esc_html__('Social Profiles', 'fona'),
                'live_title_field' => 'title',
                'default'          => array(
                   array(
                       'title' => "Facebook",
                       'url' => '#',
                       'icon' => array(
                           'type' => 'cs-font',
                           'icon' => 'cs-font clever-icon-facebook',
                       )
                   ),
                    array(
                        'title' => "Twitter",
                        'url' => '#',
                        'icon' => array(
                            'type' => 'cs-font',
                            'icon' => 'cs-font clever-icon-twitter',
                        )
                    ),
                    array(
                        'title' => "Youtube",
                        'url' => '#',
                        'icon' => array(
                            'type' => 'cs-font',
                            'icon' => 'cs-font clever-icon-youtube-1',
                        )
                    ),
                    array(
                        'title' => "Instagram",
                        'url' => '#',
                        'icon' => array(
                            'type' => 'cs-font',
                            'icon' => 'cs-font clever-icon-instagram',
                        )
                    ),
                    array(
                        'title' => "Pinterest",
                        'url' => '#',
                        'icon' => array(
                            'type' => 'cs-font',
                            'icon' => 'cs-font clever-icon-pinterest',
                        )
                    ),
                ),
                'fields'           => array(
                    array(
                        'name'  => 'title',
                        'type'  => 'text',
                        'label' => esc_html__('Title', 'fona'),
                    ),
                    array(
                        'name'  => 'icon',
                        'type'  => 'icon',
                        'label' => esc_html__('Icon', 'fona'),
                    ),

                    array(
                        'name'  => 'url',
                        'type'  => 'text',
                        'label' => esc_html__('URL', 'fona'),
                    ),

                )
            ),

            array(
                'name'            => $prefix . '_target',
                'type'            => 'checkbox',
                'section'         => $section,
                'selector'        => $this->selector,
                'render_callback' => $fn,
                'default'         => 1,
                'checkbox_label'  => esc_html__('Open link in a new tab.', 'fona'),
            ),
            array(
                'name'            => $prefix . '_nofollow',
                'type'            => 'checkbox',
                'section'         => $section,
                'render_callback' => $fn,
                'default'         => 1,
                'checkbox_label'  => esc_html__('Adding rel="nofollow" for social links.', 'fona'),
            ),
            [
                'name' => $prefix . '_heading_style',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Style Settings', 'fona'),
            ],[
                'name' => $prefix . '_advanced_styling',
                'type' => 'checkbox',
                'section' => $this->section,
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Allow change style if checked.', 'fona'),
            ],
            array(
                'name'            => $prefix . '_size',
                'type'            => 'slider',
                'device_settings' => true,
                'section'         => $section,
                'min'             => 10,
                'step'            => 1,
                'max'             => 100,
                'selector'        => "format",
                'css_format'      => "$selector li a{ font-size: {{value}};}$selector li a i{height: {{value}};width: {{value}}; }",
                'label'           => esc_html__('Size', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1]
            ),

            array(
                'name'            => $prefix . '_padding',
                'type'            => 'slider',
                'device_settings' => true,
                'section'         => $section,
                'min'             => 0,
                'step'            => 1,
                'max'             => 25,
                'selector'        => "$selector li a",
                'unit'            => 'px',
                'css_format'      => 'padding: {{value_no_unit}}px;',
                'label'           => esc_html__('Padding', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1]
            ),

            array(
                'name'            => $prefix . '_spacing',
                'type'            => 'slider',
                'device_settings' => true,
                'section'         => $section,
                'min'             => 0,
                'max'             => 20,
                'selector'        => "$selector li",
                'css_format'      => 'margin-left: {{value}}; margin-right: {{value}};',
                'label'           => esc_html__('Icon Spacing', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1]
            ),

            array(
                'name'             => $prefix . '_shape',
                'type'             => 'select',
                'section'          => $section,
                'selector'         => '.header-social-icons',
                'default'         => 'circle',
                'render_callback'  => $fn,
                'title'            => esc_html__('Shape', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1],
                'choices'          => array(
                    'rounded' => esc_html__('Rounded', 'fona'),
                    'square' => esc_html__('Square', 'fona'),
                    'circle' => esc_html__('Circle', 'fona'),
                    'none' => esc_html__('None', 'fona'),
                ),
            ),

            array(
                'name'             => $prefix . '_color_type',
                'type'             => 'select',
                'section'          => $section,
                'selector'         => $this->selector,
                'default'         => 'default',
                'render_callback'  => $fn,
                'title'            => esc_html__('Color', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1],
                'choices'          => array(
                    'default' => esc_html__('Official Color', 'fona'),
                    'custom' => esc_html__('Custom', 'fona'),
                ),
            ),

            array(
                'name'             => $prefix . '_custom_color',
                'type'             => 'modal',
                'section'          => $section,
                'selector'         => "{$this->selector} li a",
                'required'         => array( $prefix . '_color_type', '==', 'custom'),
                'css_format'       => 'styling',
                'title'            => esc_html__('Custom Color', 'fona'),
                'fields' => array(
                    'tabs' => array(
                        'default'=> esc_html__('Normal', 'fona'),
                        'hover'=> esc_html__('Hover', 'fona'),
                    ),
                    'default_fields' => array(
                        array(
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Background Color', 'fona'),
                            'selector'  => "$selector.color-custom li a",
                            'css_format' => 'background-color: {{value}};',
                        ),
                        array(
                            'name' => 'secondary',
                            'type' => 'color',
                            'label' => esc_html__('Icon Color', 'fona'),
                            'selector'        => "$selector.color-custom li a",
                            'css_format' => 'color: {{value}};',
                        ),
                    ),
                    'hover_fields' => array(
                        array(
                            'name' => 'primary',
                            'type' => 'color',
                            'label' => esc_html__('Background Color', 'fona'),
                            'selector'        => "$selector.color-custom li a:hover",
                            'css_format' => 'background-color: {{value}};',
                        ),
                        array(
                            'name' => 'secondary',
                            'type' => 'color',
                            'label' => esc_html__('Icon Color', 'fona'),
                            'selector'        => "$selector.color-custom li a:hover",
                            'css_format' => 'color: {{value}};',
                        ),
                    )
                )
            ),


            array(
                'name'             => $prefix . '_border',
                'type'             => 'modal',
                'section'          => $section,
                'selector'         => "{$this->selector} li a",
                'css_format'       => 'styling',
                'title'            => esc_html__('Border', 'fona'),
                'description'      => esc_html__('Border & border radius', 'fona'),
                'required'=>[$prefix . '_advanced_styling','==',1],
                'fields' => array(
                    'tabs' => array(
                        'default'=> '_',
                    ),
                    'default_fields' => array(
                        array(
                            'name' => 'border_style',
                            'type' => 'select',
                            'class' => 'clear',
                            'label' => esc_html__('Border Style', 'fona'),
                            'default' => 'none',
                            'choices' => array(
                                ''          => esc_html__('Default', 'fona'),
                                'none'      => esc_html__('None', 'fona'),
                                'solid'     => esc_html__('Solid', 'fona'),
                                'dotted'    => esc_html__('Dotted', 'fona'),
                                'dashed'    => esc_html__('Dashed', 'fona'),
                                'double'    => esc_html__('Double', 'fona'),
                                'ridge'     => esc_html__('Ridge', 'fona'),
                                'inset'     => esc_html__('Inset', 'fona'),
                                'outset'    => esc_html__('Outset', 'fona'),
                            ),
                            'css_format' => 'border-style: {{value}};',
                            'selector'        => "$selector li a",
                        ),

                        array(
                            'name' => 'border_width',
                            'type' => 'css_rule',
                            'label' => esc_html__('Border Width', 'fona'),
                            'required' => array('border_style', '!=', 'none'),
                            'selector'        => "$selector li a",
                            'css_format' => array(
                                'top' => 'border-top-width: {{value}};',
                                'right' => 'border-right-width: {{value}};',
                                'bottom'=> 'border-bottom-width: {{value}};',
                                'left'=> 'border-left-width: {{value}};'
                            ),
                        ),
                        array(
                            'name' => 'border_color',
                            'type' => 'color',
                            'label' => esc_html__('Border Color', 'fona'),
                            'required' => array('border_style', '!=', 'none'),
                            'selector'        => "$selector li a",
                            'css_format' => 'border-color: {{value}};',
                        ),

                        array(
                            'name' => 'border_radius',
                            'type' => 'slider',
                            'label' => esc_html__('Border Radius', 'fona'),
                            'selector'        => "$selector li a",
                            'css_format' => 'border-radius: {{value}};',
                        ),
                    )
                )
            ),


        );

        // Item Layout
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    public function render($item_config = array())
    {
        $atts = [];
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $atts['align'] = $align;
        }
        
        $atts['shape'] = zoo_customize_get_setting($this->section.'_shape', $args[1]);
        $atts['color_type'] = zoo_customize_get_setting($this->section.'_color_type', $args[1]);
        $atts['items'] = zoo_customize_get_setting($this->section.'_items', $args[1]);
        $atts['nofollow']      = zoo_customize_get_setting($this->section.'_nofollow', $args[1]);
        $atts['target_blank'] = zoo_customize_get_setting($this->section.'_target', $args[1]);

        $tpl = apply_filters('header/element/social-icons', ZOO_THEME_DIR . 'core/customize/templates/header/element-social-icons.php', $atts);

        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Social_Icons());
