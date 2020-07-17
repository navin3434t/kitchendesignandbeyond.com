<?php
/**
 */

$display = 'none';
if (!empty($selected_filter_option)) {
    $display = 'block';
    $filters_count = count($selected_filter_option);
    if ($filters_count === 1) {
        if (isset($selected_filter_option['orderby']) || isset($selected_filter_option['relation'])) {
            $display = 'none';
        }
        if(isset($has_filter_category)) {
            if (!$has_filter_category) {
                $display = 'none';
            }
        }
    }
}

$filter_id = mt_rand();
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="zoo-filter-block zoo-active-filter" style="display: <?php echo $display; ?>">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?></h4>
    <ul class="zoo-ln-wrap-activated-filter">
        <?php
        if (isset($selected_filter_option) && isset($filters_count)) {
            require ZOO_LN_TEMPLATES_PATH . 'view/active-filter/items.php';
        }
        ?>
    </ul>
</div>
