<?php
/**
 * Google Map Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_Google_Map {

    /**
     * Holds the base object.
     *
     * @since   2.5.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   2.5.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        add_filter( 'page_generator_pro_shortcode_add_shortcodes', array( $this, 'add_shortcode' ) );

    }

    /**
     * Registers this shortcode / block in Page Generator Pro
     *
     * @since   2.5.1
     */
    public function add_shortcode( $shortcodes ) {

        // Add this shortcode to the array of registered shortcodes
        $shortcodes[ $this->get_name() ] = array_merge(
            $this->get_overview(),
            array(
                'name'          => $this->get_name(),
                'fields'        => $this->get_fields(),
                'tabs'          => $this->get_tabs(),
                'default_values'=> $this->get_default_values(),
            )
        );

        // Return
        return $shortcodes;

    }

    /**
     * Returns this shortcode / block's programmatic name.
     *
     * @since   2.5.1
     */
    public function get_name() {

        return 'google-map';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'         => __( 'Google Map', $this->base->plugin->name ),
            'description'   => __( 'Displays a Google Map', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/_modules/dashboard/feather/map-pin.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'Google Map', $this->base->plugin->name ),
                __( 'Google', $this->base->plugin->name ),
                __( 'Map', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css' => true,
            'requires_js' => false,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_google_map', 'render' ),
        );

    }

    /**
     * Returns this shortcode / block's Fields
     *
     * @since   2.5.1
     */
    public function get_fields() {

        if ( ! $this->base->is_admin_or_frontend_editor() ) {
            return false;
        }

        return array(
            'maptype'       => array(
                'label'         => __( 'Map Type', $this->base->plugin->name ),
                'type'          => 'select',
                'values'        => array(
                    'roadmap'       => __( 'Road Map', 'page-generator-pro' ),
                    'satellite'     => __( 'Satellite', 'page-generator-pro' ),
                    'directions'    => __( 'Driving Directions', 'page-generator-pro' ),
                    'streetview'    => __( 'Street View', 'page-generator-pro' ),
                ),
                'default_value' => $this->get_default_value( 'maptype' ),
            ),
            'location'       => array(
                'label'         => __( 'Location / Origin', $this->base->plugin->name ),
                'type'          => 'text',
                'class'         => 'wpzinc-autocomplete',
            ),
            'destination'   => array(
                'label'         => __( 'Destination', $this->base->plugin->name ),
                'type'          => 'text',
                'class'         => 'wpzinc-autocomplete',
                'description'   => __( 'If Map Type = Directions, specify the Destination here.  The Location field above is used as the Origin / Starting Point.', 'page-generator-pro' ),
                'condition'     => array(
                    'key'           => 'maptype',
                    'value'         => 'directions',
                    'comparison'    => '==',
                ),
            ),
            'country_code'  => array(
                'label'         => __( 'Country Code', $this->base->plugin->name ),
                'type'          => 'select',
                'values'        => $this->base->get_class( 'common' )->get_countries(),
                'default_value' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'country_code', 'US' ),
                'condition'     => array(
                    'key'           => 'maptype',
                    'value'         => 'streetview',
                    'comparison'    => '==',
                ),
            ),
            'term'          => array(
                'label'         => __( 'Term', $this->base->plugin->name ),
                'type'          => 'text',
                'class'         => 'wpzinc-autocomplete',
                'description'   => __( 'Optional: If defined, will plot all items (e.g. businesses) matching the Term and Location', 'page-generator-pro' ),
                'condition'     => array(
                    'key'           => 'maptype',
                    'value'         => array(
                        'roadmap',
                        'satellite',
                    ),
                    'comparison'    => 'IN',
                ),
            ),
            'height'        => array(
                'label'         => __( 'Height (px)', $this->base->plugin->name ),
                'type'          => 'number',
                'min'           => 1,
                'max'           => 9999,
                'step'          => 1,
                'default_value' => $this->get_default_value( 'height' ),
            ),
            'zoom'          => array(
                'label'         => __( 'Zoom Level', $this->base->plugin->name ),
                'type'          => 'number',
                'min'           => 1,
                'max'           => 20,
                'step'          => 1,
                'default_value' => $this->get_default_value( 'zoom' ),
                'description'   => __( 'A higher number means a higher zoom level, showing more detail. As a guide, 1 = World; 20 = Buildings', 'page-generator-pro' ),
            ),
        );

    }

    /**
     * Returns this shortcode / block's UI Tabs
     *
     * @since   2.5.1
     */
    public function get_tabs() {

        if ( ! $this->base->is_admin_or_frontend_editor() ) {
            return false;
        }

        return array(
            'general' => array(
                'label'     => __( 'General', 'page-generator-pro' ),
                'fields'    => array(
                    'maptype',
                    'location',
                    'destination',
                    'country_code',
                    'term',
                    'height',
                    'zoom',
                ),
            ),
        );

    }

    /**
     * Returns this shortcode / block's Default Values
     *
     * @since   2.5.1
     */
    public function get_default_values() {

        return array(
            'maptype'       => 'roadmap',
            'location'      => '',
            'destination'   => '',
            'country_code'  => '',
            'term'          => '',
            'height'        => 250,
            'zoom'          => 14,
        );

    }

    /**
     * Returns the given shortcode / block's field's Default Value
     *
     * @since   2.5.1
     */
    public function get_default_value( $field ) {

        $defaults = $this->get_default_values();
        if ( isset( $defaults[ $field ] ) ) {
            return $defaults[ $field ];
        }

        return '';
    }

    /**
     * Returns this shortcode / block's output
     *
     * @since   2.5.1
     *
     * @param  array   $atts   Shortcode Attributes
     * @return string          Output
     */
    public function render( $atts ) {

        // Parse shortcode attributes, defining fallback defaults if required
        $atts = shortcode_atts( $this->get_default_values(), $atts, $this->base->plugin->name . '-' . $this->get_name() );

        // Build iframe arguments and determine endpoint
        $args = false;
        switch ( $atts['maptype'] ) {

            /**
             * Street View
             */
            case 'streetview':
                // Get latitude and longitude
                $lat_lng = false;
                $result = $this->base->get_class( 'georocket' )->get_geocode( $atts['location'] . ', ' . $atts['country_code'], $this->base->licensing->get_license_key() );

                // Bail if errors occured
                if ( is_wp_error( $result ) ) {
                    if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                        return sprintf( __( 'Google Maps Shortcode Error: %s', 'page-generator-pro' ), $result->get_error_message() );
                    }

                    return '';
                }

                if ( ! $result->data ) {
                    if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                        return sprintf( __( 'Google Maps Shortcode Error: %s', 'page-generator-pro' ), 'No Data in Geocode Response' );
                    }

                    return '';
                }

                // If here, we have a latitude and longitude
                $endpoint = $atts['maptype'];
                $args = array(
                    'location' => $result->data->latitude . ',' . $result->data->longitude,
                );
                break;

            /**
             * Directions
             */
            case 'directions':
                $endpoint = $atts['maptype'];
                $args = array(
                    'origin'        => $atts['location'],
                    'destination'   => $atts['destination'],
                );
                break;

            /**
             * Road Map or Satellite
             */
            default:
                $endpoint = 'place';
                $args = array(
                    'q'         => ( ! empty( $atts['term'] ) ? $atts['term'] . ' in ' . $atts['location'] : $atts['location'] ),
                    'zoom'      => $atts['zoom'],
                    'maptype'   => $atts['maptype'],
                );
                break;

        }

        // Fetch API Key
        $google_maps_api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-google', 'google_maps_api_key' );
        if ( empty( $google_maps_api_key ) ) {
            $google_maps_api_key = 'AIzaSyCNTEOso0tZG6YMSJFoaJEY5Th1stEWrJI';
        }
        $args['key'] = $google_maps_api_key;

        // Build URL
        $url = 'https://www.google.com/maps/embed/v1/' . $endpoint . '?' . http_build_query( $args );

        /**
         * Filter the Google Maps iFrame URL, before output.
         *
         * @since   2.0.4
         *
         * @param   string  $url        URL with Arguments
         * @param   array   $atts       Shortcode Attributes
         * @param   array   $args       URL Arguments
         * @param   string  $endpoint   URL Endpoint
         */
        $url = apply_filters( 'page_generator_pro_shortcode_google_maps_url', $url, $atts, $args, $endpoint );

        // Build HTML using the URL
        $html = '<iframe class="page-generator-pro-map" width="100%" height="' . $atts['height'] . '" frameborder="0" style="border:0" src="' . $url . '" allowfullscreen></iframe>';

        /**
         * Filter the Google Maps HTML output, before returning.
         *
         * @since   1.0.0
         *
         * @param   string  $html   HTML Output
         * @param   array   $atts   Shortcode Attributes
         */
        $html = apply_filters( 'page_generator_pro_shortcode_google_maps', $html, $atts );

        // Return
        return $html;

    }

}