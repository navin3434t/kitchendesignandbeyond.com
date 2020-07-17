<?php
/**
 * Template for navigation icon element.
 *
 * Template of `core/customize/builder/elements/nav-icon.php`
 */

$classes = array('off-canvas-toggle');
$label_classes = array('nav-icon-label');
if (is_array($atts['show_label'])) {
    foreach ($atts['show_label'] as $d => $v) {
        if ($v) {
        } else {
            $label_classes[] = 'hide-on-' . $d;
        }
    }
}
if ($atts['style']) {
    $classes[] = $atts['style'];
}

if (!empty($atts['align'])) {
    $classes[] = 'element-align-'.esc_attr($atts['align']);
}if (!empty($atts['icon_style'])) {
    $classes[] = 'icon-style-'.esc_attr($atts['icon_style']);
}

?>
<a href="#" <?php $this->element_class($classes);?> title="<?php echo esc_attr($atts['label'])?>">
    <i class="zoo-css-icon-menu"></i>
    <?php
    if ($atts['show_label']) {
        ?>
        <span class="<?php echo esc_attr(join(' ', $label_classes))?>"><?php echo esc_html($atts['label'])?></span>
    <?php
    } ?>
</a>
