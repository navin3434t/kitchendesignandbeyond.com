<?php
/**
 * Shortcode class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Shortcode {

    /**
     * Holds the base object.
     *
     * @since   1.2.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   1.0.0
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Hooks and Filters
        add_action( 'init', array( $this, 'add_shortcodes' ), 10, 1 );
        add_action( 'wp_head', array( $this, 'maybe_load_js' ) );
        add_filter( 'wp_get_custom_css', array( $this, 'maybe_load_css' ), 10, 2 );
        add_filter( 'the_content', array( $this, 'maybe_change_css_prefix_content' ) );

    }

    /**
     * Registers the shortcodes used by this plugin, depending on whether we're running
     * content generation for a Group or not.
     *
     * @since   1.2.0
     *
     * @param   bool    $generating_group   Generating Group
     */
    public function add_shortcodes( $generating_group = false ) {

        // Get shortcodes
        $shortcodes = $this->get_shortcodes();

        // Bail if no shortcodes are available
        if ( ! is_array( $shortcodes ) || count( $shortcodes ) == 0 ) {
            return;
        }

        // Get CSS Prefix
        $css_prefix = $this->get_css_prefix();

        // Iterate through shortcodes, registering them
        foreach ( $shortcodes as $shortcode => $properties ) {
           
            // Skip if this shortcode should only be registered WHEN generating content
            if ( ! $generating_group && $properties['register_on_generation_only'] ) {
                continue;
            }

            // Skip if this shortcode should only be registered when NOT generating content
            if ( $generating_group && ! $properties['register_on_generation_only'] ) {
                continue;
            }

            // Register the shortcode
            add_shortcode( $this->base->plugin->name . '-' . $shortcode, array(
                $this->base->get_class( $properties['render_callback'][0] ), // e.g. $this->base->get_class( 'shortcode_google_map' )
                $properties['render_callback'][1] // e.g. 'render'
            ) );

            // If a CSS Prefix is specified, and this is the Related Links shortcode, register the actual CSS prefix
            // as an additional shortcode
            if ( $css_prefix == $this->base->plugin->name ) {
                continue;
            }
            if ( $shortcode != 'related-links' ) {
                continue;
            }
            add_shortcode( $css_prefix . '-' . $shortcode, array(
                $this->base->get_class( $properties['render_callback'][0] ), // e.g. $this->base->get_class( 'shortcode_google_map' )
                $properties['render_callback'][1] // e.g. 'render'
            ) );
               
        }

    }

    /**
     * Outputs the contents of this Plugin's frontend.js file to the WordPress
     * header as an inline script, if OpenStreetMap markup is present. 
     *
     * We do this instead of enqueueing JS to avoid what people believe is
     * the 'footprint' problem.
     *
     * @since   2.3.4
     */
    public function maybe_load_js() {

        global $post;

        // Bail if in the admin interface
        if ( function_exists( 'is_admin' ) && is_admin() ) {
            return;
        }

        // Bail if Post object is empty
        if ( is_null( $post ) || ! isset( $post->post_content ) ) {
            return;
        }

        // Get shortcodes requiring JS
        $shortcodes_requiring_js = $this->get_shortcodes_requiring_js();

        // If no shortcodes require JS,bail
        if ( ! $shortcodes_requiring_js ) {
            return;
        }

        // Iterate through shortcodes, returning frontend JS
        // if a shortcode or shortcode HTML is found
        foreach ( $shortcodes_requiring_js as $shortcode_name ) {
            if ( strpos( $post->post_content, $shortcode_name ) ) {
                // Fetch Frontend JS
                $plugin_js = file_get_contents( $this->base->plugin->folder . '/assets/js/min/frontend-min.js' );

                // Bail if none found
                if ( empty( $plugin_js ) || ! $plugin_js ) {
                    return;
                }

                // Output
                echo '<script>' . $plugin_js . '</script>';
                return;
            }
        }

        // If here, we don't need to load any frontend JS
        return;

    }

    /**
     * Appends the contents of this Plugin's frontend.css file to the WordPress
     * Theme Customizer Additional CSS.
     *
     * We do this instead of enqueueing CSS to avoid what people believe is
     * the 'footprint' problem.
     *
     * @since   2.0.4
     *
     * @param   string  $customizer_css     Customizer CSS
     * @param   string  $stylesheet         Stylesheet URI
     * @return  string                      Customizer CSS
     */
    public function maybe_load_css( $customizer_css, $stylesheet ) {

        global $post;

        // Bail if in the admin interface
        if ( function_exists( 'is_admin' ) && is_admin() ) {
            return $customizer_css;
        }

        // Bail if Post object is empty
        if ( is_null( $post ) || ! isset( $post->post_content ) ) {
            return $customizer_css;
        }

        // Get shortcodes requiring CSS
        $shortcodes_requiring_css = $this->get_shortcodes_requiring_css();

        // If no shortcodes require CSS, just return the customizer CSS
        if ( ! $shortcodes_requiring_css ) {
            return $customizer_css;
        }

        // Iterate through shortcodes, returning frontend CSS with customizer CSS
        // if a shortcode or shortcode HTML is found
        foreach ( $shortcodes_requiring_css as $shortcode_name ) {
            if ( strpos( $post->post_content, $shortcode_name ) ) {
                return $this->append_css_to_customizer_css( $customizer_css );
            }
        }

        // If here, we don't need to load any frontend CSS
        // Just return the customizer CSS
        return $customizer_css;
    
    }

    /**
     * Appends this Plugin's CSS to the Theme Customizer CSS, changing
     * the CSS Prefix if necessary
     *
     * @since   2.3.4
     *
     * @param   string  $customizer_css     Customizer CSS
     * @return  string                      Customizer CSS
     */
    private function append_css_to_customizer_css( $customizer_css ) {

        // Fetch Frontend CSS
        $plugin_css = file_get_contents( $this->base->plugin->folder . '/assets/css/frontend.css' );

        // Change prefixes, if required
        $plugin_css = $this->change_css_prefix( $plugin_css );

        // If the Customizer CSS already contains the Frontend CSS, bail
        if ( strpos( $customizer_css, $plugin_css ) !== false ) {
            return $customizer_css;
        }

        // Append CSS
        return $customizer_css . "\n" . $plugin_css;

    }

    /**
     * Replaces CSS prefix in the content
     *
     * @since   2.0.3
     *
     * @param   string  $content    Content
     * @return  string              Content
     */
    public function maybe_change_css_prefix_content( $content ) {

        // Change CSS prefix
        $content = $this->change_css_prefix( $content );

        // Return
        return $content;

    }

    /**
     * Returns an array comprising of plugin specific Shortcodes,
     * and their attributes.
     *
     * This is used by both TinyMCE, Gutenberg and Page Builders, so that Shortcodes
     * are registered as Shortcodes, Blocks and Page Builder Elements.
     *
     * @since   2.0.5
     *
     * @return  mixed   bool | array
     */
    public function get_shortcodes() {

        return apply_filters( 'page_generator_pro_shortcode_add_shortcodes', array() );

    }

    /**
     * Returns an array comprising of plugin specific Shortcodes,
     * and their attributes, for shortcodes that register on generation only
     *
     * This is used by both TinyMCE, Gutenberg and Page Builders, so that Shortcodes
     * are registered as Shortcodes, Blocks and Page Builder Elements.
     *
     * @since   2.0.5
     *
     * @return  mixed   bool | array
     */
    public function get_shortcode_supported_outside_of_content_groups() {

        return apply_filters( 'page_generator_pro_shortcode_add_shortcodes_outside_of_content_groups', array() );

    }

    /**
     * Returns the given shortcode's properties.
     *
     * @since   2.0.5
     */
    public function get_shortcode( $name ) {

        // Get shortcodes
        $shortcodes = $this->get_shortcodes();

        // Bail if no shortcodes are registered
        if ( ! is_array( $shortcodes ) ) {
            return false;
        }
        if ( ! isset( $shortcodes[ $name ] ) ) {
            return false;
        }

        return $shortcodes[ $name ];

    }

    /**
     * Returns Shortcode Names requiring CSS.
     *
     * This can be used to determine if we need to load frontend CSS for a given Page.
     *
     * @since   2.3.4
     *
     * @return  array   Shortcode Names
     */
    private function get_shortcodes_requiring_css() {

        // Fetch all shortcodes
        $shortcodes = $this->get_shortcodes();

        // Bail if the given shortcode name has not been registered
        if ( ! is_array( $shortcodes ) || count( $shortcodes ) == 0 ) {
            return false;
        }  

        $shortcodes_requiring_css = array();
        foreach ( $shortcodes as $shortcode_name => $shortcode ) {
            if ( ! $shortcode['requires_css'] ) {
                continue;
            }

            $shortcodes_requiring_css[] = $shortcode_name;
        }

        // If no shortcodes, return false
        if ( ! count( $shortcodes_requiring_css ) ) {
            return false;
        }

        // Return shortcode names requiring CSS
        return $shortcodes_requiring_css;

    }

    /**
     * Returns Shortcode Names requiring JS.
     *
     * This can be used to determine if we need to load frontend JS for a given Page.
     *
     * @since   2.3.4
     *
     * @return  array   Shortcode Names
     */
    public function get_shortcodes_requiring_js() {

        // Fetch all shortcodes
        $shortcodes = $this->get_shortcodes();
        
        // Bail if the given shortcode name has not been registered
        if ( ! is_array( $shortcodes ) || count( $shortcodes ) == 0 ) {
            return false;
        }  

        $shortcodes_requiring_js = array();
        foreach ( $shortcodes as $shortcode_name => $shortcode ) {
            if ( ! $shortcode['requires_js'] ) {
                continue;
            }

            $shortcodes_requiring_js[] = $shortcode_name;
        }

        // If no shortcodes, return false
        if ( ! count( $shortcodes_requiring_js ) ) {
            return false;
        }

        // Return shortcode names requiring JS
        return $shortcodes_requiring_js;

    }

    /**
     * Helper function to try and fetch the Group ID
     *
     * This is then used by Shortcodes to store Attachments against
     * the given Group ID.
     *
     * @since   2.4.1
     *
     * @return  int     Group ID
     */
    public function get_group_id() {

        global $page_generator_pro_group_id;

        if ( ! empty( $page_generator_pro_group_id ) ) {
            return $page_generator_pro_group_id;
        }

        return 0;

    }

    /**
     * Helper function to try and fetch the Index
     *
     * This is then used by Shortcodes to store Attachments against
     * the given Generation Index.
     *
     * @since   2.4.1
     *
     * @return  int     Index
     */
    public function get_index() {

        global $page_generator_pro_index;

        if ( ! empty( $page_generator_pro_index ) ) {
            return $page_generator_pro_index;
        }

        return 0;

    }

    /**
     * Returns the CSS prefix to use.
     *
     * @since   2.0.3
     *
     * @return  string  CSS Prefix
     */
    private function get_css_prefix() {

        // Get prefix
        $css_prefix = trim( $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'css_prefix' ) );
        
        // Fallback to plugin name if no prefix specified
        if ( empty( $css_prefix ) ) {
            $css_prefix = $this->base->plugin->name;
        }

        /**
         * Returns the CSS prefix to use.
         *
         * @since   2.0.3
         *
         * @param   string    $css_prefix   CSS Prefix to use
         */
        $css_prefix = apply_filters( 'page_generator_pro_shortcode_get_css_prefix', $css_prefix );

        // Return
        return $css_prefix;

    }

    /**
     * Changes the default Plugin CSS Prefix for the one specified in the Plugin Settings
     *
     * @since   2.0.3
     *
     * @param   string  $content    Content
     * @return  string              Amended Content
     */
    private function change_css_prefix( $content ) {

        // Get CSS Prefix
        $css_prefix = $this->get_css_prefix();

        // Bail if it matches the Plugin Name
        if ( $css_prefix == $this->base->plugin->name ) {
            return $content;
        }

        // Replace prefix
        $content = str_replace( $this->base->plugin->name, $css_prefix, $content );

        // Return
        return $content;

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
        $name = 'shortcode';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}