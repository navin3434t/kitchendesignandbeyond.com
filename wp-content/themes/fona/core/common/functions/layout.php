<?php
/**
 * Layout Functionality
 *
 * @package  Zoo_Theme\Core\Common\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Get registered sidebar id=>name
 *
 * @return  array
 */
function zoo_get_registered_sidebars()
{
    global $wp_registered_sidebars;

    $sidebar_options = [];
    $sidebar_options['none'] = esc_html__('None', 'fona');

    foreach ($wp_registered_sidebars as $sidebar) {
        $sidebar_options[$sidebar['id']] = $sidebar['name'];
    }

    return $sidebar_options;
}