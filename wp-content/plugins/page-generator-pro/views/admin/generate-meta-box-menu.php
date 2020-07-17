 <div class="wpzinc-option sidebar">
	<div class="left">
		<label for="menu"><?php _e( 'Menu', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<select name="<?php echo $this->base->plugin->name; ?>[menu]" id="menu" size="1" class="widefat">
			<option value="0"<?php selected( $this->settings['menu'], 0 ); ?>>
				<?php _e( '(none)', 'page-generator-pro' ); ?>
			</option>
			<?php
			if ( is_array( $menus ) && count( $menus ) > 0 ) {
				foreach ( $menus as $menu ) {
					?>
					<option value="<?php echo $menu->term_id; ?>"<?php selected( $this->settings['menu'], $menu->term_id ); ?>>
						<?php echo $menu->name; ?>
					</option>
					<?php
				}
			}
			?>
		</select>
	</div>
	<p class="description">
		<?php 
		echo sprintf(
			__( 'If defined, generated Pages will be added to this WordPress Menu.<br />
				To display a Menu in your Theme, see %s<br />
				In Test Mode, the generated Page will <strong>not</strong> be assigned to this Menu.', 'page-generator-pro' ),
			'<a href="nav-menus.php">' . __( 'Appearance > Menus', 'page-generator-pro' ) . '</a>'
		);
		?>
	</p>
</div>

 <div class="wpzinc-option sidebar">
	<div class="left">
		<label for="menu_title"><?php _e( 'Menu Title', 'page-generator-pro' ); ?></label>
	</div>
	<div class="right">
		<input type="text" name="<?php echo $this->base->plugin->name; ?>[menu_title]" id="menu_title" value="<?php echo $this->settings['menu_title']; ?>" class="widefat" />
	</div>
	<p class="description">
		<?php 
		_e( 'If defined, generated Pages will have the above title set in the Menu.<br />
			 If empty, the generated Page title will be used.<br />
			 Keywords and Spintax are supported.', 'page-generator-pro' );
		?>
	</p>
</div>

