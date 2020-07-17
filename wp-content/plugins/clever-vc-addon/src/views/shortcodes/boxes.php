<?php
/**
 * Feature box Shortcode
 */
$zoo_start_link = $zoo_end_link = $zoo_link_text = $css_class = $media_style = $box_style = $box_class = $box_col = '';

$custom_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), 'CleverBoxes', $atts );

if ( ! empty($custom_class) ) {
    $css_class .= ' ' . $custom_class;
}

if ( ! empty($atts['el_class']) ) {
    $css_class .= ' ' . $atts['el_class'];
}

if ( ! empty($atts['desktop_boxes_cols']) ) {    
    $box_col .= ' desktop-' . $atts['desktop_boxes_cols'] . '-col';
}

if ( ! empty($atts['desktopsmall_boxes_cols']) ) {    
    $box_col .= ' desktopsmall-' . $atts['desktopsmall_boxes_cols'] . '-col';
}

if ( ! empty($atts['tablet_boxes_cols']) ) {    
    $box_col .= ' tablet-' . $atts['tablet_boxes_cols'] . '-col';
}

if ( ! empty($atts['tabletsmall_boxes_cols']) ) {    
    $box_col .= ' tabletsmall-' . $atts['tabletsmall_boxes_cols'] . '-col';
}

if ( ! empty($atts['mobile_boxes_cols']) ) {    
    $box_col .= ' mobile-' . $atts['mobile_boxes_cols'] . '-col';
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

$box_width = $box_height = '';

if ( ! empty($atts['min_height']) ) {
    $box_height = 'height: ' . $atts['min_height'] . 'px;';
}
wp_enqueue_style( 'font-awesome' );
?>
<div class="zoo-boxes-shortcode<?php echo esc_attr($css_class); ?>" >
    <?php if ( !empty( $atts['boxes_title'] ) ) : ?>
    <h3 class="boxes-title">
        <?php echo esc_html($atts['boxes_title']); ?>
    </h3>
    <?php endif; ?>
    <div class="boxes-content box-cols">
        <?php 
        if ( !empty($atts['boxes']) ) :
            $boxes = vc_param_group_parse_atts($atts['boxes']);
                   
            foreach ( $boxes as $box ) : ?>
            <?php
                if ( !empty( $box['link'] ) ) {
                    $zoo_link = vc_build_link( $box['link'] );

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

                if ( ! empty($atts['type']) ) {
                    $box_class .= ' ' . $box['type'] . '-type ';
                }

                if ( ! empty($atts['style']) ) {
                    $box_class .= ' ' . $box['style'] . '-style';
                }

                if ( ! empty($atts['align']) ) {
                    $box_class .= ' ' . $box['align'];
                }

                /* Start $media_style */
                $media_style .= ' style="display: inline-block;';

                if ( !empty( $box['media_color'] ) ) {
                    $media_style .= 'color:' . $box['media_color'] . ';';
                }

                if ( !empty( $box['media_bg_color'] ) ) {
                    $media_style .= 'background-color:' . $box['media_bg_color'] . ';';
                }

                $media_style .= '"';

                /* Start Box Style */
                $box_style .= ' style="' . $box_height;
                
                if ( $box['box_bg_type'] != 'none' && !empty( $box['box_color'] ) ) {
                    $box_style .= 'color:' . $box['box_color'] . ';' ;
                }

                if ( $box['box_bg_type'] === 'color' && !empty( $box['box_bg_color'] ) ) {
                    $box_style .= 'display: table;background-color:' . $box['box_bg_color'] . ';' ;
                }

                if ( $box['box_bg_type'] === 'image' && !empty( $box['box_bg_image'] ) ) {

                    $box_style .= 'background-image:url(' . wp_get_attachment_image_url( $box['box_bg_image'], 'full' ) . ');';

                    if ( !empty( $box['box_bg_repeat'] ) ) {
                        $box_style .= 'background-repeat:' . $box['box_bg_repeat'] . ';';
                    }

                    if ( !empty( $box['box_bg_size'] ) ) {
                        $box_style .= 'background-size:' . $box['box_bg_size'] . ';';
                    }

                    if ( !empty( $box['box_bg_position'] ) ) {
                        $box_style .= 'background-position:' . $box['box_bg_position'] . ';';
                    }

                    if ( !empty( $box['box_bg_attachment'] ) ) {
                        $box_style .= 'background-attachment:' . $box['box_bg_attachment'] . ';' ;
                    }
                }

                $box_style .= '"';
                /* End Box Style */
            ?>
            <div class="zoo-box box-col<?php echo $box_col . $box_class; ?>"<?php echo $box_style; ?>>
                <div class="zoo-box-inner">
                <?php
                if ($box['type'] != '') {
                    switch ($box['style']) {
                        case 'before':
                        case 'after': ?>
                            <?php if ( !empty($box['title']) && $box['style'] == 'after' ) : ?>
                                <h4 class="box-title">
                                    <?php
                                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                    echo esc_html($box['title']);
                                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                    ?>
                                </h4>
                            <?php endif; ?>

                            <div class="box-media"<?php echo $media_style; ?>>
                                <?php if (!empty($box['type']) && $box['type'] == 'icon') : ?>
                                    <i class="circus-box <?php echo esc_attr($box['icon']) ?>" style="font-size: <?php echo $box['icon_fontsize']; ?>"></i>
                                <?php endif; ?>

                                <?php if (!empty($box['type']) && $box['type'] == 'image') {
                                    echo wp_get_attachment_image($box['image'], 'full');
                                } ?>
                            </div>

                            <?php if ( !empty($box['title']) && $box['style'] == 'before' ) : ?>
                                <h4 class="box-title">
                                    <?php
                                    echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                    echo esc_html($box['title']);
                                    echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                    ?>
                                </h4>
                            <?php endif; ?>

                            <?php if ( ! empty($box['description']) ) : ?>
                                <div class="box-content">
                                    <?php echo $box['description']; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ( $box['link'] != '' && $zoo_link_text != '' ) {
                                echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                echo esc_html($zoo_link_text);
                                echo wp_kses($zoo_end_link, $zoo_allow_tag);
                             }
                            break;

                        case 'left':
                        case 'right': ?>
                            <div class="media">
                                <?php if ( $box['style'] == 'left' ) : ?>
                                <div class="pull-left">
                                    <span class="box-media"<?php echo $media_style; ?>>
                                        <?php if ($box['type'] == 'icon') : ?>
                                            <i class="circus-box <?php echo esc_attr($box['icon']) ?>" style="font-size: <?php echo $box['icon_fontsize']; ?>"></i>
                                        <?php endif; ?>

                                        <?php if ($box['type'] == 'image') {
                                            echo wp_get_attachment_image($box['image'], 'full');
                                        } ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <div class="media-body">
                                    <?php if ( !empty($box['title']) ) : ?>
                                        <h4 class="box-title">
                                            <?php
                                                echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                                echo esc_html($box['title']);
                                                echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                            ?>
                                        </h4>
                                    <?php endif; ?>

                                    <?php if ( ! empty($box['description']) ) : ?>
                                        <div class="box-content">
                                            <?php echo $box['description']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($box['link'] != '' && $zoo_link_text != '') {
                                        echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                        echo esc_html($zoo_link_text);
                                        echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                    } ?>
                                </div>
                                <?php if ( $box['style'] == 'right' ) : ?>
                                <div class="pull-right">
                                    <span class="box-media"<?php echo $media_style; ?>>
                                        <?php if ($box['type'] == 'icon') : ?>
                                            <i class="circus-box <?php echo esc_attr($box['icon']) ?>" style="font-size: <?php echo $box['icon_fontsize']; ?>"></i>
                                        <?php endif; ?>

                                        <?php if ($box['type'] == 'image') {
                                            echo wp_get_attachment_image($box['image'], 'full');
                                        } ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php
                            break;
                        default: ?>
                            <div class="media">
                                <?php if ( $box['style'] == 'left-inline' ) : ?>
                                <div class="pull-left">
                                    <span class="box-media"<?php echo $media_style; ?>>
                                        <?php if ($box['type'] == 'icon') : ?>
                                            <i class="circus-box <?php echo esc_attr($box['icon']) ?>" style="font-size: <?php echo $box['icon_fontsize']; ?>"></i>
                                        <?php endif; ?>

                                        <?php if ($box['type'] == 'image') {
                                            echo wp_get_attachment_image($box['image'], 'full');
                                        } ?>
                                    </span>
                                </div>
                                <?php endif; ?>

                                <div class="media-body">
                                    <?php if ( !empty($box['title']) ) : ?>
                                        <h4 class="box-title">
                                            <?php
                                                echo wp_kses($zoo_start_link, $zoo_allow_tag);
                                                echo esc_html($box['title']);
                                                echo wp_kses($zoo_end_link, $zoo_allow_tag);
                                            ?>
                                        </h4>
                                    <?php endif; ?>
                                </div>

                                <?php if ( $box['style'] == 'right-inline' ) : ?>
                                <div class="pull-right">
                                    <span class="box-media"<?php echo $media_style; ?>>
                                        <?php if ($box['type'] == 'icon') : ?>
                                            <i class="circus-box <?php echo esc_attr($box['icon']) ?>" style="font-size: <?php echo $box['icon_fontsize']; ?>"></i>
                                        <?php endif; ?>

                                        <?php if ($box['type'] == 'image') {
                                            echo wp_get_attachment_image($box['image'], 'full');
                                        } ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( ! empty($box['description']) ) : ?>
                                <div class="box-content">
                                    <?php echo $box['description']; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($box['link'] != '' && $zoo_link_text != '') {
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
            </div>
            <?php $zoo_start_link = $zoo_end_link = $zoo_link_text = $media_style = $box_style = $box_class = ''; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

