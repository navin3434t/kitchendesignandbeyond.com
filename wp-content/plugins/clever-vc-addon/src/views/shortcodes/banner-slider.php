<?php
/**
 * Image Gallery Shortcode
 */
$cvca_wrap_class = $atts['el_class'];
$cvca_content = vc_param_group_parse_atts($atts['banners']);
$cvca_start_link = $cvca_end_link = '';
$cvca_allow_tag = array(
    'a' => array(
        'href' => array(),
        'target' => array(),
        'rel' => array(),
        'title' => array()
    )
);
$pagination = $atts['show_pag'] ? "true" : "false";
$navigation = $atts['show_nav'] ? "true" : "false";
wp_enqueue_style('slick');
wp_enqueue_style('cvca-style');
wp_enqueue_script('slick');
wp_enqueue_script('cvca-script');
?>
<div class="cvca-banner-slider cvca-carousel <?php echo esc_attr($cvca_wrap_class) ?>" data-config='{"item":"<?php echo esc_attr($atts['columns'])?>","pagination":"<?php echo esc_attr($pagination)?>","navigation":"<?php echo esc_attr($navigation)?>"}'>
    <?php foreach ($cvca_content as $cvca_item) { ?>
        <div class="cvca-banner-slider-item">
            <div class="cvca-wrap-banner-slider-item">
            <?php
            $cvca_title=isset($cvca_item['title'])?$cvca_item['title']:'';
            $cvca_desc=isset($cvca_item['desc'])?$cvca_item['desc']:'';
            $cvca_bg=isset($cvca_item['bg_color'])?$cvca_item['desc']:'';
            $cvca_color=isset($cvca_item['text_color'])?$cvca_item['text_color']:'';
            if (isset($cvca_item['link']) && $cvca_item['link'] != '') {
                $cvca_link = vc_build_link($cvca_item['link']);
                $cvca_link_title= $cvca_link['title']!=''? ' title="'.$cvca_link['title'].'"':'';
                $cvca_link_target= $cvca_link['target']!=''? ' target="'.$cvca_link['target'].'"':'';
                $cvca_link_rel= $cvca_link['rel']!=''? ' rel="'.$cvca_link['rel'].'"':'';
                $cvca_start_link = '<a href="' . $cvca_link['url'] . '"'.$cvca_link_title.$cvca_link_target.$cvca_link_rel.'>';
                $cvca_end_link = '</a>';
            }
            echo wp_kses($cvca_start_link, $cvca_allow_tag);
            echo wp_get_attachment_image($cvca_item['image'], 'full');
            if($cvca_desc!=''&&$cvca_title!='') {
                ?>
                <div class="cvca-banner-slider-content" style="background:<?php echo esc_attr($cvca_bg)?>; color:<?php echo esc_attr($cvca_color);?>">
                    <?php
                    if($cvca_title!=''){
                        ?>
                        <h3 class="cvca-banner-slider-title"><?php echo esc_html($cvca_title)?></h3>
                        <?php
                    }
                    if($cvca_desc!=''){
                        ?>
                        <div class="descriptions"><?php echo esc_html($cvca_desc)?></div>
                    <?php
                    }
                    ?>
                </div>
                <?php
            }
            echo wp_kses($cvca_end_link, $cvca_allow_tag);
            ?>
            </div>
        </div>
    <?php } ?>
</div>