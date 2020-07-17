<?php
/**
 * Banner Shortcode
 */

wp_enqueue_style('cvca-style');

$layout = $css_class = $zoo_start_link = $zoo_link_text = $zoo_end_link = '';

if ( isset( $atts['course_layout'] ) && $atts['course_layout'] != '' ) {
    $layout = '-' . $atts['course_layout'];
} 

$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverSenseiCourses', $atts );

if ( !empty( $atts['el_class'] ) ) {
    $css_class .= ' ' . $atts['el_class'];
}

if ( !empty( $custom_class ) ) {
    $css_class .= ' ' . $custom_class;
}

$category   = isset( $atts['category'] ) ? $atts['category'] : '';
$category   = is_numeric( $category ) ? intval( $category ) : $category;

$cols       = isset( $atts['cols'] ) ? intval( $atts['cols'] ) : 4;
$number     = isset( $atts['number'] ) ? $atts['number'] : '8';
$orderby    = isset( $atts['orderby'] ) ? $atts['orderby'] : 'date';

// set the default for menu_order to be ASC
if ( 'menu_order' == $orderby && !isset( $atts['orderby'] ) ) {

    $order =  'ASC';

} else {

    // for everything else use the value passed or the default DESC
    $order = isset( $atts['order'] ) ? $atts['order'] : 'DESC';

}

// query defaults
$query_args = array(
    'post_type'        => 'course',
    'post_status'      => 'publish',
    'orderby'          => $orderby,
    'order'            => $order,
    'posts_per_page'   => $number,

);

// add the course category taxonomy query
if ( ! empty( $category ) ) {

    $tax_query = array();
    $term_id = intval( term_exists( $category ) );

    if ( !empty( $term_id ) ) {

        $tax_query = array(
            'taxonomy' => 'course-category',
            'field' => 'id',
            'terms' => $term_id,
        );

    }

    $query_args['tax_query'] = array($tax_query);

}

$query = new WP_Query( $query_args );

global $sensei_course_loop;

// Reset columns
$sensei_course_loop['columns'] = $cols;
// Reset counter.
$sensei_course_loop['counter'] = 0;
?>
<div class="cvca-shortcode-sensei-courses zoo-sensei-courses<?php echo ( isset( $atts['course_layout'] ) && $atts['course_layout'] != '' ) ? ' layout-' . $atts['course_layout'] : ' layout-default'; ?><?php echo esc_attr( $css_class ); ?>">
    <?php if ( $atts['title'] != '' ) : ?>
        <h3 class="zoo-sensei-courses-title">
            <?php echo esc_html($atts['title']); ?>
        </h3>
    <?php endif; ?>

    <div class="zoo-sensei-courses-content">
        <?php add_filter( 'sensei_course_loop_number_of_columns', function () use ($cols) { return $cols; } ); ?>
        <?php

            global $wp_query;
            $current_global_query = $wp_query;
            $wp_query = $query;
        ?>

            <ul class="course-container columns-<?php sensei_courses_per_row(); ?>" >
               
                <?php
                while ( $query->have_posts() ) { $query->the_post();
                    echo cvca_get_shortcode_view('sensei/course' . $layout, $atts);
                }
            
                wp_reset_query();
                ?>
            </ul>

        <?php $wp_query = $current_global_query; ?>
    </div>
</div>
