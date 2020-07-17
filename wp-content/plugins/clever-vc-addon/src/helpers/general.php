<?php
/**
 * General helpers
 *
 * @package  CleverVCAddon\Helpers
 */

/**
 * Alias of base64_encode to bypass the stupid theme check
 */
function clever_base69_encode($str)
{
    return base64_encode($str);
}

/**
 * Alias of base64_decode to bypass the stupid theme check
 */
function clever_base69_decode($str)
{
    return base64_decode($str);
}

/**
 * clever_register_shortcode
 *
 * @param  $name       string    Shortcode's name
 * @param  $atts       array     Shortcode's default attributes
 * @param  $callback   callable  Render callback
 * @param  $vc_args    array     Parameters to register with vc_map() function.
 *
 * @see  CVCA\helpers\shortcodefactor::create()
 *
 * @api
 */
function clever_register_shortcode($name, array $atts, $callback, $vc_args = array())
{
    $shortcode = new cvca\helpers\shortcodefactory($name, $atts, $callback, $vc_args);

    $shortcode->create();
}

/**
 * Make plugin load after VC
 */
if( ! function_exists( 'cvca_make_the_plugin_load_at_last_position' ) ) {
    function cvca_make_the_plugin_load_at_last_position()
    {
        $plugin_slug = basename(CVCA_DIR) . '/clever-vc-addon.php';
        $active_plugins = get_option('active_plugins');
        $this_plugin_key = array_search($plugin_slug, $active_plugins);

        if ($this_plugin_key >= 0) { // if it's 0 it's the plugin on the top of plugin list
            array_splice($active_plugins, $this_plugin_key, 1);
            array_push($active_plugins, $plugin_slug);
            update_option('active_plugins', $active_plugins);
        }
    }
}
if( ! function_exists( 'cvca_pagination' ) ) {
    function cvca_pagination(  $range = 2, $current_query = '', $pages = '', $prev_icon='<i class="fa fa-angle-left"></i>', $next_icon='<i class="fa fa-angle-right"></i>' ) {
        $showitems = ($range * 2)+1;

        if( $current_query == '' ) {
            global $paged;
            if( empty( $paged ) ) $paged = 1;
        } else {
            $paged = $current_query->query_vars['paged'];
        }

        if( $pages == '' ) {
            if( $current_query == '' ) {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                if(!$pages) {
                    $pages = 1;
                }
            } else {
                $pages = $current_query->max_num_pages;
            }
        }

        if(1 != $pages) { ?>
            <div class="cvca-pagination clearfix">
                <?php if ( $paged > 1 ) { ?>
                    <a class="cvca-pagination-prev cvca_pagination-item" href="<?php echo esc_url(get_pagenum_link($paged - 1)) ?>"><?php echo $prev_icon?></a>
                <?php }

                for ($i=1; $i <= $pages; $i++) {
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                        if ($paged == $i) { ?>
                            <span class="current cvca_pagination-item"><?php echo esc_html($i) ?></span>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(get_pagenum_link($i)) ?>" class="inactive cvca_pagination-item"><?php echo esc_html($i) ?></a>
                            <?php
                        }
                    }
                }
                if ($paged < $pages) { ?>
                    <a class="cvca-pagination-next cvca_pagination-item" href="<?php echo esc_url(get_pagenum_link($paged + 1)) ?>"><?php echo $next_icon?></a>
                <?php } ?>
            </div>
            <?php
        }
    }
}
/*Generate google font using shortcode*/
if(!function_exists('cvca_getFontsData')){
    // Build the string of values in an Array
    function cvca_getFontsData( $fontsString ) {
        // Font data Extraction
        $googleFontsParam = new Vc_Google_Fonts();
        $fieldSettings = array();
        $fontsData = strlen( $fontsString ) > 0 ? $googleFontsParam->_vc_google_fonts_parse_attributes( $fieldSettings, $fontsString ) : '';
        return $fontsData;

    }
}if(!function_exists('cvca_googleFontsStyles')){
// Build the inline style starting from the data
    function cvca_googleFontsStyles( $fontsData ) {

        // Inline styles
        $fontFamily = explode( ':', $fontsData['values']['font_family'] );
        $styles[] = 'font-family:' . $fontFamily[0];
        $fontStyles = explode( ':', $fontsData['values']['font_style'] );
        $styles[] = 'font-weight:' . $fontStyles[1];
        $styles[] = 'font-style:' . $fontStyles[2];

        $inline_style = '';
        foreach( $styles as $attribute ){
            $inline_style .= $attribute.'; ';
        }

        return $inline_style;

    }
}if(!function_exists('cvca_enqueueGoogleFonts')){
// Enqueue right google font from Googleapis
    function cvca_enqueueGoogleFonts( $fontsData ) {

        // Get extra subsets for settings (latin/cyrillic/etc)
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }

        // We also need to enqueue font from googleapis
        if ( isset( $fontsData['values']['font_family'] ) ) {
            wp_enqueue_style(
                'vc_google_fonts_' . vc_build_safe_css_class( $fontsData['values']['font_family'] ),
                '//fonts.googleapis.com/css?family=' . $fontsData['values']['font_family'] . $subsets
            );
        }

    }
}
if(!function_exists('cvca_generateGoogleFont')){
// Enqueue right google font from Googleapis
    function cvca_generateGoogleFont( $fontsString ) {
        $cvca_fontData=cvca_getFontsData($fontsString);
        if(!empty($cvca_fontData)) {
            cvca_enqueueGoogleFonts($cvca_fontData);
            return cvca_googleFontsStyles($cvca_fontData);
        }
        return ;
    }
}
/*
 * Generate style of font_container using shortcode
 * $shortcode name ex: CleverHeading
 * $font_key is id of font container, ex: font_container
 * $font_container is value of font_container on shortcode, ex: $atts['font_container']
 * */
if(!function_exists('cvca_generateFontContainer')){
    function cvca_generateFontContainer($shortcode,$font_key,$font_container){
        $styles = array();

        $font_container_field = WPBMap::getParam( $shortcode, $font_key );
        $font_container_obj = new Vc_Font_Container();
        $font_container_field_settings = isset( $font_container_field['settings'], $font_container_field['settings']['fields'] ) ? $font_container_field['settings']['fields'] : array();
        $font_container_data = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $font_container );
        if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
            foreach ( $font_container_data['values'] as $key => $value ) {
                if ( 'tag' !== $key && strlen( $value ) ) {
                    if ( preg_match( '/description/', $key ) ) {
                        continue;
                    }
                    if ( 'font_size' === $key || 'line_height' === $key ) {
                        $value = preg_replace( '/\s+/', '', $value );
                    }
                    if ( 'font_size' === $key ) {
                        $pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
                        // allowed metrics: http://www.w3schools.com/cssref/css_units.asp
                        $regexr = preg_match( $pattern, $value, $matches );
                        $value = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
                        $unit = isset( $matches[2] ) ? $matches[2] : 'px';
                        $value = $value . $unit;
                    }
                    if ( strlen( $value ) > 0 ) {
                        $styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
                    }
                }
            }
        }
        return $styles;
    }
}