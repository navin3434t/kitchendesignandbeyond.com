/**
 * Initialises the Related Links modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	1.0.0
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_related_links', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_related_links', {
			title: 	'Insert Related Links',
			image: 	url + '../../../../_modules/dashboard/feather/list.svg',
			cmd: 	'page_generator_pro_related_links',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_related_links', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert Related Links',
                width: 	800,
                height: 710,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'related-links'
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

	            	// Initialize conditional fields
	            	page_generator_pro_conditional_fields_initialize();
	            	jQuery( 'select.wpzinc-conditional, .wpzinc-conditional select' ).trigger( 'change' );
	            }
	        );
		} );
	} );

} )();