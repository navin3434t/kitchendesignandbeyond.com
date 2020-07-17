<?php
/**
 * Carousel layout item for service shortcode
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 * @license     GPL v2
 */
$class = 'cvca-service-item ';
if ($atts['set_height'] == 'yes') {
    $cvca_img = get_post_thumbnail_id(get_the_ID());
    $cvca_attachments = get_attached_file($cvca_img);
    if (has_post_thumbnail() && $cvca_attachments) {
        $cvca_item = wp_get_attachment_image_src($cvca_img, 'full');
        $cvca_img_url = $cvca_item[0];
    }
    $class .= 'fix-height';
    $cvca_style = "background:url('" . $cvca_img_url . "') center center/cover no-repeat; height:" . $atts['height'] . 'px;';
}
?>
<article
    <?php echo post_class($class);
    if ($atts['set_height'] == 'yes') { ?> style="<?php echo $cvca_style; ?>"<?php } ?> >
    <?php if ($atts['set_height'] != 'yes') { ?>
        <div class="wrap-head-service">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="wrap-media">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail($atts['blog_img_size']); ?>
                <?php endif; ?>
            </a>
            <?php
            the_title(sprintf('<h5 class="entry-title service-title"><a href="%s" rel="' . esc_html__('bookmark', 'cvca') . '">', esc_url(get_permalink())), '</a></h5>');
            ?>
        </div>
        <?php
    }
    ?>
    <div class="wrap-service-content">
        <?php
        if ($atts['set_height'] == 'yes') {
            the_title(sprintf('<h5 class="entry-title service-title"><a href="%s" rel="' . esc_html__('bookmark', 'cvca') . '">', esc_url(get_permalink())), '</a></h5>');
        }
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
    </div>
</article>