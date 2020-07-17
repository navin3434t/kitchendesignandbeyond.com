<?php

/**
 * Zoo_Customize_Frontend_Builder
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Builder
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Frontend_Builder
{
    private $control_id = 'header_builder_panel';
    public $id = 'header';
    private $rows = [];
    private $data = false;
    private $config_items = false;
    private $is_preview;
    private $zoo_customizer;
    private $cartEnabled = false;
    private $accountEnabled = false;
    private $searchBoxEnabled = false;

    /**
     * Nope constructor
     */
    private function __construct(Zoo_Customizer $zoo_customizer)
    {
        $this->zoo_customizer = $zoo_customizer;
        $this->is_preview = is_customize_preview();
    }

    /**
     * Singleton
     */
    public static function get_instance()
    {
        static $self = null;

        if (null === $self) {
            $self = new self(Zoo_Customizer::get_instance());
        }

        return $self;
    }

    /**
     * Set current block ID and reset the data
     */
    public function set_id($id)
    {
        $this->id = $id;
        $this->data = null;
    }

    /**
     *  Set current block control ID and reset the data
     */
    public function set_control_id($id)
    {
        $this->control_id = $id;
        $this->data = null;
    }

    /**
     * Set config items
     *
     * @param $config_items
     */
    public function set_config_items($config_items)
    {
        $this->config_items = $config_items;
    }

    /**
     * Get settings for row
     *
     * @param $row_id
     * @param string $device
     * @return bool
     */
    public function get_row_settings($row_id, $device = 'desktop')
    {
        $data = zoo_customize_get_panel_setting($this->control_id);

        if (isset($data[$device])) {
            if (isset($data[$device][$row_id])) {
                return !empty($data[$device][$row_id]) ? $data[$device][$row_id] : false;
            }
        }

        return false;
    }

    /**
     * Render items to HTML
     *
     * @param  array $setting Panel config.
     *
     * @return array
     */
    private function render_row_items(array $row_items, $row_id, $device)
    {
        $items = [];
        $widgets = get_option('sidebars_widgets', []);
        $items_count = count($row_items);
        $number_of_widgets = 0;
        $empty_widgets_count = 0;

        $this->rows[$row_id][$device] = $device;

        foreach ($row_items as $item_index => $item) {
            if (false !== strpos($item['id'], '-widget-')) {
                $number_of_widgets++;
                if (empty($widgets[$item['id']])) {
                    $empty_widgets_count++;
                }
            }
            $return_render = null;
            $item = array_merge([
                'x' => '',
                'width' => '1',
                'id' => '',
            ], $item);
            if (!$item['id']) {
                continue;
            }
            if ($item['id'] == 'nav-icon') { // F envato
                $mobile_items = $this->get_row_settings('sidebar', 'mobile');
                if (empty($mobile_items) || $this->invalidMobileCanvas($mobile_items)) {
                    continue;
                }
            }
            if ('cart-icon' === $item['id']) {
                $this->cartEnabled = $item;
                $this->cartEnabled['device'] = $device;
            }
            if ('header-account' === $item['id']) {
                $this->accountEnabled = $item;
                $this->accountEnabled['device'] = $device;
            }
            if ('search-box' === $item['id']) {
                $this->searchBoxEnabled = $item;
                $this->searchBoxEnabled['device'] = $device;
            }
            $item_config = isset($this->config_items[$item['id']]) ? $this->config_items[$item['id']] : [];
            if (!isset($items[$item['id']])) {
                $items[$item['id']] = [
                    'render_content' => '',
                    'devices' => [],
                    'rows' => [],
                    'id' => $item['id']
                ];
            }

            if (!$items[$item['id']]['render_content']) {
                ob_start();
                $has_cb = false;
                $object_item = Zoo_Customize_Builder::get_instance()->get_builder_item($this->id, $item['id']);
                if ($object_item) {
                    $return_render = call_user_func_array([$object_item, 'render'], [$item, $device]);
                }
                $ob_render = ob_get_clean();
                if (!$return_render && $ob_render) {
                    $return_render = $ob_render;
                }
                if ($return_render) {
                    $items[$item['id']]['render_content'] = $return_render;
                }
            }

            $items[$item['id']]['added'] = false;
            $items[$item['id']]['devices'][$device] = [
                'x' => $item['x'],
                'width' => $item['width'],
                'id' => $item['id'],
                'row' => $row_id,
            ];
            if (isset($items[$item['id']]['rows'][$row_id])) {
                $items[$item['id']]['rows'][$row_id] = [$items[$item['id']]['rows'][$row_id]];
                $items[$item['id']]['rows'][$row_id][] = $device;
            } else {
                $items[$item['id']]['rows'][$row_id] = $device;
            }
        }

        if ($items_count == $number_of_widgets && $items_count == $empty_widgets_count) {
            return '';
        }

        return $items;
    }

    /**
     * Sort items by their position on the grid.
     *
     * @access  private
     *
     * @return  array
     */
    private function _sort_items_by_position(array $items = [])
    {
        $ordered_items = [];

        foreach ($items as $key => $item) {
            $ordered_items[$key] = $item['x'];
        }

        array_multisort($ordered_items, SORT_ASC, $items);

        return $items;
    }

    /**
     * Setup Item content
     *
     * @todo Ensure item have not duplicate id
     *
     * @param $content
     * @param $id
     * @param $device
     * @return mixed
     */
    public function setup_item_content($content, $id, $device)
    {
        $content = str_replace('__id__', $id, $content);
        $content = str_replace('__device__', $device, $content);

        return $content;
    }

    /**
     * Render row
     *
     * @param  array $items Row items
     * @param  string $id Row ID
     * @param  string  device  Row ID
     *
     * @return  string  HTML
     */
    public function render_row(array $items, $id, $device)
    {
        $row_html = '';
        $max_columns = 12;
        $items = $this->_sort_items_by_position(array_values($items));
        $last_item = false;
        $prev_item = false;
        $group_items = [];
        $gi = 0;
        $n = count($items);
        $index = 0;
        $render_items = $this->render_row_items($items, $id, $device);

        if (!$render_items) {
            return '';
        }

        ob_start();

        while ($index < $n) {
            $item = $items[$index];

            if ($gi < 0) {
                $gi = 0;
            }
            if ($n > $index + 1) {
                $next_item = $items[$index + 1];
            } else {
                $next_item = false;
            }

            $item_id = $item['id'];
            $merge_key = $this->id . '_' . $item_id . '_merge';
            $merge = zoo_customize_get_setting($merge_key, $device);
            $merge_next = false;
            $merge_prev = false;
            if ($merge == 'no' || $merge == '0') {
                $merge = false;
            }

            if ($next_item) {
                $merge_key_next = $this->id . '_' . $next_item['id'] . '_merge';
                $merge_next = zoo_customize_get_setting($merge_key_next, $device);
            }

            if ($merge_next == 'no' || $merge_next == '0') {
                $merge_next = false;
            }

            if ($prev_item) {
                $merge_prev = $prev_item['__merge'];
            }

            if (
                (!$merge_prev || $merge_prev == 'prev')
                && (!$merge || $merge == 'next')
                && (!$merge_next || $merge_next == 'next')
            ) {
                $gi++;
            } elseif (
                (!$merge_prev || $merge_prev == 'prev')
                && ($merge == 'next')
                && (!$merge_next || $merge_next == 'prev')
            ) {
                $gi++;
            }


            $prev_item = $item;
            $prev_item['__merge'] = $merge;

            if (!isset($group_items[$gi])) {
                $group_items[$gi] = $item;
                $group_items[$gi]['items'] = array();
                $group_items[$gi]['items'][] = $prev_item;
            } else {
                $group_items[$gi]['width'] = ($item['x'] + $item['width']) - $group_items[$gi]['x'];
                $group_items[$gi]['items'][] = $prev_item;
            }

            if ($index == 0 && (!$merge || $merge == 'prev') && (!$merge_next || $merge_next == 'next')) {
                $gi++;
            }


            $index++;
        }

        $index = 0;
        foreach ($group_items as $item) {

            $first_id = $item['id'];
            $x = intval($item['x']);
            $width = intval($item['width']);
            $classes = array();

            $bootstrap_col_size = ('desktop' === $device) ? 'md-' . $width : $width;

            if ($item['items'][0]['__merge'] == "next") {
                $classes[] = "col-auto ml-auto";
            } elseif ($item['items'][count($item['items']) - 1]['__merge'] == "prev") {
                $classes[] = "col-auto mr-auto";
            } else {
                $classes[] = 'col-' . $bootstrap_col_size;
            }


            if (12 !== $width && 'header' !== $this->id) {
                $classes[] = 'col-12';
            }

            if ($x > 0) {
                if (!$last_item) {
                    $classes[] = 'offset-md-' . $x;
                } else {
                    $o = intval($last_item['width']) + intval($last_item['x']);
                    if ($x - $o > 0) {
                        $classes[] = 'offset-md-' . ($x - $o);
                    }
                }
            }

            $classes[] = 'builder-item builder-block-' . $first_id;

            $first_item_align = zoo_customize_get_setting($this->id . '_' . $first_id . '_align');

            if ($first_item_align) {
                if (is_array($first_item_align)) {
                    $first_item_align = $first_item_align[$device];
                }
                $classes[] = 'row-align-' . $first_item_align;
            }

            if (count($item['items']) > 1) {
                $classes[] = 'row-item-group';
            }

            $classes = apply_filters('zoo/builder/item-wrapper-classes', $classes, $item);
            $classes = join(' ', $classes);

            $row_items_html = '';
            foreach ($item['items'] as $_it) {
                $item_id = $_it['id'];
                $content = !empty($render_items[$item_id]['render_content']) ? $render_items[$item_id]['render_content'] : '';
                if ($content) {
                    $item_config = isset($this->config_items[$item_id]) ? $this->config_items[$item_id] : array();
                    if (!isset($item_config['section'])) {
                        $item_config['section'] = '';
                    }
                    $item_classes = array();
                    $item_classes[] = 'item-inner';
                    $item_classes[] = 'row-item-' . $item_id;
                    if (strpos($item_id, '-menu')) {
                        $item_classes[] = 'builder-block-m';
                    } else {
                        $item_classes[] = 'builder-block-nm';
                    }
                    if ($this->is_preview) {
                        $item_classes[] = ' builder-item-focus';
                    }

                    $item_classes = join(' ', $item_classes);
                    if ($this->is_preview) {
                        $row_items_html .= '<div class="' . esc_attr($item_classes) . '" data-section="' . $item_config['section'] . '" data-item-id="' . esc_attr($item_id) . '" >';
                    }
                    $row_items_html .= $this->setup_item_content($content, $id, $device);
                    if ($this->is_preview) {
                        $row_items_html .= '<span class="item-preview-name">' . esc_html($item_config['name']) . '</span></div>';
                    }
                }
            }
            if ($row_items_html) {
                echo '<div class="' . esc_attr($classes) . '">';
                echo ent2ncr($row_items_html);
                echo '</div>';
            }

            $last_item = $item;
            $index++;
        } // end loop items

        // Get item output
        $row_html = ob_get_clean();
        return $row_html;
    }

    /**
     * Render entire panel
     */
    public function render($row_ids = ['top', 'main', 'bottom'])
    {
        $configs = zoo_customize_get_panel_setting($this->control_id);

        $panel_html = '';

        foreach ($configs as $device => $rows) {
            if (empty($rows)) continue;
            $row_html = '';
            if ($this->id == 'header') {
                $row_html = '<div class="wrap-site-' . $this->id . '-' . $device . ' show-on-' . $device . '">';
            }
            foreach ($rows as $row_id => $row_items) {
                $show = zoo_customize_get_setting($this->id . '_' . $row_id . '_enable', $device);
                if (empty($row_items) || ($this->id === 'header' && 'sidebar' === $row_id) || 0 === $show) continue;
                $row_content = $this->render_row($row_items, $row_id, $device);
                if (!$row_content) continue;
                $row_layout = zoo_customize_get_setting($this->id . '_' . $row_id . '_layout');
                $row_sticky = zoo_customize_get_setting($this->id . '_' . $row_id . '_sticky_enable', $device);
                $row_sticky_animation = zoo_customize_get_setting($this->id . '_' . $row_id . '_sticky_animation');
                $row_sticky_height = '';
                $row_classes = $this->id . '-row ' . $this->id . '-' . $row_id;
                $row_classes .= ' ' . $row_layout;

                if ('contained' === $row_layout) {
                    $row_classes .= ' container';
                }

                if ('full-width' === $row_layout) {
                    $row_classes .= ' container-fluid';
                }
                if ($row_sticky_animation != 'none' && !!$row_sticky_animation) {
                    $row_classes .= ' ' . $row_sticky_animation . '-animation';
                }
                if ($this->id == 'footer') {
                    $row_classes .= ' footer-preset-' . zoo_customize_get_setting($this->id . '_' . $row_id . '_preset');
                }
                if (!!$row_sticky) {
                    $row_classes .= ' sticker';
                    $sticky_height = zoo_customize_get_setting($this->id . '_' . $row_id . '_sticky_height', $device);
                    if (!empty($sticky_height)) {
                        $row_sticky_height = 'data-sticky-height="' . esc_attr($sticky_height['value']) . '"';
                    }
                }
                $row_html .= '<div id="' . $row_id . '-' . $this->id . ($this->id == 'header' ? '-' . $device : '') . '" class="' . $row_classes . '" ' . $row_sticky_height . '>';

                if ('full-width-contained' === $row_layout) {
                    $row_html .= '<div class="container">';
                }
                $row_html .= '<div class="wrap-builder-items"><div class="row">';
                $row_html .= $row_content;
                $row_html .= '</div></div>';

                if ('full-width-contained' === $row_layout) {
                    $row_html .= '</div>';
                }

                $row_html .= '</div>';
            }
            if ($this->id == 'header') {
                $row_html .= '</div>';
            }
            $panel_html .= $row_html;
        }

        echo ent2ncr($panel_html);

        if ($this->id === 'header') {
            $this->render_off_canvas_sidebar();
            if ($this->cartEnabled && class_exists('WooCommerce', false)) {
                $cart_display_style = zoo_customize_get_setting('header_cart_display_style');
                if ($cart_display_style === 'off-canvas') :
                    ?>
                    <div class="wrap-element-cart-off-canvas woocommerce">
                        <div class="element-cart-content widget_shopping_cart element-off-canvas-cart">
                            <div class="heading-element-cart-content">
                                <?php esc_html_e('My Cart', 'fona'); ?>
                                <span class="total-cart-item">(<?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                    )</span>
                                <span class="close-cart"><i class="zoo-icon-close"></i> </span>
                            </div>
                            <div class="widget_shopping_cart_content">
                                <?php woocommerce_mini_cart(); ?>
                            </div>
                        </div>
                        <div class="mask-close"></div>
                    </div>
                <?php endif;
            }
            if ($this->accountEnabled && class_exists('WooCommerce', false)) {
                $account_layout = zoo_customize_get_setting('header_account_type', 'link');
                if (!is_user_logged_in() && !is_account_page() && $account_layout != 'link') {
                    $account_wrap_class = 'zoo-account-block';
                    if ($account_layout == 'modal') {
                        $account_wrap_class .= ' login-form-popup';
                    } else {
                        $account_wrap_class .= ' login-form-off-canvas';
                    }
                    ?>
                    <div class="<?php echo esc_attr($account_wrap_class); ?>">
                        <div class="wrap-login-form">
                            <p><span class="lb-login"><?php echo esc_html__('Sign In', 'fona'); ?></span>
                                <?php if ($account_layout == 'modal') { ?>
                                    <span class="close-login"><i class="cs-font clever-icon-close"></i></span>
                                    <?php
                                    if ('yes' === get_option('woocommerce_enable_myaccount_registration')) {
                                        ?>
                                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')) . '?action=register'); ?>"
                                           class="register"><?php echo esc_html__('Create an Account', 'fona'); ?></a>
                                    <?php }
                                }
                                ?>
                            </p>
                            <?php woocommerce_login_form();
                            if ($account_layout == 'off-canvas' && 'yes' === get_option('woocommerce_enable_myaccount_registration')) {
                                ?>
                                <div class="wrap-create-account-button">
                                    <span class="lb-login"><?php echo esc_html__('Or', 'fona'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')) . '?action=register'); ?>"
                                       class="register button button-outline"
                                       title="<?php echo esc_attr__('Create an Account', 'fona'); ?>"><?php echo esc_html__('Create an Account', 'fona'); ?></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="overlay"></div>
                    </div>
                    <?php
                }
            }
            if ($this->searchBoxEnabled) {
                $searchbox_placeholder = zoo_customize_get_setting('header_search_box_placeholder');
                $searchbox_live_search = zoo_customize_get_setting('header_search_box_live_search_enable');
                $searchbox_show_cat = zoo_customize_get_setting('header_search_box_show_product_cat_options');
                $ajax_class_attr = (1 === $searchbox_live_search) ? ' zoo-live-search' : '';
                ?>
                <div class="zoo-search-box-container header-search-box header-search wrap-lb-search">
                    <div class="wrap-form-lb-search">
                        <a href="#" class="btn-close-lb-search btn"><i class="zoo-icon-close"></i></a>
                        <form role="search"
                              class="zoo-search-form header-search-form<?php echo esc_attr($ajax_class_attr) ?>"
                              action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="wrap-input">
                                <input type="search" class="input-text search-field"
                                       placeholder="<?php echo esc_attr($searchbox_placeholder); ?>"
                                       value="<?php echo esc_attr(get_search_query()) ?>" name="s" autocomplete="off"
                                       title="<?php echo esc_attr($searchbox_placeholder); ?>"/>
                            </div>
                            <?php
                            if (1 === $searchbox_show_cat) :
                                $cats = get_terms([
                                    'hide_empty' => true,
                                    'taxonomy' => 'product_cat'
                                ]);
                                if ($cats && !is_wp_error($cats)) :
                                    ?>
                                    <div class="wrap-list-cat-search">
                                        <select class="zoo-product-cat-options" name="zoo-search-box-product-cat">
                                            <option value="all"><?php esc_html_e('All Categories', 'fona') ?></option>
                                            <?php
                                            foreach ($cats as $cat) {
                                                ?>
                                                <option value="<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                        <i class="zoo-icon-down"></i>
                                        <span class="label-cat-search"><?php esc_html_e('All Categories', 'fona') ?></span>
                                    </div>
                                <?php
                                endif;
                            endif;
                            ?>
                            <button type="submit" class="button search-submit">
                                <i class="zoo-icon-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
    }

    /**
     * Render sidebar row
     */
    private function render_off_canvas_sidebar()
    {
        $mobile_items = $this->get_row_settings('sidebar', 'mobile');
        $mobile_menu_skin = zoo_customize_get_setting('header_sidebar_text_mode', 'mobile');
        $mobile_animation = zoo_customize_get_setting('header_sidebar_animate', 'mobile');
        $desktop_items = $this->get_row_settings('sidebar', 'desktop');
        $desktop_menu_skin = zoo_customize_get_setting('header_sidebar_text_mode', 'desktop');
        $desktop_animation = zoo_customize_get_setting('header_sidebar_animate', 'desktop');

        if (!$mobile_animation) {
            $mobile_animation = 'off-canvas-slide-left';
        }

        if (!$desktop_animation) {
            $desktop_animation = 'off-canvas-slide-left';
        }

        if (!empty($mobile_items)) {
            $render_items = $this->render_row_items($mobile_items, 'sidebar', 'mobile');
            $classes = array('header-off-canvas-sidebar off-canvas-sidebar-panel');
            if ($mobile_menu_skin) {
                $classes[] = $mobile_menu_skin;
            }
            $classes[] = $mobile_animation;
            if ($mobile_animation == 'off-canvas-slide-right' || $mobile_animation == 'off-canvas-slide-left') {
                $classes[] = 'off-canvas-effect';
            }
            echo '<div class="' . esc_attr(join(' ', $classes)) . ' show-on-mobile">';
            echo '<div class="wrap-header-off-canvas"><a href="#" class="off-canvas-close"><i class="zoo-icon-close"></i></a><div class="wrap-content-header-off-canvas">';
            foreach ($mobile_items as $item) {
                $item_id = $item['id'];
                $content = $render_items[$item['id']]['render_content'];
                $item_config = isset($this->config_items[$item_id]) ? $this->config_items[$item_id] : [];
                $item_config = array_merge([
                    'section' => '',
                    'name' => '',
                ], (array)$item_config);
                $classes = "builder-item-sidebar mobile-builder-block-" . $item_id;
                if (strpos($item_id, 'menu')) {
                    $classes = $classes . " mobile-builder-block-menu ";
                }
                if ($this->is_preview) {
                    $classes .= ' builder-item-focus';
                }
                $content = str_replace('__id__', 'sidebar', $content);
                $content = str_replace('__device__', 'mobile', $content);
                if ($this->is_preview) {
                    echo '<div class="' . esc_attr($classes) . '" data-item-id="' . esc_attr($item_id) . '" data-section="' . $item_config['section'] . '">';
                } else {
                    echo '<div class="' . esc_attr($classes) . '">';
                }
                echo ent2ncr($content);
                if ($this->is_preview) {
                    echo '<span class="item-preview-name">' . esc_html($item_config['name']) . '</span>';
                }
                echo '</div>';
            }
            echo '</div></div>';
            echo '<div class="mask-off-canvas">';
            echo '</div>';
            echo '</div>';
        }

        if (!empty($desktop_items)) {
            $render_items = $this->render_row_items($desktop_items, 'sidebar', 'desktop');
            $classes = array('header-off-canvas-sidebar off-canvas-sidebar-panel');
            if ($desktop_menu_skin) {
                $classes[] = $desktop_menu_skin;
            }
            $classes[] = $desktop_animation;
            if ($desktop_animation == 'off-canvas-slide-right' || $desktop_animation == 'off-canvas-slide-left') {
                $classes[] = 'off-canvas-effect';
            }
            echo '<div class="' . esc_attr(join(' ', $classes)) . ' show-on-desktop">';
            echo '<div class="wrap-header-off-canvas"><a href="#" class="off-canvas-close"><i class="zoo-icon-close"></i></a><div class="wrap-content-header-off-canvas">';
            foreach ($desktop_items as $item) {
                $item_id = $item['id'];
                $content = $render_items[$item['id']]['render_content'];
                $item_config = isset($this->config_items[$item_id]) ? $this->config_items[$item_id] : [];
                $item_config = array_merge([
                    'section' => '',
                    'name' => '',
                ], (array)$item_config);
                $classes = "builder-item-sidebar desktop-builder-block-" . $item_id;
                if (strpos($item_id, 'menu')) {
                    $classes = $classes . " desktop-builder-block-menu ";
                }
                if ($this->is_preview) {
                    $classes .= ' builder-item-focus';
                }
                $content = str_replace('__id__', 'sidebar', $content);
                $content = str_replace('__device__', 'desktop', $content);
                if ($this->is_preview) {
                    echo '<div class="' . esc_attr($classes) . '" data-item-id="' . esc_attr($item_id) . '" data-section="' . $item_config['section'] . '">';
                } else {
                    echo '<div class="' . esc_attr($classes) . '">';
                }
                echo ent2ncr($content);
                if ($this->is_preview) {
                    echo '<span class="item-preview-name">' . esc_html($item_config['name']) . '</span>';
                }
                echo '</div>';
            }
            echo '</div></div>';
            echo '<div class="mask-off-canvas">';
            echo '</div>';
            echo '</div>';
        }
    }

    /**
     * Check if mobile canvas should be displayed
     */
    private function invalidMobileCanvas(array $items)
    {
        $has_menu = false;
        $items_count = count($items);

        foreach ($items as $item) {
            if ($item['id'] == 'mobile-menu') {
                $has_menu = has_nav_menu('mobile-menu');
            }
        }

        if ($items_count > 1 || $has_menu) {
            return false;
        } else {
            return true;
        }
    }
}
