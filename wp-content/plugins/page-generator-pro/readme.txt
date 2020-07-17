=== Page Generator Pro ===
Contributors: wpzinc
Donate link: https://www.wpzinc.com/plugins/page-generator-pro
Tags: page,generator,content,bulk,pages
Requires at least: 5.0
Tested up to: 5.4.2
Requires PHP: 7.2
Stable tag: trunk

Generate multiple Pages, Posts and Custom Post Types using dynamic content.

== Description ==

Page Generator allows you to generate multiple Pages, Posts or Custom Post Types, each with their own variation of a base content template.  

Variations can be produced by using keywords, which contain multiple words or phrases that are then cycled through for each Page that is generated.

Generate multiple Pages, Posts or CPT's in bulk by defining:

* Page Title
* Page Slug / Permalink
* Content
* Publish status (Draft or Publish)
* Number of Pages to generate
* Author

[youtube http://www.youtube.com/watch?v=KTBDy3-6Z1E]

= Support =

For all support queries, please email us: <a href="mailto:support@wpzinc.com">support@wpzinc.com</a>

= WP Zinc =

We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us:

* <a href="http://www.wpzinc.com">Our Plugins</a>
* <a href="http://www.facebook.com/wpzinc">Facebook</a>
* <a href="http://twitter.com/wp_zinc">Twitter</a>
* <a href="https://plus.google.com/b/110192203343779769233/110192203343779769233/posts?rel=author">Google+</a>

== Installation ==

1. Upload the `page-generator-pro` folder to the `/wp-content/plugins/` directory
2. Active the Page Generator Pro plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `Page Generator Pro` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Keywords table
2. Editing a keyword
3. Generating Pages screen

== Changelog ==

= 2.7.3 (2020-07-09) =
* Added: Keywords: Add/Edit: Don't wrap a single Term onto multiple lines
* Fix: Keywords: Generate Locations: Don't allow non-valid Output Types
* Fix: Keywords: Generate Phone Area Codes: Don't allow non-valid Output Types
* Fix: Dynamic Elements: Related Links: Don't allow non-valid Groups
* Fix: Dynamic Elements: Wikipedia: Don't allow non-valid Elements
* Fix: Dynamic Elements: Wikipedia: 500 error would occur when a child node could not be removed

= 2.7.2 (2020-07-02) =
* Added: Keyword Transformations: Output Different Random Term.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-keywords/#output-different-random-term
* Added: Keyword Transformations: First Word and Last Word Transformations.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-keywords/#transforming-keywords
* Fix: Generate: Content: Duplicate: Settings and some third party Plugin Settings wouldn't copy to duplicated Content Group
* Fix: Generate: Terms: Duplicate: Settings wouldn't copy to duplicated Content Group
* Fix: Dynamic Elements: Oxygen Builder: Render shortcodes on Generation
* Fix: Updated Contextual Link to reflect new Documentation structure
* Fix: Whitelabelling: Don't display Review Request notification if whitelabelling is available

= 2.7.1 (2020-06-25) =
* Added: Dynamic Elements: Wikipedia: Option to specify elements to return (paragraphs, lists, headings, tables).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-wikipedia-content/
* Added: Dynamic Elements: Wikipedia: Option to retain or remove links in imported Wikipedia content.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-wikipedia-content/
* Added: Generate: Content: Menu: Add Generated Page to a WordPress Menu.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--menu 
* Fix: Dynamic Elements: Wikipedia: Retain article formatting (bold, italic etc)
* Fix: Dynamic Elements: Wikipedia: Undefined offset error when specifying the last section of a Wikipedia Article
* Fix: Keywords: Add/Edit: Validation: Columns: Ensure comma is used to separate Column Names
* Fix: Keywords: Add/Edit: Validation: Improved error messages when validating field values
* Fix: Keywords: Add/Edit: Use <label> for field names for accessibility
* Fix: Keywords: Edit: Form field values wouldn't display immediately after correcting a validation error and successfully saving
* Fix: ACF: Uncaught ArgumentCountError: Too few arguments to function Page_Generator_Pro_ACF::match_term_group_location_rule() when activating Themes or Plugins that bundle older versions of ACF

= 2.7.0 (2020-06-18) =
* Added: Import: Support for Zipped JSON file
* Added: Export: Export as JSON, Zipped

= 2.6.9 (2020-06-11) =
* Added: Generate Content: WooCommerce Products: Display Product Data and Gallery Meta boxes, providing native support for generating Products.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-woocommerce-products/
* Added: Generate: Content: Dynamic Elements: Creative Commons Images.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-creative-commons-image/
* Fix: Dynamic Elements: Gutenberg: Dynamic Elements can be used inside other blocks, such as columns
* Fix: Dynamic Elements: Yelp: Honor Language / locale instead of always using en, which would then fail

= 2.6.8 (2020-06-04) =
* Added: Dynamic Elements: Yelp: Include precise Yelp error response in Test mode when fetching listings fails
* Fix: Dynamic Elements: Yelp: Default locale to en_US instead of get_locale() to avoid HTTP 400 request errors 
* Fix: Import & Export: Improved importing and exporting to catch edge cases where imports and exports might fail

= 2.6.7 (2020-05-28) =
* Added: Keywords: Generate Locations: Cities: Added Population and Median Household Income data for Canada
* Added: Dynamic Elements: Related Links: Option to specify Link Anchor Title when Output Type = List of Links or List of Links, Comma Separated.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-related-links/
* Fix: Dynamic Elements: Media Library: Don't link image to '_self' when no Link specified
* Fix: Keywords: Generate Locations: Some UK Cities had incorrect population numbers 

= 2.6.6 (2020-05-23) =
* Fix: Activation: Could not load class Page_Generator_Pro_Common
* Fix: Generate: Content: SiteOrigins Page Builder: Display Buttons to add Dynamic Elements / Shortcodes
* Fix: Generate: Terms: Removed debugging output on Term Meta
* Removed: Keywords: Generate Locations: Cities: Removed Population Ethnicity Data

= 2.6.5 (2020-05-21) =
* Added: Keywords: Generate Locations: Region Code returns ISO3166 two-letter Region Code
* Added: Generate: Content: Publish draft Content Group immediately before Test, Generate or Generate via Browser to ensure generation works in Gutenberg
* Added: Generate: Content: Prevent Preview of Content Group. Use Test functionality to test output
* Added: WP-CLI: List Term Groups Command.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/#list-term-groups
* Added: Keywords: Screen Options to define Keywords per Page.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords/#define-number-of-keywords-per-page
* Added: Dynamic Elements: Related Links: List of Links, Comma Separated Output Type.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-related-links/
* Fix: WP-CLI: List Content Groups Command would only list first Group.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/#list-content-groups
* Fix: Keywords: Retain Search, Order and Order By parameters when using Pagination
* Fix: Performance Addon: Load Cornerstone, Polylang and WPML
* Fix: Generate: Content: Generate Spintax: TinyMCE / Classic Editor button was missing
* Fix: Generate: Content: Generate Spintax: ChimpRewriter: Strip slashes on quotation marks

= 2.6.4 (2020-05-07) =
* Fix: Generate via Server: Reset Searches and Replacements to prevent same Keyword Term being used for each Generated Page
* Fix: Generate via CLI: Reset Searches and Replacements to prevent same Keyword Term being used for each Generated Page

= 2.6.3 (2020-05-07) =
* Added: Checks to ensure server configuration for correct working functionality, showing an error notice where failing
* Added: Generate: Content: Register Metaboxes on Content Groups where Metaboxes reigstered by Themes using Metabox.io
* Added: Dynamic Elements: Apply sensible default values to new Dynamic Elements
* Added: ACF: Specify Field Group to display on specific Content Groups.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-custom-field-plugins/#advanced-custom-fields--content-groups
* Added: ACF: Specify Field Group to display on specific Term Groups.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-custom-field-plugins/#advanced-custom-fields--term-groups
* Fix: Keywords: Generate Keyword Term Ideas.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords/#generate-keyword-term-ideas
* Fix: Generate: Content: Wizelaw Theme: Preserve Metaboxes on non-Content Groups
* Fix: Generate: Terms: Copy Term Meta (ACF, Yoast) to Generated Terms
* Fix: Dynamic Elements: Media Library: Regression where Operator option was removed
* Fix: Dynamic Elements: Yelp: Display Sort By option on TinyMCE instances
* Fix: Generate: Multilingual Content: WPML: Prevent 404 on Generated Content when WPML not enabled on Content Groups
* Fix: Generate: Content: Keyword Transformations: Detect mb_* functions for transforming accented and special characters, falling back to less reliable methods if mb_* functions unavailable

= 2.6.2 (2020-04-30) =
* Added: Generate: Content: Construction Theme: General, Header, Sidebar and Footer Options available in Content Groups
* Added: Generate: Content: Medicenter Theme: Post and Sidebar Options available in Content Groups 
* Added: Dynamic Elements: Related Links: Classic Editor / TinyMCE Shortcode available in all Post Types (Posts, Pages etc)
* Added: Dynamic Elements: Related Links: Gutenberg Block available in all Post Types (Posts, Pages etc)
* Added: Dynamic Elements: Yelp Business Listings: Option to specify Image Alt Tag.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-yelp-business-listings/
* Fix: Dynamic Elements: Gutenberg: Numeric Fields would should blank instead of saved value
* Fix: Generate: Support Numeric Keywords with Columns
* Fix: Generate: Support Keywords with Columns regardless of Column Name being upper/lower/mixed case
* Fix: Generate: Content: Store Keywords functionality not working
* Fix: Generate: Terms: Keywords not being replaced by Terms
* Fix: Generate: Don't attempt to replace Keywords that don't exist
* Fix: Export: PHP Warning: count(): Parameter must be an array or an object that implements Countable when no Term Groups specified
* Fix: CSS: Renamed option class to wpzinc-option to avoid CSS conflicts with third party Plugins

= 2.6.1 (2020-04-23) =
* Added: Generate: Logs.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/logs/
* Added: Generate: Content: Generate via Server: Option to enable logging.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-run/#generate-via-server
* Fix: Generate: Improved Performance by ~80% when using ~10,000+ Keyword Terms and/or Keyword Transformations, Columns and nth Terms.
* Fix: Generate: Content: Generate via Server: Generation would fail when Number of Posts and Resume Index were zero
* Fix: Generate: Content: Elementor: Removed unused tooltip classes to prevent Menu and Element Icons from not displaying
* Fix: Generate: Content: Visual Composer: Show Generated Page's Content when manually editing an existing Generated Page
* Fix: Generate: Content: Cornerstone (Pro / X Theme): Only attempt to convert Elements when Cornerstone is active
* Fix: Generate: Content: Cornerstone (Pro / X Theme): Honor Whitelabelling Setting on Agency Licenses for Dynamic Element Names

= 2.6.0 (2020-04-16) =
* Added: Licensing: Verbose error message when unable to connect to Licensing API
* Added: Keywords: Generate Locations: Verbose error message when unable to connect to Georocket API
* Added: Generate: Content: Flatsome Theme: Dynamic Elements / Shortcodes available in Text Element. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-flatsome/#dynamic-elements
* Added: Generate: Content: Pro Theme: Dynamic Elements / Shortcodes available as Elements. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-pro-theme/#dynamic-elements
* Added: Generate: Content: X Theme: Dynamic Elements / Shortcodes available as Elements. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-x-theme/#dynamic-elements
* Fix: Keywords: Generate Locations: Uncaught ReferenceError: page_generator_pro_show_error_message_and_exit is not defined Javascript error
* Fix: Related Links: Default Output Type = List of Links when no Output Type specified
* Fix: Licensing: Don't repetitively check the validity of a license that's invalid or exceeds the number of sites permitted, unless we're on the Licensing screen
* Fix: Dashboard > Updates: Show link to Changelog on View version details link

= 2.5.9 (2020-04-09) =
* Added: Generate: Content: Porto2 Theme: Layout and Sidebar settings compatibility. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-porto2-theme/
* Fix: Menu: Don't display Generate sub menu at WordPress Admin > Page Generator Pro
* Fix: Generate: Content: Gutenberg: Use WordPress native serialize_blocks() function to prevent columns and Classic Blocks being stripped from Generated Pages 
* Fix: Dynamic Elements: YouTube: Gutenberg: Parse oEmbed URL to output video instead of YouTube URL

= 2.5.8 (2020-04-02) =
* Added: Keywords: Generate Locations: Only fetch Output Types when sending API request for performance
* Added: Generate: Content: SiteOrigins Page Builder: Buttons to add Dynamic Elements / Shortcodes into Backend Editor Module. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-siteorigin-page-builder/#dynamic-elements
* Added: Generate: Content: Thrive Architect: Buttons to add Dynamic Elements / Shortcodes into WordPress Content Element. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-thrive-architect/#dynamic-elements
* Added: Generate: Content: Visual Composer: Buttons to add Dynamic Elements / Shortcodes into Frontend Text Block. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-visual-composer/#dynamic-elements
* Added: Generate: Content: WPBakery Page Builder: Buttons to add Dynamic Elements / Shortcodes into Frontend Text Block. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-wpbakery-page-builder/#dynamic-elements
* Added: Generate: Content: Dynamic Elements: Media Library: Option to link image.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-media-library-image/#configuration--link
* Added: Generate: Content: Dynamic Elements: Pexels: Option to link image.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-pexels/#configuration--link
* Added: Generate: Content: Dynamic Elements: Pixabay: Option to link image.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-dynamic-elements-pixabay/#configuration--link
* Fix: Keywords: Generate Locations: Number of columns does not match deliniated Terms error would occur when using a City, County or Region Wikipedia URL containing a comma

= 2.5.7 (2020-03-26) =
* Added: Generate: Content: Beaver Builder: Buttons to add Dynamic Elements / Shortcodes into Text Editor Module. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-beaver-builder/#dynamic-elements
* Added: Generate: Content: BeTheme / Muffin Page Builder: Buttons to add Dynamic Elements / Shortcodes into Visual Editor. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-betheme-muffin-page-builder-integration/
* Added: Generate: Content: Bold Builder: Buttons to add Dynamic Elements / Shortcodes into Text Element. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-bold-builder/
* Added: Generate: Content: Divi: Buttons to add Dynamic Elements / Shortcodes into Text Module. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-divi/#dynamic-elements
* Added: Generate: Content: Elementor: Buttons to add Dynamic Elements / Shortcodes into Text Editor. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-elementor/#dynamic-elements
* Added: Generate: Content: Enfold / Avia Layout Builder: Buttons to add Dynamic Elements / Shortcodes into Text Block. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-enfold-avia-layout-builder/#dynamic-elements
* Added: Generate: Content: Live Composer: Buttons to add Dynamic Elements / Shortcodes into Text Element. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-live-composer/#dynamic-elements
* Added: Generate: Content: Oxygen Builder: Buttons to add Dynamic Elements / Shortcodes into Rich Text Module. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-oxygen-builder/#dynamic-elements
* Added: Generate: Content: WPBakery Page Builder: Buttons to add Dynamic Elements / Shortcodes into Backend Text Block. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration-wpbakery-page-builder/#dynamic-elements
* Added: Generate: Content: Generate via Server: Show error if DISABLE_WP_CRON is enabled in wp-config.php
* Added: Generate: Terms: Generate via Server: Show error if DISABLE_WP_CRON is enabled in wp-config.php
* Fix: Activation: Prevent DB character set / collation errors on table creation by using WordPress' native get_charset_collate()

= 2.5.6 (2020-03-23) =
* Added: Spintax: Improved performance of spintax for larger spins
* Fix: BeTheme / Muffin Page Builder: No such file or directory error

= 2.5.5 (2020-03-21) =
* Fix: Shortcode: OpenWeatherMap: array_merge() error

= 2.5.4 (2020-03-19) =
* Added: Generate: Content: Shortcodes are now available as Gutenberg Blocks.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/
* Fix: Generate: Content: Keyword Dropdown: Ensure width does not exceed meta box
* Fix: Generate: Content: Keyword Dropdown: Ensure height does not exceed 120px and is scrollable
* Fix: Generate: Multilingual Content: WPML would wrongly be detected as active when using Polylang
* Fix: Generate: Content: Divi: Honor Content Group's Featured Image setting when using Divi

= 2.5.3 (2020-03-13) =
* Fix: Shortcodes: Prevent errors when using frontend Page Builders

= 2.5.2 (2020-03-12) =
* Added: Performance: Only load required Plugin classes depending on the request type
* Added: Generate: Multlingual Content: WPML Integration.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-multilingual-content-wpml/
* Added: Shortcode: Related Links: List of Links: Link Description and Featured Image options.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/#output

= 2.5.1 (2020-03-05) =
* Added: Generate: Multlingual Content: Polylang Integration.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-multilingual-content-polylang/
* Added: Settings: General: Country Code: The default country to select for any Country Code dropdowns within the Plugin.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/#country-code
* Fix: Shortcodes: TinyMCE Modal input sizing for smaller screen resolution compatibility
* Fix: Shortcode: Related Links: Remove Distance Tags if no distance is available

= 2.5.0 (2020-02-27) =
* Added: Generate: Content: Featured Image: Option to specify EXIF Latitude, Longitude, Description and Comment in image file..  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--featured-image
* Fix: Generate: Content: Featured Image: Tabbed UI to match Media Library, Pexels and Pixabay shortcodes.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--featured-image
* Fix: EXIF: Write EXIF metadata if specified in a Shortcode or Featured Image, where the image supports EXIF but does not have existing EXIF metadata

= 2.4.9 (2020-02-20) =
* Added: Settings: OpenWeatherMaps: Option to use own API key.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/open-weather-map-settings/
* Fix: Generate: Content: Generate via Server: Permit unfiltered HTML so e.g. iframes are not stripped by WordPress on generation

= 2.4.8 (2020-02-17) =
* Added: Shortcode: OpenWeatherMaps.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-openweathermap/
* Added: Generate: Terms: Overwrite: Options to skip or overwrite if a Term exists, whether created by a Page Generator Pro Group or manually in WordPress.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-terms/#fields--generation
* Fix: Keywords: Generate Locations: Locations would not generate
* Fix: Generate: Terms: UI Output for Sidebar

= 2.4.7 (2020-02-14) =
* Fix: Generate: Content: Shortcodes would not insert into content when pressing Insert button

= 2.4.6 (2020-02-13) =
* Added: Generate: Content: Improved modal UI
* Added: Deactivation: Remove the Must-Use Performance Addon Plugin automatically, if not a Multisite environment

= 2.4.5 (2020-02-06) =
* Added: Keywords: Generate Locations: Cities: Added Population Male/Female, Children/Adults/Elderly, Ethnicity and Median Household Income Output Types for the USA.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Restrict by Min / Max Median Household Income Option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Shortcode: Related Links: Option to display distance in km or miles.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/
* Added: Install/Update: Copy Must-Use Plugin: Developer Actions.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/developers/
* Fix: Keywords: Could not Generate Locations / Save Keyword when defining several columns that would exceed 200 characters in total length 

= 2.4.4 (2020-01-30) =
* Added: Generate: Content: KuteThemes compatibility (Stuno, Ovic Addons Toolkit Plugin)

= 2.4.3 (2020-01-23) =
* Added: Generate: Content: {keyword:random} transformation to output random Keyword Term.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-keywords/#output-random-term
* Added: Whitelabelling and Access Control: Agency Licenses can control settings via https://www.wpzinc.com/account.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/whitelabelling-access/

= 2.4.2 (2020-01-09) =
* Fix: Shortcode: Wikipedia: Undefined variable $headings_keys, which would prevent some shortcodes from fetching Wikipedia content
* Fix: Shortcode: Wikipedia: Set User-Agent to ensure full HTML is fetched from Wikipedia prior to parsing, to minimise "no paragraphs could be found" errors

= 2.4.1 (2020-01-02) =
* Added: Generate: Content: Developer Actions.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/developers/
* Added: Generate: Terms: Developer Actions.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/developers/
* Added: Shortcode: Media Library: Assign Image to Generated Post when Create as Copy enabled
* Added: Shortcode: Pexels: Assign Image to Generated Post, not just the Group
* Added: Shortcode: Pixabay: Assign Image to Generated Post, not just the Group
* Added: Generate: Content: Overwrite: Delete existing Media Library attachments belonging to the existing Post and Group
* Fix: Generate: Content: Delete Generate Content: Only delete Media Library attachments belonging to the Deleted Post and Group
* Fix: Generate: Content: Block Spinning: Ensure #p# and #s# blocks outside of a #section# are spun when using #section# elsewhere
* Fix: Generate: Content: Generate Spintax: WordAI: Ensure response is not URL encoded

= 2.4.0 (2019-12-26) =
* Added: Shortcode: Media Library: Output: Create as Copy and Image Attribute options.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-media-library-image/#output
* Fix: EXIF: Uncaught Error: Call to a member function getIfd() on null

= 2.3.9 (2019-12-19) =
* Added: Keywords: Generate Locations: Cities: Added Wikipedia URL and Wikipedia Sumamry Output Types.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Generate: Content: Renamed Generate via CRON to Generate via Server.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-run/
* Added: Generate: Content: Option to Generate via Server when editing a Content Group.
* Added: Shortcode: Wikipedia: Exact Wikipedia URL can be specified as a Term.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-wikipedia-content/
* Added: Forms: Accessibility: Replaced Titles with <label> elements that focus the given input element on click
* Added: Generate: Content: Featured Image: EXIF Latitude, Longitude, Description and Comment (Caption) automatically written to image if specified.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--featured-image
* Added: Shortcode: Media Library: Option to specify EXIF Latitude, Longitude, Description and Comment in image file.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-media-library-image/#exif
* Added: Shortcode: Pexels: Option to specify EXIF Latitude, Longitude, Description and Comment in image file.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-pexels/#exif
* Added: Shortcode: Pixabay: Option to specify EXIF Latitude, Longitude, Description and Comment in image file.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-pixabay/#exif
* Fix: Shortcode: Wikipedia: Return all text if no Table of Contents exist on Wikipedia Page, to ensure smaller Wikipedia Pages return content
* Fix: Shortcode: Pexels/Pixabay: Caption and Description were stored the wrong way around
* Fix: Generate: Content: Featured Image: Image URL/Pexels/Pixabay: Caption and Description were stored the wrong way around
* Fix: Generate: Content: Keywords: Check Term exists when using Keyword Transformation with Column Name
* Fix: Generate: Content: Keyword Transformations: Check Term exists when using Keyword Transformation with Column Name
* Fix: Generate: Content: Keyword Transformations: Support accented and special characters

= 2.3.8 (2019-12-12) =
* Fix: New Installations / Plugin Activation: Could not load class geo

= 2.3.7 (2019-12-12) =
* Added: Keywords: Generate Locations: ZIP Codes: Added Latitude and Longitude Output Types.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Cities: Added Latitude and Longitude Output Types.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Counties: Added County Code, Wikipedia URL and Wikipedia Summary Output Types.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Regions: Added Region Code, Wikipedia URL and Wikipedia Summary Output Types.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Generate: Content: Geolocation Data.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--geolocation-data
* Added: Shortcode: Related Links: Radius Option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/#radius-conditions
* Added: Generate: Content: {keyword:all} transformation to output all Keyword Terms.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-keywords/#output-all-terms
* Added: Keywords: Delimiters can be ignored within Terms by using quotes.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords/#using-the-delimiter-character-within-terms 
* Fix: Keywords: Database error: Field 'columns doesn't have a default value

= 2.3.6 (2019-11-28) =
* Added: Generate: Content: The7 Theme Meta Box Support
* Added: Generate: Content: TheBuilt Theme Page and Post Settings Meta Boxes Support
* Added Shortcodes: Related Links: Reset margin and padding on links to improve Theme compatibility
* Fix: Shortcodes: Don't attempt to load JS if Post Content isn't available

= 2.3.5 (2019-11-21) =
* Added: Settings: General: Enable Revisions on Content Groups. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/
* Added: Generate: Content: Include Description when searching Content Groups
* Added: Generate: Content: Choose Sections of Content Group to overwrite.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--generate
* Notice: Generate: Content: Overwrite with Preserve Date option is deprecated; use Overwrite Sections to not overwrite existing Page published dates.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--generate
* Fix: Licensing: Obscure License Key if valid
* Fix: Settings: Display confirmation notification that settings have saved
* Fix: Settings: Change Page Parent Dropdown Field renamed to Change Page Dropdown Fields, and applied wherever WordPress attempts to list Pages (e.g. Appearance > Customize, Settings > Reading)
* Fix: Generate: Content: Don't show Group Filter Dropdown above WP_List_Table
* Fix: Shortcodes: Don't attempt to load CSS if Post Content isn't available
* Fix: Shortcodes: TinyMCE Modal input styling and sizing for WordPress 5.3 compatibility

= 2.3.4 (2019-11-14) =
* Added: Generate: Content: Enfold / Avia Builder: Display 'Advanced Layout Editor' button when Gutenberg enabled to toggle between Gutenberg and Avia
* Added: Shortcode: OpenStreetMap: Load CSS inline
* Added: Shortcodes: Only load JS and CSS when required
* Added: Licensing: Clear WordPress options cache when updating or deleting license validity information, to prevent aggressive third party caching solutions from storing stale data
* Fix: Spintax: SpinnerChief authentication would fail due to incorrect apikey parameter

= 2.3.3 (2019-10-24) =
* Fix: Shortcode: OpenStreetMap: Honor CSS Prefix change in leaflet.css
* Fix: Shortcode: Related Links: Honor CSS Prefix change in HTML
* Fix: Shortcode: Related Links: Don't attempt to trim multi select inputs (Group), which prevented Insert button working
* Fix: Shortcode: Wikipedia: Don't attempt to trim multi select inputs (Terms, Sections), which prevented Insert button working

= 2.3.2 (2019-10-17) =
* Fix: Licensing: Don't show license expired notice on Plugins screen, for performance
* Fix: Keywords: Import CSV: Attempt to UTF-8 encode strings in CSV files containing mixed UTF-8 and non-UTF-8 content

= 2.3.1 (2019-10-10) =
* Added: Generate: Content: Generate Spintax: Support for ChimpRewriter and SpinnerChief.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/spintax-settings/
* Added: Shortcodes: Autocomplete Keyword Suggestions displayed when typing in supported fields
* Fix: Shortcode: Related Links: Honor Group ID when limiting links by Custom Field Key / Value pairs
* Fix: Unexpected 'return' (T_RETURN) on PHP 5.x.  However, please note minimum supported PHP version of 7.1: https://www.wpzinc.com/documentation/installation-licensing-updates/hosting-requirements/#php-version

= 2.3.0 (2019-10-03) =
* Added: Shortcode: Related Links: Link, Previous and Next Titles support outputting Custom Field values.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/
* Added: Generate: Content: Block Spinning: Support for randomising order of paragraphs within sections.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-spintax/#block-spinning
* Added: Licensing: Show licensing server response on HTTP or server error
* Fix: Shortcode: Yelp: Ensure Radius cannot exceed the maximum supported 20 miles 
* Fix: Generate: Spintax: Support for larger spintax lengths and greater levels of nesting
* Fix: Licensing: Updated endpoint URL
* Fix: Licensing: Use options cache instead of transients to reduce license key and update failures

= 2.2.9 (2019-09-26) =
* Added: Keywords: Keyword Names can include any language
* Added: Generate: Content: Generate Spintax: Support for Spin Rewriter and WordAI.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/spintax-settings/
* Added: Settings: Spintax: Options to not spin capitalized words and define protected words.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/spintax-settings/
* Added: Shortcode: Pexels.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-pexels/
* Added: Shortcode: Pixabay.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-pixabay/
* Added: Generate: Content: Featured Image: Specify Title, Caption, Description and Filename when Image Source is Image URL, Pexels or Pixabay. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--featured-image
* Fix: Keywords: Terms: Remove empty newlines
* Fix: Generate: Content: Generate Spintax: Preserve line breaks and paragraphs
* Fix: Shortcode: Media Library: Tabbed UI so fields are not cut off on smaller screens
* Fix: Shortcode: Remove leading and trailing whitespace on any shortcode parameters
* Fix: Shortcode: Related Links: Insert button would fail when no Group (or 'This Group') specified
* Fix: Shortcode: Wikipedia: Improve Table of Contents detection to ensure content is returned
* Fix: Shortcode: Wikipedia: Improve Disambiguation Page detection when use_similar_page is enabled
* Fix: Shortcode: Wikipedia: Iterate through multiple Terms when specified in Generate mode
* Fix: Shortcode: Wikipedia: Support for multiple shortcode instances of the same term and different languages in a single Content Group
* Removed: Shortcode: Unsplash.  Use Pexels or Pixabay Shortcodes above

= 2.2.8 (2019-09-19) =
* Added: Generate: Content: Custom Fields: Option to automatically store the used Keyword(s) and Term(s) on generated Pages as Custom Fields / Post Meta data.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--custom-fields
* Added: Shortcode: Related Links: Limit links by Custom Field Key / Value pairs.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/#custom-fields
* Added: Shortcode: Wikipedia: Support for specifying one or more Terms to use, in order, when finding Wikipedia content. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-wikipedia-content/
* Added: Shortcode: Wikipedia: Option to fetch first similar page when Term could not be found and Wikipedia provides alternate Articles.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes-wikipedia-content/
* Added: Shortcode: Wikipedia: Verbose error logging when Wikipedia shortcode fails in Test mode, output on the generated Test Page
* Fix: Shortcode: Related Links: Tabbed UI so fields are not cut off on smaller screens
* Fix: Shortcode: Wikipedia: Return blank content if content could not be fetched
* Removed: CLI: Method and Overwrite override options.  Settings always taken from Group.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/

= 2.2.7 (2019-09-12) =
* Added: Generate: Content: Overwrite: Options to skip or overwrite if a Page exists, whether created by a Page Generator Pro Group or manually in WordPress.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--generate
* Added: Generate: Content: Verbose logging on whether Generation created, updated or skipped.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-run/#understanding-the-output-log
* Added: Shortcode: Wikipedia: Options to choose sections to output, maximum number of paragraphs, apply synonms and process spintax.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/#wikipedia-content
* Fix: Shortcode: Wikipedia: Remove footnote references from output text
* Fix: Generate: Content: Only attempt to UTF-8 Post Excerpt when Page Generation fails and the Post Type supports Excerpts
* Fix: Elementor: invalid_page_template error on Generation when overwriting existing generated Pages

= 2.2.6 (2019-08-29) =
* Added: Keywords: Generate Locations: Restrict by Min / Max City Population Option available when using Radius.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Generate: Content: Group ID displayed on Group Lists Table
* Added: Generate: Content: Last Index Generated displayed on Group Lists Table
* Added: Generate: Terms: Group ID displayed on Group Lists Table
* Added: Generate: Terms: Last Index Generated displayed on Group Lists Table
* Added: Shortcodes: Open Street Map.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/#openstreetmap
* Added: Shortcodes: Related Links: Option to specify multiple Group IDs.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/
* Fix: Generate: Content: Convert Post Parent string to sanitized Permalink to ensure Post Parent can be found when using non alpha-numeric characters

= 2.2.5 (2019-08-19) =
* Fix: TinyMCE Editor: Return registered TinyMCE Plugins when not registering Page Generator Pro TinyMCE Plugins

= 2.2.4 (2019-08-15) =
* Added: Shortcodes: Yelp: Radius, Minimum Rating, Language, Price Level and Sort options.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/#yelp-business-listings
* Added: Generate: Terms: Visual Editor for Description
* Fix: Shortcodes: Media Library Image: Adjusted layout to work on smaller screens, so fields are not cut off
* Fix: Shortcodes: Related Links: Adjusted layout to work on smaller screens, so fields are not cut off
* Fix: Generate: Terms: Don't remove HTML tags from Description
* Fix: Generate: Terms: Support for Block Spinning
* Fix: Generate: Terms: Align Action Buttons to the left

= 2.2.3 (2019-08-08) =
* Added: Settings: Google: Option to specify Google Maps API Key for Google Maps Shortcode embeds that are billable by Google (i.e. Street View, Driving Directions).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/google-settings/#google-maps-embed-usage-and-billing
* Added: Keywords: Generate Locations: City Population option in Output Type
* Added: Keywords: Generate Locations: Restrict by Min / Max City Population.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Generate: Content: Support for using 1 or 2 Keyword Transformations.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-keywords/#apply-multiple-keyword-transformations
* Added: Shortcodes: Related Links: Added Columns option for List of Links.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/
* Added: Import: Import Post Meta from third party Plugins for Content Groups (e.g. Yoast)

= 2.2.2 (2019-07-25) =
* Added: Whitelabelling: Plugin Name, Author and URL on WordPress Admin > Plugins is now whitelabelled.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/whitelabelling-access/
* Added: Shortcodes: Media Library: Operator option to define whether image must contain any or all of the given Title, Alt, Caption and Description value(s)
* Added: Generate: Content: Featured Image: Operator option to define whether image must contain any or all of the given Title, Alt, Caption and Description value(s)
* Fix: Generate: Content: Trim setting values to avoid failures in e.g. Featured Image searches, Overwriting by Title failing etc.
* Fix: Generate: Terms: Trim setting values to avoid failures in e.g. Overwriting by Title failing etc.
* Fix: Generate: Content: Block Spinning: remove blank lines in #s blocks, to avoid possibly selecting a blank sentence during Generation
* Fix: Import: Added support for UTF8 BOM sequenced / encoded JSON exported files

= 2.2.1 (2019-07-18) =
* Added: Keywords: Import CSV: Added support for UTF8 BOM sequenced / encoded CSV files
* Added: Shortcodes: Related Links: Specify Link Title format for each Related Link
* Added: Shortcodes: Related Links: Limit Related Links matching a given slug
* Fix: Shortcodes: Media Library: Honor search settings for Alt Tag, Caption and Description

= 2.2.0 (2019-07-11) =
* Added: Keywords: Generate Locations: Added Street Names and Zipcode Districts for the UK.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Shortcodes: Yelp: Only display the Yelp! logo once, regardless of how many times the shortcode is used in a content section
* Fix: Keywords: Generate Locations through browser would fail when whitelabelling enabled

= 2.1.9 (2019-07-08) =
* Fix: Generate: Content: "A name is required for this term." error when attempting to generate Post Types that have Taxonomies registered to them, and no Terms specified.

= 2.1.8 (2019-07-04) =
* Added: Generate: Content: Overwrite: Skip if Exists: Don't create or update a Page if already generated by the same Group with the same Permalink.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#fields--generate
* Added: Settings: General: Change Page Parent Dropdown to either ID Field or Search Dropdown.  Improves performance on WordPress sites with a large number of Pages.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/#change-page-parent-dropdown-field
* Fix: Settings: General: CSS Prefix: Only allow CSS and shortcode compliant characters
* Fix: Keywords: Import CSV: Correct identify screen to avoid loading unused Javascript
* Fix: Generate: Generate through browser would fail when whitelabelling enabled
* Fix: Generate: Terms: Uncaught TypeError: Cannot read property 'category' of undefined

= 2.1.7 (2019-06-27) =
* Added: Shortcodes: Related Links: Page Parent option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/ 
* Added: Access Control: Option to limit Plugin access (requires Agency License).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/whitelabelling-access/
* Added: Shortcodes: Related Links: Option to display Parent, Previous and/or Next Post / Page Links.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-related-links/
* Added: Generate: Content: Separator between Plugin TinyMCE Buttons and WordPress TinyMCE Buttons
* Added: Generate: Content: Standardised TinyMCE Button Icons
* Fix: Generate: Content: When overwrite enabled, only overwrite if an existing Page exists by Slug AND Parent.  Prevents the same page being overwritten every time in a generation routine

= 2.1.6 (2019-06-20) =
* Added: Settings: General: Change Page Parent Dropdown to ID Field.  Improves performance on WordPress sites with a large number of Pages.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/#change-page-parent-dropdown-to-id-field
* Fix: Generate Content: Use the Divi Builder / Use Frontend Builder would not work in some instances for newly created Content Groups
* Fix: Settings: Google: Removed Google Maps API key, as usage for embedded maps is free with no limit
* Fix: Generate: Content: Block Spinning: don't insert break / newlines for each sentence in a paragraph

= 2.1.5 (2019-06-13) =
* Added: Generate: Content: Make Theme Page Builder compatibility
* Added: Whitelabelling: Option to whitelabel Plugin (requires Agency License).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/whitelabelling-access/

= 2.1.4 (2019-06-06) =
* Added: Generate: Content: Display warning when saving a Group, or attempting to generate content from a Group, when the Group isn't saved (prevents other errors such as keyword missing errors)

= 2.1.3 (2019-05-31) =
* Fix: Shortcodes: Related Links: Don't attempt to process shortcode on Generation, resulting in its removal

= 2.1.2 (2019-05-30) =
* Fix: Generate: Content: BeTheme compatibility for 21.1.1+
* Fix: Keywords: Prevent success / error notices displaying twice in Keyword Table list

= 2.1.1 (2019-05-23) =
* Added: Generate: Content: Metaboxes are no longer filtered out or removed, ensuring better third party Theme / Plugin compatibility
* Fix: Generate: Content: Renamed Remove Trackbacks and Pingbacks to Remove Track / Pingbacks, to avoid text overflowing in the UI

= 2.1.0 (2019-05-16) =
* Added: Shortcodes: Yelp: Options to choose whether to display Image, Rating, Categories, Phone Number and/or Address
* Fix: Generate: Content: Scheduled Specific Date with Increment honors the increment

= 2.0.9 (2019-05-09) =
* Added: Generate: Content: Smartcrawl SEO Meta Box Support

= 2.0.8 (2019-05-06) =
* Fix: Don't load Gutenberg scripts when Avada Fusion Builder is used

= 2.0.7 (2019-05-02) =
* Added: Settings: General: Option to Disable Custom Fields Dropdown on Pages, for performance.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/
* Added: Settings: General: Option to Limit Depth on Page Parent Dropdown on Pages, for performance.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/
* Fix: Generate: Content: Number of Generated Items count now includes scheduled Pages
* Fix: Generate: Content: Trash and Delete Generated Content will Trash / Delete scheduled, draft, private and published Pages

= 2.0.6 (2019-04-25) =
* Added: Shortcode: Unsplash: Option to specify Title and Caption to use
* Fix: Shortcodes: Related Links: Ensure Related Links display when Settings > General > CSS Prefix is defined
* Fix: Generate: Ensure progress bar styles don't override other styles in the WordPress Admin UI

= 2.0.5 (2019-04-18) =
* Added: Shortcode: Media Library: Output Alt Tag option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/#media-library-image
* Fix: Shortcodes: Don't include blank parameters in shortcode output, as they're not needed.
* Fix: Keywords: Require both delimiter and column name if either field is specified.
* Fix: Generate: Content: Prevent PHP warnings displaying when a Keyword is specified with column names, but no delimiter.
* Fix: Generate: Content: Publish: Don't allow Generate to generate Posts in the Content Groups section.
* Fix: Generate: Content: Gutenberg: Don't display Gutenberg's Permalink Panel in the sidebar, as it's not used.

= 2.0.4 (2019-04-11) =
* Added: Settings: General: Option to specify unique CSS Prefix.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/general-settings/
* Added: Shortcode: Google Maps: Map Types for Road Map, Satellite, Directions and Street View.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/
* Fix: Settings: Generate: Corrected Meta Box Title
* Fix: Generate: Terms: Save Settings in Sidebar

= 2.0.3 (2019-04-03) =
* Fix: Keywords: Generate Locations: Include license key in requests for compatibility with location API, preventing errors

= 2.0.2 (2019-03-28) =
* Added: Groups: Content: Keywords can specify any combination of column name, transformation and index. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/#using-keywords--transforming-keywords
* Added: Groups: Content: TinyMCE: Autocomplete Keyword Suggestions displayed when typing
* Added: Groups: Content: Gutenberg Blocks: Autocomplete Keyword Suggestions displayed when typing
* Added: Groups: Split functionality into separate class files for performance across Groups Table, Groups Add/Edit and Groups
* Added: Groups: Terms: Split functionality into separate class files for performance across Groups Table, Groups Add/Edit and Groups
* Added: Groups: Terms: Parent Term and Taxonomy Fields on Add New Taxonomy Group form
* Fix: Groups: Terms: Show error message when attempting to delete generated content from a Term Group that has no generated content

= 2.0.1 (2019-03-21) =
* Added: Generate: Content: Block Spinning.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-spintax/
* Added: Generate: Content: Warning when specifying a static Permalink.
* Added: Generate: Content: Project Supremacy v3 Meta Box Support
* Added: Page Builders: Automatically register Page Generator Pro with supported Page Builders, instead of manually changing settings.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-page-builders-integration/
* Fix: Fatal error: Call to undefined function wp_doing_cron()
* Fix: Generate: Terms: Not all Terms would generate when Parent Term was specified and the Child Term exists as a Parent Term

= 2.0.0 (2019-03-07) =
* Added: Generate: Content: SEOPress and SEOPress Pro Meta Box Support

= 1.9.9 (2019-02-28) =
* Added: Generate: Content: Bulk Actions to Duplicate, Generate via CRON, Trash and Delete Generated Content
* Added: Generate: Content: Added Status Column to Groups Table
* Added: Generate: Content: Lock Group when it is generating content, to prevent editing part way through content generation
* Added: Generate: Content: Generate via WordPress Cron
* Added: Generate: Content: Moved Table Row Actions to respective columns for easier access and improved UI
* Added: Generate: Terms: Bulk Actions to Duplicate, Generate via CRON and Delete Generated Content
* Added: Generate: Terms: Added Status Column to Groups Table
* Added: Generate: Terms: Lock Group when it is generating terms, to prevent editing part way through term generation
* Added: Generate: Terms: Generate via WordPress Cron
* Added: Generate: Terms: Moved Table Row Actions to respective columns for easier access and improved UI
* Added: Generate: WP-CLI: Trash Generated Content Command
* Fix: Generate: Content: Custom Fields: Fix Meta Key / Value Field Alignment
* Fix: Generate: Content: Only display Trash / Delete Generated Content options if Generated Content exists
* Fix: Generate: Content: Fusion Builder 1.8.x not working with WordPress 5.1+
* Fix: Generate: Terms: Only display Trash / Delete Generated Content options if Generated Terms exists
* Fix: Generate: Terms: Copy Term Meta (e.g. Yoast data, ACF data etc) to Generated Terms
* Fix: Generate: WP-CLI: Generation would silently fail on some instances

= 1.9.8 (2019-02-21) =
* Added: Code refactoring for better performance
* Added: Keywords: Generate Locations: Add Exclusions options, to exclude Cities / Counties / Regions from results.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Generate: Terms: Autocomplete Keyword Suggestions displayed when typing in applicable fields that support Keywords
* Fix: Generate: Terms: Don't strip keyword characters, ensuring keywords are saved and replaced correctly
* Fix: Keywords: Generate Phone Area Codes: Javascript errors resulting in Output Type not displaying correctly
* Fix: Removed unused logging from Javascript
* Fix: Installation / Upgrade: PHP error when mu-plugin file failed to copy to mu-plugins folder
* Fix: Keywords: Aligned "Search results for" label correctly when searching for Keywords
* Fix: Content Groups: Aligned "Search results for" label correctly when searching for Content Groups
* Developers: get_instance() calls are deprecated in favour of Page_Generator_Pro()->get_class( 'class_name' ).  WordPress standard deprecated notices will display.

= 1.9.7 (2019-02-14) =
* Added: Settings: Generate: Option to enable Performance Addon.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/settings-generate/
* Added: Generate: Content: Test: Verbose errors displayed on generated test Page / Post.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-run/
* Added: Generate: Content: Excerpt: Only display Excerpt field if the Post Type being generated supports Excerpts
* Added: Generate: Content: Keywords Dropdown: List Column Subsets when a keyword's columns are defined
* Added: Generate: Content: Autocomplete Keyword Suggestions displayed when typing in applicable fields that support Keywords 
* Added: Generate: Content: Use GD Image Library instead of Imagick, if GD is available to WordPress.  Improves Performance and reduces server errors
* Added: Generate: Content: Delete associated Media Library attachments when Delete Generated Content is used
* Added: Shortcodes: Keywords Dropdown to applicable fields
* Added: Shortcode: Related Links: Options to define Author and Taxonomies
* Added: Shortcode: Related Links: Output title attribute on links
* Fix: Generate: Content: Only fetch Group Settings once, to improve performance
* Fix: Generate: Content: Only fetch Keywords once, to improve performance
* Fix: Generate: Content: Process Shortcodes after all keyword replacements have been completed
* Fix: Shortcode: Wikipedia: Better verbose error message when failing to fetch Wikipedia content
* Fix: Shortcode: Related Links: Only show publish and draft Content Groups in the dropdown
* Fix: Export: Export Keywords
* Fix: Export: Export Generate: Terms
* Fix: Export: Don't include auto-draft Content Groups

= 1.9.6 (2019-02-08) =
* Fix: Generate: Content: Replace keywords with column / term subsets defined

= 1.9.5 (2019-02-07) =
* Added: Generate: Content: Optimized performance for generation
* Added: Generate: Terms: Optimized performance for generation
* Added: Generate: Terms: Test, Generate and Delete actions from table view
* Added: Generate: Terms: Ensure actions behave in the same way as Generate: Content, with confirmation alerts
* Fix: Only load JS when required for performance
* Fix: Activation: Fix ‘Specified key was too long; max key length is 767 bytes’ error on Phone Area Code Table creation for MySQL 5.6 and lower 
* Fix: Generate: Content: Alignment of Deselect All button on Taxonomies
* Fix: Generate: Content: Undefined index: group_id Javascript errors
* Fix: Generate: Content: Don't show Trash and Delete Options in table if no content has been generated by the Group
* Fix: Generate: Terms: Don't require Parent Term field
* Fix: Elementor: Improve Generation Performance by not processing shortcodes in the Post Content, as Post Content is not used by Elementor.
* Fix: Elementor: Prevent duplicate processing of the same shortcodes for performance (prevents duplicate Unsplash image imports).
* Fix: Keywords: Generate Locations: Ensure multiple Regions, Counties and/or Cities are all honored as restrictions, not just the last entered Region / County / City

= 1.9.4 (2019-02-02) =
* Fix: Activation: Fix ‘Specified key was too long; max key length is 767 bytes’ error on Keyword Table creation for MySQL 5.6 and lower 

= 1.9.3 (2019-01-31) =
* Added: Developers: Docblock comments on all Plugin specific filters and actions.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/developers/
* Added: Generate: Content: Clear Elementor Cache once Generation has completed, to ensure compilation of CSS etc.
* Fix: Generate: Content: Elementor: Override Page Template filter resulting in non-Group Post/Page Templates not displaying
* Fix: Generate: Content: Page Builders: Process Page Generator Pro Shortcodes on Test / Generate for all Page Builders
* Fix: Generate: Content: Ensure Trash link allows deletion of Group
* Fix: Generate: Content: Author field search failing
* Fix: Licensing and Updates: Improved mechanism for WP-CLI support
* Fix: Minified all CSS and JS for performance

= 1.9.2 (2019-01-24) =
* Added: Shortcodes: Unsplash: Alt Tag option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/
* Fix: Activation: Don't specify ENGINE on CREATE TABLE syntax
* Fix: Multisite: Network Activation: Ensure database tables are automatically created on all existing sites
* Fix: Multisite: Network Activation: Ensure database tables are automatically created on new sites created after Network Activation of Plugin
* Fix: Multisite: Site Activation: Ensure database tables are created
* Fix: Keywords: Allow Keywords to be sorted ascending and descending when clicking Keywords column in table

= 1.9.1 (2019-01-17) =
* Added: Generate: Content: Option to Trash or Delete Generated Content
* Added: Success and Error Notices can be dismissed
* Fix: Keywords: Avoid HTTP API 200 error when creating a keyword with no Keyword or Terms specified
* Fix: Keywords: Validate that column names exist when a delimiter exists
* Fix: Keywords: Validate that terms contain the matching delimiter when a delimiter exists
* Fix: Keywords: Validate that the number of column names specified matches the number of deliniated items in a term
* Fix: Keywords: Ensure that Sorting Keywords in the table doesn't re-trigger a duplicate or delete event
* Fix: Keywords: Generate Phone Area Codes: Populate Delimiter and Column fields
* Fix: Generate: Content: PHP warnings when duplicating Content Group

= 1.9.0 (2019-01-10) =
* Added: Generate: Content: Added Internal Description Field
* Fix: Generate: Content: Force priority of Actions in Sidebar to display top and bottom of meta boxes list
* Fix: Keywords: Import CSV: PHP warning on using continue instead of break

= 1.8.9 (2019-01-03) =
* Fix: Keywords: Generate Locations: Allow multiple Counties and Cities of the same name, in different areas, to display in search results for selection
* Fix: UI Enhancements for mobile compatibility

= 1.8.8 (2018-12-28) =
* Fix: ACF and Divi compatibility

= 1.8.7 (2018-12-27) =
* Added: Generate: Content: Salient Page Meta Box Support
* Fix: Generate: Content: Action Buttons CSS to ensure buttons aren't cut off
* Fix: Generate: Content: Enfold / Avia Builder: Ensure Plugin Shortcodes are rendered and stored in post meta
* Fix: Generate: Content: Table: Ensure that Test Generation generates content from selected Group ID
* Fix: Generate: Content: Table: Ensure that Delete Generated Content deletes content from selected Group ID

= 1.8.6 (2018-12-21) =
* Fix: Keywords: Generate Locations: Modal not dismissing on completion
* Fix: Related Links Shortcode: Force Group ID if not specified to ensure results display
* Fix: Related Links Shortcode: Force Post Type if not specified to ensure results display

= 1.8.5 (2018-12-20) =
* Fix: Removed all select2 references, as select2 is no longer used 

= 1.8.4 (2018-12-13) =
* Added: Generate Content: Test, Generate and Delete Generated Content Actions in Sidebar for Gutenberg Editor
* Fix: Generate Content: Gutenberg: Save all Settings 
* Fix: Keywords: Generate Locations: Prefetch Restrict by Counties and Regions for the selected Country, so the user can search and/or select from the dropdown list
* Fix: Keywords: Generate Locations: Some missing data for Restrict by Counties and Regions
* Fix: Keywords: Generate Locations: Use Restrict by Counties and Regions when searching for Restrict by City
* Fix: Keywords: Generate Locations: Report errors on screen if searching Restrictions fails
* Fix: Shortcodes: Google Maps: Remove sensor=false parameter, as it's no longer needed

= 1.8.3 (2018-11-29) =
* Added: Keywords: Generate Locations: Ability to fetch large datasets of ZIP Codes, Cities etc asynchronously. See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Settings: Generate Locations: Option to specify default Radius, in miles.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-locations-settings/
* Fix: Activation: Fatal error on unlicensed new installations
* Fix: Keywords: Validate the columns field, ensuring no spaces are used
* Fix: Generate: Content: Correctly replace keywords when using PHP versions older than 5.5.x (please upgrade to PHP 7 - PHP 5.x is end of life January 1st 2019: http://php.net/supported-versions.php)
* Fix: Generate: Content: Author field now uses selectize asynchronous search for better performance on sites with a large number of WordPress Users
* Fix: Generate: Terms: Correctly replace keywords when using PHP versions older than 5.5.x (please upgrade to PHP 7 - PHP 5.x is end of life January 1st 2019: http://php.net/supported-versions.php)
* Removed: Keywords: Generate Nearby Cities.  Replaced by Generate Locations.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/

= 1.8.2 (2018-11-22) =
* Added: Generate Terms: Add option to specify parent Term on hierarchical (e.g. Category) based Taxonomies
* Fix: Keywords: Generate Locations: Restrict by City / County / Region results populate when searching
* Fix: Keywords: Generate Locations: Improved performance / response time for searching Restrictions
* Fix: Generate Content: Hide Actions Meta Box compatible when using Gutenberg 4.4+
* Fix: Generate Content: Hide Attributes Meta Box if no Attributes apply to the generated Post Type
* Fix: Generate Terms: keyword_error when using Keywords, resulting in no generated Terms

= 1.8.1 (2018-11-15) =
* Added: Shortcode: Unsplash: Image Orientation option
* Fix: Shortcode: Unsplash: Image could not always be fetched
* Fix: Shortcode: Media Library Image: Image could not always be fetched
* Fix: Keywords: Term Indicies (e.g. {city:2}) were not working

= 1.8.0 (2018-11-08) =
* Added: Settings: Generate Locations Tab: Define default choices for Area and Country.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-locations-settings/
* Added: Generate Content: Gutenberg Compatibility
* Added: Generate Content: Test option for each Content Group in the list of Content Groups.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/
* Added: Generate Content: Delete Generated Content option for each Content Group in the list of Content Groups.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/
* Added: Generate Content: Confirmation Dialogs for actions in the list of Content Groups.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/
* Added: Generate Content: Apply Synonyms to Content automatically.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/
* Added: Generate Content: Featured Image: Option to choose Media Library Image at random, with optional filters for Title, Caption, Alt, Description and ID constraints.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/
* Fix: Generate Content: Confirmation Dialogs localized for translation
* Fix: Keywords: Typo on example usage of Keyword Term Subsets

= 1.7.9 (2018-11-01) =
* Added: Generate Content: Visual Editor: Automatically Generate Spintax from Selected Text.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-using-spintax/
* Added: Shortcode: Unsplash: Add image size option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/
* Added: Shortcode: Media Library Image.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-shortcodes/
* Fix: Exclude Content Groups from Yoast SEO Sitemaps, regardless of Yoast settings
* Fix: Generate Content: Don't strip Keyword Term Subset brackets in Permalink field
* Fix: Shortcode: Wikipedia: Better content detection, ignoring empty paragraphs

= 1.7.8 (2018-10-25) =
* Added: Keywords: Delimiter and Column options, to allow Term Subset data to be accessed (such as the City Name from a full location).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords/
* Added: Keywords: Generate Nearby Cities: Renamed to Generate Locations.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Replaced Geonames and Google Geocoding APIs with Georocket.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Restrict Results by Radius or Area (City, County or Region).  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Generate Locations: Maximum Radius restriction removed.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-generate-locations/
* Added: Keywords: Use native wpdb class insert(), update() and delete() functions when creating, updating and deleting Keywords
* Added: Generate Content: Unsplash Featured Image Option
* Added: Generate Content: Unsplash Shortcode
* Fix: Generate Content: Don't process shortcodes when saving a Group (improves load times and performance)

= 1.7.7 (2018-09-13) =
* Fix: Generate: Content: Initialize array in a PHP 5+ compatible manner
* Fix: WP-CLI: Honor resume_index option
* Fix: Google Maps: Ensure custom height is honored and not overridden by CSS
* Removed: 500px support (500px no longer grant access to their API to fetch photos. Please note this is outside of our control: https://support.500px.com/hc/en-us/articles/360002435653-API- )

= 1.7.6 (2018-08-30) =
* Added: Generate: Content: Option to force specific Keyword Term when using a Keyword, using e.g. {city:2} to always output the second Term.
* Added: Generate: Term: Option to force specific Keyword Term when using a Keyword, using e.g. {city:2} to always output the second Term.
* Added: WP-CLI: Delete Generated Content (see Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/)
* Added: WP-CLI: Delete Generated Terms (see Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/)

= 1.7.5 (2018-08-23) =
* Fix: Generate: Content: Improved error message in Test and Generate mode when the total number of possible keyword term combinations exceeds PHP's floating point limit.
* Fix: Generate: Terms: Improved error message in Test and Generate mode when the total number of possible keyword term combinations exceeds PHP's floating point limit.

= 1.7.4 (2018-08-18) =
* Fix: Generate: Content: Scheduled functionality missing on some upgrades from 1.7.2 to 1.7.3

= 1.7.3 (2018-08-16) =
* Added: Keywords: Import CSV option.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/keywords-import-csv/

= 1.7.2 (2018-08-09) =
* Fix: Generate: Content: Ignore _wp_page_template if supplied in Post Meta; this ensures the Content Group's Page Template is always honored.

= 1.7.1 (2018-07-26) =
* Added: Keywords: Automatically generate Terms based on Keyword if no Terms are supplied
* Added: Generate: Content: Confirmation dialog when deleting Generated Content
* Added: Generate: Content: Honor Number of Posts settings for Random generating (noting a value must be specified, otherwise 10 Posts generated.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/)

= 1.7.0 (2018-07-19) =
* Fix: Yelp: Serve logo and link over HTTPS
* Fix: Elementor: Spin and replace keywords (note: keyword tags MUST be complete, and NOT broken up by HTML.  See Docs: https://www.wpzinc.com/documentation/page-generator-pro/generate-content/)

= 1.6.9 (2018-07-12) =
* Fix: Elementor: Using existing Templates will be honored in generated content

= 1.6.8 (2018-06-28) =
* Added: Generate: Content: Support for Live Composer
* Fix: Improved licensing mechanism

= 1.6.7 (2018-06-08) =
* Added: Generate: Content: Yoast SEO: Prevent Yoast SEO stripping curly braces from Canonical URL
* Fix: Yelp: Use correct data when reporting errors from Yelp
* Fix: Activation: Better method of deactivating free version of the plugin if it's still active

= 1.6.6 (2018-05-10) =
* Fix: Licensing: Improved performance
* Fix: Activation: Deactivate free version of the plugin if it's still active
* Fix: Generate: Wikipedia Shortcode: Better importing of Wikipedia content

= 1.6.5 (2018-04-26) =
* Added: Generate Content: Support for using Taxonomies as Keywords (e.g. {taxonomy_category})

= 1.6.4 (2018-04-12) =
* Fix: Generate Content: Divi Settings: Ensure that correct Divi Settings can be customised for Posts and Pages
* Fix: Generate Content: Elementor: Display Page Template in frontend preview

= 1.6.3 (2018-04-02) =
* Added: Generate: Test: Honor Resume Index Setting, so a specific starting index can be tested
* Added: Generate: Output: Display Keywords + Term Replacements used in each Page, Post + Term Generation (both wp-admin and wp-cli)

= 1.6.2 (2018-03-22) =
* Added: show_in_rest = false for Content Groups, until we're happy that the Gutenberg editor is stable in WordPress 
* Fix: Shortcodes: 500px: Don't attempt to choose an image index outside of the resultset
* Fix: Shortcodes: YouTube: Don't attempt to choose a video index outside of the resultset
* Fix: Call wp_enqueue_media() on Plugin screens, because Plugins which register Meta Boxes and Yoast SEO wrongly assume that there is always a Visual Editor and Featured Image on a Post Type
* Fix: Generate Content: Permalink: Allow keyword transformations

= 1.6.1 (2018-03-13) =
* Added: Generate Terms
* Fix: Keywords: Prevent spaces in Keywords
* Fix: Generate: Prevent spaces in Permalink
* Fix: Code formatting

= 1.6.0 (2018-03-02) =
* Fix: Class 'Page_Generator_Pro_Geo' not found in includes/admin/install.php on line 58

= 1.5.9 (2018-03-01) =
* Added: Generate Nearby Cities / ZIP Codes: Output format can be any one or more of City, County and/or Zip Code, in any order
* Added: Generate: Generation: Overwrite: Added option to overwrite existing Pages, preserving their existing Published date
* Added: Generate Phone Area Codes
* Added: Shortcode: Filters to all shortcode outputs
* Added: Shortcode: Related Links
* Fix: Generate: 500px: Errors importing 500px images into Media Library

= 1.5.8 (2018-02-01) =
* Added: Generate: Support for X and Pro Themes by ThemeCo
* Fix: Generate: Attributes: Only display Template option if the Post Type has registered templates available
* Fix: Generate: Prevent Preview / View of Group on frontend, which results in errors (use 'Test' method instead)

= 1.5.7 (2018-01-18) =
* Fix: Generate: Use date_i18n() instead of date() to ensure that published Posts honor WordPress' locale

= 1.5.6 (2018-01-10) =
* Added: Generate: Support for Avia Layout Builder (Enfold Theme)

= 1.5.5 (2017-12-14) =
* Added: Generate: WPBakery Visual Composer Backend Editor Support

= 1.5.4 (2017-11-22) =
* Fix: 404 errors on generated Pages when Page Parent was previous set and then removed

= 1.5.3 (2017-11-09) =
* Added: Generate: Support for Page Slug and Keyword in Attributes > Parent
* Added: Generate: Native support for AIOSEO Pack, Yoast SEO and Yoast SEO Premium (see Documentation: https://www.wpzinc.com/documentation/page-generator-pro/generate-seo-integration/)
* Added: Generate: WP-CLI Arguments (see Documentation: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/)
* Added: Generate: WP-CLI: Support for multiple Group IDs (see Documentation: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/)
* Added: Generate: WP-CLI: page-generator-pro-groups-list command (see Documentation: https://www.wpzinc.com/documentation/page-generator-pro/generate-wp-cli/)

= 1.5.2 (2017-10-02) =
* Added: Settings: GeoNames Username option (see Documentation: https://www.wpzinc.com/documentation/page-generator-pro/geonames-settings/)
* Added: Post Type Template Support (WordPress 4.7+)
* Added: Generate: Support for large keyword term combinations in All mode (e.g. 100 million+ pages). Requires PHP 5.5+

= 1.5.1 (2017-09-25) =
* Added: Improved UI
* Added: Generate Nearby Cities / ZIP Codes: Ability to generate list of ZIP Codes, with formatting options (City, County, ZIP Code)
* Fix: Uncaught TypeError: Illegal constructor in admin-min.js for clipboard.js functionality

= 1.5.0 (2017-08-10) =
* Fix: Generate: Wikipedia: Detect mb_convert_encoding() function before attempting to parse Wikipedia HTML
* Fix: Google Maps: Use HTTPS and return more accurate latitude and longitude for Cities

= 1.4.9 (2017-07-10) =
* Added: Generate: Overwrite Existing Pages (generated by this Plugin)
* Added: Generate: Featured Image: Alt Tag (for Image URLs and 500px)
* Added: Generate: Custom Fields: Move option
* Fix: Generate: Parent: Added description explaining how to determine the Parent Page ID
* Fix: Keywords: Prevent slashes from displaying / added on double quotation marks

= 1.4.8 (2017-07-05) =
* Fix: Settings: Google: Click here links go to valid Documentation URL
* Fix: Generate: Wikipedia: Improved content building method to avoid blank results from Wikipedia in some cases

= 1.4.7 (2017-06-22) =
* Added: Settings: Google: Google Maps API Disable JS Library option, for installations where another Plugin or Theme might load Google Map's API library already

= 1.4.6 (2017-05-28) =
* Fix: Use utf8_encode on Title, Excerpt and Content if wp_insert_post() fails on generation / testing

= 1.4.5 (2017-05-26) =
* Added: Yelp API v3 Support (no need to define keys or tokens)

= 1.4.4 (2017-04-26) =
* Added: Generate: ACF Support
* Added: All Group Post Metadata has keyword replacement and spintax operations performed on them before being copied to the generated Page/Post/CPT.
* Fix: Improved Generate performance by not duplicating spintax process

= 1.4.3 (2017-04-20) =
* Fix: UTF-8 encoding on Wikipedia content to avoid corrupt character output

= 1.4.2 (2017-04-13) =
* Fix: Undefined property Page_Generator_Pro_PostType::$post_type
* Fix: Muffin Builder: Replace keywords in SEO fields

= 1.4.1 (2017-03-16) =
* Added: Generate: Divi Page and Post Layouts are now available in Page Generator Pro when using Divi > Load from Library

= 1.4.0 (2017-02-27) =
* Fix: Only display Review Helper for Super Admin and Admin

= 1.3.9 (2017-02-20) =
* Added: Review Helper to check if the user needs help
* Fix: Ensure first keyword within spintax at the very start of the content (or a Page Builder module) is replaced with a keyword
* Updated: Dashboard and Licensing Submodules

= 1.3.8 (2017-02-14) =
* Added: Generate: Spintax all fields, including Page Builders
* Added: Post Type: Use variable for Post Type Name for better abstraction
* Fix: Generate: Don't attempt to test for permitted meta boxes if none exist
* Fix: Generate: Check Custom Fields are set before running checks on them
* Fix: Use Plugin Name variable for better abstraction
* Fix: Improved Installation and Upgrade routines

= 1.3.7 (2017-02-09) =
* Added: Generate: Support for Beaver Builder
* Added: Generate: Support for Visual Composer
* Added: Page Builders: Moved integration code and associated functions to frontend facing class for better compatibility
* Fix: Yelp: Fallback to cURL with User-Agent string, if wp_remote_get() fails

= 1.3.6 (2017-01-30) =
* Fix: Changed branding from WP Cube to WP Zinc
* Fix: Updated licensing endpoint to reflect brand change

= 1.3.5 (2017-01-23) =
* Fix: Generate: Parent Page is now an ID field, to prevent memory errors when trying to use wp_dropdown_pages() to list 3,000+ Pages
* Fix: Generate: Improve performance when fetching Number of Generated Pages for a given group, to prevent memory errors

= 1.3.4 (2016-12-30) =
* Fix: Generate: Page = Draft when using Test mode
* Fix: Generate: Copy Divi Post Meta to generated Page(s) to honor Divi settings

= 1.3.3 (2016-12-14) =
* Fix: Generate: Attributes > Parent displays the chosen / saved Parent Page
* Fix: Generate: Spintax: More accurate process for returning correct inline CSS, JSON or general text in curly braces when running spintax routine, rather than stripping it entirely
* Fix: Generate: Prevent "Do you want to leave this site" message when using Action buttons at the bottom of the screen

= 1.3.2 (2016-12-09) =
* Fix: Generate: Handle Google latitude/longitude lookup errors better, instead of returning a 500 server error
* Fix: Generate: Spintax: Return inline CSS or general text in curly braces when running spintax routine, rather than stripping it entirely

= 1.3.1 (2016-12-05) =
* Added: Generate: Support for BeTheme
* Added: Generate: Support for Muffin Page Builder
* Added: Generate: Google Maps: Zoom Option
* Fix: Generate > Nearby Cities: Country dropdown option preserved on form submit error
* Fix: Generate: Improved search/replace method for Custom Fields

= 1.3.0 (2016-11-15) =
* Fix: When upgrading from < 1.2.1 to 1.2.3+, don't try to create a Groups table - just migrate the single Group settings into the new Groups CPT.
* Fix: Only set a Post Name (slug) if one is defined in the Group settings.

= 1.2.9 (2016-11-14) =
* Fix: Undefined variable $notices error on groups.php

= 1.2.8 (2016-11-03) =
* Added: Generate: Support for Avada Theme
* Added: Generate: Support for Fusion Builder

= 1.2.7 (2016-10-24) =
* Added: Generate: Support for Divi 3.0+ Theme
* Added: Generate: Support for Divi Builder Plugin

= 1.2.6 (2016-10-07) =
* Added: Generate: Option to stop Generation part way through the process.
* Added: Generate: Generation will now stop if a server side error is encountered when generating a Page.

= 1.2.5 (2016-10-01) =
* Fix: Keywords: Generate Nearby Cities: Use cURL instead of wp_remote_get() so that the User-Agent header is set correctly (wp_remote_get() would be better, however it results in a 403 Error from the API)
* Fix: Generate: Generating Pages with no Parent would result in Pages not truly Publishing until Updated.
* Fix: CLI: Call to undefined method Page_Generator_Pro_Groups::get_by_id()

= 1.2.4 (2016-09-27) =
* Added: Generate: Hierarchical Taxonomies can have new Taxonomy Term(s) specified, instead of just choosing existing Taxonomy Term(s).
* Fix: Generate: Google Maps, Wikipedia, Yelp, 500px and YouTube buttons reinstated to Groups content editor.
* Fix: Generate: Don't throw a 500 error when an undefined {keyword} is used in a Group.
* Fix: Import/Export: Added support to import JSON configurations generated in 1.2.2 and older.

= 1.2.3 (2016-09-22) =
* Added: Generate: Support for SiteOrigin Page Builder
* Added: Generate: Delete Generated Pages / Posts / CPTs (only for content generated since version 1.2.3)
* Added: Generate: Custom Fields: Meta values use textarea to support multiline text, formatting and HTML / JS markup
* Added: Generate: Duplicate Generation Set
* Fix: Generate: Honor 'Allow Comments' setting
* Fix: Generate: Honor 'Allow trackbacks and pingbacks' setting
* Fix: Generate: Allow Author selection when 'Rotate' is not enabled

= 1.2.2 (2016-07-12) =
* Added: Enable database debugging output if WP_DEBUG enabled
* Fix: Fatal error on installation for Page_Generator_Pro_Groups

= 1.2.1 (2016-07-06) =
* Added: Create, edit, run, delete, import and export multiple generation sets.
* Added: Shortcode: Wikipedia: Support for multiple languages
* Added: Shortcode: Google Maps API Key option (for users who exceed API limits, you can now specify your own Google Maps API key)
* Added: Shortcode: YouTube API Key option (for users who exceed API limits, you can now specify your own Youtube Data API key)
* Added: Generate: Show Page Parent option if Custom Post Type supports parent items
* Added: Generate: Reset Button to deselect taxonomy term(s)
* Added: Generate: Search field on taxonomies
* Added: Generate: Save / Test / Generate options at top and bottom of screen
* Added: Spintax support on custom / meta field values
* Fix: Generate: Improved TinyMCE / Visual Editor shortcode options for Google Maps, Wikipedia, Yelp, 500px and YouTube

= 1.2.0 (2016-06-24) =
* Added: Shortcode: YouTube Video
* Fix: Keyword search / replace on Page Generation is now case insensitive (e.g. {city} and {City} will both be replaced with a term)
* Fix: Out of memory errors when using case variations of a keyword (e.g. {city} and {City})
* Fix: Keyword replacements now fully work in Custom Fields and Taxonomy Terms

= 1.1.9 (2016-06-20) =
* Fix: Use same fallback method on map shortcode as Keywords > Generate Nearby Cities, to ensure lat/lng is always returned where possible

= 1.1.8 (2016-06-16) =
* Added: Keywords: Uppercase flag e.g. {keyword:uppercase_all}
* Added: Keywords: Lowercase flag e.g. {keyword:lowercase_all}
* Added: Keywords: Capitalise first letter flag e.g. {keyword:uppercase_first_character}
* Added: Keywords: Capitalise first letter of each word flag e.g. {keyword:uppercase_first_character_words}
* Added: Keywords: Capitalise first letter of each word flag e.g. {keyword:url}
* Added: Featured Image: 500px option
* Added: Shortcode: 500px Image
* Fix: Generate Nearby Cities: OVER_QUERY_LIMIT will now automatically trigger using OpenStreetMap to fetch latitude/longitude as a fallback

= 1.1.7 (2016-06-09) =
* Added: Spintax support on Tags
* Added: Featured Image option
* Added: Generate Nearby Cities: Include original city in results option
* Added: Generate Nearby Cities: Country is now a dropdown field to avoid ambiguity in guessing a country's code
* Fix: Generate Nearby Cities: Don't allow a radius of greater than 100 miles to be specified, as the API will not support this
* Fix: Generate Nearby Cities: More meaningful error messages are returned when something goes wrong
* Fix: Increased size of keyword terms database field from TEXT to MEDIUMTEXT, to support larger keyword imports (~ 16 million characters / 16MB ) 

= 1.1.6 =
* Added: Keywords can be included in spins
* Added: Option to choose specific publish / scheduled date
* Added: Option to choose random publish date with min/max date parameters
* Added: Contextual help to Generate screen
* Fix: Only parse Page Generator Pro shortcodes. Provides compatibility with page builders and other plugins / themes that use shortcodes for content
* Fix: Keep spinning content, even when the final spin has been reached and there are more pages to generate
* Fix: Spins would fail if certain characters existed
* Fix: Licensing mechanism works correctly with W3 Total Cache and memcache

= 1.1.5 =
* Added: Page Generation Methods (All, Sequential and Random)
* Fix: Replace spaces in slug with hyphens

= 1.1.4 =
* Fix: Don't display a division by zero error when keyword does not exist.
* Fix: Changed Yelp oAuth class names to avoid conflicts with other plugins.

= 1.1.3 =
* Added: Singleton Instances for better performance
* Fix: Use do_shortcode() instead of apply_filters( 'the_content' ) so we only parse necessary shortcodes in the content

= 1.1.2 =
* Fix: License check takes place outside of admin if required
* Fix: Activation on new multisite activation

= 1.1.1 =
* Fix: Activation routines for installation
* Fix: Yelp button not displaying on Visual Editor

= 1.1.0 =
* Added: Plugin structure changes and code optimisation for better performance
* Added: Google Maps Shortcode: Zoom attribute
* Added: Wikipedia Shortcode: Number of sections attribute
* Added: Generate: Removed 999 Limit when generating Pages
* Added: Generate: Page Parent Option
* Added: Generate: Schedule Option

= 1.0.9 =
* Fix: Faster Page Generation routine
* Fix: Warnings when not rotating authors 

= 1.0.8 =
* Fix: Fatal error when an error occurs during keyword saving.

= 1.0.7 =
* Added: Generate: Custom Fields (Meta Key/Value Pairs)
* Added: Generate: Progress Bar + Log with AJAX / JS support to prevent timeouts and support larger (~ 1000+) page generations
* Added: Minified JS and CSS
* Fix: Yelp OAuth errors

= 1.0.6 =
* Fix: Use $wpdb->prepare() in place of mysql_real_escape_string()
* Fix: Multisite Activation

= 1.0.5 =
* Added: Support for HTML elements in keyword data

= 1.0.4 =
* Added: Import + Export Settings, allowing users to copy settings to other plugin installations
* Added: Support Panel

= 1.0.3 =
* Fix: Transients for license key validation

= 1.0.2 =
* Fix: Force license key check method to beat aggressive server caching
* Added: Support menu with debug information

= 1.0.1 =
* Added translation support and .pot file

= 1.0 =
* First release.

== Upgrade Notice ==
