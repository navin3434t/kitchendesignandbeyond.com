<?php
/**
 * List layout for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @core        3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 
 */
$class = 'post-loop-item post-loop-item list-layout-item col-12';
?>
<article <?php echo post_class($class) ?>>
    <div class="zoo-post-inner">
        <?php
        the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="' . esc_attr__('bookmark', 'fona') . '">', esc_url(get_permalink())), '</a></h3>');
        get_template_part('inc/templates/posts/loop/post', 'info');
        get_template_part('inc/templates/posts/loop/media');
        ?>
        <div class="entry-content<?php if (get_theme_mod('zoo_enable_loop_excerpt', 0) == 1) { echo esc_attr(' excerpt');}?>">
            <?php
            if (get_theme_mod('zoo_enable_loop_excerpt', 0) == 1 || is_search() ) {
                the_excerpt();
            }else{
                the_content();
            }
            ?>
        </div>
        <?php
        if (get_theme_mod('zoo_enable_loop_readmore','1')==1 || get_the_title()=='') {
        ?>
        <a href="<?php echo esc_url(the_permalink()); ?>"
           class="readmore"><?php echo esc_html__('Read more', 'fona'); ?></a>
        <?php
        }
        ?>
    </div>
</article>