<?php
/**
 * Template display cover image of Archive WooCommerce Page template.
 * @since: zoo-theme 3.0.0
 * @Ver: 3.0.0
 */
if ( ! check_vendor() ) {
	$enable_shop_heading = get_theme_mod( 'zoo_enable_shop_heading', 1 );
	if ( is_shop() ) {
		if ( $enable_shop_heading ) {
			$thumb_img=get_theme_mod('zoo_shop_banner','');
			if(isset($thumb_img['url'])){
				$thumb_img=$thumb_img['url'];
            }
			if ( is_product_category() ) {
				global $wp_query;
				$cat          = $wp_query->get_queried_object();
				$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
				$thumb        = wp_get_attachment_url( $thumbnail_id );
				$thumb_img       = $thumb ? $thumb : $thumb_img;
			}
			if ( $thumb_img != '' ) {
				?>
				<div class="cover-without-slider" style="padding-top:100px;padding-bottom:100px;background-color:transparent;background-image:url(<?php echo esc_url($thumb_img)?>);">
					<div class="container">
		                <h2 class="shop-title"><?php echo woocommerce_page_title(); ?></h2>
		                <?php woocommerce_taxonomy_archive_description(); ?>
	                </div>
				</div>
                <?php
			} else {
	            ?>
				<div class="container">
		            <h2 class="shop-title"><?php echo woocommerce_page_title(); ?></h2>
		        </div>
	            <?php
	        }
		}
	}
}