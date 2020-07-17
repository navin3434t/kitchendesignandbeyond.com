<?php
/**
 * Auto typing shortcode view
 *
 * @package  CleverVCAddon\Views
 */
$wrapID = 'cvca-sc-autotyping-' . uniqid();
$texts = vc_param_group_parse_atts($atts['text']);
$list = array();

foreach ($texts as $text) {
    if(isset($text['text-item']) && $text['text-item']!='') {
        $list[] .= $text['text-item'];
    }
}
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverAutoTyping', $atts );
$css_class.=' '.$atts['el_class'];
wp_enqueue_style('cvca-style');
wp_enqueue_script('typed');
wp_enqueue_script('cvca-script');

?><div id="<?php echo esc_attr($wrapID); ?>" class="cvca-auto-typing <?php echo esc_attr($css_class) ?>" data-text='<?php echo esc_attr(json_encode($list))?>' data-speed="<?php echo esc_attr($atts['typeSpeed'])?>" data-delay="<?php echo esc_attr($atts['delay_time']) ?>" data-cursor="<?php echo esc_attr($atts['show_cursor']) ?>" style="font-size:<?php echo esc_attr($atts['font-size']) ?>px;text-transform:<?php echo esc_attr($atts['text-transform']) ?>;color:<?php echo esc_attr($atts['text_color']) ?>;"><?php
    if ($atts['fixed-text'] !== '') :
        ?><span style="color:<?php echo esc_attr($atts['fixed_text_color']) ?>;"><?php echo esc_html($atts['fixed-text'])?></span><?php
    endif;
    ?><span class="content-auto-typing"></span>
</div>
