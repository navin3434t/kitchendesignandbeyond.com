<?php
/**
 * Custom functions for pass Team reviewer
 */
// Author Link Social
if (!function_exists('zoo_frame_social_author')) {
    function zoo_frame_social_author($contactmethods)
    {
        $contactmethods['twitter'] = esc_html__('Twitter Username', 'zoo-framework');
        $contactmethods['facebook'] = esc_html__('Facebook Username', 'zoo-framework');
        $contactmethods['google'] = esc_html__('Google Plus Username', 'zoo-framework');
        $contactmethods['tumblr'] = esc_html__('Tumblr Username', 'zoo-framework');
        $contactmethods['instagram'] = esc_html__('Instagram Username', 'zoo-framework');
        $contactmethods['pinterest'] = esc_html__('Pinterest Username', 'zoo-framework');

        return $contactmethods;
    }
}

add_filter('user_contactmethods', 'zoo_frame_social_author', 10, 1);