<?php
wp_enqueue_style('cvca-style');
$args = array(
    'post_type' => 'service',
    'posts_per_page' => ($atts['number'] > 0) ? $atts['number'] : get_option('posts_per_page')
);

if ($atts['post_in'] != '')
    $args['post_in'] = explode(',', $atts['post_in']);
$args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;

$the_query = new WP_Query($args);
if ($atts['layout'] == 'carousel') {
    $jsconfig = '{"item":"' . $atts['columns'] . '","wrap":".wrap-service-carousel"}';
    wp_enqueue_style('slick');
    wp_enqueue_script('slick');
    wp_enqueue_script('cvca-script');
}
$zoo_wrap_class = $atts['style'] . ' cvca-services cvca-service-' . $atts['layout'] . '-layout '
?>
<div class="<?php echo esc_attr($zoo_wrap_class);
echo esc_attr($atts['layout'] == 'carousel' ? ' cvca-carousel' : ''); ?> "
    <?php if ($atts['layout'] == 'carousel') { ?>   data-config=' <?php echo esc_attr($jsconfig); ?> ' <?php } ?>>
<?php if ($atts['title']) { ?>
    <h3 class="shortcode-title"><?php echo esc_html($atts['title']); ?></h3>
<?php } ?>
<?php if ($the_query->have_posts()):
if ($atts['layout'] == 'grid') {
    ?>
    <div class="row">
    <?php
    }
    if ($atts['layout'] == 'carousel') { ?>
    <div class="wrap-service-carousel">
        <?php
        }
        while ($the_query->have_posts()): $the_query->the_post();
            echo cvca_get_shortcode_view('service-layouts/' . $atts['layout']. '-layout', $atts);
        endwhile;
        ?>
    </div>
    <?php
endif;
?>
    </div>
<?php
if ($atts['pagination'] != 'none' && $atts['layout'] != 'carousel') {
    if (function_exists("zoo_pagination")) :
        echo '<div class="cvca-pagination">';
        //zoo_pagination(3, $the_query, '', '<i class="clever-icon-arrow-regular"></i>', '<i class="clever-icon-arrow-regular"></i>');
        echo '</div>';

    endif;
}
wp_reset_postdata();
?>