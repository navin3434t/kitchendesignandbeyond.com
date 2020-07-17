<?php
/**
 * Zoo_Customize_Builder_Element_Language_Switcher
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Language_Switcher extends Zoo_Customize_Builder_Element
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id      = 'language-switcher';
        $this->title   = esc_html__('Language Switcher', 'fona');
        $this->width   = 3;
        $this->section = 'language_switcher';
        $this->panel   = 'header_settings';
    }

    public function get_builder_configs()
    {
        return [
            'name'    => $this->title,
            'id'      => $this->id,
            'width'   => 3,
            'section' => $this->section
        ];
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $selector='.element-language-switcher';
        $config= [
			[
				'name'  => $this->section,
				'type'  => 'section',
				'panel' => 'header_settings',
				'title' => esc_html__('Language Switcher', 'fona'),
			],
			[
				'name'            => 'header_language_switcher_label',
				'type'            => 'text',
				'section'         => $this->section,
				'theme_supports'  => '',
				'selector'        => $selector,
				'render_callback' => [$this, 'render'],
				'title'           => esc_html__('Label', 'fona'),
				'default'         => esc_html__('Languages', 'fona'),
			],
			[
				'name'            => 'header_language_switcher_icon',
				'type'            => 'icon',
				'section'         => $this->section,
				'selector'        => $selector,
				'render_callback' => [$this, 'render'],
				'theme_supports'  => '',
				'title'           => esc_html__('Icon', 'fona'),
			],
			[
				'name'            => 'header_language_switcher_icon_position',
				'type'            => 'select',
				'section'         => $this->section,
				'selector'        => $selector,
				'render_callback' => [$this, 'render'],
				'default'         => 'before',
				'title'           => esc_html__('Icon Position', 'fona'),
				'choices'         => [
					'before' => esc_html__('Before', 'fona'),
					'after'  => esc_html__('After', 'fona'),
				]
			],
            [
                'name' => 'header_language_switcher_heading_styling',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Styling', 'fona'),
            ],[
                'name' => 'header_language_switcher_advanced_styling',
                'type' => 'checkbox',
                'section' => $this->section,
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Will be showed if checked.', 'fona'),
                'default' =>0
            ],
            array(
                'name'        => 'header_language_switcher_styling',
                'type'        => 'styling',
                'section'     => $this->section,
                'title'       => esc_html__('Styling', 'fona'),
                'description' => esc_html__('Styling for language switcher', 'fona'),
                'required'=>['header_language_switcher_advanced_styling','==',1],
                'selector'    => array(
                    'normal'           => "{$selector}",
                    'hover'            => "{$selector}:hover",
                    'hover_text_color' => "{$selector}:hover>span"
                ),
                'css_format'  => 'styling',
                'fields'      => array(
                    'tabs'          => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover'  => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        'margin' => false,
                        'link_color'    => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                        'link_hover_color'   => false,
                    ),
                    'hover_fields'  => array(
                        'link_color'    => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                    ),
                )
            ),
            array(
                'name'        => 'header_language_switcher_styling_list',
                'type'        => 'styling',
                'section'     => $this->section,
                'title'       => esc_html__('List Languages Styling', 'fona'),
                'description' => esc_html__('Styling for list language switcher', 'fona'),
                'required'=>['header_language_switcher_advanced_styling','==',1],
                'selector'    => array(
                    'normal'           => "{$selector} .list-languages",
                    'normal_text_color'           => "{$selector} .list-languages li",
                    'normal_padding'           => "{$selector} .list-languages li",
                    'normal_bg_color'           => "{$selector} .list-languages li",
                    'normal_border_style'           => "{$selector} .list-languages li",
                    'hover'            => "{$selector} .list-languages",
                ),
                'css_format'  => 'styling',
                'fields'      => array(
                    'tabs'          => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover'  => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        'margin' => false,
                        'link_color'    => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                        'link_hover_color'   => false,
                    ),
                    'hover_fields'  => array(
                        'link_color'    => false,
                        'bg_cover'      => false,
                        'bg_image'      => false,
                        'bg_repeat'     => false,
                        'bg_attachment' => false,
                        'bg_position'   => false,
                        'box_shadow'   => false,
                    ),
                )
            ),
        ];
        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

	function render()
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

        $atts['device'] = $args[1];
        $atts['text']      = zoo_customize_get_setting('header_language_switcher_label', $args[1]);
        $atts['icon']      = zoo_customize_get_setting('header_language_switcher_icon', $args[1]);
        $atts['position']  = zoo_customize_get_setting('header_language_switcher_icon_position', $args[1]);

        $tpl = apply_filters('header/element/language-switcher', ZOO_THEME_DIR . 'core/customize/templates/header/element-language-switcher.php', $atts);

        require $tpl;

	}
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Language_Switcher());
