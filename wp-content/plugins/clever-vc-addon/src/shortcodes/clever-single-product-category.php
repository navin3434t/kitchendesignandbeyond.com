<?php
/**
 * Clever Product IDs Shortcode
 * Get products with specific ids
 */

function cvca_get_product_cats()
{
    $cats=get_terms('product_cat');
    $cats = array_values($cats);
    $results= array();
    foreach($cats as $cat){
        $data = array();
        $data['value'] =$cat->slug;
        $data['label'] = $cat->name;
        $results[] = $data;
    }
    return $results;
}

if (!function_exists('cvca_shortcode_single_product_category')) {
    function cvca_shortcode_single_product_category($atts, $content)
    {
        $atts = shortcode_atts(
            apply_filters('CleverSingleProductCategory_shortcode_atts', array(
                'product_cat' => '',
                'cat_img' => '',
                'show_count' => '',
                'count_extend' => '',
                'count_extends' => '',
                'readmore' => '',
                'el_class' => '',
                'css' => ''
            )),
            $atts, 'CleverSingleProductCategory'
        );
        $html = cvca_get_shortcode_view('single-product-category', $atts, $content);
        return $html;
    }
}
add_shortcode('CleverSingleProductCategory', 'cvca_shortcode_single_product_category');

add_action('vc_before_init', 'cvca_single_product_category_integrate_vc');

if (!function_exists('cvca_single_product_category_integrate_vc')) {
    function cvca_single_product_category_integrate_vc()
    {
        if (class_exists('WooCommerce')) {
            vc_map(
                array(
                    'name' => esc_html__('Clever Single Product Category', 'cvca'),
                    'base' => 'CleverSingleProductCategory',
                    'icon' => 'icon-rit',
                    'category' => esc_html__('CleverSoft', 'cvca'),
                    'description' => esc_html__('Show single category banner.', 'cvca'),
                    'params' => array(
                        array(
                            "type" => "autocomplete",
                            "heading" => esc_html__("Product category", 'cvca'),
                            "description" => esc_html__("Select category you want use", 'cvca'),
                            "param_name" => "product_cat",
                            "admin_label" => true,
                            'settings' => array(
                                'multiple' => false,
                                'sortable' => true,
                                'min_length' => 0,
                                'no_hide' => true, // In UI after select doesn't hide an select list
                                'groups' => true, // In UI show results grouped by groups
                                'unique_values' => true, // 0In UI show results except selected. NB! You should manually check values in backend
                                'display_inline' => true, // In UI show results inline view
                                'values' => cvca_get_product_cats(),
                            ),
                        ),
                        array(
                            'type' => 'attach_image',
                            'heading' => esc_html__( 'Image', 'cvca' ),
                            'param_name' => 'cat_img',
                            'description' => esc_html__( 'Image of category, leave it blank if you want use category config', 'cvca' )
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Show product count', 'cvca'),
                            'param_name' => 'show_count',
                            'std' => '',
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Count text when category have only 1 product', 'cvca'),
                            'param_name' => 'count_extend',
                            'std' => '',
                            "dependency" => Array('element' => 'show_count', 'value' => array('1')),
                        ), array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Count text when category have more than 1 product', 'cvca'),
                            'param_name' => 'count_extends',
                            'std' => '',
                            "dependency" => Array('element' => 'show_count', 'value' => array('1')),
                        ),array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Read More text', 'cvca'),
                            'param_name' => 'readmore',
                            'std' => '',
                            'description' => esc_html__('Leave it blank if don\'t want show read more button.', 'cvca'),
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
