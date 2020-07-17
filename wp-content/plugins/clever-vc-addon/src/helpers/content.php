<?php
/**
 * Content helpers
 *
 * @package  CVCA\Helpers
 */

/**
 * Correct WordPress excerpt
 *
 * @param  object  $post    \WP_Post
 * @param  int     $length  Expected excerpt length.
 * @param  string  $more    Read more string.
 *
 * @see https://developer.wordpress.org/reference/functions/get_post/
 *
 * @return  string
 */
function cvca_get_excerpt($length = 55)
{
    $post = get_post(null);
    $text = $post->post_excerpt ? : $post->post_content;
    $text = do_shortcode($text);
    $text = strip_shortcodes($text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = wp_trim_words($text, $length, false);

    return $text.'...';
}

/**
 * Word limit
 *
 * @param  string  $text
 * @param  int     $length  Expected excerpt length.
 * @param  string  $end     End string for excerpt.
 *
 * @return  string
 */
function cvca_word_limit( $text = '', $length = 16, $end = '[&hellip;]' ) {

    $text = strip_shortcodes( $text );

    $text = str_replace(']]>', ']]&gt;', $text);

    $text = wp_trim_words( $text, $length, $end );

    return $text;
}

/**
 * Get course categories data for VC
 */
function get_course_categories_for_vc()
{
    $data = array();
    $tax_slug = esc_attr( apply_filters( 'sensei_course_category_slug', _x( 'course-category', 'taxonomy archive slug', 'cvca' ) ) );

    $query = new WP_Term_Query(array(
        'hide_empty' => true,
        'taxonomy'   => $tax_slug,
    ));

    if (!empty($query->terms)) {
        foreach ($query->terms as $cat) {
            $cat_data = array('label' => $cat->name, 'value' => $cat->slug);
            $data[] = $cat_data;
        }
    }

    return $data;
}

/**
 * Get sensei courses data for vc
 */
function get_sensei_courses_data_for_vc()
{
    $data = array();

    $query = new WP_Query(array(
        'post_type'           => 'course',
        'post_status'         => 'publish',
        'suppress_filters'    => true,
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true
    ));

    if (!empty($query->posts)) {
        foreach ($query->posts as $course) {
            $course_data = array('label' => $course->post_title, 'value' => $course->post_name);
            $data[] = $course_data;
        }
    }

    return $data;
}

if ( ! function_exists( 'zoo_scrape_instagram' ) ) {
    // based on https://gist.github.com/cosmocatalano/4544576
    function zoo_scrape_instagram( $username ) {

        $username = strtolower( $username );
        $username = str_replace( '@', '', $username );

        if ( false === ( $instagram = get_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ) ) ) ) {

            $remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );

            if ( is_wp_error( $remote ) )
                return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'cvca' ) );

            if ( 200 != wp_remote_retrieve_response_code( $remote ) )
                return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'cvca' ) );

            $shards = explode( 'window._sharedData = ', $remote['body'] );
            $insta_json = explode( ';</script>', $shards[1] );
            $insta_array = json_decode( $insta_json[0], TRUE );

            if ( ! $insta_array )
                return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'cvca' ) );

            if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
                $images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'cvca' ) );
            }

            if ( ! is_array( $images ) )
                return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'cvca' ) );

            $instagram = array();

            foreach ( $images as $image ) {

                $image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
                $image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

                // handle both types of CDN url
                if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
                    $image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
                    $image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
                } else {
                    $urlparts = wp_parse_url( $image['thumbnail_src'] );
                    $pathparts = explode( '/', $urlparts['path'] );
                    array_splice( $pathparts, 3, 0, array( 's160x160' ) );
                    $image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
                    $pathparts[3] = 's320x320';
                    $image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
                }

                $image['large'] = $image['thumbnail_src'];

                if ( $image['is_video'] == true ) {
                    $type = 'video';
                } else {
                    $type = 'image';
                }

                $caption = __( 'Instagram Image', 'cvca' );
                if ( ! empty( $image['caption'] ) ) {
                    $caption = $image['caption'];
                }

                $instagram[] = array(
                    'description'   => $caption,
                    'link'              => trailingslashit( '//instagram.com/p/' . $image['code'] ),
                    'time'              => $image['date'],
                    'comments'          => $image['comments']['count'],
                    'likes'             => $image['likes']['count'],
                    'thumbnail'         => $image['thumbnail'],
                    'small'            => $image['small'],
                    'large'            => $image['large'],
                    'original'        => $image['display_src'],
                    'type'              => $type
                );
            }

            // do not set an empty transient - should help catch private or empty accounts
            if ( ! empty( $instagram ) ) {
                $instagram = base64_encode( serialize( $instagram ) );
                set_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS*2 ) );
            }
        }

        if ( ! empty( $instagram ) ) {

            return unserialize( base64_decode( $instagram ) );

        } else {

            return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'cvca' ) );

        }
    }
}

if ( ! function_exists( 'zoo_images_only' ) ) {
    function zoo_images_only( $media_item ) {

        if ( $media_item['type'] == 'image' )
            return true;

        return false;
    }
}

if ( ! function_exists( 'zoo_abbreviate_total_count' ) ) {
    function zoo_abbreviate_total_count( $value, $floor = 0 ) {
        if ( $value >= $floor ) {
            $abbreviations = array(12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => '');

            foreach ( $abbreviations as $exponent => $abbreviation ) {
                if ( $value >= pow(10, $exponent) ) {
                    return round(floatval($value / pow(10, $exponent)),1).$abbreviation;
                }
            }
        } else {
            return $value;
        }
    }
}

if ( ! function_exists( 'zoo_time_elapsed_string' ) ) {
    function zoo_time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
