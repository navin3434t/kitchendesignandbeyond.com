<?php
/**
 * User related helpers
 */

/**
 * Get teacher data for VC shortcode
 */
function clever_get_teacher_data_for_vc()
{
	$data = array();

	$teachers = get_users(array(
		'role'    => 'teacher',
		'orderby' => 'nicename',
		'fields'  => array('user_nicename', 'display_name')
	));

    if (!empty($teachers)) {
        foreach ($teachers as $teacher) {
            $teacher_data = array(
                'label' => $teacher->display_name,
                'value' => $teacher->user_nicename
            );
            $data[] = $teacher_data;
        }
    }

   return $data;
}
