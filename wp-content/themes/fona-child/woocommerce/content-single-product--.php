<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
$zoo_single_layout = zoo_woo_gallery_layout_single();
$zoo_class = $zoo_single_layout . ' zoo-single-product';
wp_enqueue_style('slick');
wp_enqueue_style('slick-theme');
if (zoo_woo_enable_zoom()) {
    wp_enqueue_style('zoomove');
    wp_enqueue_script('zoomove');
    $zoo_class .= ' zoo-product-zoom';
}
$zoo_single_sidebar = zoo_woo_single_sidebar();
$zoo_class .= ' ' . $zoo_single_sidebar;
if ($zoo_single_layout == 'vertical-gallery-center-thumb') {
    $zoo_single_layout = 'vertical-gallery';
    $zoo_class .= ' vertical-gallery';
}
if ($zoo_single_layout == 'carousel') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
}
if ($zoo_single_layout == 'sticky-right-content'||$zoo_single_layout == 'sticky-accordion') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    $zoo_single_layout = 'sticky';
    $zoo_class .= ' sticky';
}
if ($zoo_single_layout == 'images-center') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
}
?>
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class($zoo_class, $product); ?>>
        <div class="wrap-top-single-product">
            <div class="container">
                <?php get_template_part('woocommerce/single-product/layout/' . $zoo_single_layout, 'layout'); ?>
            </div>
		</div>
		

<?php //var_dump($product);?>
		<div id="gallery" class="product_gallery">
	<div class="container">
	<h2>Gallery</h2>
	<div class="row">
	<?php 
	$aaaa = $product->get_meta( 'same_product_title' );
	$att_ID = $product->get_image_id();
	
	//echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $att_ID ), $att_ID );
	?>
	<div class="col-lg-3 col-md-3 col-sm-3">
	<a href="<?php echo wp_get_attachment_url( $att_ID ); ?>" class="item-wrap" data-fancybox="gal">
		  <span class="icon-search2"></span>
		  <img class="img-fluid" src="<?php echo wp_get_attachment_url( $att_ID ); ?>">
		</a>
		</div>
	<?php
	//global $product;
	$attachment_ids = $product->get_gallery_image_ids();
if ( $attachment_ids && $product->get_image_id() ) {
	foreach ( $attachment_ids as $attachment_id ) {
		?>
		<div class="col-lg-3 col-md-3 col-sm-3">
		<a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" class="item-wrap" data-fancybox="gal">
		  <span class="icon-search2"></span>
		  <img class="img-fluid" src="<?php echo wp_get_attachment_url( $attachment_id ); ?>">
		</a>
		</div>
		<?php
	}
}
	?>
	</ul>
</div>
</div>

</div>


        <div class="wrap-main-single-product">
            <?php
            /**
             * woocommerce_after_single_product_summary hook.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            do_action('woocommerce_after_single_product_summary');
            ?>
        </div>
    </div>


	<?php //echo 'abc'. $aaaa;
//echo $product->get_meta( 'same_product_title' );
					$args = array(
						//'return' => 'ids',
						'same_product_title' => $product->get_meta( 'same_product_title' ),
						'limit' => 5,
					);
					$same_products = wc_get_products( $args );
					
					
if ( $same_products ) : ?>

	<section id="abc" class="related products">
<div class="container">
		<h2><?php esc_html_e( 'Accessories', 'woocommerce' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

<ul id='pddd' class='products owl-carousel owl-theme'>

			<?php foreach ( $same_products as $related_product ) : ?>

				<?php
				 	$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>
</ul>
		<?php woocommerce_product_loop_end(); ?>
</div>
	</section>

<?php endif;  wp_reset_postdata();?>
<?php
do_action('woocommerce_after_single_product');
?>
<!--script>
	jQuery(document).ready(function () {
    (function ($) {
	$('#pddd').owlCarousel({
    loop:false,
    margin:15,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:5,
            nav:false
        },
        10240:{
            items:5,
            nav:true,
            loop:false
        }
    }
})
})(jQuery);
});
	</script-->

	<link href="<?php echo get_stylesheet_directory_uri();?>/jquery.fancybox.min.css" rel="stylesheet" type="text/css">
	<script src="<?php echo get_stylesheet_directory_uri();?>/jquery.fancybox.min.js"></script>