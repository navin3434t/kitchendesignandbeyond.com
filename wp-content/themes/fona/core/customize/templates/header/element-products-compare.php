<?php
/**
 * Template for Products Compare element.
 *
 * Template of `core/customize/builder/elements/wishlist.php`
 */
$el_html_classes = [$atts['count_position']];

if (!empty($atts['align'])) {
    $el_html_classes[] = 'element-align-'.esc_attr($atts['align']);
}

?>
<div id="element-header-products-compare-<?php echo esc_attr($atts['device'])?>" <?php $this->element_class($el_html_classes);?>>
    <a class="products-compare-link<?php if (!$atts['page_enable']) echo ' browse-products-compare'; ?>" href="<?php if (get_theme_mod('zoo_compare_page','')!='') echo esc_url(get_page_link(get_page_by_path(get_theme_mod('zoo_compare_page','')))) ?>">
        <span class="products-compare-icon">
            <?php if(isset($atts['icon']['icon'])){?>
            <i class="<?php echo esc_attr($atts['icon']['icon']); ?>"></i>
            <?php } if ($atts['show_count']) { ?>
                <span class="products-compare-counter zoo-hidden">0</span>
            <?php } ?>
        </span>
        <?php if (!empty($atts['title'])) : ?>
            <span class="products-compare-title"><?php echo esc_html($atts['title']) ?></span>
        <?php endif; ?>
    </a>
</div>
<?php
