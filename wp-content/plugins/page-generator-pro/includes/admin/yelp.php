<?php
/**
 * Yelp API v3 Wrapper
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.0.0
 */
class Page_Generator_Pro_Yelp {

    /**
     * Holds the base class object.
     *
     * @since   1.4.5
     *
     * @var     object
     */
    public $base;

    /**
     * API Endpoint
     *
     * @since   1.4.5
     *
     * @var     string
     */
    public $endpoint = 'https://api.yelp.com/v3/';

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
     * Returns an array of rating options for business listings
     *
     * @since   2.5.1
     *
     * @return  array   Rating Options
     */
    public function get_rating_options() {

        return array(
            '0'     => __( 'No Minimum Rating', 'page-generator-pro' ),
            '0.5'   => __( '0.5 / 5 or higher', 'page-generator-pro' ),
            '1'     => __( '1 / 5 or higher', 'page-generator-pro' ),
            '1.5'   => __( '1.5 / 5  r higher', 'page-generator-pro' ),
            '2'     => __( '2 / 5 or higher', 'page-generator-pro' ),
            '2.5'   => __( '2.5 / 5  r higher', 'page-generator-pro' ),
            '3'     => __( '3 / 5 or higher', 'page-generator-pro' ),
            '3.5'   => __( '3.5 / 5  r higher', 'page-generator-pro' ),
            '4'     => __( '4 / 5 or higher', 'page-generator-pro' ),
            '4.5'   => __( '4.5 / 5  r higher', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of locales supported by Yelp's Business Listings endpoint
     *
     * @since   2.2.4
     *
     * @return  array   Locales
     */
    public function get_locales() {

        // Define supported locales
        $locales = array(
            'cs_CZ' => __( 'Czech Republic: Czech', 'page-generator-pro' ),
            'da_DK' => __( 'Denmark: Danish', 'page-generator-pro' ),
            'de_AT' => __( 'Austria: German', 'page-generator-pro' ),
            'de_CH' => __( 'Switzerland: German', 'page-generator-pro' ),
            'de_DE' => __( 'Germany: German', 'page-generator-pro' ),
            'en_AU' => __( 'Australia: English', 'page-generator-pro' ),
            'en_BE' => __( 'Belgium: English', 'page-generator-pro' ),
            'en_CA' => __( 'Canada: English', 'page-generator-pro' ),
            'en_CH' => __( 'Switzerland: English', 'page-generator-pro' ),
            'en_GB' => __( 'United Kingdom: English', 'page-generator-pro' ),
            'en_HK' => __( 'Hong Kong: English', 'page-generator-pro' ),
            'en_IE' => __( 'Republic of Ireland: English', 'page-generator-pro' ),
            'en_MY' => __( 'Malaysia: English', 'page-generator-pro' ),
            'en_NZ' => __( 'New Zealand: English', 'page-generator-pro' ),
            'en_PH' => __( 'Philippines: English', 'page-generator-pro' ),
            'en_SG' => __( 'Singapore: English', 'page-generator-pro' ),
            'en_US' => __( 'United States: English', 'page-generator-pro' ),
            'es_AR' => __( 'Argentina: Spanish', 'page-generator-pro' ),
            'es_CL' => __( 'Chile: Spanish', 'page-generator-pro' ),
            'es_ES' => __( 'Spain: Spanish', 'page-generator-pro' ),
            'es_MX' => __( 'Mexico: Spanish', 'page-generator-pro' ),
            'fi_FI' => __( 'Finland: Finnish', 'page-generator-pro' ),
            'fil_PH' => __( 'Philippines: Filipino', 'page-generator-pro' ),
            'fr_BE' => __( 'Belgium: French', 'page-generator-pro' ),
            'fr_CA' => __( 'Canada: French', 'page-generator-pro' ),
            'fr_CH' => __( 'Switzerland: French', 'page-generator-pro' ),
            'fr_FR' => __( 'France: French', 'page-generator-pro' ),
            'it_CH' => __( 'Switzerland: Italian', 'page-generator-pro' ),
            'it_IT' => __( 'Italy: Italian', 'page-generator-pro' ),
            'ja_JP' => __( 'Japan: Japanese', 'page-generator-pro' ),
            'ms_MY' => __( 'Malaysia: Malay', 'page-generator-pro' ),
            'nb_NO' => __( 'Norway: Norwegian', 'page-generator-pro' ),
            'nl_BE' => __( 'Belgium: Dutch', 'page-generator-pro' ),
            'nl_NL' => __( 'The Netherlands: Dutch', 'page-generator-pro' ),
            'pl_PL' => __( 'Poland: Polish', 'page-generator-pro' ),
            'pt_BR' => __( 'Brazil: Portuguese', 'page-generator-pro' ),
            'pt_PT' => __( 'Portugal: Portuguese', 'page-generator-pro' ),
            'sv_FI' => __( 'Finland: Swedish', 'page-generator-pro' ),
            'sv_SE' => __( 'Sweden: Swedish', 'page-generator-pro' ),
            'tr_TR' => __( 'Turkey: Turkish', 'page-generator-pro' ),
            'zh_HK' => __( 'Hong Kong: Chinese', 'page-generator-pro' ),
            'zh_TW' => __( 'Taiwan: Chinese', 'page-generator-pro' ),  
        );

        return $locales;

    }

    /**
     * Returns an array of price options for business listings
     *
     * @since   2.5.1
     *
     * @return  array   Price Options
     */
    public function get_price_options() {

        return array(
            '0'     => __( 'Any Price Level', 'page-generator-pro' ),
            '1'     => __( '$', 'page-generator-pro' ),
            '2'     => __( '$$', 'page-generator-pro' ),
            '3'     => __( '$$$', 'page-generator-pro' ),
            '4'     => __( '$$$$', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of sort by options for business listings
     *
     * @since   2.5.1
     *
     * @return  array   Sort By Options
     */
    public function get_sort_by_options() {

        return array(
            'best_match'    => __( 'Best Match', 'page-generator-pro' ),
            'rating'        => __( 'Rating', 'page-generator-pro' ),
            'review_count'  => __( 'Review Count', 'page-generator-pro' ),
            'description'   => __( 'Description', 'page-generator-pro' ),
        );

    }

    /**
     * Performs a GET request to /businesses/search
     *
     * @since   1.4.5
     *
     * @param   array   $args               Arguments
     *      $args = [
     *          'term'              => (string) Search Terms
     *          'location'          => (string) Location
     *          'radius'            => (int) Radius, in Miles (max: 25)
     *          'minimum_rating'    => (int) Minimum Rating
     *          'locale'            => (string) Locale
     *          'price'             => (int) Price Level (0 - 4)
     *          'limit'             => (int) Number of Listings
     *          'sort_by'           => (string) Sort Listings By
     *      ]
     * @return  mixed                       WP_Error | array
     */
    public function businesses_search( $args ) {

        // Remove any zero or false arguments
        foreach ( $args as $key => $value ) {
            if ( empty( $value ) || ! $value ) {
                unset( $args[ $key ] );
            }
        }

        // If a minimum rating is specified, increase the limit so we can sort through
        // the results to extract those that match the minimum rating
        if ( isset( $args['minimum_rating'] ) ) {
            // Store minimum rating and limit
            $minimum_rating = $args['minimum_rating'];
            $limit = $args['limit'];

            // Set limit to a high number, and remove the minimum rating argument
            $args['limit'] = 50; // Maximum supported
            unset( $args['minimum_rating'] );
        }

        // If a radius is set, ensure it doesn't exceed the maximum permitted, and convert it to metres
        if ( isset( $args['radius'] ) ) {
            if ( $args['radius'] > 20 ) {
                $args['radius'] = 20;
            }

            // Convert radius to metres
            $args['radius'] = $args['radius'] * 1609;
        }

        // Get results
        $results = $this->get( 'businesses/search', $args );

        // Bail if no results were found
        if ( is_wp_error( $results ) ) {
            return $results;
        }
        if ( ! $results->total ) {
            return false;
        }

        // If there's no minimum rating requirement, return the results
        if ( ! isset( $minimum_rating ) ) {
            return $results->businesses;
        }

        // Build results by minimum rating
        $filtered_results = array();
        $count = 0;
        foreach ( $results->businesses as $result ) {
            // Skip if the rating doesn't match our minimum
            if ( $result->rating < $minimum_rating ) {
                continue;
            }

            // Add to filtered results
            $filtered_results[] = $result;
            $count++;
            
            // If we hit the limit, exit the loop
            if ( $count == $limit ) {
                break;
            }
        }

        // Return results
        return $filtered_results;

    }

    /**
     * Checks settings for an Access Token, falling back to calling
     * the WP Zinc oAuth Gateway to fetch a new Access Token if
     * required.
     *
     * @since   1.4.5
     */
    private function get_access_token() {

        // Get settings
        $access_token = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-yelp' );

        // If settings are empty or an array, fetch a v3 token
        if ( empty( $access_token ) || is_array( $access_token ) ) {
            $response = wp_remote_post( 'https://www.wpzinc.com/?oauth=yelp' );

            // If an error, return it
            if ( is_wp_error( $response ) ) {
                return $response;
            }

            // Get response code
            $response_code = wp_remote_retrieve_response_code( $response );
            $response_message = wp_remote_retrieve_response_message( $response );

            // Bail if response code isn't 200 OK
            if ( $response_code != 200 ) {
                return new WP_Error( 'page_generator_pro_yelp_get_access_token', $response_code . ' ' . $response_message );
            }

            // Get body
            $data = wp_remote_retrieve_body( $response );

            // Bail if not successful
            $data = json_decode( $data );
            if ( ! $data->success ) {
                return new WP_Error( 'page_generator_pro_yelp_get_access_token', $data->data );
            }

            // Store access token
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-yelp', $data->data->access_token );
        }

        // Return access token from settings
        return $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-yelp' );

    }

    /**
     * Performs a GET request
     *
     * @since   1.4.5
     *
     * @param   string  $endpoint   Endpoint
     * @param   array   $arguments  Arguments
     * @return  mixed               WP_Error | array
     */
    private function get( $endpoint, $arguments ) {

        // Get Access Token
        $access_token = $this->get_access_token();

        // If an error was returned, bail
        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }

        // Perform GET Request
        $response = wp_remote_get( $this->endpoint . $endpoint, array(
            'headers'   => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
            'body'      => $arguments,
            'user-agent'=> 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36',
        ) );

        // Bail if an error
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Get body and response code
        $data = json_decode( wp_remote_retrieve_body( $response ) );
        $response_code = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        // Bail if response code isn't 200 OK
        if ( $response_code != 200 ) {
            // Check if the body contains an error message
            if ( isset( $data->error ) ) {
                $message = $data->error->code . ': ';
                if ( isset( $data->error->field ) ) {
                    $message .= 'Field ' . $data->error->field . ': Value ' . $data->error->description;
                } else {
                    $message .= $data->error->description;

                }
                return new WP_Error( 'page_generator_pro_yelp_get_error', $message );       
            }
            
            return new WP_Error( 'page_generator_pro_yelp_get_error', $response_code . ' ' . $response_message );
        }

        // Return data
        return $data;

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
        $name = 'yelp';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}