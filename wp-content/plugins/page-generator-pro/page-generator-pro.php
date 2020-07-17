<?php
/**
* Plugin Name: Page Generator Pro
* Plugin URI: http://www.wpzinc.com/plugins/page-generator-pro
* Version: 2.7.3
* Author: WP Zinc
* Author URI: http://www.wpzinc.com
* Description: Generate multiple Pages, Posts and Custom Post Types, using dynamic content selected from a number of sources.
*/

/**
 * Page Generator Pro Class
 * 
 * @package   Page_Generator_Pro
 * @author    Tim Carr
 * @version   1.0.0
 * @copyright WP Zinc
 */
class Page_Generator_Pro {

    /**
     * Holds the class object.
     *
     * @since   1.1.3
     *
     * @var     object
     */
    public static $instance;

    /**
     * Holds the plugin information object.
     *
     * @since   1.0.0
     *
     * @var     object
     */
    public $plugin = '';

    /**
     * Holds the dashboard class object.
     *
     * @since   1.1.6
     *
     * @var     object
     */
    public $dashboard = '';

    /**
     * Holds the licensing class object.
     *
     * @since   1.1.6
     *
     * @var     object
     */
    public $licensing = '';

    /**
     * Classes
     *
     * @since   1.9.8
     *
     * @var     array
     */
    public $classes = '';

    /**
     * Constructor. Acts as a bootstrap to load the rest of the plugin
     * 
     * @since   1.0.0
     */
    public function __construct() {

        // Plugin Details
        $this->plugin                   = new stdClass;
        $this->plugin->name             = 'page-generator-pro';
        $this->plugin->displayName      = 'Page Generator Pro';
        $this->plugin->author_name      = 'WP Zinc';
        $this->plugin->version          = '2.7.3';
        $this->plugin->buildDate        = '2020-07-09 18:00:00';
        $this->plugin->requires         = '5.0';
        $this->plugin->tested           = '5.4.2';
        $this->plugin->folder           = plugin_dir_path( __FILE__ );
        $this->plugin->url              = plugin_dir_url( __FILE__ );
        $this->plugin->documentation_url= 'https://www.wpzinc.com/documentation/page-generator-pro';
        $this->plugin->support_url      = 'https://www.wpzinc.com/support';
        $this->plugin->upgrade_url      = 'https://www.wpzinc.com/plugins/page-generator-pro';
        $this->plugin->review_name      = 'page-generator';
        $this->plugin->review_notice    = sprintf( __( 'Thanks for using %s to generate content!', $this->plugin->name ), $this->plugin->displayName );

        // Licensing Submodule
        if ( ! class_exists( 'LicensingUpdateManager' ) ) {
            require_once( $this->plugin->folder . '_modules/licensing/lum.php' );
        }
        $this->licensing = new LicensingUpdateManager( $this->plugin, 'https://www.wpzinc.com/wp-content/plugins/lum' );

        // Run Plugin Display Name, URLs through Whitelabelling if available
        $this->plugin->displayName = $this->licensing->get_feature_parameter( 'whitelabelling', 'display_name', $this->plugin->displayName );
        $this->plugin->support_url = $this->licensing->get_feature_parameter( 'whitelabelling', 'support_url', $this->plugin->support_url );
        $this->plugin->documentation_url = $this->licensing->get_feature_parameter( 'whitelabelling', 'documentation_url', $this->plugin->documentation_url );       

        // Dashboard Submodule
        if ( ! class_exists( 'WPZincDashboardWidget' ) ) {
            require_once( $this->plugin->folder . '_modules/dashboard/dashboard.php' );
        }
        $this->dashboard = new WPZincDashboardWidget( $this->plugin, 'https://www.wpzinc.com/wp-content/plugins/lum-deactivation' );

        // Show Support Menu and hide Upgrade Menu
        $this->dashboard->show_support_menu();
        $this->dashboard->hide_upgrade_menu();

        // Defer loading of Plugin Classes
        add_action( 'admin_init', array( $this, 'deactivate_free_version' ) );
        add_action( 'init', array( $this, 'initialize' ), 1 );
        add_action( 'init', array( $this, 'upgrade' ), 2 );

    }

    /**
     * Detects if the Free version of the Plugin is running, and if so,
     * deactivates it.
     *
     * @since   1.6.7
     */
    public function deactivate_free_version() {
        
        // Bail if the function is not available
        if ( ! function_exists( 'deactivate_plugins' ) ) {
            return;
        }

        // Bail if the Free version is not active
        if ( ! is_plugin_active( 'page-generator/page-generator.php' ) ) {
            return;
        }

        // Deactivate the Free version
        deactivate_plugins( 'page-generator/page-generator.php' );
      
    }

    /**
     * Initializes required and licensed classes
     *
     * @since   1.9.8
     */
    public function initialize() {

        $this->classes = new stdClass;

        $this->initialize_admin_or_frontend_editor();
        $this->initialize_cli_cron();
        $this->initialize_frontend();

    }

    /**
     * Initialize classes for the WordPress Administration interface or a frontend Page Builder
     *
     * @since   2.5.2
     */
    private function initialize_admin_or_frontend_editor() {

        // Bail if this request isn't for the WordPress Administration interface and isn't for a frontend Page Builder
        if ( ! $this->is_admin_or_frontend_editor() ) {
            return;
        }

        // Initialize classes used by the activation and update processes, before the Plugin might be licensed 
        $this->classes->access              = new Page_Generator_Pro_Access( self::$instance );
        $this->classes->admin               = new Page_Generator_Pro_Admin( self::$instance );
        $this->classes->common              = new Page_Generator_Pro_Common( self::$instance );
        $this->classes->cron                = new Page_Generator_Pro_Cron( self::$instance );
        $this->classes->install             = new Page_Generator_Pro_Install( self::$instance );
        $this->classes->geo                 = new Page_Generator_Pro_Geo( self::$instance );
        $this->classes->groups              = new Page_Generator_Pro_Groups( self::$instance );
        $this->classes->keywords            = new Page_Generator_Pro_Keywords( self::$instance );
        $this->classes->log                 = new Page_Generator_Pro_Log( self::$instance );
        $this->classes->notices             = new Page_Generator_Pro_Notices( self::$instance );
        $this->classes->phone_area_codes    = new Page_Generator_Pro_Phone_Area_Codes( self::$instance );
        $this->classes->post_type           = new Page_Generator_Pro_PostType( self::$instance );
        $this->classes->settings            = new Page_Generator_Pro_Settings( self::$instance );
        $this->classes->screen              = new Page_Generator_Pro_Screen( self::$instance );
        $this->classes->taxonomy            = new Page_Generator_Pro_Taxonomy( self::$instance );

        // Initialize licensed classes
        if ( $this->licensing->check_license_key_valid() ) {
            $this->classes->acf                 = new Page_Generator_Pro_ACF( self::$instance );
            $this->classes->ajax                = new Page_Generator_Pro_AJAX( self::$instance );
            $this->classes->block_spin          = new Page_Generator_Pro_Block_Spin( self::$instance );
            $this->classes->chimprewriter       = new Page_Generator_Pro_ChimpRewriter_API( self::$instance );
            $this->classes->cornerstone         = new Page_Generator_Pro_Cornerstone( self::$instance );
            $this->classes->creative_commons    = new Page_Generator_Pro_Creative_Commons( self::$instance );
            $this->classes->editor              = new Page_Generator_Pro_Editor( self::$instance );
            $this->classes->exif                = new Page_Generator_Pro_Exif( self::$instance );
            $this->classes->export              = new Page_Generator_Pro_Export( self::$instance );
            $this->classes->generate            = new Page_Generator_Pro_Generate( self::$instance );
            $this->classes->georocket           = new Page_Generator_Pro_Georocket_API( self::$instance );
            $this->classes->groups_table        = new Page_Generator_Pro_Groups_Table( self::$instance );
            $this->classes->groups_ui           = new Page_Generator_Pro_Groups_UI( self::$instance );
            $this->classes->groups_terms        = new Page_Generator_Pro_Groups_Terms( self::$instance );
            $this->classes->groups_terms_table  = new Page_Generator_Pro_Groups_Terms_Table( self::$instance );
            $this->classes->groups_terms_ui     = new Page_Generator_Pro_Groups_Terms_UI( self::$instance );
            $this->classes->gutenberg           = new Page_Generator_Pro_Gutenberg( self::$instance );
            $this->classes->i18n                = new Page_Generator_Pro_i18n( self::$instance );
            $this->classes->import              = new Page_Generator_Pro_Import( self::$instance );
            $this->classes->media_library       = new Page_Generator_Pro_Media_Library( self::$instance );
            $this->classes->open_weather_map    = new Page_Generator_Pro_Open_Weather_Map_API( self::$instance );
            $this->classes->page_builders       = new Page_Generator_Pro_PageBuilders( self::$instance );
            $this->classes->pexels              = new Page_Generator_Pro_Pexels( self::$instance );
            $this->classes->pixabay             = new Page_Generator_Pro_Pixabay( self::$instance );
            $this->classes->shortcode_creative_commons  = new Page_Generator_Pro_Shortcode_Creative_Commons( self::$instance );
            $this->classes->shortcode_google_map        = new Page_Generator_Pro_Shortcode_Google_Map( self::$instance );
            $this->classes->shortcode_media_library     = new Page_Generator_Pro_Shortcode_Media_Library( self::$instance );
            $this->classes->shortcode_open_street_map   = new Page_Generator_Pro_Shortcode_Open_Street_Map( self::$instance );
            $this->classes->shortcode_open_weather_map  = new Page_Generator_Pro_Shortcode_Open_Weather_Map( self::$instance );
            $this->classes->shortcode_pexels            = new Page_Generator_Pro_Shortcode_Pexels( self::$instance );
            $this->classes->shortcode_pixabay           = new Page_Generator_Pro_Shortcode_Pixabay( self::$instance );
            $this->classes->shortcode_related_links     = new Page_Generator_Pro_Shortcode_Related_Links( self::$instance );
            $this->classes->shortcode_wikipedia         = new Page_Generator_Pro_Shortcode_Wikipedia( self::$instance );
            $this->classes->shortcode_yelp              = new Page_Generator_Pro_Shortcode_Yelp( self::$instance );
            $this->classes->shortcode_youtube           = new Page_Generator_Pro_Shortcode_YouTube( self::$instance );
            $this->classes->shortcode                   = new Page_Generator_Pro_Shortcode( self::$instance );
            $this->classes->spin_rewriter       = new Page_Generator_Pro_Spin_Rewriter_API( self::$instance );
            $this->classes->spinnerchief        = new Page_Generator_Pro_SpinnerChief_API( self::$instance );
            $this->classes->spintax             = new Page_Generator_Pro_Spintax( self::$instance );
            $this->classes->thesaurus           = new Page_Generator_Pro_Thesaurus( self::$instance );
            $this->classes->woocommerce         = new Page_Generator_Pro_WooCommerce( self::$instance );
            $this->classes->wikipedia           = new Page_Generator_Pro_Wikipedia( self::$instance );
            $this->classes->wordai              = new Page_Generator_Pro_WordAI_API( self::$instance );
            $this->classes->yelp                = new Page_Generator_Pro_Yelp( self::$instance ); 
            $this->classes->youtube             = new Page_Generator_Pro_YouTube( self::$instance ); 
        }

    }

    /**
     * Initialize classes for WP-CLI and WP-Cron
     *
     * @since   2.5.2
     */
    private function initialize_cli_cron() {

        // Bail if this isn't a CLI or CRON request
        if ( ! $this->is_cli() && ! $this->is_cron() ) {
            return;
        }

        // Bail if the Plugin isn't licensed
        if ( ! $this->licensing->check_license_key_valid() ) {
            return;
        }

        $this->classes->block_spin          = new Page_Generator_Pro_Block_Spin( self::$instance );
        $this->classes->chimprewriter       = new Page_Generator_Pro_ChimpRewriter_API( self::$instance );
        $this->classes->common              = new Page_Generator_Pro_Common( self::$instance );
        $this->classes->creative_commons    = new Page_Generator_Pro_Creative_Commons( self::$instance );
        $this->classes->cron                = new Page_Generator_Pro_Cron( self::$instance );
        $this->classes->exif                = new Page_Generator_Pro_Exif( self::$instance );
        $this->classes->generate            = new Page_Generator_Pro_Generate( self::$instance );
        $this->classes->geo                 = new Page_Generator_Pro_Geo( self::$instance );
        $this->classes->georocket           = new Page_Generator_Pro_Georocket_API( self::$instance );
        $this->classes->groups              = new Page_Generator_Pro_Groups( self::$instance );
        $this->classes->groups_terms        = new Page_Generator_Pro_Groups_Terms( self::$instance );
        $this->classes->i18n                = new Page_Generator_Pro_i18n( self::$instance );
        $this->classes->import              = new Page_Generator_Pro_Import( self::$instance );
        $this->classes->keywords            = new Page_Generator_Pro_Keywords( self::$instance );
        $this->classes->log                 = new Page_Generator_Pro_Log( self::$instance );
        $this->classes->media_library       = new Page_Generator_Pro_Media_Library( self::$instance );
        $this->classes->open_weather_map    = new Page_Generator_Pro_Open_Weather_Map_API( self::$instance );
        $this->classes->page_builders       = new Page_Generator_Pro_PageBuilders( self::$instance );
        $this->classes->pexels              = new Page_Generator_Pro_Pexels( self::$instance );
        $this->classes->post_type           = new Page_Generator_Pro_PostType( self::$instance );
        $this->classes->phone_area_codes    = new Page_Generator_Pro_Phone_Area_Codes( self::$instance );
        $this->classes->pixabay             = new Page_Generator_Pro_Pixabay( self::$instance );
        $this->classes->shortcode_creative_commons  = new Page_Generator_Pro_Shortcode_Creative_Commons( self::$instance );
        $this->classes->shortcode_google_map        = new Page_Generator_Pro_Shortcode_Google_Map( self::$instance );
        $this->classes->shortcode_media_library     = new Page_Generator_Pro_Shortcode_Media_Library( self::$instance );
        $this->classes->shortcode_open_street_map   = new Page_Generator_Pro_Shortcode_Open_Street_Map( self::$instance );
        $this->classes->shortcode_open_weather_map  = new Page_Generator_Pro_Shortcode_Open_Weather_Map( self::$instance );
        $this->classes->shortcode_pexels            = new Page_Generator_Pro_Shortcode_Pexels( self::$instance );
        $this->classes->shortcode_pixabay           = new Page_Generator_Pro_Shortcode_Pixabay( self::$instance );
        $this->classes->shortcode_related_links     = new Page_Generator_Pro_Shortcode_Related_Links( self::$instance );
        $this->classes->shortcode_wikipedia         = new Page_Generator_Pro_Shortcode_Wikipedia( self::$instance );
        $this->classes->shortcode_yelp              = new Page_Generator_Pro_Shortcode_Yelp( self::$instance );
        $this->classes->shortcode_youtube           = new Page_Generator_Pro_Shortcode_YouTube( self::$instance );
        $this->classes->shortcode                   = new Page_Generator_Pro_Shortcode( self::$instance );
        $this->classes->settings            = new Page_Generator_Pro_Settings( self::$instance );
        $this->classes->spin_rewriter       = new Page_Generator_Pro_Spin_Rewriter_API( self::$instance );
        $this->classes->spinnerchief        = new Page_Generator_Pro_SpinnerChief_API( self::$instance );
        $this->classes->spintax             = new Page_Generator_Pro_Spintax( self::$instance );
        $this->classes->taxonomy            = new Page_Generator_Pro_Taxonomy( self::$instance );
        $this->classes->thesaurus           = new Page_Generator_Pro_Thesaurus( self::$instance );
        $this->classes->wikipedia           = new Page_Generator_Pro_Wikipedia( self::$instance );
        $this->classes->wordai              = new Page_Generator_Pro_WordAI_API( self::$instance );
        $this->classes->yelp                = new Page_Generator_Pro_Yelp( self::$instance );
        $this->classes->youtube             = new Page_Generator_Pro_YouTube( self::$instance );
        
        // Register the CLI command(s)
        if ( class_exists( 'WP_CLI' ) ) {
            require_once( $this->plugin->folder . 'includes/admin/cli.php' );
        }

    }

    /**
     * Initialize classes for the frontend web site
     *
     * @since   2.5.2
     */
    private function initialize_frontend() {

        // Bail if this request isn't for the frontend web site
        if ( is_admin() ) {
            return;
        }

        $this->classes->common                      = new Page_Generator_Pro_Common( self::$instance );
        $this->classes->cornerstone                 = new Page_Generator_Pro_Cornerstone( self::$instance );
        $this->classes->geo                         = new Page_Generator_Pro_Geo( self::$instance );
        $this->classes->gutenberg                   = new Page_Generator_Pro_Gutenberg( self::$instance );
        $this->classes->post_type                   = new Page_Generator_Pro_PostType( self::$instance );
        $this->classes->settings                    = new Page_Generator_Pro_Settings( self::$instance );
        $this->classes->shortcode_open_street_map   = new Page_Generator_Pro_Shortcode_Open_Street_Map( self::$instance );
        $this->classes->shortcode_related_links     = new Page_Generator_Pro_Shortcode_Related_Links( self::$instance );
        $this->classes->shortcode                   = new Page_Generator_Pro_Shortcode( self::$instance );
        $this->classes->taxonomy                    = new Page_Generator_Pro_Taxonomy( self::$instance );
        
    }

    /**
     * Improved version of WordPress' is_admin(), which includes whether we're
     * editing on the frontend using a Page Builder.
     *
     * @since   2.5.2
     *
     * @return  bool    Is Admin or Frontend Editor Request
     */
    public function is_admin_or_frontend_editor() {

        // If we're in the wp-admin, return true
        if ( is_admin() ) {
            return true;
        }

        // Pro
        if ( isset( $_SERVER ) ) {
            if ( strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), '/pro/' ) !== false ) {
                return true;
            }
            if ( strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), '/x/' ) !== false ) {
                return true;
            }
            if ( strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), 'cornerstone-endpoint' ) !== false ) {
                return true;
            }
        }

        // If the request global exists, check for specific request keys which tell us
        // that we're using a frontend editor
        if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) ) {
            // Beaver Builder
            if ( array_key_exists( 'fl_builder', $_REQUEST ) ) {
                return true;
            }

            // Cornerstone (AJAX)
            if ( array_key_exists( '_cs_nonce', $_REQUEST ) ) {
                return true;
            }

            // Divi
            if ( array_key_exists( 'et_fb', $_REQUEST ) ) {
                return true;
            }

            // Elementor
            if ( array_key_exists( 'action', $_REQUEST ) && sanitize_text_field( $_REQUEST['action'] ) == 'elementor' ) {
                return true;
            }

            // Kallyas
            if ( array_key_exists( 'zn_pb_edit', $_REQUEST ) ) {
                return true;
            }

            // Oxygen
            if ( array_key_exists( 'ct_builder', $_REQUEST ) ) {
                return true;
            }

            // Thrive Architect
            if ( array_key_exists( 'tve', $_REQUEST ) ) {
                return true;
            }

            // Visual Composer
            if ( array_key_exists( 'vcv-editable', $_REQUEST ) ) {
                return true;
            }

            // WPBakery Page Builder
            if ( array_key_exists( 'vc_editable', $_REQUEST ) ) {
                return true;
            }
        }

        // Assume we're not in the Administration interface
        $is_admin_or_frontend_editor = false;

        /**
         * Filters whether the current request is a WordPress Administration / Frontend Editor request or not.
         *
         * Page Builders can set this to true to allow Page Generator Pro to load its functionality.
         *
         * @since   2.5.2
         *
         * @param   bool    $is_admin_or_frontend_editor    Is WordPress Administration / Frontend Editor request.
         * @param   array   $_REQUEST                       $_REQUEST data                
         */
        $is_admin_or_frontend_editor = apply_filters( 'page_generator_pro_is_admin_or_frontend_editor', $is_admin_or_frontend_editor, $_REQUEST );
       
        // Return filtered result 
        return $is_admin_or_frontend_editor;

    }

    /**
     * Detects if the request is through the WP-CLI
     *
     * @since   2.5.2
     *
     * @return  bool    Is WP-CLI Request
     */
    public function is_cli() {

        if ( ! defined( 'WP_CLI' ) ) {
            return false;
        }
        if ( ! WP_CLI ) {
            return false;
        }

        return true;

    }

    /**
     * Detects if the request is through the WP CRON
     *
     * @since   2.5.2
     *
     * @return  bool    Is WP CRON Request
     */
    public function is_cron() {

        if ( ! defined( 'DOING_CRON' ) ) {
            return false;
        }
        if ( ! DOING_CRON ) {
            return false;
        }

        return true;

    }

    /**
     * Runs the upgrade routine once the plugin has loaded
     *
     * @since   1.1.7
     */
    public function upgrade() {

        // Bail if we're not in the WordPress Admin
        if ( ! is_admin() ) {
            return;
        }

        // Run upgrade routine
        $this->get_class( 'install' )->upgrade();

    }

    /**
     * Returns the given class
     *
     * @since   1.9.8
     *
     * @param   string  $name   Class Name
     * @return  object          Class Object
     */
    public function get_class( $name ) {

        // If the class hasn't been loaded, throw a WordPress die screen
        // to avoid a PHP fatal error.
        if ( ! isset( $this->classes->{ $name } ) ) {
            // Define the error
            $error = new WP_Error( 'page_generator_pro_get_class', sprintf( __( '%s: Error: Could not load Plugin class <strong>Page_Generator_Pro_%s</strong>', $this->plugin->name ), $this->plugin->displayName, $name ) );
             
            // Depending on the request, return or display an error
            // Admin UI
            if ( is_admin() ) {  
                wp_die(
                    $error,
                    sprintf( __( '%s: Error', 'page-generator-pro' ), $this->plugin->displayName ),
                    array(
                        'back_link' => true,
                    )
                );
            }

            // Cron / CLI
            return $error;
        }

        // Return the class object
        return $this->classes->{ $name };

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since   1.1.6
     *
     * @return  object Class.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }

        return self::$instance;

    }

}

/**
 * Define the autoloader for this Plugin
 *
 * @since   1.9.8
 *
 * @param   string  $class_name     The class to load
 */
function Page_Generator_Pro_Autoloader( $class_name ) {

    /**
     * Load Vendor Class
     */
    $vendor_packages = array(
        'lsolesen\\pel\\',
    );
    foreach ( $vendor_packages as $vendor_namespace ) {
        // Skip if this isn't a vendor namespace belonging to this Plugin
        if ( substr_compare( $class_name, $vendor_namespace, 0, strlen( $vendor_namespace ) ) !== 0 ) {
            continue;
        }
    
        // Define the file name we need to include
        $path_file = dirname( __FILE__ ) . '/vendor/' . str_replace( '\\', '/', $vendor_namespace ) . str_replace( $vendor_namespace, '', $class_name ) . '.php';
        if ( file_exists( $path_file ) ) {
            require_once( $path_file );
            return;
        }
    }

    /**
     * Load Plugin Class
     */
    $class_start_name = array(
        'Page_Generator_Pro',
    );

    // Get the number of parts the class start name has
    $class_parts_count = count( explode( '_', $class_start_name[0] ) );

    // Break the class name into an array
    $class_path = explode( '_', $class_name );

    // Bail if it's not a minimum length
    if ( count( $class_path ) < $class_parts_count ) {
        return;
    }

    // Build the base class path for this class
    $base_class_path = '';
    for ( $i = 0; $i < $class_parts_count; $i++ ) {
        $base_class_path .= $class_path[ $i ] . '_';
    }
    $base_class_path = trim( $base_class_path, '_' );

    // Bail if the first parts don't match what we expect
    if ( ! in_array( $base_class_path, $class_start_name ) ) {
        return;
    }

    // Define the file name we need to include
    $file_name = strtolower( implode( '-', array_slice( $class_path, $class_parts_count ) ) ) . '.php';

    // Define the paths with file name we need to include
    $include_paths = array(
        dirname( __FILE__ ) . '/includes/admin/' . $file_name,
        dirname( __FILE__ ) . '/includes/global/' . $file_name,
    );

    // Iterate through the include paths to find the file
    foreach ( $include_paths as $path_file ) {
        if ( file_exists( $path_file ) ) {
            require_once( $path_file );
            return;
        }
    }

}
spl_autoload_register( 'Page_Generator_Pro_Autoloader' );

/**
 * Define the WP Cron function to perform the log cleanup
 *
 * @since   2.6.1
 */
function page_generator_pro_log_cleanup_cron() {

    // Initialise Plugin
    $page_generator_pro = Page_Generator_Pro::get_instance();
    $page_generator_pro->initialize();

    // Call CRON Log Cleanup function
    $page_generator_pro->get_class( 'cron' )->log_cleanup();

    // Shutdown
    unset( $page_generator_pro );

}
add_action( 'page_generator_pro_log_cleanup_cron', 'page_generator_pro_log_cleanup_cron' );

/**
 * Define the WP Cron function to perform the generate routine
 *
 * @since   2.6.1
 *
 * @param   int     $group_id   Group ID
 * @param   string  $type       Content Type
 */
function page_generator_pro_generate_cron( $group_id, $type = 'content' ) {

    // Initialise Plugin
    $page_generator_pro = Page_Generator_Pro::get_instance();
    $page_generator_pro->initialize();

    // Call CRON Generate function
    $page_generator_pro->get_class( 'cron' )->generate( $group_id, $type );

    // Shutdown
    unset( $page_generator_pro );

}
add_action( 'page_generator_pro_generate_cron', 'page_generator_pro_generate_cron', 10, 2 );

// Load Activation and Deactivation functions
include_once( dirname( __FILE__ ) . '/includes/admin/activation.php' );
include_once( dirname( __FILE__ ) . '/includes/admin/deactivation.php' );
register_activation_hook( __FILE__, 'page_generator_pro_activate' );
add_action( 'wpmu_new_blog', 'page_generator_pro_activate_new_site' );
add_action( 'activate_blog', 'page_generator_pro_activate_new_site' );
register_deactivation_hook( __FILE__, 'page_generator_pro_deactivate' );

/**
 * Main function to return Plugin instance.
 *
 * @since   1.9.8
 */
function Page_Generator_Pro() {
    
    return Page_Generator_Pro::get_instance();

}

// Finally, initialize the Plugin.
Page_Generator_Pro();