<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $this->base->plugin->displayName; ?>

        <span>
            <?php _e( 'Licensing', $this->base->plugin->name ); ?>
        </span>
    </h1>

    <?php    
    // Notices
    if ( isset( $this->message ) ) {
        ?>
        <div class="updated notice"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if ( isset( $this->errorMessage ) ) {
        ?>
        <div class="error notice"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?> 

    <div class="wrap-inner">
        <div id="poststuff">
            <?php require_once( 'licensing-inline.php' ); ?>
        </div>
    </div><!-- /.wrap-inner -->
</div><!-- /.wrap -->