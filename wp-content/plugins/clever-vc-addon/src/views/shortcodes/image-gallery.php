<?php
/**
 * Image Gallery Shortcode
 */
$cvca_wrap_class = $atts['el_class'].' '.$atts['layout'].'-layout';
if($atts['shadow']){
    $cvca_wrap_class.=' shadow';
}
$cvca_content = vc_param_group_parse_atts($atts['images']);
$cvca_start_link = $cvca_end_link = '';
$cvca_allow_tag = array(
    'a' => array(
        'href' => array(),
        'target' => array(),
        'rel' => array(),
        'title' => array()
    ),
    'br' => array()
);
$pagination = $atts['show_pag'] ? "true" : "false";
$navigation = $atts['show_nav'] ? "true" : "false";
$center_mod="false";
if(isset($atts['center_mod'])){
    $center_mod = $atts['center_mod'] ? "true" : "false";
}
$auto_play = '';
if(isset($atts['auto_play'])){
    $auto_play = $atts['auto_play'];
}
wp_enqueue_style('slick');
wp_enqueue_style('cvca-style');
wp_enqueue_script('slick');
wp_enqueue_script('cvca-script');
?>
<div class="cvca-wrap_sc_images_gallery cvca-carousel <?php echo esc_attr($cvca_wrap_class) ?>" data-config='{"item":"<?php echo esc_attr($atts['columns'])?>","pagination":"<?php echo esc_attr($pagination)?>","navigation":"<?php echo esc_attr($navigation)?>","center_mod":"<?php echo esc_attr($center_mod)?>","auto_play":"<?php echo esc_attr($auto_play)?>","layout":"<?php echo esc_attr($atts['layout'])?>"}'>
    <?php foreach ($cvca_content as $cvca_item) { ?>
        <div class="image_gallery_item"><?php
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
            echo wp_kses($cvca_end_link, $cvca_allow_tag);
            ?>
        </div>
    <?php } ?>
</div>