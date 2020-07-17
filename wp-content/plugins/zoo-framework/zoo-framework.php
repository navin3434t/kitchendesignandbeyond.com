<?php
/**
 * Plugin Name: Zoo Framework
 * Plugin URI:  http://wpplugin.zootemplate.com/zoo-framework
 * Description: WordPress theme framework from Zootemplate.
 * Author:      Zootemplate
 * Version:     1.0.0
 * Author URI:  http://zootemplate.com
 * Text Domain: zoo-framework
 */
final class ZooFramework
{
    /**
     * Version
     *
     * @var    string
     */
    const VERSION = '1.0.0';

    /**
     * Option name
     *
     * @var    string
     */
    const OPTION_NAME = 'zoo_framework_settings';

    /**
     * Hook suffix
     *
     * @var  string
     */
    public $hook_suffix;

    /**
     * Plugin settings
     *
     * @var    array
     */
    private $settings;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->settings = get_option(self::OPTION_NAME) ? : array();
        $this->settings['basedir']  = __DIR__ . '/';
        $this->settings['baseuri']  = preg_replace('/^http(s)?:/', '', plugins_url('/', __FILE__));
    }

    /**
     * Singleton
     */
    static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new self;
            register_activation_hook(__FILE__, array($instance, '_activate'));
            register_deactivation_hook(__FILE__, array($instance, '_deactivate'));
            add_action('plugins_loaded', array($instance, '_install'), 10, 0);
            register_uninstall_hook(__FILE__, 'ZooFramework::_uninstall');
        }
    }

    /**
     * Do activation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_activation_hook/
     *
     * @param    bool    $network    Whether activating this plugin on network or a single site.
     */
    function _activate($network)
    {
        try {
            $this->preActivate();
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        add_option(self::OPTION_NAME, array(
            'header_scripts'  => '',
            'footer_scripts'  => '',
            'google_map_api'  => '',
            'google_font_api' => '',
            'import_settings' => '',
        ));
    }

    /**
     * Do installation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/hooks/plugins_loaded/
     */
    function _install()
    {
        load_plugin_textdomain('zoo-framework', false, $this->settings['basedir'] . 'i18n');

        require $this->settings['basedir'] . 'src/helpers/formatting.php';
        require $this->settings['basedir'] . 'src/classes/class-zoo-logger.php';
        require $this->settings['basedir'] . 'src/helpers/custom-functions.php';

        if (function_exists('Sensei')) {
            require $this->settings['basedir'] . 'src/classes/class-zoo-sensei-settings-import-export.php';
        }
    }

    /**
     * Do deactivation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_deactivation_hook/
     *
     * @param    bool    $network    Whether deactivating this plugin on network or a single site.
     */
    function _deactivate($network)
    {
        flush_rewrite_rules(false);
    }

    /**
     * Do uninstallation
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see    https://developer.wordpress.org/reference/functions/register_uninstall_hook/
     */
    static function _uninstall()
    {
        delete_option(self::OPTION_NAME);
    }

    /**
     * Pre-activation
     */
    private function preActivate()
    {
        $wp_compare = version_compare($GLOBALS['wp_version'], '4.4');
        $php_compare = version_compare(phpversion(), '5.4');

        if ($wp_compare < 0) {
            throw new Exception( sprintf('Whoops, Zoo Theme requires %1$s version %2$s at least. Please delete this theme and upgrade %1$s to latest version for better perfomance and security.', 'WordPress', '4.4') );
        }

        if ($php_compare < 0) {
            throw new Exception( sprintf('Whoops, Zoo Theme requires %1$s version %2$s at least. Please delete this theme and upgrade %1$s to latest version for better perfomance and security.', 'PHP', '5.4') );
        }
    }
}
ZooFramework::getInstance();
