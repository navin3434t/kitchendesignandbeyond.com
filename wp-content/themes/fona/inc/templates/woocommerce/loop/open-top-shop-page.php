<?php
/**
 * Open Top Shop Page template
 * Display count down end sale
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 */

$widget = 'shop';
if ( zoo_product_sidebar() == 'top' ) {
	$widget = 'top-shop';
}
$css_class = 'wrap-top-shop-loop';
?>
<div id="top-shop-loop" class="<?php echo esc_attr( $css_class ) ?>">
    <div class="left-top-shop-loop">
		<?php
		if ( is_active_sidebar( $widget ) ) {
			?>
            <a href="#" class="zoo-sidebar-control" title="<?php esc_attr__( 'Show/Hide Sidebar', 'fona' ) ?>">
                <span class="togglelines"></span>
                <span class="text-before"><?php echo esc_html__( 'Filter', 'fona' ) ?></span>
                <span class="text-after"><?php echo esc_html__( 'Close', 'fona' ) ?></span>
            </a>
			<?php
		}
		$grid_active=' active';
		$list_active=' ';
		if(isset($_COOKIE['zoo_product_layout'])){
			$grid_active='';
			$list_active=' active';
        }
		?>
        <div class="wrap-toggle-products-layout">
            <span class="label-toggle-products-layout"><?php esc_html_e('View as','fona');?></span>
            <a class="toggle-products-grid-layout toggle-products-layout-button<?php echo esc_attr($grid_active)?>" data-layout="grid" href="#" title="<?php esc_attr_e('Grid layout','fona')?>"><i class="cs-font clever-icon-grid"></i></a>
            <a class="toggle-products-list-layout toggle-products-layout-button<?php echo esc_attr($list_active)?>" data-layout="list" href="#" title="<?php esc_attr_e('List layout','fona')?>"><i class="togglelines"></i></a>
        </div>
    </div>
    <div class="right-top-shop-loop">