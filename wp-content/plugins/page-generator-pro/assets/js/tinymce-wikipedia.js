/**
 * Initialises the Wikipedia modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	1.0.0
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_wikipedia', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_wikipedia', {
			title: 	'Insert Wikipedia Content',
			image: 	url + '../../../../_modules/dashboard/feather/wikipedia.svg',
			cmd: 	'page_generator_pro_wikipedia',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_wikipedia', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert Wikipedia Content',
                width: 	800,
                height: 600,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'wikipedia'
	            },
	            function( response ) {
	            	// Inject HTML into modal
	            	jQuery( '#page-generator-pro-modal-body-body' ).html( response );
	            	
	            	// Initialize tabbed interface
	            	wp_zinc_tabs_init();

	            	// Initialize selectize instances
	            	page_generator_pro_reinit_selectize();

	            	// Initialize autocomplete instances
	            	page_generator_pro_autocomplete_initialize();
	            }
	        );
		} );
	} );

} )();