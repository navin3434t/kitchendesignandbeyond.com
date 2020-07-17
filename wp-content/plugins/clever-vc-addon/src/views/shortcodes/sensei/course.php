<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Content-course.php template file
 */
// $course = Sensei()->course;
$course = get_post( get_the_ID() );
$category_output = get_the_term_list( get_the_ID(), 'course-category', '', ', ', '' );
$author_display_name = get_the_author_meta( 'display_name', $course->post_author  );
?>
<li <?php post_class(  WooThemes_Sensei_Course::get_course_loop_content_class() ); ?>>
    <?php do_action( 'sensei_course_content_before', get_the_ID() ); ?>

    <section class="course-content">
        <section class="entry">
            <?php do_action( 'sensei_course_content_inside_before', get_the_ID() ); ?>

            <?php if ( isset( $atts['show_image'] ) && $atts['show_image'] === 'yes' ) : ?>
            <div class="course-media">
                <?php
                    Sensei()->course->course_image( get_the_ID(), Sensei()->settings->settings[ 'course_archive_image_width' ], Sensei()->settings->settings[ 'course_archive_image_height' ] );
                ?>
                <?php if ( isset( $atts['show_author'] ) && $atts['show_author'] === 'yes' ) : ?>
                <div class="course-author-box">
	                <?php if ( isset( Sensei()->settings->settings[ 'course_author' ] ) && ( Sensei()->settings->settings[ 'course_author' ] ) ) { ?>
	                    <?php if ( function_exists( 'get_avatar' ) ) : ?>
	                        <span class="course-author">
	                            <a href="<?php echo esc_attr( get_author_posts_url( $course->post_author ) ); ?>" title="<?php echo esc_attr( $author_display_name ); ?>">
	                                <?php echo wp_kses( get_avatar( $course->post_author, '60' ), array( 'img' => array( 'class' => array(), 'width' => array(), 'height' => array(), 'alt' => array(), 'src' => array() ) ) ); ?>
	                            </a>
	                        </span>
	                    <?php endif; ?>
	                <?php } ?>
                
                    <a class="author-name" href="<?php echo esc_attr( get_author_posts_url( $course->post_author ) ); ?>" title="<?php echo esc_attr( $author_display_name ); ?>"><?php echo esc_attr( $author_display_name ); ?></a>
                </div>
                <?php endif; ?>
                <?php if ( isset( $atts['show_quickview'] ) && $atts['show_quickview'] === 'yes' ) : ?>
                <div class="quick-view">
                    <a class="btn sensei-quick-view" href="<?php echo get_permalink( get_the_ID() ); ?>" data-course-id="<?php echo get_the_ID(); ?>"><?php esc_html_e( 'Quick View', 'cvca' );?></a>
                </div>
                <?php endif; ?>
            </div><!-- .course-media -->
            <?php endif; ?>
            <div class="course-text">
                <div class="course-header">
                    <?php Sensei_Templates::the_title( get_the_ID() ); ?>  
                </div><!-- .course-header -->
                <?php if ( isset( $atts['show_date'] ) && $atts['show_date'] === 'yes' ) : ?>
                <span class="course-date"><?php echo get_the_date(); ?></span>
            	<?php endif; ?>
                <?php if ( isset($atts['show_category']) && $atts['show_category'] === 'yes' && '' != $category_output ) { ?>
                    <div class="course-category"><?php echo sprintf( esc_html__( 'in %s', 'cvca' ), $category_output ); ?></div>
                <?php } ?>
                <?php if( isset( $atts['show_desc'] ) && $atts['show_desc'] === 'yes' ) : ?>
                    <?php
                    $excerpt = get_the_content();
                    if ( ! empty( get_the_excerpt() ) ) {
                        $excerpt = get_the_excerpt();
                    }?>
                    <div class="course-excerpt">
                        <?php echo cvca_word_limit( $excerpt, $atts['desc_length'], '...' ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( isset( $atts['show_review'] ) && $atts['show_review'] === 'yes' && function_exists( 'zoo_the_comment_rating' ) ) : ?>
                <div class="course-reviews">
                    <span class="course-reviews-content">
                        <span class="average-rating"><?php echo zoo_rate_calculate(); ?></span>
                        <span class="average-rating-count">(<?php echo zoo_rating_total() . ' '; ?><?php echo ( zoo_rating_total() > 1 ) ? esc_html__( 'Reviews', 'cvca' ) : esc_html__( 'Review', 'cvca' ); ?>)</span>
                    </span>
                </div>
                <?php endif; ?>
                <?php if ( ( isset( $atts['show_lesson'] ) && $atts['show_lesson'] === 'yes' ) || ( isset( $atts['show_comment'] ) && $atts['show_comment'] === 'yes' ) || ( isset( $atts['show_price'] ) && $atts['show_price'] === 'yes' ) ) : ?>
                <div class="sensei-course-meta">
                    <?php
                    // Get course participant count
                    // do_action( 'zoo_eduhub_sensei_course_participant_count', get_the_ID() );
                    ?>
                    <?php if( isset( $atts['show_lesson'] ) && $atts['show_lesson'] === 'yes' ) : ?>
                        <span class="course-lesson-count">
                            <?php echo Sensei()->course->course_lesson_count( get_the_ID() ) . '&nbsp;' .  esc_html__( 'Lessons', 'cvca' ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if( isset( $atts['show_comment'] ) && $atts['show_comment'] === 'yes' ) : ?>
                    <?php // Get comment count ?>
                        <span class="comments-number">
                            <?php comments_popup_link( '<i class="fa fa-comment"></i> 0', '<i class="fa fa-comment"></i> 1', '<i class="fa fa-comment"></i> %', 'comments-link'); ?>
                        </span>
                    <?php endif; ?>
                    <?php if( isset( $atts['show_price'] ) && $atts['show_price'] === 'yes' ) {
                            sensei_simple_course_price( get_the_ID() );
                    } ?>
                </div><!-- .course-action -->
                <?php endif; ?>
            </div>
        </section> <!-- section .entry -->
    </section> <!-- section .course-content -->

    <?php do_action('sensei_course_content_after', get_the_ID() ); ?>
</li>