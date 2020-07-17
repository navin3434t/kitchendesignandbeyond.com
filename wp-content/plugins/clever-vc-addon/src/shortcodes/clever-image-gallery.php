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
function cvca_add_clever_image_gallery_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverImageGallery_shortcode_atts',array(
            'title'     => '',
            'show_pag' => '',
            'show_nav' => '',
            'center_mod' => '',
            'auto_play' => '',
            'shadow' => '',
            'columns'   => '3',
            'layout'   => 'horizontal',
            'images'    => '',
            'el_class'  => ''
        )),
        $atts, 'CleverImageGallery'
    );

    $html = cvca_get_shortcode_view( 'image-gallery', $atts, $content );

    return $html;
}
add_shortcode( 'CleverImageGallery', 'cvca_add_clever_image_gallery_shortcode' );


/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_image_gallery_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Image Gallery', 'cvca'),
            'base' => 'CleverImageGallery',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Show Image Gallery', 'cvca'),
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    'param_name' => 'title',
                    "admin_label" => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Columns', 'cvca'),
                    'description' => esc_html__('Number columns of layout', 'cvca'),
                    'std' => '3',
                    'param_name' => 'columns',
                    "admin_label" => true,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Layout', 'cvca'),
                    'std' => 'horizontal',
                    'param_name' => 'layout',
                    "admin_label" => true,
                    "value" => array(
                        esc_html__('Horizontal', 'cvca' ) => 'horizontal',
                        esc_html__('Vertical', 'cvca' ) => 'vertical',
                    ),
                ),
                array(
                    "type" => "param_group",
                    "heading" => esc_html__("Images", 'cvca'),
                    'value' => '',
                    'param_name' => 'images',
                    'description' => esc_html__('Click to show more options, and starting add content.', 'cvca'),
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__('Image', 'cvca'),
                            'value' => '',
                            'param_name' => 'image',
                        ),
                        array(
                            'type' => 'vc_link',
                            'heading' => esc_html__( 'Link', 'cvca' ),
                            'param_name' => 'link',
                            'description' => esc_html__( 'Link of Image', 'cvca' )
                        ),
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show pagination', 'cvca'),
                    'param_name' => 'show_pag',
                    'description' => esc_html__('If check, pagination of gallery will show', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Show navigation', 'cvca'),
                    'param_name' => 'show_nav',
                    'description' => esc_html__('If check, navigation of gallery will show', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Box shadow', 'cvca'),
                    'param_name' => 'shadow',
                    'description' => esc_html__('Shadow for gallery item', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Center Mode', 'cvca'),
                    'param_name' => 'center_mod',
                    'description' => esc_html__('Gallery will align center', 'cvca'),
                    'value'=>true,
                    'std'=>''
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Auto play', 'cvca'),
                    'value' => '',
                    'param_name' => 'auto_play',
                    'description' => esc_html__('Leave it blank if want disable auto play', 'cvca'),
                    "admin_label" => true,
                ),array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'cvca'),
                    'param_name' => 'el_class',
                    'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca')
                )
                )
            )
        );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_image_gallery_shortcode_with_vc', 10, 0 );
