<?php
/**
 * Georocket API class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.7.8
 */
class Page_Generator_Pro_Georocket_API extends Page_Generator_Pro_WPZinc_API {

    /**
     * Holds the API endpoint
     *
     * @since   1.7.8
     *
     * @var     string
     */
    public $api_endpoint = 'https://www.wpzinc.com/?georocket_api=1';
    
    /**
     * Returns Countries
     *
     * @since   1.7.8
     *
     * @return  mixed   WP_Error | array
     */
	public function get_countries() {

        // Call API and return results
        return $this->post( 'countries' );

	}

    /**
     * Returns Regions
     *
     * @since   1.7.8
     *
     * @return  mixed    WP_Error | array
     */
    public function get_regions( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'regions', $args );

    }

    /**
     * Returns Counties
     *
     * @since   1.7.8
     *
     * @return  mixed    WP_Error | array
     */
    public function get_counties( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'counties', $args );

    }

    /**
     * Returns Cities
     *
     * @since   1.7.8
     *
     * @return  mixed    WP_Error | array
     */
    public function get_cities( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'cities', $args );

    }

    /**
     * Returns ZIP Codes
     *
     * @since   1.7.8
     *
     * @return  mixed    WP_Error | array
     */
    public function get_zipcodes( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'zipcodes', $args );

    }

    /**
     * Returns Zipcode Districts
     *
     * @since   2.2.0
     *
     * @return  mixed    WP_Error | array
     */
    public function get_zipcode_districts( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'zipcode_districts', $args );

    }

    /**
     * Returns Street Names
     *
     * @since   2.2.0
     *
     * @return  mixed    WP_Error | array
     */
    public function get_street_names( $args ) {

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'street_names', $args );

    }

    /**
     * Returns a Latitude and Longitude for the given Location
     *
     * @since   1.7.8
     *
     * @param   string  $location       Location
     * @param   string  $license_key    Plugin License Key
     * @return  mixed                   WP_Error | array
     */
    public function get_geocode( $location, $license_key ) {

        // Build array
        $args = array(
            // Plugin License Key
            'license_key'   => $license_key,
            'location'      => $location,
        );

        // Remove any arguments that are false
        $args = $this->sanitize_arguments( $args );

        // Call API and return results
        return $this->post( 'geocode', $args );

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
        $name = 'georocket';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}