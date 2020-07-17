<?php
/**
 * Meta data for custom post type
 *
 * @package     Clever VC Addon
 * @version     1.0.2
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 * @license     GPL v2
 */
add_filter('rwmb_meta_boxes', 'cvca_add_meta_box_options');
function cvca_add_meta_box_options()
{
    $prefix = "cvca_";
    $meta_boxes = array();
    $meta_boxes[] = array(
        'id' => 'post_meta_box',
        'title' => esc_html__('Post Meta', 'cvca'),
        'pages' => array('testimonial'),
        'context' => 'normal',
        'fields' => array(
            array(
                'name' => esc_html__('Author avatar', 'cvca'),
                'desc' => esc_html__('Author avatar display in frontend', 'cvca'),
                'id' => $prefix."author_img",
                'type' => 'image_advanced',
                'max_file_uploads' => 1
            ),
            array(
                'name' => esc_html__('Author name', 'cvca'),
                'desc' => esc_html__('Author name display in frontend', 'cvca'),
                'id' => $prefix."author",
                'type' => 'text',
            ),
            array(
                'name' => esc_html__('Author description', 'cvca'),
                'desc' => esc_html__('Author description display in frontend', 'cvca'),
                'id' => $prefix."author_des",
                'type' => 'text',
            ),
        ));
    $meta_boxes[] = array(
        'id' => 'zoo_tm_meta_box',
        'title' => esc_html__('Team member information', 'cvca'),
        'pages' => array('team'),
        'context' => 'normal',
        'fields' => array(
            array(
                'name' => esc_html__('Team member position', 'cvca'),
                'desc' => esc_html__('Team member position display in frontend', 'cvca'),
                'id' => $prefix."team_member_pos",
                'type' => 'text',
            ),
            array(
                'name' => esc_html__('Experience', 'cvca'),
                'desc' => esc_html__('Team member experience display in frontend', 'cvca'),
                'id' => $prefix."team_member_exp",
                'type' => 'text',
            ),
            array(
                'name' => esc_html__('Team member description', 'cvca'),
                'desc' => esc_html__('Team member description display in frontend', 'cvca'),
                'id' => $prefix."team_member_des",
                'type' => 'textarea',
            ),
            array(
                'name' => esc_html__('Phone', 'cvca'),
                'desc' => esc_html__('Phone number of team member', 'cvca'),
                'id' => $prefix."team_member_phone",
                'type' => 'tel',
            ),array(
                'name' => esc_html__('Email', 'cvca'),
                'desc' => esc_html__('Email of team member', 'cvca'),
                'id' => $prefix."team_member_email",
                'type' => 'email',
            ),array(
                'name' => esc_html__('Facebook profile', 'cvca'),
                'desc' => esc_html__('Full url profile of team member', 'cvca'),
                'id' => $prefix."team_member_fb",
                'type' => 'url',
            ),array(
                'name' => esc_html__('Twitter profile', 'cvca'),
                'desc' => esc_html__('Full url profile of team member', 'cvca'),
                'id' => $prefix."team_member_tw",
                'type' => 'url',
            ),array(
                'name' => esc_html__('Google plus profile', 'cvca'),
                'desc' => esc_html__('Full url profile of team member', 'cvca'),
                'id' => $prefix."team_member_gp",
                'type' => 'url',
            ),array(
                'name' => esc_html__('LinkedIn profile', 'cvca'),
                'desc' => esc_html__('Full url profile of team member', 'cvca'),
                'id' => $prefix."team_member_li",
                'type' => 'url',
            ),array(
                'name' => esc_html__('Youtube profile', 'cvca'),
                'desc' => esc_html__('Full url profile of team member', 'cvca'),
                'id' => $prefix."team_member_yt",
                'type' => 'url',
            ),
        ));
    $meta_boxes[] = array(
        'id' => 'zoo_tm_project',
        'title' => esc_html__('Project complete', 'cvca'),
        'pages' => array('team'),
        'context' => 'normal',
        'fields' => array(
            array(
                'id'      => $prefix.'tm_project',
                'name'    => esc_html__( 'Project complete', 'cvca'),
                'type'    => 'fieldset_text',
                'clone'=>true,
                'desc'    => esc_html__( 'Please enter following details:', 'cvca' ),
                'options' => array(
                    'name'    => esc_html__( 'Name', 'cvca' ),
                    'date' => esc_html__( 'Date complete', 'cvca' ),
                    'link'   => esc_html__( 'Project url', 'cvca' ),
                ),
            ),
        ));
    return $meta_boxes;
}