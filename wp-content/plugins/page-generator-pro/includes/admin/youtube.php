<?php
/**
 * Youtube class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.2.0
 */
class Page_Generator_Pro_Youtube {

    /**
     * Holds the base object.
     *
     * @since   1.9.8
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the API Key
     *
     * @since   1.2.0
     *
     * @var     string
     */
    public $api_key = 'AIzaSyC4IwPk9Iyp1uALNkj5WTblmQCO9Dr7ZCo';

    /**
     * Holds the API endpoint
     *
     * @since   1.2.0
     *
     * @var     string
     */
    private $endpoint = 'https://www.googleapis.com/youtube/v3/';

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
     * Search for YouTube Videos for the given keyword and optional
     * latitude / longitude.
     *
     * @since   1.2.0
     *
     * @param   string  $keyword    Search Terms
     * @param   array   $lat_lng    Latitude and Longitude
     */
    public function search( $keyword, $lat_lng = false ) {

        // Build array of arguments
        $args = array(
            'type'      => 'video',
            'q'         => $keyword,
            'part'      => 'snippet',
            'maxResults'=> 50,
        );

        // If a latitude and longitude is supplied, add it to the query
        if ( $lat_lng != false ) {
            $args['location'] = $lat_lng['latitude'] . ',' . $lat_lng['longitude'];
            $args['locationRadius'] = '10mi';
        }

        // Run the query
        $results = $this->get( 'search', $args );
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Parse results
        $videos = array();
        foreach ( $results->items as $video ) {
            $videos[] = array(
                'id'        => $video->id->videoId,
                'url'       => 'https://youtube.com/watch?v=' . $video->id->videoId,
                'title'     => $video->snippet->title,
                'caption'   => $video->snippet->description,
            );
        }

        // Return array of videos
        return $videos;
        
    }

    /**
     * Performs an authorized GET request
     *
     * @since   1.2.0
     *
     * @param   string  $action     Action
     * @param   array   $args       Arguments
     * @return  array   $data       Result Data
     */
    private function get( $action, $args ) {

        // Add API key to args
        $args['key'] = $this->api_key;

        // Build URL
        $url = $this->endpoint . $action . '?' . http_build_query( $args );
        
        // Run query
        $response = wp_remote_get( $url );
        
        // Bail if an error occured
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Get body
        $data = wp_remote_retrieve_body( $response );
        $json = json_decode( $data );
        
        // Check for errors
        if ( isset( $json->error ) ) {
            return new WP_Error( 'page_generator_pro_youtube_get', $json->error->code . ': ' . $json->error->message );
        }

        return $json;

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
        $name = 'youtube';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}