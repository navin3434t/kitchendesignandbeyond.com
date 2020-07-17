 <div class="wpzinc-option">
	<div class="left">
		<label for="comments"><?php _e( 'Allow comments?', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="checkbox" id="comments" name="<?php echo $this->base->plugin->name; ?>[comments]" value="1"<?php checked( $this->settings['comments'], 1 ); ?> />
	</div>
</div>

<div class="wpzinc-option">
	<div class="left">
		<label for="trackbacks"><?php _e( 'Allow track / pingbacks?', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="checkbox" id="trackbacks" name="<?php echo $this->base->plugin->name; ?>[trackbacks]" value="1"<?php checked( $this->settings['trackbacks'], 1 ); ?> />
	</div>
</div>