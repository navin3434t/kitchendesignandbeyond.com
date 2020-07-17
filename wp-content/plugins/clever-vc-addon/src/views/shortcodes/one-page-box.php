<?php
/**
 * Parallax Box Shortcode
 */
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), 'CleverOnePageBox', $atts);
$icon = '';
if($atts['show_icon']==1) {
    switch ($atts['icon_type']) {
        case 'fontawesome':
            $icon = $atts['icon_fontawesome'];
            break;

        case 'openiconic':
            $icon = $atts['icon_openiconic'];
            break;

        case 'typicons':
            $icon = $atts['icon_typicons'];
            break;

        case 'entypo':
            $icon = $atts['icon_entypo'];
            break;

        case 'linecons':
            $icon = $atts['icon_linecons'];
            break;

        case 'monosocial':
            $icon = $atts['icon_monosocial'];
            break;

        case 'material':
            $icon = $atts['icon_material'];
            break;

        case 'cleverfont':
            $icon = $atts['icon_cleverfont'];
            break;

        case 'strokegap':
            $icon = $atts['icon_strokegap'];
            break;

        default:
            $icon = '';
            break;
    }
}
wp_enqueue_script('cvca-script');
wp_enqueue_style('cvca-style');
$css_class .= $atts['full_height']=='1'?' full-height':'';
$css_class .= $atts['jump']=='1'?' jump-scroll':'';
?>
<div id="cvca-one-page-<?php echo esc_attr(uniqid()); ?>"
     class="cvca-one-page <?php echo esc_attr($css_class); ?>"
     data-title="<?php echo esc_attr($atts['title']) ?>"
     data-icon="<?php echo esc_attr($icon) ?>"
     data-preset="<?php echo esc_attr($atts['preset_color']) ?>">
    <?php if ($atts['show_title']) { ?>
        <h3 class="cvca-title-one-page"
            <?php if ($atts['preset_color'] != ''){ ?>style="background:<?php echo esc_attr($atts['preset_color']) ?>"<?php } ?>><?php echo esc_html($atts['title']); ?></h3>
    <?php }
    echo do_shortcode($content); ?>
</div>
