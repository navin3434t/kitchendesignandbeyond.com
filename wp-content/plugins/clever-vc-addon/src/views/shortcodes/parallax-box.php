<?php
/**
 * Parallax Box Shortcode
 */
$clever_img_id = $atts['img_bg'];
$clever_img = wp_get_attachment_image_src($clever_img_id, 'full');
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverParallaxBox', $atts);
if ($clever_img) :
    wp_enqueue_script('parally');
    wp_enqueue_script('cvca-script');
    ?>
    <div id="cvca-parallax-<?php echo esc_attr(uniqid()); ?>"
         class="cvca-parallax-box <?php echo esc_attr($atts['layout'] . ($atts['add_nav'] == 1 ? ' in-nav' : '') . ' ' . $css_class); ?>" <?php if ($atts['add_nav'] == 1) { ?> data-title="<?php echo esc_attr($atts['title_nav']) ?>"<?php } ?>
         data-image-src="<?php echo esc_url($clever_img[0]) ?>" data-offset="<?php echo esc_attr($atts['offset']) ?>">
        <?php
        if (isset($atts['container'])){
        if ($atts['container'] != ''){
        ?>
        <div class="container">
            <?php
            }
            }
            echo do_shortcode($content);
            if (isset($atts['container'])){
            if ($atts['container'] != ''){
            ?>
        </div>
    <?php
    }
    } ?>
    </div>
<?php endif;
