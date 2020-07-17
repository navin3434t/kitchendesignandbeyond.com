<?php
/**
 * Licensing and Update Manager Class
 * 
 * @package      Licensing Update Manager
 * @author       Tim Carr
 * @version      3.0.0
 * @copyright    WP Zinc
 */
class LicensingUpdateManager {

    /**
     * Flag to determine if we've queried the remote endpoint
     * for updates. Prevents plugin update checks running
     * multiple times
     *
     * @since   1.0.0
     *
     * @var     boolean
     */
    public $update_check = false;

    /**
     * Constructor.
     *
     * @since   1.0.0
     * 
     * @param   object  $plugin    WordPress Plugin
     * @param   string  $endpoint  Licensing Endpoint
     */
    public function __construct( $plugin, $endpoint ) {

        global $pagenow;

        // Set Plugin and Endpoint
        $this->plugin = $plugin;
        $this->endpoint = $endpoint;
        
        // Admin Notice
        $this->notice = new stdClass;

        if ( is_admin() ) {
            /**
             * Updates
             * - Delete cache if we're forcing an update check via WordPress Admin > Updates
             */
            if ( $pagenow == 'update-core.php' && isset( $_GET['force-check'] ) ) {
                $this->cache_delete();
            }

            /**
             * Licensing Screen
             */
            if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->plugin->name ) {
                if ( isset( $_POST[ $this->plugin->name ] ) && is_array( $_POST[ $this->plugin->name ] ) && array_key_exists( 'licenseKey', $_POST[ $this->plugin->name ] ) ) {
                    update_option( $this->plugin->name . '_licenseKey', sanitize_text_field( $_POST[ $this->plugin->name ]['licenseKey'] ) );
                }

                // Force license key check
                $this->check_license_key_valid( true );
            } else {
                // Check license key, trusting cache
                $this->check_license_key_valid( false );
            }

            // Hooks and Filters
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );
            add_filter( 'all_plugins', array( $this, 'maybe_filter_plugin_name' ) );
        } else {
            // Check license key, trusting cache
            $this->check_license_key_valid( false );
        }

        // Check for updates, outside of is_admin() so WP-CLI is supported
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ), 50 );
        add_action( 'delete_site_transient_update_plugins', array( $this, 'cache_delete' ) );
    
    }

    /**
     * Outputs Administration Notices relating to license key validation
     *
     * @since 3.0.0
     */
    public function admin_notices() {

        // Get cache
        $cache = $this->cache_get();
        
        // Bail if there is no message to display
        if ( ! isset( $cache['message'] ) ) {
            return;
        }
        if ( empty( $cache['message'] ) ) {
            return;
        }

        // If the license isn't valid and we have a message to show the user, show it now and exit
        if ( ! $cache['valid'] ) {
            ?>
            <div class="notice error"><p><?php echo $cache['message']; ?></p></div>
            <?php
            return;
        }

        // If here, the license is valid. Only show that it's valid if we're on the Licensing Screen
        // so we don't bombard the user with this message site-wide
        $screen = get_current_screen();
        if ( $screen->base == 'toplevel_page_' . $this->plugin->name ||
            ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == $this->plugin->name ) ) {
            ?>
            <div class="notice updated"><p><?php echo $cache['message']; ?></p></div>
            <?php
            return;
        }
    
    }

    /**
     * Gets the license key from either the wp-config constant, or the options table
     *
     * @since   3.0.0
     *
     * @return  string  License Key
     */
    public function get_license_key() {

        // If the license key is defined in wp-config, use that
        if ( $this->is_license_key_a_constant() ) {
            // Get from wp-config
            $license_key = constant( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' );
        } else {
            // Get from options table
            $license_key = get_option( $this->plugin->name . '_licenseKey' );
        }

        return $license_key;

    }

    /**
     * Returns a flag denoting whether the license key is stored as a PHP constant
     *
     * @since   3.0.0
     *
     * @return  bool
     */
    public function is_license_key_a_constant() {

        return defined( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' );

    }
    
    /**
     * Checks whether a license key has been specified in the settings table.
     * 
     * @since   3.0.0
     *
     * @return  bool    License Key Exists
     */                   
    public function check_license_key_exists() {

        // Get license key
        $license_key = $this->get_license_key();
        
        // Return license key
        return ( ( isset( $license_key ) && trim( $license_key ) != '' ) ? true : false );
    
    }   
    
    /**
     * Checks whether the license key stored in the settings table exists and is valid.
     *
     * If so, we store the latest remote plugin details in our own 'cache', which can then be used when
     * updating plugins.
     * 
     * @since   3.0.0
     *
     * @param   bool $force     Force License Key Check, ignoring cache
     * @return  bool            License Key Valid
     */
    public function check_license_key_valid( $force = false ) { 

        // If no license key exists, license is not valid
        if ( ! $this->check_license_key_exists() ) {
            $this->cache_set( false, $this->plugin->displayName . __( ': Please specify a license key on the Licensing screen.', $this->plugin->name ) );
            return false;
        }

        // Check last result from cache, provided it has not expired
        if ( ! $force ) {
            $cache = $this->cache_get();

            if ( $cache['expires'] ) {
                return (bool) $cache['valid'];
            }
        }

        // If here, we're either forcing a check, the cache does not exist or the cache has expired.

        // Get site URL, excluding http(s), and whether this is an MS install
        $site_url = str_replace( parse_url( get_bloginfo( 'url' ), PHP_URL_SCHEME ) . '://', '', get_bloginfo( 'url' ) );
        $ip_address = ( isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : false );
        $is_multisite = ( is_multisite() ? '1' : '0' );

        // Get license key
        $license_key = $this->get_license_key();

        // Build endpoint
        $url = $this->endpoint . "/index.php?request=checkLicenseKeyIsValid&params[]=" . $license_key . '&params[]=' . $this->plugin->name . '&params[]=' . urlencode( $site_url ) . '&params[]=' . $is_multisite . '&params[]=' . $this->plugin->version . '&params[]=' . get_bloginfo( 'version' );

        // Send license key check
        // Set user agent to beat aggressive caching
        $response = wp_remote_get( $url, array(
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36',
        ) );
        
        // Check response
        if ( is_wp_error( $response ) ) {
            // Depending on the error, perhaps show a more helpful response
            switch( $response->get_error_code() ) {
                /**
                 * Licensing Server not responding
                 */
                case 'http_request_failed':
                    $message = sprintf(
                            __( 'Unable to communicate with the licensing server. %s will continue to function, but if this error persists, 
                                please open a <a href="%s">support request</a> including the following information:<br />
                                Product Name: %s<br />
                                Product Version: %s<br />
                                Domain: %s<br />
                                IP Address: %s', $this->plugin->name ),
                            $this->plugin->displayName,
                            $this->plugin->support_url,
                            $this->plugin->displayName,
                            $this->plugin->version,
                            $site_url,
                            $ip_address
                        );
                    break;

                /**
                 * Other error
                 */
                default:
                    $message = $this->plugin->displayName . ': ' . $response->get_error_message();
                    break;
            }

            // Permit plugin usage but no updates
            $this->cache_set( true, $message );
            return true;
        }

        // Retrieve the response code and body content
        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        // Bail if the HTTP response code is an error
        if ( $code != 200 && $code != 301 ) {
            // Permit plugin usage but no updates
            $this->cache_set( true, $this->plugin->displayName . ': ' . sprintf(
                __( 'Licensing Server HTTP %s Error.', 'lum' ),
                $code
            ) );
            return true;
        }

        // Bail if the response body is empty
        if ( empty( $body ) ) {
            // Permit plugin usage but no updates
            $this->cache_set( true, $this->plugin->displayName . ': ' . sprintf(
                __( 'Licensing Server HTTP %s Error.', 'lum' ),
                $code
            ) );
            return true;
        }

        // Decode the body JSON into an array
        $result = json_decode( $body );

        // Store information
        $this->cache_set( 
            (int) $result->code,
            $this->plugin->displayName . ': ' . (string) $result->codeDescription,
            ( isset( $result->productVersion ) ? (string) $result->productVersion : 0 ),
            ( isset( $result->product ) ? $result->product : '' ),
            ( isset( $result->features ) ? $result->features : '' ),
            ( isset( $result->features_parameters ) ? $result->features_parameters : '' )
        );

        // Return license validity
        return (int) $result->code;

    }  

    /**
     * Checks to see if the License has access to a given Feature.
     *
     * @since   1.0.0
     *
     * @param   string  $feature    Feature
     */
    public function has_feature( $feature ) {

        // Get cache
        $cache = $this->cache_get();

        // If no features, bail
        if ( empty( $cache['features'] ) || ! $cache['features'] || ! is_array( $cache['features'] ) ) {
            return false;
        }

        // If the feature isn't set, bail
        if ( ! in_array( $feature, $cache['features'] ) ) {
            return false;
        }

        return true;

    }

    /**
     * Returns a feature's parameter (such as whitelabelling > display name), if
     * the license's license type permits the feature and the parameter exists
     * in either wp-config (1st) or the license payload (2nd)
     *
     * @since   1.0.0
     *
     * @param   string  $feature        Feature
     * @param   string  $parameter      Parameter
     * @param   mixed   $default_value  Default Value, if Feature or Feature Parameter is not defined
     * @return  mixed                   bool| string
     */
    public function get_feature_parameter( $feature, $parameter, $default_value ) {

        // Check the license has the feature
        $has_feature = $this->has_feature( $feature );
        if ( ! $has_feature ) {
            return $default_value;
        }

        // If the Feature Parameter exists in wp-config, use that
        if ( defined( strtoupper( $this->plugin->name ) . '_' . strtoupper( $parameter ) ) ) {
            // Convert to an array depending on the parameter
            switch ( $parameter ) {
                case 'show_submenus':
                case 'permitted_users':
                    return explode( ',', constant( strtoupper( $this->plugin->name ) . '_' . strtoupper( $parameter ) ) );
                    break;

                default:
                    return constant( strtoupper( $this->plugin->name ) . '_' . strtoupper( $parameter ) );
                    break;
            } 
        }

        // Check if the Feature Parameter exists in the license payload's cache
        $cache = $this->cache_get();

        // If no feature parameter exists, bail
        if ( ! isset( $cache['features_parameters']->{ $feature } ) ) {
            return $default_value; // assumptive
        }
        if ( ! isset( $cache['features_parameters']->{ $feature }->{ $parameter } ) ) {
            return $default_value;
        }

        // Return feature parameter
        return $cache['features_parameters']->{ $feature }->{ $parameter };

    }
    
    /**
     * Hooks into the plugin update check process, telling WordPress if a newer version of our
     * Plugin is available.
     *
     * @since   3.0.0
     *
     * @param   array   $transient  Transient
     * @return  array               Transient Plugin Data
     */
    public function api_check( $transient ) {

        // If we haven't called the licensing endpoint (which includes product update info),
        // do so now.
        if ( ! $this->update_check ) {
            $this->update_check = true;

            // If the license key isn't valid, bail
            if ( ! $this->check_license_key_valid( true ) ) {
                return $transient;
            }
        }

        // Get remote package data from cache
        // This was populated by the update/license checks earlier
        $cache = $this->cache_get();

        // If cache has a newer version available, show this in WordPress
        if ( ! empty( $cache['version'] ) && $cache['version'] > $this->plugin->version ) {
            // New version available - add to transient
            $response               = new stdClass;
            $response->slug         = $this->plugin->name;
            $response->plugin       = $this->plugin->name . '/' . $this->plugin->name . '.php';
            $response->new_version  = $cache['version'];

            // Package is only available in the cache if the license key is valid
            // Expired or Domain Exceeded licenses won't have this data, but we
            // want to show the user that their product is out of date by setting
            // the new version above.
            if ( ! empty( $cache['package'] ) ) {
                $response->url          = $cache['package']->homepage;
                $response->requires     = $cache['package']->requires;
                $response->tested       = $cache['package']->tested;

                if ( isset( $cache['package']->download_link ) ) {
                    $response->package      = $cache['package']->download_link;
                }
            }
            
            // Add response to transient array
            $transient->response[ $this->plugin->name . '/' . $this->plugin->name . '.php' ] = $response;
        }
   
        return $transient;

    }

    /**
     * Hooks into the plugins_api process, telling WordPress information about our plugin, such
     * as the WordPress compatible version and the changelog.
     *
     * @since 3.0.0
     *
     * @param object    $api    The original plugins_api object.
     * @param string    $action The action sent by plugins_api.
     * @param array     $args   Additional args to send to plugins_api.
     * @return object           New stdClass with plugin information on success, default response on failure.
     */
    public function plugins_api( $api, $action = '', $args = null ) {

        // Check if we are getting info for our plugin
        $plugin = ( 'plugin_information' == $action ) && isset( $args->slug ) && ( $this->plugin->name == $args->slug );
        if ( ! $plugin ) {
            return $api;
        }

        // Get remote package data from cache
        // This was populated by the update/license checks earlier
        $cache = $this->cache_get();

        // Create a new stdClass object and populate it with our plugin information.
        $api                        = new stdClass;
        $api->name                  = $this->plugin->displayName;
        $api->slug                  = $this->plugin->name;
        $api->plugin                = $this->plugin->name . '/' . $this->plugin->name . '.php';
        $api->version               = $cache['version'];

        // Package is only available in the cache if the license key is valid
        // Expired or Domain Exceeded licenses won't have this data, but we
        // want to show the user that their product is out of date by setting
        // the new version above.
        if ( ! empty( $cache['package'] ) ) {
            $api->author                = $cache['package']->author;
            $api->author_profile        = $cache['package']->author_profile;
            $api->requires              = $cache['package']->requires;
            $api->tested                = $cache['package']->tested;
            $api->last_updated          = date( 'Y-m-d H:i:s', $cache['package']->last_updated );
            $api->homepage              = $cache['package']->homepage;
            $api->sections['changelog'] = $cache['package']->changelog;

            if ( isset( $cache['package']->download_link ) ) {
                $api->download_link         = $cache['package']->download_link;
            }
        }

        // Return the new API object with our custom data.
        return $api;

    }

    /**
     * Filter the Plugin Name, Author Name and Plugin URI
     * if whitelabelling is enabled.
     *
     * @since   1.0.0
     *
     * @param   array   $plugins    All Installed Plugins
     * @return  array               All Installed Plugins
     */
    public function maybe_filter_plugin_name( $plugins ) {

        // Bail if whitelabelling isn't available
        if ( ! $this->has_feature( 'whitelabelling' ) ) {
            return $plugins;
        }

        // Bail if this Plugin isn't in the list
        if ( ! isset( $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ] ) ) {
            return $plugins;
        }

        // Get whitelabelling values
        $display_name = $this->get_feature_parameter( 'whitelabelling', 'display_name', $this->plugin->displayName );
        $author_name = $this->get_feature_parameter( 'whitelabelling', 'author_name', $this->plugin->author_name );
        $support_url = $this->get_feature_parameter( 'whitelabelling', 'support_url', $this->plugin->support_url );
        
        // Change the Plugin Name, Author Name and URIs
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['Name'] = $display_name;
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['Title'] = $display_name;
        
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['Author'] = $author_name;
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['AuthorName'] = $author_name;
        
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['PluginURI'] = $support_url;
        $plugins[ $this->plugin->name . '/' . $this->plugin->name . '.php' ]['AuthorURI'] = $support_url;
    
        // Return
        return $plugins;

    }

    /**
     * Fetches cached data from the WordPress options table
     *
     * @since   1.0.0
     *
     * @return  array   Cached Data
     */
    private function cache_get() {

        // Define defaults
        $defaults = array(
            'valid'     => 0,
            'message'   => '',
            'version'   => 0,
            'package'   => '',
            'features'  => '',
            'expires'   => 0,
        );

        // Get cache
        $cache = get_option( $this->plugin->name . '_lum', $defaults );

        // If the cache has expired, delete it and return the defaults
        if ( strtotime( 'now' ) > $cache['expires'] ) {
            $this->cache_delete();
            return $defaults;
        }

        // Return cached data
        return $cache;

    } 

    /**
     * Sets cached data in the WordPress options table for a day
     *
     * @since   1.0.0
     *
     * @param   bool    $valid                  License Key Valid
     * @param   string  $message                License Key Message
     * @param   string  $version                Remote Package Version Available
     * @param   object  $package                Package Details
     * @param   array   $features               Package Features
     * @param   array   $features_parameters    Package Features Parameters
     */
    private function cache_set( $valid = false, $message = '', $version = '', $package = '', $features = '', $features_parameters = '' ) {

        update_option( $this->plugin->name . '_lum', array(
            'valid'                 => $valid,
            'message'               => $message,
            'version'               => $version,
            'package'               => $package,
            'features'              => $features,
            'features_parameters'   => $features_parameters,
            'expires'               => time() + DAY_IN_SECONDS,
        ) );

        // Clear options cache, so that persistent caching solutions
        // have to fetch the latest options data from the DB
        wp_cache_delete( 'alloptions', 'options' );
        wp_cache_delete( $this->plugin->name . '_lum', 'options' );

    }

    /**
     * Deletes the cached data in the WordPress option table
     *
     * @since   1.0.0
     */
    public function cache_delete() {

        delete_option( $this->plugin->name . '_lum' );

        // Clear options cache, so that persistent caching solutions
        // have to fetch the latest options data from the DB
        wp_cache_delete( 'alloptions', 'options' );
        wp_cache_delete( $this->plugin->name . '_lum', 'options' );

    } 

}