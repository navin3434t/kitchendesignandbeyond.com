<?php
/**
 * Instagram Shortcode
 */

wp_enqueue_style('slick');
wp_enqueue_style('cvca-style');
wp_enqueue_script('slick');
wp_enqueue_script('cvca-script');

$css_class = $target = '';

$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverInstagram', $atts );

if ( !empty( $atts['el_class'] ) ) {
    $css_class .= ' ' . $atts['el_class'];
}

if ( !empty( $custom_class ) ) {
    $css_class .= ' ' . $custom_class;
}

if ( !empty( $atts['link_target'] ) && $atts['link_target'] == '_blank' ) {
    $target = 'target="_blank"';
}

$limit = !empty( $atts['number'] ) ? $atts['number'] : 9;
$img_size = !empty( $atts['img_size'] ) ? $atts['img_size'] : 'small';

$slick_data_html = '';
$center_padding = '60px';

$columns = ( !empty( $atts['columns'] ) ) ? $atts['columns'] : 6;
$center_mode = ( $atts['center_mode'] == '1' ) ? true : false;
$show_pag = ( $atts['show_pag'] == '1' ) ? true : false;
$show_nav = ( $atts['show_nav'] == '1' ) ? true : false;

// $slick_data['responsive'] = '[{"breakpoint": 768,"settings":{"slidesToShow": 1}}]';

$slick_data_html = '{"item":"' . $columns . '"';
$slick_data_html .= ',"pagination":"' . $show_pag . '","navigation":"' . $show_nav . '"';

if ( $atts['center_mode'] == '1' ) {
    $css_class .= ' carousel-center';
    if ( !empty($atts['center_padding']) ) {
        $center_padding = $atts['center_padding'];
    }
    $slick_data_html .= ',"center_mode":"' . $atts['center_mode'] . '","center_padding":"' . $center_padding . '"';
}

$slick_data_html .= '}';
?>

<?php if ( !empty( $atts['username'] ) ) : ?>
    <?php $media_array = zoo_scrape_instagram( $atts['username'] ); ?>

    <?php if ( is_wp_error( $media_array ) ) : ?>

        <?php echo wp_kses_post( $media_array->get_error_message() ); ?>

    <?php else : ?>
        <?php
        if ( $images_only = apply_filters( 'zoo_instagram_images_only', FALSE ) ) {
            $media_array = array_filter( $media_array, 'zoo_images_only' );
        }

        // slice list down to required limit
        $media_array = array_slice( $media_array, 0, $limit );
        ?>
        <div class="cvca-shortcode-instagram zoo-instagram-feed<?php echo esc_attr( $css_class ); ?>">
            <div class="zoo-instagram-photos cvca-carousel-block" data-config='<?php echo esc_attr($slick_data_html); ?>'>
                <?php foreach ( $media_array as $item ) : ?>
                    <div class="instagram-item">
                        <div class="instagram-item-inner">
                            <?php
                                $type =  $item['type']; // image, video
                                $comments = zoo_abbreviate_total_count( $item['comments'], 10000 );
                                $likes = zoo_abbreviate_total_count( $item['likes'], 10000 );
                                $time =  zoo_time_elapsed_string( '@' . $item['time'] );

                                $gmt = get_option('gmt_offset');;
                                $time_zone = get_option( 'timezone_string' );
                                if ( !empty( $atts['date_format'] ) ) {
                                    $date_format = $atts['date_format'];
                                } else {
                                    $date_format = get_option( 'date_format' );
                                }
                            ?>
                            <a href="<?php echo esc_url( $item['link'] ); ?>"<?php echo $target; ?>>
                                <?php if ( !empty( $atts['show_type'] ) && $atts['show_type'] == '1' ) : ?>
                                    <?php if ( $type == 'video' ) : ?>
                                        <span class="type type-video"><i class="cs-font clever-icon-triangle"></i></span>
                                    <?php else : ?>
                                        <span class="type type-image"><i class="cs-font clever-icon-compare-6"></i></span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <span class="group-items">
                                    <?php if ( !empty( $atts['show_likes'] ) && $atts['show_likes'] == '1' ) : ?>
                                        <span class="likes"><i class="cs-font clever-icon-heart-o"></i><?php echo $likes; ?></span>
                                    <?php endif; ?>

                                    <?php if ( !empty( $atts['show_comments'] ) && $atts['show_comments'] == '1' ) : ?>
                                        <span class="comments"><i class="cs-font clever-icon-consulting-message"></i><?php echo $comments; ?></span>
                                    <?php endif; ?>
                                </span>

                                <?php if ( !empty( $atts['show_time'] ) && $atts['show_time'] == '1' ) : ?>
                                    <?php if ( !empty( $atts['time_layout'] ) && $atts['time_layout'] == 'elapsed' ) : ?>
                                        <span class="time elapsed-time"><?php echo $time; ?></span>
                                    <?php endif; ?>

                                    <?php if ( !empty( $atts['time_layout'] ) && $atts['time_layout'] == 'date' ) : ?>
                                        <span class="time date-time"><?php echo date_i18n( $date_format, $item['time'], $gmt ); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <img src="<?php echo esc_url( $item[$img_size] ); ?>"  alt="<?php echo esc_attr( $item['description'] ); ?>"  class="instagram-photo"/>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
