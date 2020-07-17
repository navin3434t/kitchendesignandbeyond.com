<?php
/**
 * SpinnerChief API class.
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.3.1
 */
class Page_Generator_Pro_SpinnerChief_API {

    /**
     * Holds the base object.
     *
     * @since   2.3.1
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the API endpoint
     *
     * @since   2.3.1
     */
    private $api_endpoint = 'http://api.spinnerchief.com:9001/';

    /**
     * Holds the account URL where users can obtain their API key
     *
     * @since   2.3.1
     */
    private $account_url = 'http://account.spinnerchief.com/';

    /**
     * Holds the referal URL to use for users wanting to sign up
     * to SpinnerChief's service.
     *
     * @since   2.3.1
     */
    private $referral_url = 'http://www.whitehatbox.com/Agents/SSS?code=0vbtYQiezQ69rR4wkFq6AQs9StMsnOWJZae2sjYH%2BH%2B0DfOPc1i%2BBw==';

    /**
     * Holds the username
     *
     * @since   2.3.1
     */
    private $username;

    /**
     * Holds the password
     *
     * @since   2.3.1
     */
    private $password;

    /**
     * Holds the user's API key
     *
     * @since   2.3.1
     */
    private $api_key = 'apifed603a55050401ca';

    /**
     * Constructor.
     *
     * @since   2.3.1
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
     * @since   2.3.1
     *
     * @param   string  $username       Username
     * @param   string  $password       Password
     */
    public function set_credentials( $username, $password ) {

        $this->username = $username;
        $this->password = $password;

    }

    /**
     * Returns the URL where the user can get their API key
     *
     * @since   2.3.1
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
     * @since   2.3.1
     *
     * @return  string  Registration URL
     */
    public function get_registration_url() {

        return $this->referral_url;

    }

    /**
     * Returns a spintax version of the given non-spintax text, that can be later processed.
     *
     * @since   2.3.1
     *
     * @param   string  $text               Original non-spintax Text
     * @param   array   $params             Spin Parameters
     *      int     $spinfreq                   Spin Frequency
     *      int     $wordquality                Word Quality
     *                                          0: Best Thesaurus
     *                                          1: Better Thesaurus
     *                                          2: Good Thesaurus
     *                                          3: All Thesaurus
     *                                          9: Everyone's Favourite
     *      string  $thesaurus                  Thesaurus Language to Use
     *      bool    $pos                        Use Part of Speech Analysis
     *      bool    $UseGrammarAI               Use Grammar Correction
     *      int     $replacetype                Replacement Method
     *                                          0：Replace phrase and word
     *                                          1：Only replace phrase
     *                                          2: Only replace word
     *                                          3: Replace phrase first, then replace word till the article passes copyscape
     *                                          4: Spin the article to most unique
     *                                          5: Spin the article to most readable
     * @param   mixed   $protected_words    Protected Words not to spin (false | array)
     * @return  mixed   WP_Error | string    Error | Text with Spintax
     */
    public function text_with_spintax( $text, $params = array(), $protected_words = false ) {

        // Build params
        $params['tagprotect'] = '[],(),<- ->';

        if ( $protected_words != false ) {
            $params['protectwords'] = implode( ",", $protected_words );
        }
        
        // Send request
        $result = $this->post( $text, $params );

        // Bail if an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Return text with spintax
        return $result;

    }

    /**
     * Private function to perform a POST request
     *
     * @since  2.3.1
     *
     * @param  array   $params     Request Parameters
     * @return mixed               WP_Error | object
     */
    private function post( $text, $params = array() ) {

        // Define timeout, in seconds
        $timeout = 30;

        /**
         * Defines the maximum number of seconds to allow an API request to run.
         *
         * @since   1.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( 'page_generator_pro_spinnerchief_api_request_timeout', $timeout );

        // Add the username, password and API key to the params
        $params['username'] = $this->username;
        $params['password'] = $this->password;
        $params['apikey'] = $this->api_key;

        // Build URL
        $url = $this->api_endpoint . http_build_query( $params );

        // Send request
        $response = wp_remote_post( $url, array(
            'body'      => $text,
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
            return new WP_Error( 'page_generator_pro_spinnerchief_api', sprintf(
                __( 'SpinnerChief API: HTTP Error Code %s: %s', 'page-generator-pro' ),
                $response_code,
                wp_remote_retrieve_response_message( $response )
            ) );
        }

        // Get body
        $data = wp_remote_retrieve_body( $response );

        // If the response body starts with 'error=', there's an error
        if ( strpos( $data, 'error=' ) !== false ) {
             return new WP_Error( 'page_generator_pro_spinnerchief_api', sprintf(
                __( 'SpinerChief API: %s', 'page-generator-pro' ),
                substr( $data, strpos( $data, 'error=' ) + 6 )
            ) );
        }

        // Return data
        return $data;

    }

}