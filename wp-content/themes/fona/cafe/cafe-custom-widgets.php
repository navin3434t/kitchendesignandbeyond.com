<?php 
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// add_action( 'elementor/element/clever-posts/layout_settings/after_section_start', function( $element, $args ) {
//   /** @var \Elementor\Widgets\Widget_Counter $element */
//   $element->add_control(
//     'layout', [
//       'label' => esc_html__('Layout', 'fona'),
//       'type' => Controls_Manager::SELECT,
//       'default' => 'grid',
//       'options' => [
//         'grid' => esc_html__('Grid', 'fona'),
//         'list' => esc_html__('List', 'fona'),
//         'full' => esc_html__('Full', 'fona'),
//         'carousel' => esc_html__('Carousel', 'fona'),
//       ],
//       'description' => esc_html__('Layout of blog.', 'fona'),
//     ]
//   );
//   $element->add_control(
//     'output_type', [
//       'label' => esc_html__('Content display', 'fona'),
//       'type' => Controls_Manager::SELECT,
//       'default' => 'excerpt',
//       'options' => [
//         'excerpt' => esc_html__('Excerpt', 'fona'),
//         'full' => esc_html__('Full Content', 'fona'),
//         'none' => esc_html__('None', 'fona'),
//       ],
//       'description' => esc_html__('Number testimonial display.', 'fona'),
//     ]
//   );
//   $element->add_control(
//     'pagination', [
//       'label' => esc_html__('Pagination', 'fona'),
//       'type' => Controls_Manager::SELECT,
//       'default' => 'none',
//       'options' => [
//         'none' => esc_html__('none', 'fona'),
//         'numeric' => esc_html__('Numeric', 'fona'),
//       ],
//       'condition' => [
//         'layout' => ['grid','list','full'],
//       ],
//       'description' => esc_html__('Not work with carousel layout. Numeric pagination work only with page have single widget posts. ', 'fona'),
//     ]
//   );
// }, 10, 2 );

// add_action( 'elementor/element/clever-site-nav-menu/layout_settings/before_section_start', function( $element, $args ) {
// 	/** @var \Elementor\Widgets\Widget_Counter $element */
// 	$element->add_group_control(
// 		Group_Control_Typography::get_type(),
// 		[
// 			'name'          => 'submenu_typo',
// 			'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
// 			'selector'      => '{{WRAPPER}} .cafe-site-menu .menu-item > a',
// 		]
// 	);
// }, 15, 5 );
