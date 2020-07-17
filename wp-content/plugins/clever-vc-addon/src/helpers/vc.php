<?php
/**
 * Visual Composer helpers
 */

 add_filter('vc_iconpicker-type-cleverfont', 'vc_iconpicker_type_cleverfont');
 function vc_iconpicker_type_cleverfont( $icons )
 {
     $cleverfont_icons = array(
         array('cs-font clever-icon-twitter' => esc_html__('Twitter', 'cvca')),
         array('cs-font clever-icon-facebook' => esc_html__('Facebook', 'cvca')),
         array('cs-font clever-icon-googleplus' => esc_html__('Google plus', 'cvca')),
         array('cs-font clever-icon-instagram' => esc_html__('Instagram', 'cvca')),
         array('cs-font clever-icon-pinterest' => esc_html__('Pinterest', 'cvca')),
         array('cs-font clever-icon-skype' => esc_html__('Skype', 'cvca')),
         array('cs-font clever-icon-vimeo' => esc_html__('Vimeo', 'cvca')),
         array('cs-font clever-icon-youtube' => esc_html__('Youtube', 'cvca')),
         array('cs-font clever-icon-award-1' => esc_html__('Award 1', 'cvca')),
         array('cs-font clever-icon-building' => esc_html__('Building', 'cvca')),
         array('cs-font clever-icon-faucet' => esc_html__('Faucet', 'cvca')),
         array('cs-font clever-icon-flower' => esc_html__('Flower', 'cvca')),
         array('cs-font clever-icon-house' => esc_html__('House', 'cvca')),
         array('cs-font clever-icon-house-1' => esc_html__('House 1', 'cvca')),
         array('cs-font clever-icon-pines' => esc_html__('Pines', 'cvca')),
         array('cs-font clever-icon-plant' => esc_html__('Plant', 'cvca')),
         array('cs-font clever-icon-sprout' => esc_html__('Sprout', 'cvca')),
         array('cs-font clever-icon-sprout-1' => esc_html__('Sprout 1', 'cvca')),
         array('cs-font clever-icon-trees' => esc_html__('Trees', 'cvca')),
         array('cs-font clever-icon-arrow-left' => esc_html__('Arrow Left', 'cvca')),
         array('cs-font clever-icon-arrow-right' => esc_html__('Arrow Right', 'cvca')),
         array('cs-font clever-icon-arrow-left-1' => esc_html__('Arrow Left 1', 'cvca')),
         array('cs-font clever-icon-arrow-right-1' => esc_html__('Arrow Right 1', 'cvca')),
         array('cs-font clever-icon-arrow-left-2' => esc_html__('Arrow Left 2', 'cvca')),
         array('cs-font clever-icon-arrow-right-2' => esc_html__('Arrow Right 2', 'cvca')),
         array('cs-font clever-icon-arrow-left-3' => esc_html__('Arrow Left 3', 'cvca')),
         array('cs-font clever-icon-arrow-right-3' => esc_html__('Arrow Right 3', 'cvca')),
         array('cs-font clever-icon-close-1' => esc_html__('Close 1', 'cvca')),
         array('cs-font clever-icon-three-dots' => esc_html__('Three dots', 'cvca')),
         array('cs-font clever-icon-morevertical' => esc_html__('More Vertical', 'cvca')),
         array('cs-font clever-icon-list-1' => esc_html__('List 1', 'cvca')),
         array('cs-font clever-icon-list-2' => esc_html__('List 2', 'cvca')),
         array('cs-font clever-icon-grid-5' => esc_html__('Grid 5', 'cvca')),
         array('cs-font clever-icon-menu-4' => esc_html__('Menu 4', 'cvca')),
         array('cs-font clever-icon-menu-5' => esc_html__('Menu 5', 'cvca')),
         array('cs-font clever-icon-menu-6' => esc_html__('Menu 6', 'cvca')),
         array('cs-font clever-icon-heart-o' => esc_html__('Heart 0', 'cvca')),
         array('cs-font clever-icon-heart-1' => esc_html__('Heart 1', 'cvca')),
         array('cs-font clever-icon-heart-2' => esc_html__('Heart 2', 'cvca')),
         array('cs-font clever-icon-user-6' => esc_html__('User 6', 'cvca')),
         array('cs-font clever-icon-attachment' => esc_html__('Attachment', 'cvca')),
         array('cs-font clever-icon-bag' => esc_html__('Bag', 'cvca')),
         array('cs-font clever-icon-ball' => esc_html__('Ball', 'cvca')),
         array('cs-font clever-icon-battery' => esc_html__('Battery', 'cvca')),
         array('cs-font clever-icon-briefcase' => esc_html__('Briefcase', 'cvca')),
         array('cs-font clever-icon-car' => esc_html__('Car', 'cvca')),
         array('cs-font clever-icon-cpu-1' => esc_html__('CPU 1', 'cvca')),
         array('cs-font clever-icon-cpu-2' => esc_html__('CPU 2', 'cvca')),
         array('cs-font clever-icon-dress-woman' => esc_html__('Woman dress', 'cvca')),
         array('cs-font clever-icon-drill-tool' => esc_html__('Drill tool', 'cvca')),
         array('cs-font clever-icon-feeding-bottle' => esc_html__('Feeding bottle', 'cvca')),
         array('cs-font clever-icon-fruit' => esc_html__('Fruit', 'cvca')),
         array('cs-font clever-icon-furniture-2' => esc_html__('Furniture 2', 'cvca')),
         array('cs-font clever-icon-furniture-1' => esc_html__('Furniture 1', 'cvca')),
         array('cs-font clever-icon-shoes-woman-2' => esc_html__('Woman Shoe 2', 'cvca')),
         array('cs-font clever-icon-shoes-woman-1' => esc_html__('Woman Shoe 1', 'cvca')),
         array('cs-font clever-icon-horse' => esc_html__('Horse', 'cvca')),
         array('cs-font clever-icon-laptop' => esc_html__('Laptop', 'cvca')),
         array('cs-font clever-icon-lipstick' => esc_html__('Lipstick', 'cvca')),
         array('cs-font clever-icon-iron' => esc_html__('Iron', 'cvca')),
         array('cs-font clever-icon-perfume' => esc_html__('Iron', 'cvca')),
         array('cs-font clever-icon-baby-toy-2' => esc_html__('Baby Toy 2', 'cvca')),
         array('cs-font clever-icon-baby-toy-1' => esc_html__('Baby Toy 1', 'cvca')),
         array('cs-font clever-icon-paint-roller' => esc_html__('Paint roller', 'cvca')),
         array('cs-font clever-icon-shirt' => esc_html__('Shirt', 'cvca')),
         array('cs-font clever-icon-shoe-man-2' => esc_html__('Man Shoe 2', 'cvca')),
         array('cs-font clever-icon-small-diamond' => esc_html__('Diamond', 'cvca')),
         array('cs-font clever-icon-tivi' => esc_html__('TV Screen', 'cvca')),
         array('cs-font clever-icon-smartphone' => esc_html__('Smartphone', 'cvca')),
         array('cs-font clever-icon-lights' => esc_html__('Led buib', 'cvca')),
         array('cs-font clever-icon-microwave' => esc_html__('Microwave', 'cvca')),
         array('cs-font clever-icon-wardrobe' => esc_html__('Wardrobe', 'cvca')),
         array('cs-font clever-icon-washing-machine' => esc_html__('Washing Machine', 'cvca')),
         array('cs-font clever-icon-watch-1' => esc_html__('Watch 1', 'cvca')),
         array('cs-font clever-icon-watch-2' => esc_html__('Watch 2', 'cvca')),
         array('cs-font clever-icon-slider-3' => esc_html__('Slider 3', 'cvca')),
         array('cs-font clever-icon-slider-2' => esc_html__('Slider 2', 'cvca')),
         array('cs-font clever-icon-slider-1' => esc_html__('Slider 1', 'cvca')),
         array('cs-font clever-icon-cart-16' => esc_html__('Cart 16', 'cvca')),
         array('cs-font clever-icon-cart-15' => esc_html__('Cart 15', 'cvca')),
         array('cs-font clever-icon-cart-14' => esc_html__('Cart 14', 'cvca')),
         array('cs-font clever-icon-cart-13' => esc_html__('Cart 13', 'cvca')),
         array('cs-font clever-icon-cart-12' => esc_html__('Cart 12', 'cvca')),
         array('cs-font clever-icon-cart-11' => esc_html__('Cart 11', 'cvca')),
         array('cs-font clever-icon-cart-10' => esc_html__('Cart 10', 'cvca')),
         array('cs-font clever-icon-cart-9' => esc_html__('Cart 9', 'cvca')),
         array('cs-font clever-icon-cart-8' => esc_html__('Cart 8', 'cvca')),
         array('cs-font clever-icon-cart-7' => esc_html__('Cart 7', 'cvca')),
         array('cs-font clever-icon-online-purchase' => esc_html__('Purchase Cart', 'cvca')),
         array('cs-font clever-icon-online-shopping' => esc_html__('ID Card', 'cvca')),
         array('cs-font clever-icon-line-triangle2' => esc_html__('Line Triangle 2', 'cvca')),
         array('cs-font clever-icon-plane-1' => esc_html__('Airplane', 'cvca')),
         array('cs-font clever-icon-bag-black-fashion-model' => esc_html__('Black Fashion Bag', 'cvca')),
         array('cs-font clever-icon-funnel-o' => esc_html__('Filter Blank', 'cvca')),
         array('cs-font clever-icon-funnel' => esc_html__('Filter', 'cvca')),
         array('cs-font clever-icon-grid-1' => esc_html__('Grid 1', 'cvca')),
         array('cs-font clever-icon-contract' => esc_html__('Compress', 'cvca')),
         array('cs-font clever-icon-expand' => esc_html__('Expand', 'cvca')),
         array('cs-font clever-icon-quotes' => esc_html__('Quotes', 'cvca')),
         array('cs-font clever-icon-next-arrow-1' => esc_html__('Next Arrow 1', 'cvca')),
         array('cs-font clever-icon-prev-arrow-1' => esc_html__('Prev Arrow 1', 'cvca')),
         array('cs-font clever-icon-reload' => esc_html__('Reload', 'cvca')),
         array('cs-font clever-icon-truck' => esc_html__('Truck', 'cvca')),
         array('cs-font clever-icon-wallet' => esc_html__('Wallet', 'cvca')),
         array('cs-font clever-icon-electric-1' => esc_html__('Electric 1', 'cvca')),
         array('cs-font clever-icon-electric-2' => esc_html__('Electric 2', 'cvca')),
         array('cs-font clever-icon-lock' => esc_html__('Lock', 'cvca')),
         array('cs-font clever-icon-share-1' => esc_html__('Share 1', 'cvca')),
         array('cs-font clever-icon-share-2' => esc_html__('Share 2', 'cvca')),
         array('cs-font clever-icon-check-box' => esc_html__('Check box', 'cvca')),
         array('cs-font clever-icon-clock' => esc_html__('Clock', 'cvca')),
         array('cs-font clever-icon-clock-1' => esc_html__('Clock 1', 'cvca')),
         array('cs-font clever-icon-analytics-laptop' => esc_html__('Analytic Laptop', 'cvca')),
         array('cs-font clever-icon-code-design' => esc_html__('Code', 'cvca')),
         array('cs-font clever-icon-competitive-chart' => esc_html__('Competitive Chart', 'cvca')),
         array('cs-font clever-icon-computer-monitor-and-cellphone' => esc_html__('Computer and Cellphone', 'cvca')),
         array('cs-font clever-icon-consulting-message' => esc_html__('Consulting Message', 'cvca')),
         array('cs-font clever-icon-creative-process' => esc_html__('Creative Light Buib', 'cvca')),
         array('cs-font clever-icon-customer-reviews' => esc_html__('Customer reviews', 'cvca')),
         array('cs-font clever-icon-data-visualization' => esc_html__('Compass', 'cvca')),
         array('cs-font clever-icon-document-storage' => esc_html__('Folder 1', 'cvca')),
         array('cs-font clever-icon-download-arrow' => esc_html__('Download', 'cvca')),
         array('cs-font clever-icon-download-cloud' => esc_html__('Cloud Download', 'cvca')),
         array('cs-font clever-icon-email-envelope' => esc_html__('Envelope', 'cvca')),
         array('cs-font clever-icon-file-sharing' => esc_html__('File Blank', 'cvca')),
         array('cs-font clever-icon-finger-touch-screen' => esc_html__('Touch', 'cvca')),
         array('cs-font clever-icon-horizontal-tablet-with-pencil' => esc_html__('Tablet with Pencil', 'cvca')),
         array('cs-font clever-icon-illustration-tool' => esc_html__('Illustration Tools', 'cvca')),
         array('cs-font clever-icon-keyboard-and-hands' => esc_html__('Keyboard and Hands', 'cvca')),
         array('cs-font clever-icon-landscape-image' => esc_html__('Lanscape Image', 'cvca')),
         array('cs-font clever-icon-layout-squares' => esc_html__('Layout Square', 'cvca')),
         array('cs-font clever-icon-mobile-app-developing' => esc_html__('Mobile with Gears', 'cvca')),
         array('cs-font clever-icon-online-video' => esc_html__('Video with line', 'cvca')),
         array('cs-font clever-icon-optimization-clock' => esc_html__('Performance Clock', 'cvca')),
         array('cs-font clever-icon-optimization-clock' => esc_html__('Performance Clock', 'cvca')),
         array('cs-font clever-icon-padlock-key' => esc_html__('Padlock', 'cvca')),
         array('cs-font clever-icon-pc-monitor' => esc_html__('PC Monitor', 'cvca')),
         array('cs-font clever-icon-place-localizer' => esc_html__('Map Icon Blank', 'cvca')),
         array('cs-font clever-icon-search-results' => esc_html__('Search Results', 'cvca')),
         array('cs-font clever-icon-search-tool' => esc_html__('Search Blank', 'cvca')),
         array('cs-font clever-icon-settings-tools' => esc_html__('Toolset', 'cvca')),
         array('cs-font clever-icon-sharing-symbol' => esc_html__('Sharing Symbol', 'cvca')),
         array('cs-font clever-icon-site-map' => esc_html__('Sitemap', 'cvca')),
         array('cs-font clever-icon-smartphone-with-double-arrows' => esc_html__('Smartphone Scale', 'cvca')),
         array('cs-font clever-icon-tablet-with-double-arrow' => esc_html__('Tablet Scale', 'cvca')),
         array('cs-font clever-icon-thin-expand-arrows' => esc_html__('Expand Thin Arrows', 'cvca')),
         array('cs-font clever-icon-upload-information' => esc_html__('Cloud Upload', 'cvca')),
         array('cs-font clever-icon-upload-to-web' => esc_html__('Upload', 'cvca')),
         array('cs-font clever-icon-volume-off' => esc_html__('Volume Off', 'cvca')),
         array('cs-font clever-icon-volume-on' => esc_html__('Volume On', 'cvca')),
         array('cs-font clever-icon-web-development' => esc_html__('Config', 'cvca')),
         array('cs-font clever-icon-web-home' => esc_html__('Home', 'cvca')),
         array('cs-font clever-icon-web-link' => esc_html__('Link', 'cvca')),
         array('cs-font clever-icon-web-links' => esc_html__('Links', 'cvca')),
         array('cs-font clever-icon-website-protection' => esc_html__('Website Protection', 'cvca')),
         array('cs-font clever-icon-work-team' => esc_html__('Work Team', 'cvca')),
         array('cs-font clever-icon-zoom-in-symbol' => esc_html__('Zoom In Symbol', 'cvca')),
         array('cs-font clever-icon-zoom-out-button' => esc_html__('Zoom Out Button', 'cvca')),
         array('cs-font clever-icon-arrow-1' => esc_html__('Arrow 1', 'cvca')),
         array('cs-font clever-icon-arrow-bold' => esc_html__('Arrow Bold', 'cvca')),
         array('cs-font clever-icon-arrow-light' => esc_html__('Arrow Light', 'cvca')),
         array('cs-font clever-icon-arrow-regular' => esc_html__('Arrow Regular', 'cvca')),
         array('cs-font clever-icon-cart-1' => esc_html__('Cart 1', 'cvca')),
         array('cs-font clever-icon-cart-2' => esc_html__('Cart 2', 'cvca')),
         array('cs-font clever-icon-cart-3' => esc_html__('Cart 3', 'cvca')),
         array('cs-font clever-icon-cart-4' => esc_html__('Cart 4', 'cvca')),
         array('cs-font clever-icon-cart-5' => esc_html__('Cart 5', 'cvca')),
         array('cs-font clever-icon-cart-6' => esc_html__('Cart 6', 'cvca')),
         array('cs-font clever-icon-chart' => esc_html__('Chart', 'cvca')),
         array('cs-font clever-icon-close' => esc_html__('Close', 'cvca')),
         array('cs-font clever-icon-compare-1' => esc_html__('Compare 1', 'cvca')),
         array('cs-font clever-icon-compare-2' => esc_html__('Compare 2', 'cvca')),
         array('cs-font clever-icon-compare-3' => esc_html__('Compare 3', 'cvca')),
         array('cs-font clever-icon-compare-4' => esc_html__('Compare 4', 'cvca')),
         array('cs-font clever-icon-compare-5' => esc_html__('Compare 5', 'cvca')),
         array('cs-font clever-icon-compare-6' => esc_html__('Compare 6', 'cvca')),
         array('cs-font clever-icon-compare-7' => esc_html__('Compare 7', 'cvca')),
         array('cs-font clever-icon-down' => esc_html__('Down', 'cvca')),
         array('cs-font clever-icon-grid' => esc_html__('Grid', 'cvca')),
         array('cs-font clever-icon-hand' => esc_html__('Hand', 'cvca')),
         array('cs-font clever-icon-layout-1' => esc_html__('Layout 1', 'cvca')),
         array('cs-font clever-icon-layout' => esc_html__('Layout', 'cvca')),
         array('cs-font clever-icon-light' => esc_html__('Light', 'cvca')),
         array('cs-font clever-icon-line-triangle' => esc_html__('Line Triangle', 'cvca')),
         array('cs-font clever-icon-list' => esc_html__('List', 'cvca')),
         array('cs-font clever-icon-mail-1' => esc_html__('Mail 1', 'cvca')),
         array('cs-font clever-icon-mail-2' => esc_html__('Mail 2', 'cvca')),
         array('cs-font clever-icon-mail-3' => esc_html__('Mail 3', 'cvca')),
         array('cs-font clever-icon-mail-4' => esc_html__('Mail 4', 'cvca')),
         array('cs-font clever-icon-mail-5' => esc_html__('Mail 5', 'cvca')),
         array('cs-font clever-icon-map-1' => esc_html__('Map 1', 'cvca')),
         array('cs-font clever-icon-map-2' => esc_html__('Map 2', 'cvca')),
         array('cs-font clever-icon-map-3' => esc_html__('Map 3', 'cvca')),
         array('cs-font clever-icon-map-4' => esc_html__('Map 4', 'cvca')),
         array('cs-font clever-icon-map-5' => esc_html__('Map 5', 'cvca')),
         array('cs-font clever-icon-menu-1' => esc_html__('Menu 1', 'cvca')),
         array('cs-font clever-icon-menu-2' => esc_html__('Menu 2', 'cvca')),
         array('cs-font clever-icon-grid-3' => esc_html__('Grid 3', 'cvca')),
         array('cs-font clever-icon-grid-4' => esc_html__('Grid 4', 'cvca')),
         array('cs-font clever-icon-menu-3' => esc_html__('Menu 3', 'cvca')),
         array('cs-font clever-icon-grid-2' => esc_html__('Grid 2', 'cvca')),
         array('cs-font clever-icon-minus' => esc_html__('Minus', 'cvca')),
         array('cs-font clever-icon-next' => esc_html__('Next', 'cvca')),
         array('cs-font clever-icon-phone-1' => esc_html__('Phone 1', 'cvca')),
         array('cs-font clever-icon-phone-2' => esc_html__('Phone 2', 'cvca')),
         array('cs-font clever-icon-phone-3' => esc_html__('Phone 3', 'cvca')),
         array('cs-font clever-icon-phone-4' => esc_html__('Phone 4', 'cvca')),
         array('cs-font clever-icon-phone-5' => esc_html__('Phone 5', 'cvca')),
         array('cs-font clever-icon-phone-6' => esc_html__('Phone 6', 'cvca')),
         array('cs-font clever-icon-picture' => esc_html__('Picture', 'cvca')),
         array('cs-font clever-icon-pin' => esc_html__('Pin', 'cvca')),
         array('cs-font clever-icon-plus' => esc_html__('Plus', 'cvca')),
         array('cs-font clever-icon-prev' => esc_html__('Prev', 'cvca')),
         array('cs-font clever-icon-quickview-1' => esc_html__('Quickview 1', 'cvca')),
         array('cs-font clever-icon-quickview-2' => esc_html__('Quickview 2', 'cvca')),
         array('cs-font clever-icon-quickview-3' => esc_html__('Quickview 3', 'cvca')),
         array('cs-font clever-icon-quickview-4' => esc_html__('Quickview 4', 'cvca')),
         array('cs-font clever-icon-quickview-5' => esc_html__('Quickview 5', 'cvca')),
         array('cs-font clever-icon-refresh' => esc_html__('Refresh', 'cvca')),
         array('cs-font clever-icon-rounded-triangle' => esc_html__('Rounded Triangle', 'cvca')),
         array('cs-font clever-icon-search-1' => esc_html__('Search 1', 'cvca')),
         array('cs-font clever-icon-search-2' => esc_html__('Search 2', 'cvca')),
         array('cs-font clever-icon-search-3' => esc_html__('Search 3', 'cvca')),
         array('cs-font clever-icon-search-4' => esc_html__('Search 4', 'cvca')),
         array('cs-font clever-icon-search-5' => esc_html__('Search 5', 'cvca')),
         array('cs-font clever-icon-support' => esc_html__('Support', 'cvca')),
         array('cs-font clever-icon-tablet' => esc_html__('Tablet', 'cvca')),
         array('cs-font clever-icon-triangle' => esc_html__('Triangle', 'cvca')),
         array('cs-font clever-icon-up' => esc_html__('Up', 'cvca')),
         array('cs-font clever-icon-user-1' => esc_html__('User 1', 'cvca')),
         array('cs-font clever-icon-user-2' => esc_html__('User 2', 'cvca')),
         array('cs-font clever-icon-user-3' => esc_html__('User 3', 'cvca')),
         array('cs-font clever-icon-user-4' => esc_html__('User 4', 'cvca')),
         array('cs-font clever-icon-user-5' => esc_html__('User 5', 'cvca')),
         array('cs-font clever-icon-user' => esc_html__('User', 'cvca')),
         array('cs-font clever-icon-vector' => esc_html__('Vector', 'cvca')),
         array('cs-font clever-icon-wishlist' => esc_html__('Wishlist', 'cvca'))
     );

     return array_merge( $icons, $cleverfont_icons );
 }

 add_filter('vc_iconpicker-type-strokegap', 'vc_iconpicker_type_stroke_gap_icons');
 function vc_iconpicker_type_stroke_gap_icons( $icons )
 {
     $strokegap_icons = array(
         array('icon icon-WorldWide'         => esc_html__('World Wide', 'cvca')),
         array('icon icon-WorldGlobe'        => esc_html__('World Globe', 'cvca')),
         array('icon icon-Underpants'        => esc_html__('Under pants', 'cvca')),
         array('icon icon-Tshirt'            => esc_html__('Tshirt', 'cvca')),
         array('icon icon-Trousers'          => esc_html__('Trousers', 'cvca')),
         array('icon icon-Tie'               => esc_html__('Tie', 'cvca')),
         array('icon icon-TennisBall'        => esc_html__('Tennis Ball', 'cvca')),
         array('icon icon-Telesocpe'         => esc_html__('Telesocpe', 'cvca')),
         array('icon icon-Stop'              => esc_html__('Stop', 'cvca')),
         array('icon icon-Starship'          => esc_html__('Starship', 'cvca')),
         array('icon icon-Starship2'         => esc_html__('Starship 2', 'cvca')),
         array('icon icon-Speaker'           => esc_html__('Speaker', 'cvca')),
         array('icon icon-Speaker2'          => esc_html__('Speaker 2', 'cvca')),
         array('icon icon-Soccer'            => esc_html__('Soccer', 'cvca')),
         array('icon icon-Snikers'           => esc_html__('Snikers', 'cvca')),
         array('icon icon-Scisors'           => esc_html__('Scisors', 'cvca')),
         array('icon icon-Puzzle'            => esc_html__('Puzzle', 'cvca')),
         array('icon icon-Printer'           => esc_html__('Printer', 'cvca')),
         array('icon icon-Pool'              => esc_html__('Pool', 'cvca')),
         array('icon icon-Podium'            => esc_html__('Podium', 'cvca')),
         array('icon icon-Play'              => esc_html__('Play', 'cvca')),
         array('icon icon-Planet'            => esc_html__('Planet', 'cvca')),
         array('icon icon-Pause'             => esc_html__('Pause', 'cvca')),
         array('icon icon-Next'              => esc_html__('Next', 'cvca')),
         array('icon icon-MusicNote'         => esc_html__('MusicNote', 'cvca')),
         array('icon icon-MusicNote2'        => esc_html__('MusicNote 2', 'cvca')),
         array('icon icon-MusicMixer'        => esc_html__('MusicMixer', 'cvca')),
         array('icon icon-Microphone'        => esc_html__('Microphone', 'cvca')),
         array('icon icon-Medal'             => esc_html__('Medal', 'cvca')),
         array('icon icon-ManFigure'         => esc_html__('ManFigure', 'cvca')),
         array('icon icon-Magnet'            => esc_html__('Magnet', 'cvca')),
         array('icon icon-Like'              => esc_html__('Like', 'cvca')),
         array('icon icon-Hanger'            => esc_html__('Hanger', 'cvca')),
         array('icon icon-Handicap'          => esc_html__('Handicap', 'cvca')),
         array('icon icon-Forward'           => esc_html__('Forward', 'cvca')),
         array('icon icon-Footbal'           => esc_html__('Footbal', 'cvca')),
         array('icon icon-Flag'              => esc_html__('Flag', 'cvca')),
         array('icon icon-FemaleFigure'      => esc_html__('FemaleFigure', 'cvca')),
         array('icon icon-Dislike'           => esc_html__('Dislike', 'cvca')),
         array('icon icon-DiamondRing'       => esc_html__('DiamondRing', 'cvca')),
         array('icon icon-Crown'             => esc_html__('Crown', 'cvca')),
         array('icon icon-Column'            => esc_html__('Column', 'cvca')),
         array('icon icon-Click'             => esc_html__('Click', 'cvca')),
         array('icon icon-Cassette'          => esc_html__('Cassette', 'cvca')),
         array('icon icon-Bomb'              => esc_html__('Bomb', 'cvca')),
         array('icon icon-BatteryLow'        => esc_html__('BatteryLow', 'cvca')),
         array('icon icon-BatteryFull'       => esc_html__('BatteryFull', 'cvca')),
         array('icon icon-Bascketball'       => esc_html__('Bascketball', 'cvca')),
         array('icon icon-Astronaut'         => esc_html__('Astronaut', 'cvca')),
         array('icon icon-WineGlass'         => esc_html__('WineGlass', 'cvca')),
         array('icon icon-Water'             => esc_html__('Water', 'cvca')),
         array('icon icon-Wallet'            => esc_html__('Wallet', 'cvca')),
         array('icon icon-Umbrella'          => esc_html__('Umbrella', 'cvca')),
         array('icon icon-TV'                => esc_html__('TV', 'cvca')),
         array('icon icon-TeaMug'            => esc_html__('TeaMug', 'cvca')),
         array('icon icon-Tablet'            => esc_html__('Tablet', 'cvca')),
         array('icon icon-Soda'              => esc_html__('Soda', 'cvca')),
         array('icon icon-SodaCan'           => esc_html__('SodaCan', 'cvca')),
         array('icon icon-SimCard'           => esc_html__('SimCard', 'cvca')),
         array('icon icon-Signal'            => esc_html__('Signal', 'cvca')),
         array('icon icon-Shaker'            => esc_html__('Shaker', 'cvca')),
         array('icon icon-Radio'             => esc_html__('Radio', 'cvca')),
         array('icon icon-Pizza'             => esc_html__('Pizza', 'cvca')),
         array('icon icon-Phone'             => esc_html__('Phone', 'cvca')),
         array('icon icon-Notebook'          => esc_html__('Notebook', 'cvca')),
         array('icon icon-Mug'               => esc_html__('Mug', 'cvca')),
         array('icon icon-Mastercard'        => esc_html__('Mastercard', 'cvca')),
         array('icon icon-Ipod'              => esc_html__('Ipod', 'cvca')),
         array('icon icon-Info'              => esc_html__('Info', 'cvca')),
         array('icon icon-Icecream1'         => esc_html__('Icecream 1', 'cvca')),
         array('icon icon-Icecream2'         => esc_html__('Icecream 2', 'cvca')),
         array('icon icon-Hourglass'         => esc_html__('Hourglass', 'cvca')),
         array('icon icon-Help'              => esc_html__('Help', 'cvca')),
         array('icon icon-Goto'              => esc_html__('Goto', 'cvca')),
         array('icon icon-Glasses'           => esc_html__('Glasses', 'cvca')),
         array('icon icon-Gameboy'           => esc_html__('Gameboy', 'cvca')),
         array('icon icon-ForkandKnife'      => esc_html__('ForkandKnife', 'cvca')),
         array('icon icon-Export'            => esc_html__('Export', 'cvca')),
         array('icon icon-Exit'              => esc_html__('Exit', 'cvca')),
         array('icon icon-Espresso'          => esc_html__('Espresso', 'cvca')),
         array('icon icon-Drop'              => esc_html__('Drop', 'cvca')),
         array('icon icon-Download'          => esc_html__('Download', 'cvca')),
         array('icon icon-Dollars'           => esc_html__('Dollars', 'cvca')),
         array('icon icon-Dollar'            => esc_html__('Dollar', 'cvca')),
         array('icon icon-DesktopMonitor'    => esc_html__('Desktop Monitor', 'cvca')),
         array('icon icon-Corkscrew'         => esc_html__('Corkscrew', 'cvca')),
         array('icon icon-CoffeeToGo'        => esc_html__('Coffee To Go', 'cvca')),
         array('icon icon-Chart'             => esc_html__('Chart', 'cvca')),
         array('icon icon-ChartUp'           => esc_html__('Chart Up', 'cvca')),
         array('icon icon-ChartDown'         => esc_html__('Chart Down', 'cvca')),
         array('icon icon-Calculator'        => esc_html__('Calculator', 'cvca')),
         array('icon icon-Bread'             => esc_html__('Bread', 'cvca')),
         array('icon icon-Bourbon'           => esc_html__('Bourbon', 'cvca')),
         array('icon icon-BottleofWIne'      => esc_html__('Bottle of Wine', 'cvca')),
         array('icon icon-Bag'               => esc_html__('Bag', 'cvca')),
         array('icon icon-Arrow'             => esc_html__('Arrow', 'cvca')),
         array('icon icon-Antenna1'          => esc_html__('Antenna1', 'cvca')),
         array('icon icon-Antenna2'          => esc_html__('Antenna2', 'cvca')),
         array('icon icon-Anchor'            => esc_html__('Anchor', 'cvca')),
         array('icon icon-Wheelbarrow'       => esc_html__('Wheelbarrow', 'cvca')),
         array('icon icon-Webcam'            => esc_html__('Webcam', 'cvca')),
         array('icon icon-Unlinked'          => esc_html__('Unlinked', 'cvca')),
         array('icon icon-Truck'             => esc_html__('Truck', 'cvca')),
         array('icon icon-Timer'             => esc_html__('Timer', 'cvca')),
         array('icon icon-Time'              => esc_html__('Time', 'cvca')),
         array('icon icon-StorageBox'        => esc_html__('Storage Box', 'cvca')),
         array('icon icon-Star'              => esc_html__('Star', 'cvca')),
         array('icon icon-ShoppingCart'      => esc_html__('Shopping Cart', 'cvca')),
         array('icon icon-Shield'            => esc_html__('Shield', 'cvca')),
         array('icon icon-Seringe'           => esc_html__('Seringe', 'cvca')),
         array('icon icon-Pulse'             => esc_html__('Pulse', 'cvca')),
         array('icon icon-Plaster'           => esc_html__('Plaster', 'cvca')),
         array('icon icon-Plaine'            => esc_html__('Plaine', 'cvca')),
         array('icon icon-Pill'              => esc_html__('Pill', 'cvca')),
         array('icon icon-PicnicBasket'      => esc_html__('Picnic Basket', 'cvca')),
         array('icon icon-Phone2'            => esc_html__('Phone2', 'cvca')),
         array('icon icon-Pencil'            => esc_html__('Pencil', 'cvca')),
         array('icon icon-Pen'               => esc_html__('Pen', 'cvca')),
         array('icon icon-PaperClip'         => esc_html__('Paper Clip', 'cvca')),
         array('icon icon-On-Off'            => esc_html__('On Off', 'cvca')),
         array('icon icon-Mouse'             => esc_html__('Mouse', 'cvca')),
         array('icon icon-Megaphone'         => esc_html__('Megaphone', 'cvca')),
         array('icon icon-Linked'            => esc_html__('Linked', 'cvca')),
         array('icon icon-Keyboard'          => esc_html__('Keyboard', 'cvca')),
         array('icon icon-House'             => esc_html__('House', 'cvca')),
         array('icon icon-Heart'             => esc_html__('Heart', 'cvca')),
         array('icon icon-Headset'           => esc_html__('Headset', 'cvca')),
         array('icon icon-FullShoppingCart'  => esc_html__('Full Shopping Cart', 'cvca')),
         array('icon icon-FullScreen'        => esc_html__('Full Screen', 'cvca')),
         array('icon icon-Folder'            => esc_html__('Folder', 'cvca')),
         array('icon icon-Floppy'            => esc_html__('Floppy', 'cvca')),
         array('icon icon-Files'             => esc_html__('Files', 'cvca')),
         array('icon icon-File'              => esc_html__('File', 'cvca')),
         array('icon icon-FileBox'           => esc_html__('File Box', 'cvca')),
         array('icon icon-ExitFullScreen'    => esc_html__('Exit Full Screen', 'cvca')),
         array('icon icon-EmptyBox'          => esc_html__('Empty Box', 'cvca')),
         array('icon icon-Delete'            => esc_html__('Delete', 'cvca')),
         array('icon icon-Controller'        => esc_html__('Controller', 'cvca')),
         array('icon icon-Compass'           => esc_html__('Compass', 'cvca')),
         array('icon icon-CompassTool'       => esc_html__('Compass Tool', 'cvca')),
         array('icon icon-ClipboardText'     => esc_html__('Clipboard Text', 'cvca')),
         array('icon icon-ClipboardChart'    => esc_html__('Clipboard Chart', 'cvca')),
         array('icon icon-ChemicalGlass'     => esc_html__('Chemical Glass', 'cvca')),
         array('icon icon-CD'                => esc_html__('Cd', 'cvca')),
         array('icon icon-Carioca'           => esc_html__('Carioca', 'cvca')),
         array('icon icon-Car'               => esc_html__('Car', 'cvca')),
         array('icon icon-Book'              => esc_html__('Book', 'cvca')),
         array('icon icon-BigTruck'          => esc_html__('Big Truck', 'cvca')),
         array('icon icon-Bicycle'           => esc_html__('Bicycle', 'cvca')),
         array('icon icon-Wrench'            => esc_html__('Wrench', 'cvca')),
         array('icon icon-Web'               => esc_html__('Web', 'cvca')),
         array('icon icon-Watch'             => esc_html__('Watch', 'cvca')),
         array('icon icon-Volume'            => esc_html__('Volume', 'cvca')),
         array('icon icon-Video'             => esc_html__('Video', 'cvca')),
         array('icon icon-Users'             => esc_html__('Users', 'cvca')),
         array('icon icon-User'              => esc_html__('User', 'cvca')),
         array('icon icon-UploadCLoud'       => esc_html__('Upload CLoud', 'cvca')),
         array('icon icon-Typing'            => esc_html__('Typing', 'cvca')),
         array('icon icon-Tools'             => esc_html__('Tools', 'cvca')),
         array('icon icon-Tag'               => esc_html__('Tag', 'cvca')),
         array('icon icon-Speedometter'      => esc_html__('Speedometter', 'cvca')),
         array('icon icon-Share'             => esc_html__('Share', 'cvca')),
         array('icon icon-Settings'          => esc_html__('Settings', 'cvca')),
         array('icon icon-Search'            => esc_html__('Search', 'cvca')),
         array('icon icon-Screwdriver'       => esc_html__('Screwdriver', 'cvca')),
         array('icon icon-Rolodex'           => esc_html__('Rolodex', 'cvca')),
         array('icon icon-Ringer'            => esc_html__('Ringer', 'cvca')),
         array('icon icon-Resume'            => esc_html__('Resume', 'cvca')),
         array('icon icon-Restart'           => esc_html__('Restart', 'cvca')),
         array('icon icon-PowerOff'          => esc_html__('Power Off', 'cvca')),
         array('icon icon-Pointer'           => esc_html__('Pointer', 'cvca')),
         array('icon icon-Picture'           => esc_html__('Picture', 'cvca')),
         array('icon icon-OpenedLock'        => esc_html__('Opened Lock', 'cvca')),
         array('icon icon-Notes'             => esc_html__('Notes', 'cvca')),
         array('icon icon-Mute'              => esc_html__('Mute', 'cvca')),
         array('icon icon-Movie'             => esc_html__('Movie', 'cvca')),
         array('icon icon-Microphone2'       => esc_html__('Microphone 2', 'cvca')),
         array('icon icon-Message'           => esc_html__('Message', 'cvca')),
         array('icon icon-MessageRight'      => esc_html__('Message Right', 'cvca')),
         array('icon icon-MessageLeft'       => esc_html__('Message Left', 'cvca')),
         array('icon icon-Menu'              => esc_html__('Menu', 'cvca')),
         array('icon icon-Media'             => esc_html__('Media', 'cvca')),
         array('icon icon-Mail'              => esc_html__('Mail', 'cvca')),
         array('icon icon-List'              => esc_html__('List', 'cvca')),
         array('icon icon-Layers'            => esc_html__('Layers', 'cvca')),
         array('icon icon-Key'               => esc_html__('Key', 'cvca')),
         array('icon icon-Imbox'             => esc_html__('Imbox', 'cvca')),
         array('icon icon-Eye'               => esc_html__('Eye', 'cvca')),
         array('icon icon-Edit'              => esc_html__('Edit', 'cvca')),
         array('icon icon-DSLRCamera'        => esc_html__('DSLR Camera', 'cvca')),
         array('icon icon-DownloadCloud'     => esc_html__('Download Cloud', 'cvca')),
         array('icon icon-CompactCamera'     => esc_html__('Compact Camera', 'cvca')),
         array('icon icon-Cloud'             => esc_html__('Cloud', 'cvca')),
         array('icon icon-ClosedLock'        => esc_html__('Closed Lock', 'cvca')),
         array('icon icon-Chart2'            => esc_html__('Chart 2', 'cvca')),
         array('icon icon-Bulb'              => esc_html__('Bulb', 'cvca')),
         array('icon icon-Briefcase'         => esc_html__('Briefcase', 'cvca')),
         array('icon icon-Blog'              => esc_html__('Blog', 'cvca')),
         array('icon icon-Agenda'            => esc_html__('Agenda', 'cvca')),
     );

     return array_merge( $icons, $strokegap_icons );
 }

/**
 * cvca_create_select_tree
 */
function cvca_create_select_tree($parent_id, $array, $level, &$dropdown)
{

    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i]->parent == $parent_id) {
            $name = str_repeat('-', $level) .' '. $array[$i]->name;
            $value = $array[$i]->slug;
            $dropdown[] = array(
                'label' => $name,
                'value' => $value,
            );
            cvca_create_select_tree($array[$i]->term_id, $array, $level + 1, $dropdown);
        }
    }
}

/**
 * cvca_multi_select_categories
 */
function cvca_multi_select_categories($settings, $value, $taxonomies = 'category')
{
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $categories = get_terms($taxonomies);
    $categories = array_values($categories);

    $categories_tree = array();

    cvca_create_select_tree(0, $categories, 0, $categories_tree);

    $output = $selected = $ids = '';
    if ($value !== '') {
        $ids = explode(',', $value);
        $ids = array_map('trim', $ids);
    } else {
        $ids = array();
    }
    $output .= '<select class="cvca-select-multi-category" multiple="multiple" style="min-width:200px;">';
    foreach ($categories_tree as $cat) {
        if (in_array($cat['value'], $ids)) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        $output .= '<option ' . esc_attr($selected) . ' value="' . esc_attr($cat['value']) . '">' . $cat['label'] . '</option>';
    }
    $output .= '</select>';

    $output .= "<input type='hidden' name='" . esc_attr($param_name) . "' value='" . esc_attr($value) . "' class='wpb_vc_param_value " . esc_attr($param_name) . " " . esc_attr($type) . " " . esc_attr($class) . "'>";
    $output .= '<script type="text/javascript">
					jQuery(".cvca-select-multi-category").select({
						placeholder: "Select Categories",
						allowClear: true
					});
					jQuery(".cvca-select-multi-category").on("change",function(){
						jQuery(this).next().val(jQuery(this).val());
					});
				</script>';

    return $output;
}

/**
 * cvca_vc_multiselect
 */
function cvca_vc_multiselect_field($settings, $value)
{
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $options = isset($settings['value']) ? $settings['value'] : array();

    $output = $selected = $ids = '';

    $values = explode( ',', $value );

    if ( $value !== '' ) {
        $ids = explode(',', $value);
        $ids = array_map('trim', $ids);
    } else {
        $ids = array();
    }

    $output .= '<select class="cvca-multiselect ' . esc_attr($param_name) . '" multiple="multiple" style="min-width:200px;">';
    foreach ($options as $key => $val) {
        $vals = array(
            'value' => trim( $val['value'] ),
            'label' => trim( $val['label'] ),
        );

        if (in_array($vals['value'], $ids)) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }

        if ( isset( $vals['value'], $vals['label'] ) ) {
            $output .= '<option ' . esc_attr($selected) . 'value="' . esc_attr($vals['value']) . '">' . esc_html__($vals['label'], 'cvca') . '</option>';
        }
    }
    $output .= '</select>';

    $output .= "<input type='hidden' name='" . esc_attr($param_name) . "' value='" . esc_attr($value) . "' class='wpb_vc_param_value " . esc_attr($param_name) . " " . esc_attr($type) . "_field " . esc_attr($class) . "' data-settings='" . htmlentities( json_encode( $options ), ENT_QUOTES, 'utf-8' ) . "'>";

    return $output;
}

/**
 * cvca_vc_multiselect2
 */
function cvca_vc_multiselect2_field($settings, $value)
{
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $options = isset($settings['value']) ? $settings['value'] : array();
    $default = isset($settings['default']) ? $settings['default'] : array();

    $output = $selected = $ids = '';

    $values = explode( ',', $value );

    if ( $value !== '' ) {
        $ids = explode(',', $value);
        $ids = array_map('trim', $ids);
    } else {
        $ids = array();
    }
    
    $output .= '<select class="cvca-multiselect2 ' . esc_attr($param_name) . '" multiple="multiple">';
    foreach ($options as $key => $val) {
        $vals = array(
            'value' => trim( $val['value'] ),
            'label' => trim( $val['label'] ),
        );

        if (in_array($vals['value'], $ids)) {
            $selected = ' selected=selected';
        } else {
            $selected = '';
        }

        if ( isset( $vals['value'], $vals['label'] ) ) {
            $output .= '<option' . esc_attr($selected) . ' value="' . esc_attr($vals['value']) . '">' . esc_html__($vals['label'], 'cvca') . '</option>';
        }
    }
    $output .= '</select>';
    $output .= '<a href="javascript:void(0)" class="cvca-multiselect2-all"><i class="vc-composer-icon vc-c-icon-add"></i> Select all</a>';
    $output .= '<a href="javascript:void(0)" class="cvca-multiselect2-clear"><i class="vc-composer-icon vc-c-icon-delete_empty"></i> Clear all</a>';
    $output .= "<input type='hidden' name='" . esc_attr($param_name) . "' value='" . esc_attr($value) . "' class='wpb_vc_param_value " . esc_attr($param_name) . " " . esc_attr($type) . "_field " . esc_attr($class) . "' data-settings='" . htmlentities( json_encode( $options ), ENT_QUOTES, 'utf-8' ) . "'>";

    return $output;
}

/**
 * cvca_vc_animation_type
 */
function cvca_vc_animation_type($settings, $value)
{
    $param_line = '<select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value dropdown wpb-input wpb-select ' . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '">';

    $param_line .= '<option value="">none</option>';

    $param_line .= '<optgroup label="' . esc_html__('Attention Seekers', 'cvca') . '">';
    $options = array("bounce", "flash", "pulse", "rubberBand", "shake", "swing", "tada", "wobble", "jello");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Bouncing Entrances', 'cvca') . '">';
    $options = array("bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Fading Entrances', 'cvca') . '">';
    $options = array("fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Flippers', 'cvca') . '">';
    $options = array("flip", "flipInX", "flipInY");//, "flipOutX", "flipOutY");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Lightspeed', 'cvca') . '">';
    $options = array("lightSpeedIn");//, "lightSpeedOut");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Rotating Entrances', 'cvca') . '">';
    $options = array("rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Sliders', 'cvca') . '">';
    $options = array("slideInDown", "slideInLeft", "slideInRight");//, "slideOutLeft", "slideOutRight", "slideOutUp");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';
    $param_line .= '<optgroup label="' . esc_html__('Zoom Entrances', 'cvca') . '">';
    $options = array("zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '<optgroup label="' . esc_html__('Specials', 'cvca') . '">';
    $options = array("hinge", "rollIn");//, "rollOut");
    foreach ($options as $option) {
        $selected = '';
        if ($option == $value) $selected = ' selected="selected"';
        $param_line .= '<option value="' . esc_attr($option) . '"' . esc_attr($selected) . '>' . esc_html($option) . '</option>';
    }
    $param_line .= '</optgroup>';

    $param_line .= '</select>';

    return $param_line;
}

/**
 * cvca_vc_multi_select
 */
function cvca_vc_multi_select($settings, $value)
{
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $options = isset($settings['value']) ? $settings['value'] : array();

    $output = $selected = $ids = '';

    if ($value !== '') {
        $ids = explode(',', $value);
        $ids = array_map('trim', $ids);
    } else {
        $ids = array();
    }

    $output .= '<select class="cvca-select-multi" multiple="multiple" style="min-width:200px;">';
    foreach ($options as $name => $val) {

        if (in_array($val, $ids)) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        $output .= '<option ' . esc_attr($selected) . ' value="' . esc_attr($val) . '">' . esc_html__($name, 'cvca') . '</option>';
    }
    $output .= '</select>';

    $output .= "<input type='hidden' name='" . esc_attr($param_name) . "' value='" . esc_attr($value) . "' class='wpb_vc_param_value " . esc_attr($param_name) . " " . esc_attr($type) . " " . esc_attr($class) . "'>";
    $output .= '<script type="text/javascript">
							jQuery(".cvca-select-multi").select({
								placeholder: "Select Categories",
								allowClear: true
							});
							jQuery(".cvca-select-multi").on("change",function(){
								jQuery(this).next().val(jQuery(this).val());
							});
						</script>';
    return $output;
}

/**
 * cvca_vc_post_categories
 */
function cvca_vc_post_categories($settings, $value)
{
    return cvca_multi_select_categories($settings, $value, 'category');
}

/**
 * cvca_vc_testimonial_categories
 */
function cvca_vc_testimonial_categories($settings, $value)
{
    return cvca_multi_select_categories($settings, $value, 'testimonial_category');
}/**
 * cvca_vc_testimonial_categories
 */
function cvca_vc_team_member_categories($settings, $value)
{
    return cvca_multi_select_categories($settings, $value, 'team_category');
}

/**
 * cvca_vc_product_categories
 */
function cvca_vc_product_categories($settings, $value)
{
    return cvca_multi_select_categories($settings, $value, 'product_cat');
}

/**
 * cvca_vc_image_radio
 */
function cvca_vc_image_radio($settings, $value)
{
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $output = '<input class="wpb_vc_param_value ' . esc_attr($settings['param_name']) . ' ' . esc_attr($type) . ' ' . esc_attr($class) . '"  type="hidden" name="' . esc_attr($settings['param_name']) . '" value="' . esc_attr($value) . '">';
    $width = isset($settings['width']) ? $settings['width'] : '150px';
    if (count($settings['value']) > 0) {
        foreach ($settings['value'] as $param => $param_val) {
            $border_color = 'white';
            if ($param_val == $value) {
                $border_color = 'green';
            }
            $output .= '<img class="cvca-image-radio-' . esc_attr($settings['param_name']) . '" src="' . esc_url($param) . '" data-value="' . esc_attr($param_val) . '" style="width:' . esc_attr($width) . ';border-style: solid;border-width: 5px;border-color: ' . esc_attr($border_color) . ';margin-left: 15px;">';
        }
        $output .= '<script type="text/javascript">
							jQuery(".cvca-image-radio-' . esc_js($settings['param_name']) . '").click(function() {
							    jQuery("input[name=\'' . esc_js($settings['param_name']) . '\']").val(jQuery(this).data("value"));
							    jQuery(".cvca-image-radio-' . esc_js($settings['param_name']) . '").css("border-color", "white");
							    jQuery(this).css("border-color", "green");
							});
						</script>';
    }

    return $output;
}

/**
 * cvca_vc_datepicker
 */
function cvca_vc_datepicker($settings, $value)
{
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $output = '<input id="cvca-pick-a-date" class="wpb_vc_param_value date-picker' . esc_attr($settings['param_name']) . ' ' . esc_attr($type) . ' ' . esc_attr($class) . '"  type="datetime" name="' . esc_attr($settings['param_name']) . '" value="' . esc_attr($value) . '">';
    $output .= '<script type="text/javascript">
    jQuery( function() {
        jQuery( "#cvca-pick-a-date" ).datepicker();
    } );
  </script>';
    return $output;
}

/**
 * cvca_vc_image_size
 */
function cvca_vc_image_size($settings, $value)
{
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $output = $selected = $ids = '';
    $cvca_img_size = get_intermediate_image_sizes();
    global $_wp_additional_image_sizes;
    $output .= '<select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value cvca-select-image-size dropdown wpb-input wpb-select ' . esc_attr($settings['param_name'] . ' ' . $class) . ' ' . esc_attr($type) . '"value="' . esc_attr($value) . '">';
    foreach ($cvca_img_size as &$size) :
        if ($size == $value) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) :
            $output .= '<option value="' . esc_attr($size) . '"' . $selected . '>' . ucwords(str_replace(array('_', ' - '), array(' ', ' '), $size)) . ' (' . get_option("{$size}_size_w") . 'x' . get_option("{$size}_size_h") . ')' . '</option>';
        elseif (isset($_wp_additional_image_sizes[$size])) :
            $output .= ' <option value="' . esc_attr($size) . '"' . $selected . '> ' . ucwords(str_replace(array('_', '-'), array(' ', ' '), $size)) . ' (' . $_wp_additional_image_sizes[$size]['width'] . 'x' . $_wp_additional_image_sizes[$size]['height'] . ')</option>';
        endif;
    endforeach;
    $output .= ' <option value="full"' . $selected . '>'.esc_html__('Full','cvca').'</option>';
    $output .= '</select>';
    return $output;
}


/**
 * Add VC shortcode params
 */
if ( !function_exists('vc_add_shortcode_param') ) {
    if(function_exists('cvca_make_the_plugin_load_at_last_position')) {
        cvca_make_the_plugin_load_at_last_position();
    }
} else {
    vc_add_shortcode_param('cvca_animation_type', 'cvca_vc_animation_type');
    vc_add_shortcode_param('cvca_post_categories', 'cvca_vc_post_categories');
    vc_add_shortcode_param('cvca_testimonial_categories', 'cvca_vc_testimonial_categories');
    vc_add_shortcode_param('cvca_team_member_categories', 'cvca_vc_team_member_categories');
    vc_add_shortcode_param('cvca_product_categories', 'cvca_vc_product_categories');
    vc_add_shortcode_param('cvca_image_radio', 'cvca_vc_image_radio');
    vc_add_shortcode_param('cvca_datepicker', 'cvca_vc_datepicker');
    vc_add_shortcode_param('cvca_multi_select', 'cvca_vc_multi_select');
    vc_add_shortcode_param('cvca_multiselect', 'cvca_vc_multiselect_field', CVCA_URI . 'assets/js/fields/cvca_multiselect.js');
    vc_add_shortcode_param('cvca_multiselect2', 'cvca_vc_multiselect2_field', CVCA_URI . 'assets/js/fields/cvca_multiselect2.js');
    vc_add_shortcode_param('cvca_image_size', 'cvca_vc_image_size');
}
