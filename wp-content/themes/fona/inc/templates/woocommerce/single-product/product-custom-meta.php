<?php
/**
 * Product custom meta
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 *
 */

global $product;
?>
<div class="product_meta wrap-custom-meta">
    <div class="wrap-left-custom-meta">
	<?php
	/**
	 * This is custom hook, allow add another template to this hook
	 * */
	do_action( 'zoo_custom_before_product_meta' );
	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
		?>
		<span class="sku_wrapper">
                <span class="heading-meta"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?></span>
                <span class="sku"><?php if ( $sku = $product->get_sku() ) {
	                    echo esc_html( $sku );
                    } else {
	                    esc_html_e( 'N/A', 'woocommerce' );
                    } ?>
                </span>
            </span>
		<?php

	endif;
	/**
	 * This is custom hook, allow add another template to this hook
	 * */
	do_action( 'zoo_custom_after_product_meta' );
	?>
    </div>
    <?php
    do_action('zoo_product_enable_sold_per_day');
    ?>
</div>