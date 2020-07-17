<?php namespace CVCA\helpers;
/**
 * ShortcodeFactory
 *
 * A simple shortcode factory.
 */
class ShortcodeFactory
{
    /**
     * Shortcode name
     *
     * @var  string
     */
    protected $name;

    /**
     * Shortcode attributes
     *
     * @var  array
     */
    protected $atts;

    /**
     * Render callback
     *
     * @var  array|string
     */
    protected $callback;

    /**
     * Visual Composer arguments
     *
     * @var  array
     */
    protected $vc_args;

    /**
     * Constructor
     */
    function __construct($name, array $atts, $callback, $vc_args = array())
    {
        $this->name = $name;
        $this->atts = $atts;
        $this->vc_args = $vc_args;
        $this->callback = $callback;
    }

    /**
     * Create
     *
     * @see  https://developer.wordpress.org/reference/functions/remove_shortcode/
     */
    function create()
    {
        if ( !is_callable($this->callback) ) {
            throw new \InvalidArgumentException( esc_html__('The callback is not callable.', 'cvca') );
        }

        // If duplicate shortcode name, override it.
        remove_shortcode($this->name);
        add_shortcode( $this->name, array($this, '_add') );

        if ( !empty($this->vc_args) ) {
            add_action( 'vc_before_init', array( $this, '_integrateWithVC' ), 0, 0 );
        }
    }

    /**
     * Add a shortcode
     *
     * @param  array  $atts  User's defined attributes
     * @param  string  $content  Shortcode content.
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @see  https://developer.wordpress.org/reference/functions/add_shortcode/
     */
    function _add(array $atts, $content = null)
    {
        $atts = shortcode_atts($this->atts, $atts, $this->name);

        $html = call_user_func_array( $this->callback, array($atts, $content) );

        return $html;
    }

    /**
     * Integrate to Visual Composer
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     */
    function _integrateWithVC()
    {
        if ( empty($this->vc_args) ) return;

        vc_map($this->vc_args);
    }
}
