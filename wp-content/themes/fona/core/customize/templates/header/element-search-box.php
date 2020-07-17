<?php
/**
 * Template for search box element.
 *
 * Template of `core/customize/builder/elements/search-box.php`
 */
$ajax_enable = $atts['live-search'];
$button_label = $atts['button-label'];
$ajax_class_attr = $ajax_enable ? ' zoo-live-search' : '';
$element_class = 'zoo-search-box-container header-search-box header-search';
if($atts['advanced-styling']!=1){
    $element_class.=' '.$atts['preset'];
}

if (!empty($atts['align'])) {
    $element_class .= ' element-align-'.esc_attr($atts['align']);
}
if($button_label!=''){
    $element_class .= ' has-text-label';
}
if ($atts['icon-only'] == 1) {
    $element_class.=' only-icon';
}
?>
<div <?php $this->element_class($element_class)?>>
    <?php if ($atts['icon-only'] == 1) { ?>
        <a href="#" class="btn-lb-search"><i class="zoo-icon-search"></i></a>
    <?php  } else { ?>
        <form role="search" class="zoo-search-form header-search-form<?php echo esc_attr($ajax_class_attr) ?>"
              action="<?php echo esc_url(home_url('/')); ?>">
            <div class="wrap-input">
                <input type="search" class="input-text search-field"
                       placeholder="<?php echo esc_attr($atts['placeholder']); ?>"
                       value="<?php echo esc_attr(get_search_query()) ?>" name="s" autocomplete="off"
                       title="<?php echo esc_attr($atts['placeholder']); ?>"/>
            </div>
            <?php
            if (1 === $atts['show-cat'] && !zoo_customize_get_setting('header_search_box_by_product_sku_only')) :
                $cats = get_terms([
                    'hide_empty' => true,
                    'taxonomy' => 'product_cat'
                ]);
                if ($cats && !is_wp_error($cats)) :
                    ?>
                    <div class="wrap-list-cat-search">
                        <select class="zoo-product-cat-options" name="zoo-search-box-product-cat">
                            <option value="all"><?php esc_html_e('All Categories', 'fona') ?></option>
                            <?php
                            foreach ($cats as $cat) {
                                ?>
                                <option value="<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></option>
                                <?php
                            } ?>
                        </select>
                        <i class="zoo-icon-down"></i>
                        <span class="label-cat-search"><?php esc_html_e('All Categories', 'fona') ?></span>
                    </div>
                <?php
                endif;
            endif;
            ?>
            <button type="submit" class="button search-submit">
                <i class="zoo-icon-search"></i>
                <?php echo esc_html($button_label);?>
            </button>
        </form>
    <?php } ?>
</div>
