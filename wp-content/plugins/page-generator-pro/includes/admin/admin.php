<?php
/**
 * Administration class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Admin {

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

        // Check Plugin Setup
        add_action( 'init', array( $this, 'check_plugin_setup' ) );

        // Admin Notices
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );

        // Admin CSS, JS and Menu
        add_filter( 'wpzinc_admin_body_class', array( $this, 'admin_body_class' ) ); // WordPress Admin
        add_filter( 'body_class', array( $this, 'body_class' ) ); // Frontend Editors

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_css' ) ); // WordPress Admin
        add_action( 'wp_enqueue_scripts', array( $this, 'admin_scripts_css' ) ); // Frontend Editors

        add_action( 'admin_menu', array( $this, 'admin_menu' ), 8 );
        add_action( 'parent_file', array( $this, 'admin_menu_hierarchy_correction' ), 999 );

        // Keywords: Bulk and Row Actions
        add_filter( 'set-screen-option', array( $this, 'set_keyword_screen_options' ), 10, 3 );
        add_action( 'current_screen', array( $this, 'run_keyword_table_bulk_actions' ) );
        add_action( 'current_screen', array( $this, 'run_keyword_table_row_actions' ) );

        // Settings Panels
        add_filter( 'page_generator_pro_setting_panel', array( $this, 'register_settings_panel' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-general', array( $this, 'settings_screen_general' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-google', array( $this, 'settings_screen_google' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-generate', array( $this, 'settings_screen_generate' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-open-weather-map', array( $this, 'settings_screen_open_weather_map' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-pexels', array( $this, 'settings_screen_pexels' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-pixabay', array( $this, 'settings_screen_pixabay' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-spintax', array( $this, 'settings_screen_generate_spintax' ), 1 );
        add_action( 'page_generator_pro_setting_panel-page-generator-pro-georocket', array( $this, 'settings_screen_georocket' ), 1 );

        // Localization
        add_action( 'plugins_loaded', array( $this, 'load_language_files' ) );

    }

    /**
     * Displays a dismissible WordPress notification if required functions aren't available in PHP.
     *
     * @since   2.6.3
     */
    public function check_plugin_setup() {

        // Define array of errors
        $errors = array();

        // Define required PHP functions that might not be available on all PHP installations
        $required_functions = array(
            'mb_convert_case'       => __( 'Install the mbstring and gd PHP libraries.', 'page-generator-pro' ),
            'mb_detect_encoding'    => __( 'Install the mbstring and gd PHP libraries.', 'page-generator-pro' ),
            'mb_convert_encoding'   => __( 'Install the mbstring and gd PHP libraries.', 'page-generator-pro' ),
            'mb_strtoupper'         => __( 'Install the mbstring and gd PHP libraries.', 'page-generator-pro' ),
        );

        // Iterate through required functions
        foreach ( $required_functions as $required_function => $resolution ) {
            if ( ! function_exists( $required_function ) ) {
                $errors[] = sprintf(
                    __( 'The <code>%s()</code> PHP function does not exist. %s', 'page-generator-pro' ),
                    $required_function,
                    $resolution
                );
            }
        }

        // If no errors, nothing to show
        if ( ! count( $errors ) ) {
            return;
        }

        // Output errors
        $this->base->get_class( 'notices' )->add_error_notice(
            sprintf( 
                __( '%s detected the following issues that need resolving to ensure correct working functionality:<br />%s', 'page-generator-pro' ),
                $this->base->plugin->displayName,
                implode( '<br />', $errors )
            )
        );

    }

    /**
     * Checks the transient to see if any admin notices need to be output now.
     *
     * @since   1.2.3
     */
    public function admin_notices() {

        // Determine the screen that we're on
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // If we're not on a plugin screen, exit
        if ( ! $screen['screen'] ) {
            return;
        }

        // Output notices
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );
        $this->base->get_class( 'notices' )->output_notices();

    }

    /**
     * Registers screen names that should add the wpzinc class to the <body> tag
     *
     * @since   1.6.1
     *
     * @param   array   $screens    Screen Names
     * @return  array               Screen Names
     */
    public function admin_body_class( $screens ) {

        // Add Post Types
        $screens[] = $this->base->get_class( 'taxonomy' )->taxonomy_name;

        /**
         * Registers screen names that should add the wpzinc class to the <body> tag
         *
         * @since   2.5.7
         *
         * @param   array   $screens    Screen Names
         * @return  array               Screen Names
         */
        $screens = apply_filters( 'page_generator_pro_admin_body_class', $screens );

        // Return
        return $screens;

    }

    /**
     * Defines CSS classes for the frontend output
     *
     * @since   1.6.1
     *
     * @param   array   $classes    CSS Classes
     * @return  array               CSS Classes
     */
    public function body_class( $classes ) {

        $classes[] = 'wpzinc';

        return $classes;

    }

    /**
     * Enqueues CSS and JS
     *
     * @since   1.0.0
     */
    public function admin_scripts_css() {

        global $post;

        // CSS - always load, admin / frontend editor wide
        if ( $this->base->is_admin_or_frontend_editor() ) {
            wp_enqueue_style( $this->base->plugin->name . '-admin', $this->base->plugin->url . 'assets/css/admin.css', array(), $this->base->plugin->version );
        }

        // Determine the screen that we're on
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // If we're not on a plugin screen, exit
        if ( ! $screen['screen'] ) {
            return;
        }

        // (Re)register dashboard scripts and enqueue CSS for frontend editors, which won't have registered these yet
        $this->base->dashboard->admin_scripts_css();

        // CSS - always load
        // Some WordPress styles are enqueued (again) for Frontend Editors that otherwise wouldn't call them
        wp_enqueue_style( 'buttons-css' );
        wp_enqueue_style( 'forms' );
        
        // @TODO Do we need this?!
        add_editor_style( $this->base->plugin->url . 'assets/css/admin.css' );

        // If SCRIPT_DEBUG is enabled, load unminified versions
        if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            $ext = '';
        } else {
            $ext = 'min';
        }

        // JS - register scripts we might use
        wp_register_script( $this->base->plugin->name . '-autocomplete', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'autocomplete' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-conditional-fields', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'conditional-fields' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-generate-content', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'generate-content' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-gutenberg', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'gutenberg' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-keywords-generate-locations', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'keywords-generate-locations' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-keywords-generate-phone-area-codes', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'keywords-generate-phone-area-codes' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-page-builders', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'page-builders' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-selectize', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'selectize' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-settings', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'settings' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        wp_register_script( $this->base->plugin->name . '-synchronous-ajax', $this->base->plugin->url . 'assets/js/' . ( $ext ? 'min/' : '' ) . 'synchronous-ajax' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->base->plugin->version, true );
        
        // If here, we're on a plugin screen
        // Conditionally load scripts and styles depending on which section of the Plugin we're loading
        switch ( $screen['screen'] ) {
            /**
             * Settings
             */
            case 'settings':
                // JS: WP Zinc
                wp_enqueue_script( 'wpzinc-admin-conditional' );
                wp_enqueue_script( 'wpzinc-admin' );   

                // JS: Plugin
                wp_enqueue_script( $this->base->plugin->name . '-settings' );     
                break;

            /**
             * Keywords
             */
            case 'keywords':
                switch ( $screen['section'] ) {
                    /**
                     * Keywords: WP_List_Table
                     */
                    case 'wp_list_table':
                        break;

                    /**
                     * Keywords: Add / Edit
                     */
                    case 'edit':
                        break;

                    /**
                     * Keywords: Generate Locations
                     */
                    case 'generate_locations':
                        // CSS: WP Zinc
                        wp_enqueue_style( 'wpzinc-admin-selectize' );

                        // JS: WP Zinc
                        wp_enqueue_script( 'jquery-ui-sortable' );
                        wp_enqueue_script( 'wpzinc-admin-modal' ); 
                        wp_enqueue_script( 'wpzinc-admin-selectize' );
                       
                        // JS: Plugin
                        wp_enqueue_script( $this->base->plugin->name . '-keywords-generate-locations' );
                        wp_localize_script( $this->base->plugin->name . '-keywords-generate-locations', 'page_generator_pro_keywords_generate_locations', array(
                            'titles'    => array(
                                'keywords_generate_location_request'        => __( 'Building Location Terms', 'page-generator-pro' ),
                            ),
                            'messages'  => array(
                                'keywords_generate_location_error'          => __( 'An error occured. Please try again.', 'page-generator-pro' ),
                                'keywords_generate_location_request'        => __( 'Sending Request', 'page-generator-pro' ),
                                'keywords_generate_location_response'       => __( 'Added Location Terms to Keyword', 'page-generator-pro' ),
                                'keywords_generate_location_request_next'   => __( 'Additional Location Terms found.', 'page-generator-pro' ),
                                'keywords_generate_location_success'        =>  __( 'Locations Keyword generated successfully.', 'page-generator-pro' ),
                            ),
                            'options'   => array(
                                'output_types' => $this->base->get_class( 'common' )->get_locations_output_types(),
                            )
                        ) );

                        wp_enqueue_script( $this->base->plugin->name . '-selectize' );
                        wp_localize_script( $this->base->plugin->name . '-selectize', 'page_generator_pro_selectize', array(
                            'fields'   => $this->base->get_class( 'common' )->get_selectize_enabled_fields(),
                        ) );
                        break;

                    /**
                     * Keywords: Generate Phone Area Codes
                     */
                    case 'generate_phone_area_codes':
                        // CSS: WP Zinc
                        wp_enqueue_style( 'wpzinc-admin-selectize' );

                        // JS: WP Zinc
                        wp_enqueue_script( 'jquery-ui-sortable' );
                        wp_enqueue_script( 'wpzinc-admin-modal' );
                        wp_enqueue_script( 'wpzinc-admin-selectize' );

                        // JS: Plugin
                        wp_enqueue_script( $this->base->plugin->name . '-keywords-generate-phone-area-codes' );
                        wp_enqueue_script( $this->base->plugin->name . '-selectize' );
                        wp_localize_script( $this->base->plugin->name . '-selectize', 'page_generator_pro_selectize', array(
                            'fields'   => $this->base->get_class( 'common' )->get_selectize_enabled_fields(),
                        ) );
                        break;
                }
                break;

            /**
             * Content: Groups
             */
            case 'content_groups':
                // JS: WP Zinc
                wp_enqueue_script( 'wpzinc-admin-modal' );
                wp_enqueue_script( 'wpzinc-admin-tabs' );

                // JS: Plugin
                wp_enqueue_script( $this->base->plugin->name . '-generate-content' );

                // Get localization strings
                $localization = $this->base->get_class( 'groups_ui' )->get_titles_and_messages();

                // Add data to localization depending on the screen we're viewing
                switch ( $screen['section'] ) {
                    /**
                     * Content: Groups: Add / WP_List_Table
                     */
                    case 'wp_list_table':
                        break;

                    /**
                     * Content: Groups: Edit
                     */
                    case 'edit':
                        // Prevents errors with meta boxes and Yoast
                        wp_enqueue_media();

                        // CSS: WP Zinc
                        wp_enqueue_style( 'wpzinc-admin-selectize' );

                        // JS: WordPress
                        wp_enqueue_script( 'jquery-ui-autocomplete' );
                        wp_enqueue_script( 'jquery-ui-sortable' );

                        // JS: WP Zinc
                        wp_enqueue_script( 'wpzinc-admin-conditional' );
                        wp_enqueue_script( 'wpzinc-admin-inline-search' );
                        wp_enqueue_script( 'wpzinc-admin-selectize' );
                        wp_enqueue_script( 'wpzinc-admin-tags' );
                        wp_enqueue_script( 'wpzinc-admin-tinymce-modal' );
                        wp_enqueue_script( 'wpzinc-admin' );

                        // JS: Plugin
                        wp_enqueue_script( $this->base->plugin->name . '-autocomplete' );
                        wp_enqueue_script( $this->base->plugin->name . '-conditional-fields' );
                        wp_enqueue_script( $this->base->plugin->name . '-gutenberg' );
                        wp_enqueue_script( $this->base->plugin->name . '-page-builders' );
                        wp_enqueue_script( $this->base->plugin->name . '-selectize' );

                        // Localize Autocomplete
                        wp_localize_script( $this->base->plugin->name . '-autocomplete', 'page_generator_pro_autocomplete', array(
                            'keywords' => $this->base->get_class( 'keywords' )->get_keywords_and_columns( true ),
                            'fields'   => $this->base->get_class( 'common' )->get_autocomplete_enabled_fields(),
                        ) );

                        // Localize Gutenberg
                        wp_localize_script( $this->base->plugin->name . '-gutenberg', 'page_generator_pro_gutenberg', array(
                            'keywords'  => $this->base->get_class( 'keywords' )->get_keywords_and_columns( true ),
                            'shortcodes'=> $this->base->get_class( 'shortcode' )->get_shortcodes(),
                        ) );

                        // Localize Selectize
                        wp_localize_script( $this->base->plugin->name . '-selectize', 'page_generator_pro_selectize', array(
                            'fields'   => $this->base->get_class( 'common' )->get_selectize_enabled_fields(),
                        ) );

                        // Get localization strings
                        $localization['post_id'] = ( isset( $post->ID ) ? $post->ID : false );
                        $localization['taxonomy_is_hierarchical'] = $this->base->get_class( 'common' )->get_taxonomies_hierarchical_status();
                        break;
                }

                // Apply Localization
                wp_localize_script( $this->base->plugin->name . '-generate-content', 'page_generator_pro_generate_content', $localization );
                break;

            /**
             * Content: Terms
             */
            case 'content_terms':
                // JS: WordPress
                wp_enqueue_script( 'jquery-ui-autocomplete' );
                wp_enqueue_script( 'wpzinc-admin-modal' );

                // JS: Plugin
                wp_enqueue_script( $this->base->plugin->name . '-autocomplete' );
                wp_enqueue_script( $this->base->plugin->name . '-generate-content' );

                // Get localization strings
                $localization = $this->base->get_class( 'groups_terms_ui' )->get_titles_and_messages();
                $localization['taxonomy_is_hierarchical'] = $this->base->get_class( 'common' )->get_taxonomies_hierarchical_status();

                // Localize autocomplete
                wp_localize_script( $this->base->plugin->name . '-autocomplete', 'page_generator_pro_autocomplete', array(
                    'keywords' => $this->base->get_class( 'keywords' )->get_keywords_and_columns( true ),
                    'fields'   => $this->base->get_class( 'common' )->get_autocomplete_enabled_fields(),
                ) );

                switch ( $screen['section'] ) {
                    /**
                     * Content: Terms: Add / WP_List_Table
                     */
                    case 'wp_list_table':
                        // JS: Plugin
                        
                        break;

                    /**
                     * Content: Terms: Edit
                     */
                    case 'edit':
                        // JS: Plugin
                        
                        break;
                }

                // Apply Localization
                wp_localize_script( $this->base->plugin->name . '-generate-content', 'page_generator_pro_generate_content', $localization );
                break;

            /**
             * Generate
             */
            case 'generate':
                wp_enqueue_script( 'jquery-ui-progressbar' );
                wp_enqueue_script( $this->base->plugin->name . '-synchronous-ajax' );
                break;

            /**
             * Posts / Pages > Edit
             * Appearance > Customize
             * Settings > Reading
             */
            case 'post':
            case 'appearance':
            case 'options':
                switch ( $screen['section'] ) {
                    /**
                     * Posts: Edit
                     */
                    case 'edit':
                        // CSS: WP Zinc
                        wp_enqueue_style( 'wpzinc-admin-selectize' );

                        // JS: WordPress
                        wp_enqueue_script( 'jquery-ui-autocomplete' );
                        
                        // JS: WP Zinc
                        wp_enqueue_script( 'wpzinc-admin-conditional' );
                        wp_enqueue_script( 'wpzinc-admin-selectize' );
                        wp_enqueue_script( 'wpzinc-admin-tabs' );
                        wp_enqueue_script( 'wpzinc-admin-tinymce-modal' );
                        wp_enqueue_script( 'wpzinc-admin' );

                        // JS: Plugin
                        wp_enqueue_script( $this->base->plugin->name . '-autocomplete' );
                        wp_enqueue_script( $this->base->plugin->name . '-conditional-fields' );
                        wp_enqueue_script( $this->base->plugin->name . '-gutenberg' );
                        //wp_enqueue_script( $this->base->plugin->name . '-page-builders' ); enabling adds blue header to e.g. edit page
                        wp_enqueue_script( $this->base->plugin->name . '-selectize' );

                        // Localize Autocomplete
                        wp_localize_script( $this->base->plugin->name . '-autocomplete', 'page_generator_pro_autocomplete', array(
                            'keywords' => $this->base->get_class( 'keywords' )->get_keywords_and_columns( true ),
                            'fields'   => $this->base->get_class( 'common' )->get_autocomplete_enabled_fields(),
                        ) );

                        // Localize Gutenberg with just Shortcodes that are supported outside of Content Groups
                        wp_localize_script( $this->base->plugin->name . '-gutenberg', 'page_generator_pro_gutenberg', array(
                            'keywords'  => $this->base->get_class( 'keywords' )->get_keywords_and_columns( true ),
                            'shortcodes'=> $this->base->get_class( 'shortcode' )->get_shortcode_supported_outside_of_content_groups(),
                        ) );

                        // Localize Selectize
                        wp_localize_script( $this->base->plugin->name . '-selectize', 'page_generator_pro_selectize', array(
                            'fields'   => $this->base->get_class( 'common' )->get_selectize_enabled_fields(),
                        ) );
                        break;
                }

                /**
                 * Performance
                 */
                
                // Don't enqueue if we're not changing wp_dropdown_pages() to an AJAX selectize instance 
                $change_page_dropdown_field = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'restrict_parent_page_depth', '0' );
                if ( $change_page_dropdown_field != 'ajax_select' ) {
                    break;
                }

                // CSS: WP Zinc
                wp_enqueue_style( 'wpzinc-admin-selectize' );

                // JS: WP Zinc
                wp_enqueue_script( 'wpzinc-admin-selectize' );

                // JS: Plugin
                wp_enqueue_script( $this->base->plugin->name . '-selectize' );
                wp_localize_script( $this->base->plugin->name . '-selectize', 'page_generator_pro_selectize', array(
                    'fields'   => $this->base->get_class( 'common' )->get_selectize_enabled_fields(),
                ) );
                break;
        }

        // Add footer action to output overlay modal markup
        add_action( 'admin_footer', array( $this, 'output_modal' ) );

        /**
         * Enqueues CSS and JS
         *
         * @since   2.6.2
         *
         * @param   array       $screen     Screen (screen, section)
         * @param   WP_Post     $post       WordPress Post
         */
        do_action( 'page_generator_pro_admin_admin_scripts_css', $screen, $post );

        // CSS
        if ( class_exists( 'Page_Generator' ) ) {
            // Hide 'Add New' if a Group exists
            $number_of_groups = $this->base->get_class( 'groups' )->get_count();
            if ( $number_of_groups > 0 ) {
                ?>
                <style type="text/css">body.post-type-page-generator-pro a.page-title-action { display: none; }</style>
                <?php
            }
        }
        
    }

    /**
     * Add the Plugin to the WordPress Administration Menu
     *
     * @since   1.0.0
     */
    public function admin_menu() {

        global $submenu;

        // Bail if we cannot access any menus
        if ( ! $this->base->get_class( 'access' )->can_access( 'show_menu' ) ) {
            return;
        }

        // Licensing
        add_menu_page( $this->base->plugin->displayName, $this->base->plugin->displayName, 'manage_options', $this->base->plugin->name, array( $this, 'licensing_screen' ), 'dashicons-format-aside' );
        add_submenu_page( $this->base->plugin->name, __( 'Licensing', 'page-generator-pro' ), __( 'Licensing', 'page-generator-pro' ), 'manage_options', $this->base->plugin->name, array( $this, 'licensing_screen' ) );

        // Bail if the product is not licensed
        if ( ! $this->base->licensing->check_license_key_valid() ) {
            return;
        }

        // Licensed - add additional menu entries, if access permitted
        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_settings' ) ) {
            $settings_page = add_submenu_page( $this->base->plugin->name, __( 'Settings', 'page-generator-pro' ), __( 'Settings', 'page-generator-pro' ), 'manage_options', $this->base->plugin->name . '-settings', array( $this, 'settings_screen' ) );    
        }

        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_keywords' ) ) {
            $keywords_page = add_submenu_page( $this->base->plugin->name, __( 'Keywords', 'page-generator-pro' ), __( 'Keywords', 'page-generator-pro' ), 'manage_options', $this->base->plugin->name . '-keywords', array( $this, 'keywords_screen' ) );    
            add_action( "load-$keywords_page", array( $this, 'add_keyword_screen_options' ) );
        }

        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_generate' ) ) {
            $groups_page = add_submenu_page( $this->base->plugin->name, __( 'Generate Content', 'page-generator-pro' ), __( 'Generate Content', 'page-generator-pro' ), 'manage_options', 'edit.php?post_type=' . $this->base->get_class( 'post_type' )->post_type_name );    
            $groups_tax_page = add_submenu_page( $this->base->plugin->name, __( 'Generate Terms', 'page-generator-pro' ), __( 'Generate Terms', 'page-generator-pro' ), 'manage_options', 'edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name ); 
            $generate_page = add_submenu_page( $this->base->plugin->name, __( 'Generate', 'page-generator-pro' ), __( 'Generate', 'page-generator-pro' ), 'manage_options', $this->base->plugin->name . '-generate', array( $this, 'generate_screen' ) );    
        }

        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_logs' ) ) {
            if ( $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_enabled', '0' ) ) {
                $log_page = add_submenu_page( $this->base->plugin->name, __( 'Logs', 'page-generator-pro' ), __( 'Logs', 'page-generator-pro' ), 'manage_options', $this->base->plugin->name . '-logs', array( $this, 'log_screen' ) );    
            }
        }

        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_import_export' ) ) {
            do_action( 'page_generator_pro_admin_menu_import_export' );
        }

        if ( $this->base->get_class( 'access' )->can_access( 'show_menu_support' ) ) {
            do_action( 'page_generator_pro_admin_menu_support' );
        }

    }

    /**
     * Ensures this Plugin's top level Admin menu remains open when the user clicks on:
     * - Generate Content
     * - Generate Terms
     *
     * This prevents the 'wrong' admin menu being open (e.g. Posts)
     *
     * @since   1.2.3
     *
     * @param   string  $parent_file    Parent Admin Menu File Name
     * @return  string                  Parent Admin Menu File Name
     */
    public function admin_menu_hierarchy_correction( $parent_file ) {

        global $current_screen;

        // If we're creating or editing a Content Group, set the $parent_file to this Plugin's registered menu name
        if ( $current_screen->base == 'post' && $current_screen->post_type == $this->base->get_class( 'post_type' )->post_type_name ) {
            // The free version uses a different top level filename
            if ( class_exists( 'Page_Generator' ) ) {
                return $this->base->plugin->name . '-keywords';
            }

            return $this->base->plugin->name;
        }

        // If we're creating or editing a Term Group, set the $parent_file to this Plugin's registered menu name
        if ( ( $current_screen->base == 'edit-tags' || $current_screen->base == 'term' ) && $current_screen->taxonomy == $this->base->get_class( 'taxonomy' )->taxonomy_name ) {
            return $this->base->plugin->name;
        }

        return $parent_file;

    }

    /**
     * Defines options to display in the Screen Options dropdown on the Keywords
     * WP_List_Table
     *
     * @since   2.6.5
     */
    public function add_keyword_screen_options() {

        add_screen_option( 'per_page', array(
            'label' => __( 'Keywords', 'page-generator-pro' ),
            'default' => 20,
            'option' => 'page_generator_pro_keywords_per_page',
        ) );

    }

    /**
     * Sets values for options displayed in the Screen Options dropdown on the Keywords
     * WP_List_Table
     *
     * @since   2.6.5
     */
    public function set_keyword_screen_options( $keep, $option, $value ) {
  
        return $value;

    }

    /**
     * Run any bulk actions on the Log WP_List_Table
     *
     * @since   2.6.5
     */
    public function run_keyword_table_bulk_actions() {

        // Get screen
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // Bail if we're not on the Keywords Screen
        if ( $screen['screen'] != 'keywords' ) {
            return;
        }
        if ( $screen['section'] != 'wp_list_table' ) {
            return;
        }

        // Get bulk action from the fields that might contain it
        $bulk_action = array_values( array_filter( array(
            ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] != -1 ? sanitize_text_field( $_REQUEST['action'] ) : '' ),
            ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] != -1 ? sanitize_text_field( $_REQUEST['action2'] ) : '' ),
            ( isset( $_REQUEST['action3'] ) && ! empty( $_REQUEST['action3'] ) ? sanitize_text_field( $_REQUEST['action3'] ) : '' ),
        ) ) );

        // Bail if no bulk action
        if ( ! is_array( $bulk_action ) ) {
            return;
        }
        if ( ! count( $bulk_action ) ) {
            return;
        }

        // Perform Bulk Action
        switch ( $bulk_action[0] ) {

            /**
             * Delete Keywords
             */
            case 'delete':
                // Setup notices class, enabling persistent storage
                $this->base->get_class( 'notices' )->enable_store();
                $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

                // Get Keyword IDs
                if ( ! isset( $_REQUEST['ids'] ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice(
                        __( 'No Keywords were selected for deletion.', 'page-generator-pro' )
                    );
                    break;
                }

                // Delete Keywords
                $result = $this->base->get_class( 'keywords' )->delete( $_REQUEST['ids'] );

                // Output success or error messages
                if ( is_wp_error( $result ) ) {
                    // Add error message and redirect back to the keyword table
                    $this->base->get_class( 'notices' )->add_error_notice( $result );
                } else {
                    $this->base->get_class( 'notices' )->add_success_notice(
                        sprintf(
                            __( '%s Keywords deleted.', 'page-generator-pro' ),
                            count( $_REQUEST['ids'] )
                        )
                    );
                }

                // Redirect
                $this->redirect_after_keyword_action();
                break;

        }

    }

    /**
     * Run any row actions on the Keywords WP_List_Table now
     *
     * @since   1.2.3
     */
    public function run_keyword_table_row_actions() {

        // Bail if no page specified
        $page = ( ( isset( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : false );
        if ( ! $page ) {
            return;
        }
        if ( $page != $this->base->plugin->name . '-keywords' ) {
            return;
        }
        
        // Bail if no row action specified
        $cmd = ( ( isset( $_GET['cmd'] ) ) ? sanitize_text_field( $_GET['cmd'] ) : false );
        if ( ! $cmd ) {
            return;
        }

        switch ( $cmd ) {

            /**
             * Duplicate Keyword
             */
            case 'duplicate':
                // Setup notices class, enabling persistent storage
                $this->base->get_class( 'notices' )->enable_store();
                $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );
                
                // Bail if no ID set
                if ( ! isset( $_GET['id'] ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice( __( 'No Group was selected for duplication.', 'page-generator-pro' ) );
                    break;
                }

                // Duplicate keyword
                $result = $this->base->get_class( 'keywords' )->duplicate( absint( $_GET['id'] ) );

                // Output success or error messages
                if ( is_wp_error( $result ) ) {
                    // Error
                    $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
                } elseif ( is_numeric( $result ) ) {
                    // Success
                    $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( 'Keyword duplicated successfully. <a href="%s">View Keyword</a>', 'page-generator-pro' ), admin_url( 'admin.php?page=' . $this->base->plugin->name . '-keywords&cmd=form&id=' . $result ) ) );
                }

                // Redirect
                $this->redirect_after_keyword_action();
                break;

            /**
             * Delete Keyword
             */
            case 'delete':
                // Setup notices class, enabling persistent storage
                $this->base->get_class( 'notices' )->enable_store();
                $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );
                
                // Bail if no ID set
                if ( ! isset( $_GET['id'] ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice( __( 'No Group was selected for duplication.', 'page-generator-pro' ) );
                    break;
                }

                // Delete keyword
                $result = $this->base->get_class( 'keywords' )->delete( absint( $_GET['id'] ) );

                // Output success or error messages
                if ( is_string( $result ) ) {
                    // Add error message and redirect back to the keyword table
                    $this->base->get_class( 'notices' )->add_error_notice( $result );
                } elseif ( $result === true ) {
                    // Success
                    $this->base->get_class( 'notices' )->add_success_notice( __( 'Keyword deleted successfully.', 'page-generator-pro' ) );
                }

                // Redirect
                $this->redirect_after_keyword_action();
                break;

        }

    }

    /**
     * Reloads the Keywords WP_List_Table, with search, order and pagination arguments if supplied
     *
     * @since   2.6.5
     */
    private function redirect_after_keyword_action() {

        $url = add_query_arg( array(
            'page'      => $this->base->plugin->name . '-keywords',
            's'         => ( isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '' ),
            'paged'     => ( isset( $_REQUEST['paged'] ) ? sanitize_text_field( $_REQUEST['paged'] ) : 1 ),
            'orderby'   => ( isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'keyword' ),
            'order'     => ( isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC' ),
        ), 'admin.php' );

        wp_redirect( $url );
        die();

    }

    /**
     * Registers Settings Panel(s) for the WordPress Administration Settings screen
     *
     * @since   1.0.0
     *
     * @param   array $panels   Settings Panels (key/value pairs)
     * @return  array           Panels
     */
    public function register_settings_panel( $panels ) {
        
        $panels[ $this->base->plugin->name . '-general' ] = array(
            'label' => __( 'General', 'page-generator-pro' ),
            'icon'  => 'dashicons-admin-site',
        );
        $panels[ $this->base->plugin->name . '-google' ] = array(
            'label' => __( 'Google', 'page-generator-pro' ),
            'icon'  => 'dashicons-location-alt',
        );
        $panels[ $this->base->plugin->name . '-generate' ] = array(
            'label' => __( 'Generate', 'page-generator-pro' ),
            'icon'  => 'dashicons-admin-page',
        );
        $panels[ $this->base->plugin->name . '-georocket' ] = array(
            'label' => __( 'Generate Locations', 'page-generator-pro' ),
            'icon'  => 'dashicons-admin-site',
        );
        $panels[ $this->base->plugin->name . '-open-weather-map' ] = array(
            'label' => __( 'OpenWeatherMap', 'page-generator-pro' ),
            'icon'  => 'dashicons-admin-site',
        );
        $panels[ $this->base->plugin->name . '-pexels' ] = array(
            'label' => __( 'Pexels', 'page-generator-pro' ),
            'icon'  => 'dashicons-camera',
        );
        $panels[ $this->base->plugin->name . '-pixabay' ] = array(
            'label' => __( 'Pixabay', 'page-generator-pro' ),
            'icon'  => 'dashicons-camera',
        );
        $panels[ $this->base->plugin->name . '-spintax' ] = array(
            'label' => __( 'Spintax', 'page-generator-pro' ),
            'icon'  => 'dashicons-code-standards',
        );
        
        return $panels;

    }

    /**
     * Outputs the Licensing Screen
     *
     * @since   1.0.0
     */
    public function licensing_screen() {

        include_once( $this->base->plugin->folder . '_modules/licensing/views/licensing.php' ); 

    }

    /**
     * Output the Settings Screen
     * Save POSTed data from the Administration Panel into a WordPress option
     *
     * @since   1.0.0
     */
    public function settings_screen() {

        // Get Page
        $page = ( isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '' );

        // Get registered settings panels
        $panels = array();

        /**
         * Filters the available panels / sections on the settings screen.
         *
         * @since   1.0.0
         *
         * @param   array   $panels     Settings Panels
         */
        $panels = apply_filters( 'page_generator_pro_setting_panel', $panels );

        // Get active tab
        $tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : $this->base->plugin->name . '-general' );

        // Setup notices class
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

        // Maybe save settings
        $result = $this->save_settings( $tab );
        if ( is_wp_error( $result ) ) {
            // Error
            $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
        } elseif ( $result === true ) {
            // Success
            $this->base->get_class( 'notices' )->add_success_notice( __( 'Settings saved successfully.', 'page-generator-pro' ) );
        }

        // Load View
        include_once( $this->base->plugin->folder . '/views/admin/settings.php' ); 

    }

    /**
     * Outputs the General Settings Screen within Page Generator Pro > Settings
     *
     * @since   1.2.1
     */
    public function settings_screen_general() {

        // Get form select options
        $countries = $this->base->get_class( 'common' )->get_countries();

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-general.php' );    
    
    }

    /**
     * Outputs the Google Settings Screen within Page Generator Pro > Settings
     *
     * @since   1.2.1
     */
    public function settings_screen_google() {

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-google.php' );    
    
    }

    /**
     * Outputs the Generate Settings Screen within Page Generator Pro > Settings
     *
     * @since   1.5.2
     */
    public function settings_screen_generate() {

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-generate.php' );    
    
    }

    /**
     * Outputs the Generate Locations Settings Screen within Page Generator Pro > Settings
     *
     * @since   1.5.2
     */
    public function settings_screen_georocket() {

        // Get form select options
        $methods            = $this->base->get_class( 'common' )->get_locations_methods();
        
        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-georocket.php' );    
    
    }

    /**
     * Outputs the OpenWeatherMap Settings Screen within Page Generator Pro > Settings
     *
     * @since   2.4.9
     */
    public function settings_screen_open_weather_map() {

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-open-weather-map.php' );    
    
    }

    /**
     * Outputs the Pexels Settings Screen within Page Generator Pro > Settings
     *
     * @since   2.2.9
     */
    public function settings_screen_pexels() {

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-pexels.php' );    
    
    }

    /**
     * Outputs the Pixabay Settings Screen within Page Generator Pro > Settings
     *
     * @since   2.2.9
     */
    public function settings_screen_pixabay() {

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-pixabay.php' );    
    
    }

    /**
     * Outputs the Generate Spintax Settings Screen within Page Generator Pro > Settings
     *
     * @since   2.2.9
     */
    public function settings_screen_generate_spintax() {

        // Get form select options
        $providers = $this->base->get_class( 'common' )->get_spintax_providers();
        $confidence_levels = array(
            'chimprewriter' => $this->base->get_class( 'chimprewriter' )->get_confidence_levels(),
            'spin_rewriter' => $this->base->get_class( 'spin_rewriter' )->get_confidence_levels(),
            'wordai'        => $this->base->get_class( 'wordai' )->get_confidence_levels(),
        );
        $part_of_speech_levels = array(
            'chimprewriter' => $this->base->get_class( 'chimprewriter' )->get_part_of_speech_levels(),
        );

        // Get settings
        $settings = array(
            'skip_capitalized_words'                => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'skip_capitalized_words', 1 ),
            'protected_words'                       => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'protected_words' ),
            'provider'                              => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'provider' ),

            // ChimpRewriter
            'chimprewriter_email_address'           => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_email_address' ),
            'chimprewriter_api_key'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_api_key' ),
            'chimprewriter_confidence_level'        => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_confidence_level', 5 ),
            'chimprewriter_part_of_speech_level'    => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_part_of_speech_level', 5 ),
            'chimprewriter_verify_grammar'          => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_verify_grammar', 1 ),
            'chimprewriter_nested_spintax'          => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_nested_spintax', 1 ),
            'chimprewriter_change_phrase_sentence_structure' => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'chimprewriter_change_phrase_sentence_structure', 1 ),

            // SpinnerChief
            'spinnerchief_username'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_username' ),           
            'spinnerchief_password'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spinnerchief_password' ),  

            // Spin Rewriter
            'spin_rewriter_email_address'           => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_email_address' ),
            'spin_rewriter_api_key'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_api_key' ),
            'spin_rewriter_confidence_level'        => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_confidence_level', 'medium' ),
            'spin_rewriter_nested_spintax'          => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_nested_spintax', 1 ),
            'spin_rewriter_auto_sentences'          => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentences', 1 ),
            'spin_rewriter_auto_paragraphs'         => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_paragraphs', 1 ),
            'spin_rewriter_auto_new_paragraphs'     => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_new_paragraphs', 1 ),
            'spin_rewriter_auto_sentence_trees'     => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'spin_rewriter_auto_sentence_trees', 1 ),

            // WordAI
            'wordai_email_address'                  => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_email_address' ),
            'wordai_api_key'                        => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_api_key' ),
            'wordai_confidence_level'               => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_confidence_level', 'Readable' ),
            'wordai_nested_spintax'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_nested_spintax', 1 ),
            'wordai_spin_sentences'                 => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_spin_sentences', 1 ),
            'wordai_spin_paragraphs'                => $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-spintax', 'wordai_spin_paragraphs', 1 ),
        );

        // Load view
        include_once( $this->base->plugin->folder . '/views/admin/settings-spintax.php' );    
    
    }

    /**
     * Outputs the Keywords Screens
     *
     * @since 1.0.0
     */
    public function keywords_screen() {

        // Setup notices class
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

        // Get Page
        $page = ( isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '' );

        // Get command
        $cmd = ( ( isset($_GET['cmd'] ) ) ? sanitize_text_field( $_GET['cmd'] ) : '' );
        switch ( $cmd ) {

            /**
             * Generate Locations
             */
            case 'form-locations':
                // Generate Locations
                $result = $this->generate_locations();
                if ( is_wp_error( $result ) ) {
                    // Error
                    $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
                } elseif ( is_numeric( $result ) ) {
                    // Success
                    $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( 'Locations Keyword generated successfully. <a href="%s">View Keyword</a>', 'page-generator-pro' ), admin_url( 'admin.php?page=' . $this->base->plugin->name . '-keywords&cmd=form&id=' . $result ) ) );
                }

                // Get countries and output types
                $countries          = $this->base->get_class( 'common' )->get_countries();
                $methods            = $this->base->get_class( 'common' )->get_locations_methods();
                $restrictions       = $this->base->get_class( 'common' )->get_locations_restrictions();
                $output_types       = $this->base->get_class( 'common' )->get_locations_output_types();
                $order_by_options   = $this->base->get_class( 'common' )->get_locations_order_by_options();
                $order_options      = $this->base->get_class( 'common' )->get_order_options();

                // View
                $view = 'views/admin/keywords-form-locations.php';

                break;

            /**
             * Generate Phone Area Codes
             */
            case 'form-phone':
                // Generate phone area codes
                $result = $this->generate_phone_area_codes();
                if ( is_wp_error( $result ) ) {
                    // Error
                    $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
                } elseif ( is_numeric( $result ) ) {
                    // Success
                    $this->base->get_class( 'notices' )->add_success_notice( __( 'Phone Area Codes generated successfully.', 'page-generator-pro' ) );
                }

                // Get countries and output types
                $countries      = $this->base->get_class( 'phone_area_codes' )->get_phone_area_code_countries();
                $output_types   = $this->base->get_class( 'common' )->get_phone_area_code_output_types();

                // View
                $view = 'views/admin/keywords-form-phone.php';

                break;

            /**
             * Import
             */
            case 'form-import-csv':
                $result = $this->import_csv();
                if ( is_wp_error( $result ) ) {
                    // Error
                    $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
                } elseif ( is_numeric( $result ) ) {
                    // Success
                    $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( '%s Keywords imported successfully.', 'page-generator-pro' ), $result ) );
                }

                // View
                $view = 'views/admin/keywords-form-import-csv.php';
                break;

            /**
             * Add / Edit Keyword
             */
            case 'form':
                // Get keyword from POST data or DB
                if ( isset( $_POST['keyword'] ) ) {
                    // Get keyword from POST data
                    $keyword = stripslashes_deep( $_POST );
                } else if ( isset( $_GET['id'] ) ) {
                    // Editing an existing Keyword
                    $keyword = $this->base->get_class( 'keywords' )->get_by_id( absint( $_GET['id'] ) );
                }

                // Save keyword
                $keyword_id = $this->save_keyword();
                
                if ( is_wp_error( $keyword_id ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice( $keyword_id->get_error_message() );
                } else if ( is_numeric( $keyword_id ) ) {
                    $this->base->get_class( 'notices' )->add_success_notice( __( 'Keyword saved successfully.', 'page-generator-pro' ) );

                    // Fetch saved keyword from DB
                    $keyword = $this->base->get_class( 'keywords' )->get_by_id( absint( $keyword_id ) );
                }

                // View
                $view = 'views/admin/keywords-form.php';
                
                break;

            /**
             * Duplicate Keyword
             * Delete Keyword
             * Index
             */
            case 'duplicate':
            case 'delete':
            default:
                // Setup Table
                $keywords_table = new Page_Generator_Pro_Keywords_Table( $this->base );
                $keywords_table->prepare_items();

                // View
                $view = 'views/admin/keywords-table.php';
                break;

        }

        // Load View
        include_once( $this->base->plugin->folder . $view ); 

    }

    /**
     * Save Settings Screen
     *
     * @since   1.0.0
     *
     * @param   string  $type   Plugin Name / Type
     * @return  mixed           WP_Error | bool
     */
    public function save_settings( $type ) {

        // For oAuth authorization, the values we need to save (or destroy) are in the URL
        if ( isset( $_REQUEST['wpzinc_provider'] ) ) {
            // Save oauth data
            return $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-' . $_REQUEST['wpzinc_provider'], array(
                'oauth_token'       => $_REQUEST['wpzinc_oauth_token'],
                'oauth_token_secret'=> $_REQUEST['wpzinc_oauth_token_secret'],
            ) );
        }
        if ( isset( $_REQUEST['wpzinc_provider_disconnect'] ) ) {
            // Delete oAuth data
            return $this->base->get_class( 'settings' )->delete_settings( $this->base->plugin->name . '-' . $_REQUEST['wpzinc_provider_disconnect'] );
        }

        // Check if a POST request was made
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return new WP_Error( 'page_generator_pro_admin_save_settings', __( 'Nonce field is missing. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'page-generator-pro' ) ) {
            return new WP_Error( 'page_generator_pro_admin_save_settings', __( 'Invalid nonce specified. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Store settings in array
        $settings = $_POST[ $type ];

        // Depending on the setting type, perform some further validation
        switch ( $type ) {
            case 'page-generator-pro-general':
                $settings['css_prefix'] = sanitize_title( str_replace( ' ', '_', $settings['css_prefix'] ) );
                break;
        }

        // Save settings
        $this->base->get_class( 'settings' )->update_settings( $type, $settings );

        // Depending on the Settings Tab, perform some actions after saving settings
        switch ( $type ) {
            case 'page-generator-pro-generate':
                // Reschedule CRON events
                $this->base->get_class( 'cron' )->reschedule_log_cleanup_event();
                break;
        }
        
        // Return
        return true;

    }

    /**
     * Generate Locations
     *
     * @since   1.7.8
     *
     * @return  mixed   false | WP_Error | int
     */
    public function generate_locations() {

        // Check if a POST request was made
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return new WP_Error( 'page_generator_pro_admin_generate_locations_missing_nonce', __( 'Nonce field is missing. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'generate_locations' ) ) {
            return new WP_Error( 'page_generator_pro_admin_generate_locations_invalid_nonce', __( 'Invalid nonce specified. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Run form validation checks
        if ( empty( $_POST['output_type'] ) || ! is_array( $_POST['output_type'] ) ) {
            return new WP_Error('page_generator_pro_admin_generate_locations_missing_output_types', __( 'Please specify the Output Type(s) for the locations.', 'page-generator-pro' ) );    
        }

        // Setup Georocket, and define an array of all possible arguments
        $args = array(
            // Plugin License Key
            'license_key'   => $this->base->licensing->get_license_key(),

            // Location and Radius
            'location'      => false,
            'radius'        => false,

            // City Restraints
            'city_id'       => false,
            'city_name'     => false,
            
            // County Restraints
            'county_id'     => false,
            'county_name'   => false,
            
            // Region Restraints
            'region_id'     => false,
            'region_name'   => false,

            // Country Restraints
            'country_id'    => false,
            'country_code'  => sanitize_text_field( $_POST['country_code'] ),
            'country_name'  => false,

            // Order By and Order
            'orderby'       => isset( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : false,
            'order'         => isset( $_POST['order'] ) ? sanitize_text_field( $_POST['order'] ) : 'asc',

            // Pagination
            'per_page'      => 1000,
        );

        // Arguments will be either location/radius or city/county/region/country
        switch ( sanitize_text_field( $_POST['method'] ) ) {
            case 'radius':
                $args['location'] = sanitize_text_field( $_POST['location'] ) . ', ' . sanitize_text_field( $_POST['country_code'] );
                $args['radius'] = sanitize_text_field( $_POST['radius'] );
                break;

            case 'area':
                $keys = array(
                    'city_name',
                    'county_name',
                    'region_name',
                );
                foreach ( $keys as $key ) {
                    if ( ! isset( $_POST[ $key ] ) ) {
                        continue;
                    }
                    if ( empty( $_POST[ $key ] ) ) {
                        continue;
                    }
                    if ( ! is_array( $_POST[ $key ] ) ) {
                        continue;
                    }

                    if ( count( $_POST[ $key ] ) == 1 ) {
                        $args[ $key ] = sanitize_text_field( $_POST[ $key ][0] );   
                    } else {
                        $args[ $key ] = $_POST[ $key ];
                    }
                }
        }

        // Make Georocket API call, depending on the level of detail required for the output
        if ( in_array( 'zipcode', $_POST['output_type'] ) ) {
            // API call to zipcodes endpoint
            $args['per_page'] = 10000;
            $terms = $this->base->get_class( 'georocket' )->get_zipcodes( $args );
        } elseif ( in_array( 'city_name', $_POST['output_type'] ) ) {
            // API call to cities endpoint
            $args['per_page'] = 5000;
            $terms = $this->base->get_class( 'georocket' )->get_cities( $args );
        } elseif ( in_array( 'county_name', $_POST['output_type'] ) ) {
            // API call to counties endpoint
            $terms = $this->base->get_class( 'georocket' )->get_counties( $args );
        } elseif ( in_array( 'region_name', $_POST['output_type'] ) ) {
            // API call to regions endpoint
            $terms = $this->base->get_class( 'georocket' )->get_regions( $args );
        }

        // Bail if an error occured
        if ( is_wp_error( $terms ) ) {
            return $terms;
        }

        // Bail if no results were found
        if ( ! is_array( $terms->data ) || count( $terms->data ) == 0 ) {
            return new WP_Error( 'page_generator_pro_admin_generate_locations_no_results', __( 'No results were found for the given criteria.', 'page-generator-pro' ) );
        }


        // Build single Keyword
        $keyword = array(
            'keyword'   => sanitize_text_field( $_POST['keyword'] ),
            'data'      => '',
            'delimiter' => ( count( $_POST['output_type'] ) > 1 ? ',' : '' ),
            'columns'   => ( count( $_POST['output_type'] ) > 1 ? implode( ',', $_POST['output_type'] ) : '' ),
        );

        // Build the keyword data based on the output type formatting
        $formatted_terms = array();
        foreach ( $terms->data as $i => $term ) {
            // Define array to build output order for this term
            $formatted_terms[ $i ] = array();

            // Build array
            foreach ( $_POST['output_type'] as $output_type ) {
                if ( isset( $term->{ $output_type } ) && ! empty( $term->{ $output_type } ) ) {
                    $formatted_terms[ $i ][] = $term->{ $output_type };
                }
            }

            // Remove any empty array values, and implode into a string
            $formatted_terms[ $i ] = implode( ', ', array_filter( $formatted_terms[ $i ] ) );
        }

        // Remove duplicates
        // This should never occur, but it's a good fallback just in case
        $formatted_terms = array_values( array_unique( $formatted_terms ) );

        // Add Terms to keyword data
        $keyword['data'] = implode( "\n", $formatted_terms ); 

        // Save Keyword, returning Keyword ID or WP_Error
        return $this->base->get_class( 'keywords' )->save( $keyword );

    }

    /**
     * Generate Phone Area Codes
     *
     * @since   1.5.9
     *
     * @return  mixed   WP_Error | int
     */
    public function generate_phone_area_codes() {

        // Check if a POST request was made
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return new WP_Error( __( 'Nonce field is missing. Phone Area Codes NOT generated.', 'page-generator-pro' ) );
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'generate_phone_area_codes' ) ) {
            return new WP_Error( __( 'Invalid nonce specified. Phone Area Codes NOT generated.', 'page-generator-pro' ) );
        }

        // Fetch all phone area codes for the given country
        $terms = $this->base->get_class( 'phone_area_codes' )->get_phone_area_codes( sanitize_text_field( $_POST['country'] ) );

        // Bail if no area codes were found
        if ( ! $terms ) {
            return new WP_Error( __( 'No phone area codes could be found for the selected country. Please choose a different country.', 'page-generator-pro' ) );
        }

        // Generate keyword
        $keyword = array(
            'keyword'   => sanitize_text_field( $_POST['keyword'] ),
            'delimiter' => ( count( $_POST['output_type'] ) > 1 ? ',' : '' ),
            'columns'   => ( count( $_POST['output_type'] ) > 1 ? implode( ',', $_POST['output_type'] ) : '' ),
            'data'      => '',
        );

        // Build the keyword data based on the output type formatting
        $formatted_terms = array();
        foreach ( $terms as $i => $term ) {
            // Define array to build output order for this term
            $formatted_terms[ $i ] = array();

            // Build array
            foreach ( $_POST['output_type'] as $output_type ) {
                $formatted_terms[ $i ][] = ( isset( $term[ $output_type ] ) ? $term[ $output_type ] : '' );
            }

            // Remove any empty array values, and implode into a string
            $formatted_terms[ $i ] = implode( ', ', array_filter( $formatted_terms[ $i ] ) );
        }

        // Remove duplicates
        // This should never occur, but it's a good fallback just in case
        $formatted_terms = array_values( array_unique( $formatted_terms ) );

        // Add Terms to keyword data
        $keyword['data'] .= implode( "\n", $formatted_terms ); 

        // Save keyword
        return $this->base->get_class( 'keywords' )->save( $keyword );

    }

    /**
     * Imports the given CSV file into multiple keywords, each with terms
     *
     * @since   1.7.3
     *
     * @return  mixed   WP_Error | int
     */
    public function import_csv() {

        // Check if a POST request was made
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return new WP_Error( __( 'Nonce field is missing. CSV file NOT imported.', 'page-generator-pro' ) );
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'import_csv' ) ) {
            return new WP_Error( __( 'Invalid nonce specified. CSV file NOT imported.', 'page-generator-pro' ) );
        }

        // Get form data
        $keywords_location = sanitize_text_field( $_POST['keywords_location'] );

        // Fetch keywords and terms from CSV file
        return $this->base->get_class( 'keywords' )->import_csv_file_data( $keywords_location );

    }

    /**
     * Save Keyword
     *
     * @since   1.0.0
     *
     * @return  mixed   WP_Error | int
     */
    public function save_keyword() {

        // Check if a POST request was made
        if ( ! isset( $_POST['submit'] ) ) {
            return false;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return new WP_Error( __( 'Nonce field is missing. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'save_keyword' ) ) {
            return new WP_Error( __( 'Invalid nonce specified. Settings NOT saved.', 'page-generator-pro' ) );
        }

        // Get ID
        $id = ( ( isset($_REQUEST['keywordID'] ) && ! empty( $_REQUEST['keywordID'] ) ) ? $_REQUEST['keywordID'] : '' );

        // Validate Form Inputs
        $keyword = sanitize_text_field( $_POST['keyword'] );
        if ( empty( $keyword ) ) {
            return new WP_Error( 'page_generator_pro_admin_save_keyword_validation_error', __( 'Please complete the keyword field.', 'page-generator-pro' ) );
        }

        // Build keyword
        $keyword = array(
            'keyword'   => sanitize_text_field( $_POST['keyword'] ),
            'delimiter' => ( isset( $_POST['delimiter'] ) && ! empty( $_POST['delimiter'] ) ? $_POST['delimiter'] : '' ),
            'columns'   => ( isset( $_POST['columns'] ) && ! empty( $_POST['columns'] ) ? $_POST['columns'] : '' ),
            'data'      => $this->base->get_class( 'keywords' )->import_text_file_data( $_POST['data'] ),
        );

        // If there is no keyword data, generate terms automatically
        if ( empty( $keyword['data'] ) ) {
            // Setup Thesaurus API and run query
            $terms = $this->base->get_class( 'thesaurus' )->get_synonyms( $keyword['keyword'] );

            // Bail if we couldn't get terms
            if ( is_wp_error( $terms ) ) {
                return $terms;
            }

            // Add terms to keyword
            $keyword['data'] = implode( "\n", $terms );
        }

        // Save Keyword
        return $this->base->get_class( 'keywords' )->save( $keyword, $id );

    }

    /**
     * Generates content for the given Group and Group Type
     *
     * @since   1.2.3
     */
    public function generate_screen() {

        // Setup notices class, enabling persistent storage
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

        // Bail if no Group ID was specified
        if ( ! isset( $_REQUEST['id'] ) ) {
            $this->base->get_class( 'notices' )->add_error_notice( __( 'No Group ID was specified.', 'page-generator-pro' ) );
            include_once( $this->base->plugin->folder . 'views/admin/notices.php' );
            return;
        }

        // Get Group ID and Type
        $id     = absint( $_REQUEST['id'] );
        $type   = ( isset( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : 'content' );

        // Get groups or groups terms class, depending on the content type we're generating
        $group = ( ( $type == 'term' ) ? $this->base->get_class( 'groups_terms' ) : $this->base->get_class( 'groups' ) );

        // If this Group has a request to cancel generation, silently clear the status, system and cancel
        // flags before performing further checks on whether we should generate
        if ( $group->cancel_generation_requested( $id ) ) {
            $group->stop_generation( $id );
        }

        // Fetch group settings
        $settings = $group->get_settings( $id );

        // Validate group
        $validated = $group->validate( $id );
        if ( is_wp_error( $validated ) ) {
            $this->base->get_class( 'notices' )->add_error_notice( $validated->get_error_message() );
            include_once( $this->base->plugin->folder . 'views/admin/generate-run-notice.php' );
            return;
        }

        // Define return to Group URL, depending on the type
        switch ( $type ) {
            case 'term':
                $return_url = admin_url( 'term.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&tag_ID=' . $id );
                break;

            case 'content':
            default:
                $return_url = admin_url( 'post.php?post=' . $id . '&amp;action=edit' );
                break;
        }
        
        // Calculate how many pages could be generated
        $number_of_pages_to_generate = $this->base->get_class( 'generate' )->get_max_number_of_pages( $settings );
          
        // Check that the number of posts doesn't exceed the maximum that can be generated
        if ( $settings['numberOfPosts'] > $number_of_pages_to_generate ) {
            $settings['numberOfPosts'] = $number_of_pages_to_generate;
        }  

        // If no limit specified, set one now
        if ( $settings['numberOfPosts'] == 0 ) {
            if ( $settings['method'] == 'random' ) {
                $settings['numberOfPosts'] = 10;
            } else {
                $settings['numberOfPosts'] = $number_of_pages_to_generate;
            }
        }

        // Add Plugin Settings
        $settings['stop_on_error'] = (int) $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'stop_on_error', '1' );

        // Set a flag to denote that this Group is generating content
        $group->start_generation( $id, 'generating', 'browser' );

        // Load View
        include_once( $this->base->plugin->folder . 'views/admin/generate-run.php' );

    }

    /**
     * Outputs the Log Screen
     *
     * @since   2.6.1
     */
    public function log_screen() {

        // Init table
        $table = new Page_Generator_Pro_Log_Table( $this->base );
        $table->prepare_items();

        // Load View
        include_once( $this->base->plugin->folder . 'views/admin/log.php' ); 

    }
    
    /**
     * Outputs the hidden Javascript Modal and Overlay in the Footer
     *
     * @since   2.4.6
     */
    public function output_modal() {

        // Load view
        require_once( $this->base->plugin->folder . '_modules/dashboard/views/modal.php' );

    }

    /**
     * Loads plugin textdomain
     *
     * @since   1.0.0
     */
    public function load_language_files() {

        load_plugin_textdomain( $this->base->plugin->name, false, $this->base->plugin->name . '/languages/' );

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
        $name = 'admin';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}