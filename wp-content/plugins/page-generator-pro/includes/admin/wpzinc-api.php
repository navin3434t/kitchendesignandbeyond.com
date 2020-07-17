<?php
/**
 * WP Zinc API class.  Used by other classes to perform POST and GET requests
 * to wpzinc.com
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.7.8
 */
class Page_Generator_Pro_WPZinc_API {

    /**
     * Holds the base object.
     *
     * @since   1.9.8
     *
     * @var     object
     */
    public $base;

    /**
     * Holds endpoint to IP resolutions, which are used
     * if WordPress' wp_remote_get() / wp_remote_post()
     * fails on DNS resolution
     *
     * @since   1.7.8
     *
     * @var     array
     */
    public $api_endpoint_resolutions = array( 
        'wpzinc.com:80:178.62.58.212',
        'wpzinc.com:443:178.62.58.212',
    );

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
     * Sanitizes API arguments, by removing false or empty
     * arguments in the array.
     *
     * @since   1.7.8
     *
     * @param   array   $args   Arguments
     * @return  array           Sanitized Arguments
     */
    public function sanitize_arguments( $args ) {

        foreach ( $args as $key => $value ) {
            if ( empty( $value ) || ! $value ) {
                unset( $args[ $key ] );
            }
        }

        return $args;
        
    }

    /**
     * Private function to perform a GET request
     *
     * @since  1.7.8
     *
     * @param  string  $cmd        Command (required)
     * @param  array   $params     Params (optional)
     * @return mixed               WP_Error | object
     */
    public function get( $cmd, $params = array() ) {

        return $this->request( $cmd, 'get', $params );

    }

    /**
     * Private function to perform a POST request
     *
     * @since  1.7.8
     *
     * @param  string  $cmd        Command (required)
     * @param  array   $params     Params (optional)
     * @return mixed               WP_Error | object
     */
    public function post( $cmd, $params = array() ) {

        return $this->request( $cmd, 'post', $params );

    }

    /**
     * Main function which handles sending requests to an API
     *
     * @since   1.7.8
     *
     * @param   string  $cmd        Command
     * @param   string  $method     Method (get|post)
     * @param   array   $params     Parameters (optional)
     * @return  mixed               WP_Error | object
     */
    private function request( $cmd, $method = 'get', $params = array() ) {

        // Define timeout, in seconds
        $timeout = 10;

        /**
         * Defines the maximum number of seconds to allow an API request to run.
         *
         * @since   1.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( 'page_generator_pro_wpzinc_api_request_timeout', $timeout );

        // Send request
        $result = $this->request_curl( $this->api_endpoint, $cmd, $method, $params, $timeout );

        // Result will be WP_Error or the data we expect
        return $result;

    }

    /**
     * Performs POST and GET requests through PHP's curl_exec() function.
     *
     * If this function is called, request_wordpress() failed, most likely
     * due to a DNS lookup failure or CloudFlare failing to respond.
     *
     * @since   1.7.1
     *
     * @param   string  $url        URL
     * @param   string  $cmd        API Command
     * @param   string  $method     Method (post|get)
     * @param   array   $params     Parameters
     * @param   int     $timeout    Timeout, in seconds (default: 10)
     * @return  mixed               WP_Error | object
     */
    private function request_curl( $url, $cmd, $method, $params, $timeout = 10 ) {

        // Init
        $ch = curl_init();

        // Set request specific options
        switch ( $method ) {
            /**
             * GET
             */
            case 'get':
            case 'GET':
                curl_setopt_array( $ch, array(
                    CURLOPT_URL             => $url . '&' . http_build_query( array(
                        'endpoint'  => $cmd,
                        'params'    => $params,
                    ) ),
                    CURLOPT_RESOLVE         => $this->api_endpoint_resolutions,
                ) );
                break;

            /**
             * POST
             */
            case 'post':
            case 'POST':
                curl_setopt_array( $ch, array(
                    CURLOPT_URL             => $url,
                    CURLOPT_POST            => true,
                    CURLOPT_POSTFIELDS      => http_build_query( array(
                        'endpoint'  => $cmd,
                        'params'    => $params,
                    ) ),
                    CURLOPT_RESOLVE         => $this->api_endpoint_resolutions,
                ) );
                break;
        }

        // Set shared options
        curl_setopt_array( $ch, array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_CONNECTTIMEOUT  => $timeout,
            CURLOPT_TIMEOUT         => $timeout,
        ) );

        // Execute
        $result     = curl_exec( $ch );
        $http_code  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        $error      = curl_error( $ch );
        curl_close( $ch );

        // If our error string isn't empty, something went wrong
        if ( ! empty( $error ) ) {
            // Depending on the error, return a more verbose WP_Error
            if ( strpos( $error, 'timed out' ) !== false ) {
                return new WP_Error( 'page_generator_pro_wpzinc_api_request_curl_timeout', sprintf(
                    __( 'Your site cannot communicate with the location server, or the location data response is too large.
                        Consider adding more constraints, but if this error still persists, please open a <a href="%s">support request</a> 
                        including the following information:<br />
                        Product Name: %s<br />
                        Product Version: %s<br />
                        Domain: %s<br />
                        IP Address: %s<br />
                        Endpoint: %s<br />
                        Parameters: %s', $this->plugin->name ),
                    $this->base->plugin->support_url,
                    $this->base->plugin->displayName,
                    $this->base->plugin->version,
                    get_bloginfo( 'url' ),
                    ( isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : 'N/A' ),
                    $cmd,
                    http_build_query( $params )
                ) );
            }
            
            // Return the cURL response
            return new WP_Error( 'page_generator_pro_wpzinc_api_request_curl', $error );
        }

        // Decode the result
        $result = json_decode( $result );

        // If the response is empty or missing the data payload, return a generic error
        if ( is_null( $result ) || ! isset( $result->data ) ) {
            return new WP_Error(
                $http_code,
                'API Error: HTTP Code ' . $http_code . '. Sorry, we don\'t have any more information about this error. Please try again.'
            );
        }

        // If the response's success flag is false, return the data as an error
        if ( ! $result->success ) {
            return new WP_Error( $http_code, $result->data );
        }

        // All OK - return the data
        unset( $result->data->status ); // This is from the originating API request, and we no longer need it

        return $result->data; // object comprising of data, links + meta

    }

}