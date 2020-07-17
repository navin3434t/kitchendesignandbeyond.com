<?php
/**
 * Product extend cart info template
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 *
 */
?>
<div class="wrap-content-popup-page">
	<?php if ( get_theme_mod( 'zoo_single_product_cart_size_guide', '0' ) != '0' ) {
		$size_page = get_post( get_theme_mod( 'zoo_single_product_cart_size_guide', '0' ) );
		if ( $size_page != null ) {
			?>
            <div class="content-popup-page size-guide-content">
				<?php echo apply_filters( 'the_content', $size_page->post_content ); ?>
            </div>
			<?php
		}
	}
	if ( get_theme_mod( 'zoo_single_product_cart_delivery', '0' ) != '0' ) {
		$delivery_page = get_post( get_theme_mod( 'zoo_single_product_cart_delivery', '0' ) );
		if ( $delivery_page != null ) {
			?>
            <div class="content-popup-page delivery-return-content">
				<?php echo apply_filters( 'the_content', $delivery_page->post_content ); ?>
            </div>
			<?php
		}
	}
	if ( get_theme_mod( 'zoo_single_product_cart_ask_product', '0' ) != '0' ) {
		$ask_page = get_post( get_theme_mod( 'zoo_single_product_cart_ask_product', '0' ) );
		if ( $ask_page != null ) {
			?>
            <div class="content-popup-page ask-product-content">
				<?php echo apply_filters( 'the_content', $ask_page->post_content ); ?>
            </div>
			<?php
		}
	}
	?>
    <span class="close-popup-page"><i class="cs-font clever-icon-close"></i></span>
</div>
<div class="close-zoo-extend-cart-info">
</div>
