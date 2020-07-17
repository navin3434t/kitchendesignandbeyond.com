<?php
/**
 * Video Shortcode
 */
wp_enqueue_style('cvca-style');
$el_class = $css = '';

// Generate map id
$randid = 'cvca-video-' . uniqid();

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverVideo', $atts );


$button_style = '';
if ( !empty($atts['btn_color']) || !empty($atts['btn_bg_color']) ) {
	$button_style .= ' style="';
	
	if ( !empty($atts['btn_color']) ) {
		$button_style .= 'color: ' . $atts['btn_color'] . ';';
	}

	if ( !empty($atts['btn_bg_color']) ) {
		$button_style .= 'background-color: ' . $atts['btn_bg_color'] . ';';
	}
	
	$button_style .= '"';
}

$output = '';

$output .= '<div id="'. esc_attr( $randid ) .'" class="cvca-video '. esc_attr( $atts['el_class'] ) .' '. esc_attr( $css_class ) . '" style="background-image: url('. wp_get_attachment_url( $atts['thumbnail'] ) .');">';

if ( !empty( $atts['overlay_color'] ) ) {
	$output .= '<span class="zoo-addon-overlay"';
	if ( !empty( $atts['overlay_color'] ) ) {
		$output .= ' style="background-color:' . $atts['overlay_color'] . '"';
	}
	$output .= '></span>';
	$output .= '<div class="zoo-overlay-content">';
}

$output .= '<div class="cvca-wrap-video-content">';

if ( !empty( $atts['title'] ) ) {
	$output .= '<h3 class="cvca-video-title">' . wp_kses( $atts['title'], array( 'br' => array() ) ) . '</h3>';
}

if ( !empty( $content ) ) {
    $output .= '<div class="cvca-video-desc">';
    $output .= $content;
    $output .= '</div>';
}

$output .= '<a href="'. esc_url( $atts['source_video'] ) .'"  class="cvca-video-button"' . $button_style . ' data-height="'. esc_attr( $atts['height'] ) .'" data-width="'. esc_attr( $atts['width'] ) .'"><i class="cs-font clever-icon-line-triangle"></i></a>';
$output .= '</div>';

if ( !empty( $atts['overlay_color'] ) ) {
	$output .= '</div>';
}

$output .= '</div>';

echo $output; // End view
wp_enqueue_script('cvca-script');