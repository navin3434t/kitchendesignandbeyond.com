<?php
/**
 * Zoo_Customize_Builder
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Builder
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
class Zoo_Customize_Builder
{
    /**
     * Registered items
     */
    private $registered_items = [];

    /**
     * Registered builders
     */
    private $registered_builders = [];

    /**
     * Nope
     */
    private function __construct()
    {

    }

    /**
     * Singleton
     */
    public static function get_instance()
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
            $self->add_row(new Zoo_Customize_Header_Builder($self));
            $self->add_row(new Zoo_Customize_Footer_Builder($self));
            if (is_admin()) {
                add_action('init', [$self, '_export_template'], 10, 0);
                add_action('customize_controls_print_footer_scripts', [$self, 'template']);
                add_action('wp_ajax_zoo_builder_save_template', [$self, '_ajax_save_template']);
                add_action('wp_ajax_zoo_builder_update_template', [$self, '_ajax_update_template']);
                add_action('wp_ajax_zoo_builder_install_template', [$self, '_ajax_install_template']);
            }

            $elements = [
                'header/header-sticky',
                'header/html1',
                'header/html2',
                'header/html3',
                'header/html4',
                'header/html5',
                'header/logo',
                'header/account',
                'header/language-switcher',
                'header/wishlist',
                'header/products-compare',
                'header/nav-icon',
                'header/templates',
                'header/search-box',
                'header/top-menu',
                'header/primary-menu',
                'header/mobile-menu',
                'header/button',
                'header/social-icons',
                'header/style',
                'header/widgets',
                'footer/style',
                'footer/widgets',
                'footer/templates',
                'footer/copyright',
                'footer/html1',
                'footer/html2'
            ];

            if (class_exists('WooCommerce', false)) {
                $elements[] = 'header/woocommerce-cart';
            }

            if (class_exists('Easy_Digital_Downloads', false)) {
                $elements[] = 'header/edd-cart';
            }

            foreach ($elements as $element) {
                require ZOO_THEME_DIR . 'core/customize/builder/elements/' . $element . '.php';
            }

            do_action('zoo_add_customize_builder_elements', $self);
        }

        return $self;
    }

    /**
     * Add a row builder
     *
     * Every row must be an instance of a block which will ocupies entire container's width.
     *
     * @param  object  $builder
     */
    public function add_row(Zoo_Customize_Builder_Block $builder)
    {
        add_filter('zoo/customizer/config', [$builder, '_row_customize'], 35, 2);

        $this->registered_builders[$builder::ID] = $builder;
    }

    /**
     * Add builder element
     *
     * @param $builder_id string        Id of panel
     * @param $class      object        Class to handle this item
     * @return bool
     */
    public function add_element($builder_id, Zoo_Customize_Builder_Element $class)
    {
        if (!$builder_id || !isset($this->registered_builders[$builder_id])) {
            throw new Exception(__('Invalid builder panel!', 'fona'));
        }

        if (is_object($class)) {
        } else {
            if (!class_exists($class)) {
                return false;
            }
            $class = new $class();
        }

        if (!isset($this->registered_items[$builder_id])) {
            $this->registered_items[$builder_id] = array();
        }

        $this->registered_items[$builder_id][$class->id] = $class;

        return true;
    }

    /**
     * Get all items for builder panel
     *
     * @param $builder_id string        Id of panel
     * @return array|mixed|void
     */
    public function get_builder_items($builder_id)
    {
        $items = [];

        if (isset($this->registered_items[$builder_id])) {
            foreach ($this->registered_items[$builder_id] as $name => $obj) {
                $configs = $obj->get_builder_configs();
                if (!empty($configs)) {
                    $items[$obj->id] = $configs;
                }
            }
        }

        return apply_filters('zoo/builder/' . $builder_id . '/items', $items);
    }

    /**
     * Get all customize settings of all items for builder panel
     *
     * @param $builder_id string        Id of panel
     * @param null $wp_customize        WP Customize
     * @return array|bool
     */
    public function get_items_customize($builder_id, $wp_customize = null)
    {
        if (!$builder_id || !isset($this->registered_items[$builder_id]))
            return false;

        $configs = [];

        foreach ($this->registered_items[$builder_id] as $name => $obj) {
            $builder_configs = $obj->get_builder_configs();
            $customize_configs = $obj->get_customize_configs($wp_customize);
            foreach ($customize_configs as $config) {
                if (!empty($builder_configs['devices']))
                    $config['device_settings'] = false;
                $configs[] = $config;
            }
        }

        return $configs;
    }

    /**
     * Get a builder item for builder panel
     *
     * @param $builder_id   string        Id of panel
     * @param $item_id      string        Builder item id
     * @return bool
     */
    public function get_builder_item($builder_id, $item_id)
    {
        if (!$builder_id) {
            return false;
        }
        if (!isset($this->registered_items[$builder_id])) {
            return false;
        }

        if (!isset($this->registered_items[$builder_id][$item_id])) {
            return false;
        }

        return $this->registered_items[$builder_id][$item_id];
    }

    /**
     * Handle event save template
     */
    public function _ajax_save_template()
    {
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error(__('Access denied', 'fona'));
        }

        $panel_id        = sanitize_text_field($_POST['panel']);
        $builder_id      = sanitize_text_field($_POST['builder']);
        $option_name     = get_option('template', 'fona').'_'.$builder_id.'_saved_templates';
        $saved_templates = get_option($option_name, []);

        if (!isset($this->registered_builders[$builder_id])) {
            wp_send_json_error(__('Invalid builder ID.', 'fona'));
        }

        if (isset($_POST['remove'])) {
            $remove = sanitize_title($_POST['remove']);
            if (isset($saved_templates[$remove])) {
                unset($saved_templates[$remove]);
            }
            update_option($option_name, $saved_templates);
            wp_send_json_success();
        } elseif (isset($_POST['zooImportTemplate']) && isset($_FILES['zooCustomizeTemplateFile'])) {
            if (empty($_FILES['zooCustomizeTemplateFile']['tmp_name']) || !empty($_FILES['zooCustomizeTemplateFile']['error'])) {
                wp_send_json_error(esc_html__('Invalid import template file.', 'fona'));
            }
            global $wp_filesystem;
            $data = json_decode($wp_filesystem->get_contents($_FILES['zooCustomizeTemplateFile']['tmp_name']), true);
            if (!empty($data['thememods']) && !empty($data['name'])) {
                $tpl_key = sanitize_title($data['name']).'-'.time();
                $data['name'] = '['.esc_html__('Imported', 'fona').'] '.$data['name'];
                $saved_templates[$tpl_key] = $data;
                update_option($option_name, $saved_templates);
                $html = '<li class="saved_template li-boxed" data-tpl-id="'.esc_attr($tpl_key).'" data-builder-id="'.esc_attr($builder_id).'" data-builder-panel="'.esc_attr($panel_id).'">' . esc_html($data['name']) . ' <a href="#" class="delete-tpl"><i class="dashicons dashicons-trash"></i></a><a href="?export-tpl='.esc_attr($tpl_key).'&export-tpl-builder='.esc_attr($builder_id).'" class="export-tpl" title="'.esc_attr__('Export Template', 'fona').'"><i class="dashicons dashicons-upload"></i></a><a href="#" class="import-tpl"><i class="dashicons dashicons-download"></i></a></li>';
                wp_send_json_success(['key_id' => $tpl_key, 'name' => $data['name'], 'li' => $html]);
            }
        } else {
            $tpl_name   = sanitize_text_field($_POST['name']);
            $tpl_key = sanitize_title($tpl_name).'-'.time();
            $saved_templates[$tpl_key] = [
                'name'       => $tpl_name,
                'image'      => '',
                'panel_id'   => $panel_id,
                'widgets'    => get_option('sidebars_widgets'),
                'thememods'  => get_theme_mods()
            ];
            update_option($option_name, $saved_templates);
            set_theme_mod('active_'.$builder_id.'_template', $tpl_key);
            $html = '<li class="saved_template li-boxed active-builder-template" data-tpl-id="'.esc_attr($tpl_key).'" data-builder-id="'.esc_attr($builder_id).'" data-builder-panel="'.esc_attr($panel_id).'">'.esc_html($tpl_name) . ' <a href="#" class="delete-tpl"><i class="dashicons dashicons-trash"></i></a><a href="?export-tpl='.esc_attr($tpl_key).'&export-tpl-builder='.esc_attr($builder_id).'" class="export-tpl" title="'.esc_attr__('Export Template', 'fona').'"><i class="dashicons dashicons-upload"></i></a><a href="#" class="import-tpl"><i class="dashicons dashicons-download"></i></a></li>';
            wp_send_json_success(['key_id' => $tpl_key, 'name' => $tpl_name, 'li' => $html]);
        }
    }

    /**
     * Handle update template event
     */
    public function _ajax_update_template()
    {
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error(__('Access denied', 'fona'));
        }

        $site_url    = get_option('siteurl');
        $builder_id  = sanitize_text_field($_POST['builderId']);
        $template_id = sanitize_text_field($_POST['templateId']);

        if (!isset($this->registered_builders[$builder_id])) {
            wp_send_json_error(__('Invalid builder ID.', 'fona'));
        }

        $option_name     = get_option('template', 'fona').'_'.$builder_id.'_saved_templates';
        $saved_templates = get_option($option_name, []);
        $active_template = get_theme_mod('active_'.$builder_id.'_template');

        if (!$active_template || $active_template !== $template_id || !isset($saved_templates[$active_template])) {
            wp_send_json_error(__('Invalid active template ID.', 'fona'));
        }

        $saved_templates[$active_template]['widgets'] = get_option('sidebars_widgets');
        $saved_templates[$active_template]['thememods'] = get_theme_mods();

        $updated = update_option($option_name, $saved_templates);

        if ($updated) {
            wp_send_json_success(esc_html__('Active template updated succesfully!', 'fona'));
        } else {
            wp_send_json_error(__('Failed to update the active template.', 'fona'));
        }
    }

    /**
     * Handle AJAX import builder template
     */
    public function _ajax_install_template()
    {
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error(__('Access denied', 'fona'));
        }

        $mods        = (array)get_theme_mods();
        $mod_key     = is_child_theme() ? 'theme_mods_'.ZOO_CHILD_THEME_SLUG : 'theme_mods_'.get_option('template', 'fona');
        $_widgets    = get_option('sidebars_widgets');
        $panel_id    = sanitize_text_field($_POST['panelId']);
        $builder_id  = sanitize_text_field($_POST['builderId']);
        $template_id = sanitize_text_field($_POST['tplId']);

        if (!isset($this->registered_builders[$builder_id])) {
            wp_send_json_error(__('Invalid builder ID.', 'fona'));
        }

        $tpl_data = zoo_customize_get_builder_template_data($template_id, $builder_id);

        if ($panel_id !== $tpl_data['panel_id']) {
            wp_send_json_error(__('Invalid panel ID.', 'fona'));
        }

        // If setting key belong to the builder of the template but its value is not set, use deault value.
        foreach ($mods as $key => $value) {
            if (false !== strpos($key, $builder_id) && !isset($tpl_data['thememods'][$key])) {
                $mods[$key] = zoo_customize_get_setting($key, 'all', false, true);
            }
        }

        // Migrate template's data.
        foreach ($tpl_data['thememods'] as $key => $value) {
            if ('custom_logo' === $key || (false !== strpos($key, $builder_id))) {
                $mods[$key] = $value;
            }
        }

        $mods['active_'.$builder_id.'_template'] = $template_id;

        $widgets = !empty($tpl_data['widgets']) ? (array)$tpl_data['widgets'] : [];

        // Install builder widgets from templates.
        foreach ($widgets as $key => $value) {
            if (false !== strpos($key, $builder_id) && !empty($value)) {
                $_widgets[$key] = $value;
            }
        }

        delete_option('sidebars_widgets');

        if (!add_option('sidebars_widgets', $_widgets)) {
            wp_send_json_error(__('Failed to update widgets location.', 'fona'));
        }

        delete_option($mod_key);

        if (!add_option($mod_key, $mods)) {
            wp_send_json_error(__('Failed to update theme mods.', 'fona'));
        }

        wp_send_json_success();
    }

    /**
     * Export template
     */
    function _export_template()
    {
        if (!isset($_GET['export-tpl']) || !current_user_can('edit_theme_options')) {
            return;
        }

        $tpl_id = sanitize_text_field($_GET['export-tpl']);
        $builder_id = sanitize_text_field($_GET['export-tpl-builder']);

        if (!isset($this->registered_builders[$builder_id])) {
            return;
        }

        $templates = get_option(get_option('template', 'fona').'_'.$builder_id.'_saved_templates', []);

        if (!isset($templates[$tpl_id])) {
            return;
        }

        header('Content-Disposition: attachment; filename='.$tpl_id.'.json');
        header('Content-Type: application/json; charset='.get_option('blog_charset'));

        exit(json_encode($templates[$tpl_id]));
    }

    /**
     *  Get all builders registered.
     *
     * @return array
     */
    public function get_builders()
    {
        static $builders = [];

        if (!$builders) {
            foreach ($this->registered_builders as $id => $builder) {
                $config          = $builder->get_config();
                $config['rows']  = $builder->get_rows_config();
                $config['items'] = $this->get_builder_items($id);
                $builders[$id]   = $config;
            }
        }

        return $builders;
    }

    /**
     * Panel Builder Template
     */
    public function template()
    {
        ?><script type="text/html" id="tmpl-zoo-customize-builder-panel">
        <div class="zoo-customize-builder-panel">
            <div class="zoo-customize-builder-inner">
                <div class="zoo-customize-builder-header">
                    <div class="zoo-customize-builder-label"> <# if (data.id === 'header') { #><?php esc_html_e('Header Builder','fona')?>
                        <# }else{ #>
                        <?php esc_html_e('Footer Builder','fona')?>
                        <# } #></div>
                    <div class="zoo-customize-builder-devices-switcher"></div>
                    <div class="zoo-customize-builder-actions">
                        <# if (data.id === 'header') { #>
                        <a data-id="header_sticky" class="zoo-customize-focus-button button button-secondary" href="javascript:void(0)"><?php esc_html_e('Header Sticky', 'fona'); ?></a>
                        <# } #>
                        <a data-id="{{ data.id }}_builder_style" class="zoo-customize-focus-button button button-secondary" href="javascript:void(0)"><?php esc_html_e('Style', 'fona'); ?></a>
                        <a data-id="{{ data.id }}_templates" class="zoo-customize-focus-button button button-secondary" href="javascript:void(0)"><?php esc_html_e('Templates', 'fona'); ?></a>
                        <a class="button button-secondary zoo-customize-builder-panel-minimize" href="javascript:void(0)">
                            <span class="zoo-customize-builder-minimize-text"><?php esc_html_e('Minimize', 'fona'); ?></span>
                            <span class="zoo-customize-builder-title-text">{{ data.title }}</span>
                        </a>
                    </div>
                </div>
                <div class="zoo-customize-builder-body"></div>
            </div>
        </div>
    </script>

        <script type="text/html" id="tmpl-zoo-customize-builder-rows">
            <div class="zoo-customize-builder-rows">
                <# if (!_.isUndefined( data.rows.top)) { #>
                <div class="zoo-row-top zoo-customize-builder-row" data-id="{{ data.id }}_top">
                    <a class="zoo-customize-builder-row-settings" title="{{ data.rows.top }}" data-id="top" href="javascript:void(0)"></a>
                    <div class="zoo-customize-builder-inner-row">
                        <div class="zoo-customize-row-grid">
                            <?php for ($i = 1; $i <= 12; $i ++) echo '<div></div>'; ?>
                        </div>
                        <div class="zoo-customize-builder-items grid-stack gridster" data-id="top"></div>
                    </div>
                </div>
                <#  } #>
                <# if (!_.isUndefined( data.rows.main)) { #>
                <div class="zoo-row-main zoo-customize-builder-row" data-id="{{ data.id }}_main">
                    <a class="zoo-customize-builder-row-settings" title="{{ data.rows.main }}" data-id="main" href="javascript:void(0)"></a>
                    <div class="zoo-customize-builder-inner-row">
                        <div class="zoo-customize-row-grid">
                            <?php for ($i = 1; $i <= 12; $i ++) echo '<div></div>'; ?>
                        </div>
                        <div class="zoo-customize-builder-items grid-stack gridster" data-id="main"></div>
                    </div>
                </div>
                <#  } #>
                <# if (!_.isUndefined( data.rows.bottom)) { #>
                <div class="zoo-row-bottom zoo-customize-builder-row" data-id="{{ data.id }}_bottom">
                    <a class="zoo-customize-builder-row-settings" title="{{ data.rows.bottom }}" data-id="bottom" href="javascript:void(0)"></a>
                    <div class="zoo-customize-builder-inner-row">
                        <div class="zoo-customize-row-grid">
                            <?php for ($i = 1; $i <= 12; $i ++) echo '<div></div>'; ?>
                        </div>
                        <div class="zoo-customize-builder-items grid-stack gridster" data-id="bottom"></div>
                    </div>
                </div>
                <#  } #>
            </div>
            <# if (!_.isUndefined( data.rows.sidebar)) { #>
            <div class="zoo-customize-builder-sidebar">
                <div class="zoo-customize-builder-row zoo-customize-builder-row-sidebar" data-id="{{ data.id }}_sidebar">
                    <a class="zoo-customize-builder-row-settings" title="{{ data.rows.sidebar }}" data-id="sidebar" href="javascript:void(0)"></a>
                    <div class="zoo-customize-builder-inner-row">
                        <div class="zoo-customize-builder-items zoo-customize-builder-sidebar-items" data-id="sidebar"></div>
                    </div>
                </div>
                <div>
                    <# } #>
        </script>

        <script type="text/html" id="tmpl-zoo-customize-builder-item">
            <div class="grid-stack-item item-from-list for-s-{{ data.section }}"
                 title="{{ data.name }}"
                 data-id="{{ data.id }}"
                 data-section="{{ data.section }}"
                 data-control="{{ data.control }}"
                 data-gs-x="{{ data.x }}"
                 data-gs-y="{{ data.y }}"
                 data-gs-width="{{ data.width }}"
                 data-df-width="{{ data.width }}"
                 data-gs-height="1"
            >
                <div class="item-tooltip" data-section="{{ data.section }}">{{ data.name }}</div>
                <div class="grid-stack-item-content">
                    <span class="zoo-customize-builder-item-name" data-section="{{ data.section }}">{{ data.name }}</span>
                    <span class="zoo-customize-builder-item-remove zoo-customize-builder-icon"></span>
                    <span class="zoo-customize-builder-item-setting zoo-customize-builder-icon" data-section="{{ data.section }}"></span>
                </div>
            </div>
        </script><?php
    }
}
Zoo_Customize_Builder::get_instance();
