<?php
/**
 * Links Shortcode
 */
$css_class = '';

if ( !empty( $atts['el_class'] ) ) {
    $css_class .= ' ' . $atts['el_class'];
}

$links = vc_param_group_parse_atts($atts['links']);
$html='';
foreach ($links as $fl) {
    $html.='<a href="'.(vc_build_link( $fl['socail-link'] )['url']==''?'#':vc_build_link( $fl['socail-link'] )['url']).'" title="'.vc_build_link( $fl['socail-link'] )['title'].'" target="'.vc_build_link( $fl['socail-link'] )['target'].'">';
    if (!empty($fl['socail-icon'])) {
        $html.='<i class="'.$fl['socail-icon'].'"></i>';
    }
    if (!empty($fl['socail-link'])) {
        $html.='<span class="link-text">' . vc_build_link( $fl['socail-link'] )['title'] . '</span>';
    }
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
$id = 'cvca-links-' . uniqid();
 
?><div id="<?php echo esc_attr($id);?>" class="cvca-links<?php echo esc_attr( $css_class ); ?>">
    <?php if($atts['title']!=''){?>
        <h3 class="title-shortcode">
            <?php echo esc_html($atts['title'])?>
        </h3>
    <?php }?>
    <div class="cvca-links-content">
        <?php
            echo wp_kses($html,$allowhtml);
        ?>
    </div>
</div><?php //End view
