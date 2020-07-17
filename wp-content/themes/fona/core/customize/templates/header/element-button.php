<?php
/**
 * Template for button element.
 *
 * Template of `core/customize/builder/elements/logo.php`
 */
$classes = array('button');

$icon = wp_parse_args($atts['icon'], array(
    'type' => '',
    'icon' => ''
));

$target = '';

if ($atts['target'] == 1) {
    $target = ' target="_blank" ';
}

if (!empty($atts['align'])) {
    $classes[] = 'element-align-'.esc_attr($atts['align']);
}

$icon_html = '';
$allow_html = array('i' => array('class' => array()));
if ($icon['icon']) {
    $icon_html = '<i class="' . esc_attr($icon['icon']) . '"></i> ';
    $classes[] = 'is-icon-' . $atts['icon_position'];
}
$text = !$atts['text'] ? esc_html__('Button', 'fona') : $atts['text'];
?>
<a href="<?php echo esc_url($atts['link']) ?>"  <?php $this->element_class($classes);?> title="<?php echo esc_attr($text) ?>" <?php echo esc_attr($target); ?>>
    <?php
    if ($atts['icon_position'] != 'after') {
        echo wp_kses($icon_html, $allow_html) . esc_html($text);
    } else {
        echo esc_html($text) . wp_kses($icon_html, $allow_html);
    }
    ?>
</a>
