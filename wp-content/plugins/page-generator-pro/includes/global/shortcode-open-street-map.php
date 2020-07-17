<?php
/**
 * Open Street Map Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_Open_Street_Map {

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

        return 'open-street-map';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'     => __( 'Open Street Map', $this->base->plugin->name ),
            'description'   => __( 'Displays an Open Street Map', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/_modules/dashboard/feather/map.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'Open Street Map', $this->base->plugin->name ),
                __( 'Map', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css' => true,
            'requires_js' => true,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_open_street_map', 'render' ),
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
            'location'       => array(
                'label'                 => __( 'Location', $this->base->plugin->name ),
                'type'                  => 'text',
                'class'                 => 'wpzinc-autocomplete',
            ),
            'country_code'  => array(
                'label'                 => __( 'Country Code', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'common' )->get_countries(),
                'default_value'         => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'country_code', 'US' ),
            ),
            'height'        => array(
                'label'                 => __( 'Height (px)', $this->base->plugin->name ),
                'type'                  => 'number',
                'min'                   => 1,
                'max'                   => 9999,
                'default_value'         => $this->get_default_value( 'height' ),
            ),
            'zoom'          => array(
                'label'                 => __( 'Zoom Level', $this->base->plugin->name ),
                'type'                  => 'number',
                'min'                   => 1,
                'max'                   => 20,
                'default_value'         => $this->get_default_value( 'zoom' ),
                'description'           => __( 'A higher number means a higher zoom level, showing more detail. As a guide, 1 = World; 20 = Buildings', 'page-generator-pro' ),
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
                    'location',
                    'country_code',
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
            'location'      => '',
            'country_code'  => '',
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

        // Get latitude and longitude
        $lat_lng = false;
        $result = $this->base->get_class( 'georocket' )->get_geocode( $atts['location'] . ', ' . $atts['country_code'], $this->base->licensing->get_license_key() );

        // Bail if errors occured
        if ( is_wp_error( $result ) ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Open Street Maps Shortcode Error: %s', 'page-generator-pro' ), $result->get_error_message() );
            }

            return '';
        }

        // Generate random ID for the map
        $map_id = md5( rand() );

        // Build HTML
        $html = '<div id="page-generator-pro-open-street-map-' . $map_id . '" class="page-generator-pro-map" style="height:' . $atts['height'] . 'px;"></div>';
        $html .= '<script type="text/javascript">
var map = L.map(\'page-generator-pro-open-street-map-' . $map_id . '\').setView([' . $result->data->latitude . ', ' . $result->data->longitude . '], ' . $atts['zoom'] . ');
L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {
    attribution: \'' . sprintf( __( 'Map data &copy; %s', 'page-generator-pro' ), '<a href="https://www.openstreetmap.org/" rel="nofollow noreferrer noopener" target="_blank">OpenStreetMap</a>' ) . '\'
}).addTo(map);
</script>';

        /**
         * Filter the Open Street Maps HTML output, before returning.
         *
         * @since   2.2.6
         *
         * @param   string  $html   HTML Output
         * @param   array   $atts   Shortcode Attributes
         */
        $html = apply_filters( 'page_generator_pro_shortcode_open_street_map', $html, $atts );

        // Return
        return $html;

    }

}