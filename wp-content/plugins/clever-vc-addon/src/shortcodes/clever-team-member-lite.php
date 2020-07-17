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
function cvca_add_clever_team_member_lite_shortcode( $atts, $content = null )
{
    $atts = shortcode_atts(
        apply_filters('CleverTeamMemberLite_shortcode_atts',array(
            'avatar'          => '',
            'member_name'     => '',
            'member_position' => '',
            'member_des'      => '',
            'facebook'        => '#',
            'dribbble'        => '',
            'twitter'         => '#',
            'messenger'       => '',
            'google_plus'     => '#',
            'skype'           => '',
            'instagram'       => '',
            'github'          => '',
            'flickr'          => '',
            'youtube'         => '',
            'vimeo'           => '',
            'tumblr'          => '',
            'el_class'        => '',
            'css'        => '',
        )),
        $atts, 'CleverTeamMemberLite'
    );

    $html = cvca_get_shortcode_view( 'team-member-lite', $atts, $content );

    return $html;
}
add_shortcode( 'CleverTeamMemberLite', 'cvca_add_clever_team_member_lite_shortcode' );

/**
 * Integrate to Visual Composer
 *
 * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
 */
function cvca_integrate_clever_team_member_lite_shortcode_with_vc()
{
    vc_map(
        array(
            'name' => esc_html__('Clever Team Member Lite', 'cvca'),
            'base' => 'CleverTeamMemberLite',
            'icon' => '',
            'category' => esc_html__('CleverSoft', 'cvca'),
            'description' => esc_html__('Display single team member block', 'cvca'),
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__('Avatar', 'cvca'),
                    'value' => '',
                    'param_name' => 'avatar',
                    "admin_label" => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Member Name', 'cvca'),
                    'value' => '',
                    'param_name' => 'member_name',
                    "admin_label" => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Member Position', 'cvca'),
                    'value' => '',
                    'param_name' => 'member_position',
                    "admin_label" => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => esc_html__('Member Description', 'cvca'),
                    'value' => '',
                    'param_name' => 'member_des',
                    "admin_label" => true,
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Facebook', 'cvca'),
                    'value'      => '#',
                    'description' => esc_html__('Your facebook page/profile url', 'cvca'),
                    'param_name' => 'facebook',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Dribbble', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Dribbble username', 'cvca'),
                    'param_name' => 'dribbble',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Twitter', 'cvca'),
                    'value'      => '#',
                    'description' => esc_html__('Your Twitter username (no @).', 'cvca'),
                    'param_name' => 'twitter',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Messenger', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your messenger.', 'cvca'),
                    'param_name' => 'messenger',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Google Plus', 'cvca'),
                    'value'      => '#',
                    'description' => esc_html__('Your Google+ page/profile URL', 'cvca'),
                    'param_name' => 'google_plus',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Skype', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Skype username', 'cvca'),
                    'param_name' => 'skype',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Instagram', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Instagram username', 'cvca'),
                    'param_name' => 'instagram',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('GitHub', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your GitHub URL', 'cvca'),
                    'param_name' => 'github',
                ),
                 array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Flickr', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Flickr page url', 'cvca'),
                    'param_name' => 'flickr',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Youtube', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your YouTube URL', 'cvca'),
                    'param_name' => 'youtube',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Vimeo', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Vimeo username', 'cvca'),
                    'param_name' => 'vimeo',
                ),
                array(
                    'type'       => 'textfield',
                    'heading'    => esc_html__('Tumblr', 'cvca'),
                    'value'      => '',
                    'description' => esc_html__('Your Tumblr username', 'cvca'),
                    'param_name' => 'tumblr',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra class name', 'cvca' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca' )
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'Css', 'cvca' ),
                    'param_name' => 'css',
                    'group' => __( 'Design options', 'cvca' ),
                ),
            )
        )
    );
}
add_action( 'vc_before_init', 'cvca_integrate_clever_team_member_lite_shortcode_with_vc', 10, 0 );
