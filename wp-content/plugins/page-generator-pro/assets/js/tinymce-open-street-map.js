/**
 * Initialises the OpenStreetMap modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	2.2.6
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_open_street_map', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_open_street_map', {
			title: 	'Insert OpenStreetMap',
			image: 	url + '../../../../_modules/dashboard/feather/map.svg',
			cmd: 	'page_generator_pro_open_street_map',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_open_street_map', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert OpenStreetMap',
                width: 	600,
                height: 295,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'open-street-map'
	            },
	            function( response ) {
	            	// Inject HTML into modal
	            	jQuery( '#page-generator-pro-modal-body-body' ).html( response );

	            	// Reload autocomplete instances
	            	page_generator_pro_autocomplete_initialize();
	            }
	        );
		} );
	} );

} )();