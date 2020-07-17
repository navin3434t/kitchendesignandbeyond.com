<?php
/**
 * Clever Product IDs Shortcode
 * Get products with specific ids
 */

function cvca_get_products_data()
{
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );
    $the_query = new \WP_Query($args);
    $results = array();

    if ($the_query->have_posts()):
        while ($the_query->have_posts()): $the_query->the_post();
            $data = array();
            $data['value'] = get_the_ID();
            $data['label'] = get_the_title();
            $results[] = $data;
        endwhile;
    endif;
    wp_reset_postdata();
    return $results;
}

if (!function_exists('cvca_shortcode_products_ids')) {
    function cvca_shortcode_products_ids($atts, $content)
    {
        $atts = shortcode_atts(
            apply_filters('CleverProductIDs_shortcode_atts', array(
                'products_ids' => '',
                'products_type' => 'grid',
                'col_width' => '170',
                'column' => '3',
                'show_pag' => '',
                'show_nav' => '',
                'show_rating' => '',
                'show_qv' => '',
                'show_stock' => '',
                'el_class' => '',
                'css' => ''
            )),
            $atts, 'CleverProductIDs'
        );
        $html = cvca_get_shortcode_view('product-ids', $atts, $content);
        return $html;
    }
}
add_shortcode('CleverProductIDs', 'cvca_shortcode_products_ids');

add_action('vc_before_init', 'cvca_product_ids_integrate_vc');

if (!function_exists('cvca_product_ids_integrate_vc')) {
    function cvca_product_ids_integrate_vc()
    {
        if (class_exists('WooCommerce')) {
            vc_map(
                array(
                    'name' => esc_html__('Clever Products Ids', 'cvca'),
                    'base' => 'CleverProductIDs',
                    'icon' => 'icon-rit',
                    'category' => esc_html__('CleverSoft', 'cvca'),
                    'description' => esc_html__('Show products with specific ids.', 'cvca'),
                    'params' => array(
                        array(
                            "type" => "autocomplete",
                            "heading" => esc_html__("Product IDs", 'cvca'),
                            "description" => esc_html__("comma separated list of post ids", 'cvca'),
                            "param_name" => "products_ids",
                            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'min_length' => 0,
                                'no_hide' => true, // In UI after select doesn't hide an select list
                                'groups' => true, // In UI show results grouped by groups
                                'unique_values' => true, // 0In UI show results except selected. NB! You should manually check values in backend
                                'display_inline' => true, // In UI show results inline view
                                'values' => cvca_get_products_data(),
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Layout', 'cvca'),
                            'value' => array(
                                esc_html__('Carousel', 'cvca') => 'carousel',
                                esc_html__('Grid', 'cvca') => 'grid',
                                esc_html__('List', 'cvca') => 'list'
                            ),
                            "std" => 'grid',
                            "admin_label" => true,
                            'param_name' => 'products_type',
                            'description' => esc_html__('Select layout type for display product', 'cvca'),
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => esc_html__('Layout', 'cvca'),
                            'heading' => esc_html__('Column width', 'cvca'),
                            'std' => '170',
                            "dependency" => Array('element' => 'products_type', 'value' => array('grid')),
                            'param_name' => 'col_width',
                            'description' => esc_html__('Width of one column.', 'cvca'),
                        ),
                        array(
                            'type' => 'textfield',
                            'group' => esc_html__('Layout', 'cvca'),
                            'heading' => esc_html__('Columns number', 'cvca'),
                            'std' => '3',
                            "dependency" => Array('element' => 'products_type', 'value' => array('carousel')),
                            'param_name' => 'column',
                            'description' => esc_html__('Display product with the number of columns, accept only number.', 'cvca'),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show carousel pagination', 'cvca'),
                            'param_name' => 'show_pag',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            "dependency" => Array('element' => 'products_type', 'value' => array('carousel')),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show carousel navigation', 'cvca'),
                            'param_name' => 'show_nav',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            "dependency" => Array('element' => 'products_type', 'value' => array('carousel')),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show rating', 'cvca'),
                            'param_name' => 'show_rating',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show quick view', 'cvca'),
                            'param_name' => 'show_qv',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ), array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show stock status', 'cvca'),
                            'param_name' => 'show_stock',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Custom Class', 'cvca'),
                            'value' => '',
                            'param_name' => 'el_class',
                            'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca'),
                        ),
                        array(
                            'type' => 'css_editor',
                            'counterup' => __('Css', 'cvca'),
                            'param_name' => 'css',
                            'group' => __('Design options', 'cvca'),
                        ),
                    )
                )
            );
        }
    }
}
