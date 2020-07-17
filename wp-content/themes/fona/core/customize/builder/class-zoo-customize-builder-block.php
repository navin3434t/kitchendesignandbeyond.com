<?php
/**
 * Zoo_Customize_Builder_Block
 *
 * @package  Zoo_Theme\Core\Customize\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
abstract class Zoo_Customize_Builder_Block
{
    /**
     * Layout builder
     *
     * @object  Zoo_Customize_Builder
     */
    protected $layout_builder;

    /**
     * Constructor
     */
    function __construct(Zoo_Customize_Builder $builder)
    {
        $this->layout_builder = $builder;
    }

    /**
     * Add customize settings for this panel if needed.
     *
     * @return  array
     */
    abstract public function customize();

    /**
     * Add add customize config for each row
     *
     * If you want to add config for special row e.g: `top`:
     * You can add more method in your class example:
     * function `row_top_config` for row `top` settings
     * function `row_main_config` for row `main` settings
     *
     * @return array
     */
    abstract public function row_config();

    /**
     * Get Rows Config
     *
     * Available rows: top, main, bottom, sidebar
     *
     * @return array
     */
    abstract public function get_rows_config();

    /**
     * Get all customize settings and register them into WP Customize
     *
     * @see Zoo_Customizer::register()
     *
     * @param array $configs
     * @param null $wp_customize
     * @return array
     */
    public function _row_customize(array $configs = [], WP_Customize_Manager $wp_customize = null)
    {
        $config = $this->customize($wp_customize);
        $rows = $this->get_rows_config();

        foreach ($rows as $id => $name) {
            $m = 'row_' . $id . '_config';
            if (method_exists($this, $m)) {
                $config = array_merge($config, (array)call_user_func_array([$this, $m], [static::ID . '_' . $id, $name]));
            } else {
                if (method_exists($this, 'row_config')) {
                    $config = array_merge($config, $this->row_config(static::ID . '_' . $id, $name));
                }
            }
        }

        $items_config = $this->layout_builder->get_items_customize(static::ID, $wp_customize);

        if (is_array($items_config)) {
            $config = array_merge($config, $items_config);
        }

        return array_merge($configs, $config);
    }
}
