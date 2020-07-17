<?php
/**
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
class Zoo_Customize_Builder_Element_Widget_1 extends Zoo_Customize_Builder_Element
{
    public $id = 'footer-widget-1';

    /**
     * Constructor
     */
    function __construct()
    {
        $this->builder_id = 'footer';
    }

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 1', 'fona'),
            'id' => 'footer-widget-1',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-1'
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

class Zoo_Customize_Builder_Element_Widget_2 extends Zoo_Customize_Builder_Element_Widget_1
{
    public $id = 'footer-widget-2';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 2', 'fona'),
            'id' => 'footer-widget-2',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-2'
        );
    }
}

class Zoo_Customize_Builder_Element_Widget_3 extends Zoo_Customize_Builder_Element_Widget_1
{
    public $id = 'footer-widget-3';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 3', 'fona'),
            'id' => 'footer-widget-3',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-3'
        );
    }
}

class Zoo_Customize_Builder_Element_Widget_4 extends Zoo_Customize_Builder_Element_Widget_1
{
    public $id = 'footer-widget-4';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 4', 'fona'),
            'id' => 'footer-widget-4',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-4'
        );
    }
}

class Zoo_Customize_Builder_Element_Widget_5 extends Zoo_Customize_Builder_Element_Widget_1
{
    public $id = 'footer-widget-5';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 5', 'fona'),
            'id' => 'footer-widget-5',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-5'
        );
    }
}

class Zoo_Customize_Builder_Element_Widget_6 extends Zoo_Customize_Builder_Element_Widget_1
{
    public $id = 'footer-widget-6';

    public function get_builder_configs()
    {
        return array(
            'name' => esc_html__('Footer Widget 6', 'fona'),
            'id' => 'footer-widget-6',
            'width' => '3',
            'section' => 'sidebar-widgets-footer-widget-6'
        );
    }
}


 function zoo_change_footer_widgets_location($wp_customize)
 {
     for ($i = 1; $i<= 6; $i++) {
         if ($wp_customize->get_section('sidebar-widgets-footer-widget-'.$i)) {
             $wp_customize->get_section('sidebar-widgets-footer-widget-'.$i)->panel = 'footer_settings';
         }
     }
 }
 add_action('customize_register', 'zoo_change_footer_widgets_location', 199);

/**
 * Always show footer widgets for customize builder
 *
 * @param $active
 * @param $section
 * @return bool
 */
function zoo_customize_footer_widgets_show($active, $section)
{
    if (strpos($section->id, 'widgets-footer-widget-')) {
        $active = true;
    }
    return $active;
}
add_filter('customize_section_active', 'zoo_customize_footer_widgets_show', 15, 2);

$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_1());
$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_2());
$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_3());
$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_4());
$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_5());
$self->add_element('footer', new Zoo_Customize_Builder_Element_Widget_6());
