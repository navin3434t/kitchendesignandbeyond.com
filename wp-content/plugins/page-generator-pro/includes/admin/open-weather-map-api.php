<?php
/**
 * OpenWeatherMap API class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.4.8
 */
class Page_Generator_Pro_Open_Weather_Map_API {

    /**
     * Holds the base object.
     *
     * @since   2.4.8
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the API Key
     *
     * @since   2.4.8
     *
     * @var     string
     */
    public $api_key = '44cd0f66dbf150164a4289bfc29fa565';

    /**
     * Holds the API endpoint
     *
     * @since   2.4.8
     *
     * @var     string
     */
    private $api_endpoint = 'http://api.openweathermap.org/';

    /**
     * Constructor.
     *
     * @since   2.4.8
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Returns the supported Forecast Types
     *
     * @since   2.5.1
     *
     * @return  array   Forecast Types
     */
    public function get_forecast_types() {

        return array(
            13 => __( 'Small', 'page-generator-pro' ),
            16 => __( 'Medium', 'page-generator-pro' ),
            17 => __( 'Medium with Details', 'page-generator-pro' ),
            12 => __( 'Large', 'page-generator-pro' ),
            11 => __( 'Large with Details', 'page-generator-pro' ),
            18 => __( 'Banner', 'page-generator-pro' ),
            19 => __( 'Banner Alternative', 'page-generator-pro' ),
        );

    }

    /**
     * Returns the supported Temperature Units
     *
     * @since   2.5.1
     *
     * @return  array   Tempoerature Units
     */
    public function get_temperature_units() {

        return array(
            'imperial'  => __( 'Imperial (Farenheight)', 'page-generator-pro' ),
            'metric'    => __( 'Metric (Celcius)', 'page-generator-pro' ),
        );

    }

    /**
     * Returns the City ID for the given Location (City or ZIP Code) and Country,
     * which can then be used for subsequent API queries or the JS widget.
     *
     * @since   2.4.8
     *
     * @param   string  $location       Location (City, ZIP Code)
     * @param   string  $country_code   Country Code
     * @return  mixed                   WP_Error | int
     */
    public function get_city_id( $location, $country_code ) {

        // Build array of parameters
        $params = array(
            'q'         => $location . ',' . $country_code,
        );

        // Run the query
        $results = $this->get( 'data/2.5/weather', $params );
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Bail if no ID in the results
        if ( ! isset( $results->id ) || empty( $results->id ) ) {
            return new WP_Error( 'page_generator_pro_open_weather_map_api', sprintf(
                __( 'OpenWeatherMap API: No Location ID could be found for %s', 'page-generator-pro' ),
                $params['q']
            ) );
        }

        return $results->id;
        
    }

    /**
     * Private function to perform a POST request
     *
     * @since  2.2.9
     *
     * @param  string   $endpoint   Endpoint
     * @param  array    $params     Request Parameters
     * @return mixed                WP_Error | object
     */
    private function get( $endpoint, $params = array() ) {

        // Define timeout, in seconds
        $timeout = 30;

        /**
         * Defines the maximum number of seconds to allow an API request to run.
         *
         * @since   1.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( 'page_generator_pro_open_weather_map_api_request_timeout', $timeout );

        // Add API key to the params
        $params['APPID'] = $this->api_key;

        // Send request
        $response = wp_remote_get( add_query_arg( $params, $this->api_endpoint . $endpoint ), array(
            'timeout'   => $timeout,
        ) );

        // If an error, return it
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Get response code
        $response_code = wp_remote_retrieve_response_code( $response );

        // Bail if response code isn't 200 OK
        if ( $response_code != 200 ) {
            switch ( $response_code ) {
                /**
                 * Location not found
                 */
                case 404:
                    return new WP_Error( 'page_generator_pro_open_weather_map_api', sprintf(
                        __( 'OpenWeatherMap API: Could not find %s', 'page-generator-pro' ),
                        $params['q']
                    ) );
                    break;

                default:
                    return new WP_Error( 'page_generator_pro_open_weather_map_api', sprintf(
                        __( 'OpenWeatherMap API: HTTP Error Code %s: %s', 'page-generator-pro' ),
                        $response_code,
                        wp_remote_retrieve_response_message( $response )
                    ) );
                    break;
            }
        }

        // Get body
        $data = wp_remote_retrieve_body( $response );

        // Decode JSON
        $data = json_decode( $data );

        /*
        // Bail if the status is an error
        if ( ! isset( $data->status ) ) { 
            return new WP_Error( 'page_generator_pro_wordai_api', __( 'WordAI API: Unable to determine success or failure of request.', 'page-generator-pro' ) );   
        }
        if ( $data->status != 'Success' ) {
            return new WP_Error( 'page_generator_pro_wordai_api', sprintf(
                __( 'WordAI API: %s: %s', 'page-generator-pro' ),
                $data->status,
                $data->error
            ) );
        }
        */

        // Return data
        return $data;

    }

}