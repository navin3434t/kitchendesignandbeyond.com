<?php
/**
 * Block information for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 Zootemplate
 
 */
?>
<ul class="post-info">
    <?php
    if (get_theme_mod('zoo_blog_single_post_info_style', 'icon') == 'icon') {
        if (get_theme_mod('zoo_enable_blog_author_post', '1') == 1) {
            ?>
            <li class="author-post">
                <i class="cs-font clever-icon-user-2"></i>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                   title="<?php echo esc_attr(get_the_author()) ?>">
                    <?php echo esc_html(get_the_author()) ?>
                </a>
            </li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_date_post', '1') == 1) {
            ?>
            <li class="post-date">
                <i class="cs-font clever-icon-clock-4"></i>
                <?php echo esc_html(get_the_date()); ?></li>
            <?php }
        if (get_theme_mod('zoo_enable_blog_cat_post', '1') == 1) {
            ?>
            <li class="list-cat">
                <i class="cs-font clever-icon-document"></i>
                <?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?></li>
        <?php }
    } else {
        if (get_theme_mod('zoo_enable_blog_author_post', '1') == 1) { ?>
            <li class="author-post"><?php esc_html_e('by', 'fona'); ?>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                   title="<?php echo esc_attr(get_the_author()) ?>">
                    <?php echo esc_html(get_the_author()) ?>
                </a>
            </li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_date_post', '1') == 1) {
            ?>
            <li class="post-date"><?php esc_html_e('on', 'fona'); ?><?php echo esc_html(get_the_date()); ?></li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_cat_post', '1') == 1) {
            ?>
            <li class="list-cat"><?php esc_html_e('in', 'fona'); ?><?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?></li>
        <?php }
    }
    ?>
</ul>

