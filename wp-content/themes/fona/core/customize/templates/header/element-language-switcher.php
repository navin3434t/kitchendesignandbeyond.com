<?php
/**
 * Template for Language Switcher element.
 *
 * Template of `core/customize/builder/elements/language-switcher.php`
 */

$languages = [];
$text = $atts['text'];
$icon = $atts['icon'];
$icon_pos = $atts['position'];
$el_html_classes = [];

if (!empty($atts['align'])) {
    $el_html_classes[] = 'element-align-'.esc_attr($atts['align']);
}

$icon = wp_parse_args($icon, array(
    'type' => '',
    'icon' => ''
));

$icon_html = '';

if ($icon['icon']) {
    $icon_html = '<i class="' . esc_attr($icon['icon']) . '"></i> ';
}

if (!$text) {
    $text = esc_html__('Languages', 'fona');
}

if (function_exists('pll_the_languages')) { // Polylang active.
    $languages = pll_the_languages(['raw' => 1]);
    foreach ($languages as $lang) {
        if ($lang['current_lang']) {
            $icon_html = '<i class="image-icon"><img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . '"/></i>';
            $text = $lang['name'];
        }
    }
} elseif (function_exists('icl_get_languages')) { // WPML active.
    $languages = icl_get_languages();
    foreach ($languages as $lang) {
        if ($lang['active']) {
            $icon_html = '<i class="image-icon"><img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang['native_name']) . '"/></i>';
            $text = $lang['native_name'];
        }
    }
}

$allow_html=array('i'=>array('class'=>array()),'img'=>array('src'=>array(),'alt'=>array()));
?>
    <div id="element-language-switcher-<?php echo esc_attr($atts['device'])?>" <?php $this->element_class($el_html_classes); ?>>
        <span class="language-options">
            <?php
                if ($icon_pos != 'after') {
                    echo wp_kses($icon_html,$allow_html) . esc_html($text);
                } else {
                    echo esc_html($text) . wp_kses($icon_html,$allow_html) ;
                }
            ?>
        </span>
            <ul id="language-switcher-available-languages-<?php echo esc_attr($atts['device'])?>" class="list-languages">
                <?php
                if ($languages && function_exists('pll_the_languages')) {
                    foreach ($languages as $lang) {
                        echo '<li><a href="' . esc_url($lang['url']) . '" hreflang="' . esc_url($lang['slug']) . '" title="' . esc_attr($lang['name']) . '"><i class="icon-image"><img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . '"/></i> ' . esc_html($lang['name']) . '</a></li>';
                    }
                } elseif ($languages && function_exists('icl_get_languages')) {
                    foreach ($languages as $lang) {
                        echo '<li><a href="' . esc_url($lang['url']) . '" hreflang="' . esc_url($lang['language_code']) . '"  title="' . esc_attr($lang['native_name']) . '"><i class="icon-image"><img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang['native_name']) . '"/></i> ' . esc_html($lang['native_name']) . '</a></li>';
                    }
                }
                if (!function_exists('pll_the_languages') && !function_exists('icl_get_languages')) {
                    ?>
                    <li>
                        <?php esc_html_e('Please activate Polylang or WPML plugin to show available languages.', 'fona');?>
                    </li>
                    <?php
                }
                ?>
            </ul>
    </div>
<?php
