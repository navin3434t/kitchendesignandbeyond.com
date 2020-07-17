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
function cvca_add_clever_boxes_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverBoxes_shortcode_atts', array(
            'boxes_title'               => '',
            'desktop_boxes_cols'        => '3',
            'desktopsmall_boxes_cols'   => '3',
            'tablet_boxes_cols'         => '3',
            'tabletsmall_boxes_cols'    => '2',
            'mobile_boxes_cols'         => '1',
            'min_height'     => '100',
            'boxes'          => '',
            'type'           => 'icon',
            'style'          => 'left-inline',
            'icon'           => '',
            'icon_fontsize'  => '60px',
            'image'          => '',
            'align'          => 'center',
            'media_color'    => '',
            'media_bg_color' => '',
            'box_bg_type'    => '',
            'box_color'      => '',
            'box_bg_color'   => '',
            'box_bg_image'   => '',
            'box_bg_repeat'  => 'no-repeat',
            'box_bg_size'    => 'cover',
            'box_bg_position' => 'center center',
            'box_bg_attachment' => 'scroll',
            'title'      => '',
            'description'    => '',
            'link'           => '#',
            'boxes_class'    => '',
            'el_class'       => '',
            'css'            => ''
        )),
        $atts, 'CleverBoxes'
    );

    $html = cvca_get_shortcode_view( 'boxes', $atts, $content );

    return $html;
}
add_shortcode( 'CleverBoxes', 'cvca_add_clever_boxes_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_boxes_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Boxes', 'cvca'),
            'base' => 'CleverBoxes',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display feature box with image icon or icon font.', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    'admin_label' => true,
                    'param_name' => 'boxes_title',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Desktop Columns (>=1200px)', 'cvca'),
                    'value' => array(
                        esc_html__('1', 'cvca')  => '1',
                        esc_html__('2', 'cvca')  => '2',
                        esc_html__('3', 'cvca')  => '3',
                        esc_html__('4', 'cvca')  => '4',
                        esc_html__('5', 'cvca')  => '5',
                        esc_html__('6', 'cvca')  => '6',
                        esc_html__('7', 'cvca')  => '7',
                        esc_html__('8', 'cvca')  => '8',
                        esc_html__('9', 'cvca')  => '9',
                        esc_html__('10', 'cvca')  => '10',
                    ),
                    'std' => '3',
                    'param_name' => 'desktop_boxes_cols',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Desktop Small Columns (>=992px and < 1200px)', 'cvca'),
                    'value' => array(
                        esc_html__('1', 'cvca')  => '1',
                        esc_html__('2', 'cvca')  => '2',
                        esc_html__('3', 'cvca')  => '3',
                        esc_html__('4', 'cvca')  => '4',
                        esc_html__('5', 'cvca')  => '5',
                        esc_html__('6', 'cvca')  => '6',
                        esc_html__('7', 'cvca')  => '7',
                        esc_html__('8', 'cvca')  => '8',
                        esc_html__('9', 'cvca')  => '9',
                        esc_html__('10', 'cvca')  => '10',
                    ),
                    'std' => '3',
                    'param_name' => 'desktopsmall_boxes_cols',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Tablet Columns (>=768px and < 992px)', 'cvca'),
                    'value' => array(
                        esc_html__('1', 'cvca')  => '1',
                        esc_html__('2', 'cvca')  => '2',
                        esc_html__('3', 'cvca')  => '3',
                        esc_html__('4', 'cvca')  => '4',
                        esc_html__('5', 'cvca')  => '5',
                        esc_html__('6', 'cvca')  => '6',
                        esc_html__('7', 'cvca')  => '7',
                        esc_html__('8', 'cvca')  => '8',
                        esc_html__('9', 'cvca')  => '9',
                        esc_html__('10', 'cvca')  => '10',
                    ),
                    'std' => '3',
                    'param_name' => 'tablet_boxes_cols',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Tablet Small Columns (>=480px and < 768px)', 'cvca'),
                    'value' => array(
                        esc_html__('1', 'cvca')  => '1',
                        esc_html__('2', 'cvca')  => '2',
                        esc_html__('3', 'cvca')  => '3',
                        esc_html__('4', 'cvca')  => '4',
                        esc_html__('5', 'cvca')  => '5',
                        esc_html__('6', 'cvca')  => '6',
                        esc_html__('7', 'cvca')  => '7',
                        esc_html__('8', 'cvca')  => '8',
                        esc_html__('9', 'cvca')  => '9',
                        esc_html__('10', 'cvca')  => '10',
                    ),
                    'std' => '2',
                    'param_name' => 'tabletsmall_boxes_cols',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Mobile Columns (< 480px)', 'cvca'),
                    'value' => array(
                        esc_html__('1', 'cvca')  => '1',
                        esc_html__('2', 'cvca')  => '2',
                        esc_html__('3', 'cvca')  => '3',
                        esc_html__('4', 'cvca')  => '4',
                        esc_html__('5', 'cvca')  => '5',
                        esc_html__('6', 'cvca')  => '6',
                        esc_html__('7', 'cvca')  => '7',
                        esc_html__('8', 'cvca')  => '8',
                        esc_html__('9', 'cvca')  => '9',
                        esc_html__('10', 'cvca')  => '10',
                    ),
                    'std' => '1',
                    'param_name' => 'mobile_boxes_cols',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Box min height(px)', 'cvca'),
                    'value' => '100',
                    'param_name' => 'min_height',
                    'description' => esc_html__('Only type value is a number', 'cvca'),
                ),
                array(
                    'type' => 'param_group',
                    'heading' => esc_html__('Boxes', 'cvca'),
                    'value' => '',
                    'param_name' => 'boxes',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Type', 'cvca'),
                            'value' => array(
                                esc_html__('Icon', 'cvca') => 'icon',
                                esc_html__('Image', 'cvca') => 'image',
                            ),
                            'std' => 'icon',
                            'param_name' => 'type',
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__('Image', 'cvca'),
                            'value' => '',
                            'param_name' => 'image',
                            'description' => esc_html__('Image demo of box', 'cvca'),
                            'dependency' => array('element' => 'type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'iconpicker',
                            'heading' => esc_html__('Icon Class', 'cvca'),
                            'value' => '',
                            'param_name' => 'icon',
                            'description' => esc_html__('Class of icon font icon (Awesome font, or CleverSoft font) you want use', 'cvca'),
                            'dependency' => array('element' => 'type', 'value' => array('icon')),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Icon Font Size', 'cvca'),
                            'value' => '60px',
                            'param_name' => 'icon_fontsize',
                            'description' => esc_html__('Font size for icon', 'cvca'),
                            'dependency' => array('element' => 'type', 'value' => array('icon')),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Icon/Image Position', 'cvca'),
                            'value' => array(
                                esc_html__('Before Title', 'cvca')  => 'before',
                                esc_html__('After Title', 'cvca')   => 'after',
                                esc_html__('Left Title', 'cvca')    => 'left-inline',
                                esc_html__('Right Title', 'cvca')   => 'right-inline',
                                esc_html__('Left Box', 'cvca')    => 'left',
                                esc_html__('Right Box', 'cvca')   => 'right',
                            ),
                            'std' => 'left-inline',
                            'param_name' => 'style',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Text align', 'cvca'),
                            'value' => array(
                                esc_html__('Left', 'cvca')      => 'text-left',
                                esc_html__('Right', 'cvca')     => 'text-right',
                                esc_html__('Center', 'cvca')    => 'text-center',
                            ),
                            'std' => 'text-left',
                            'param_name' => 'align',
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Icon color', 'cvca'),
                            'value' => '',
                            'param_name' => 'media_color',
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Icon background color', 'cvca'),
                            'value' => '',
                            'param_name' => 'media_bg_color',
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Box Background Type', 'cvca'),
                            'value' => array(
                                esc_html__('None', 'cvca')      => 'none',
                                esc_html__('Image', 'cvca')      => 'image',
                                esc_html__('Color', 'cvca')      => 'color',
                            ),
                            'std' => 'none',
                            'param_name' => 'box_bg_type',
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Box Color', 'cvca'),
                            'value' => '',
                            'param_name' => 'box_color',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image', 'color')),
                        ),
                        array(
                            'type' => 'colorpicker',
                            'heading' => esc_html__('Box Background Color', 'cvca'),
                            'value' => '',
                            'param_name' => 'box_bg_color',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('color')),
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__('Box Background Image', 'cvca'),
                            'value' => '',
                            'param_name' => 'box_bg_image',
                            'description' => esc_html__('Background image of box', 'cvca'),
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Background Size', 'cvca'),
                            'value' => array(
                                esc_html__('Auto', 'cvca')          => 'auto',
                                esc_html__('Cover', 'cvca')         => 'cover',
                                esc_html__('Contain', 'cvca')       => 'contain',
                                esc_html__('Initial', 'cvca')       => 'initial',
                            ),
                            'std' => 'cover',
                            'param_name' => 'box_bg_size',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Background Repeat', 'cvca'),
                            'value' => array(
                                esc_html__('No Repeat', 'cvca')      => 'no-repeat',
                                esc_html__('Repeat', 'cvca')         => 'repeat',
                                esc_html__('Repeat X', 'cvca')       => 'repeat-x',
                                esc_html__('Repeat Y', 'cvca')       => 'repeat-y',
                            ),
                            'std' => 'no-repeat',
                            'param_name' => 'box_bg_repeat',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Background Position', 'cvca'),
                            'value' => array(
                                esc_html__('Left Top', 'cvca')          => 'left top',
                                esc_html__('Left Center', 'cvca')       => 'left center',
                                esc_html__('Left Bottom', 'cvca')       => 'left bottom',
                                esc_html__('Right Top', 'cvca')         => 'right top',
                                esc_html__('Right Center', 'cvca')      => 'right center',
                                esc_html__('Right Bottom', 'cvca')      => 'right bottom',
                                esc_html__('Center Top', 'cvca')        => 'center top',
                                esc_html__('Center Bottom', 'cvca')     => 'center bottom',
                                esc_html__('Center Center', 'cvca')     => 'center center',
                            ),
                            'std' => 'center center',
                            'param_name' => 'box_bg_position',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Background Attachment', 'cvca'),
                            'value' => array(
                                esc_html__('Scroll', 'cvca')          => 'scroll',
                                esc_html__('Fixed', 'cvca')           => 'fixed',
                            ),
                            'std' => 'scroll',
                            'param_name' => 'box_bg_attachment',
                            'dependency' => array('element' => 'box_bg_type', 'value' => array('image')),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Title', 'cvca'),
                            'value' => '',
                            'admin_label' => true,
                            'param_name' => 'title',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => esc_html__( 'Description', 'cvca' ),
                            'param_name' => 'description', // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
                            'value' => '',
                            'description' => esc_html__( 'Enter your description.', 'cvca' )
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => esc_html__('Link', 'cvca'),
                            'value' => '#',
                            'param_name' => 'link',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__( 'Extra class name', 'cvca' ),
                            'param_name' => 'boxes_class',
                            'description' => esc_html__( 'Style particular content boxes element differently.', 'cvca' )
                        ),
                    )
                ),

                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                ),

                // Design option tab
                array(
                    'type'       => 'css_editor',
                    'counterup'  => __( 'Css', 'cvca' ),
                    'param_name' => 'css',
                    'group'      => __( 'Design options', 'cvca' ),
                ),
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_boxes_shortcode_with_vc', 10, 0 );
