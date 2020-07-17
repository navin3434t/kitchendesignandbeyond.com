<?php
/**
 * Template for EDD cart element.
 *
 * Template of `core/customize/builder/elements/edd-cart.php`
 */
$display_style = $atts['display_style']==''?'style-1':$atts['display_style'];

$count_style   = $atts['display_count_position']==''?'top-right':$atts['display_count_position'];

if ($atts['icon_style'] == 'style-2' && $atts['enable_cart_styling'] != '1') {
    $count_style = 'inside';
}

$class   = ['edd-cart'];
$class[] = 'element-cart-icon';
$class[] = $atts['icon_style'];
$class[] = $display_style;
$class[] = $count_style;

if (!empty($atts['align'])) {
    $class[] = 'element-align-'.esc_attr($atts['align']);
}

$cart_count = edd_get_cart_quantity();
$cart_total = edd_currency_filter(edd_format_amount(edd_get_cart_total()));

if (empty($cart_count)) {
    $cart_count = '0';
    $class[] = 'cart-empty';
}
?>
    <div <?php $this->element_class($class);?>>
        <a class="element-cart-link" href="<?php echo esc_url(edd_get_checkout_uri()); ?>" title="<?php echo esc_attr__('View your shopping cart', 'fona') ?>">
            <?php if (!empty($atts['cart_icon'])) { ?>
            <span class="icon-element-cart">
                <i class="<?php echo esc_attr($atts['cart_icon']['icon']) ?>"></i>
                <span class="edd-cart-quantity element-cart-count"><?php echo strval($cart_count); ?></span>
            </span>
            <?php } ?>
            <?php if ($atts['show_title'] || $atts['show_total']) { ?>
                <div class="wrap-right-element-cart">
                    <?php if ($atts['show_title']) { ?>
                        <span class="title-element-cart">
                            <?php esc_html_e('Cart', 'fona'); ?>
                        </span>
                    <?php }
                    if ($atts['show_total']) { ?>
                        <span class="edd-cart-total"><?php echo esc_html($cart_total); ?></span>
                    <?php } ?>
                </div>
            <?php } ?>
        </a>
    </div>
<?php
