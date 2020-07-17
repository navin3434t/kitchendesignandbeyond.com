<?php
/**
 * Template for Html element.
 *
 * Template of `core/customize/builder/elements/html.php`
 */

$el_class_att = ['builder-block-html'];

if (!empty($align)) {
    $el_class_att[] = 'element-align-'.esc_attr($align);
}

?>
<div <?php $this->element_class($el_class_att);?>>
    <?php echo apply_filters('zoo_the_content', do_shortcode($atts)); ?>
</div>
