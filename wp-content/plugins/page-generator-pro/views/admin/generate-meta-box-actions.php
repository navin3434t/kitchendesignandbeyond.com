<?php 
if ( ! $bottom ) {
	// Nonce field
	wp_nonce_field( 'save_generate', $this->base->plugin->name . '_nonce' );
}
?>

<!-- 
#submitpost is required, so WordPress can unload the beforeunload.edit-post JS event.
If we didn't do this, the user would always get a JS alert asking them if they want to navigate
away from the page as they may lose their changes
-->
<div class="submitbox" id="submitpost">
	<div id="publishing-action">
		<div class="wpzinc-option">
			<div class="full">
			<?php
			// Save
			if ( isset( $post ) && ( ! in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) || 0 == $post->ID ) ) {
				// Publish
				?>
				<input name="original_publish" type="hidden" id="original_publish<?php echo $bottom; ?>" value="<?php esc_attr_e( 'Publish' ) ?>" />
				<?php submit_button( __( 'Save' ), 'primary button-large', 'publish', false, array(
					'id' => 'publish' . $bottom,
				) ); ?>
				<?php
			} else {
				// Update
				?>
				<input name="original_publish" type="hidden" id="original_publish<?php echo $bottom; ?>" value="<?php esc_attr_e( 'Update' ) ?>" />
				<?php submit_button( __( 'Save' ), 'primary button-large', 'publish', false, array(
					'id' => 'publish' . $bottom,
				) ); ?>
				<?php
			}

			// Test
			?>

			<?php submit_button( __( 'Test' ), 'primary button-large', 'test', false, array(
				'id' => 'test' . $bottom,
			) ); ?>
			</div>
		</div>

		<div class="wpzinc-option">
			<?php submit_button( __( 'Generate via Browser' ), 'primary button-large margin-bottom', 'generate', false, array(
				'id' => 'generate' . $bottom,
			) ); ?>
			<br />
			<?php submit_button( __( 'Generate via Server' ), 'primary button-large', 'generate_server', false, array(
				'id' => 'generate_server' . $bottom,
			) ); ?>
		</div>
	</div>
</div>

<?php
// Delete Generated Content, if any exist
if ( $this->settings['generated_pages_count'] > 0 ) {
	?>
	<div class="wpzinc-option">
		<?php
		if ( $this->settings['group_type'] == 'content' ) {
			submit_button( __( 'Trash Generated Content', 'page-generator-pro' ), 'trash-generated-content wpzinc-button-red margin-bottom', 'trash_generated_content', false, array(
				'id' => 'trash_generated_content' . $bottom,
			) );
			?>
			<br />
			<?php	
		}
		
		submit_button( __( 'Delete Generated Content', 'page-generator-pro' ), 'delete-generated-content wpzinc-button-red', 'delete_generated_content', false, array(
			'id' => 'delete_generated_content' . $bottom,
		) );
		?>
	</div>
	<?php	
}