<?php

namespace WpCafe_Pro\Core\Modules\Integrations;

defined('ABSPATH') || exit;

use Exception;

class Zapier{
    
    use \WpCafe_Pro\Traits\Singleton;

    /**
     * Call api function
     *
     * @return void
     */
    public function init($data){

        $request_url = !empty( $data['request_url'] ) ?  $data['request_url'] : [] ;
        if ( empty( $request_url[0] ) && empty($request_url[1] )  ) {
            return false;
        }
        try {
            if ( count($request_url)>0 ) {
                if ( $request_url[0] !=="" ) {
                    $this->call_to_action( $request_url[0] , $data );
                }

                if ( $request_url[1] !=="" ) {
                    $this->call_to_action( $request_url[1] , $data );
                }
                
            }

        } catch ( Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Call api for data
     */
    public function call_to_action( $api_url , $data ){
        // header
        $args = array( 'headers' => 
        array(
            'Content-Type'  => 'application/json'
        ) );

        unset( $data['request_url'] );  
        unset( $data['action'] );  
        unset( $data['wpc_action'] );  
        unset( $data['wpc_check_for_submission'] );  
        
        $response = [];    
        $args['body']   = !empty( $data ) ? json_encode( $data ) : [];
        $args['method'] = "POST";    
        $response       = wp_remote_post( $api_url, $args );
        $response_body  = wp_remote_retrieve_body( $response );

        if( !$response_body ){
            return false;
        }

        return $response_body;
    }

    /**
     * Send data to api
     */
    public function send_data_to_api( $post_arr ){
        // zapier/ pabbly integration
        $settings    = get_option( 'wpcafe_reservation_settings_options' );
        $zapier      = !empty( $settings['zapier_web_hooks'] ) ?  $settings['zapier_web_hooks'] : '';
        $pabbly      = !empty( $settings['pabbly_web_hooks'] ) ?  $settings['pabbly_web_hooks'] : '';

        $post_arr['request_url'] = [ $zapier , $pabbly ];
        
        // Integration with zapier / pabbly
        return \WpCafe_Pro\Core\Modules\Integrations\Zapier::instance()->init( $post_arr );
    }

}
