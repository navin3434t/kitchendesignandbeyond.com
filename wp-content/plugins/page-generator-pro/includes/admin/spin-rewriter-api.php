<?php
/**
 * Spin Rewriter API class.
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.2.9
 */
class Page_Generator_Pro_Spin_Rewriter_API {

    /**
     * Holds the base object.
     *
     * @since   2.2.9
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the API endpoint
     *
     * @since   2.2.9
     */
    private $api_endpoint = 'http://www.spinrewriter.com/action/api';

    /**
     * Holds the account URL where users can obtain their API key
     *
     * @since   2.2.9
     */
    private $account_url = 'https://www.spinrewriter.com/cp-api';

    /**
     * Holds the referal URL to use for users wanting to sign up
     * to Spin Rewriter's service.
     *
     * @since   2.2.9
     */
    private $referral_url = 'https://www.spinrewriter.com/?ref=2c883';

    /**
     * Holds the user's email address
     *
     * @since   2.2.9
     */
    private $email_address;

    /**
     * Holds the user's API key
     *
     * @since   2.2.9
     */
    private $api_key;

    /**
     * Constructor.
     *
     * @since   2.2.9
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Sets the credentials to use for API calls
     *
     * @since   2.2.9
     *
     * @param   string  $email_address  Email Address
     * @param   string  $api_key        API Key
     */
    public function set_credentials( $email_address, $api_key ) {

        $this->email_address = $email_address;
        $this->api_key = $api_key;

    }

    /**
     * Returns the URL where the user can get their API key
     *
     * @since   2.2.9
     *
     * @return  string  Account URL
     */
    public function get_account_url() {

        return $this->account_url;

    }

    /**
     * Returns the URL where the user can register, if they
     * do not yet have an account
     *
     * @since   2.2.9
     *
     * @return  string  Registration URL
     */
    public function get_registration_url() {

        return $this->referral_url;

    }

    /**
     * Returns the valid values for confidence levels,
     * which can be used on API calls.
     *
     * @since   2.2.9
     *
     * @return  array   Confidence Levels
     */
    public function get_confidence_levels() {

        $confidence_levels = array(
            'low'       => __( 'Low', 'page-generator-pro' ),
            'medium'    => __( 'Medium', 'page-generator-pro' ),
            'high'      => __( 'High', 'page-generator-pro' ),
        );

        return $confidence_levels;

    }

    /**
     * Returns the number of made and remaining API calls for the
     * 24 hour period
     *
     * @since   2.2.9
     *
     * @return  mixed   WP_Error | array
     */
    public function api_quota() {

        // Build params
        $params = array(
            'action' => 'api_quota',
        );

        // Return response
        return $this->post( $params );

    }

    /**
     * Returns a spintax version of the given non-spintax text, that can be later processed.
     *
     * @since   2.2.9
     *
     * @param   string  $text               Original non-spintax Text
     * @param   array   $params             Spin Parameters
     *      string  $confidence_level           Confidence Level (low, medium, high)
     *      bool    $auto_protected_terms       Don't spin capitalized words
     *      bool    $nested_spintax             Build Nested Spintax
     *      bool    $auto_sentences             Spin Sentences
     *      bool    $auto_paragraphs            Spin Paragraphs
     *      bool    $auto_new_paragraphs        Add Paragraphs
     *      bool    $auto_sentence_structure    Change Sentence Structure
     * @param   mixed   $protected_words    Protected Words not to spin (false | array)
     * @return  mixed   WP_Error | string    Error | Text with Spintax
     */
    public function text_with_spintax( $text, $params, $protected_words = false ) {

        // Build params
        $params['action'] = 'text_with_spintax';
        $params['text'] = $text;
        $params['protected_terms'] = ( $protected_words != false ? implode( "\n", $protected_words ) : '' );
        $params['spintax_format'] = '{|}';

        // Convert boolean to true/false strings, as required by https://www.spinrewriter.com/cp-api
        foreach ( $params as $key => $value ) {
            if ( ! $value || empty( $value ) ) {
                $params[ $key ] = 'false';
            }
            if ( $value == '1' ) {
                $params[ $key ] = 'true';
            }
        }

        // Send request
        $result = $this->post( $params );

        // Bail if an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Clean up the response, which will have some erronous whitespaces in shortcodes
        $spintax_content = str_replace( '=" ', '="', $result->response );
        $spintax_content = str_replace( '"%  ', '"%', $spintax_content );

        // Return text with spintax
        return $spintax_content;

    }

    /**
     * Private function to perform a POST request
     *
     * @since  2.2.9
     *
     * @param  array   $params     Request Parameters
     * @return mixed               WP_Error | object
     */
    private function post( $params = array() ) {

        // Define timeout, in seconds
        $timeout = 30;

        /**
         * Defines the maximum number of seconds to allow an API request to run.
         *
         * @since   1.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( 'page_generator_pro_spin_rewriter_api_request_timeout', $timeout );


        // Add the email address and API key to the params
        $params['email_address'] = $this->email_address;
        $params['api_key'] = $this->api_key;

        // Send request
        $response = wp_remote_post( $this->api_endpoint, array(
            'body'      => http_build_query( $params ),
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
            return new WP_Error( 'page_generator_pro_spin_rewriter_api', sprintf(
                __( 'Spin Rewriter API: HTTP Error Code %s: %s', 'page-generator-pro' ),
                $response_code,
                wp_remote_retrieve_response_message( $response )
            ) );
        }

        // Get body
        $data = wp_remote_retrieve_body( $response );

        // Decode JSON
        $data = json_decode( $data );

        // Bail if the status is an error
        if ( ! isset( $data->status ) ) { 
            return new WP_Error( 'page_generator_pro_spin_rewriter_api', __( 'Spin Rewriter API: Unable to determine success or failure of request.', 'page-generator-pro' ) );   
        }
        if ( $data->status != 'OK' ) {
            return new WP_Error( 'page_generator_pro_spin_rewriter_api', sprintf(
                __( 'Spin Rewriter API: %s: %s', 'page-generator-pro' ),
                $data->status,
                $data->response
            ) );
        }

        // Return data
        return $data;

    }

}