<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $this->base->plugin->displayName; ?>

        <span>
        	<?php _e( 'Import Multiple Keywords', 'page-generator-pro' ); ?>
        </span>
    </h1>

    <?php
    // Button Links
    require_once( 'keywords-links.php' );

    // Output Success and/or Error Notices, if any exist
    $this->base->get_class( 'notices' )->output_notices();
    ?>
    
    <div class="wrap-inner">
	    <div id="poststuff">
	    	<div id="post-body" class="metabox-holder columns-1">
	    		<!-- Content -->
	    		<div id="post-body-content">
	    			<!-- Form Start -->
	    			<form name="post" method="post" action="admin.php?page=<?php echo $page; ?>&amp;cmd=form-import-csv" enctype="multipart/form-data">		
		    			<div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
			                <div id="keyword-panel" class="postbox">
			                    <h3 class="hndle"><?php _e( 'Import CSV', 'page-generator-pro' ); ?></h3>
		
			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<strong><?php _e( 'CSV File', 'page-generator-pro' ); ?></strong>
			                    	</div>
			                    	<div class="right">
			                    		<input type="file" name="file" />
			                    	
				                    	<p class="description">
				                    		<?php _e( 'If you have a CSV file comprising of multiple keywords and terms, upload it here.', 'page-generator-pro' ); ?>
				                    		<br />
				                    		<?php echo sprintf( __( '<a href="%s">Click here</a> for an example of a supported CSV file structure.', 'page-generator-pro' ), $this->base->plugin->url . 'assets/example.csv' ); ?>
				                    	</p>
			                    	</div>
			                    </div>

			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<strong><?php _e( 'Keywords', 'page-generator-pro' ); ?></strong>
			                    	</div>
			                    	<div class="right">
			                    		<select name="keywords_location" size="1" class="widefat">
			                    			<option value="columns"><?php _e( 'Each keyword is in a column on the first row of the CSV file.', 'page-generator-pro' ); ?></option>
			                    			<option value="rows"><?php _e( 'Each keyword is in a row on the first column of the CSV file.', 'page-generator-pro' ); ?></option>
			                    		</select>
			                    	
				                    	<p class="description">
				                    		<?php _e( 'Choose an option above, based on where your keywords are listed in the CSV file.', 'page-generator-pro' ); ?>
				                    	</p>
			                    	</div>
			                    </div>

			                    <div class="wpzinc-option">
		                    		<?php wp_nonce_field( 'import_csv', $this->base->plugin->name . '_nonce' ); ?>
		                			<input type="submit" name="submit" value="<?php _e( 'Import', 'page-generator-pro' ); ?>" class="button button-primary" />
			                    </div>
			                </div>
						</div>
						<!-- /normal-sortables -->
				    </form>
				    <!-- /form end -->
	    		</div>
	    		<!-- /post-body-content -->
	    	</div>
		</div>  
	</div>     
</div>