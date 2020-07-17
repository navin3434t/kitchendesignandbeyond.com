<!-- Parent -->
<div class="wpzinc-option sidebar <?php echo $hierarchical_post_types_class; ?>">
	<div class="full">
    	<label for="parent"><?php _e( 'Parent', 'page-generator-pro' ); ?></label>
    </div>
    <div class="full">
		<?php
	    // For each hierarchical post type, output a Post ID field
		if ( is_array( $hierarchical_post_types ) && count( $hierarchical_post_types ) > 0 ) {
			foreach ( $hierarchical_post_types as $type => $post_type ) {
				?>
				<input type="text" id="parent" name="<?php echo $this->base->plugin->name . '[pageParent][' . $type . ']'; ?>" class="<?php echo $type; ?> widefat wpzinc-autocomplete" value="<?php echo ( isset( $this->settings['pageParent'][ $type ] ) ? $this->settings['pageParent'][ $type ] : '' ); ?>" />
				<?php
			}
		}
	    ?>
    </div>

    <p class="description">
    	<?php _e( 'To make generated Page(s) / Post(s) the child of an existing Page / Post, enter the parent Page / Post ID or Name (Slug) here.', 'page-generator-pro' ); ?><br />
    	<a href="<?php echo $this->base->plugin->documentation_url; ?>/generate-content/#fields--attributes" rel="noopener" target="_blank">
    		<?php _e( 'How to find the Parent Page ID', 'page-generator-pro' ); ?>
    	</a>
    </p>
</div>

<?php
// Output Template Options for Post Types
foreach ( $post_types_templates as $post_type => $templates ) {
	$template = ( isset( $this->settings['pageTemplate'][ $post_type ] ) ? $this->settings['pageTemplate'][ $post_type ] : '' );
	?>
	<div class="wpzinc-option sidebar <?php echo $post_type; ?>">
		<div class="full">
	    	<label for="<?php echo $post_type; ?>_template"><?php _e( 'Template', 'page-generator-pro' ); ?></label>
	    </div>
		<div class="full">
	    	<select name="<?php echo $this->base->plugin->name; ?>[pageTemplate][<?php echo $post_type; ?>]" id="<?php echo $post_type; ?>_template" size="1" class="widefat">
	    		<option value="default"<?php selected( $template, 'default' ); ?>>
	    			<?php _e( 'Default Template', 'page-generator-pro' ); ?>
	    		</option>
	    		<?php page_template_dropdown( $template, $post_type ); ?>
			</select>
		</div>
	</div>
	<?php
}