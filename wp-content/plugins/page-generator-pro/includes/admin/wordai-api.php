<?php
/**
 * WordAI API class.
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.2.9
 */
class Page_Generator_Pro_WordAI_API {

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
    private $api_endpoint = 'http://wordai.com/users/';

    /**
     * Holds the referal URL to use for users wanting to sign up
     *
     * @since   2.2.9
     */
    private $referral_url = 'https://wordai.com/?ref=17haci';

    /**
     * Holds the user's email address
     *
     * @since   2.2.9
     */
    private $email_address;

    /**
     * Holds the user's API key (password)
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
     * Returns the valid values for quality,
     * which can be used on API calls.
     *
     * @since   2.2.9
     *
     * @return  array   Quality Options
     */
    public function get_confidence_levels() {

        $confidence_levels = array(
            'Regular'       => __( 'Regular (Low)', 'page-generator-pro' ),
            'Readable'      => __( 'Readable (Medium)', 'page-generator-pro' ),
            'Very Readable' => __( 'Very Readable (High)', 'page-generator-pro' ),
        );

        return $confidence_levels;

    }

    /**
     * Returns a spintax version of the given non-spintax text, that can be later processed.
     *
     * @since   2.2.9
     *
     * @param   string  $text               Original non-spintax Text
     * @param   array   $params             Spin Parameters
     *      string  $quality                    Quality (Regular,Readable,Very Readable)
     *      bool    $nonested                   Build Nested Spintax
     *      bool    $sentence                   Spin Sentences
     *      bool    $paragraph                  Spin Paragraphs
     * @param   mixed   $protected_words    Protected Words not to spin (false | array)
     * @return  mixed   WP_Error | string    Error | Text with Spintax
     */
    public function text_with_spintax( $text, $params, $protected_words = false ) {

        // Build params
        $params = array(
            's'         => $text,
            'quality'   => $params['quality'],
            'nonested'  => ( $params['nonested'] ? 'off' : 'on' ), 
            'sentence'  => ( $params['sentence'] ? 'on' : 'off' ), 
            'paragraph' => ( $params['paragraph'] ? 'on' : 'off' ),
            'protected' => ( $protected_words != false ? implode( ',', $protected_words ) : '' ),
        );

        // Return response
        $result = $this->post( 'turing-api.php', $params );

        // Bail if an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Return text with spintax
        return $result->text;

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
    private function post( $endpoint, $params = array() ) {

        // Define timeout, in seconds
        $timeout = 30;

        /**
         * Defines the maximum number of seconds to allow an API request to run.
         *
         * @since   1.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( 'page_generator_pro_wordai_api_request_timeout', $timeout );

        // Add the email address and API key to the params
        $params['email'] = $this->email_address;
        $params['pass'] = $this->api_key;
        $params['output'] = 'json';

        // Send request
        $response = wp_remote_post( $this->api_endpoint . $endpoint, array(
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
            return new WP_Error( 'page_generator_pro_wordai_api', sprintf(
                __( 'WordAI API: HTTP Error Code %s: %s', 'page-generator-pro' ),
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
            return new WP_Error( 'page_generator_pro_wordai_api', __( 'WordAI API: Unable to determine success or failure of request.', 'page-generator-pro' ) );   
        }
        if ( $data->status != 'Success' ) {
            return new WP_Error( 'page_generator_pro_wordai_api', sprintf(
                __( 'WordAI API: %s: %s', 'page-generator-pro' ),
                $data->status,
                $data->error
            ) );
        }

        // Return data
        return $data;

    }

}