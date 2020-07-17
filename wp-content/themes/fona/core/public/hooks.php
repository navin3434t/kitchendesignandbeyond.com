<?php
/**
 * Public Hooks
 *
 * Hooks used on public screens only.
 *
 * @package  Zoo_Theme\Core\Admin
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

// Maybe search products by SKU only.
add_action('pre_get_posts', function($query) {
    if (!$query->is_main_query() || !$query->is_search()) {
        return;
    }

    if (zoo_customize_get_setting('header_search_box_by_product_sku_only')) {
        global $wpdb;
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $query->get('s')));
        if ($product_id) {
            wp_redirect(get_permalink($product_id));
            exit;
        }
    }
}, PHP_INT_MAX);

// Maybe do search in titles only.
 add_filter('posts_search', function($s, $query)
 {
     if (empty($s) || !zoo_customize_get_setting('header_search_box_by_title_only')) {
         return $s;
     }

     if (!empty($query->query_vars['search_terms'])) {
         global $wpdb;
         $q = $query->query_vars;
         $n = ! empty($q['exact']) ? '' : '%';
         $s = [];
         foreach ((array)$q['search_terms'] as $term) {
             $s[] = $wpdb->prepare("$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like($term) . $n);
         }
         if (!is_user_logged_in()) {
             $s[] = "$wpdb->posts.post_password = ''";
         }
         $s = ' AND ' . implode(' AND ', $s);
     }

     return $s;
 }, PHP_INT_MAX, 2);

 /**
  * Add drop-down icon for menus
  *
  * @see  https://developer.wordpress.org/reference/hooks/nav_menu_item_title/
  */
 add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
     if (in_array('menu-item-has-children', $item->classes)) {
         $menu_locations = get_nav_menu_locations();
         if (!empty($menu_locations['primary-menu']) && $menu_locations['primary-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_primary_menu_hide_arrow')) {
             $title .= '<span class="zoo-icon-down"></span>';
         } elseif (!empty($menu_locations['top-menu']) && $menu_locations['top-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_top_menu_hide_arrow')) {
             $title .= '<span class="zoo-icon-down"></span>';
         } elseif (!empty($menu_locations['mobile-menu']) && $menu_locations['mobile-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_mobile_menu__hide-arrow')) {
             $title .= '<span class="zoo-icon-down"></span>';
         }
     }

     return $title;
 }, 25, 4);

/**
 * Show different widgets base on page footer template.
 */
add_action('zoo_before_rendering_footer_widgets', function ($widget_id) {
    add_filter('sidebars_widgets', function (array $widgets) {
        $page_id = zoo_get_current_page_id();
        $footer_template = get_post_meta($page_id, 'zoo_meta_page_footer_template', true);

        if ($footer_template && 'inherit' != $footer_template) {
            $footer_template_data = zoo_customize_get_builder_template_data($footer_template, 'footer');
            if (isset($footer_template_data['widgets'])) {
                return $footer_template_data['widgets'];
            }
        }

        return $widgets;
    }, PHP_INT_MAX);
}, PHP_INT_MAX);

/**
 * Render site header
 */
add_action('zoo_render_site_header', 'Zoo_Customize_Header_Builder::render');

/**
 * Render site footer
 */
add_action('zoo_render_site_footer', 'Zoo_Customize_Footer_Builder::render');

/**
 * @see  https://developer.wordpress.org/reference/hooks/template_include/
 */
if (class_exists('WooCommerce', false)) {
    add_filter('template_include', function ($tpl) {
        global $wp_query;

        $wishlist_page_enable = zoo_customize_get_setting('zoo_enable_wishlist_redirect');
        $compare_page_enable = zoo_customize_get_setting('zoo_enable_compare_redirect');

        if (!$wp_query->is_main_query()) {
            return $tpl;
        }

        $wishlist_page = get_theme_mod('zoo_wishlist_page');
        $compare_page = get_theme_mod('zoo_compare_page');

        if ($wishlist_page_enable && $wishlist_page && $wp_query->is_page($wishlist_page)) {
            return ZOO_THEME_DIR . 'woocommerce/wishlist/my-wishlist.php';
        }

        if ($compare_page_enable && $compare_page && $wp_query->is_page($compare_page)) {
            return ZOO_THEME_DIR . 'woocommerce/compare/my-compare.php';
        }

        return $tpl;
    }, 99);
}
/**
 * @see  https://developer.wordpress.org/reference/hooks/wp_head/
 */
add_action('wp_enqueue_scripts', function () {
    $settings = get_option(ZOO_SETTINGS_KEY);

    if (!empty($settings['header_scripts'])) {
        wp_add_inline_script('jquery-core', wp_unslash($settings['header_scripts']));
    }
}, PHP_INT_MAX, 0);

/**
 * @see  https://developer.wordpress.org/reference/hooks/wp_head/
 */
add_action('wp_enqueue_scripts', function () {
    $settings = get_option(ZOO_SETTINGS_KEY);
    if (!empty($settings['footer_scripts'])) {
        wp_add_inline_script('zoo-scripts', wp_unslash($settings['footer_scripts']));
    }
}, PHP_INT_MAX, 0);

/**
 * Enqueue frontend styles and scripts
 */
add_action('wp_enqueue_scripts', function () {
    $zoo_auto_css = Zoo_Customize_Live_CSS::get_instance();
    $theme_options = get_option(ZOO_SETTINGS_KEY, []);

    unset($theme_options['header_scripts'], $theme_options['footer_scripts']);

    $theme_options['isRtl'] = is_rtl();
    $theme_options['ajaxUrl'] = admin_url('admin-ajax.php');

    wp_enqueue_style('clever-font', ZOO_THEME_URI . 'assets/vendor/cleverfont/style.min.css', [], ZOO_THEME_VERSION);
    wp_enqueue_style('zoo-theme-builder-elements', ZOO_THEME_URI . 'core/assets/css/elements.min.css', [], ZOO_THEME_VERSION);

    if (class_exists('WooCommerce', false)) {
        if(get_theme_mod('zoo_enable_wishlist','1')){
            wp_enqueue_script('zoo-wishlist', ZOO_THEME_URI . 'core/assets/js/wishlist' . ZOO_JS_SUFFIX, ['jquery-core'], ZOO_THEME_VERSION, true);
            $add_to_wishlist_icon = get_theme_mod('zoo_icon_add_to_wishlist', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-heart-o']);
            if ($add_to_wishlist_icon) {
                $add_to_wishlist_icon = '<i class="' . $add_to_wishlist_icon['icon'] . '"></i> ';
            } else {
                $add_to_wishlist_icon = '';
            }
            $browse_to_wishlist_icon = get_theme_mod('zoo_icon_browse_to_wishlist', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-heart']);
            if ($browse_to_wishlist_icon) {
                $browse_to_wishlist_icon = '<i class="' . $browse_to_wishlist_icon['icon'] . '"></i> ';
            } else {
                $browse_to_wishlist_icon = '';
            }

            wp_localize_script('zoo-wishlist', 'zooWishlistCDATA', [
                'addToWishlist' => get_theme_mod('zoo_text_add_to_wishlist', esc_html__('Add to Wishlist', 'fona')),
                'addToWishlistIcon' => $add_to_wishlist_icon,
                'browseWishlist' => get_theme_mod('zoo_text_browse_to_wishlist', esc_html__('Browse Wishlist', 'fona')),
                'browseWishlistIcon' => $browse_to_wishlist_icon,
                'addToWishlistErr' => esc_html__('Failed to add the item to Wishlist.', 'fona'),
                'wishlistIsEmpty' => esc_html__('Wishlist is empty.', 'fona')
            ]);
        }
        if(get_theme_mod('zoo_enable_compare','1')) {
            wp_enqueue_script( 'zoo-products-compare', ZOO_THEME_URI . 'core/assets/js/products-compare' . ZOO_JS_SUFFIX, [ 'jquery-core' ], ZOO_THEME_VERSION, true );
            $add_to_compare_icon = get_theme_mod( 'zoo_icon_add_to_compare', [
                'type' => 'zoo-icon',
                'icon' => 'zoo-icon-refresh'
            ] );
            if ( $add_to_compare_icon ) {
                $add_to_compare_icon = '<i class="' . $add_to_compare_icon['icon'] . '"></i> ';
            } else {
                $add_to_compare_icon = '';
            }
            $browse_to_compare_icon = get_theme_mod( 'zoo_icon_browse_to_compare', [
                'type' => 'zoo-icon',
                'icon' => 'zoo-icon-refresh'
            ] );
            if ( $browse_to_compare_icon ) {
                $browse_to_compare_icon = '<i class="' . $browse_to_compare_icon['icon'] . '"></i> ';
            } else {
                $browse_to_compare_icon = '';
            }

            wp_localize_script( 'zoo-products-compare', 'zooProductsCompareCDATA', [
                'addToCompare'      => get_theme_mod( 'zoo_text_add_to_compare', esc_html__( 'Add to Compare', 'fona' ) ),
                'addToCompareIcon'  => $add_to_compare_icon,
                'browseCompare'     => get_theme_mod( 'zoo_text_browse_to_compare', esc_html__( 'Browse Compare', 'fona' ) ),
                'browseCompareIcon' => $browse_to_compare_icon,
                'addToCompareErr'   => esc_html__( 'Failed to add the item to compare list.', 'fona' ),
                'compareIsEmpty'    => esc_html__( 'No products to compare.', 'fona' )
            ] );
        }
    }
    wp_enqueue_script('zoo-theme-builder-elements', ZOO_THEME_URI . 'core/assets/js/elements' . ZOO_JS_SUFFIX, [], ZOO_THEME_VERSION, true);

    wp_localize_script('jquery-core', 'zooThemeSettings', $theme_options);

    wp_add_inline_style('zoo-styles', $zoo_auto_css->auto_css());

    if ($google_fonts_url = $zoo_auto_css->get_font_url()) {
        wp_enqueue_style('zoo-google-fonts', $google_fonts_url, [], ZOO_THEME_VERSION);
    }
}, 10, 0);

/**
 * @see  https://developer.wordpress.org/reference/hooks/body_class/
 */
add_filter('body_class', function ($classes) {
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    $classes[] = zoo_site_layout() . '-layout';
    $sidebar_vertical_border = zoo_customize_get_setting('sidebar_vertical_border');

    if ($sidebar_vertical_border == 'sidebar_vertical_border') {
        $classes[] = 'sidebar_vertical_border';
    }

    if (is_customize_preview()) {
        $classes[] = 'customize-previewing';
    }

    $site_layout = zoo_customize_get_setting('site_layout');

    if ($site_layout) {
        $classes[] = sanitize_text_field($site_layout);
    }

    $animate = zoo_customize_get_setting('header_sidebar_animate');

    if (!$animate) {
        $animate = 'menu_sidebar_slide_left';
    }

    $classes[] = $animate;

    return $classes;
});

/**
 * @see  https://developer.wordpress.org/reference/hooks/default_title/
 * @see  https://developer.wordpress.org/reference/hooks/default_content/
 * @see  https://developer.wordpress.org/reference/functions/current_filter/
 */
if (function_exists('pll_get_post')) { // make sure that Polylang activated.
    function zoo_localize_pll_post_content($content, $post)
    {
        $filter = current_filter();
        $from_post = isset($_GET['from_post']) ? (int)$_GET['from_post'] : false;

        if ($content == '') {
            $from_post = get_post($from_post);
            if ($from_post) {
                switch ($filter) {
                    case 'default_content':
                        $content = $from_post->post_content;
                        break;
                    case 'default_title':
                        $content = $from_post->post_title;
                        break;
                    default:
                        $content = apply_filters('zoo_localize_pll_post_content', $content, $from_post);
                        break;
                }
            }
        }

        return $content;
    }

    add_filter('default_title', 'zoo_localize_pll_post_content', 100, 2);
    add_filter('default_content', 'zoo_localize_pll_post_content', 100, 2);
}
