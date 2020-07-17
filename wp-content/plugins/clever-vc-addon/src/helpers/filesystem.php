<?php
/**
 * Filesystem helpers
 *
 * @package  CleverVCAddon\Helpers
 */

/**
 * Load all PHP files inside a directory
 *
 * Since `glob()` is not available on some system, we shouldn't use it.
 *
 * @param  string  $dir      Basedir.
 * @param  array   $exclude  An array of excluded files.
 */
function cvca_load_php_files($dir, $exclude = array())
{
    $base = rtrim($dir, DIRECTORY_SEPARATOR);
    $files = new DirectoryIterator($base);

    foreach ($files as $file) {
        $filename = $file->getFilename();
        $extension = $file->getExtension();
        if ( $file->isDot() || $file->isLink() || $file->isDir() || ( 'php' !== $extension ) || in_array($filename, $exclude) ) {
            continue;
        }
        require $base . DIRECTORY_SEPARATOR . $filename;
    }
}

/**
 * cvca_get_view
 *
 * Like WordPress's `locate_template()`, this function will load|return templates in a specific folder of a theme before falling back to default templates of the plugin.
 *
 * @param    string    $tpl_slug    Template slug.
 * @param    string    $tpl_name    Template name.
 * @param    bool      $load        Whether to load the located template or return.
 */
function cvca_get_view($tpl_slug, $tpl_name = '', $load = false)
{
    $located   = '';
    $templates = array();
    $tpl_name  = trim( str_replace('.php', '', $tpl_name), DIRECTORY_SEPARATOR );

    if ($tpl_name) {
        $templates[] = 'clever-vc-addon/' . $tpl_slug . '-' . $tpl_name . '.php';
        $templates[] = 'clever-vc-addon/' . $tpl_slug . '/' . $tpl_name . '.php';
        $templates[] = 'src/views/' . $tpl_slug . '-' . $tpl_name . '.php';
        $templates[] = 'src/views/' . $tpl_slug . '/' . $tpl_name . '.php';
    }

    $templates[] = 'clever-vc-addon/' . $tpl_slug . '.php';
    $templates[] = 'src/views/' . $tpl_slug . '.php';

    foreach ($templates as $template) {
        if ( file_exists(STYLESHEETPATH . '/' . $template) ) {
            $located = STYLESHEETPATH . '/' . $template;
            break;
        } elseif ( file_exists(TEMPLATEPATH . '/' . $template) ) {
            $located = TEMPLATEPATH . '/' . $template;
            break;
        } elseif ( file_exists(WP_PLUGIN_DIR . '/clever-vc-addon/' . $template) ) {
            $located = WP_PLUGIN_DIR . '/clever-vc-addon/' . $template;
            break;
        } else {
            $located = false;
        }
    }

    if ($located) {
        if ($load) {
            include $located;
        } else {
            return $located;
        }
    } else {
        throw new \InvalidArgumentException( sprintf( __('Failed to load template with slug "%s" and name "%s".', 'cvca'), $tpl_slug, $tpl_name ) );
    }
}

/**
 * cvca_get_shortcode_view
 */
function cvca_get_shortcode_view($tpl_name, array $atts, $content = null)
{
    $output = '';

    try {
        $template = cvca_get_view( 'shortcodes', $tpl_name );
        ob_start();
        include $template;
        $output = ob_get_clean();
    } catch (Exception $e) {
        exit( $e->getMessage() );
    }

    return $output;
}
