<?php
/**
 */

namespace Zoo\Frontend\Ajax;

function zoo_ln_get_product_list()
{
    global $wp_query;

    $args = [];

    $GLOBALS['zoo_ln_data']['is_ajax'] = 1;
    $GLOBALS['zoo_ln_data']['need_reset_paging'] = 0;

    if (!isset($_POST['zoo_ln_form_data'])) {
        wp_send_json_error(__('Invalid request data!', 'clever-layered-navigation'));
    } else {
        parse_str($_POST['zoo_ln_form_data'], $post_args);
    }

    $wc_query = \Zoo\Frontend\Hook\process_filter(new \WP_Query());

    $args['post_type'] = 'product';

    if (!empty($post_args['vendor_store_author_id'])) {
        $args['author'] = $post_args['vendor_store_author_id'];
    }

    $args['paged']          = !empty($post_args['paged']) ? intval($post_args['paged']) : 1;
    $args['order']          = $post_args['order'];
    $args['posts_per_page'] = !empty($post_args['posts_per_page']) ? intval($post_args['posts_per_page']) : get_option('posts_per_page');
    $args['meta_key']       = $wc_query->get('meta_key');
    $args['meta_query']     = $wc_query->get('meta_query');
    $args['tax_query']      = (array)$wc_query->get('tax_query');
    $args['product_cat']    = $wc_query->get('product_cat');
    $args['wc_query']       = 'product_query';
    $args['post__in']       = $wc_query->get('post__in');

    $hidden_tax_query = false;

    if (!empty($args['tax_query'])) {
        foreach ($args['tax_query'] as $_query) {
            if (array_search('product_visibility', $_query)) {
                $hidden_tax_query = true;
                break;
            }
        }
    }

    if (!$hidden_tax_query) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_visibility',
            'field'    => 'slug',
            'terms'    => 'exclude-from-catalog',
            'operator' => 'NOT IN'
        ];
    }

    switch ($post_args['orderby']) {
        case 'menu_order':
            $args['orderby'] = 'menu_order title';
        break;
        case 'title':
            $args['orderby'] = 'title';
            $args['order']   = ('DESC' === $post_args['order']) ? 'DESC' : 'ASC';
        break;
        case 'relevance':
            $args['orderby'] = 'relevance';
            $args['order']   = 'DESC';
        break;
        case 'date':
            $args['orderby'] = 'date ID';
            $args['order']   = ('ASC' === $post_args['order']) ? 'ASC' : 'DESC';
        break;
        case 'price':
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = '_price';
            $args['order']    = 'asc';
        break;
        case 'price-desc':
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = '_price';
            $args['order']    = 'desc';
        break;
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            add_filter('posts_clauses', function($filtering_args) {
                global $wpdb;
        		$filtering_args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
        		return $filtering_args;
            });
        break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby']  = array(
                'meta_value_num' => 'DESC',
                'ID' => 'ASC',
            );
        break;
    }

    // Compatibility issue. See https://github.com/woocommerce/woocommerce/blob/master/includes/class-wc-template-loader.php#L275
    // apply_filters('woocommerce_shortcode_products_query', $args)

    $wp_query = new \WP_Query(apply_filters('cln_products_query_args', $args));

    $item_id = 0;

    if (isset($_POST['zoo_ln_form_data'])) {
        parse_str($_POST['zoo_ln_form_data'], $post_option);
        $item_id = ($post_option['filter_list_id']);
    }

    ob_start();

    require ZOO_LN_TEMPLATES_PATH . 'product-list.php';
    $html_ul_products_content = ob_get_contents();
    ob_clean();

    woocommerce_result_count();
    $html_result_count_content = ob_get_contents();
    ob_clean();

    wc_get_template('loop/pagination.php');
    $html_pagination_content = ob_get_contents();
    ob_clean();

    $selected_filter_option = \Zoo\Frontend\Hook\get_activated_filter($post_args);

    if (!$selected_filter_option) {
        $html_active_list_item = '';
    } else {
        require ZOO_LN_TEMPLATES_PATH . 'view/active-filter/items.php';
        $html_active_list_item = ob_get_contents();
    }

    ob_end_clean();

    wp_reset_postdata();

    $return['html_ul_products_content'] = $html_ul_products_content;
    $return['html_result_count_content'] = $html_result_count_content;
    $return['html_pagination_content'] = preg_replace('/<\/*nav[^>]*>/', '', $html_pagination_content);
    $return['html_active_list_item'] = $html_active_list_item;

    wp_send_json($return);

}
