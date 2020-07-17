<?php
/**
 * Visual Composer Woocommerce helpers
 */
if(!function_exists('cvca_ajax_product_filter')){

    function cvca_ajax_product_filter(){

        global $wpdb;

        $atts = array(
            'post_type' => $_POST['post_type'],
            'pagination' => $_POST['pagination'],
            'column' => $_POST['column'],
            'posts_per_page' => $_POST['posts_per_page'],
            'products_type' => $_POST['products_type'],
            'paged' => $_POST['paged'],
            'ignore_sticky_posts' => $_POST['ignore_sticky_posts'],
            'show' => $_POST['show'],
            'orderby' => $_POST['orderby'],
            'col_width' => $_POST['col_width'],
            'element_custom_class' => $_POST['element_custom_class'],
            'filter_attributes' => $_POST['filter_attributes'],
            'filter_tags' => $_POST['filter_tags'],
            'filter_categories' => $_POST['filter_categories'],
            'show_filter' => $_POST['show_filter'],
            'show_featured_filter' => $_POST['show_featured_filter'],
            'show_price_filter'=> $_POST['show_price_filter'],
            'price_filter_level'=> $_POST['price_filter_level'],
            'price_filter_range' => $_POST['price_filter_range'],
            'loadmore' => $_POST['loadmore'],
            'show_stock'=>$_POST['show_stock'],
            'show_rating'=>$_POST['show_rating'],
            'show_qv'=>$_POST['show_qv'],
        );



        $wc_attr = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['posts_per_page'],
            'paged' => $atts['paged'],
            'orderby' => $atts['orderby'],
            'ignore_sticky_posts' => $atts['ignore_sticky_posts'],
        );

        $meta_query = WC()->query->get_meta_query();
        $tax_query = array();

        if(isset($_POST['show'])){


            if ($atts['show'] == 'featured') {

                $meta_query[] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                        'operator' => 'IN'
                    ),
                );

            } elseif ($atts['show'] == 'onsale') {

                $product_ids_on_sale = wc_get_product_ids_on_sale();

                $wc_attr['post__in'] = $product_ids_on_sale;

            } elseif ($atts['show'] == 'best-selling') {

                $wc_attr['meta_key'] = 'total_sales';

            } elseif ($atts['show'] == 'latest'){

                $wc_attr['orderby'] = 'date';

                $wc_attr['order'] = 'DESC';

            } elseif ($atts['show'] == 'toprate'){

                add_filter('posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses'));

            } elseif ($atts['show'] == 'price'){

                $wc_attr['orderby']  = "meta_value_num {$wpdb->posts}.ID";
                $wc_attr['order']    = 'ASC';
                $wc_attr['meta_key'] = '_price';

            } elseif ($atts['show'] == 'price-desc'){

                $wc_attr['orderby']  = "meta_value_num {$wpdb->posts}.ID";
                $wc_attr['order']    = 'DESC';
                $wc_attr['meta_key'] = '_price';

            }

        }


        if(isset($_POST['product_attribute']) && isset($_POST['attribute_value'])){
            if(is_array($_POST['product_attribute'])){
                foreach ($_POST['product_attribute'] as $key => $value) {
                    $tax_query[] = array(
                        'taxonomy' => $value,
                        'terms' => $_POST['attribute_value'][$key],
                        'field'         => 'slug',
                        'operator'      => 'IN'
                    );
                }
            }else {
                $tax_query[] = array(
                    'taxonomy' => $_POST['product_attribute'],
                    'terms' => $_POST['attribute_value'],
                    'field'         => 'slug',
                    'operator'      => 'IN'
                );

            }
        }

        if(isset($_POST['filter_categories'])){
            $wc_attr['product_cat'] = $_POST['filter_categories'];
        }

        if(isset($_POST['product_tag'])){
            $wc_attr['product_tag'] = $_POST['product_tag'];
        }

        if(isset($_POST['price_filter']) && $_POST['price_filter'] > 0 ){

            $min = (intval($_POST['price_filter']) - 1)*intval($_POST['price_filter_range']);
            $max = intval($_POST['price_filter'])*intval($_POST['price_filter_range']);
            $meta_query[] = array(
                'key' => '_price',
                'value' => array($min, $max),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            );
        }

        if(isset($_POST['s']) && $_POST['s'] != '' ){
            $wc_attr['s'] = $_POST['s'];
        }

        $wc_attr['tax_query'] = $tax_query;
        $wc_attr['meta_query'] = $meta_query;
        $atts['wc_attr'] = $wc_attr;
        echo cvca_get_shortcode_view( 'product', $atts,'' );

    }

    add_action('wp_ajax_cvca_ajax_product_filter', 'cvca_ajax_product_filter');
    add_action( 'wp_ajax_nopriv_cvca_ajax_product_filter', 'cvca_ajax_product_filter' );
}
if(!function_exists('cvca_ajax_product_search')){
    function cvca_ajax_product_search(){
        if(isset($_POST['cvca_search'])){
            echo cvca_get_shortcode_view( 'product', '','' );
        }
    }
    add_action('wp_ajax_cvca_ajax_product_search', 'cvca_ajax_product_search');
    add_action( 'wp_ajax_nopriv_cvca_ajax_product_search', 'cvca_ajax_product_search' );
}

// hook into wp pre_get_posts
add_action('pre_get_posts', 'cvca_woo_search_pre_get_posts');

if(!function_exists('cvca_woo_search_pre_get_posts')) {
    function cvca_woo_search_pre_get_posts($q)
    {

        if (is_search()) {
            add_filter('posts_join', 'cvca_search_post_join');
            add_filter('posts_where', 'cvca_search_post_excerpt');
        }
    }
}

if(!function_exists('cvca_search_post_join')) {
    function cvca_search_post_join($join = '')
    {

        global $wp_the_query;

        // escape if not woocommerce searcg query
        if (empty($wp_the_query->query_vars['wc_query']) || empty($wp_the_query->query_vars['cvca_search']))
            return $join;

        $join .= "INNER JOIN wp_postmeta AS ritmeta ON (wp_posts.ID = ritmeta.post_id)";
        return $join;
    }
}

if(!function_exists('cvca_search_post_excerpt')) {
    function cvca_search_post_excerpt($where = ''){

        global $wp_the_query;

        // escape if not woocommerce search query
        if (empty($wp_the_query->query_vars['wc_query']) || empty($wp_the_query->query_vars['cvca_search']))
            return $where;

        $where = preg_replace("/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1)
                    OR (ritmeta.meta_key = '_sku' AND CAST(ritmeta.meta_value AS CHAR) LIKE $1)
                    OR  (ritmeta.meta_key = '_author' AND CAST(ritmeta.meta_value AS CHAR) LIKE $1)
                    OR  (ritmeta.meta_key = '_publisher' AND CAST(ritmeta.meta_value AS CHAR) LIKE $1)
                    OR  (ritmeta.meta_key = '_format' AND CAST(ritmeta.meta_value AS CHAR) LIKE $1 ", $where);

        return $where;
    }
}
if (!function_exists('cvca_current_url')) {
    function cvca_current_url()
    {
        $s = $_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = (false && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host . $s['REQUEST_URI'];
    }
}