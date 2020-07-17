<?php
/**
 * Template for primary menu element.
 *
 * Template of `core/customize/builder/elements/primary-menu.php`
 */

$container_classes[] = $atts['menu_class'];
$container_classes[] = 'top-menu';
?>
<nav id="top-menu" <?php $this->element_class($container_classes)?>>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'top-menu',
        'container' => false,
        'container_id' => false,
        'container_class' => false,
        'menu_id' => false,
        'menu_class' => 'menu nav-menu',
        'fallback_cb' => false,
    ));
    ?>
</nav>
