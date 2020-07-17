<?php
/**
 * Zoo_Customizer
 *
 * @package  Zoo_Theme\Core\Customize\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customizer
{
    /**
     * Supporting devices
     *
     * @var  array
     */
    const SUPPORT_DEVICES = ['desktop', 'mobile'];

    /**
     * Panels
     *
     * @var  array
     */
    private $panels;

    /**
     * Sections
     *
     * @var  array
     */
    private $sections;

    /**
     * Settings
     *
     * @var  array
     */
    private $settings;

    /**
     * WP_Customize_Manager
     *
     * @var  object
     */
    protected $wp_customize;

    /**
     * Default control types
     *
     * @var  array
     */
    const SUPPORT_CONTROL_TYPES = [
        'default',
        'select',
        'font',
        'font_style',
        'text_align',
        'text_align_no_justify',
        'checkbox',
        'css_rule',
        'shadow',
        'icon',
        'slider',
        'color',
        'textarea',
        'radio',
        'media',
        'image',
        'video',
        'hidden',
        'heading',
        'typography',
        'modal',
        'styling',
        'hr',
        'repeater'
    ];

    /**
     * Default settings
     *
     * @var  array
     */
    const DEFAULT_SETTINGS = [
        'header_builder_panel' => [
            'desktop' => [
                'main' => [
                    [
                        'x'      => '0',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'logo',
                    ],
                    [
                        'x'      => '3',
                        'y'      => '1',
                        'width'  => '9',
                        'height' => '1',
                        'id'     => 'primary-menu',
                    ]
                ]
            ],
            'mobile' => [
                'main' => [
                    [
                        'x'      => '0',
                        'y'      => '1',
                        'width'  => '9',
                        'height' => '1',
                        'id'     => 'logo',
                    ],
                    [
                        'x'      => '9',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'nav-icon',
                    ],
                ],
                'sidebar' => [
                    [
                        'x'      => '0',
                        'y'      => '1',
                        'width'  => '1',
                        'height' => '1',
                        'id'     => 'mobile-menu',
                    ],
                ]
            ]
        ],
        'header_top_height' => [
            'desktop' => ['unit'  => 'px', 'value' => ''],
            'mobile' => ['unit' => 'px', 'value' => ''],
        ],
        'header_main_height' => [
            'desktop' => ['unit' => 'px', 'value' => ''],
            'mobile' => ['unit' => 'px', 'value' => ''],
        ],
        'header_bottom_height' => [
            'desktop' => ['unit' => 'px', 'value' => ''],
            'mobile' => ['unit' => 'px', 'value' => ''],
        ],
        'header_main_styling' => [
            'normal' => [
                'border_width' => [
                    'unit' => 'px',
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '1',
                    'left' => '0',
                    'link' => '1'
                ],
                'border_style' => 'solid',
                'border_color' => '#eaecee',
            ]
        ],
        'header_sidebar_animate' => 'off-canvas-slide-left',
        'header_nav-icon_align' => [
            'desktop' => 'right',
            'mobile' => 'right',
        ],
        'header_primary-menu_align' => [
            'desktop' => 'right',
            'mobile' => '',
        ],
        'footer_builder_panel' => [
            'desktop' => [
                'main' => [
                    [
                        'x'      => '0',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'footer-widget-1',
                    ],
                    [
                        'x'      => '3',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'footer-widget-2',
                    ],
                    [
                        'x'      => '6',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'footer-widget-3',
                    ],
                    [
                        'x'      => '9',
                        'y'      => '1',
                        'width'  => '3',
                        'height' => '1',
                        'id'     => 'footer-widget-4',
                    ]
                ],
                'bottom' => [
                    [
                        'x'      => '0',
                        'y'      => '1',
                        'width'  => '6',
                        'height' => '1',
                        'id'     => 'footer_copyright',
                    ],
                    [
                        'x'      => '6',
                        'y'      => '1',
                        'width'  => '6',
                        'height' => '1',
                        'id'     => 'footer-html1',
                    ]
                ]
            ]
        ]
    ];

    private $selective_settings = [];

    /**
     * Nope constructor
     */
    private function __construct()
    {
        $this->panels = apply_filters('zoo_default_customize_panels', [
            'header_settings' => [
                'title'    => esc_html__('Site Header', 'fona'),
                'priority' => -999
            ],
            'layout_panel' => [
                'title'    => esc_html__('Layouts', 'fona'),
                'priority' => 19
            ],
            'footer_settings' => [
                'title'    => esc_html__('Site Footer', 'fona'),
                'priority' => 999
            ]
        ]);

        $this->sections = apply_filters('zoo_default_customize_sections', [
            'header_builder_panel' => [
                'title' => esc_html__('Header Builder', 'fona'),
                'panel' => 'header_settings',
            ],
            'global_layout_section' => [
                'title' => esc_html__('Global', 'fona'),
                'panel' => 'layout_panel',
            ],
            'sidebar_layout_section' => [
                'panel' => 'layout_panel',
                'title' => esc_html__('Sidebars', 'fona')
            ],
            'footer_general' => [
                'title'    => esc_html__('General Settings', 'fona'),
                'panel'    => 'footer_settings',
                'priority' => 0,
            ],
        ]);

        $this->settings = apply_filters('zoo_default_customize_settings', [

        ]);

        add_action('customize_register', [$this, '_register']);
        add_action('customize_preview_init', [$this, '_load_preview_assets']);
        add_action('wp_ajax_zoo__reset_section', [$this, '_ajax_reset_section'], 10, 0);
        add_action('customize_controls_enqueue_scripts', [$this, '_load_controls_assets'], 10, 0);
        add_action('wp_ajax_zoo_customize_load_fonts', [$this, '_ajax_load_fonts'], 10, 0);
        add_action('wp_ajax_zoo_customize__load_font_icons', [$this, '_ajax_load_font_icons'], 10, 0);
    }

    /**
     * Add field
     *
     * A shortcut for Zoo_Customizer::add_setting() and Zoo_Customizer::add_control()
     *
     * @param  array  $args
     */
    public function add_field(array $args)
    {
        $defaults = self::DEFAULT_SETTINGS;
        $args = array_merge([
            'priority'             => null,
            'title'                => null,
            'label'                => null,
            'name'                 => null,
            'type'                 => null,
            'description'          => null,
            'capability'           => null,
            'settings'             => null,
            'active_callback'      => null,
            'sanitize_callback'    => 'Zoo_Customizer::sanitize',
            'sanitize_js_callback' => null,
            'theme_supports'       => null,
            'default'              => null,
            'selector'             => null,
            'render_callback'      => null,
            'css_format'           => null,
            'device'               => null,
            'device_settings'      => null,
            'field_class'          => null,
            'setting'              => null,
            'input_attrs'          => null,
            'choices'              => null
        ], $args);

        if (null === $args['device_settings']) {
            $args['device_settings'] = false;
        }

        switch ($args['type']) {
            case 'panel':
                $name = $args['name'];
                if (!$args['title']) {
                    $args['title'] = $args['label'];
                }
                $args['type'] = 'zoo_panel';
                foreach ($args as $key => $value) {
                    if (!in_array($key, ['type', 'title', 'priority', 'capability', 'description', 'theme_supports', 'active_callback'])) {
                        unset($args[$key]);
                    }
                }
                $this->wp_customize->add_panel(new WP_Customize_Panel($this->wp_customize, $name, $args));
            break;
            case 'section':
                $name = $args['name'];
                if (!$args['title']) {
                    $args['title'] = $args['label'];
                }
                $args['type'] = 'zoo_section';
                foreach ($args as $key => $value) {
                    if (!in_array($key, ['type', 'title', 'panel', 'priority', 'capability', 'description', 'theme_supports', 'active_callback', 'description_hidden'])) {
                        unset($args[$key]);
                    }
                }
                $this->wp_customize->add_section(new WP_Customize_Section($this->wp_customize, $name, $args));
                break;
            default:
                switch ($args['type']) {
                    case 'image_select':
                        $args['setting_type'] = 'radio';
                        $args['field_class'] = 'custom-control-image_select' . ($args['field_class'] ? ' ' . $args['field_class'] : '');
                        break;
                    case 'radio_group':
                        $args['setting_type'] = 'radio';
                        $args['field_class'] = 'custom-control-radio_group' . ($args['field_class'] ? ' ' . $args['field_class'] : '');
                        break;
                    default:
                        $args['setting_type'] = $args['type'];
                }
                $args['defaultValue'] = $args['default'];
                $settings_args = array(
                    'sanitize_callback'    => $args['sanitize_callback'],
                    'sanitize_js_callback' => $args['sanitize_js_callback'],
                    'theme_supports'       => $args['theme_supports'],
                    'type'                 => 'theme_mod',
                );
                $settings_args['default'] = isset($defaults[$args['name']]) ? $defaults[$args['name']] : $args['default'];

                $settings_args['transport'] = !empty($args['transport']) ? $args['transport'] : 'refresh';
                if (!$settings_args['sanitize_callback']) {
                    $settings_args['sanitize_callback'] = 'Zoo_Customizer::sanitize';
                }

                foreach ($settings_args as $k => $v) {
                    unset($args[$k]);
                }

                $name = $args['name'];
                unset($args['name']);

                unset($args['type']);
                if (!$args['label']) {
                    $args['label'] = $args['title'];
                }

                $selective_refresh = null;
                if ($args['selector'] && ($args['render_callback'] || $args['css_format'])) {
                    $selective_refresh = array(
                        'selector'        => $args['selector'],
                        'render_callback' => $args['render_callback'],
                    );

                    if ($args['css_format']) {
                        $settings_args['transport'] = 'postMessage';
                        $selective_refresh = null;
                    } else {
                        $settings_args['transport'] = 'postMessage';
                    }
                }
                unset($args['default']);
                $this->wp_customize->add_setting($name, array_merge(
                    ['sanitize_callback' => 'Zoo_Customizer::sanitize'
                ],
                    $settings_args
                ));

                $control_class_name = 'Zoo_Customize_Control_';
                $tpl_type = str_replace('_', ' ', $args['setting_type']);
                $tpl_type = str_replace(' ', '_', ucfirst($tpl_type));
                $control_class_name .= $tpl_type;

                if (in_array($args['setting_type'], ['custom_html', 'text'])) {
                    $control_class_name = 'Zoo_Customize_Control_Default';
                }

                    if (class_exists($control_class_name)) {
                        $this->wp_customize->add_control(new $control_class_name($this->wp_customize, $name, $args));
                    } elseif (in_array($args['setting_type'], ['text', 'custom_html', 'js_raw'])) {
                        $this->wp_customize->add_control(new Zoo_Customize_Control_Default($this->wp_customize, $name, $args));
                    } else {
                        $this->wp_customize->add_control($name, [
                            'settings'        => $args['settings'],
                            'setting'         => $args['setting'],
                            'capability'      => $args['capability'],
                            'priority'        => $args['priority'],
                            'section'         => $args['section'],
                            'label'           => $args['label'],
                            'description'     => $args['description'],
                            'choices'         => $args['choices'],
                            'type'            => $args['setting_type'],
                            'active_callback' => $args['active_callback'],
                            'input_attrs'     => $args['input_attrs']
                        ]);
                    }
                if ($selective_refresh) {
                    $s_id = $selective_refresh['render_callback'];
                    if (is_array($s_id)) {
                        $__id = get_class($s_id[0]) . '__' . $s_id[1];
                    } else {
                        $__id = $s_id;
                    }
                    if (!isset($this->selective_settings[$__id])) {
                        $this->selective_settings[$__id] = array(
                            'settings'            => [],
                            'selector'            => $selective_refresh['selector'],
                            'container_inclusive' => (strpos($__id, 'Zoo_Customize_Live_CSS') === false) ? true : false,
                            'render_callback'     => $s_id,
                        );
                    }

                    $this->selective_settings[$__id]['settings'][] = $name;
                }

                break;
        }
    }

    /**
     * Singleton
     */
    public static function get_instance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new self;
        }

        return $instance;
    }

    /**
     * AJAX reset a section
     *
     * @internal  Used as a callback.
     */
    public function _ajax_reset_section()
    {
        if (!current_user_can('customize')) {
            wp_send_json_error();
        }

        $settings = isset($_POST['settings']) ? wp_unslash($_POST['settings']) : [];

        foreach ($settings as $k) {
            $k = sanitize_text_field($k);
            remove_theme_mod($k);
        }

        wp_send_json_success();
    }

    /**
     * Load fonts
     */
    function _ajax_load_fonts()
    {
        $fonts = [
            'normal' => [
                'title' => esc_html__('Default Web Fonts', 'fona'),
                'fonts' => zoo_get_websafe_fonts()
            ],
            'google' => [
                'title' => esc_html__('Google Web Fonts', 'fona'),
                'fonts' => zoo_get_google_fonts()
            ]
        ];

        wp_send_json_success($fonts);
    }

    /**
     * AJAX get fonts
     *
     * @internal  Used as a callback.
     */
    public function _ajax_load_font_icons()
    {
        if (!current_user_can('customize')) {
            wp_send_json_error();
        }

        $fonts = [
            'zoo-icon' => [
                'name'  => esc_html__('Theme Icon', 'fona'),
                'icons' => zoo_get_font_icons('zoo-icon'),
                'url'   => ZOO_THEME_URI . 'assets/css/icons/icons.css',
                'class_config' => '__icon_name__'
            ]
        ];

        if (current_theme_supports('cs-font')) {
            $fonts['cs-font'] = [
                'name'  => esc_html__('Clever Font', 'fona'),
                'icons' => zoo_get_font_icons('cs-font'),
                'url'   => ZOO_THEME_URI . 'assets/vendor/cleverfont/style' . ZOO_CSS_SUFFIX,
                'class_config' => 'cs-font __icon_name__'
            ];
        }

        wp_send_json_success($fonts);
    }

    /**
     * Load preview assets.
     *
     * @internal  Used as a callback.
     *
     * @hook  customize_preview_init
     */
    public function _load_preview_assets(WP_Customize_Manager $wp_customize)
    {
        wp_enqueue_script('zoo-customize-preview', ZOO_THEME_URI . 'core/assets/js/customize-preview' . ZOO_JS_SUFFIX, ['customize-selective-refresh'], ZOO_THEME_VERSION, true);

        wp_localize_script('zoo-customize-preview', 'ZooCustomizePreviewData', [
            'fields'          => zoo_customize_get_all_config($wp_customize),
            'devices'         => self::SUPPORT_DEVICES,
            'cssMediaQueries' => Zoo_Customize_Live_CSS::get_instance()->media_queries,
            'typo_fields'     => $this->get_typo_fields(),
            'styling_config'  => $this->get_styling_config()
        ]);
    }

    /**
     * Load controls' assets
     *
     * @internal  Used as a callback
     *
     * @hook  customize_controls_enqueue_scripts
     */
    public function _load_controls_assets()
    {
        wp_enqueue_media();

        wp_enqueue_style('zoo-customizer-control', ZOO_THEME_URI . 'core/assets/css/customizer.min.css', ['wp-color-picker'], ZOO_THEME_VERSION);

        wp_enqueue_script('DOMPurify', ZOO_THEME_URI . 'core/assets/js/purify.min.js', [], ZOO_THEME_VERSION);

        wp_enqueue_script('wp-color-picker-alpha', ZOO_THEME_URI . 'core/assets/js/wp-color-picker-alpha' . ZOO_JS_SUFFIX, ['wp-color-picker'], ZOO_THEME_VERSION, true);

        wp_enqueue_script('zoo-customize', ZOO_THEME_URI . 'core/assets/js/customize-builder' . ZOO_JS_SUFFIX, [
            'jquery',
            'customize-base',
            'customize-controls',
            'jquery-ui-core',
            'jquery-ui-slider',
            'jquery-ui-sortable',
            'jquery-ui-resizable',
            'jquery-ui-droppable',
            'jquery-ui-draggable'
        ], ZOO_THEME_VERSION, true);

        wp_localize_script('zoo-customize', 'ZooCustomizeBuilderData', [
            'home_url'         => esc_url(home_url('')),
            'ajax'             => admin_url('admin-ajax.php'),
            'is_rtl'           => is_rtl(),
            'theme_default'    => esc_html__('Theme Default', 'fona'),
            'reset'            => esc_html__('Reset this section settings', 'fona'),
            'untitled'         => esc_html__('Untitled', 'fona'),
            'confirm_reset'    => esc_html__('Do you want to reset this section settings?', 'fona'),
            'typo_fields'      => $this->get_typo_fields(),
            'styling_config'   => $this->get_styling_config(),
            'devices'          => self::SUPPORT_DEVICES,
            'list_font_weight' => [
                ''       => esc_html__('Default', 'fona'),
                'normal' => _x('Normal', 'zoo-font-weight', 'fona'),
                'bold'   => _x('Bold', 'zoo-font-weight', 'fona')
            ],
            'builders' => Zoo_Customize_Builder::get_instance()->get_builders(),
            'isRtl' => is_rtl()
        ]);
    }

    /**
     * Sanitize input data
     *
     * @internal  Used as a callback
     */
    public static function sanitize($data, $setting)
    {
        $data = wp_unslash($data);

        if (!is_array($data)) {
            $data = json_decode(urldecode_deep($data), true);
        }

        $sanitizer = new Zoo_Customize_Sanitizer($setting->manager->get_control($setting->id), $setting);

        return $sanitizer->sanitize($data);
    }

    /**
     * Get typography fields
     *
     * @return array
     */
    public function get_typo_fields()
    {
        $typo_fields = array(
            array(
                'name'    => 'font',
                'type'    => 'select',
                'label'   => esc_html__('Font Family', 'fona'),
                'choices' => array()
            ),
            array(
                'name'    => 'font_weight',
                'type'    => 'select',
                'label'   => esc_html__('Font Weight', 'fona'),
                'choices' => array()
            ),
            array(
                'name'  => 'languages',
                'type'  => 'checkboxes',
                'label' => esc_html__('Font Languages', 'fona'),
            ),
            array(
                'name'            => 'font_size',
                'type'            => 'slider',
                'label'           => esc_html__('Font Size', 'fona'),
                'min'             => 9,
                'max'             => 80,
                'step'            => 1
            ),
            array(
                'name'            => 'line_height',
                'type'            => 'slider',
                'label'           => esc_html__('Line Height', 'fona'),
                'min'             => 9,
                'max'             => 80,
                'step'            => 1
            ),
            array(
                'name'  => 'letter_spacing',
                'type'  => 'slider',
                'label' => esc_html__('Letter Spacing', 'fona'),
                'min'   => -10,
                'max'   => 10,
                'step'  => 0.1
            ),
            array(
                'name'    => 'style',
                'type'    => 'select',
                'label'   => esc_html__('Font Style', 'fona'),
                'choices' => array(
                    ''        => esc_html__('Default', 'fona'),
                    'normal'  => esc_html__('Normal', 'fona'),
                    'italic'  => esc_html__('Italic', 'fona'),
                    'oblique' => esc_html__('Oblique', 'fona'),
                )
            ),
            array(
                'name'    => 'text_decoration',
                'type'    => 'select',
                'label'   => esc_html__('Text Decoration', 'fona'),
                'choices' => array(
                    ''             => esc_html__('Default', 'fona'),
                    'underline'    => esc_html__('Underline', 'fona'),
                    'overline'     => esc_html__('Overline', 'fona'),
                    'line-through' => esc_html__('Line through', 'fona'),
                    'none'         => esc_html__('None', 'fona'),
                )
            ),
            array(
                'name'    => 'text_transform',
                'type'    => 'select',
                'label'   => esc_html__('Text Transform', 'fona'),
                'choices' => array(
                    ''           => esc_html__('Default', 'fona'),
                    'uppercase'  => esc_html__('Uppercase', 'fona'),
                    'lowercase'  => esc_html__('Lowercase', 'fona'),
                    'capitalize' => esc_html__('Capitalize', 'fona'),
                    'none'       => esc_html__('None', 'fona'),
                )
            )
        );

        return $typo_fields;
    }

    /**
     * Get styling field
     *
     * @return array
     */
    public function get_styling_config()
    {
        $fields = array(
            'tabs'          => array(
                'normal' => esc_html__('Normal', 'fona'),  // null or false to disable
                'hover'  => esc_html__('Hover', 'fona'), // null or false to disable
            ),
            'normal_fields' => array(
                array(
                    'name'       => 'text_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Color', 'fona'),
                    'css_format' => 'color: {{value}}; text-decoration-color: {{value}};'
                ),
                array(
                    'name'       => 'link_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Link Color', 'fona'),
                    'css_format' => 'color: {{value}}; text-decoration-color: {{value}};'
                ),
                array(
                    'name'       => 'link_hover_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Link Hover Color', 'fona'),
                    'css_format' => 'color: {{value}}; text-decoration-color: {{value}};'
                ),

                array(
                    'name'            => 'margin',
                    'type'            => 'css_rule',
                    'device_settings' => true,
                    'css_format'      => array(
                        'top'    => 'margin-top: {{value}};',
                        'right'  => 'margin-right: {{value}};',
                        'bottom' => 'margin-bottom: {{value}};',
                        'left'   => 'margin-left: {{value}};',
                    ),
                    'label'           => esc_html__('Margin', 'fona'),
                ),

                array(
                    'name'            => 'padding',
                    'type'            => 'css_rule',
                    'device_settings' => true,
                    'css_format'      => array(
                        'top'    => 'padding-top: {{value}};',
                        'right'  => 'padding-right: {{value}};',
                        'bottom' => 'padding-bottom: {{value}};',
                        'left'   => 'padding-left: {{value}};',
                    ),
                    'label'           => esc_html__('Padding', 'fona'),
                ),

                array(
                    'name'  => 'bg_heading',
                    'type'  => 'heading',
                    'label' => esc_html__('Background', 'fona'),
                ),

                array(
                    'name'       => 'bg_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Background Color', 'fona'),
                    'css_format' => 'background-color: {{value}};'
                ),
                array(
                    'name'       => 'bg_image',
                    'type'       => 'image',
                    'device_settings' => true,
                    'label'      => esc_html__('Background Image', 'fona'),
                    'css_format' => 'background-image: url("{{value}}");'
                ),
                array(
                    'name'       => 'bg_cover',
                    'type'       => 'select',
                    'device_settings' => true,
                    'choices'    => array(
                        ''        => esc_html__('Default', 'fona'),
                        'auto'    => esc_html__('Auto', 'fona'),
                        'cover'   => esc_html__('Cover', 'fona'),
                        'contain' => esc_html__('Contain', 'fona'),
                    ),
                    'required'   => array('bg_image', 'not_empty', ''),
                    'label'      => esc_html__('Size', 'fona'),
                    'class'      => 'field-half-left',
                    'css_format' => '-webkit-background-size: {{value}}; -moz-background-size: {{value}}; -o-background-size: {{value}}; background-size: {{value}};'
                ),
                array(
                    'name'       => 'bg_position',
                    'type'       => 'select',
                    'device_settings' => true,
                    'label'      => esc_html__('Position', 'fona'),
                    'required'   => array('bg_image', 'not_empty', ''),
                    'class'      => 'field-half-right',
                    'choices'    => array(
                        ''              => esc_html__('Default', 'fona'),
                        'center'        => esc_html__('Center', 'fona'),
                        'top left'      => esc_html__('Top Left', 'fona'),
                        'top right'     => esc_html__('Top Right', 'fona'),
                        'top center'    => esc_html__('Top Center', 'fona'),
                        'bottom left'   => esc_html__('Bottom Left', 'fona'),
                        'bottom center' => esc_html__('Bottom Center', 'fona'),
                        'bottom right'  => esc_html__('Bottom Right', 'fona'),
                    ),
                    'css_format' => 'background-position: {{value}};'
                ),
                array(
                    'name'       => 'bg_repeat',
                    'type'       => 'select',
                    'device_settings' => true,
                    'label'      => esc_html__('Repeat', 'fona'),
                    'class'      => 'field-half-left',
                    'required'   => array(
                        array('bg_image', 'not_empty', ''),
                    ),
                    'choices'    => array(
                        'repeat'    => esc_html__('Default', 'fona'),
                        'no-repeat' => esc_html__('No repeat', 'fona'),
                        'repeat-x'  => esc_html__('Repeat horizontal', 'fona'),
                        'repeat-y'  => esc_html__('Repeat vertical', 'fona'),
                    ),
                    'css_format' => 'background-repeat: {{value}};'
                ),

                array(
                    'name'       => 'bg_attachment',
                    'type'       => 'select',
                    'device_settings' => true,
                    'label'      => esc_html__('Attachment', 'fona'),
                    'class'      => 'field-half-right',
                    'required'   => array(
                        array('bg_image', 'not_empty', '')
                    ),
                    'choices'    => array(
                        ''       => esc_html__('Default', 'fona'),
                        'scroll' => esc_html__('Scroll', 'fona'),
                        'fixed'  => esc_html__('Fixed', 'fona')
                    ),
                    'css_format' => 'background-attachment: {{value}};'
                ),

                array(
                    'name'  => 'border_heading',
                    'type'  => 'heading',
                    'label' => esc_html__('Border', 'fona'),
                ),

                array(
                    'name'       => 'border_style',
                    'type'       => 'select',
                    'device_settings' => true,
                    'class'      => 'clear',
                    'label'      => esc_html__('Border Style', 'fona'),
                    'default'    => '',
                    'choices'    => array(
                        ''       => esc_html__('Default', 'fona'),
                        'none'   => esc_html__('None', 'fona'),
                        'solid'  => esc_html__('Solid', 'fona'),
                        'dotted' => esc_html__('Dotted', 'fona'),
                        'dashed' => esc_html__('Dashed', 'fona'),
                        'double' => esc_html__('Double', 'fona'),
                        'ridge'  => esc_html__('Ridge', 'fona'),
                        'inset'  => esc_html__('Inset', 'fona'),
                        'outset' => esc_html__('Outset', 'fona'),
                    ),
                    'css_format' => 'border-style: {{value}};',
                ),

                array(
                    'name'       => 'border_width',
                    'type'       => 'css_rule',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Width', 'fona'),
                    'required'   => array(
                        array( 'border_style', '!=', 'none' ),
                        array( 'border_style', '!=', '' )
                    ),
                    'css_format' => array(
                        'top'    => 'border-top-width: {{value}};',
                        'right'  => 'border-right-width: {{value}};',
                        'bottom' => 'border-bottom-width: {{value}};',
                        'left'   => 'border-left-width: {{value}};'
                    ),
                ),
                array(
                    'name'       => 'border_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Color', 'fona'),
                    'required'   => array(
                        array( 'border_style', '!=', 'none' ),
                        array( 'border_style', '!=', '' )
                    ),
                    'css_format' => 'border-color: {{value}};',
                ),

                array(
                    'name'       => 'border_radius',
                    'type'       => 'css_rule',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Radius', 'fona'),
                    'css_format' => array(
                        'top'    => 'border-top-left-radius: {{value}};',
                        'right'  => 'border-top-right-radius: {{value}};',
                        'bottom' => 'border-bottom-right-radius: {{value}};',
                        'left'   => 'border-bottom-left-radius: {{value}};'
                    ),
                ),

                array(
                    'name'       => 'box_shadow',
                    'type'       => 'shadow',
                    'device_settings' => true,
                    'label'      => esc_html__('Box Shadow', 'fona'),
                    'css_format' => 'box-shadow: {{value}};',
                ),

            ),

            'hover_fields' => array(
                array(
                    'name'       => 'text_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Color', 'fona'),
                    'css_format' => 'color: {{value}}; text-decoration-color: {{value}};'
                ),
                array(
                    'name'       => 'link_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Link Color', 'fona'),
                    'css_format' => 'color: {{value}}; text-decoration-color: {{value}};'
                ),
                array(
                    'name'  => 'bg_heading',
                    'type'  => 'heading',
                    'label' => esc_html__('Background', 'fona'),
                ),
                array(
                    'name'       => 'bg_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Background Color', 'fona'),
                    'css_format' => 'background-color: {{value}};'
                ),
                array(
                    'name'  => 'border_heading',
                    'type'  => 'heading',
                    'label' => esc_html__('Border', 'fona'),
                ),
                array(
                    'name'       => 'border_style',
                    'type'       => 'select',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Style', 'fona'),
                    'default'    => '',
                    'choices'    => array(
                        ''       => esc_html__('Default', 'fona'),
                        'none'   => esc_html__('None', 'fona'),
                        'solid'  => esc_html__('Solid', 'fona'),
                        'dotted' => esc_html__('Dotted', 'fona'),
                        'dashed' => esc_html__('Dashed', 'fona'),
                        'double' => esc_html__('Double', 'fona'),
                        'ridge'  => esc_html__('Ridge', 'fona'),
                        'inset'  => esc_html__('Inset', 'fona'),
                        'outset' => esc_html__('Outset', 'fona'),
                    ),
                    'css_format' => 'border-style: {{value}};',
                ),
                array(
                    'name'       => 'border_width',
                    'type'       => 'css_rule',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Width', 'fona'),
                    'required'   => array('border_style', '!=', 'none'),
                    'css_format' => array(
                        'top'    => 'border-top-width: {{value}};',
                        'right'  => 'border-right-width: {{value}};',
                        'bottom' => 'border-bottom-width: {{value}};',
                        'left'   => 'border-left-width: {{value}};'
                    ),
                ),
                array(
                    'name'       => 'border_color',
                    'type'       => 'color',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Color', 'fona'),
                    'required'   => array('border_style', '!=', 'none'),
                    'css_format' => 'border-color: {{value}};',
                ),
                array(
                    'name'       => 'border_radius',
                    'type'       => 'css_rule',
                    'device_settings' => true,
                    'label'      => esc_html__('Border Radius', 'fona'),
                    'css_format' => array(
                        'top'    => 'border-top-left-radius: {{value}};',
                        'right'  => 'border-top-right-radius: {{value}};',
                        'bottom' => 'border-bottom-right-radius: {{value}};',
                        'left'   => 'border-bottom-left-radius: {{value}};'
                    ),
                ),
                array(
                    'name'       => 'box_shadow',
                    'type'       => 'shadow',
                    'device_settings' => true,
                    'label'      => esc_html__('Box Shadow', 'fona'),
                    'css_format' => 'box-shadow: {{value}};',
                ),

            ),


        );

        return apply_filters('zoo/get_styling_config', $fields);
    }

    /**
     * Register customize options
     *
     * @internal  Used as a callback.
     *
     * @param  object  $wp_customize
     */
    public function _register(WP_Customize_Manager $wp_customize)
    {
        $this->wp_customize = $wp_customize;

        // Print controls' template.
        foreach (self::SUPPORT_CONTROL_TYPES as $ctrl) {
            if ($ctrl === 'radio_group' || $ctrl === 'image_select') {
                continue;
            }
            $fname = str_replace('_', '-', $ctrl);
            $type  = str_replace('_', ' ', $ctrl);
            $cname = 'Zoo_Customize_Control_' . str_replace(' ', '_', ucfirst($type));
            require ZOO_THEME_DIR . 'core/customize/controls/class-zoo-customize-control-' . $fname . '.php';
            if (method_exists($cname, 'control_template')) {
                add_action('customize_controls_print_footer_scripts', [$cname, 'control_template']);
            }
        }

        do_action('zoo_customize_before_register', $this);

        // Register panels.
        foreach ($this->panels as $panel_id => $panel_args) {
            $wp_customize->add_panel(new WP_Customize_Panel($wp_customize, $panel_id, $panel_args));
        }

        // Register sections.
        foreach ($this->sections as $sections_id => $sections_args) {
            $wp_customize->add_section(new WP_Customize_Section($wp_customize, $sections_id, $sections_args));
        }

        $config = zoo_customize_get_all_config($wp_customize);

        foreach ($config as $args) {
            $this->add_field($args);
        }

        $wp_customize->get_section('title_tagline')->panel = 'header_settings';
        $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
        // add selective refresh
        $wp_customize->get_setting('custom_logo')->transport = 'postMessage';
        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

        foreach ($this->selective_settings as $cb => $settings) {
            reset($settings['settings']);
            if ($cb == 'Zoo_Builder_Item_Logo__render') {
                $settings['settings'][] = 'custom_logo';
                $settings['settings'][] = 'blogname';
                $settings['settings'][] = 'blogdescription';
            }
            $settings = apply_filters($cb, $settings);
            $wp_customize->selective_refresh->add_partial($cb, $settings);
        }

        // For live CSS
        $wp_customize->add_setting('zoo__css', [
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'zoo_sanitize_css_input',
        ]);

        do_action('zoo_customize_after_register', $this);
    }
}
Zoo_Customizer::get_instance();
