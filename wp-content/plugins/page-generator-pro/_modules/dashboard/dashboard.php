<?php
/**
 * Dashboard Widget
 * 
 * @package     Dashboard
 * @author      Dashboard
 * @version     1.0.0
 */
class WPZincDashboardWidget {  

    /**
     * Holds the plugin object
     *
     * @since   1.0.0
     *
     * @var     object
     */
    public $plugin; 

    /**
     * Holds the exact path to this file's folder
     *
     * @since   1.0.0
     *
     * @var     string
     */
    public $dashboard_folder;

    /**
     * Holds the exact URL to this file's folder
     *
     * @since   1.0.0
     *
     * @var     string
     */
    public $dashboard_url;  

    /**
     * Holds the endpoint
     *
     * @since   1.0.0
     */
    private $endpoint;

    /**
     * Flag to show the Import and Export Sub Menu
     *
     * @since   1.0.0
     */
    private $show_import_export_menu = true;

    /**
     * Flag to show the Upgrade Sub Menu
     *
     * @since   1.0.0
     */
    private $show_upgrade_menu = true;

    /**
     * Flag to show the Support Sub Menu
     *
     * @since   1.0.0
     */
    private $show_support_menu = false;

    /**
     * Constructor
     *
     * @since   1.0.0
     *
     * @param   object  $plugin    WordPress Plugin
     * @param   string  $endpoint  LUM Deactivation Endpoint
     */
    public function __construct( $plugin, $endpoint ) {

        // Plugin Details
        $this->plugin = $plugin;
        $this->endpoint = $endpoint;

        // Set class vars
        $this->dashboard_folder = plugin_dir_path( __FILE__ );
        $this->dashboard_url    = plugin_dir_url( __FILE__ );

        // Admin CSS, JS and Menu
        add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_css' ) );
        add_action( str_replace( '-', '_', $this->plugin->name ) . '_admin_menu_import_export', array( $this, 'register_import_export_menu' ), 99 );
        add_action( str_replace( '-', '_', $this->plugin->name ) . '_admin_menu_support', array( $this, 'register_support_menu' ), 99 );
        add_action( str_replace( '-', '_', $this->plugin->name ) . '_admin_menu', array( $this, 'admin_menu' ), 99 );
        
        // Plugin Actions
        if ( ! isset( $this->plugin->hide_upgrade_menu ) || ! $this->plugin->hide_upgrade_menu ) {
            add_filter( 'plugin_action_links_' . $this->plugin->name . '/' . $this->plugin->name . '.php', array( $this, 'add_action_link' ), 10, 2 );
        }

        // Reviews
        if ( $this->plugin->review_name != false ) {
            add_action( 'wp_ajax_' . str_replace( '-', '_', $this->plugin->name ) . '_dismiss_review', array( $this, 'dismiss_review' ) );
            add_action( 'admin_notices', array( $this, 'display_review_request' ) );
        }

        // Export and Support
        add_action( 'init', array( $this, 'export' ) );
        add_action( 'plugins_loaded', array( $this, 'maybe_redirect' ) );

        // Deactivation
        add_action( 'wp_ajax_wpzinc_dashboard_deactivation_modal_submit', array( $this, 'deactivation_modal_submit' ) );

    }    

    /**
     * Shows the Import & Export Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function show_import_export_menu() {

        $this->show_import_export_menu = true;

    }

    /**
     * Hides the Import & Export Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function hide_import_export_menu() {

        $this->show_import_export_menu = false;

    }

    /**
     * Shows the Support Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function show_support_menu() {

        $this->show_support_menu = true;

    }

    /**
     * Hides the Support Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function hide_support_menu() {

        $this->show_support_menu = false;

    }

    /**
     * Shows the Upgrade Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function show_upgrade_menu() {

        $this->show_upgrade_menu = true;

    }

    /**
     * Hides the Upgrade Submenu in the Plugin's Menu
     *
     * @since   1.0.0
     */
    public function hide_upgrade_menu() {

        $this->show_upgrade_menu = false;

    }

    /**
     * Adds the WP Zinc CSS class to the <body> tag when we're in the WordPress Admin interface
     * and viewing a Plugin Screen
     *
     * This allows us to then override some WordPress layout styling on e.g. #wpcontent, without
     * affecting other screens, Plugins etc.
     *
     * @since   1.0.0
     *
     * @param   string   $classes    CSS Classes
     * @return  string               CSS Classes
     */
    public function admin_body_class( $classes ) {

        // Define a list of strings that determine whether we're viewing a Plugin Screen
        $screens = array(
            $this->plugin->name,
        );

        /**
         * Filter the body classes to output on the <body> tag.
         *
         * @since   1.0.0
         *
         * @param   array   $screens        Screens
         * @param   array   $classes        Classes
         */
        $screens = apply_filters( 'wpzinc_admin_body_class', $screens, $classes );
        
        // Determine whether we're on a Plugin Screen
        $is_plugin_screen = $this->is_plugin_screen( $screens );

        // Bail if we're not a Plugin screen
        if ( ! $is_plugin_screen ) {
            return $classes;
        }

        // Add the wpzinc class and plugin name
        $classes  .= ' wpzinc ' . $this->plugin->name;

        // Return
        return trim( $classes );

    }

    /**
     * Determines whether we're viewing this Plugin's screen in the WordPress Administration
     * interface
     *
     * @since   1.0.0
     *
     * @param   array   $screens    Screens
     * @return  bool                Is Plugin Screen
     */
    private function is_plugin_screen( $screens ) {

        // Bail if the current screen can't be obtained
        if ( ! function_exists( 'get_current_screen' ) ) {
            return false;
        }

        // Bail if no screen names were specified to search for
        if ( empty( $screens ) || count( $screens ) == 0 ) {
            return false;
        }

        // Get screen
        $screen = get_current_screen();

        foreach ( $screens as $screen_name ) {
            if ( strpos( $screen->id, $screen_name ) === false ) {
                continue;
            }

            // We're on a Plugin Screen
            return true;
        }

        // If here, we're not on a Plugin Screen
        return false;

    } 
    
    /**
     * Register JS scripts, which Plugins may optionally load via wp_enqueue_script()
     * Enqueues CSS
     *
     * @since   1.0.0
     */
    public function admin_scripts_css() {    

        // If SCRIPT_DEBUG is enabled, load unminified versions
        if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            $ext = '';
        } else {
            $ext = 'min';
        }

        // JS
        wp_register_script( 'wpzinc-admin-autosize', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'autosize' . ( $ext ? '-min' : '' ) . '.js', false, $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-conditional', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'jquery.form-conditionals' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-clipboard', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'clipboard' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-deactivation', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'deactivation' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-inline-search', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'inline-search' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-media-library', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'media-library' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-modal', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'modal' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-selectize', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'selectize' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-synchronous-ajax', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'synchronous-ajax' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-tabs', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'tabs' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-tags', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'tags' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin-tinymce-modal', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'tinymce-modal' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpzinc-admin', $this->dashboard_url . 'js/' . ( $ext ? 'min/' : '' ) . 'admin' . ( $ext ? '-min' : '' ) . '.js', array( 'jquery' ), $this->plugin->version, true );
           
        // CSS
        wp_register_style( 'wpzinc-admin-selectize', $this->dashboard_url . 'css/selectize.css' ); 
        wp_enqueue_style( 'wpzinc-admin', $this->dashboard_url . 'css/admin.css' );

        // Depending on the screen we're on, maybe enqueue specific scripts now
        if ( ! function_exists( 'get_current_screen' ) ) {
            return;
        }
        
        $screen = get_current_screen();

        switch ( $screen->id ) {

            /**
             * Import / Export
             */
            case $this->plugin->name . '_page_' . $this->plugin->name . '-import-export':
               wp_enqueue_script( 'wpzinc-admin-tabs' );
               break;

        }

    }   

    /**
     * Registers the Import / Export Menu Link in the WordPress Administration interface
     *
     * @since   1.0.0
     *
     * @param   string  $parent_slug   Parent Slug 
     */
    public function register_import_export_menu( $parent_slug = '' ) {

        // Bail if the Import & Export Menu is hidden
        if ( ! $this->show_import_export_menu ) {
            return;
        }

        // If a parent slug is defined, attach the submenu items to that
        // Otherwise use the plugin's name
        $slug = ( ! empty( $parent_slug ) ? $parent_slug : $this->plugin->name );

        add_submenu_page( $slug, __( 'Import & Export', $this->plugin->name ), __( 'Import & Export', $this->plugin->name ), 'manage_options', $this->plugin->name . '-import-export', array( $this, 'import_export_screen' ) ); 
        
    }

    /**
     * Registers the Support Menu Link in the WordPress Administration interface
     *
     * @since   1.0.0
     *
     * @param   string  $parent_slug   Parent Slug 
     */
    public function register_support_menu( $parent_slug = '' ) {

        // Bail if the Support Menu is hidden
        if ( ! $this->show_support_menu ) {
            return;
        }

        // If a parent slug is defined, attach the submenu items to that
        // Otherwise use the plugin's name
        $slug = ( ! empty( $parent_slug ) ? $parent_slug : $this->plugin->name );

        add_submenu_page( $slug, __( 'Support', $this->plugin->name ), __( 'Support', $this->plugin->name ), 'manage_options', $this->plugin->name . '-support', array( $this, 'support_screen' ) );
    
    }

    /**
     * Registers the Upgrade Menu Link in the WordPress Administration interface
     *
     * @since   1.0.0
     *
     * @param   string  $parent_slug   Parent Slug 
     */
    public function register_upgrade_menu( $parent_slug = '' ) {

        // Bail if the Upgrade Menu is hidden
        if ( ! $this->show_upgrade_menu ) {
            return;
        }

        // If a parent slug is defined, attach the submenu items to that
        // Otherwise use the plugin's name
        $slug = ( ! empty( $parent_slug ) ? $parent_slug : $this->plugin->name );

        add_submenu_page( $slug, __( 'Upgrade', $this->plugin->name ), __( 'Upgrade', $this->plugin->name ), 'manage_options', $this->plugin->name . '-upgrade', array( $this, 'upgrade_screen' ) );
    
    }

    /**
     * Registers the Import / Export, Support and Upgrade Menu Links in the WordPress Administration interface.
     *
     * @since   1.0.0
     *
     * @param   string  $parent_slug   Parent Slug 
     */
    public function admin_menu( $parent_slug = '' ) {

        // Only register Import/Export Menu if enabled
        if ( $this->show_import_export_menu ) {
            $this->register_import_export_menu( $parent_slug );
        }

        // Only register Support Menu if enabled
        if ( $this->show_support_menu ) {
            $this->register_support_menu( $parent_slug );
        }

        // Only register Upgrade Menu if enabled
        if ( $this->show_upgrade_menu ) {
            $this->register_upgrade_menu( $parent_slug );
        }

    }

    /**
     * Adds Plugin Action Links to the Plugin when activated in the Plugins Screen,
     * as well as loading the deactivation Javascript and action for the modal view
     * if we're on a Free Plugin.
     *
     * @since   1.0.0
     *
     * @param   array   $links  Action Links
     * @param   string  $file   Plugin File
     * @return  array           Action Links
     */
    public function add_action_link( $links, $file ) {

        // Bail if the licensing class exists,as this means we're on a Pro version
        if ( class_exists( 'LicensingUpdateManager' ) ) {
            return $links;
        }

        // Late enqueue deactivation script
        wp_enqueue_script( 'wpzinc-admin-deactivation' );
        wp_localize_script( 'wpzinc-admin-deactivation', 'wpzinc_dashboard', array(
            'plugin'    => array(
                'name'      => $this->plugin->name,
            ),
        ) );

        // Late bind loading the deactivation modal HTML
        add_action( 'admin_footer', array( $this, 'output_deactivation_modal' ) );

        // Add Links
        $links[] = '<a href="' . $this->get_upgrade_url( 'plugins' ) . '" rel="noopener" target="_blank">' . __( 'Upgrade', $this->plugin->name ) . '</a>';

        /**
         * Filter the action links
         *
         * @since   1.0.0
         *
         * @param   array   $links          Action Links
         * @param   string  $plugin_name    Plugin Name
         * @param   object  $plugin         Plugin
         */
        $links = apply_filters( 'wpzinc_dashboard_add_action_link', $links, $this->plugin->name, $this->plugin );

        // Return
        return $links;

    }

    /**
     * Outputs the Deactivation Modal HTML, which is displayed by Javascript
     *
     * @since   1.0.0
     */
    public function output_deactivation_modal() {

        // Define the deactivation reasons
        $reasons = array(
            'upgrade_pro'       => __( 'I\'m upgrading to the Pro version', $this->plugin->name ),
            'not_working'       => __( 'The Plugin didn\'t work', $this->plugin->name ),
            'not_required'      => __( 'I no longer need the Plugin', $this->plugin->name ),
            'better_alternative'=> __( 'I found a better Plugin', $this->plugin->name ),
            'temporary'         => __( 'This is just a temporary deactivation', $this->plugin->name ),
            'other'             => __( 'Other', $this->plugin->name ),  
        );

        /**
         * Filter the deactivation reasons
         *
         * @since   1.0.0
         *
         * @param   array   $reasons        Reasons
         * @param   string  $plugin_name    Plugin Name
         * @param   object  $plugin         Plugin
         */
        $reasons = apply_filters( 'wpzinc_dashboard_output_deactivation_modal_reasons', $reasons, $this->plugin->name, $this->plugin );

        // Bail if no reasons are given
        if ( empty( $reasons ) || count( $reasons ) == 0 ) {
            return;
        }

        // Output modal, which will be displayed when the user clicks deactivate on this plugin.
        require_once( $this->plugin->folder . '/_modules/dashboard/views/deactivation-modal.php' );

    }

    /**
     * Sends the deactivation reason
     *
     * @since   1.0.0
     */
    public function deactivation_modal_submit() {

        // Build args
        $args = array(
            'product'       => sanitize_text_field( $_REQUEST['product'] ),
            'reason'        => sanitize_text_field( $_REQUEST['reason'] ),
            'reason_text'   => sanitize_text_field( $_REQUEST['reason_text'] ),
            'reason_email'  => sanitize_text_field( $_REQUEST['reason_email'] ),
            'site_url'      => str_replace( parse_url( get_bloginfo( 'url' ), PHP_URL_SCHEME ) . '://', '', get_bloginfo( 'url' ) ),
        );

        // Send deactivation reason
        $response = wp_remote_get( $this->endpoint . '/index.php?' . http_build_query( $args ) );
        
        // Return error or success, depending on the result
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( $response->get_error_message(), wp_remote_retrieve_response_code( $response ) );
        }

        wp_send_json_success( wp_remote_retrieve_body( $response ) );

    }

    /**
     * Displays a dismissible WordPress Administration notice requesting a review, if the main
     * plugin's key action has been completed.
     *
     * @since   1.0.0
     */
    public function display_review_request() {

        // If we're not an Admin user, bail
        if ( ! function_exists( 'current_user_can' ) ) {
            return;
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // If the review request was dismissed by the user, bail.
        if ( $this->dismissed_review() ) {
            return;
        }

        // If no review request has been set by the plugin, bail.
        if ( ! $this->requested_review() ) {
            return;
        }

        // If here, display the request for a review
        include_once( $this->dashboard_folder . '/views/review-notice.php' );

    }

    /**
     * Flag to indicate whether a review has been requested.
     *
     * @since   1.0.0
     *
     * @return  bool    Review Requested
     */
    public function requested_review() {

        $time = get_option( $this->plugin->review_name . '-review-request' );
        if ( empty( $time ) ) {
            return false;
        }

        // Check the current date and time matches or is later than the above value
        $now = time();
        if ( $now >= ( $time + ( 3 * DAY_IN_SECONDS ) ) ) {
            return true;
        }

        // We're not yet ready to show this review
        return false;

    }

    /**
     * Requests a review notification, which is displayed on subsequent page loads.
     *
     * @since   1.0.0
     */
    public function request_review() {

        // If a review has already been requested, bail
        $time = get_option( $this->plugin->review_name . '-review-request' );
        if ( ! empty( $time ) ) {
            return;
        }

        // Request a review, setting the value to the date and time now.
        update_option( $this->plugin->review_name . '-review-request', time() );

    }

    /**
     * Flag to indicate whether a review request has been dismissed by the user.
     *
     * @since   1.0.0
     *
     * @return  bool    Review Dismissed
     */
    public function dismissed_review() {

        return get_option( $this->plugin->review_name . '-review-dismissed' );

    }

    /**
     * Dismisses the review notification, so it isn't displayed again.
     *
     * @since   1.0.0
     */
    public function dismiss_review() {

        update_option( $this->plugin->review_name . '-review-dismissed', 1 );

        // Send success response if called via AJAX
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            wp_send_json_success( 1 );
        }

    }

    /**
     * Returns the Upgrade URL for this Plugin.
     *
     * Adds Google Analytics UTM tracking, and optional coupon flag
     *
     * @since   1.0.0
     *
     * @param   string  $utm_content    UTM Content Value
     * @return  string                  Upgrade URL
     */
    public function get_upgrade_url( $utm_content = '' ) {

        // Build URL
        $url = $this->plugin->upgrade_url . '?utm_source=wordpress&utm_medium=link&utm_content=' . $utm_content . '&utm_campaign=general';

        // Return
        return $url;

    }

    /**
     * Import / Export Screen
     *
     * @since   1.0.0
     */
    public function import_export_screen() {

        if ( ! empty( $_POST ) ) {
            // Check nonce
            $result = $this->import_security_check();

            if ( is_wp_error( $result ) ) {
                $this->errorMessage = $result->get_error_message();
            } else {
                // Import JSON
                if ( isset( $_POST['import'] ) ) {
                    $this->import();
                } else {
                    // Import from Third Party
                    $result = true;
                    $result = apply_filters( str_replace( '-', '_', $this->plugin->name ) . '_import_third_party', $result, $_POST );

                    if ( is_wp_error( $result ) ) {
                        $this->errorMessage = $result->get_error_message();
                    } else {
                        $this->message = __( 'Settings imported.', $this->plugin->name );
                    }
                }
            }
        }

        // Allow Plugin to define additional import routines
        $import_sources = apply_filters( str_replace( '-', '_', $this->plugin->name ) . '_import_sources', array() );
        
        // Output view
        include_once( $this->dashboard_folder . '/views/import-export.php' );

    }

    /**
     * Check the nonce before importing
     *
     * @since   1.0.0
     *
     * @return  mixed   WP_Error | bool
     */
    private function import_security_check() {

        // Check nonce
        if ( ! isset( $_POST[ $this->plugin->name . '_nonce' ] ) ) {
            // Missing nonce    
            return new WP_Error( 'import_export_nonce_missing', __( 'nonce field is missing. Settings NOT saved.', $this->plugin->name ) );
        }

        if ( ! wp_verify_nonce( $_POST[ $this->plugin->name . '_nonce' ], $this->plugin->name ) ) {
            // Invalid nonce
            return new WP_Error( 'import_export_nonce_invalid', __( 'Invalid nonce specified. Settings NOT saved.', $this->plugin->name ) );
        }

        return true;

    }

    /**
     * Import JSON file upload that confirms to our standards
     *
     * @since   1.0.0
     */
    private function import() {

        if ( ! is_array( $_FILES ) ) {
            $this->errorMessage = __( 'No file was uploaded', $this->plugin->name );
            return;
        }

        if ( $_FILES['import']['error'] != 0 ) {
            $this->errorMessage = __( 'Error when uploading file.', $this->plugin->name );
            return;
        }

        // Determine if the file is JSON or ZIP
        switch ( $_FILES['import']['type'] ) {
            /**
             * ZIP File
             */
            case 'application/zip':
                // Open ZIP file
                $zip = new ZipArchive;
                if ( $zip->open( $_FILES['import']['tmp_name'] ) !== true ) {
                    $this->errorMessage = __( 'Could not extract the supplied ZIP file.', $this->plugin->name );
                    return;
                }

                // Extract and close
                $zip->extractTo( sys_get_temp_dir() );
                $zip->close();

                // Read JSON file
                $handle = fopen( sys_get_temp_dir() . '/export.json', 'r' );
                $json = fread( $handle, filesize( sys_get_temp_dir() . '/export.json' ) );
                fclose( $handle );
                break;

            default:
                // Read file
                $handle = fopen( $_FILES['import']['tmp_name'], 'r' );
                $json = fread( $handle, $_FILES['import']['size'] );
                fclose( $handle );

        }

        // Remove UTF8 BOM chars
        $bom = pack( 'H*','EFBBBF' );
        $json = preg_replace( "/^$bom/", '', $json );

        // Decode
        $import = json_decode( $json, true );

        // Check data is an array
        if ( ! is_array( $import ) ) {
            $this->errorMessage = __( 'Supplied file is not a valid JSON settings file, or has become corrupt.', $this->plugin->name );
            return;
        }

        // Allow Plugin to run its Import Routine using the supplied data now
        $result = true;
        $result = apply_filters( str_replace( '-', '_', $this->plugin->name ) . '_import', $result, $import );
    
        // Bail if an error occured
        if ( is_wp_error( $result ) ) {
            $this->errorMessage = $result->get_error_message();
            return;
        }

        $this->message = __( 'Settings imported.', $this->plugin->name );

    }
    
    /**
     * Support Screen
     *
     * @since   1.0.0
     */
    public function support_screen() {   
        // We never reach here, as we redirect earlier in the process
    }

    /**
     * Upgrade Screen
     *
     * @since   1.0.0
     */
    public function upgrade_screen() {   
        // We never reach here, as we redirect earlier in the process
    }
    
    /**
     * If we have requested the export JSON, force a file download
     *
     * @since   1.0.0
     */ 
    public function export() {

        // Check we are on the right page
        if ( ! isset( $_GET['page'] ) ) {
            return;
        }
        if ( sanitize_text_field( $_GET['page'] ) != $this->plugin->name . '-import-export' ) {
            return;
        }
        if ( ! isset( $_GET['export'] ) ) {
            return;
        }

        // Get any other data from the main plugin
        // Main plugin can hook into this filter and return an array of data
        $data = apply_filters( str_replace( '-', '_', $this->plugin->name ) . '_export', array() );

        // Force a file download, depending on the export format
        switch ( sanitize_text_field( $_GET['export'] ) ) {
            /**
             * JSON, Zipped
             */
            case 'zip':
                $this->force_zip_file_download( json_encode( array(
                    'data'      => $data,
                ) ) );
                break;

            /**
             * JSON
             */
            case 'json':
            default:
                $this->force_json_file_download( json_encode( array(
                    'data'      => $data,
                ) ) );
                break;
        }

    }

    /**
     * Force a browser download comprising of the given file, zipped
     *
     * @since   1.0.0
     *
     * @param   string  $data   Uncompressed Data
     */
    public function force_zip_file_download( $json ) {

        // Create new ZIP file
        $zip = new ZipArchive();
        $filename = 'export.zip';

        // Bail if ZIP file couldn't be created
        if ( $zip->open( $filename, ZipArchive::CREATE ) !== true ) {
            return;
        }

        // Add JSON data to export.json and close
        $zip->addFromString( 'export.json', $json );
        $zip->close();

        // Output ZIP data, prompting the browser to auto download as a ZIP file now
        header( "Content-type: application/zip" );
        header( "Content-Disposition: attachment; filename=export.zip" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        readfile( $filename );
        unlink( $filename );
        exit();

    }

    /**
     * Force a browser download comprising of the given JSON data
     *
     * @since   1.0.0
     *
     * @param   string  $json   JSON Data for file
     */
    public function force_json_file_download( $json ) {

        // Output JSON data, prompting the browser to auto download as a JSON file now
        header( "Content-type: application/x-msdownload" );
        header( "Content-Disposition: attachment; filename=export.json" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        echo $json;
        exit();

    }

    /**
     * If the Support or Upgrade menu item was clicked, redirect
     *
     * @since 3.0
     */
    public function maybe_redirect() {

        // Check we requested the support page
        if ( ! isset( $_GET['page'] ) ) {
            return;
        }
        
        // Redirect to Support
        if ( $_GET['page'] == $this->plugin->name . '-support' ) {
            wp_redirect( $this->plugin->support_url );
            die();
        }

        // Redirect to Upgrade
        if ( $_GET['page'] == $this->plugin->name . '-upgrade' ) {
            wp_redirect( $this->get_upgrade_url( 'menu' ) );
            die();
        }

    }

}