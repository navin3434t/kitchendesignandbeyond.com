<?php
/**
 * Contact Shortcode
 */

wp_enqueue_style('cvca-style');
$cvca_wrap_class = $atts['el_class'] . ' ' . $atts['style'];
$cvca_contact = vc_param_group_parse_atts($atts['contact']);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverContact', $atts );
$css_class.=' '.$cvca_wrap_class;
?>
<div class="cvca-shortcode-contact <?php echo esc_attr($css_class) ?>">
    <?php if ($atts['image'] != '' && $atts['title'] != '') { ?>
        <div class="wrap-header-contact">
            <?php
            if (isset($atts['image'])) {
                echo wp_get_attachment_image($atts['image'], 'full');
            }
            if (isset($atts['title'])) {
                ?>
                <h4 class="contact-title">
                    <?php echo esc_html($atts['title']); ?>
                </h4>
                <?php
            }
            ?>
        </div>
    <?php } ?>
    <ul class="wrap-contact-info">
        <?php foreach ($cvca_contact as $cvca_item) {
            ?>
            <li>
                <?php
                if (isset($cvca_item['icon'])) {
                    ?>
                    <i class="<?php echo esc_attr($cvca_item['icon']); ?>"></i>
                    <?php
                }
                if (isset($cvca_item['text_val'])) {
                    ?>
                    <div class="content">
                        <?php echo ent2ncr($cvca_item['text_val']); ?>
                    </div>
                    <?php
                }
                ?>
            </li>
        <?php } ?>
    </ul>
</div>
