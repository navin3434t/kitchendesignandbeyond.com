<?php
$wrapID = 'cvca-sc-countup-' . uniqid();
$countID= 'number-count' . uniqid();
$jsconfig='{"wrapid":"'.$wrapID.'","countid":"'.$countID.'","start_number":"'.$atts['start_number'].'","end_number":"'.$atts['end_number'].'","decimals":"'.$atts['decimals'].'","duration":"'.$atts['duration'].'"}';
wp_enqueue_style( 'cvca-style' );
wp_enqueue_script( 'countup' );
wp_enqueue_script( 'cvca-script' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverCountUp', $atts );
?>
<div id="<?php echo esc_attr($wrapID)?>" class="cvca-countup <?php echo esc_attr($atts['el_class'].' '.$css_class);?>"
data-config="<?php echo esc_attr($jsconfig)?>">
    <h2 id="<?php echo esc_attr($countID)?>" class="number-count" style="color:<?php echo esc_attr($atts['number_color'])?>;font-size:<?php echo esc_attr($atts['number_size'])?>px">
        <?php echo esc_html($atts['end_number'])?></h2>
    <?php if(isset($atts['title'])){?>
    <p class="text-count" style="color:<?php echo esc_attr($atts['text_color'])?>;font-size:<?php echo esc_attr($atts['title_size'])?>px"><?php echo esc_html($atts['title'])?></p>
    <?php }?>
</div>

