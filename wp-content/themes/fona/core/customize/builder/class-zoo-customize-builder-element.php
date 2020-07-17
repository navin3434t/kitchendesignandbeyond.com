<?php

/**
 * Zoo_Customize_Builder_Element
 *
 * @package  Core\Customize\Builder\Elements
 */
abstract class Zoo_Customize_Builder_Element
{
    /**
     * ID
     *
     * @var  string
     */
    protected $id;

    /**
     * Title
     *
     * @var  string
     */
    protected $title;

    /**
     * Default column width on the builder grid.
     *
     * @var  int  Base on the well known 12 columns layout.
     */
    protected $width;

    /**
     * Primary CSS selector for this element
     *
     * @var  string
     */
    protected $selector;

    /**
     * Section where this element belongs to.
     *
     * @var  string
     */
    protected $section;

    /**
     * Panel where this element belongs to.
     *
     * @var  string
     */
    protected $panel;

    /**
     * Builder ID
     *
     * @var  string
     */
    protected $builder_id = 'header';

    /**
     * Getter
     *
     * Read element's property
     */
    function __get($name)
    {
        if (!isset($this->$name) || !is_string($name)) {
            throw new InvalidArgumentException(__('Invalid builder element property!', 'fona'));
        }

        return $this->$name;
    }

    /**
     * Get layout configs
     *
     * @param  string $merge_selector CSS selector for merging live preview.
     *
     * @return  array
     */
    protected function get_layout_configs($merge_selector)
    {
        return [
            [
                'name' => $this->builder_id.'_'.$this->id . '_element_border_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Element Border', 'fona'),
                'priority' => 900,
            ],
            [
                'name'       => $this->builder_id.'_'.$this->id . '_border_width',
                'type'       => 'css_rule',
                'device_settings' => true,
                'label'      => esc_html__('Border Width', 'fona'),
                'section'    => $this->section,
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'css_format' => [
                    'top'    => 'border-top-width: {{value}};',
                    'right'  => 'border-right-width: {{value}};',
                    'bottom' => 'border-bottom-width: {{value}};',
                    'left'   => 'border-left-width: {{value}};'
                ],
                'priority' => 901,
            ],
            [
                'name'       => $this->builder_id.'_'.$this->id.'_border_style',
                'type'       => 'select',
                'device_settings' => true,
                'label'      => esc_html__('Border Style', 'fona'),
                'default'    => '',
                'section'    => $this->section,
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'choices'    => [
                    ''       => esc_html__('Default', 'fona'),
                    'none'   => esc_html__('None', 'fona'),
                    'solid'  => esc_html__('Solid', 'fona'),
                    'dotted' => esc_html__('Dotted', 'fona'),
                    'dashed' => esc_html__('Dashed', 'fona'),
                    'double' => esc_html__('Double', 'fona'),
                    'ridge'  => esc_html__('Ridge', 'fona'),
                    'inset'  => esc_html__('Inset', 'fona'),
                    'outset' => esc_html__('Outset', 'fona'),
                ],
                'css_format' => 'border-style: {{value}};',
                'priority' => 902,
            ],
            [
                'name'       => $this->builder_id.'_'.$this->id . '_border_color',
                'type'       => 'color',
                'device_settings' => true,
                'section'    => $this->section,
                'label'      => esc_html__('Border Color', 'fona'),
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'css_format' => 'border-color: {{value}};',
                'priority' => 903,
            ],
            [
                'name'       => $this->builder_id.'_'.$this->id . '_border_radius',
                'type'       => 'css_rule',
                'device_settings' => true,
                'section'    => $this->section,
                'label'      => esc_html__('Border Radius', 'fona'),
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'css_format' => [
                    'top'    => 'border-top-left-radius: {{value}};',
                    'right'  => 'border-top-right-radius: {{value}};',
                    'bottom' => 'border-bottom-right-radius: {{value}};',
                    'left'   => 'border-bottom-left-radius: {{value}};'
                ],
                'priority' => 904,
            ],
            [
                'name' => $this->builder_id.'_'.$this->id . '_l_heading',
                'type' => 'heading',
                'section' => $this->section,
                'title' => esc_html__('Layout', 'fona'),
                'priority' => 905,
            ],
            [
                'name' => $this->builder_id.'_'.$this->id . '_margin',
                'type' => 'css_rule',
                'section' => $this->section,
                'device_settings' => true,
                'css_format' => [
                    'top' => 'margin-top: {{value}};',
                    'right' => 'margin-right: {{value}};',
                    'bottom' => 'margin-bottom: {{value}};',
                    'left' => 'margin-left: {{value}};',
                ],
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'label' => esc_html__('Margin', 'fona'),
                'priority' => 906,
            ],
            [
                'name' => $this->builder_id.'_'.$this->id . '_padding',
                'type' => 'css_rule',
                'section' => $this->section,
                'device_settings' => true,
                'css_format' => [
                    'top' => 'padding-top: {{value}};',
                    'right' => 'padding-right: {{value}};',
                    'bottom' => 'padding-bottom: {{value}};',
                    'left' => 'padding-left: {{value}};',
                ],
                'selector' => ".{$this->builder_id}-row .element-{$this->id}, .builder-item.row-item-group .element-item.element-{$this->id}, .header-off-canvas-sidebar .element-{$this->id}",
                'label' => esc_html__('Padding', 'fona'),
                'priority' => 907,
            ],
            [
                'name' => $this->builder_id.'_'.$this->id . '_align',
                'type' => 'text_align_no_justify',
                'section' => $this->section,
                'device_settings' => true,
                'selector' => '.builder-block-' . $this->id,
                'css_format' => 'text-align: {{value}};',
                'title' => esc_html__('Align', 'fona'),
                'priority' => 908,
            ],
            [
                'name' => $this->builder_id.'_'.$this->id . '_merge',
                'type' => 'select',
                'section' => $this->section,
                'selector' => $merge_selector,
                'render_callback' => 'Zoo_Customize_Header_Builder::render',
                'device_settings' => true,
                'devices' => ['desktop', 'mobile'],
                'title' => esc_html__('Merging', 'fona'),
                'description' => esc_html__('If you choose to merge this item, the alignment setting will inherit from the item you are merging.', 'fona'),
                'choices' => [
                    0 => esc_html__('No', 'fona'),
                    'prev' => esc_html__('Merge with left item', 'fona'),
                    'next' => esc_html__('Merge with right item', 'fona'),
                ],
                'priority' => 909,
            ]
        ];
    }

    /**
     * Get builder configs
     *
     * @return  array
     */
    abstract public function get_builder_configs();

    /**
     * Get customize configs
     *
     * @return  array
     */
    abstract public function get_customize_configs(WP_Customize_Manager $wp_customize = null);

    /**
     * Echo HTML classes attribute
     *
     * @param  array $classes Custom HTML classes.
     */
    protected function element_class($class='')
    {
        $classes = '';
        if (!empty($class)) {
            if (!isset($class['element-item'])) {
                $classes .= 'element-item';
            }
            if (!isset($class['element-' . $this->id])) {
                $classes .= ' element-' . $this->id;
            }
            if(is_array($class)) {
                $classes .= ' ' . join(' ', $class);
            }else{
                $classes .=' '.$class;
            }
        }else{
            $classes .= 'element-item element-' . $this->id;
        }
        echo 'class="' . esc_attr($classes) . '"';
    }

    /**
     * Render
     */
    abstract public function render();
}
