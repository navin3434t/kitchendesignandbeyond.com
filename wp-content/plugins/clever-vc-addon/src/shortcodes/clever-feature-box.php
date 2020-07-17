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
function cvca_add_clever_feature_box_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverFeatureBox_shortcode_atts',array(
            'type'              => 'icon',
            'style'             => 'left-inline',
            'icon_type'         => 'fontawesome',
            'icon_fontawesome'  => 'fa fa-adjust',
            'icon_openiconic'   => 'vc-oi vc-oi-dial',
            'icon_typicons'     => 'typcn typcn-adjust-brightness',
            'icon_entypo'       => 'entypo-icon entypo-icon-note',
            'icon_linecons'     => 'vc_li vc_li-heart',
            'icon_monosocial'   => 'vc-mono vc-mono-fivehundredpx',
            'icon_material'     => 'vc-material vc-material-cake',
            'icon_cleverfont'   => 'cs-font clever-icon-heart-1',
            'icon_strokegap'    => 'icon icon-WorldWide',
            'icon_fontsize'     => '60px',
            'image'             => '',
            'align'             => 'center',
            'media_color'       => '',
            'media_bg_color'    => '',
            'box_bg_type'       => '',
            'box_bg_color'      => '',
            'box_bg_image'      => '',
            'box_bg_repeat'     => 'no-repeat',
            'box_bg_size'       => 'cover',
            'box_bg_position'   => 'center center',
            'box_bg_attachment' => 'scroll',
            'title'             => '',
            'content'           => '',
            'link'              => '#',
            'el_class'          => '',
            'css'               => ''
        )),
        $atts, 'CleverFeatureBox'
    );

    $html = cvca_get_shortcode_view( 'feature-box', $atts, $content );

    return $html;
}
add_shortcode( 'CleverFeatureBox', 'cvca_add_clever_feature_box_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_feature_box_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Feature Box', 'cvca'),
            'base' => 'CleverFeatureBox',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display feature box with image icon or icon font.', 'cvca'),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Type', 'cvca'),
                    'value' => array(
                        esc_html__('Text', 'cvca') => 'text',
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
    				'type' => 'dropdown',
    				'heading' => esc_html__( 'Icon library', 'cvca' ),
    				'value' => array(
    					esc_html__( 'Font Awesome', 'cvca' ) => 'fontawesome',
    					esc_html__( 'Open Iconic', 'cvca' ) => 'openiconic',
    					esc_html__( 'Typicons', 'cvca' ) => 'typicons',
    					esc_html__( 'Entypo', 'cvca' ) => 'entypo',
    					esc_html__( 'Linecons', 'cvca' ) => 'linecons',
    					esc_html__( 'Mono Social', 'cvca' ) => 'monosocial',
    					esc_html__( 'Material', 'cvca' ) => 'material',
    					esc_html__( 'Cleverfont', 'cvca' ) => 'cleverfont',
    					esc_html__( 'Stroke gap', 'cvca' ) => 'strokegap',
    				),
    				'admin_label' => true,
    				'param_name' => 'icon_type',
    				'description' => esc_html__( 'Select icon library.', 'cvca' ),
                    'dependency' => array('element' => 'type', 'value' => array('icon')),
    			),

                array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_fontawesome',
    				'value' => 'fa fa-adjust',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'fontawesome',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),

                array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_openiconic',
    				'value' => 'vc-oi vc-oi-dial',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'openiconic',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'openiconic',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),

                array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_typicons',
    				'value' => 'typcn typcn-adjust-brightness',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'typicons',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'typicons',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),
    			array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_entypo',
    				'value' => 'entypo-icon entypo-icon-note',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'entypo',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'entypo',
    				),
    			),
    			array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_linecons',
    				'value' => 'vc_li vc_li-heart',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'linecons',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'linecons',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),
    			array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_monosocial',
    				'value' => 'vc-mono vc-mono-fivehundredpx',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'monosocial',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'monosocial',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),
    			array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_material',
    				'value' => 'vc-material vc-material-cake',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'material',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'material',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),

                array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_cleverfont',
    				'value' => 'cs-font clever-icon-heart-1',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'cleverfont',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'cleverfont',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
    			),

                array(
    				'type' => 'iconpicker',
    				'heading' => esc_html__( 'Icon', 'cvca' ),
    				'param_name' => 'icon_strokegap',
    				'value' => 'icon icon-WorldWide',
    				// default value to backend editor admin_label
    				'settings' => array(
    					'emptyIcon' => false,
    					// default true, display an "EMPTY" icon?
    					'type' => 'strokegap',
    					'iconsPerPage' => 4000,
    					// default 100, how many icons per/page to display
    				),
    				'dependency' => array(
    					'element' => 'icon_type',
    					'value' => 'strokegap',
    				),
    				'description' => esc_html__( 'Select icon from library.', 'cvca' ),
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
                    'dependency' => array('element' => 'type', 'value' => array('icon', 'image')),
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
                    'dependency' => array('element' => 'type', 'value' => array('icon', 'image')),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Icon background color', 'cvca'),
                    'value' => '',
                    'param_name' => 'media_bg_color',
                    'dependency' => array('element' => 'type', 'value' => array('icon', 'image')),
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
                    "admin_label" => true,
                    'param_name' => 'title',
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__( "Content", "cvca" ),
                    "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
                    "value" => '',
                    "description" => esc_html__( "Enter your content.", "cvca" )
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
add_action( 'vc_before_init', 'cvca_integrate_clever_feature_box_shortcode_with_vc', 10, 0 );
