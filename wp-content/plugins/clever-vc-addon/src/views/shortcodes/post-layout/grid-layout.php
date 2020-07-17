<?php
/**
 * Grid layout for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 * @license     GPL v2
 */
$class = 'cvca-blog-item layout-item grid-layout-item ';
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
        $class .= "col-xs-12 col-sm-12";
        break;
}
?>
<article <?php echo post_class($class) ?>>
    <div class="cvca-post-inner">
        <?php
        echo cvca_get_shortcode_view('post-layout/media-block', $atts);?>
        <div class="wrap-content-post">
        <?php
        echo cvca_get_shortcode_view('post-layout/post-info', $atts);
        the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="' . esc_html__('bookmark', 'cvca') . '">', esc_url(get_permalink())), '</a></h3>'); ?>
        <?php if ($atts['output_type'] != 'no') { ?>
            <div class="entry-content">
                <?php
                if ($atts['output_type'] == 'excerpt') {
                    echo cvca_get_excerpt($atts['excerpt_length']);
                } else {
                    the_content();
                }
                ?>
            </div>
        <?php }
        if ($atts['view_more'] == 'yes') {
            ?>
            <a href="<?php echo esc_url(the_permalink()); ?>"
               class="readmore"><?php echo esc_html__('Read more', 'cvca'); ?>
            </a>
        <?php } ?>
        </div>
    </div>
</article>