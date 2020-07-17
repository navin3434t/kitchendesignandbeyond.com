<?php

/**
 * One page box.
 *
 * @description: Using for building one page layout. Allow user access block by static menu.
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 *
 * @param    array    $atts    Users' defined attributes in shortcode.
 *
 * @return    string    $html    Rendered shortcode content.
 */
function cvca_add_clever_one_page_box_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverOnePageBox_shortcode_atts',array(
			'title'   =>'',
			'show_title'   =>'1',
			'full_height'   =>'1',
			'jump'   =>'1',
			'show_icon'   =>'1',
            'icon_type'         => 'fontawesome',
            'icon_fontawesome'  => '',
            'icon_openiconic'   => '',
            'icon_typicons'     => '',
            'icon_entypo'       => '',
            'icon_linecons'     => '',
            'icon_monosocial'   => '',
            'icon_material'     => '',
            'icon_cleverfont'   => '',
            'icon_strokegap'    => '',
			'preset_color'    =>'',
            'css'=>''
        )),
        $atts, 'CleverParallaxBox'
    );

    $html = cvca_get_shortcode_view( 'one-page-box', $atts, $content );

    return $html;
}
add_shortcode( 'CleverOnePageBox', 'cvca_add_clever_one_page_box_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_one_page_box_shortcode_with_vc()
{
	vc_map(
		array(
			'name' => esc_html__('Clever One Page Box', 'cvca'),
			'base' => 'CleverOnePageBox',
			'icon' => '',
			'is_container' => true,
			'js_view'      => 'VcColumnView',
			'category' => esc_html__('CleverSoft', 'cvca'),
			'description' => esc_html__('Using for building one page layout. Allow user access block by static menu.', 'cvca'),
			'params' => array(
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title", 'cvca'),
                    "param_name" => "title",
                    "admin_label" => true,
                    "description" => esc_html__("Title display at navigation.", 'cvca'),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Full Height", 'cvca'),
                    'param_name' => 'full_height',
                    'description' => esc_html__('If check block will full height, content will align middle.', 'cvca'),
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Jump when scroll", 'cvca'),
                    'param_name' => 'jump',
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Show title", 'cvca'),
                    'param_name' => 'show_title',
                    'description' => esc_html__('If check title of block will show like heading of block.', 'cvca'),
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
                ),                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__("Show Icon", 'cvca'),
                    'param_name' => 'show_icon',
                    'description' => esc_html__('If check icon of block will show like heading of block.', 'cvca'),
                    'std' => '1',
                    'value' => array(esc_html__('Yes', 'cvca') => '1'),
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
                    'std' => 'fontawesome',
                    'param_name' => 'icon_type',
                    'description' => esc_html__( 'Select icon library.', 'cvca' ),
                ),

                array(
                    'type' => 'iconpicker',
                    'heading' => esc_html__( 'Icon', 'cvca' ),
                    'param_name' => 'icon_fontawesome',
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'value' => '',
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
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Preset color', 'cvca'),
                    'value' => '',
                    'param_name' => 'preset_color',
                    'description' => esc_html__('This color will use is background of static navigation entry, and background of title block', 'cvca'),
                ),
                array(
                    'type'       => 'css_editor',
                    'counterup'  => esc_html__( 'Css', 'cvca' ),
                    'param_name' => 'css',
                    'group'      => esc_html__( 'Design options', 'cvca' ),
                ),
			)
		)
	);
}
add_action( 'vc_before_init', 'cvca_integrate_clever_one_page_box_shortcode_with_vc', 10, 0 );
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_CleverOnePageBox extends WPBakeryShortCodesContainer {
    }
}