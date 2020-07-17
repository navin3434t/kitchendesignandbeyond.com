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
function cvca_add_clever_links_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverLinks_shortcode_atts',array(
            'title'     => '',
            'links' => '',
            'el_class'  => '',
        )),
        $atts, 'CleverLinks'
    );

    $html = cvca_get_shortcode_view( 'links', $atts, $content );

    return $html;
}
add_shortcode( 'CleverLinks', 'cvca_add_clever_links_shortcode' );
/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_links_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Links', 'cvca'),
            'base' => 'CleverLinks',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => '',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'cvca'),
                    'value' => '',
                    'param_name' => 'title',
                    "admin_label" => true,
                ),
                array(
                    "type" => "param_group",
                    "heading" => esc_html__("Follow Me block", 'cvca'),
                    'value' => '',
                    'param_name' => 'links',
                    'description' => esc_html__('Icons and links block, click to starting add', 'cvca'),
                    // Note params is mapped inside param-group:
                    'params' => array(
                        array(
                            'type' => 'iconpicker',
                            'value' => '',
                            'heading' => esc_html__('Socail icon', 'cvca'),
                            'param_name' => 'socail-icon',
                            'edit_field_class'=>'vc_col-xs-6',
                        ),
                        array(
                            'type' => 'vc_link',
                            'value' => '',
                            'heading' => esc_html__('Link', 'cvca'),
                            'param_name' => 'socail-link',
                            'edit_field_class'=>'vc_col-xs-6',
                        ),
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'edit_field_class'=>'vc_col-xs-6',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                )
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_links_shortcode_with_vc', 10, 0 );
