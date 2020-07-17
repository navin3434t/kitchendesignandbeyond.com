<?php
foreach ( $this->items as $count => $result ) {
    ?>
    <tr>
        <th scope="row" class="check-column">
            <input type="checkbox" name="ids[<?php echo $result['id']; ?>]" value="<?php echo $result['id']; ?>" />
        </th>
        <td>
            <a href="<?php echo admin_url( 'admin.php?page=' . $this->base->plugin->name . '-logs&s=' . $result['group_id'] ); ?>" title="<?php _e( 'Filter Log by this Group', 'page-generator-pro' ); ?>"> 
                #<?php echo $result['group_id']; ?><br />
                <?php echo $result['group_name']; ?>
            </a>
        </td>
        <td>
            <a href="<?php echo $result['url']; ?>" target="_blank" title="<?php _e( 'View Generated Item', 'page-generator-pro' ); ?>"><?php echo $result['url']; ?></a>
        </td>
        <td>
            <?php echo $result['system']; ?>
        </td>
        <td>
            <?php echo ( $result['test_mode'] ? __( 'Yes', 'page-generator-pro' ) : __( 'No', 'page-generator-pro' ) ); ?>
        </td>
        <td>
            <?php echo ( $result['generated'] ? __( 'Yes', 'page-generator-pro' ) : __( 'No', 'page-generator-pro' ) ); ?>
        </td>
        <td>
            <?php
            $keywords_terms = json_decode( $result['keywords_terms'] );
            foreach ( $keywords_terms as $keyword => $term ) {
                echo $keyword . ': ' . $term . '<br />';
            }
            ?>
        </td>
        <td>
            <?php echo $result['message']; ?>
        </td>
        <td>
            <?php echo $result['duration']; ?>
        </td>
        <td>
            <?php echo $result['memory_usage']; ?>
        </td>
        <td>
            <?php echo $result['memory_peak_usage']; ?>
        </td>
        <td>
            <?php echo get_date_from_gmt( $result['generated_at'], get_option( 'date_format' ) . ' H:i:s' ); ?>
        </td>
    </tr>
    <?php
}