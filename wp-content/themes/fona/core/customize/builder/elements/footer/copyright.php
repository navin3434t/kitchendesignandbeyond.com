<?php
/**
 * Zoo_Customize_Builder_Element_Footer_Copyright
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Footer_Copyright extends Zoo_Customize_Builder_Element
{
    public $id = 'footer_copyright'; // Required
    public $section = 'footer_copyright'; // Optional
    public $name = 'footer_copyright'; // Optional
    public $label = ''; // Optional

    /**
     * Optional construct
     *
     */
    public function __construct()
    {
        $this->label = esc_html__('Copyright', 'fona');
        $this->builder_id = 'footer';
    }

    /**
     * Register Builder item
     * @return array
     */
    public function get_builder_configs()
    {
        return array(
            'name'    => esc_html__('Copyright', 'fona'),
            'id'      => $this->id,
            'col'     => 0,
            'width'   => 6,
            'section' => $this->section // Customizer section to focus when click settings
        );
    }

    /**
     * Optional, Register customize section and panel.
     *
     * @return array
     */
    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $fn = array( $this, 'render' );

        return array(
            array(
                'name'  => $this->section,
                'type'  => 'section',
                'panel' => 'footer_settings',
                'title' => $this->label,
            ),

            array(
                'name'            => $this->name,
                'type'            => 'textarea',
                'section'         => $this->section,
                'selector'        => '.builder-footer-copyright-item',
                'render_callback' => $fn,
                'theme_supports'  => '',
                'default'         => esc_html__('Copyright', 'fona') . ' &copy; {current_year} {site_title}.',
                'title'           => esc_html__('Copyright Text', 'fona'),
                'description'     => esc_html__('Arbitrary HTML code or shortcode. Available tags: {current_year}, {site_title}, {theme_author}', 'fona'),
            ),

            array(
                'name'       => $this->name . '_typography',
                'type'       => 'typography',
                'section'    => $this->section,
                'title'      => esc_html__('Copyright Text Typography', 'fona'),
                'selector'   => '.row-item-footer_copyright, .row-item-footer_copyright p',
                'css_format' => 'typography',
                'default'    => array(),
            ),

            array(
                'name'            => $this->name . '_text_align',
                'type'            => 'text_align',
                'section'         => $this->section,
                'default'         => 'left',
                'selector'        => '.footer-row .builder-block-footer_copyright',
                'css_format'      => 'text-align: {{value}};',
                'title'           => esc_html__('Align', 'fona'),
                'device_settings' => true,
            ),
        );
    }

    /**
     * Optional. Render item content
     */
    public function render()
    {
        $tags = array(
            'current_year' => date_i18n('Y'),
            'site_title'   => get_bloginfo('name'),
            'theme_author' => sprintf('<a href="zootemplate.com">%1$s</a>', 'fona'), // Brand name
        );

        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');
        $html_class = 'builder-footer-copyright-item footer-copyright';

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $html_class .= ' '.esc_attr($align);
        }

        $content = zoo_customize_get_setting($this->name);

        foreach ($tags as $k => $v) {
            $content = str_replace('{' . $k . '}', $v, $content);
        }

        echo '<div class="'.$html_class.'">';
        echo apply_filters('zoo_the_content', $content); // WPCS: XSS OK.
        echo '</div>';
    }
}

$self->add_element('footer', new Zoo_Customize_Builder_Element_Footer_Copyright());
