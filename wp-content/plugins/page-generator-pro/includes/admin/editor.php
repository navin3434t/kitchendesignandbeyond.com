<?php
/**
 * Editor class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Editor {

    /**
     * Holds the base object.
     *
     * @since 1.2.1
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the screen and section the user is viewing
     *
     * @since   2.6.2
     *
     * @var     array
     */
    public $screen = array(
        'screen'    => false,
        'section'   => false,
    );

    /**
     * Holds the shortcodes to register as TinyMCE Plugins
     *
     * @since   2.6.2
     *
     * @var     array
     */
    public $shortcodes = array();

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

        // Maybe disable the Custom Fields dropdown on the Post Editor
        add_filter( 'postmeta_form_keys', array( $this, 'maybe_remove_custom_fields_meta_box_meta_keys' ), 10, 2 );

        // Maybe change the wp_dropdown_page() select for an input
        add_filter( 'quick_edit_dropdown_pages_args', array( $this, 'maybe_simplify_wp_dropdown_page_query' ), 10 );
        add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'maybe_simplify_wp_dropdown_page_query' ), 10 );
        add_filter( 'wp_dropdown_pages', array( $this, 'maybe_replace_wp_dropdown_page' ), 10, 3 );

        // Add filters to register TinyMCE Plugins
        // Low priority ensures this works with Frontend Page Builders
        add_filter( 'mce_external_plugins', array( $this, 'register_tinymce_plugins' ), 99999 );
        add_filter( 'mce_buttons', array( $this, 'register_tinymce_buttons' ), 99999 );

    }

    /**
     * Defines the Meta Keys to display in the <select> dropdown for the Custom Fields Meta Box.
     *
     * If null is returned, WordPress will perform a DB query to fetch all unique
     * meta keys from the Post Meta table, which can be a slow and expensive
     * query if the WordPress installations contains a lot of post meta data.
     *
     * @since   0.0.1
     *
     * @param   array   $meta_keys  Meta Keys
     * @param   WP_Post $post       WordPress Post
     * @return  array               Meta Keys
     */
    public function maybe_remove_custom_fields_meta_box_meta_keys( $meta_keys, $post ) {

        // Don't do anything if we are not disabling custom fields
        $disable_custom_fields = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'disable_custom_fields', '0' );
        if ( ! $disable_custom_fields ) {
            return $meta_keys;
        }

        // Define the meta keys that you want to return
        // At least one key must be specified, otherwise WordPress will query the DB
        $keys = array(
            '_page_generator_pro_group_id',
        );

        /**
         * Defines the Meta Keys to make available in the Custom Fields dropdown.
         *
         * @since   2.0.7
         *
         * @param   array   $keys       Defined Meta Keys to use
         * @param   array   $meta_keys  Original Meta Keys
         * @param   WP_Post $post       WordPress Post
         */
        $keys = apply_filters( 'page_generator_pro_maybe_remove_custom_fields_meta_box_meta_keys', $keys, $meta_keys, $post );
        
        // Return keys
        return $keys;

    }

    /**
     * If the Plugin is configured to replace wp_dropdown_pages() <select> output with an AJAX <select> or
     * input field, simplify the Page query so it isn't slow and expensive.
     *
     * This query is always run when wp_dropdown_pages() is called (even though we might not use it, 
     * so we always need to optimize it.
     *
     * @since   2.1.6
     *
     * @param   array       $args   Arguments
     * @return  array               Arguments
     */
    public function maybe_simplify_wp_dropdown_page_query( $args ) {

        // Don't do anything if we are not 
        $change_page_dropdown_field = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'restrict_parent_page_depth', '0' );
        if ( ! $change_page_dropdown_field ) {
            return $args;
        }

        // Simplify the query
        $args['depth'] = 1;
        $args['id'] = 1;
        $args['sort_column'] = 'ID';

        // Return
        return $args;

    }

    /**
     * Replaces the wp_dropdown_pages() <select> output with either an AJAX <select>
     * or <input> output for performance.
     *
     * @since   2.1.6
     *
     * @param   string  $output     HTML Output
     * @param   array   $args       Arguments
     * @param   array   $pages      Pages from Query
     * @return  string              HTML Output
     */
    public function maybe_replace_wp_dropdown_page( $output, $args, $pages ) {

        // Don't do anything if we are not 
        $change_page_dropdown_field = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'restrict_parent_page_depth', '0' );
        if ( ! $change_page_dropdown_field ) {
            return $output;
        }

        // Filter the output, depending on how we're changing the Page Parent Output
        switch ( $change_page_dropdown_field ) {
            /**
             * AJAX Select
             */
            case 'ajax_select':
                // Get AJAX <select> HTML
                $output = $this->change_wp_dropdown_pages_output_to_ajax_select_field( $output, $args, $pages );
                break;

            /**
             * Input
             */
            default:
                // Get <input> HTML
                $output = $this->change_wp_dropdown_pages_output_to_input_field( $output, $args, $pages );
                break;
        }

        // Return
        return $output;

    }

    /**
     * Replaces the wp_dropdown_pages() <select> output with an <input> output,
     * for performance.
     *
     * @since   2.1.6
     *
     * @param   string  $output     HTML Output
     * @param   array   $args       Arguments
     * @param   array   $pages      Pages from Query
     * @return  string              HTML Output
     */
    private function change_wp_dropdown_pages_output_to_input_field( $output, $args, $pages ) {

        // Get CSS class
        $class = '';
        if ( ! empty( $r['class'] ) ) {
            $class = " class='" . esc_attr( $r['class'] ) . "'";
        }

        // Build field
        $output = '<input type="text" name="' . esc_attr( $args['name'] ) . '"' . $class . ' id="' . esc_attr( $args['id'] ) . '" value="' . $args['selected'] . '" size="6" />
                    <br /><small>' . __( 'Enter the Page / Post ID', 'page-generator-pro' ) . '</small>';

        // If a parent is specified, fetch its title
        if ( $args['selected'] ) {
            $output .= '<br /><small>' . get_the_title( $args['selected'] ) . '</small>';
        }

        // Return
        return $output;

    }

    /**
     * Replaces the wp_dropdown_pages() <select> output with an AJAX <select> output,
     * for performance.
     *
     * @since   2.1.8
     *
     * @param   string  $output     HTML Output
     * @param   array   $args       Arguments
     * @param   array   $pages      Pages from Query
     * @return  string              HTML Output
     */
    private function change_wp_dropdown_pages_output_to_ajax_select_field( $output, $args, $pages ) {

        $output = '<select name="' . esc_attr( $args['name'] ) . '" class="wpzinc-selectize-search widefat" data-action="page_generator_pro_search_pages" data-args="' . http_build_query( $args ) . '" data-name-field="post_title" data-value-field="ID" data-method="POST" data-output-fields="post_title">';
        if ( $args['selected'] ) {
            $output .= '<option value="' . $args['selected'] . '" selected>' . get_the_title( $args['selected'] ) . '</option>';
        }
        $output .= '</select>';

        // Return
        return $output;

    }

    /**
     * Register JS plugins for the TinyMCE Editor
     *
     * @since   1.0.0
     *
     * @param   array   $plugins    JS Plugins
     * @return  array 		        JS Plugins
     */
    public function register_tinymce_plugins( $plugins ) {

        // Determine the screen that we're on
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // Bail if we're not registering TinyMCE Plugins
        if ( ! $this->should_register_tinymce_plugins( $screen ) ) {
            return $plugins;
        }

        // Depending on the screen we're on, define the shortcodes to register as TinyMCE Plugins
        switch ( $screen['screen'] ) {
            case 'post':
                $shortcodes = $this->base->get_class( 'shortcode' )->get_shortcode_supported_outside_of_content_groups();
                break;

            case 'content_groups':
                $shortcodes = $this->base->get_class( 'shortcode' )->get_shortcodes();
                break;
        }

        // Always register autocomplete helper, as this isn't a shortcode
        $plugins['page_generator_pro_autocomplete']    = $this->base->plugin->url . 'assets/js/min/tinymce-autocomplete-min.js';

        // Register TinyMCE Plugins
        foreach ( $shortcodes as $shortcode => $properties ) {
            $plugins['page_generator_pro_' . str_replace( '-', '_', $shortcode ) ] = $this->base->plugin->url . 'assets/js/min/tinymce-' . $shortcode . '-min.js';
        }

        // Always register Spintax Generator, as this isn't a shortcode
        $plugins['page_generator_pro_spintax_generate'] = $this->base->plugin->url . 'assets/js/min/tinymce-spintax-generate-min.js';
        
        /**
         * Defines the TinyMCE Plugins to register
         *
         * @since   1.0.0
         *
         * @param   array   $plugins    TinyMCE Plugins
         * @param   array   $screen     Screen and Section
         * @param   array   $shortcodes Shortcodes
         */
	    $plugins = apply_filters( 'page_generator_pro_editor_register_tinymce_plugins', $plugins, $screen, $shortcodes );

        // Return filtered results
        return $plugins;

    }

    /**
     * Registers buttons in the TinyMCE Editor
     *
     * @since   1.0.0
     *
     * @param   array   $buttons    Buttons
     * @return  array 		        Buttons
     */
    public function register_tinymce_buttons( $buttons ) {

        // Determine the screen that we're on
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // Bail if we're not registering TinyMCE Plugins
        if ( ! $this->should_register_tinymce_plugins( $screen ) ) {
            return $buttons;
        }

        // Depending on the screen we're on, define the shortcodes to register as TinyMCE Plugins
        switch ( $screen['screen'] ) {
            case 'post':
                $shortcodes = $this->base->get_class( 'shortcode' )->get_shortcode_supported_outside_of_content_groups();
                break;

            case 'content_groups':
                $shortcodes = $this->base->get_class( 'shortcode' )->get_shortcodes();
                break;
        }

        // Register TinyMCE Buttons
        $buttons[] = "|";
        foreach ( $shortcodes as $shortcode => $properties ) {
            $buttons[] = 'page_generator_pro_' . str_replace( '-', '_', $shortcode );
        }

        // Add Spintax Generator
        $buttons[] = 'page_generator_pro_spintax_generate';

        /**
         * Defines the TinyMCE Buttons to register
         *
         * @since   1.0.0
         *
         * @param   array   $plugins    TinyMCE Plugins
         * @param   array   $screen     Screen and Section
         * @param   array   $shortcodes Shortcodes
         */
    	$buttons = apply_filters( 'page_generator_pro_editor_register_tinymce_buttons', $buttons, $screen, $shortcodes );

        // Return filtered results
        return $buttons;

    }

    /**
     * Determines whether TinyMCE Plugins should be registered, by checking if the
     * user is editing a Content Group in the WordPress Admin or a Frontend Page Builder
     *
     * @since   2.5.7
     *
     * @param   array   $screen     Screen and Section
     * @return  mixed               false
     */
    private function should_register_tinymce_plugins( $screen ) {

        // Set a flag to denote whether we should register TinyMCE Plugins
        $should_register_tinymce_plugins = false;

        // Depending on the screen we're on, define the Plugins that we should register
        if ( $screen['screen'] == 'post' && $screen['section'] == 'edit' ) {
            // Only register Shortcodes where register_on_generation_only = false
            $should_register_tinymce_plugins = true;
        } elseif ( $screen['screen'] == 'content_groups' && $screen['section'] == 'edit' ) {
            // Register all Shortcodes
            $should_register_tinymce_plugins = true;
        }

        /**
         * Set a flag to denote whether we should register TinyMCE Plugins
         *
         * @since   2.2.4
         *
         * @param   bool   $should_register_tinymce_plugins    Should Register TinyMCE Plugins
         */
        $should_register_tinymce_plugins = apply_filters( 'page_generator_pro_editor_should_register_tinymce_plugins', $should_register_tinymce_plugins );

        // Return
        return $should_register_tinymce_plugins;

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
        $name = 'editor';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}