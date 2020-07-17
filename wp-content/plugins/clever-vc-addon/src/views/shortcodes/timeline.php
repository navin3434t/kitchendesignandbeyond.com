<?php
/**
 * Timeline Shortcode
 */
wp_enqueue_style('cvca-style');
$timelines = vc_param_group_parse_atts($atts['timelines']);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverTimeline', $atts);
$css_class .= ' ' . $atts['el_class'];
?>
<div class="cvca-timeline-shortcode <?php echo esc_attr($css_class); ?>">
<?php if ($atts['title'] != '') { ?>
    <h5 class="title-block"><?php echo esc_html($atts['title']) ?></h5>
<?php }
if (count($timelines) > 0) {
    ?>
    <div class="wrap-timelines">
        <?php foreach ($timelines as $timeline) {
            if (count($timeline) > 0) {
                ?>
                <div class="timeline-item">
                    <?php if (isset($timeline['date-block'])) { ?>
                        <div class="date-block">
                            <h5 class="no-margin"><?php echo esc_html($timeline['date-block']) ?></h5>
                        </div>
                    <?php } ?>
                    <div class="timeline-content">
                        <?php if (isset($timeline['title-block'])) { ?>
                            <h5 class="title"><?php echo esc_html($timeline['title-block']) ?></h5>
                        <?php }
                        if (isset($timeline['des-block'])) { ?>
                            <div class="description">
                                <?php echo esc_html($timeline['des-block']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
<?php } ?>
    </div><?php // End view
