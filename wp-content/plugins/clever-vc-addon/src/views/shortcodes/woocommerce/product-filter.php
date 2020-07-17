<?php
/**
 * Product filter block
 */
if ($atts['show_filter']) {
    $col_class = 'cvca-wrap-filter-item ';
    switch ($atts['filter_col']) {
        case '2':
            $col_class .= "col-xs-12 col-sm-6";
            break;
        case '3':
            $col_class .= "col-xs-12 col-sm-4";
            break;
        case '4':
            $col_class .= "col-xs-12 col-sm-3";
            break;
        case '5':
            $col_class .= "col-xs-12 col-sm-1-5";
            break;
        case '6':
            $col_class .= "col-xs-12 col-sm-2";
            break;
        default:
            $col_class .= "col-xs-12 col-sm-12";
            break;
    }
    wp_enqueue_script('cvca-ajax-product');
    //reset filter
    //list category
    ?>
    <div class="wrap-head-product-filter">
        <?php if ($atts['filter_categories'] != '') {
            ?>
            <div class="cvca-wrap-list-cats">
                <span class="cat-selected"><?php esc_html_e('All', 'cvca-core-language');?><i
                            class="cs-font clever-icon-down"></i></span>
                <ul class="cvca-ajax-load cvca-list-product-category">
                    <?php
                    //end of list category
                    echo '<li class="cvca-ajax-load"><a class="active" data-type="cvca-reset-filter" href="' . cvca_current_url() . '">' . esc_html__('All', 'cvca-core-language') . '</a></li>';
                    $product_categories = explode(',', $atts['filter_categories']);
                    foreach ($product_categories as $product_cat_slug) {
                        $product_cat = get_term_by('slug', $product_cat_slug, 'product_cat');
                        $selected = '';
                        if (isset($atts['wc_attr']['product_cat']) && $atts['wc_attr']['product_cat'] == $product_cat->slug) {
                            $selected = 'cvca-selected';
                        }
                        echo '<li><a class="' . esc_attr($selected) . '" 
                            data-type="product_cat" data-value="' . esc_attr($product_cat->slug) . '" 
                            href="' . esc_url(get_term_link($product_cat->slug, 'product_cat')) . '" 
                            title="' . esc_attr($product_cat->name) . '">' . esc_html($product_cat->name) . '</a></li>';

                    }
                    ?></ul>
            </div>
            <?php
        }
        if ($atts['filter_attributes'] != '' || $atts['filter_tags'] != '' || $atts['show_featured_filter'] || ($atts['show_price_filter'] && intval($atts['price_filter_level']) > 0)) {
            ?>
            <span class="cvca-toogle-filter"><?php esc_html_e('+ Filter', 'cvca-core-language'); ?></span>
            <?php
        }
        ?>
    </div>
    <?php if ($atts['filter_attributes'] != '' || $atts['filter_tags'] != '' || $atts['show_featured_filter'] || ($atts['show_price_filter'] && intval($atts['price_filter_level']) > 0)): ?>
        <div class="cvca-wrap-adv-filter <?php echo esc_attr('filter-' . $atts['filter_col'] . '-cols'); ?>"
             style="display: none">
            <?php
            if ($atts['filter_categories'] == '') {
                //end of list category
                echo '<div class="cvca-ajax-load"><a data-type="cvca-reset-filter" href="' . cvca_current_url() . '">' . esc_html__('Reset Filter', 'cvca-core-language') . '</a></div>';
                //end reset filter
            }
            ?>
            <div class="row">
                <?php
                //list featured filter
                if ($atts['show_featured_filter']) {
                    $filter_arrs = array(
                        esc_html__('All', 'cvca-core-language') => 'all',
                        esc_html__('Featured product', 'cvca-core-language') => 'featured',
                        esc_html__('Onsale product', 'cvca-core-language') => 'onsale',
                        esc_html__('Best Selling', 'cvca-core-language') => 'best-selling',
                        esc_html__('Latest product', 'cvca-core-language') => 'latest',
                        esc_html__('Top rate product', 'cvca-core-language') => 'toprate ',
                        esc_html__('Sort by price: low to high', 'cvca-core-language') => 'price',
                        esc_html__('Sort by price: high to low', 'cvca-core-language') => 'price-desc',
                    );
                    ?>
                    <div class="<?php echo esc_attr($col_class) ?>">
                        <h3 class="cvca-title-filter-item"><?php esc_html_e('Sort by', 'cvca') ?> <i
                                    class="cs-font clever-icon-down"></i></h3>
                        <ul class="cvca-ajax-load cvca-list-filter">
                            <?php
                            foreach ($filter_arrs as $key => $value) {
                                $selected = '';
                                if (isset($atts['show']) && $atts['show'] == $value) {
                                    $selected = 'cvca-selected';
                                }
                                echo '<li><a  class="' . esc_attr($selected) . '" 
                            data-type="show" 
                            data-value="' . esc_attr($value) . '" 
                            href="" title="' . esc_attr($key) . '">' . esc_html($key) . '</a></li>';
                            }
                            ?></ul>
                    </div>
                    <?php
                }
                //end list tags
                //Filter price
                if ($atts['show_price_filter'] && intval($atts['price_filter_level']) > 0) {
                    ?>
                <div class="<?php echo esc_attr($col_class) ?>">
                    <h3 class="cvca-title-filter-item"><?php esc_html_e('Price', 'cvca') ?> <i
                                class="cs-font clever-icon-down"></i></h3>
                    <ul class="cvca-ajax-load cvca-price-filter">
                        <?php
                        $price_format = get_woocommerce_price_format();
                        $price_currency = get_woocommerce_currency();
                        for ($i = 1; $i <= intval($atts['price_filter_level']); $i++) {
                            $min = ($i - 1) * intval($atts['price_filter_range']);
                            $max = $i * intval($atts['price_filter_range']);

                            $min_price = sprintf($price_format, wc_format_decimal($min, 2), $price_currency);
                            $max_price = sprintf($price_format, wc_format_decimal($max, 2), $price_currency);

                            $price_text = $min_price . ' - ' . $max_price;
                            if ($i == intval($atts['price_filter_level'])) {
                                $price_text = $min_price . '+';
                            }

                            $selected = '';
                            $removed = '';
                            if (isset($_POST['price_filter']) && $_POST['price_filter'] == $i) {
                                $selected = 'cvca-selected';
                                $removed = '<span data-type="cvca-remove-price" class="cvca-remove-attribute"><i class="fa fa-times"></i></span>';
                            }
                            echo '<li>' . $removed . '<a  class="' . esc_attr($selected) . '" 
                            data-type="price_filter" 
                            data-value="' . esc_attr($i) . '" 
                            href="" title="' . esc_attr($key) . '">' . esc_html($price_text) . '</a></li>';

                        }
                        ?></ul>
                    </div><?php
                }
                //End filter price
                //list tags
                if ($atts['filter_tags'] != '') {
                    $product_tags = explode(',', $atts['filter_tags']);
                    ?>
                <div class="<?php echo esc_attr($col_class) ?>">
                    <h3 class="cvca-title-filter-item"><?php esc_html_e('Tags', 'cvca') ?><i
                                class="cs-font clever-icon-down"></i></h3>
                    <ul class="cvca-ajax-load cvca-list-product-tag">
                        <?php
                        foreach ($product_tags as $product_tag_slug) {
                            $selected = '';
                            $product_tag = get_term_by('slug', $product_tag_slug, 'product_tag');
                            if (isset($atts['wc_attr']['product_tag']) && $atts['wc_attr']['product_tag'] == $product_tag->slug) {
                                $selected = 'cvca-selected';
                            }
                            echo '<li><a class="' . esc_attr($selected) . '"  
                            data-type="product_tag" 
                            data-value="' . esc_attr($product_tag->slug) . '" 
                            title="' . esc_attr($product_tag->name) . '">' . esc_html($product_tag->name) . '</a></li>';

                        }
                        ?></ul>
                    </div><?php
                }
                //end if list tag
                //list product_attributes
                if ($atts['filter_attributes'] != '') {
                    $product_attribute_taxonomies = explode(',', $atts['filter_attributes']);
                    if (count($product_attribute_taxonomies) > 0) {

                        foreach ($product_attribute_taxonomies as $product_attribute_taxonomie_slug) {
                            global $wpdb;
                            $attribute_taxonomies = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies where attribute_name='" . $product_attribute_taxonomie_slug . "'");
                            if (isset($attribute_taxonomies[0])) {
                                $product_attribute_taxonomie = $attribute_taxonomies[0];
                                //$product_terms = get_terms( 'pa_'.$product_attribute_taxonomie->attribute_name, 'hide_empty=0' );
                                $product_terms = get_terms('pa_' . $product_attribute_taxonomie->attribute_name);
                                if (count($product_terms) > 0) {
                                    ?>
                                    <div class="<?php echo esc_attr($col_class) ?>">
                                        <h3 class="cvca-title-filter-item"><?php echo esc_html($product_attribute_taxonomie->attribute_label) ?>
                                            <i class="cs-font clever-icon-down"></i></h3>
                                        <ul class="cvca-ajax-load cvca-product-attribute-filter">
                                            <?php
                                            foreach ($product_terms as $product_term) {

                                                $selected = '';
                                                $removed = '';
                                                if (isset($atts['wc_attr']['tax_query']) && count($atts['wc_attr']['tax_query']) > 0) {
                                                    foreach ($atts['wc_attr']['tax_query'] as $tax_query) {
                                                        if ($tax_query['taxonomy'] == $product_term->taxonomy && $tax_query['terms'] == $product_term->slug) {
                                                            $selected = 'cvca-selected';
                                                            $removed = '<span data-type="cvca-remove-attr" class="cvca-remove-attribute"><i class="fa fa-times"></i></span>';
                                                        }
                                                    }

                                                }
                                                echo '<li>' . $removed . '<a class="cvca-product-attribute ' . esc_attr($selected) . '" 
                                            data-type="product_attribute" 
                                            data-attribute_value="' . esc_attr($product_term->slug) . '" 
                                            data-value="' . esc_attr($product_term->taxonomy) . '" 
                                            title="' . esc_attr($product_term->name) . '">' . esc_html($product_term->name) . '</a></li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    }
                }
                //end list product_attributes
                ?>
            </div>
        </div>
        <?php
    endif;
}