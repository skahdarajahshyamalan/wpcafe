   
   (function ($) {

    "use strict";

    $(document).ready(function () {

        function AddMinutesToDate( minutes = 0 ) {
            var now = new Date(Date.now());
            return new Date(now.getTime() + ( parseInt(minutes) * 60000  ) );
        }
        var new_order_data = {
  
            /**
             * Get last order ID.
             */
            get_last_order_id: function() {
                return parseInt( admin_object.last_order_id );
            },

            /**
             * Heartbeat send.
             */
            on_heartbeat_send: function( e, data ) {
                data[ 'wpc_pro_heartbeat' ]       = 'live_notify'; // it will receive in backend
                data[ 'wpc_pro_last_order_id' ]   = new_order_data.get_last_order_id();
            },

            on_heartbeat_tick: function( e, data ) {

                if ( typeof data.notify_data === 'undefined' ) {
                    return;
                }

                if ( data.notify_data.last_order_id <= new_order_data.get_last_order_id() ) {
                    return;
                }
                
                // If there are new orders play sound, show popup
                new_order_data.order_push_notifications(data.notify_data.rows,data.notify_data.popup);

            },

            /**
             * play sound
             * @param {*} url 
             */
            play_sound(url) {
                var audio   = new Audio(url);
                audio.muted = false;
                audio.autoplay = true;   
                var played_promise = audio.play();
                if (played_promise) {
                    played_promise.catch((e) => {
                        if (e.name === 'NotAllowedError' ||
                            e.name === 'NotSupportedError') {
                            console.log(e.name);
                        }
                    });
                }
            },

            /**
             *  new order push notification
             */
            order_push_notifications(rows="",popup="") {
                if (rows =="" || popup=="") {
                    return;
                }
                // add new row in order table
                if ( typeof admin_object.wpc_pro_sound_notify !=="undefined" && admin_object.wpc_pro_sound_notify !=="" ) {
                    // play sound
                    new_order_data.play_sound(admin_object.audio_url);
                    localStorage.setItem("clear_popup_orders", "no" );
                }

                // prepend rows
                $(".wpc_shop_order").prepend(rows);

                // popup
                $("#wpc-notification-wrapper").prepend(popup)
                $(".wpc-notification-clear").fadeIn();

            },

        }

        // new order push notifications
        if ( ( typeof admin_object !=="undefined" && admin_object !==null )
            &&   typeof admin_object.wpc_pro_order_notify !=="undefined" && admin_object.wpc_pro_order_notify !=="" ) {
            
            order_repeat_sound_notification()
            $( document ).on( 'heartbeat-send', new_order_data.on_heartbeat_send );
            $( document ).on( 'heartbeat-tick', new_order_data.on_heartbeat_tick );
        }


        /**
         * repeat the sound with interval time
         */
         function order_repeat_sound_notification(){
            // repeat sound notifications start
            if ( typeof admin_object.wpc_pro_sound_repeat !=="undefined" && admin_object.wpc_pro_sound_repeat !=="" ) {
                setInterval(function() {
                    let clear_popup_orders = localStorage.getItem("clear_popup_orders");
                    if( clear_popup_orders !==null && clear_popup_orders == "no" && $(".wpc-notification-clear").length > 0){
                        new_order_data.play_sound(admin_object.audio_url);
                    }
                }, 1000*60*admin_object.repeat_interval_time);
            }
            // repeat sound end
        }

    });

    if($(".wpc-notification-clear").length > 0){
        
        localStorage.setItem("clear_popup_orders", "yes");

        $(".wpc-notification-clear").on('click', function(e){
            e.preventDefault();
            $("#wpc-notification-wrapper").empty();
        });

    }

})(jQuery);