<?php
/**
 * Grid layout for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @core        3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 
 */
$class = 'post-loop-item layout-item grid-layout-item ';
switch (get_theme_mod('zoo_blog_cols', '3')) {
    case '2':
        $class .= "col-12 col-sm-6";
        break;
    case '3':
        $class .= "col-12 col-sm-4";
        break;
    case '4':
        $class .= "col-12 col-sm-6 col-md-3";
        break;
    case '5':
        $class .= "col-12 col-md-1-5";
        break;
    case '6':
        $class .= "col-12 col-sm-4 col-md-2";
        break;
    default:
        $class .= "col-12";
        break;
}
?>
<article <?php echo post_class($class) ?>>
    <div class="zoo-post-inner">
		<?php
		get_template_part('inc/templates/posts/loop/media');
		the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="' . esc_attr__('bookmark', 'fona') . '">', esc_url(get_permalink())), '</a></h3>');
		get_template_part('inc/templates/posts/loop/post', 'info');
        ?>
        <div class="entry-content">
            <?php
            if (get_theme_mod('zoo_enable_loop_excerpt', 0) == 1) {
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