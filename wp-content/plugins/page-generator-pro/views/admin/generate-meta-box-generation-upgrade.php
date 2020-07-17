<div class="wpzinc-option highlight">
    <div class="full">
        <h4><?php _e( 'Methods and Overwriting', 'page-generator' ); ?></h4>

        <p>
        	<?php 
        	_e( 'Generate Pages for all possible keyword combinations, and choose whether to overwrite or skip Page Generation
        	if content already exists.', 'page-generator' );
        	?>
        </p>

        <a href="<?php echo $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Upgrade', $this->base->plugin->name ); ?></a>
    </div>
</div>