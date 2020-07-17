<?php
/**
 * Media Library class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.8.0
 */
class Page_Generator_Pro_Media_Library {

    /**
     * Constructor.
     *
     * @since   1.9.8
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Returns an image ID at random based on the given parameters
     *
     * @since   1.8.0
     *
     * @param   array   $args   Attributes
     * @return  mixed           false | int
     */
    public function get_random_image_id( $args ) {

        global $wpdb;

        // Define query parameters that are always AND clauses
        $query = array(
            $wpdb->prefix . "posts.post_type = 'attachment'",
            $wpdb->prefix . "posts.post_status = 'inherit'",
            $wpdb->prefix . "posts.post_mime_type LIKE 'image/%'",
        );
        if ( $args['ids'] != false && ! empty( $args['ids'] ) ) {
            $query[] = $wpdb->prefix . "posts.ID IN (" . $args['ids'] . ")";
        }
        if ( $args['min_id'] != false && ! empty( $args['min_id'] ) ) {
            $query[] = $wpdb->prefix . "posts.ID >= " . $args['min_id'];
        }
        if ( $args['max_id'] != false && ! empty( $args['max_id'] ) ) {
            $query[] = $wpdb->prefix . "posts.ID <= " . $args['max_id'];
        }

        // Define query parameters that can be AND / OR clauses
        $query_meta = array();
        if ( $args['title'] != false && ! empty( $args['title'] ) ) {
            $query_meta[] = $wpdb->prefix . "posts.post_title LIKE '%" . $args['title'] . "%'";
        }
        if ( $args['alt'] != false && ! empty( $args['alt'] ) ) {
            $query_meta[] = "(" . $wpdb->prefix . "postmeta.meta_key = '_wp_attachment_image_alt' AND " . $wpdb->prefix . "postmeta.meta_value LIKE '%" . $args['alt'] . "%')";
        }
        if ( $args['caption'] != false && ! empty( $args['caption'] ) ) {
            $query_meta[] = $wpdb->prefix . "posts.post_excerpt LIKE '%" . $args['caption'] . "%'";
        }
        if ( $args['description'] != false && ! empty( $args['description'] ) ) {
            $query_meta[] = $wpdb->prefix . "posts.post_content LIKE '%" . $args['description'] . "%'";
        }

        /**
         * Filter the MySQL query (image type, ID) to fetch a random image ID from the Media Library.
         *
         * @since   1.8.0
         *
         * @param   array   $query  Query Conditions
         * @param   array   $args   Attributes
         */
        $query = apply_filters( 'page_generator_pro_shortcode_media_library_get_random_image_html_tag_query', $query, $args );

        /**
         * Filter the MySQL query meta (title, alt, caption, description) to fetch a random image ID from the Media Library.
         *
         * @since   2.2.2
         *
         * @param   array   $query  Query Conditions
         * @param   array   $args   Attributes
         */
        $query_meta = apply_filters( 'page_generator_pro_shortcode_media_library_get_random_image_html_tag_query_meta', $query_meta, $args );

        // Build query
        $query_string = "SELECT " . $wpdb->prefix . "posts.ID
FROM " . $wpdb->prefix . "posts
LEFT JOIN " . $wpdb->prefix . "postmeta
ON " . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "postmeta.post_id
WHERE " . implode( ' AND ', $query ) .
( count( $query_meta ) > 0 ? " AND (" . implode( ' ' . $args['operator'] . ' ', $query_meta ) . ") " : "" ) . "
GROUP BY " . $wpdb->prefix . "posts.ID
LIMIT 100";

        // Run query
        $images = $wpdb->get_col( $query_string );

        // Bail if no results were found
        if ( empty( $images ) ) {
            return false;
        }

        // Fetch an image at random from the resultset
        if ( count( $images ) == 1 ) {
            $image_id = $images[0];
        } else {
            $image_id = $images[ rand( 0, count( $images ) - 1 ) ];
        }

        /**
         * Filter the Image ID before returning.
         *
         * @since   1.8.0
         *
         * @param   int     $image_id   WordPress Media Library ID
         * @param   array   $args       Arguments
         * @param   array   $images     Image Results from Query
         */
        $image_id = apply_filters( 'page_generator_pro_media_library_get_random_image_id', $image_id, $args, $images, $query, $query_string );

        // Return Image ID
        return $image_id;

    }

    /**
     * Returns an image HTML tag for the given Attachment ID and given parameters
     *
     * @since   1.8.0
     *
     * @param   int     $image_id   Attachment ID
     * @param   array   $args       Attributes
     * @return  string              Output
     */
    public function get_image_html_tag_by_id( $image_id, $args ) {

        // If the arguments contain an alt_tag, use this for the <img> tag instead
        // of the Media Library's alt tag
        $atts = '';
        if ( isset( $args['alt_tag'] ) && $args['alt_tag'] != false ) {
            $atts = array(
                'alt' => $args['alt_tag'],
            );
        }

        // Get the image HTML tag
        $html = wp_get_attachment_image( $image_id, $args['size'], false, $atts );

        /**
         * Filter the HTML Image Tag before returning.
         *
         * @since   1.8.0
         *
         * @param   string  $html       HTML Image Tag
         * @param   array   $args       Arguments
         * @param   int     $image_id   WordPress Media Library ID
         * @param   array   $images     Image Results from Query
         */
        $html = apply_filters( 'page_generator_pro_media_library_get_random_image_html_tag', $html, $args, $image_id );

        // Return filtered HTML
        return $html;
        
    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since       1.1.6
     * @deprecated  1.9.8
     *
     * @return      object Class.
     */
    public static function get_instance() {

        // Define class name
        $name = 'media_library';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}