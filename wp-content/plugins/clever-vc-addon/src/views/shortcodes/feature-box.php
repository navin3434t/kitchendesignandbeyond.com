<?php
/**
 * Feature box Shortcode
 */
wp_enqueue_style('cvca-style');

$zoo_start_link = $zoo_end_link = $zoo_link_text = $css_class = '';

$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverFeatureBox', $atts );

if ( ! empty($custom_class) ) {
    $css_class .= ' ' . $custom_class;
}

if ( ! empty($atts['type']) ) {
    $css_class .= ' ' . $atts['type'] . '-type ';
}

if ( ! empty($atts['style']) ) {
    $css_class .= ' ' . $atts['style'] . '-style';
}

if ( ! empty($atts['align']) ) {
    $css_class .= ' ' . $atts['align'];
}

if ( ! empty($atts['el_class']) ) {
    $css_class .= ' ' . $atts['el_class'];
}

$zoo_allow_tag = array(
    'a' => array(
        'href' => array(),
        'target' => array(),
        'rel' => array(),
        'title' => array()
    ),
    'br' => array()
);

if ( !empty( $atts['link'] ) ) {
    $zoo_link = vc_build_link( $atts['link'] );

    if ( $zoo_link['url'] != '' ) {
        $zoo_start_link = '<a';
        $zoo_start_link .= ' class="banner-media-link"';
        $zoo_start_link .= ' href="' . $zoo_link['url'] . '"';

        if ( $zoo_link['title'] != '' ) {
            $zoo_start_link .= ' title="' . $zoo_link['title'] . '"';
        }

        if ( $zoo_link['target'] != '' ) {
            $zoo_start_link .= ' target="' . $zoo_link['target'] . '"';
        }

         if ( $zoo_link['rel'] != '' ) {
            $zoo_start_link .= ' rel="' . $zoo_link['rel'] . '"';
        }

        $zoo_start_link .= '>';
    }

    $zoo_link_text = ( $zoo_link['title'] != '' ) ? $zoo_link['title'] : '';

    if ( $zoo_link['url'] != '' ) {
        $zoo_end_link = '</a>';
    }
}

/* Start $media_style */
$media_style = ' style="display: inline-block;';

if ( !empty( $atts['media_color'] ) ) {
    $media_style .= 'color:' . $atts['media_color'] . ';';
}

if ( !empty( $atts['media_bg_color'] ) ) {
    $media_style .= 'background-color:' . $atts['media_bg_color'] . ';';
}

$media_style .= '"';

/* End $media_style */

/* Start Box Style */
$box_style = ' style="';

if ( $atts['box_bg_type'] === 'color' && !empty( $atts['box_bg_color'] ) ) {
    $box_style .= 'display: table;background-color:' . $atts['box_bg_color'] . ';' ;
}

if ( $atts['box_bg_type'] === 'image' && !empty( $atts['box_bg_image'] ) ) {

    $box_style .= 'background-image:url(' . wp_get_attachment_image_url( $atts['box_bg_image'], 'full' ) . ');';

    if ( !empty( $atts['box_bg_repeat'] ) ) {
        $box_style .= 'background-repeat:' . $atts['box_bg_repeat'] . ';';
    }

    if ( !empty( $atts['box_bg_size'] ) ) {
        $box_style .= 'background-size:' . $atts['box_bg_size'] . ';';
    }

    if ( !empty( $atts['box_bg_position'] ) ) {
        $box_style .= 'background-position:' . $atts['box_bg_position'] . ';';
    }

    if ( !empty( $atts['box_bg_attachment'] ) ) {
        $box_style .= 'background-attachment:' . $atts['box_bg_attachment'] . ';' ;
    }
}

$box_style .= '"';
/* End Box Style */

$icon = '';
switch ( $atts['icon_type'] ) {
    case 'fontawesome':
        $icon = $atts['icon_fontawesome'];
        wp_enqueue_style( 'font-awesome' );
        break;

    case 'openiconic':
        $icon = $atts['icon_openiconic'];
        wp_enqueue_style( 'vc_openiconic' );
        break;

    case 'typicons':
        $icon = $atts['icon_typicons'];
        wp_enqueue_style( 'vc_typicons' );
        break;

    case 'entypo':
        $icon = $atts['icon_entypo'];
        wp_enqueue_style( 'vc_entypo' );
        break;

    case 'linecons':
        wp_enqueue_style( 'vc_linecons' );
        $icon = $atts['icon_linecons'];
        break;

    case 'monosocial':
        $icon = $atts['icon_monosocial'];
        wp_enqueue_style( 'vc_monosocialiconsfont' );
        break;

    case 'material':
        $icon = $atts['icon_material'];
        wp_enqueue_style( 'vc_material' );
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
?>
<div class="zoo-feature-box<?php echo esc_attr($css_class); ?>"<?php echo $box_style; ?>>
    <div class="zoo-feature-box-inner">
    <?php
    if ($atts['type'] != '') {
        switch ($atts['style']) {
            case 'before':
            case 'after': ?>
                <?php if ( $atts['title'] != '' && $atts['style'] == 'after' ) : ?>
                    <h3 class="feature-box-title">
                        <?php
                        echo wp_kses($zoo_start_link, $zoo_allow_tag);
                        echo esc_html($atts['title']);
                        echo wp_kses($zoo_end_link, $zoo_allow_tag);
                        ?>
                    </h3>
                <?php endif; ?>

                <?php if ( ( $atts['type'] !== 'text' ) && ( !empty($icon) || !empty($atts['image']) ) ) : ?>
                <div class="feature-box-media"<?php echo $media_style; ?>>
                    <?php if ($atts['type'] == 'icon') : ?>
                        <i class="circus-box <?php echo esc_attr($icon) ?>" style="font-size: <?php echo $atts['icon_fontsize']; ?>"></i>
                    <?php endif; ?>

                    <?php if ($atts['type'] == 'image') {
                        echo wp_get_attachment_image($atts['image'], 'full');
                    } ?>
                </div>
                <?php endif; ?>

                <?php if ( $atts['title'] != '' && $atts['style'] == 'before' ) : ?>
                    <h3 class="feature-box-title">
                        <?php
                        echo wp_kses($zoo_start_link, $zoo_allow_tag);
                        echo esc_html($atts['title']);
                        echo wp_kses($zoo_end_link, $zoo_allow_tag);
                        ?>
                    </h3>
                <?php endif; ?>

                <?php if ( ! empty($content) ) : ?>
                    <div class="feature-box-content">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $atts['link'] != '' && $zoo_link_text != '' ) {
                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                    echo esc_html($zoo_link_text);
                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                 }
                break;

            case 'left':
            case 'right': ?>
                <div class="media">
                    <?php if ( $atts['style'] == 'left' && ( $atts['type'] !== 'text' ) && ( !empty($icon) || !empty($atts['image']) ) ) : ?>
                    <div class="pull-left">
                        <span class="feature-box-media"<?php echo $media_style; ?>>
                            <?php if ($atts['type'] == 'icon') : ?>
                                <i class="circus-box <?php echo esc_attr($icon) ?>" style="font-size: <?php echo $atts['icon_fontsize']; ?>"></i>
                            <?php endif; ?>

                            <?php if ($atts['type'] == 'image') {
                                echo wp_get_attachment_image($atts['image'], 'full');
                            } ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="media-body">
                        <?php if ($atts['title'] != '') : ?>
                            <h3 class="feature-box-title">
                                <?php
                                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                    echo esc_html($atts['title']);
                                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ( ! empty($content) ) : ?>
                            <div class="feature-box-content">
                                <?php echo $content; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($atts['link'] != '' && $zoo_link_text != '') {
                            echo wp_kses($zoo_start_link, $zoo_allow_tag);
                            echo esc_html($zoo_link_text);
                            echo wp_kses($zoo_end_link, $zoo_allow_tag);
                        } ?>
                    </div>
                    <?php if ( $atts['style'] == 'right' && ( $atts['type'] !== 'text' ) && ( !empty($icon) || !empty($atts['image']) ) ) : ?>
                    <div class="pull-right">
                        <span class="feature-box-media"<?php echo $media_style; ?>>
                            <?php if ($atts['type'] == 'icon') : ?>
                                <i class="circus-box <?php echo esc_attr($icon) ?>" style="font-size: <?php echo $atts['icon_fontsize']; ?>"></i>
                            <?php endif; ?>

                            <?php if ($atts['type'] == 'image') {
                                echo wp_get_attachment_image($atts['image'], 'full');
                            } ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php
                break;
            default: ?>
                <div class="media">
                    <?php if ( $atts['style'] == 'left-inline' && ( $atts['type'] !== 'text' ) && ( !empty($icon) || !empty($atts['image']) ) ) : ?>
                    <div class="pull-left">
                        <span class="feature-box-media"<?php echo $media_style; ?>>
                            <?php if ($atts['type'] == 'icon') : ?>
                                <i class="circus-box <?php echo esc_attr($icon) ?>" style="font-size: <?php echo $atts['icon_fontsize']; ?>"></i>
                            <?php endif; ?>

                            <?php if ($atts['type'] == 'image') {
                                echo wp_get_attachment_image($atts['image'], 'full');
                            } ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="media-body">
                        <?php if ($atts['title'] != '') : ?>
                            <h3 class="feature-box-title">
                                <?php
                                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                    echo esc_html($atts['title']);
                                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                ?>
                            </h3>
                        <?php endif; ?>
                    </div>

                    <?php if ( $atts['style'] == 'right-inline' && ( $atts['type'] !== 'text' ) && ( !empty($icon) || !empty($atts['image']) ) ) : ?>
                    <div class="pull-right">
                        <span class="feature-box-media"<?php echo $media_style; ?>>
                            <?php if ($atts['type'] == 'icon') : ?>
                                <i class="circus-box <?php echo esc_attr($icon) ?>" style="font-size: <?php echo $atts['icon_fontsize']; ?>"></i>
                            <?php endif; ?>

                            <?php if ($atts['type'] == 'image') {
                                echo wp_get_attachment_image($atts['image'], 'full');
                            } ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ( ! empty($content) ) : ?>
                    <div class="feature-box-content">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>

                <?php if ($atts['link'] != '' && $zoo_link_text != '') {
                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                    echo '<span>' . esc_html($zoo_link_text) . '</span>';
                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                } ?>
                <?php
                break;
        }
    }
    ?>
    </div>
</div><?php //End view
