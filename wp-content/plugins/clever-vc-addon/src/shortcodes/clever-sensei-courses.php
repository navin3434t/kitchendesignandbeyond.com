<?php
/**
 * Add shortcode
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array    $atts    Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
if ( function_exists( 'Sensei' ) ) {
    function cvca_add_clever_sensei_courses_shortcode( $atts, $content = null )
    {
        $atts = shortcode_atts(
            apply_filters('CleverSenseiCourses_shortcode_atts',array(
                'title'     => '',
                'category'  => '',
                'ids'       => '',
                'exclude'   => '',
                'cols'      => '4',
                'number'    => '8',
                'order'     => 'DESC',
                'orderby'   => 'date',
                'show_desc' => 'yes',
                'desc_length' => '7',
                'show_image' => 'yes',
                'show_date'     => '',
                'show_category' => '',
                'show_author' => 'yes',
                'show_review' => 'yes',
                'show_lesson' => 'yes',
                'show_comment' => 'yes',
                'show_price' => 'yes',
                'show_quickview' => 'yes',
                'course_layout' => '',
                'el_class'  => '',
                'css'       => ''
            )),
            $atts, 'CleverSenseiCourses'
        );

        $html = cvca_get_shortcode_view( 'sensei-courses', $atts, $content );

        return $html;
    }
    add_shortcode( 'CleverSenseiCourses', 'cvca_add_clever_sensei_courses_shortcode' );

    /**
     * Integrate to Visual Composer
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     */

    function cvca_integrate_clever_sensei_courses_shortcode_with_vc()
    {
        vc_map(
            array(
                'name'        => esc_html__('Clever Sensei Courses', 'cvca'),
                'base'        => 'CleverSenseiCourses',
                'icon'        => '',
                'category'    => esc_html__('CleverSoft', 'cvca'),
                'description' => esc_html__('Sensei courses shortcode.', 'cvca'),
                'params'      => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Title', 'cvca' ),
                        'param_name' => 'title',
                        'description' => esc_html__('', 'cvca'),
                    ),
                    array(
                        'type'        => 'cvca_multiselect',
                        'heading'     => esc_html__('Categories', 'cvca'),
                        'description' => esc_html__('Comma separated list of course categories', 'cvca'),
                        'param_name'  => 'category',
                        'value'         => get_course_categories_for_vc(),
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__('Number of columns', 'cvca'),
                        'param_name'    => 'cols',
                        'value'         => '4',
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__('Number of courses to display', 'cvca'),
                        'param_name'    => 'number',
                        'value'         => '8',
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => esc_html__('Order', 'cvca'),
                        'param_name'    => 'order',
                        'value'         => array(
                            esc_html__('ASC', 'cvca' ) => 'asc',
                            esc_html__('DESC', 'cvca' ) => 'desc',
                        ),
                        'description'   => esc_html__('The order in which the courses will be displayed.', 'cvca'),
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => esc_html__('Order By', 'cvca'),
                        'param_name'    => 'orderby',
                        'value'         => array(
                            esc_html__('Date', 'cvca' ) => 'date',
                            esc_html__('Name', 'cvca' ) => 'name',
                            esc_html__('Menu Order', 'cvca' ) => 'menu_order',
                        ),
                        'description' => esc_html__('The order in which the courses will be displayed.', 'cvca'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Course Description", 'cvca'),
                        'param_name' => 'show_desc',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__('Course Description Length', 'cvca'),
                        'param_name'    => 'desc_length',
                        'value'         => '7',
                        'dependency' => array('element' => 'show_desc', 'value' => array('yes')),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Image", 'cvca'),
                        'param_name' => 'show_image',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Date", 'cvca'),
                        'param_name' => 'show_date',
                        'std' => '',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Category", 'cvca'),
                        'param_name' => 'show_category',
                        'std' => '',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Author", 'cvca'),
                        'param_name' => 'show_author',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Review", 'cvca'),
                        'param_name' => 'show_review',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Lesson", 'cvca'),
                        'param_name' => 'show_lesson',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Comment", 'cvca'),
                        'param_name' => 'show_comment',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Price", 'cvca'),
                        'param_name' => 'show_price',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__("Show Quick View", 'cvca'),
                        'param_name' => 'show_quickview',
                        'std' => 'yes',
                        'value' => array(esc_html__('Yes', 'cvca') => 'yes'),
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => esc_html__('Choose Layout', 'cvca'),
                        'param_name'    => 'course_layout',
                        'value'         => array(
                            esc_html__('Default', 'cvca' ) => '',
                            esc_html__('Layout 1', 'cvca' ) => '1',
                            esc_html__('Layout 2', 'cvca' ) => '2',
                        ),
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__( 'Extra class name', 'cvca' ),
                        'param_name'    => 'el_class',
                        'description'   => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                    ),
                    array(
                        'type'          => 'css_editor',
                        'heading'       => __( 'Css', 'cvca' ),
                        'param_name'    => 'css',
                        'group'         => __( 'Design options', 'cvca' ),
                    ),
                )
            )
        );
    }
    add_action( 'vc_before_init', 'cvca_integrate_clever_sensei_courses_shortcode_with_vc', 10, 0 );
}
