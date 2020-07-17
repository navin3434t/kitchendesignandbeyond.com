<?php
/**
 * Product share template
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2018 ZooTemplate
 
 *
 */

if(class_exists('CleverAddons')){
?>
<div class="zoo-woo-share">
    <span class="label-share"><?php esc_html_e('Share:','fona');?></span>
        <ul class="social-icons">
            <li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>"
                                    class="post_share_facebook icon-around" onclick="javascript:window.open(this.href,
                          '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;"
                                    title="<?php echo esc_attr__('Share to facebook', 'fona') ?>"><i
                            class="cs-font clever-icon-facebook"></i> </a></li>
            <li class="twitter"><a href="https://twitter.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
                          '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;"
                                   title="<?php echo esc_attr__('Share to twitter', 'fona') ?>"
                                   class="product_share_twitter icon-around"><i class="cs-font clever-icon-twitter"></i></a></li>
            <li class="pinterest"><a
                        href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php if (function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id()); ?>&description=<?php the_title_attribute(); ?>"
                        class="product_share_email icon-around"
                        title="<?php echo esc_attr__('Share to pinterest', 'fona') ?>"><i
                            class="cs-font clever-icon-pinterest"></i></a></li>
            <li class="mail"><a
                        href="mailto:?subject=<?php the_title_attribute(); ?>&body=<?php echo strip_tags(get_the_excerpt()); ?> <?php the_permalink(); ?>"
                        class="product_share_email icon-around"
                        title="<?php echo esc_attr__('Sent to mail', 'fona') ?>"><i class="cs-font clever-icon-mail-6"></i></a>
            </li>
        </ul>
</div><?php }