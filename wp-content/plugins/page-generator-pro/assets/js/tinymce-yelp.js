/**
 * Initialises the Yelp modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	1.0.0
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_yelp', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_yelp', {
			title: 	'Insert Yelp Listings',
			image: 	url + '../../../../_modules/dashboard/feather/yelp.svg',
			cmd: 	'page_generator_pro_yelp',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_yelp', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert Yelp Listings',
                width: 	800,
                height: 610,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'yelp'
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