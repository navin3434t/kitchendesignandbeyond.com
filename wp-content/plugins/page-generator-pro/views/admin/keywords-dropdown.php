<select size="1" class="right wpzinc-tags" data-element="#<?php echo $element; ?>">
    <option value=""><?php _e( '--- Insert Keyword ---', 'page-generator-pro' ); ?></option>
    <?php
    foreach ( $keywords as $keyword ) {
        ?>
        <option value="{<?php echo $keyword; ?>}"><?php echo $keyword; ?></option>
        <?php
    }
    ?>
</select>