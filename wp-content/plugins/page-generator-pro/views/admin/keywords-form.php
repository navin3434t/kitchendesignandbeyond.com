<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $this->base->plugin->displayName; ?>

        <span>
        	<?php
        	if ( isset( $keyword ) && isset( $keyword['keywordID'] ) ) {
        		_e( 'Edit Keyword', 'page-generator-pro' );
        	} else {
        		_e( 'Add New Keyword', 'page-generator-pro' );
        	}
        	?>
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
	    			<form class="<?php echo $this->base->plugin->name; ?>" name="post" method="post" action="admin.php?page=<?php echo $page; ?>&amp;cmd=form<?php echo ( isset( $_GET['id'] ) ? '&id=' . absint( $_GET['id'] ) : '' ); ?>" enctype="multipart/form-data">		
		    	    	<div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
			                <div id="keyword-panel" class="postbox">
			                	<?php
			                	// Output if editing an existing Keyword
			                	if ( isset( $keyword ) && isset( $keyword['keywordID'] ) ) {
			                		?>
			                		<input type="hidden" name="keywordID" value="<?php echo $keyword['keywordID']; ?>" />
			                		<?php
			                	}
			                    ?>
			                    
			                    <h3 class="hndle"><?php _e( 'Keyword', 'page-generator-pro' ); ?></h3>
			                    
			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<label for="keyword"><?php _e( 'Keyword', 'page-generator-pro' ); ?></label>
			                    	</div>
			                    	<div class="right">
			                    		<input type="text" name="keyword" id="keyword" value="<?php echo ( isset( $keyword['keyword'] ) ? $keyword['keyword'] : '' ); ?>" class="widefat" />
			                    	
				                    	<p class="description">
				                    		<?php _e( 'A unique template tag name, which can then be used when generating content.', 'page-generator-pro' ); ?>
				                    	</p>
			                    	</div>
			                    </div>

			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<label for="data"><?php _e( 'Terms', 'page-generator-pro' ); ?></label>
			                    	</div>
			                    	<div class="right">
			                    		<textarea name="data" id="data" rows="10" class="widefat no-wrap" style="height:300px"><?php echo ( isset( $keyword['data'] ) ? $keyword['data'] : '' ); ?></textarea>
			                    	
				                    	<p class="description">
				                    		<?php _e( 'Word(s) or phrase(s) which will be cycled through when generating content using the above keyword template tag.', 'page-generator-pro' ); ?>
				                    		<br />
				                    		<?php _e( 'One word / phrase per line.', 'page-generator-pro' ); ?>
				                    		<br />
				                    		<?php _e( 'If no Terms are entered, the plugin will try to automatically determine a list of similar terms based on the supplied keyword when you click Save.', 'page-generator-pro' ); ?>
				                    	</p>
			                    	</div>
			                    </div>

			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<label for="delimiter"><?php _e( 'Delimiter', 'page-generator-pro' ); ?></label>
			                    	</div>
			                    	<div class="right">
			                    		<input type="text" name="delimiter" id="delimiter" value="<?php echo ( isset( $keyword['delimiter'] ) ? $keyword['delimiter'] : '' ); ?>" class="widefat" />
			                    	
				                    	<p class="description">
				                    		<?php _e( 'Optional: If each Keyword Term comprises of two or more words, and you wish to access individual word(s) within each Term when using this Keyword in the Generate Content / Terms screens, define the seperating delimiter here.', 'page-generator-pro' ); ?><br />
				                    		<?php _e( 'For example, if Keyword Terms above are in the format <code>City, County, ZIP Code</code> the delimiter would be a comma <code>,</code>', 'page-generator-pro' ); ?><br />
				                    	</p>
			                    	</div>
			                    </div>

			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<label for="columns"><?php _e( 'Columns', 'page-generator-pro' ); ?></label>
			                    	</div>
			                    	<div class="right">
			                    		<input type="text" name="columns" id="columns" value="<?php echo ( isset( $keyword['columns'] ) ? $keyword['columns'] : '' ); ?>" class="widefat" />
			                    	
				                    	<p class="description">
				                    		<?php _e( 'Optional: If each Keyword Term comprises of two or more words, and you wish to access individual word(s) within each Term when using this Keyword in the Generate Content / Terms screens, define each column name here.', 'page-generator-pro' ); ?><br />
				                    		<?php _e( 'For example, if your Keyword Terms are in the format <code>City, County, ZIP Code</code>, enter <code>city,county,zipcode</code> here.', 'page-generator-pro' ); ?><br />
				                    		<?php _e( 'When generating content, you can then use e.g. <code>{keyword(city)}</code> for each City.', 'page-generator-pro' ); ?><br />
				                    		<?php _e( 'Separate column names with a comma, regardless of the Delimiter specified above.', 'page-generator-pro' ); ?>
				                    	</p>
			                    	</div>
			                    </div>
			                    
			                    <div class="wpzinc-option">
			                    	<div class="left">
			                    		<label for="file"><?php _e( 'Data Import', 'page-generator-pro' ); ?></label>
			                    	</div>
			                    	<div class="right">
			                    		<input type="file" name="file" id="file" />
			                    	
				                    	<p class="description">
				                    		<?php _e( 'To mass import data, upload either a CSV file (format word1,word2,word3) or TXT file (one word / phrase per line).', 'page-generator-pro' ); ?>
											<br />
											<?php _e( 'This will append the imported words / phrases to the above Keyword Data.', 'page-generator-pro' ); ?>
				                    	</p>
			                    	</div>
			                    </div>
			                    
			                    <div class="wpzinc-option">
		                    		<?php wp_nonce_field( 'save_keyword', $this->base->plugin->name . '_nonce' ); ?>
		                			<input type="submit" name="submit" value="<?php _e( 'Save', 'page-generator-pro' ); ?>" class="button button-primary" />
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