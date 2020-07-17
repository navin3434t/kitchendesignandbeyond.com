<?php
/**
 * Coundown Shortcode
 */
wp_enqueue_style('cvca-style');
wp_enqueue_script('countdown');
//wp_enqueue_script('cvca-script');

$clever_time = '';

if ($atts['date'] != '') {
    $clever_time = date("m-d-Y", strtotime($atts['date'])) . '-' . $atts['hour'] . '-' . $atts['sec'] . '-' . $atts['min'];
}
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverCountDown', $atts);
$css_class .= ' ' . $atts['el_class'];
?>
<div class="cvca-countdown <?php echo esc_attr($css_class) ?>">
    <div class="countdown-block" data-countdown="countdown" data-date="<?php echo esc_attr($clever_time) ?>">
    </div>
    <?php
    $clever_link = vc_build_link($atts['link']);
    if ($clever_link['url'] != '') {
        ?><a href="<?php echo esc_url($clever_link['url']) ?>" class="btn"
             title="<?php echo esc_attr($clever_link['title']) ?>"
             <?php if($clever_link['target']!=''){?>target="<?php echo esc_attr($clever_link['target']) ?>"<?php } if($clever_link['rel']!=''){?> rel="<?php echo esc_attr($clever_link['rel']) ?>"<?php }?>>
        <?php echo esc_html($clever_link['title']) ?></a>
    <?php }
    ?>
</div>