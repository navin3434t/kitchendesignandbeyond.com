<?php
/**
 * Template for Account element.
 *
 * Template of `core/customize/builder/elements/account.php`
 */
$show_title    = $atts['show-title'];
$show_total    = $atts['show-total'];
$display_style = $atts['display-style'];
$cart_icon     = $atts['cart-icon'];
$count_style   = $atts['display-count-position'];

if ( $atts['icon-style'] == 'style-2' && $atts['enable-cart-styling'] != '1' ) {
	$count_style = 'inside';
}

$class   = [];
$class[] = $atts['icon-style'];
$class[] = $display_style;
$class[] = $count_style;

if ( ! empty( $atts['align'] ) ) {
	$class[] = 'element-align-' . esc_attr( $atts['align'] );
}

$cart_url   = wc_get_cart_url();
$cart_count = WC()->cart->get_cart_contents_count();
$cart_total = WC()->cart->get_cart_total();

if ( empty( $cart_count ) ) {
	$cart_count = '0';
	$class[]    = 'cart-empty';
}

?>
    <div id="element-cart-icon-<?php echo esc_attr( $atts['device'] ) ?>" <?php $this->element_class( $class ); ?>>
        <a class="element-cart-link" href="<?php echo esc_url( $cart_url ); ?>"
           title="<?php echo esc_attr__( 'View your shopping cart', 'fona' ) ?>">
            <span class="icon-element-cart">
                <i class="<?php echo esc_attr( $cart_icon['icon'] ) ?>"></i>
	            <?php
	            if ( $atts['icon-style'] != 'style-7' ) {
		            ?>
                    <span class="element-cart-count">
                    <?php echo wp_kses_post( $cart_count ); ?>
                </span>
	            <?php } ?>
            </span>
			<?php if ( $show_title || $show_total ) { ?>
                <div class="wrap-right-element-cart">
					<?php if ( $show_title ) { ?>
                        <span class="title-element-cart">
                        <?php esc_html_e( 'Cart', 'fona' ); ?>
                        </span>
					<?php }
					if ( $show_total && $atts['icon-style'] != 'style-7' ) { ?>
                        <span class="total-element-cart"><?php echo wp_kses_post($cart_total); ?></span>
					<?php }
					if ( $atts['icon-style'] == 'style-7' ) {
						?>(
                        <span class="element-cart-count">
							<?php echo wp_kses_post( $cart_count ); ?>
                        </span>
                        )
					<?php } ?>
                </div>
			<?php } ?>
        </a>
		<?php if ( $display_style === 'drop-down' ) { ?>
            <div class="element-cart-content widget_shopping_cart">
                <div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
                </div>
            </div>
		<?php } ?>
    </div>
<?php // EOF
