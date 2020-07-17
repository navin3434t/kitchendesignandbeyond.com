<?php
/**
 */


wp_enqueue_style('zoo-ln-style');
wp_enqueue_style('cleverfont');
wp_enqueue_script('zoo-ln-frontend');
wp_enqueue_script('zoo-ln-frontend-jquery-ui');

$item = \Zoo\Helper\Data\get_filter_item_with_short_code($tag);

$item_id = $item['item_id'];

$data = \Zoo\Frontend\Hook\prepare_data($item_id);

$raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'general-setting');
$general_setting = (array)json_decode($raw_data);
$raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'filter-style');
$filter_style = (array)json_decode($raw_data);
$raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'advanced-setting');
$advanced_setting = (array)json_decode($raw_data);
$is_clever_swatches_active = in_array('clever-swatches/clever-swatches.php', (array)get_option('active_plugins', array()));
$advanced_setting['filter_logic_operator'] = !empty($advanced_setting['filter_logic_operator']) ? $advanced_setting['filter_logic_operator'] : 'AND';

// Add custom JS.
if (!empty($advanced_setting['custom_js_before_filter'])) {
    wp_add_inline_script('zoo-ln-frontend', ' jQuery(document).bind(\'zoo_ln_before_filter\', function (event, response) {'.wp_unslash($advanced_setting['custom_js_before_filter']).'});');
}
if (!empty($advanced_setting['custom_js_after_filter'])) {
    wp_add_inline_script('zoo-ln-frontend', 'jQuery(document).bind(\'zoo_ln_after_filter\', function (event, response) {'.wp_unslash($advanced_setting['custom_js_after_filter']).'});');
}

// Use closure to print out filter styles via the `wp_footer` hook.
if (!empty($filter_style['filter_max_height'])) {
    wp_enqueue_script('scrollbar');
    add_action('wp_footer', function () use ($filter_style) {
        ?>
        <style>
            .zoo-layer-nav .zoo-list-filter-item {
                max-height: <?php echo $filter_style['filter_max_height'] ?>px;
            }
            .zoo-layer-nav ul.zoo-list-filter-item {
                max-height: <?php echo $filter_style['filter_max_height'] ?>px !important;
            }
        </style>
        <?php
    }, 10, 0);
}

$shop_page_url = get_permalink(wc_get_page_id('shop'));

if ('' === get_option('permalink_structure')) {
    $shop_page_url = get_post_type_archive_link('product');
}

$preset = !empty($tag) ? uniqid('zoo-ln-form-'.$tag) : uniqid('zoo-ln-form-');

$advanced_config = array(
    'filter_preset' => $preset,
    'jquery_selector_products' => isset($advanced_setting['jquery_selector_products']) ? $advanced_setting['jquery_selector_products'] : 'ul.products',
    'jquery_selector_products_count' => isset($advanced_setting['jquery_selector_products_count']) ? $advanced_setting['jquery_selector_products_count'] : '.woocommerce-result-count',
    'jquery_selector_paging' => isset($advanced_setting['jquery_selector_paging']) ? $advanced_setting['jquery_selector_paging'] : '.woocommerce-pagination',
    'shop_page_info' => array(
        'url' => $shop_page_url,
        'title' => Zoo\Frontend\Hook\zoo_ln_get_document_title()
    )
);
$advanced_config = json_encode($advanced_config);

$form_class = 'zoo-ln-filter-form ' . $preset;
if (isset($general_setting['instant_filtering']) && $general_setting['instant_filtering'] == 1) {
    $form_class .= ' instant_filtering ';
}

if (isset($advanced_setting['apply_ajax']) && $advanced_setting['apply_ajax'] == 1) {
    $form_class .= ' apply_ajax ';
}

$filter_view_style = isset($filter_style['filter_view_style']) ? $filter_style['filter_view_style'] : 'vertical';
$view_style_class = 'zoo_ln_' . $filter_view_style;

$always_visible = false;
if ($filter_view_style == 'horizontal') {
    if (isset($filter_style['horizontal_alway_visible']) && $filter_style['horizontal_alway_visible'] == 1) {
        $always_visible = true;
    }
}
if (count($data)) {
    $wrap_filter_class = 'zoo-layer-nav';
    if (!empty($filter_style['filter_max_height'])) {
        if ($filter_style['filter_max_height'] > 0) {
            $wrap_filter_class .= ' zoo-ln-set-max-height';
        }
    }
    ?>
    <div class="<?php echo esc_attr($wrap_filter_class);?>" id="<?php echo esc_attr('filter-preset-' . $item_id) ?>">
        <form id="zoo_ln_form_<?php echo esc_attr($item_id) ?>" class="<?php echo esc_attr($form_class); ?>" action="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"
              method="post" data-ln-config="<?php echo esc_js($advanced_config) ?>">
            <?php
            if ($filter_view_style == 'horizontal') {
                ?>
                <div class="zoo-ln-wrap-heading">
                    <?php
                    if (isset($data['primary'])) { ?>
                        <div class="zoo-ln-primary-col">
                            <?php
                            foreach ($data['primary'] as $item_data) :
                                $content_data = (array)reset($item_data['item_config_value']['filter_config_value']);
                                require ZOO_LN_TEMPLATES_PATH . 'view/'.$item_data['item_type'].'.php';
                            endforeach;
                            ?>
                        </div>
                        <?php
                    }
                    if (!$always_visible) { ?>
                        <a class="zoo-ln-btn zoo-toggle-filter-visible"
                           href="javascript:;"><i class="cs-font clever-icon-funnel-o"></i> <?php echo esc_html__('Filter', 'clever-layered-navigation') ?></a>
                    <?php } ?>
                </div>
            <?php }
            if ($filter_view_style == 'horizontal') {
            ?>
            <div class="zoo-ln-wrap-col zoo-ln-<?php echo esc_attr($filter_style["horizontal_number_col"]); ?>-cols"
                 <?php if (!$always_visible){ ?>style="display: none"<?php } ?>>
                <?php
                }
                foreach ($data as $col_id => $column_data) :
                    if ($col_id == 'primary') continue; ?>
                    <div class="zoo-wrap-layer-filter">
                        <?php
                        foreach ($column_data as $item_data) :
                            $content_data = (array)reset($item_data['item_config_value']['filter_config_value']);
                            require ZOO_LN_TEMPLATES_PATH . 'view/'.$item_data['item_type'].'.php';
                        endforeach;
                        ?>
                    </div>
                <?php
                endforeach;
                if ($filter_view_style == 'horizontal') {
                ?>
            </div>
        <?php
        }
        if (isset($advanced_setting['apply_ajax']) && $advanced_setting['apply_ajax'] == 1) {
            $orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
            if (empty($orderby)) $orderby = 'date';
            if (!empty($wp_query->query_vars['product_cat'])) {
                echo '<input type="hidden" name="categories" value="'.esc_attr($wp_query->query_vars['product_cat']).'">';
            }
            if (!empty($wp_query->query_vars['product_tag'])) {
                echo '<input type="hidden" name="tags" value="'.esc_attr($wp_query->query_vars['product_tag']).'">';
            }
        ?>
            <input type="hidden" name="orderby" value="<?php echo esc_attr($orderby); ?>">
            <input type="hidden" name="orderby" value="<?php echo esc_attr($orderby); ?>">
            <input type="hidden" name="order" value="<?php echo get_query_var('order'); ?>">
            <input type="hidden" name="posts_per_page" value="<?php echo get_query_var('posts_per_page'); ?>">
            <input type="hidden" name="paged" value="">
            <?php
        }
        if (!isset($general_setting['instant_filtering']) || $general_setting['instant_filtering'] != 1) : ?>
            <input type="submit" value="<?php echo esc_attr__('Apply', 'clever-layered-navigation') ?>">
        <?php endif; ?>
            <?php if (function_exists('dokan') && !empty($wp_query->query_vars['author'])) : ?>
                <input type="hidden" name="vendor_store_author_id" value="<?php echo intval($wp_query->query_vars['author']) ?>">
            <?php endif; ?>
            <input type="hidden" name="relation" value="<?php echo esc_attr($advanced_setting['filter_logic_operator']); ?>">
            <input type="hidden" name="pagination_link" value="<?php echo(esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))))); ?>">
            <input type="hidden" name="filter_list_id" value="<?php echo($item_id); ?>">
            <?php wp_nonce_field('apply_filter', 'zoo_ln_nonce_setting'); ?>
        </form>
    </div>
<?php } ?>
