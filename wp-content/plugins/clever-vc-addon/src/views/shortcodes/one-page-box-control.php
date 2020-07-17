<?php
/**
 * One Page Box Control, Controller of one page shortcode
 */
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverOnePageBoxControl', $atts);
$icon = $atts['title_icon'];
wp_enqueue_script('cvca-script');
?>
<div id="cvca-one-page-control-<?php echo esc_attr(uniqid()); ?>"
     class="cvca-one-page-control <?php echo esc_attr($css_class); ?>">
    <?php if ($atts['title'] != '') { ?>
        <h3 class="cvca-title-one-page-control"
            style="background:<?php echo esc_attr($atts['title_bg']) ?>;color:<?php echo esc_attr($atts['title_color']) ?>">
            <?php if ($icon != '') { ?>
                <i class="<?php echo esc_attr($icon); ?>"></i>
            <?php }
            echo esc_html($atts['title']); ?>
        </h3>
    <?php } ?>
    <ul class="cvca-wrap-control cvca-one-page-control-menu" style="color:<?php echo esc_attr($atts['item_color']) ?>"></ul>
</div>
