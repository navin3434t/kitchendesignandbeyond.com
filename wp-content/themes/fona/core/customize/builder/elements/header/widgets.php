<?php
/**
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
class Zoo_Customize_Header_Builder_Widget_1 extends Zoo_Customize_Builder_Element
{
    public $id = 'header-widget-1';

    /**
     * Constructor
     */
    function __construct()
    {
        $this->builder_id = 'header';
    }

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Header Widget 1', 'fona'),
            'id' => 'header-widget-1',
            'width' => '3',
            'section' => 'sidebar-widgets-header-widget-1'
        );
    }

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        return array();
    }

    public function render()
    {
        $args  = func_get_args();
        $align = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');
        $html_class = 'builder-widget-element widget-area';

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $html_class .= ' '.esc_attr($align);
        }

        if (is_active_sidebar($this->id)) {
            echo '<div class="'.$html_class.'">';
            // if (!is_customize_preview()) {
            //     do_action('zoo_before_rendering_footer_widgets', $this->id);
            // }
            $showed = dynamic_sidebar($this->id);
            // var_dump($showed);
            echo '</div>';
        }
    }
}

class Zoo_Customize_Header_Builder_Widget_2 extends Zoo_Customize_Header_Builder_Widget_1
{
    public $id = 'header-widget-2';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Header Widget 2', 'fona'),
            'id' => 'header-widget-2',
            'width' => '3',
            'section' => 'sidebar-widgets-header-widget-2'
        );
    }
}

class Zoo_Customize_Header_Builder_Widget_3 extends Zoo_Customize_Header_Builder_Widget_1
{
    public $id = 'header-widget-3';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Header Widget 3', 'fona'),
            'id' => 'header-widget-3',
            'width' => '3',
            'section' => 'sidebar-widgets-header-widget-3'
        );
    }
}

class Zoo_Customize_Header_Builder_Widget_4 extends Zoo_Customize_Header_Builder_Widget_1
{
    public $id = 'header-widget-4';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Header Widget 4', 'fona'),
            'id' => 'header-widget-4',
            'width' => '3',
            'section' => 'sidebar-widgets-header-widget-4'
        );
    }
}

class Zoo_Customize_Header_Builder_Widget_5 extends Zoo_Customize_Header_Builder_Widget_1
{
    public $id = 'header-widget-5';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Header Widget 5', 'fona'),
            'id' => 'header-widget-5',
            'width' => '3',
            'section' => 'sidebar-widgets-header-widget-5'
        );
    }
}


 function zoo_change_header_widgets_location($wp_customize)
 {
     for ($i = 1; $i <= 5; $i++) {
         if ($wp_customize->get_section('sidebar-widgets-header-widget-'.$i)) {
             $wp_customize->get_section('sidebar-widgets-header-widget-'.$i)->panel = 'header_settings';
         }
     }
 }
 add_action('customize_register', 'zoo_change_header_widgets_location', 198);

/**
 * Always show footer widgets for customize builder
 *
 * @param $active
 * @param $section
 * @return bool
 */
function zoo_customize_header_widgets_show($active, $section)
{
    if (strpos($section->id, 'widgets-header-widget-')) {
        $active = true;
    }
    return $active;
}
add_filter('customize_section_active', 'zoo_customize_header_widgets_show', 15, 2);

$self->add_element('header', new Zoo_Customize_Header_Builder_Widget_1());
$self->add_element('header', new Zoo_Customize_Header_Builder_Widget_2());
$self->add_element('header', new Zoo_Customize_Header_Builder_Widget_3());
$self->add_element('header', new Zoo_Customize_Header_Builder_Widget_4());
$self->add_element('header', new Zoo_Customize_Header_Builder_Widget_5());
