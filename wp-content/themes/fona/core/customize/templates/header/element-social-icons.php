<?php
/**
 * Template for social icons element.
 *
 * Template of `core/customize/builder/elements/social-icons.php`
 */
$rel = '';
if ($atts['nofollow'] == 1) {
    $rel = 'nofollow';
}

$target = '_self';
if ($atts['target_blank'] == 1) {
    $target = '_blank';
}

if (!empty($atts['items'])) {
    $classes = array();
    $classes[] = $this->class;
    $classes[] = 'item-block-social-icons';
    if ($atts['shape']) {
        $shape = ' shape-' . sanitize_text_field($atts['shape']);
    }
    if ($atts['color_type']) {
        $classes[] = 'color-' . sanitize_text_field($atts['color_type']);
    }
    if (!empty($atts['align'])) {
        $classes[] = 'element-align-'.esc_attr($atts['align']);
    }
    ?>
    <ul <?php $this->element_class($classes)?>>
        <?php

        foreach (( array )$atts['items'] as $index => $item) {
            $social_class = 'social-icon '.$shape;
            $item = wp_parse_args($item, array(
                'title' => '',
                'icon' => '',
                'url' => '',
                '_visibility' => ''
            ));

            if ($item['_visibility'] !== 'hidden') {
                ?>
                <li class="social-icon-item">
                <?php
                if (!$item['url']) {
                    $item['url'] = '#';
                }

                $icon = wp_parse_args($item['icon'], array(
                    'type' => '',
                    'icon' => '',
                ));

                if ($item['url'] && $icon['icon']) {
                   $social_class.=' '.str_replace( array( 'cs-font', 'clever-' ), array( '', '' ), esc_attr( $icon['icon'] ))
                    ?>
                    <a class="<?php echo esc_attr($social_class);?>" rel="<?php echo esc_attr($rel); ?>" target="<?php echo esc_attr($target) ?>" href="<?php echo esc_url($item['url']) ?>" title="<?php echo esc_attr($item['title']) ?>">
                <?php }
                if ($icon['icon']) { ?>
                    <i class="icon <?php echo esc_attr($icon['icon']) ?>"></i>
                    <?php
                }

                if ($item['url']) { ?>
                    </a>
                    <?php
                }
            }
            ?>
            </li>
            <?php
        }
        ?></ul>
    <?php
}
