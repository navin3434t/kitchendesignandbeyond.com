<?php
/**
 * Clever Product Shortcode ids
 */
$varid = mt_rand();
wp_enqueue_style('cvca-style');
if ($atts['products_type'] != 'carousel') {
    wp_enqueue_script('lazyload');
}
wp_enqueue_script('cvca-woo');
wp_enqueue_script('cvca-script');
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverProductIDs', $atts);
$css_class .= $atts['el_class'];

$args = array(
    'post_type' => 'product'
);
if ($atts['products_ids'] != '') {
    $args['post__in'] = explode(",", $atts['products_ids']);
}

$product_query=new WP_Query($args);

?>
<div class="woocommerce cvca-product-ids cvca-products-wrap cvca-product-ids-wrap-<?php echo esc_attr($varid); ?> <?php echo esc_attr($css_class) ?>">
    <div class="cvca-wrapper-products-shortcode">
        <?php
        $cvca_wrap_class = "cvca-wrap-products-sc";
        $class = '';
        if ($atts['products_type'] == 'list') {
            $class .= 'list';
        } else {
            $class .= 'grid';
        }
        if ($atts['products_type'] == 'carousel') {
            $class .= ' products-carousel';
            $cvca_wrap_class .= ' cvca-carousel';
            $cvca_pag = $atts['show_pag'] != '' ? 'true' : 'false';
            $cvca_nav = $atts['show_nav'] != '' ? 'true' : 'false';
            $cvca_json_config = '{"item":"' . $atts['column'] . '","wrap":"ul.products","pagination":"' . $cvca_pag . '","navigation":"' . $cvca_nav . '"}';
            wp_enqueue_style('slick');
            wp_enqueue_style('slick-theme');
            wp_enqueue_script('slick');
        } else {
            wp_enqueue_script('isotope');
        }
        if ($atts['show_rating'] != 1) {
            $cvca_wrap_class .= ' hide-rating';
        }
        ?>
        <div class="<?php echo esc_attr($cvca_wrap_class) ?>"
             <?php if ($atts['products_type'] == 'carousel'){ ?>data-config='<?php echo esc_attr($cvca_json_config) ?>'<?php } ?>>
            <ul class="products <?php echo esc_attr($class) ?>" <?php if ($atts['products_type'] != 'carousel') { ?> data-width="<?php echo esc_attr($atts['col_width']) ?>"<?php } ?>>
                <?php while ($product_query->have_posts()) {
                    $product_query->the_post();
                    global $product;
                    echo cvca_get_shortcode_view('woocommerce/content-product', $atts);
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    ?>
</div>