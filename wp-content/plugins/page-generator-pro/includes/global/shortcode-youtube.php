<?php
/**
 * YouTube Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_YouTube {

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

        return 'youtube';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'     => __( 'YouTube', $this->base->plugin->name ),
            'description'   => __( 'Displays a video from YouTube based on the given Terms.', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/_modules/dashboard/feather/youtube.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'YouTube', $this->base->plugin->name ),
                __( 'Video', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css' => true,
            'requires_js' => false,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_youtube', 'render' ),
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
            'term'       => array(
                'label'                 => __( 'Term', $this->base->plugin->name ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'location'       => array(
                'label'                 => __( 'Location (optional)', $this->base->plugin->name ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. Birmingham, UK', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
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
                    'term',
                    'location',
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
            'term'          => '',
            'location'      => 0,
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

        // If our term is a location, get its latitude and longitude now
        $lat_lng = false;
        if ( $atts['location'] ) {
            $result = $this->base->get_class( 'georocket' )->get_geocode( $atts['location'], $this->base->licensing->get_license_key() );
            
            if ( ! is_wp_error( $result ) && $result->success && $result->data != false ) {
                $lat_lng = array(
                    'latitude'  => $result->data->latitude,
                    'longitude' => $result->data->longitude,
                );
            }
        }

        // Setup YouTube instance
        $youtube = $this->base->get_class( 'youtube' );
        
        // If a YouTube Data API key has been specified, use it instead of the class default.
        $youtube_data_api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-google', 'youtube_data_api_key' );
        if ( ! empty( $youtube_data_api_key ) ) {
            $youtube->api_key = $youtube_data_api_key;
        }

        // Run query
        $videos = $youtube->search( $atts['term'], $lat_lng );
        if ( is_wp_error( $videos ) || ! is_array( $videos ) || count( $videos ) == 0 ) {
            // Couldn't fetch videos from YouTube
            // If a location was specified, remove it and try again
            if ( $lat_lng != false ) {
                $videos = $youtube->search( $atts['term'], false );
            }
        }

        // Bail if an error occured
        if ( is_wp_error( $videos ) ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'YouTube Shortcode Error: %s', 'page-generator-pro' ), $videos->get_error_message() );
            }

            return '';
        }

        // Bail if no videos found
        if ( ! is_array( $videos ) || count( $videos ) == 0 ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return __( 'YouTube Shortcode Error: No videos found', 'page-generator-pro' );
            }

            return '';
        }

        // Pick a video at random from the resultset
        if ( count( $videos ) == 1 ) {
            $video_index = 0;
        } else {
            $video_index = rand( 0, ( count( $videos ) - 1 ) );
        }

        // Get video URL
        $url = $videos[ $video_index ]['url'];

        /**
         * Filter the YouTube Shortcode URL, before returning.
         *
         * @since   1.0.0
         *
         * @param   string  $url            YouTube URL
         * @param   array   $atts           Shortcode Attributes
         * @param   array   $videos         Video Results
         * @param   int     $video_index    Video Index
         */
        $url = apply_filters( 'page_generator_pro_shortcode_youtube', $url, $atts, $videos, $video_index );

        // Return
        // Wrapped in [embed] so Gutenberg parses the Plugin's YouTube block
        return '[embed]' . $url . '[/embed]';

    }

}