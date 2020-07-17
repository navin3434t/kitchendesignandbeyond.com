<?php
/**
 * Formatting Functions
 *
 * @package  Zoo_Theme\Core\Common\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Normalize checked checkboxes' value
 *
 * @param int|string  $value
 *
 * @return  int
 */
function zoo_sanitize_checkbox_value($value)
{
    return ($value == 1 || $value === 'on') ? 1 : 0;
}

/**
 * Sanitize CSS input
 *
 * @param  string  $css  Input CSS value.
 *
 * @return  string
 */
function zoo_sanitize_css_input($css)
{
    return wp_strip_all_tags($css);
}

/**
 * Sanitize color input
 *
 * @param  string  $color  Input color value.
 *
 * @return  string
 */
function zoo_sanitize_color_input($color)
{
    if (empty($color) || !is_string($color))
        return '';

    if (false === strpos($color, 'rgba')) {
        return sanitize_hex_color($color);
    } else {
        $color = str_replace(' ', '', $color);
        sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
        return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
    }
}

/**
 * Recursively sanitize text input
 *
 * @param  string  $text
 * @param  bool    $html  Is there any HTML in the formatting text string.
 *
 * @return  string
 */
function zoo_sanitize_text_input_recursive($text)
{
    if (is_array($text)) {
        foreach ($text as $k => $v) {
            $text[$k] = zoo_sanitize_text_input_recursive($v);
        }
    } elseif (is_string($text)) {
        if (false !== strpos($text, '<svg')) {
            return $text; // Risky
        } else {
            $text = wp_kses_post($text);
        }
    }

    return $text;
}
