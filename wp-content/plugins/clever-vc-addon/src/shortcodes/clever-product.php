<?php
/**
 * Clever Product Shortcode
 */
if (!function_exists('cvca_shortcode_products')) {
    function cvca_shortcode_products($atts, $content)
    {

        if (class_exists('WooCommerce')) {
            $product_categories = get_categories(
                array(
                    'taxonomy' => 'product_cat',
                )
            );
            $product_cats = array();
            $product_cats_all = '';
            if (count($product_categories) > 0) {

                foreach ($product_categories as $value) {
                    $product_cats[$value->name] = $value->slug;
                }
                $product_cats_all = implode(',', $product_cats);
            }

            $product_tags = get_terms('product_tag');
            $product_tags_arr = array();
            $product_tags_all = '';
            if (count($product_tags) > 0) {

                foreach ($product_tags as $value) {
                    $product_tags_arr[$value->name] = $value->slug;
                }
                $product_tags_all = implode(',', $product_tags_arr);
            }


            $attributes_arr = array();
            $attributes_arr_all = '';
            if (function_exists('wc_get_attribute_taxonomies')) {
                $product_attribute_taxonomies = wc_get_attribute_taxonomies();
                if (count($product_attribute_taxonomies) > 0) {

                    foreach ($product_attribute_taxonomies as $value) {
                        $attributes_arr[$value->attribute_label] = $value->attribute_name;
                    }
                    $attributes_arr_all = implode(',', $attributes_arr);
                }
            }

            $atts = shortcode_atts(apply_filters('CleverProduct_shortcode_atts',array(
                'post_type' => 'product',
                'column' => '3',
                'col_width' => '170',
                'posts_per_page' => 4,
                'loadmore' => '',
                'products_type' => 'carousel',
                'paged' => 1,
                'ignore_sticky_posts' => 1,
                'show' => '',
                'show_nav' => '1',
                'show_pag' => '',
                'show_rating'=>0,
                'show_qv'=>1,
                'show_stock'=>1,
                'orderby' => 'date',
                'filter_categories' => $product_cats_all,
                'filter_tags' => $product_tags_all,
                'filter_attributes' => $attributes_arr_all,
                'show_filter' => 0,
                'show_featured_filter' => 0,
                'show_price_filter' => 0,
                'price_filter_level' => 5,
                'price_filter_range' => 100,
                'element_custom_class' => '',
                'filter_col' => '4',
                'css' => '',
            )), $atts, 'CleverProduct');


            $meta_query = WC()->query->get_meta_query();


            $wc_attr = array(
                'post_type' => 'product',
                'posts_per_page' => $atts['posts_per_page'],
                'paged' => $atts['paged'],
                'orderby' => $atts['orderby'],
                'ignore_sticky_posts' => $atts['ignore_sticky_posts'],
            );
            if ($atts['show'] == 'featured') {
                $meta_query[] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                        'operator' => 'IN'
                    ),
                );
                $wc_attr['tax_query'] = $meta_query;
            } elseif ($atts['show'] == 'onsale') {

                $product_ids_on_sale = wc_get_product_ids_on_sale();

                $wc_attr['post__in'] = $product_ids_on_sale;

                $wc_attr['meta_query'] = $meta_query;

            } elseif ($atts['show'] == 'best-selling') {

                $wc_attr['meta_key'] = 'total_sales';

                $wc_attr['meta_query'] = $meta_query;

            } elseif ($atts['show'] == 'latest') {

                $wc_attr['orderby'] = 'date';

                $wc_attr['order'] = 'DESC';

            } elseif ($atts['show'] == 'toprate') {

                add_filter('posts_clauses', array('WC_Shortcodes', 'order_by_rating_post_clauses'));

            } elseif ($atts['show'] == 'price') {

                $wc_attr['orderby'] = "meta_value_num {$wpdb->posts}.ID";
                $wc_attr['order'] = 'ASC';
                $wc_attr['meta_key'] = '_price';

            } elseif ($atts['show'] == 'price-desc') {
                $wc_attr['orderby'] = "meta_value_num {$wpdb->posts}.ID";
                $wc_attr['order'] = 'DESC';
                $wc_attr['meta_key'] = '_price';

            } elseif ($atts['show'] == 'deal') {
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $wc_attr['post__in'] = $product_ids_on_sale;
                $wc_attr['meta_query'] = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_sale_price_dates_to',
                        'value' => time(),
                        'compare' => '>'
                    )
                );
            }
            if ($atts['filter_categories'] != $product_cats_all && $atts['filter_categories'] != '') {
                $wc_attr['product_cat'] = $atts['filter_categories'];

            }

            /* Fix for select2 field - default value */
            if ( $atts['filter_categories'] === 'Array' ) {
                $wc_attr['product_cat'] = $product_cats_all;
            }

            $atts['wc_attr'] = $wc_attr;
            $html = cvca_get_shortcode_view( 'product', $atts, $content );

            return $html;
        }
        return null;

    }
}
add_shortcode('CleverProduct', 'cvca_shortcode_products');

add_action('vc_before_init', 'cvca_product_integrate_vc');

if (!function_exists('cvca_product_integrate_vc')) {
    function cvca_product_integrate_vc()
    {
        if (class_exists('WooCommerce')) {
            $product_categories = get_terms('product_cat');
            $product_categories = array_values($product_categories);
            $product_cats = array();
            cvca_create_select_tree(0, $product_categories, 0, $product_cats);
            $product_tags = get_terms('product_tag');
            $product_tags_arr = array();
            $product_tags_all = '';
            if (count($product_tags) > 0) {

                foreach ($product_tags as $value) {
                    $product_tags_arr[$value->name] = $value->slug;
                }
                $product_tags_all = implode(',', $product_tags_arr);
            }


            $attributes_arr = array();
            $attributes_arr_all = '';
            if (function_exists('wc_get_attribute_taxonomies')) {
                $product_attribute_taxonomies = wc_get_attribute_taxonomies();
                if (count($product_attribute_taxonomies) > 0) {

                    foreach ($product_attribute_taxonomies as $value) {
                        $attributes_arr[$value->attribute_label] = $value->attribute_name;
                    }
                    $attributes_arr_all = implode(',', $attributes_arr);
                }
            }


            vc_map(
                array(
                    'name' => esc_html__('Clever Products', 'cvca'),
                    'base' => 'CleverProduct',
                    'icon' => 'icon-rit',
                    'category' => esc_html__('CleverSoft', 'cvca'),
                    'description' => esc_html__('Show multiple products by ID or SKU.', 'cvca'),
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Layout', 'cvca'),
                            'value' => array(
                                esc_html__('Carousel', 'cvca') => 'carousel',
                                esc_html__('Grid', 'cvca') => 'grid',
                                esc_html__('List', 'cvca') => 'list'
                            ),
                            "admin_label" => true,
                            'param_name' => 'products_type',
                            'description' => esc_html__('Select layout type for display product', 'cvca'),
                        ),
                        array(
                            "type" => "cvca_multiselect2",
                            "heading" => esc_html__("Categories", 'cvca'),
                            "param_name" => "filter_categories",
                            "value" => $product_cats,
                            "default" => "all",
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Asset type', 'cvca'),
                            'value' => array(
                                esc_html__('All', 'cvca') => '',
                                esc_html__('Featured product', 'cvca') => 'featured',
                                esc_html__('Onsale product', 'cvca') => 'onsale',
                                esc_html__('Deal product', 'cvca') => 'deal',
                                esc_html__('Best Selling', 'cvca') => 'best-selling',
                                esc_html__('Latest product', 'cvca') => 'latest',
                                esc_html__('Top rate product', 'cvca') => 'toprate ',
                                esc_html__('Sort by price: low to high', 'cvca') => 'price',
                                esc_html__('Sort by price: high to low', 'cvca') => 'price-desc',
                            ),
                            'std' => '',
                            'param_name' => 'show',
                            'description' => esc_html__('Select asset type of products', 'cvca'),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Order by', 'cvca'),
                            'value' => array(
                                esc_html__('Date', 'cvca') => 'date',
                                esc_html__('Menu order', 'cvca') => 'menu_order',
                                esc_html__('Title', 'cvca') => 'title',
                                esc_html__('Random', 'cvca') => 'rand',
                            ),
                            'std' => 'date',
                            'param_name' => 'orderby',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Number of product', 'cvca'),
                            'value' => 6,
                            "admin_label" => true,
                            'param_name' => 'posts_per_page',
                            'description' => esc_html__('Number of product showing', 'cvca'),
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Ignore sticky posts", 'cvca'),
                            "param_name" => "ignore_sticky_posts",
                            'std' => 1,
                            "value" => array(
                                esc_html__('No', 'cvca') => 0,
                                esc_html__('Yes', 'cvca') => 1,
                            ),
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
                            'std' => '1',
                            'group' => esc_html__('Layout', 'cvca'),
                            "dependency" => Array('element' => 'products_type', 'value' => array('carousel')),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => esc_html__('Enable Load more', 'cvca'),
                            'param_name' => 'loadmore',
                            'std' => '',
                            'group' => esc_html__('Layout', 'cvca'),
                            "dependency" => Array('element' => 'products_type', 'value' => array('grid','list')),
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
                            'heading' => esc_html__('Show quickview', 'cvca'),
                            'param_name' => 'show_qv',
                            'std' => '1',
                            'group' => esc_html__('Layout', 'cvca'),
                            'value' => array(esc_html__('Yes', 'cvca') => '1'),
                        ),array(
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
                            'param_name' => 'element_custom_class',
                            'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'cvca'),
                        ),

                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Show Filter", 'cvca'),
                            "param_name" => "show_filter",
                            "admin_label" => true,
                            "dependency" => Array('element' => 'products_type', 'value' => array('grid')),
                            "description" => esc_html__('Number columns product filter on row', 'cvca'),
                            'group' => esc_html__('Filter', 'cvca'),
                            'std' => 0,
                            "value" => array(
                                esc_html__('No', 'cvca') => 0,
                                esc_html__('Yes', 'cvca') => 1,
                            ),
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Filter columns", 'cvca'),
                            "param_name" => "filter_col",
                            "admin_label" => true,
                            "dependency" => Array('element' => 'show_filter', 'value' => array('1')),
                            'group' => esc_html__('Filter', 'cvca'),
                            'std' => 4,
                            "value" => array(
                                esc_html__('1', 'cvca') => 1,
                                esc_html__('2', 'cvca') => 2,
                                esc_html__('3', 'cvca') => 3,
                                esc_html__('4', 'cvca') => 4,
                                esc_html__('5', 'cvca') => 5,
                                esc_html__('6', 'cvca') => 6,
                            ),
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Show Featured, Onsale, Best Selling, Latest product filter", 'cvca'),
                            "param_name" => "show_featured_filter",
                            'std' => '0',
                            'group' => esc_html__('Filter', 'cvca'),
                            "dependency" => Array('element' => 'show_filter', 'value' => '1'),
                            "value" => array(
                                esc_html__('No', 'cvca') => 0,
                                esc_html__('Yes', 'cvca') => 1,
                            ),
                        ),
                        array(
                            "type" => "cvca_multi_select",
                            "heading" => esc_html__("Tags showing in the filter", 'cvca'),
                            "param_name" => "filter_tags",
                            'group' => esc_html__('Filter', 'cvca'),
                            "dependency" => Array('element' => 'show_filter', 'value' => '1'),
                            "std" => $product_tags_all,
                            "value" => $product_tags_arr,
                        ),
                        array(
                            "type" => "cvca_multi_select",
                            "heading" => esc_html__("Product attributes showing in the filter", 'cvca'),
                            "param_name" => "filter_attributes",
                            'group' => esc_html__('Filter', 'cvca'),
                            "dependency" => Array('element' => 'show_filter', 'value' =>'1'),
                            "std" => $attributes_arr_all,
                            "value" => $attributes_arr,
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Show Price Filter", 'cvca'),
                            "param_name" => "show_price_filter",
                            'group' => esc_html__('Filter', 'cvca'),
                            "std" => 0,
                            "dependency" => Array('element' => 'show_filter', 'value' => '1'),
                            "value" => array(
                                esc_html__('No', 'cvca') => 0,
                                esc_html__('Yes', 'cvca') => 1,
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Number of price levels', 'cvca'),
                            'value' => '5',
                            'std' => '5',
                            'group' => esc_html__('Filter', 'cvca'),
                            'param_name' => 'price_filter_level',
                            "dependency" => Array('element' => 'show_price_filter', 'value' =>'1'),
                            'description' => esc_html__('Number of price levels showing in the filter', 'cvca'),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => esc_html__('Filter range', 'cvca'),
                            'std' => '100',
                            'value' => '100',
                            'group' => esc_html__('Filter', 'cvca'),
                            'param_name' => 'price_filter_range',
                            "dependency" => Array('element' => 'show_price_filter', 'value' => '1'),
                            'description' => esc_html__('Range of price filter. Example range equal 100 => price filter are "0$ to 100$", "100$ to 200$"', 'cvca'),
                        ),
                        array(
                            'type'       => 'css_editor',
                            'counterup'  => __( 'Css', 'cvca' ),
                            'param_name' => 'css',
                            'group'      => __( 'Design options', 'cvca' ),
                        ),
                    )
                )
            );
        }
    }
}
