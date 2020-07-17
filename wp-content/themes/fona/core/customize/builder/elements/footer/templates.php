<?php
/**
 * Zoo_Customize_Builder_Element_Footer_Templates
 *
 * @package  Zoo_Theme\Core\Customize\Builder\Elements
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Builder_Element_Footer_Templates extends Zoo_Customize_Builder_Element
{
    public $id = 'footer_templates';

    public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
    {
        $theme_options      = get_option(ZOO_SETTINGS_KEY, true);
        $option_name        = get_option('template', 'fona').'_footer_saved_templates';
        $active_tpl         = get_theme_mod('active_footer_template');
        $saved_templates    = get_option($option_name, []);
        $prebuilt_templates = get_option(get_option('template', 'fona').'_footer_prebuilt_templates', []);
        $tpl_count          = count($saved_templates);

        if (!$tpl_count && is_dir(ZOO_THEME_DIR.'inc/customize/presets')) {
            global $wp_filesystem;
            $presets = new DirectoryIterator(ZOO_THEME_DIR.'inc/customize/presets');
            foreach ($presets as $preset) {
                if ($preset->isFile()) {
                    $preset_name = $preset->getFilename();
                    if (false !== strpos($preset_name, 'footer') && 'json' === $preset->getExtension()) {
                        $template_data =  (array)json_decode($wp_filesystem->get_contents(ZOO_THEME_DIR.'inc/customize/presets/'.$preset_name), true);
                        $saved_templates[rtrim($preset_name, '.json')] = $template_data;
                    }
                }
            }
            update_option($option_name, $saved_templates);
        }

        $saved_templates = array_reverse($saved_templates);

        $html = '<span class="customize-control-title">'.esc_html__('Saved Templates', 'fona').'</span>';
        $html .= '<ul class="list-saved-templates footer_settings-saved-templates list-boxed '.($tpl_count > 0 ? 'has-templates' : 'no-templates').'">';
        if ($tpl_count > 0) {
            foreach ($saved_templates as $key => $tpl) {
                if ($key === $active_tpl) {
                    $active_class = ' active-builder-template';
                } else {
                    $active_class = '';
                }
                $html .= '<li class="saved_template li-boxed'.$active_class.'" data-tpl-id="'.esc_attr($key).'" data-builder-id="footer" data-builder-panel="footer_builder_panel">'.esc_html($tpl['name']).' <a href="#" class="delete-tpl" title="'.esc_attr__('Delete Template', 'fona').'"><i class="dashicons dashicons-trash"></i></a><a href="?export-tpl='.esc_attr($key).'&export-tpl-builder=footer" class="export-tpl" title="'.esc_attr__('Export Template', 'fona').'"><i class="dashicons dashicons-upload"></i></a><a href="#" class="import-tpl" title="'.esc_attr__('Install Template', 'fona').'"><i class="dashicons dashicons-download"></i></a></li>';
            }
        }
        $html .= '<li class="no_template">'.esc_html__('No saved templates.', 'fona').'</li>';
        if ($prebuilt_templates) {
            $html .= '<span class="customize-control-title">'.esc_html__('Prebuilt Templates', 'fona').'</span>';
            foreach ($prebuilt_templates as $key => $tpl) {
                if ($key === $active_tpl) {
                    $active_class = ' active-builder-template';
                } else {
                    $active_class = '';
                }
                $html .= '<li class="prebuilt-tpl-item li-boxed'.$active_class.'" data-tpl-id="'.esc_attr($key).'" data-builder-id="footer" data-builder-panel="footer_builder_panel">
                <img class="tpl-thumbnail" src="'.esc_url($tpl['image']).'" title="'.esc_attr($tpl['name']).'"></li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>';

        $fields = [
            [
                'name'     => 'footer_templates',
                'type'     => 'section',
                'panel'    => 'footer_settings',
                'priority' => 9999999,
                'title'    => esc_html__('Templates', 'fona'),
            ]
        ];

        if (!empty($theme_options['enable_dev_mode'])) {
            $fields[] = [
                'name'           => 'footer_templates_import',
                'type'           => 'custom_html',
                'section'        => 'footer_templates',
                'theme_supports' => '',
                'transport'      => 'postMessage',
                'title'          => esc_html__('Import Template', 'fona'),
                'description'    => '<div class="save-template-form"><p class="description customize-control-description">'.esc_html__('Import a footer template and use it later.', 'fona').'</p><div class="zoo-flexbox"><input type="file" data-builder-id="footer" data-builder-panel="footer_builder_panel"  class="zoo-customize-import-template-file" name="zoo-customize-import-file" accept="*.json"><button type="button" class="button button-primary zoo-customize-import-template-button">'.esc_html__('Import', 'fona').'</button></div></div>',
            ];
        }

        $fields[] = [
            'name'           => 'footer_templates_save',
            'type'           => 'custom_html',
            'section'        => 'footer_templates',
            'theme_supports' => '',
            'title'          => esc_html__('Save Template', 'fona'),
            'description'    => '<div class="save-template-form"><p class="description customize-control-description">'.esc_html__('Save current footer layout and use it later.', 'fona').'</p><div class="zoo-flexbox"><input type="text" data-builder-id="footer" data-builder-panel="footer_builder_panel" class="template-input-name change-by-js"><button class="button button-primary save-builder-template" type="button">'.esc_html__('Save', 'fona').'</button></div></div>'.$html
        ];

        return $fields;
    }

    function get_builder_configs()
    {
        return [];
    }

    function render()
    {

    }
}

$self->add_element('footer', new Zoo_Customize_Builder_Element_Footer_Templates());
