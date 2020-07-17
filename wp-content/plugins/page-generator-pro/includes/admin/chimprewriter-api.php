<?php
/**
 * ChimpRewriter API class.
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.3.1
 */
class Page_Generator_Pro_ChimpRewriter_API {

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
    private $api_endpoint = 'http://api.chimprewriter.com/';

    /**
     * Holds the account URL where users can obtain their API key
     *
     * @since   2.3.1
     */
    private $account_url = 'https://chimprewriter.com/api/?affiliate=wpzinc';

    /**
     * Holds the referal URL to use for users wanting to sign up
     * to Spin Rewriter's service.
     *
     * @since   2.3.1
     */
    private $referral_url = 'https://chimprewriter.com/api/?affiliate=wpzinc';

    /**
     * Holds the user's email address
     *
     * @since   2.3.1
     */
    private $email_address;

    /**
     * Holds the user's API key
     *
     * @since   2.3.1
     */
    private $api_key;

    /**
     * Holds the Application ID, which can be any string up to 100 characters
     *
     * @since   2.3.1
     */
    private $application_id = 'page-generator-pro';

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
     * Returns the valid values for quality levels,
     * which can be used on API calls.
     *
     * @since   2.3.1
     *
     * @return  array   Quality Levels
     */
    public function get_confidence_levels() {

        $quality_levels = array(
            5 => __( 'Best', 'page-generator-pro' ),
            4 => __( 'Better', 'page-generator-pro' ),
            3 => __( 'Good', 'page-generator-pro' ),
            2 => __( 'Average', 'page-generator-pro' ),
            1 => __( 'Any', 'page-generator-pro' ),
        );

        return $quality_levels;

    }

    /**
     * Returns the valid values for Part of Speech levels,
     * which can be used on API calls.
     *
     * @since   2.3.1
     *
     * @return  array   Quality Levels
     */
    public function get_part_of_speech_levels() {

        $part_of_speech_levels = array(
            3 => __( 'Full', 'page-generator-pro' ),
            2 => __( 'Loose', 'page-generator-pro' ),
            1 => __( 'Extremely Loose', 'page-generator-pro' ),
            0 => __( 'None', 'page-generator-pro' ),
        );

        return $part_of_speech_levels;

    }

    /**
     * Returns a spintax version of the given non-spintax text, that can be later processed.
     *
     * @since   2.3.1
     *
     * @param   string  $text               Original non-spintax Text
     * @param   array   $params             Spin Parameters
     *      int     $quality                    Synonym Replacement Quality (default: 4) (see get_quality_levels() for valid values)
     *      int     $phrasequality              Phrase Replacement Quality (default: 3) (see get_quality_levels() for valid values)
     *      int     $posmatch                   Required Part of Speech Match (default: 3) (see get_part_of_speech_levels() for valid values)
     *      string  $language                   Two letter language code (en only at this time)
     *      bool    $sentencerewrite            Rewrite Sentences (default: 0)
     *      bool    $grammarcheck               Check Grammar (default: 0)
     *      bool    $reorderparagraphs          Reorder Paragraphs (default: 0)
     *      bool    $spinwithinspin             Spin within existing Spintax (default: 0)
     *      bool    $spintidy                   Fix common type grammar mistakes (a/an) (default: 1)
     *      int     $replacefrequency           nth words spun (default: 1)
     *      int     $maxsyns                    Maximum Number of Synonyms to use for word/phrase (default: 10)
     *      int     $excludeoriginal            Exclude Original word from result (default: 0)
     *      int     $instantunique              Replace letters with similar looking chars for copyscape validation (default: 0)
     *      int     $maxspindepth               Maximum Spin Level Deptch (default: 0 = no limit)
     * @param   mixed   $protected_words    Protected Words not to spin (false | array)
     * @return  mixed   WP_Error | string    Error | Text with Spintax
     */
    public function chimprewrite( $text, $params = array(), $protected_words = false ) {

        // Build params
        $params['text'] = $text;
        $params['protectedterms'] = ( $protected_words != false ? implode( ",", $protected_words ) : '' );
        $params['tagprotect'] = '[|]{|}';

        // Send request
        $result = $this->post( 'ChimpRewrite', $params );

        // Return result
        return $result;

    }

    /**
     * Private function to perform a POST request
     *
     * @since  2.3.1
     *
     * @param   string  $endpoint   Endpoint
     * @param   array   $params     Request Parameters
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
        $timeout = apply_filters( 'page_generator_pro_chimprewriter_api_request_timeout', $timeout );


        // Add the email address and API key to the params
        $params['email'] = $this->email_address;
        $params['apikey'] = $this->api_key;
        $params['aid'] = $this->application_id;

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
            return new WP_Error( 'page_generator_pro_chimprewriter_api', sprintf(
                __( 'ChimpRewriter API: HTTP Error Code %s: %s', 'page-generator-pro' ),
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
            return new WP_Error( 'page_generator_pro_chimprewriter_api', __( 'ChimpRewriter API: Unable to determine success or failure of request.', 'page-generator-pro' ) );   
        }
        if ( $data->status != 'success' ) {
            return new WP_Error( 'page_generator_pro_chimprewriter_api', sprintf(
                __( 'ChimpRewriter API: %s: %s', 'page-generator-pro' ),
                $data->status,
                $data->output
            ) );
        }

        // Return data
        return stripslashes( $data->output );

    }

}