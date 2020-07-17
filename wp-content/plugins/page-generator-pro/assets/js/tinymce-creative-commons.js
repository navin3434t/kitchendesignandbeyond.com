/**
 * Initialises the Creative Commons modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	2.6.9
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_creative_commons', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_creative_commons', {
			title: 	'Insert Creative Commons Image',
			image: 	url + '../../../images/icons/creative-commons.svg',
			cmd: 	'page_generator_pro_creative_commons',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_creative_commons', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert Creative Commons Image',
                width: 	800,
                height: 508,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'creative-commons'
	            },
	            function( response ) {
	            	// Inject HTML into modal
	            	jQuery( '#page-generator-pro-modal-body-body' ).html( response );
	            	
	            	// Initialize tabbed interface
	            	wp_zinc_tabs_init();

	            	// Reload autocomplete instances
	            	page_generator_pro_autocomplete_initialize();
	            }
	        );
		} );
	} );

} )();