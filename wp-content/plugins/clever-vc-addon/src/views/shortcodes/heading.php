<?php
/**
 * Clever Heading shortcode view
 *
 * @package  CleverVCAddon\Views
 */
$settings = get_option( 'wpb_js_google_fonts_subsets' );
$cvca_google_font=$style=$style_underline=$css_class='';
if($atts['use_theme_fonts']!='yes'){
    $cvca_google_font = $atts['font'];
    $cvca_google_font = cvca_generateGoogleFont($cvca_google_font);
    $style = $cvca_google_font;
}
$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverHeading', $atts );
if ( !empty( $atts['el_class'] ) ) {
    $css_class .= ' ' . $atts['el_class'];
}
$font_container= cvca_generateFontContainer('CleverHeading','font_container',$atts['font_container']);
if ( ! empty( $font_container ) ) {
    $style .= implode( ';', $font_container );
}
if($atts['letter_spacing']!=''){
    $style.='; letter-spacing:'.$atts['letter_spacing'];
}
if($atts['text_underline']!=''){
    $style_underline='border-bottom: '.$atts['underline_height'].' solid '.$atts['underline_color'].';';
    if($atts['underline_width']!=''){
        $style_underline.='width:'.$atts['underline_width'];
    }
}
if($atts['title'] !='') {
    ?>
    <div class="cvca-clever-heading <?php echo esc_attr($custom_class) ?>" style="<?php echo esc_attr($style); ?>">
        <span <?php if($style_underline!=''){echo 'style="padding-bottom:'.$atts['underline_height'].'"';}?>><?php echo esc_html($atts['title']);
        if($style_underline!=''){
            ?>
            <i style="<?php echo esc_attr($style_underline); ?>"></i>
            <?php
        }
        ?>
            </span>
    </div>
    <?php
}
