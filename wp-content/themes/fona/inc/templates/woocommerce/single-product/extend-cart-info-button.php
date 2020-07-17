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

if(class_exists('CleverAddons')):
?>
<ul class="zoo-extend-cart-info">
<?php
if(get_theme_mod('zoo_single_product_cart_size_guide','0')!='0'){
	$size_page=get_post(get_theme_mod('zoo_single_product_cart_size_guide','0'));
	if($size_page!=NULL){
	?>
    <li class="zoo-extend-cart-info-item size-guide-block">
        <a href="#" title="<?php echo esc_attr($size_page->post_title)?>"  data-target="size-guide-content">
	        <?php echo esc_html($size_page->post_title)?>
        </a>
    </li>
    <?php
    }
}
if(get_theme_mod('zoo_single_product_cart_delivery','0')!='0'){
	$delivery_page=get_post(get_theme_mod('zoo_single_product_cart_delivery','0'));
	if($delivery_page!=NULL){
	?>
    <li class="zoo-extend-cart-info-item delivery-return-block">
        <a href="#" title="<?php echo esc_attr($delivery_page->post_title)?>" data-target="delivery-return-content">
	        <?php echo esc_html($delivery_page->post_title)?>
        </a>
    </li>
    <?php
    }
}
if(get_theme_mod('zoo_single_product_cart_ask_product','0')!='0'){
	$ask_page=get_post(get_theme_mod('zoo_single_product_cart_ask_product','0'));
	if($ask_page!=NULL){
	?>
    <li class="zoo-extend-cart-info-item ask-product-block">
        <a href="#" title="<?php echo esc_attr($ask_page->post_title)?>"  data-target="ask-product-content">
	        <?php echo esc_html($ask_page->post_title)?>
        </a>
    </li>
    <?php
    }
}
?>
</ul>
<?php endif;?>