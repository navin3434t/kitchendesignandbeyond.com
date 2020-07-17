# Clever-Visual-Composer-Addon
An ultimate addon for Visual Composer and Zoo Theme core.

## For developer

### Add a new shortcode into the plugin directly

 Clever Visual Composer Addon uses standard PSR-4 autoloading mechanism, so any class which is following [standard PHP naming convention](http://www.php-fig.org/bylaws/psr-naming-conventions/) will be loaded automatically. It means that you just need to create a shortcode class, drop it inside the `clever-vc-addon/src/Shortcodes`. There're a couple of thing to keep in mind:

    1. All classes must have the `CVCA\Shortcodes` as namespace or the class won't be loaded.
    2. All classes must have the `register()` method. The plugin doesn't care about how shortcode will be added or integrated with Visual Composer. It will only call the `register()` method as an interface.
    3. The class must have constructor which accepts one `settings` argument - an array of the plugin settings. The settings contains helpful values and configurations of the plugin.

**Example**

```php
<?php namespace CVCA\Shortcodes;
/**
 * SampleShortcode
 */
class SampleShortcode
{
    /**
     * Settings
     *
     * @see    CleverVCAddon::$settings
     *
     * @var    array
     */
    protected $settings;

    /**
     * Constructor
     */
    function __construct( array $settings = array() )
    {
        $this->settings = $settings;
    }

    /**
     * Register
     */
    function register()
    {
        add_shortcode( 'SampleShortcode', array( $this, '_add' ) );

        add_action( 'vc_before_init', array( $this, '_integrateWithVC' ), 0, 0 );
    }

    /**
     * Add shortcode
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     *
     * @param    array    $atts    Users' defined attributes in shortcode.
     *
     * @return    string    $html    Rendered shortcode content.
     */
    function _add( $atts, $content = null )
    {
        $args = shortcode_atts(
            array(
                'default_1' => 'something',
                'default_2' => 'something_else',
                'default_3' => 'other',
            ),
            $atts, 'SampleShortcode'
        );

        $html = $this->render( $args, $content );

        return $html;
    }

    /**
     * Integrate to Visual Composer
     *
     * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
     */
    function _integrateWithVC()
    {
        vc_map(
            array(
                'name'        => esc_html__('Sample Shortcode', 'cvca'),
                'base'        => 'SampleShortcode',
                'icon'        => '',
                'category'    => esc_html__('CleverSoft', 'cvca'),
                'description' => esc_html__('Some descriptions.', 'cvca'),
                'params'      => array(
                    // Some Visual Composer params of the shortcode.
                )
            )
        );
    }

    /**
     * Render shortcode content
     *
     * @param    $atts    Shortcode arguments.
     *
     * @return    string    $html    Rendered shortcode content.
     */
    protected function render( array $atts, $content = null  )
    {
        // Do something to render shortcode here, such as include a view template somewhere.
    }
}
```

### Use the plugin API

Clever Visual Composer Addon has a function called `clever_register_shortcode()` which will register any shortcode on the fly for you. See `clever-vc-addon/src/Helpers/General.php` for more info.

**Example**
```php
<?php
/**
 * Callback
 */
function sample_shortcode_callback(array $atts, $content = null)
{
    // Do some thing to render shortcode attributes and content.
}

/**
 * Register shortcode
 */
clever_register_shortcode(
    'SampleShortcode', // Shortcode name.
    array( // Shortcode default attributes, not user defined attributes.
        'attr_1' => 'Value 1',
        'attr_2' => 'Value 2',
        'attr_3' => 'Value 3'
    ),
    'sample_shortcode_callback',
    array( // Visual composer params for vc_map().
        'name' => esc_html__('Clever Image Gallery', 'text-domain'),
        'base' => 'CleverImageGallery',
        'icon' => '',
        'category' => esc_html__('CleverSoft', 'text-domain'),
        'description' => esc_html__('Show Image Gallery', 'text-domain'),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Title', 'text-domain'),
                'value' => '',
                'param_name' => 'title',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Columns', 'text-domain'),
                'description' => esc_html__('Number columns of layout', 'text-domain'),
                'std' => '3',
                'param_name' => 'columns',
            )
        )        
    )
);
```
### Change default values of shortcode
Allow dev change default values of shortcode in Theme.
**Example**
```php
<?php
/**
 * Sample_shortcode_atts is filter id of shortcode
 * $atts['preset'] is attribute of shortcode
 * new_value is new default value of attribute
 */
add_filter('Sample_shortcode_atts', function ($atts) {
    $atts['preset'] = 'new_value';
    return $atts;
});
```
### Change add/remove attributes of shortcode
Allow dev remove/add of shortcode by using theme functions.
**Example**
```php
<?php
/**
 * SampleShortcode is shortcode name
 * sample_attr is attribute id of shortcode
 * new_value is new default value of attribute
 */
add_action('vc_after_init', function () {
    /*For remove shortcode attribute*/
    vc_remove_param('SampleShortcode', 'sample_attr');
    /*For add shortcode attribute*/
    vc_add_params('SampleShortcode', array(
            array(
                "type" => 'attach_image',
                "heading" => esc_html__('Sample attribute', 'cvca'),
                "param_name" => 'sample_attr'
            ),
        )
    );

}, 10, 0);
```