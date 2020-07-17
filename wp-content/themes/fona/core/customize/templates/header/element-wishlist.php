<?php
/**
 * Template for Wishlist element.
 *
 * Template of `core/customize/builder/elements/wishlist.php`
 */
$title = $atts['title'];
$show_count = $atts['show_count'];
$icon = $atts['icon'];
$el_html_classes = [$atts['count-position']];

if (!empty($atts['align'])) {
    $el_html_classes[] = 'element-align-' . esc_attr($atts['align']);
}

?>
    <div id="element-header-wishlist-<?php echo esc_attr($atts['device']) ?>" <?php $this->element_class($el_html_classes); ?>>
        <a class="wishlist-link<?php if (!$atts['page_enable']) echo ' browse-wishlist'; ?>"
           href="<?php if (get_theme_mod('zoo_wishlist_page', '') != '') echo esc_url(get_page_link(get_page_by_path(get_theme_mod('zoo_wishlist_page', '')))) ?>">
            <?php if ($icon['icon'] != '') { ?>
                <span class="wishlist-icon">
                    <i class="<?php echo esc_attr($icon['icon']); ?>"></i>
                            <?php if ($show_count) { ?>
                                <span class="wishlist-counter zoo-hidden">0</span>
                            <?php } ?>
                </span>
                <?php
            }
            if (!empty($title)): ?>
                <span class="wishlist-menu-title"><?php echo esc_html($title) ?></span>
            <?php endif; ?>
        </a>
    </div>
<?php
