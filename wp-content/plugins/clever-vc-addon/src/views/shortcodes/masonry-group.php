<?php
/**
 * Masonry Shortcode
 */
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverMasonryGroup', $atts);
    wp_enqueue_script('isotope');
    wp_enqueue_script('cvca-script');
    ?>
    <div id="cvca-masonry-group-<?php echo esc_attr(uniqid()); ?>"
         class="cvca-masonry-group <?php echo esc_attr($css_class); ?>" <?php if($atts['gutter']!=0){?>style="margin:0 -<?php echo esc_attr(($atts['gutter'])/2);?>px"<?php }?> data-horizontalOrder="<?php echo esc_attr($atts['horizontal_order']) ?>" data-gutter="<?php echo esc_attr($atts['gutter']) ?>">
        <?php echo do_shortcode($content); ?>
    </div>
