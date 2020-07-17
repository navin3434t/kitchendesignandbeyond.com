<?php
/**
 * Follow me Shortcode
 */
$follow_mes = vc_param_group_parse_atts($atts['follow-me']);
$html='';
foreach ($follow_mes as $fl) {
    $html.='<a href="'.(vc_build_link( $fl['socail-link'] )['url']==''?'#':vc_build_link( $fl['socail-link'] )['url']).'" title="'.vc_build_link( $fl['socail-link'] )['title'].'" target="'.vc_build_link( $fl['socail-link'] )['target'].'">';
    $html.='<i class="'.$fl['socail-icon'].'"></i>';
    $html .='</a>';
}
$allowhtml=array(
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array()
    ),
    'i' => array('class' => array()),
);
$id = 'cvca-follow-me-' . uniqid();
 
?><div id="<?php echo esc_attr($id);?>" class="cvca-follow-me <?php echo esc_attr($atts['style']);?>">
    <?php if($atts['title']!=''){?>
        <h3 class="title-shortcode">
            <?php echo esc_html($atts['title'])?>
        </h3>
    <?php }?>
    <div class="cvca-follow-me-content">
        <?php
            echo wp_kses($html,$allowhtml);
        ?>
    </div>
</div><?php //End view
