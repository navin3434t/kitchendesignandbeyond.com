<?php
/**
 * Banner Shortcode
 */

$css_class = '';
$limit = 3;

$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverSenseiCourses', $atts );

if ( !empty( $atts['el_class'] ) ) {
    $css_class .= ' ' . $atts['el_class'];
}

if ( !empty( $custom_class ) ) {
    $css_class .= ' ' . $custom_class;
}

// Events
global $wp_query, $tribe_ecp, $post;

$hold_tribe_bar_args = array();

foreach ( $_REQUEST as $key => $value ) {
    if ( $value && strpos( $key, 'tribe-bar-' ) === 0 ) {
        $hold_tribe_bar_args[ $key ] = $value;
        unset( $_REQUEST[ $key ] );
    }
}

if ( ! function_exists( 'tribe_get_events' ) ) {
    return;
}

if ( isset( $atts['items'] ) && $atts['items'] != '' ) {
    $limit = absint( $atts['items'] );
}

$args = array(
    'eventDisplay'   => 'list',
    'posts_per_page' => $limit,
    'tribe_render_context' => 'widget',
    'featured' => false,
);

$posts = tribe_get_events($args);
$events_label_plural = tribe_get_event_label_plural();
$events_label_plural_lowercase = tribe_get_event_label_plural_lowercase();

if ( empty( $posts ) && isset($atts['no_upcoming_events']) && $atts['no_upcoming_events'] ) {
    return;
}

function zoo_get_events_time($type = 'date', $status = 'start', $format = '') {
    global $post;
    $event = $post;
    $event_date = '';

    if ( $type === 'date' ) {
        if ( $format === '' ) {
            $format = get_option( 'date_format' );
        }
        if ( $status === 'start' ) {
            $event_date = tribe_get_start_date( $event, false, $format );
        } elseif ( $status === 'end' ) {
            $event_date =  tribe_get_end_date( $event, false, $format );
        }
    } elseif ( $type === 'time' ) {
        if ( $format === '' ) {
            $format = get_option( 'time_format' );
        }
        if ( !tribe_event_is_all_day( $event ) ) {
            if ( $status === 'start' ) {
                $event_date = tribe_get_start_date( $event, false, $format );
            } elseif ( $status === 'end' ) {
                $event_date = tribe_get_end_date( $event, false, $format );
            }
        }
    }

    return $event_date;
}

$media_class = '';

if ( get_post_thumbnail_id( $post ) && isset( $atts['show_image'] ) && $atts['show_image'] === 'yes' && isset( $atts['show_date'] ) && $atts['show_date'] != 'none' ) {
    $media_class = ' image-date';
}
?>
<div class="cvca-shortcode-events zoo-events<?php echo esc_attr( $css_class ); ?>">
    <?php if ( $atts['title'] != '' ) : ?>
        <h3 class="zoo-events-title title-block title-block-medium">
            <?php echo esc_html($atts['title']); ?>
        </h3>
    <?php endif; ?>

    <div class="zoo-events-content">
       <?php
        // Check if any event posts are found.
        if ( $posts ) : ?>

            <ul class="zoo-events-list">
                <?php
                // Setup the post data for each event.
                foreach ( $posts as $post ) :
                    setup_postdata( $post );
                    ?>
                    <li class="zoo-event <?php tribe_events_event_classes() ?>">
                        <div class="media">
                            <div class="pull-left<?php echo esc_attr($media_class); ?>">
                                <?php if ( isset( $atts['show_date'] ) && $atts['show_date'] != 'none' ) : ?>
                                <div class="zoo-event-date">
                                    <?php if ( isset( $atts['show_date'] ) && $atts['show_date'] === 'start' ) : ?>
                                        <span class="day"><?php echo zoo_get_events_time('date', 'start', 'd'); ?></span>
                                        <span class="month"><?php echo zoo_get_events_time('date', 'start', 'F'); ?></span>
                                    <?php elseif( isset( $atts['show_date'] ) && $atts['show_date'] === 'end' ): ?>
                                        <span class="day"><?php echo zoo_get_events_time('date', 'end', 'd'); ?></span>
                                        <span class="month"><?php echo zoo_get_events_time('date', 'end', 'F'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php
                                if ( get_post_thumbnail_id( $post ) && isset( $atts['show_image'] ) && $atts['show_image'] === 'yes' ) {                            
                                    $thumbnail_size = apply_filters( 'tribe_events_list_widget_thumbnail_size', 'post-thumbnail' );
                                ?>
                                    <div class="tribe-event-image">
                                        <?php the_post_thumbnail( $thumbnail_size ); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="media-body">
                                <!-- Event Title -->
                                <h4 class="zoo-event-title">
                                    <a href="<?php echo esc_url( tribe_get_event_link() ); ?>" rel="bookmark"><?php the_title(); ?></a>
                                </h4>

                                <!-- Event Description -->
                                <?php if( isset( $atts['show_desc'] ) && $atts['show_desc'] === 'yes' ) : ?>
                                <div class="zoo-event-desc">
                                    <?php
                                    $excerpt = get_the_content();

                                    if ( ! empty( get_the_excerpt() ) ) {
                                        $excerpt = get_the_excerpt();
                                    }

                                    if ( empty( $atts['desc_length'] ) ) {
                                        $atts['desc_length'] = '12';
                                    }
                                    
                                    echo cvca_word_limit( $excerpt, $atts['desc_length'], '...' );
                                    ?>
                                </div>
                                <?php endif; ?>

                                <!-- Event Time -->
                                <div class="zoo-event-duration">
                                    <?php if ( zoo_get_events_time('time', 'start', 'h:i a') != '' || zoo_get_events_time('time', 'end', 'h:i a') != '' ) : ?>
                                        <?php if ( isset( $atts['show_time'] ) && $atts['show_time'] === 'yes' ) : ?>
                                        <span class="zoo-event-time">
                                            <?php
                                            if ( isset( $atts['show_date'] ) && $atts['show_date'] === 'start' ) {
                                                echo zoo_get_events_time('time', 'start', 'h:i a');
                                            } elseif( isset( $atts['show_date'] ) && $atts['show_date'] === 'end' ) {
                                                echo zoo_get_events_time('time', 'end', 'h:i a');
                                            }
                                            ?>
                                        </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ( isset( $atts['show_category'] ) && $atts['show_category'] === 'yes' ) : ?>
                                    <span class="zoo-event-cat">
                                        <?php echo get_the_term_list( get_the_ID(), 'tribe_events_cat', '', ', ', '' ); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php
                endforeach;
                ?>
            </ul><!-- .tribe-list-widget -->
            <?php if ( isset( $atts['show_view_all'] ) && $atts['show_view_all'] === 'yes' ) : ?>
                <div class="zoo-events-view-all">
                    <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" rel="bookmark"><?php printf( esc_html__( 'View All %s', 'cvca' ), $events_label_plural ); ?></a>
                </div>
            <?php endif; ?>

        <?php
        // No events were found.
        else : ?>
            <p><?php printf( esc_html__( 'There are no upcoming %s at this time.', 'cvca' ), $events_label_plural_lowercase ); ?></p>
        <?php endif; ?>
        <?php
        wp_reset_query();

        // Reinstate the tribe bar params
        if ( ! empty( $hold_tribe_bar_args ) ) {
            foreach ( $hold_tribe_bar_args as $key => $value ) {
                $_REQUEST[ $key ] = $value;
            }
        }
        ?>
    </div>
</div>
