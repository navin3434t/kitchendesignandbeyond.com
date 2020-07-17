<?php
/**
 * Template for logo element.
 *
 * Template of `core/customize/builder/elements/logo.php`
 */
$class = ['site-branding', 'item-block-logo'];
if ($atts['sticky_logo_img']) {
    $class[] = 'has-sticky-logo';
}
if (!empty($atts['align'])) {
    $class[] = 'element-align-'.esc_attr($atts['align']);
}
$logo_classes = apply_filters('zoo_site_logo_classes_attr', $class);
?>
<div <?php $this->element_class($logo_classes); ?>>
<?php
$logo = $atts['logo_img'];
if(file_exists(get_template_directory().'/assets/images/logo.svg') && $atts['header_logo_enable_svg'] == 'yes'){
    $logo=get_template_directory_uri().'/assets/images/logo.svg';
}
if ($logo != '' || $atts['header_logo_svg'] != '') {
    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" title="<?php bloginfo('name'); ?>" class="wrap-logo">
        <?php
        if ($atts['header_logo_svg']) {
            echo wp_kses_post($atts['header_logo_svg']);
        } else { ?>
            <img class="site-logo" src="<?php echo esc_url($logo) ?>"
                 alt="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
            <?php
            if ($atts['sticky_logo_img'] != '') {
                ?>
                <img class="sticky-logo" src="<?php echo esc_url($atts['sticky_logo_img']) ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
                <?php
            }
        }
        ?>
    </a>
    <?php
}
if ($atts['site_name'] != '') {
    ?>
    <h2 id="site-name-<?php echo esc_attr($atts['device']) ?>" class="site-name">
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="<?php esc_attr_e('home','fona'); ?>"
           title="<?php esc_attr(bloginfo('name')); ?>">
            <?php
            echo esc_html($atts['site_name']);
            ?></a></h2>
    <?php
}
if ($atts['site_desc'] != '') {
    ?>
    <p id="site-description-<?php echo esc_attr($atts['device']) ?>" class="site-description"><?php
        echo esc_html($atts['site_desc']);
        ?></p>
    <?php
}
?>
    </div><?php
