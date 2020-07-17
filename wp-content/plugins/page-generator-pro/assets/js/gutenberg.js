// Determine if Gutenberg is active
page_generator_pro_gutenberg_active = ( ( typeof wp.data !== 'undefined' && typeof wp.data.dispatch( 'core/edit-post' ) !== 'undefined' ) ? true : false );

if ( page_generator_pro_gutenberg_active && wp.data.dispatch( 'core/edit-post' ) !== null ) {
	// Remove the Permalink Panel, if we're using Gutenberg
	wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'post-link' );

	if ( typeof page_generator_pro_gutenberg != 'undefined' ) {

		// Register Blocks
		pageGeneratorProGutenbergRegisterBlocks();

		// Initialize autocomplete instances
	    page_generator_pro_autocomplete_initialize();

		// Initialize conditional fields
		page_generator_pro_conditional_fields_initialize();

	}

}

/**
 * Registers Blocks in Gutenberg
 *
 * @since 	2.5.4
 */
function pageGeneratorProGutenbergRegisterBlocks() {

	for ( const shortcode in page_generator_pro_gutenberg.shortcodes ) {

		// Fetch Shortcode Properties
		var shortcodeProperties = page_generator_pro_gutenberg.shortcodes[ shortcode ];

		// Build Gutenberg compliant Attributes object
		var shortcodeAttributes = {};
		for ( const field in shortcodeProperties.fields ) {
			// Assume the attribute's type is a string
			var type = 'string';

			// Depending on the field's type, change the attribute type
			switch ( shortcodeProperties.fields[ field ].type ) {
				case 'number':
					type = 'number';
					break;

				case 'text_multiple':
					type = 'array';
					break;
				
				case 'select_multiple':
					type = 'array';
					break;

				case 'toggle':
					type = 'boolean';
					break;
			}

			// Define the attribute's type
			shortcodeAttributes[ field ] = {
				type: type,
			}
		}

		// Register Block
		// https://rudrastyh.com/gutenberg/inspector-controls.html
		( function( blocks, editor, element, components, shortcode, shortcodeProperties ) {

			// Define some constants for the various items we'll use
			const el = element.createElement;
			const { registerBlockType } = blocks;
			const { RichText, InspectorControls } = editor;
			const { Fragment } = element;
			const {
				TextControl,
				CheckboxControl,
				RadioControl,
				SelectControl,
				TextareaControl,
				ToggleControl,
				RangeControl,
				FormTokenField,
				Panel,
				PanelBody,
				PanelRow
			} = components;

			// Build Icon, if it's an object
			var icon = 'dashicons-tablet';
			if ( typeof shortcodeProperties.icon !== 'undefined' ) {
				if ( shortcodeProperties.icon.search( 'svg' ) >= 0 ) {
					// SVG
					icon = element.RawHTML( {
						children: shortcodeProperties.icon
					} );
				} else {
					// Dashicon
					icon = shortcodeProperties.icon;
				}
			}

			// Register Block
		    registerBlockType( 'page-generator-pro/' + shortcode, {
		        title:      shortcodeProperties.title,
		        description:shortcodeProperties.description,
		        category:   shortcodeProperties.category,
		        icon:       icon,
		        keywords: 	shortcodeProperties.keywords,

		        // Define the shortcode attributes
		        attributes: shortcodeAttributes,
		   
		        // Editor
		        edit: function( props ) {

		        	// Build Inspector Control Panels, which will appear in the Sidebar when editing the Block
		        	var panels = [],
		        		initialOpen = true;
		            for ( const panel in shortcodeProperties.tabs ) {

		            	// Build Inspector Control Panel Rows, one for each Field
		            	var rows = [];
		            	for ( var i in shortcodeProperties.tabs[ panel ].fields ) {
		            		const attribute = shortcodeProperties.tabs[ panel ].fields[ i ], // e.g. 'term'
		            			  field = shortcodeProperties.fields[ attribute ]; // field array

		            		var fieldElement,
		            			fieldClassNames = [],
		            			fieldProperties = {},
		            			fieldOptions = [],
		            			fieldSuggestions = [],
		            			fieldData = {};

		            		// Build values for <select> inputs
		            		if ( typeof field.values !== 'undefined' ) {
		            			for ( var value in field.values ) {
	            					fieldOptions.push( {
	            						label: field.values[ value ],
	            						value: value
	            					} );
	            					fieldSuggestions.push( '[' + value + '] ' + field.values[ value ] ); // NEVER CHANGE THIS EVER
	            				}
		            		}

		            		// Build data- attributes
		            		if ( typeof field.data !== 'undefined' ) {
		            			for ( var key in field.data ) {
		            				fieldData[ 'data-' + key ] = field.data[ key ];
		            			}
		            		}

		            		// Build CSS class name(s)
		            		if ( typeof field.class !== 'undefined' ) {
		            			fieldClassNames.push( field.class );
		            		}
		            		if ( typeof field.condition !== 'undefined' ) {
		            			fieldClassNames.push( field.condition.value );
		            		}

		            		// Define Field Element based on the Field Type
		            		switch ( field.type ) {

		            			case 'select':
		            				// Define field properties
		            				fieldProperties = {
				                        label: 		field.label,
				                        help: 		field.description,
				                        className: 	fieldClassNames.join( ' ' ),
				                        options: 	fieldOptions,
				                        value: 		props.attributes[ attribute ],
				                        onChange: function( value ) {
				                        	var newValue = {};
				                        	newValue[ attribute ] = value;
				                            props.setAttributes( newValue );
				                        }
				                    };

				                    // Add data- attributes
			                    	for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}

				                    // Define field element
		            				fieldElement = el(
		            					SelectControl,
		            					fieldProperties
		            				);
		            				break;

		            			case 'select_multiple':
		            				// Convert values to labels
		            				var values = [];
		            				for ( var index in props.attributes[ attribute ] ) {
		            					values.push( '[' + props.attributes[ attribute ][ index ] + '] ' + field.values[ props.attributes[ attribute ][ index ] ] );
		            				}
		            				
		            				// Define field properties
		            				fieldProperties = {
				                        label: 			field.label,
				                        help: 			field.description,
				                        className: 		fieldClassNames.join( ' ' ),
				                        suggestions: 	fieldSuggestions,
				                        maxSuggestions: 5,
				                        value: 			values,
				                        onChange: function( values ) {
				                        	// Extract values between square brackets, and remove the rest
				            				for ( var index in values ) {
				            					values[ index ] = values[ index ].match( /\[(-?\d+)\]/ )[1];
				            				}

				                        	var newValue = {};
				                        	newValue[ attribute ] = values;
				                            props.setAttributes( newValue );
				                        },
				                    };

				                    // Add data- attributes
				                    for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}

				                    // Define field element
		            				fieldElement = el(
		            					FormTokenField,
		            					fieldProperties
		            				);
		            				break;

		            			case 'text_multiple':
		            				// Define field properties
		            				fieldProperties = {
				                        label: 			field.label,
				                        help: 			field.description,
				                        className: 		fieldClassNames.join( ' ' ),
				                        value: 			props.attributes[ attribute ],
				                        onChange: function( values ) {
				                        	var newValue = {};
				                        	newValue[ attribute ] = values;
				                            props.setAttributes( newValue );
				                        },
				                    };

				                    // Add data- attributes
				                    for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}

				                    // Define field element
		            				fieldElement = el(
		            					FormTokenField,
		            					fieldProperties
		            				);
		            				break;

		            			case 'toggle':
		            				// Define field properties
		            				fieldProperties = {
				                        label: 		field.label,
				                        help: 		field.description,
				                        className: 	fieldClassNames.join( ' ' ),
				                        checked: 	props.attributes[ attribute ],
				                        onChange: function( value ) {
				                        	var newValue = {};
				                        	newValue[ attribute ] = value;
				                            props.setAttributes( newValue );
				                        },
				                    }

				                    // Add data- attributes
				                    for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}
				                    
				                    // Define field element
		            				fieldElement = el(
		            					ToggleControl,
		            					fieldProperties
		            				);
		            				break;

		            			case 'number':
		            				// Define field properties
		            				fieldProperties = {
				                        type: 		field.type,
				                        label: 		field.label,
				                        help: 		field.description,
				                        min: 		field.min,
				                        max: 		field.max,
				                        step: 		field.step,
				                        className: 	fieldClassNames.join( ' ' ),
				                        value: 		props.attributes[ attribute ],
				                        onChange: function( value ) {
				                        	// Cast value to integer if a value exists
				                        	if ( value.length > 0 ) {
				                        		value = Number( value );
				                        	}

				                        	var newValue = {};
				                        	newValue[ attribute ] = value;
				                            props.setAttributes( newValue );
				                        },
				                    };

				                    // Add data- attributes
				                    for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}

				                    // Define field element
		            				fieldElement = el(
					                    TextControl,
					                    fieldProperties
					                );
		            				break;

		            			default:
		            				// Define field properties
		            				fieldProperties = {
				                     	type: 		field.type,
				                        label: 		field.label,
				                        help: 		field.description,
				                        className: 	fieldClassNames.join( ' ' ),
				                        value: 		props.attributes[ attribute ],
				                        onChange: function( value ) {
				                        	var newValue = {};
				                        	newValue[ attribute ] = value;
				                            props.setAttributes( newValue );
				                        },
				                    };

				                    // Add data- attributes
				                    for ( var key in fieldData ) { 
			                    		fieldProperties[ key ] = fieldData[ key ];
			                    	}

				                    // Define field element
		            				fieldElement = el(
					                    TextControl,
					                    fieldProperties
					                );
		            				break;
		            		}

		            		// Add Field as a Row
		            		rows.push(
		            			el( 
		            				PanelRow,
		            				{},
		            				fieldElement
		            			)
		            		);
		            	}

		            	// Add the Panel Rows to a new Panel
		            	panels.push(
		            		el( 
		            			PanelBody,
		            			{
		            				title: shortcodeProperties.tabs[ panel ].label,
		            				initialOpen: initialOpen
		            			},
		            			rows
		            		)
		            	);

		            	// Don't open any further panels
		            	initialOpen = false;
		            }

		            // Return
		            return (
						el( 
							Fragment, 
							{},
				            el(
				            	InspectorControls,
				            	{},
				            	panels
				            ),

				            // Block Markup
				            el(
				            	'div',
				            	{},
				            	'[page-generator-pro-' + shortcode + ']'
				            )
				        )
				    );
		        },

		        // Output
		        save: function( props ) {

		        	return null;

		        }
		    } );

		} (
		    window.wp.blocks,
		    window.wp.blockEditor,
		    window.wp.element,
		    window.wp.components,
		    shortcode,
		    shortcodeProperties
		) );

	}

}

