<?php
/**
 * Plugin Name: Clever Visual Composer Addon
 * Plugin URI:  http://www.zootemplate.com/wordpress-plugins/clevervcaddon
 * Description: An untimate addon for Visual Composer and Zoo Theme Core.
 * Author:      Zootemplate
 * Version:     1.0.2
 * Author URI:  http://zootemplate.com/
 * Text Domain: cvca
 */

/**
 * Plugin version
 *
 * @var  string
 */
define( 'CVCA_VERSION', '1.0.2' );

/**
 * Plugin DIR
 *
 * @var  string
 */
define( 'CVCA_DIR', __DIR__ . '/' );

/**
 * Plugin URI
 *
 * @var  string
 */
define( 'CVCA_URI', preg_replace('/^http(s)?:/', '', plugins_url( '/', __FILE__ ) ) );

/**
 * Do activation
 *
 * @param  bool  $network
 *
 * @internal  Used as a callback
 */
function cvca_activate($network)
{
    $php_compare = version_compare(phpversion(), '5.3.2');

    try {
        if ($php_compare < 0) {
            throw new \Exception('Clever Visual Composer Addon requires PHP version 5.3.2 at least. Please upgrade to latest version for better performance and security!');
        }
    } catch (Exception $e) {
        exit( $e->getMessage() );
    }

    flush_rewrite_rules(false);
}
register_activation_hook( __FILE__, 'cvca_activate' );

/**
 * Do deactivation
 *
 * @param  bool  $network
 *
 * @internal  Used as a callback
 */
function cvca_deactivate($network)
{
    flush_rewrite_rules(false);
}
register_deactivation_hook( __FILE__, 'cvca_deactivate' );

/**
 * Do installation
 *
 * @internal  Used as a callback
 */
function cvca_install()
{
    require CVCA_DIR . 'src/helpers/filesystem.php';
    require CVCA_DIR . 'vendor/vafpress-post-formats-ui/vp-post-formats-ui.php';

    load_plugin_textdomain('cvca', false, CVCA_DIR . 'i18n');

    cvca_load_php_files(CVCA_DIR . 'src/helpers', array('filesystem.php'));
    cvca_load_php_files(CVCA_DIR . 'src/post-types');
    require CVCA_DIR . 'src/meta-data/meta-boxes.php';
    add_action('after_setup_theme', function(){cvca_load_php_files(CVCA_DIR . 'src/shortcodes');}, 10, 0);
}
add_action( 'plugins_loaded', 'cvca_install', 10, 0 );
