<?php
/**
 * Zoo_Customize_Builder_Element_Primary_Menu
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Primary_Menu extends Zoo_Customize_Builder_Element
{
    function __construct()
    {
        $this->id       = 'primary-menu';
        $this->title    = esc_html__('Primary Menu', 'fona');
        $this->width    = 6;
        $this->selector = '.site-header .element-primary-menu > ul.nav-menu';
        $this->section  = 'header_menu_primary';
        $this->panel    = 'header_settings';
    }

    function get_builder_configs()
    {
        return array(
            'name'    => $this->title,
            'id'      => $this->id,
            'width'   => $this->width,
            'devices' => 'desktop',
            'section' => $this->section // Customizer section to focus when click settings
        );
    }

    function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $config = array(
            array(
                'name'           => $this->section,
                'type'           => 'section',
                'panel'          => $this->panel,
                'theme_supports' => '',
                'title'          => $this->title,
                'description'    => sprintf(__('Assign <a href="#menu_locations"  class="zoo-customize-focus-button">Menu Location</a> for %1$s', 'fona'), $this->title)
            ),

            array(
                'name'            => 'header_primary_menu_style',
                'type'            => 'image_select',
                'section'         => $this->section,
                'selector'        => '.row-item-' . $this->id . " .primary-menu",
                'render_callback' => [$this, 'render'],
                'title'           => esc_html__('Menu Preset', 'fona'),
                'default'         => 'style-plain',
                'css_format'      => 'html_class',
                'choices'         => array(
                    'style-plain'         => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_1.svg',
                    ),
                    'style-border-bottom' => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_2.svg',
                    ),
                    'style-border-top'    => array(
                        'img' => ZOO_THEME_URI . 'core/assets/icons/menu_style_3.svg',
                    ),
                )
            ),

            array(
                'name'           => 'header_primary_menu_hide_arrow',
                'type'           => 'checkbox',
                'default'         => '',
                'section'        => $this->section,
                'selector'       => '.row-item-' . $this->id . " .{$this->id}",
                'checkbox_label' => esc_html__('Hide menu dropdown arrow', 'fona')
            ),

            array(
                'name'            => 'header_primary_menu_arrow_size',
                'type'            => 'slider',
                'devices_setting' => true,
                'section'         => $this->section,
                'selector'        => $this->selector . " li > a > .zoo-icon-down",
                'max'             => 20,
                'label'           => esc_html__('Arrow icon size', 'fona'),
                'css_format'      => " font-size: {{value}};",
                'required'        => ['header_primary_menu_hide_arrow', '!=', 1]
            ),
            array(
                'name'           => 'header_primary_menu_advanced_styling',
                'type'           => 'checkbox',
                'section'        => $this->section,
                'title' => esc_html__('Enable Advanced Styling', 'fona'),
                'checkbox_label' => esc_html__('Allow change style if checked.', 'fona'),
            ),
            array(
                'name'    => 'header_primary_menu_bar_heading',
                'type'    => 'heading',
                'section' => $this->section,
                'title'   => esc_html__('Menu Bar', 'fona'),
                'required'=>['header_primary_menu_advanced_styling','==',1]
            ),

            array(
                'name'        => 'header_primary_menu_bar_styling',
                'type'        => 'styling',
                'section'     => $this->section,
                'title'       => esc_html__('Menu Bar Items Styling', 'fona'),
                'description' => esc_html__('Styling for top level menu items', 'fona'),
                'required'=>['header_primary_menu_advanced_styling','==',1],
                'selector'    => array(
                    'normal'           => "{$this->selector} > li",
                    'normal_text_color'    => "{$this->selector} > li > a",
                    'normal_padding'    => "{$this->selector} > li > a",
                    'normal_border_style'    => "{$this->selector} > li > a",
                    'normal_border_color'    => "{$this->selector} > li > a",
                    'normal_border_width'    => "{$this->selector} > li > a",
                    'hover'            => "{$this->selector} > li:hover, {$this->selector} > li.current-menu-item, {$this->selector} > li.current-menu-ancestor, {$this->selector} > li.current-menu-parent",
                    'hover_text_color' => "{$this->selector} > li:hover > a,{$this->selector} > li > a:hover, {$this->selector} > li > a:focus, {$this->selector} > li.current-menu-item > a, {$this->selector} > li.current-menu-ancestor > a, {$this->selector} > li.current-menu-parent > a",
                    'hover_border_style' => "{$this->selector} > li:hover > a,{$this->selector} > li > a:hover, {$this->selector} > li > a:focus, {$this->selector} > li.current-menu-item > a, {$this->selector} > li.current-menu-ancestor > a, {$this->selector} > li.current-menu-parent > a, {$this->selector} > li.active > a",
                    'hover_border_color' => "{$this->selector} > li:hover > a,{$this->selector} > li > a:hover, {$this->selector} > li > a:focus, {$this->selector} > li.current-menu-item > a, {$this->selector} > li.current-menu-ancestor > a, {$this->selector} > li.current-menu-parent > a, {$this->selector} > li.active > a",
                    'hover_border_width' => "{$this->selector} > li:hover > a,{$this->selector} > li > a:hover, {$this->selector} > li > a:focus, {$this->selector} > li.current-menu-item > a, {$this->selector} > li.current-menu-ancestor > a, {$this->selector} > li.current-menu-parent > a, {$this->selector} > li.active > a",
                ),
                'css_format'  => 'styling',
                'fields'      => array(
                    'tabs'          => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover'  => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        //'padding' => false // disable for special field.
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
                    ), // disable hover tab and all fields inside.
                )
            ),

            array(
                'name'        => 'header_primary_menu_typography',
                'type'        => 'typography',
                'section'     => $this->section,
                'title'       => esc_html__('Menu Bar Items Typography', 'fona'),
                'description' => esc_html__('Typography for top level menu items', 'fona'),
                'selector'    => "{$this->selector} > li > a",
                'css_format'  => 'typography',
                'required'=>['header_primary_menu_advanced_styling','==',1],
            ),
            array(
                'name'    => 'primary_menu_sub_menu_heading',
                'type'    => 'heading',
                'section' => $this->section,
                'title'   => esc_html__('Sub Menu', 'fona'),
                'required'=>['header_primary_menu_advanced_styling','==',1],
            ),
            array(
                'name'            => 'header_primary_menu_sub_menu_max_width',
                'type'            => 'slider',
                'section'         =>  $this->section,
                'default'         => '280',
                'max'             => 400,
                'title'           => esc_html__('Sub Menu Max Width', 'fona'),
                'selector'        => "{$this->selector} li:not(.is-mega-menu)>ul",
                'css_format'      => 'max-width: {{value}};',
                'required'=>['header_primary_menu_advanced_styling','==',1],
            ),
            array(
                'name'        => 'header_primary_menu_sub_menu_styling',
                'type'        => 'styling',
                'section'     => $this->section,
                'title'       => esc_html__('Sub Menu Items Styling', 'fona'),
                'description' => esc_html__('Styling for sub menu items', 'fona'),
                'required'=>['header_primary_menu_advanced_styling','==',1],
                'selector'    => array(
                    'normal'           => "{$this->selector} li.menu-item li.menu-item",
                    'normal_padding' => "{$this->selector} ul.dropdown-submenu>li.menu-item",
                    'normal_text_color'    => "{$this->selector} li.menu-item li.menu-item>a,{$this->selector} .mega-menu-content a",
                    'normal_box_shadow'    => "{$this->selector} ul.dropdown-submenu,{$this->selector}>li.is-mega-menu>.mega-menu-content",
                    'normal_margin'    => "{$this->selector} ul.dropdown-submenu li.menu-item,{$this->selector}>li.is-mega-menu>.mega-menu-content",
                    'normal_bg_color'    => "{$this->selector} ul.dropdown-submenu,{$this->selector}>li.is-mega-menu>.mega-menu-content",
                    'hover'            => "{$this->selector} li.menu-item li.menu-item:hover,{$this->selector} li.menu-item li.menu-item.current-menu-item > a, {$this->selector} li.menu-item li.menu-item.current-menu-ancestor > a, {$this->selector} li.menu-item li.menu-item.current-menu-parent > a",
                    'hover_text_color' => "{$this->selector} li.menu-item li.menu-item:hover > a, {$this->selector} li.menu-item li.menu-item > a:focus, {$this->selector} li.menu-item li.menu-item.current-menu-item > a, {$this->selector} li.menu-item li.menu-item.current-menu-ancestor > a, {$this->selector} li.menu-item li.menu-item.current-menu-parent > a,{$this->selector} .mega-menu-content a:hover",
                ),
                'css_format'  => 'styling',
                'fields'      => array(
                    'tabs'          => array(
                        'normal' => esc_html__('Normal', 'fona'),
                        'hover'  => esc_html__('Hover/Active', 'fona'),
                    ),
                    'normal_fields' => array(
                        //'padding' => false // disable for special field.
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
                    ), // disable hover tab and all fields inside.
                )
            ),

            array(
                'name'        => 'header_primary_menu_sub_menu_typography',
                'type'        => 'typography',
                'section'     => $this->section,
                'title'       => esc_html__('Sub Menu Items Typography', 'fona'),
                'description' => esc_html__('Typography for sub menu items', 'fona'),
                'required'=>['header_primary_menu_advanced_styling','==',1],
                'selector'    => "{$this->selector} li.menu-item li.menu-item > a,{$this->selector} .mega-menu-content a",
                'css_format'  => 'typography',
            ),

        );

        return array_merge($config, $this->get_layout_configs('#site-header'));
    }

    function menu_fallback_cb()
    {
        $pages = get_pages(array(
            'child_of'     => 0,
            'sort_order'   => 'ASC',
            'sort_column'  => 'menu_order, post_title',
            'hierarchical' => 0,
            'parent'       => 0,
            'exclude_tree' => array(),
            'number'       => 10,
        ));


        echo '<ul class="' . $this->id . '-ul menu nav-menu menu-pages">';
        foreach (( array )$pages as $p) {
            $class = '';
            if (is_page($p)) {
                $class = 'current-menu-item';
            }

            echo '<li id="menu-item-' . esc_attr($p->ID) . '" class="menu-item menu-item-type-page  menu-item-' . esc_attr($p->ID . ' ' . $class) . '"><a href="' . esc_url(get_the_permalink($p)) . '"><span class="link-before">' . apply_filters('', $p->post_title) . '</span></a></li>';
        }
        echo '</ul>';
    }


    function render()
    {
        $args    = func_get_args();
        $align   = zoo_customize_get_setting($this->builder_id.'_'.$this->id.'_align');
        $style   = sanitize_text_field(zoo_customize_get_setting('header_primary_menu_style'));
        $is_mega = false;

        if ($style) {
            $style = sanitize_text_field($style);
        }

        $hide_arrow = sanitize_text_field(zoo_customize_get_setting('header_primary_menu_hide_arrow'));

        if ($hide_arrow) {
            $style .= ' hide-arrow-active';
        }

        if ($align) {
            if (!empty($args[1]) && is_array($align)) {
                $align = $align[$args[1]];
            }
            $style .= ' element-align-'.esc_attr($align);
        }

        $menu_locations = get_nav_menu_locations();

        if (isset($menu_locations[$this->id])) {
            $is_mega = get_term_meta($menu_locations[$this->id], 'zoo_menu_is_mega', true);
        }

        $atts = [
            'menu_class' => $style
        ];

        if ($is_mega) {
            $atts['menu_class'] .= ' zoo_menu_is_mega';
        }

        $tpl = apply_filters('header/element/primary-menu', ZOO_THEME_DIR . 'core/customize/templates/header/element-primary-menu.php', $atts);

        require $tpl;
    }
}

$self->add_element('header', new Zoo_Customize_Builder_Element_Primary_Menu());
