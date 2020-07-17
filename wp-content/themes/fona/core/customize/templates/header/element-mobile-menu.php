<?php
/**
 * Template for Mobile menu element.
 *
 * Template of `core/customize/builder/elements/mobile-menu.php`
 */

$style = $atts['style'];
$container_classes[] = $this->id . ' ' . $this->id . '-__id__ nav-menu-__device__ ' . $this->id . '-__device__' . ($style ? ' ' . $style : '');
$container_classes[] = 'site-navigation';

if (!empty($align)) {
    $container_classes[] = 'element-align-'.esc_attr($align);
}

?>
    <nav id="element-mobile-menu" <?php $this->element_class($container_classes); ?>>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'mobile-menu',
            'container' => false,
            'container_id' => false,
            'container_class' => false,
            'menu_id' => false,
            'menu_class' => $this->id . '-ul menu nav-menu',
            'fallback_cb' => false,
            'link_before' => '',
            'link_after' => '',
        ));
        ?>
    </nav>
<?php
