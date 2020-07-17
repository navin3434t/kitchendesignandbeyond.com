<?php
/**
 * Team Member Shortcode
 */

$css_class = '';

$custom_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverTeamMember', $atts);

if (!empty($atts['el_class'])) {
    $css_class .= ' ' . $atts['el_class'];
}

if (!empty($custom_class)) {
    $css_class .= ' ' . $custom_class;
}

$args = array(
    'post_type' => 'team',
    'order_by' => $atts['order_by'],
    'posts_per_page' => ($atts['item_count'] > 0) ? $atts['item_count'] : get_option('posts_per_page'),
);
if ($atts['category']) {
    $catid = array();
    foreach (explode(',', $atts['category']) as $catslug) {
        $catid[] .= get_term_by('slug', $catslug, 'team_category')->term_id;
    }
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'team_category',
            'field' => 'id',
            'terms' => $catid,
        )
    );
}
$class = '';
switch ($atts['columns']) {
    case '2':
        $class .= "col-xs-12 col-sm-6";
        break;
    case '3':
        $class .= "col-xs-12 col-sm-4";
        break;
    case '4':
        $class .= "col-xs-12 col-sm-3";
        break;
    case '5':
        $class .= "col-xs-12 col-sm-1-5";
        break;
    case '6':
        $class .= "col-xs-12 col-sm-2";
        break;
    default:
        $class .= "col-xs-12";
        break;
}
if ($atts['layout'] == 'list') {
    $class = "col-xs-12";
}
$css_class .= ' ' . $atts['layout'] . '-layout';
$wrapID = 'cvca_tm_block_' . uniqid();
wp_enqueue_style('cvca-style');
?>
<div id="<?php echo esc_attr($wrapID) ?>"
     class="cvca-tm-shortcode cvca-tm <?php echo esc_attr($css_class); ?>">
    <?php
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()):
        if ($atts['title'] != '') { ?>
            <h2 class="title-block"><?php echo esc_attr($atts['title']); ?></h2>
        <?php } ?>
        <div class="row">
            <?php
            while ($the_query->have_posts()):$the_query->the_post();
                ?>
                <article id="cvca-tm-<?php the_ID(); ?>" <?php echo post_class('cvca-tm-item ' . $class) ?>>
                    <div class="cvca-wrap-tm">
                        <div class="cvca-wrap-avatar">
                            <?php the_post_thumbnail($atts['img_size']);
                            if ($atts['show_social']) {
                                ?>
                                <ul class="social-profile">
                                    <?php
                                    $cvca_tm_fb = get_post_meta(get_the_ID(), 'cvca_team_member_fb', true);
                                    $cvca_tm_tw = get_post_meta(get_the_ID(), 'cvca_team_member_tw', true);
                                    $cvca_tm_gp = get_post_meta(get_the_ID(), 'cvca_team_member_gp', true);
                                    $cvca_tm_li = get_post_meta(get_the_ID(), 'cvca_team_member_li', true);
                                    $cvca_tm_yt = get_post_meta(get_the_ID(), 'cvca_team_member_yt', true);
                                    if ($cvca_tm_fb != '') {
                                        ?>
                                        <li class="facebook"><a
                                                    href="<?php echo esc_url($cvca_tm_fb); ?>" target="_blank"><i
                                                        class="fa fa-facebook-f"></i></a></li>
                                        <?php
                                    }
                                    if ($cvca_tm_tw != '') {
                                        ?>
                                        <li class="twitter"><a
                                                    href="<?php echo esc_url($cvca_tm_tw); ?>" target="_blank"><i
                                                        class="fa fa-twitter"></i></a></li>
                                        <?php
                                    }
                                    if ($cvca_tm_gp != '') {
                                        ?>
                                        <li class="google-plus"><a
                                                    href="<?php echo esc_url($cvca_tm_gp); ?>" target="_blank"><i
                                                        class="fa fa-google-plus"></i></a></li>
                                        <?php
                                    }
                                    if ($cvca_tm_li != '') {
                                        ?>
                                        <li class="linked-in"><a
                                                    href="<?php echo esc_url($cvca_tm_li); ?>" target="_blank"><i
                                                        class="fa fa-linkedin"></i></a></li>
                                        <?php
                                    }
                                    if ($cvca_tm_yt != '') {
                                        ?>
                                        <li class="youtube"><a
                                                    href="<?php echo esc_url($cvca_tm_yt); ?>" target="_blank"><i
                                                        class="fa fa-youtube"></i></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="cvca-wrap-tm-content">
                            <?php
                            the_title(sprintf('<h4 class="cvca-tm-name"><a href="%s" rel="' . esc_html__('bookmark', 'cvca') . '">', esc_url(get_permalink())), '</a></h4>');
                            if (get_post_meta(get_the_ID(), 'cvca_team_member_pos', true) != '') { ?>
                                <span class="cvca-tm-postion"><?php
                                    echo esc_html(get_post_meta(get_the_ID(), 'cvca_team_member_pos', true)); ?>
                            </span>
                                <?php
                            }
                            if ($atts['output_type'] != 'none') { ?>
                                <div class="cvca-tm-description">
                                    <?php
                                    if ($atts['output_type'] == 'description') {
                                        echo esc_html(get_post_meta(get_the_ID(), 'cvca_team_member_des', true));
                                    } else {
                                        the_content();
                                    }
                                    ?>
                                </div>
                            <?php }
                            if ($atts['show_view_more']) {
                                ?>
                                <a href="<?php the_permalink() ?>" class="cvca-tm-view"
                                   title="<?php echo esc_attr($atts['view_more_text']) ?>">
                                    <?php echo esc_html($atts['view_more_text']) ?>
                                    <i class="fa fa-angle-double-right"></i>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </div>
        <?php
    endif;
    wp_reset_postdata();
    ?>
</div>
