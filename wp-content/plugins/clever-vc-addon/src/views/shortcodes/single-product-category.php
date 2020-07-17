<?php
/**
 * Template for shortcode Single Product Category
 */
$category = get_term_by('slug', $atts['product_cat'], 'product_cat');
if ($category):
    //cat IMG
    $img = wp_get_attachment_url($atts['cat_img'], 'full');
    $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'full', true);
    $thumb = wp_get_attachment_url($thumbnail_id);
    $img = $img ? $img : $thumb;
    //Cat prepare
    $cat_name = $category->name;
    $cat_link = get_category_link($category->term_id);

    //wrap class
    $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverSingleProductCategory', $atts);
    $css_class .= ' cvca-single-product-cat cvca-shortcode ' . $atts['el_class'];
    wp_enqueue_style('cvca-style');
    ?>
    <div class="<?php echo esc_attr($css_class); ?>">
        <div class="cvca-wrap-img-cat">
            <a href="<?php echo esc_url($cat_link) ?>" title="<?php echo esc_attr($cat_name) ?>">
                <?php
                if ($img) {
                    ?>
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($cat_name) ?>"/>
                    <?php
                }
                if ($atts['show_count'] != '' || $atts['readmore'] != ''){
                ?>
            </a>
            <div class="cvca-wrap-cat-des">
                <a href="<?php echo esc_url($cat_link) ?>" title="<?php echo esc_attr($cat_name) ?>">
                    <?php
                    if ($atts['show_count'] != '') {
                        $total_products = count(get_posts(array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1', 'product_cat' => $atts['product_cat'])));
                        ?>
                        <span class="cvca-cat-count">
                            <b><?php  echo esc_html($total_products);?></b>
                                <?php
                                printf(_n($atts['count_extend'], $atts['count_extends'], $total_products), $total_products);
                                ?>
                            </span>
                        <?php
                    }
                    if ($atts['readmore'] != '') {
                        ?>
                        <span class="cvca-cat-readmore">
                           <?php
                           echo esc_html($atts['readmore']);
                           ?>
                        </span>
                        <?php
                    }
                    ?>
                </a>
            </div>
            <?php
            }
            ?>
        </div>
        <h3 class="cvca-product-cat-title">
            <a href="<?php echo esc_url($cat_link) ?>"
               title="<?php echo esc_attr($cat_name) ?>"><?php echo esc_attr($cat_name) ?></a>
        </h3>
    </div>
<?php
endif;
?>