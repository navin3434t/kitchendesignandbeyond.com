<?php
/**
 * Shortcode Funtionality
 *
 * @package  Zoo_Theme\Core\Common\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
/**
 * Strip all WPbakery Page Builder shortcodes
 *
 * @param  string  $content
 *
 * @return  string
 */
function zoo_strip_wpbakery_page_builder_shortcodes($content)
{
    static $regex = null;

    if (null === $regex) {
        $regex = '/'.get_shortcode_regex(['vc_row', 'vc_column']).'/';
    }

    $content = do_shortcodes_in_html_tags(wp_unslash($content), true, ['vc_row', 'vc_column']);
    $content = preg_replace_callback($regex, 'strip_shortcode_tag', $content);

    return $content;
}

/**
 * Strip all shortcodes
 *
 * @param  string  $content
 *
 * @return  string
 */
function zoo_strip_all_shortcodes($content)
{
    static $regex = null;

    if (null === $regex) {
        $regex = '/'.get_shortcode_regex(['vc_row', 'vc_column']).'/';
    }

    $content  = strip_shortcodes(wp_unslash($content));
    $content  = do_shortcodes_in_html_tags($content, true, ['vc_row', 'vc_column']);
    $content  = preg_replace_callback($regex, 'strip_shortcode_tag', $content);

    return $content;
}
