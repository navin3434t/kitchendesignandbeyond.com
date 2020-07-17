<!-- Excerpt -->
<div class="wpzinc-option <?php echo $excerpt_post_type_class; ?>">
	<div class="left">
		<label for="post_excerpt"><?php _e( 'Excerpt', 'page-generator-pro' ); ?></strong>
	</div>
	<div class="right">
		<?php $this->base->get_class( 'keywords' )->output_dropdown( $this->keywords, 'excerpt' ); ?>
	</div>
	<div class="full">
		<textarea name="<?php echo $this->base->plugin->name; ?>[excerpt]" id="post_excerpt" class="widefat wpzinc-autocomplete"><?php echo $this->settings['excerpt']; ?></textarea>
	</div>
</div>