<?php
if($markup_type =='rows'){
    if( count( $order_data ) > 0 ){
        foreach( $order_data as $data ):
            $order_name = $data['order_id'] ." ". $data['first_name'] . " " .$data['last_name'];
        ?>
        <tr id="<?php esc_attr_e( "post-".$data['order_id'] )?>" class="wpc-no-viewing type-shop_order status-wc-<?php esc_attr_e($data['status']);?>" >
  
            <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-<?php esc_attr_e( $data['order_id'] );?>"></label>
			    <input id="cb-select-3035" type="checkbox" name="post[]" value="<?php esc_attr_e( $data['order_id'] );?>">
			</th>
            <td>
                <a href="<?php echo esc_url( admin_url( 'post.php?post='.$data['order_id'].'&action=edit' ) )?>">
                    <strong># <?php esc_html_e($data['order_id']) ." ".
                            esc_html_e($data['first_name']) . esc_html_e($data['last_name']) ;?>
                    </strong>
                </a>
                <a href="#" class="order-preview" data-order-id="<?php esc_attr_e($data['order_id']);?>"
                title="<?php esc_attr_e("Preview")?>"><?php echo esc_html__('Preview', 'wpcafe-pro') ?></a>
                <span class="recent-order"><?php echo esc_html__('Recent', 'wpcafe-pro') ?></span>
            </td>
            <td>
                <time datetime="<?php echo esc_attr( $data['order_date']->date( 'c' ) ); ?>">
                <?php 
                    $order_timestamp = $data['order_date'] ? $data['order_date']->getTimestamp() : '';

                    if ( ! $order_timestamp ) {
                        echo '&ndash;';
                        return;
                    }
            
                    // Check if the order was created within the last 24 hours, and not in the future.
                    if ( $order_timestamp > strtotime( '-1 day', time() ) && $order_timestamp <= time() ) {
                        $show_date = sprintf(
                            /* translators: %s: human-readable time difference */
                            _x( '%s ago', '%s = human-readable time difference', 'wpcafe-pro' ),
                            human_time_diff( $data['order_date']->getTimestamp(), time() )
                        );
                    } else {
                        $show_date = $data['order_date']->date_i18n( apply_filters( 'woocommerce_admin_order_date_format', esc_html__( 'M j, Y', 'wpcafe-pro' ) ) );
                    }
                    printf(
                        '<time datetime="%1$s" title="%2$s">%3$s</time>',
                        esc_attr( $data['order_date']->date( 'c' ) ),
                        esc_html( $data['order_date']->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ),
                        esc_html( $show_date )
                    );
                 ?>
            </time>
            </td>
            <td><?php esc_html_e($data['status']);?></td>
            <td><?php esc_html_e($data['total']);?></td>
        </tr>
        <?php
        endforeach;

    }
}


if($markup_type =='popup'){
    if( count( $order_data ) > 0 ){
        
        foreach( $order_data as $data ):
            $order_name = $data['order_id'] ." ". $data['first_name'] . " " .$data['last_name'];
            $order_link = admin_url( 'post.php?post='.$data['order_id'].'&action=edit' );
         
        ?>

        <div class="wpc-notification-list">
            <div class="wpc-notification-popup-recent-order">
                <span class="recent-order"><?php echo esc_html__('Recent', 'wpcafe-pro') ?></span>
            </div>
            <a class="order-name" href="<?php echo esc_url( $order_link )?>">
                <strong>#<?php esc_html_e( $order_name); ?>
                </strong>
            </a>

            <div class="notification-price-total"><span><?php echo esc_html__('Price : ' , 'wpcafe-pro')  ?></span><?php  esc_html_e($data['total']);?></div>
            <div class="notification-status"><span><?php echo esc_html__('Status : ' , 'wpcafe-pro') ?></span> <?php esc_html_e($data['status']);?></div>
            
            <a href="<?php echo esc_url($order_link); ?>" class="order-preview" data-order-id="<?php esc_attr_e($data['order_id']);?>"
                title="<?php esc_attr_e("Preview")?>"><?php echo esc_html__('Preview', 'wpcafe-pro'); ?></a>
        </div>

        <?php
        endforeach;

    }
}