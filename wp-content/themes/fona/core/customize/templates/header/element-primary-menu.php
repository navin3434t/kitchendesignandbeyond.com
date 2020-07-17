<?php
/**
 * Template for primary menu element.
 *
 * Template of `core/customize/builder/elements/primary-menu.php`
 */

$container_classes[] = $atts['menu_class'];
$container_classes[] = 'primary-menu';
?>
<nav id="primary-menu" <?php $this->element_class($container_classes)?>>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary-menu',
        'container' => false,
        'container_id' => false,
        'container_class' => false,
        'menu_id' => false,
        'menu_class' => 'menu nav-menu',
        'walker' => zoo_get_walker_nav_menu(),
        'fallback_cb' => false,
    ));
    ?>
</nav>
