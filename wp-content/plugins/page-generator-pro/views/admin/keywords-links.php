<?php
/**
 * Outputs button links below the Header when in the Keywords section
 *
 * @since 	1.7.8
 */
?>
<a href="admin.php?page=<?php echo $page; ?>&amp;cmd=form" class="page-title-action"><?php _e( 'Add Keyword', 'page-generator-pro' ); ?></a>
<a href="admin.php?page=<?php echo $page; ?>&amp;cmd=form-import-csv" class="page-title-action"><?php _e( 'Import CSV', 'page-generator-pro' ); ?></a>
<a href="admin.php?page=<?php echo $page; ?>&amp;cmd=form-locations" class="page-title-action"><?php _e( 'Generate Locations', 'page-generator-pro' ); ?></a>
<a href="admin.php?page=<?php echo $page; ?>&amp;cmd=form-phone" class="page-title-action"><?php _e( 'Generate Phone Area Codes', 'page-generator-pro' ); ?></a>

<hr class="wp-header-end" />