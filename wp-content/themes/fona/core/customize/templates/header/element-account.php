<?php
/**
 * Template for Account element.
 *
 * Template of `core/customize/builder/elements/account.php`
 */
global $wp;

$user_logged_in = is_user_logged_in();

$html_classes = [];

if ($atts['advanced-styling']) {
    $html_classes[] = 'custom-color-style';
} else {
    $html_classes[] = 'default-color-style';
}

$html_classes[] = $atts['links-position'] . '-position ';
$html_classes[] = $atts['style'] . '-style ';

if (!empty($atts['align'])) {
    $html_classes[] = 'element-align-' . esc_attr($atts['align']);
}

$icon = $atts['icon'];
if ($atts['layout-type']=='modal') {
    $html_classes[] = 'control-login-popup';
}
$html_classes[]=$atts['layout-type'];
$show_label = $atts['show-label'];
$label_text = esc_html__('My account', 'fona');

if ($show_label && !empty($atts['label'])) {
    $label_text = $atts['label'];
}

if (class_exists('WooCommerce', false)) {
    $myacc_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
} elseif (!empty($atts['custom_dashboard_url'])) {
    $myacc_url = $atts['custom_dashboard_url'];
} else {
    if ($user_logged_in) {
        $myacc_url = get_edit_profile_url();
    } else {
        $myacc_url = wp_login_url(home_url($wp->request));
    }
}

?>
<div id="element-header-account-<?php echo esc_attr($atts['device']) ?>" <?php $this->element_class($html_classes); ?>>
    <?php
    if ($user_logged_in) {
        ?>
        <a class="account-element-link account-url" href="<?php echo esc_url($myacc_url); ?>">
            <?php
            if ($icon['icon'] != '') {
                echo '<span class="account-icon"><i class="' . esc_attr($icon['icon']) . '"></i></span>';
            }
            if ($show_label) {
                echo esc_html($label_text);
            }
            ?>
        </a>
        <?php
        if (class_exists('WooCommerce', false)) {
            if ($user_logged_in && !is_account_page()) {
                ?>
                <div class="wrap-dashboard-form">
                    <nav class="woocommerce-MyAccount-navigation">
                        <ul>
                            <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                                <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="wrap-dashboard-form">
                <nav class="woocommerce-MyAccount-navigation">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url($myacc_url) ?>"><?php esc_html_e('My Dashboard', 'fona') ?></a>
                        </li>
                        <?php if (!empty($atts['link_1']['url']) && '#' != $atts['link_1']['url']) { ?>
                            <li>
                                <a href="<?php echo esc_url($atts['link_1']['url']) ?>"><?php echo esc_html($atts['link_1']['label']) ?></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($atts['link_2']['url']) && '#' != $atts['link_2']['url']) { ?>
                            <li>
                                <a href="<?php echo esc_url($atts['link_2']['url']) ?>"><?php echo esc_html($atts['link_2']['label']) ?></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($atts['link_3']['url']) && '#' != $atts['link_3']['url']) { ?>
                            <li>
                                <a href="<?php echo esc_url($atts['link_3']['url']) ?>"><?php echo esc_html($atts['link_3']['label']) ?></a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="<?php echo esc_url(wp_logout_url(home_url($wp->request))) ?>"><?php esc_html_e('Log Out', 'fona') ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php
        }
    } else {
        $login_url = !empty($atts['custom_login_url']) ? $atts['custom_login_url'] : wp_login_url(home_url($wp->request));
        $register_url = !empty($atts['custom_register_url']) ? $atts['custom_register_url'] : wp_registration_url();
        if ($atts['style'] == 'normal') {
            ?>
            <a class="account-element-link" href="<?php echo esc_url($myacc_url); ?>">
                <?php
                if ($icon['icon'] != '') {
                    echo '<span class="account-icon"><i class="' . esc_attr($icon['icon']) . '"></i></span>';
                }
                if ($show_label) {
                    echo esc_html($label_text);
                }
                ?>
            </a>
            <?php if ($atts['layout-type']=='link') { ?>
                <div class="wrap-dashboard-form">
                    <nav class="woocommerce-MyAccount-navigation">
                        <ul>
                            <li><a href="<?php echo esc_url($login_url) ?>" class="account-login-url"
                                   title="<?php esc_attr_e('Log in', 'fona') ?>"><?php esc_html_e('Log in', 'fona') ?></a>
                            </li>
                            <li><a href="<?php echo esc_url($register_url) ?>" class="account-register-url"
                                   title="<?php esc_attr_e('Register', 'fona') ?>"><?php esc_html_e('Register', 'fona') ?></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php
            }
        } else {
            ?>
            <a href="<?php echo esc_url($login_url) ?>" class="account-element-link account-login-url"
               title="<?php esc_attr_e('Log in', 'fona') ?>"><?php esc_html_e('Log in', 'fona') ?></a>
            <a href="<?php echo esc_url($register_url) ?>" class="account-element-link account-register-url"
               title="<?php esc_attr_e('Register', 'fona') ?>"><?php esc_html_e('Register', 'fona') ?></a>
            <?php
        }
    }
    ?>
</div>
