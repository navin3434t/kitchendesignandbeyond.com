<?php
/**
 * Grid layout item for service shortcode
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 * @license     GPL v2
 */
//class for option columns
$class = 'cvca-service-item ';
switch ($atts['columns']) {
    case '2':
        $class .= "col-xs-12 col-sm-6 col-md-6";
        break;
    case '3':
        $class .= "col-xs-12 col-sm-6 col-md-4";
        break;
    case '4':
        $class .= "col-xs-12 col-sm-6 col-md-3";
        break;
    case '5':
        $class .= "col-xs-12 col-sm-1-5 col-md-1-5";
        break;
    case '6':
        $class .= "col-xs-12 col-sm-6 col-md-2";
        break;
    default:
        $class .= "col-xs-12 col-sm-6 col-md-4";
        break;
}
?>
    <article <?php echo post_class($class) ?>>
        <a href="<?php echo esc_url(get_permalink()); ?>" class="wrap-media">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail($atts['blog_img_size']); ?>
            <?php endif; ?>
        </a>
        <?php
        the_title(sprintf('<h5 class="entry-title service-title"><a href="%s" rel="' . esc_html__('bookmark', 'cvca') . '">', esc_url(get_permalink())), '</a></h5>');
        if ($atts['output_type'] != 'no') { ?>
            <div class="entry-content"><?php
                if ($atts['output_type'] == 'excerpt') {
                    echo cvca_get_excerpt($atts['excerpt_length']);
                } else {
                    the_content();
                } ?>
            </div>
            <?php
        }
        if ($atts['view_more'] == 'yes') { ?>
            <a href="<?php echo esc_url(the_permalink()); ?>"
               class="readmore"><?php echo esc_html__('Read more', 'cvca'); ?> <i
                        class="cs-font clever-icon-arrow-bold"></i></a>
        <?php } ?>
    </article>