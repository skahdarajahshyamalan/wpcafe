'use strict';

(function($){

    $(document).ready(function(){
        /*
         * Discount settings for wpcafe pro and wpcafe multi-vendor 
         */

        //standard
        function check_percentage_discount(data) {
            if (data == 'standard') {
                var wpc_pro_discount_percentage = $(".wpc_pro_discount_percentage").val();
                if (wpc_pro_discount_percentage !== '' && wpc_pro_discount_percentage !== '0') {
                    $(".wpc_pro_order_standarad_off_amount").val("");
                    $(".wpc_pro_discount_standarad_off").val("");
                    var percentage_exist = $("#discount_message").data("percentage_exist");
                    alert(percentage_exist);
                }
            } else if (data == 'percentage') {
                var wpc_pro_discount_standarad_off = $(".wpc_pro_discount_standarad_off").val();
                var wpc_pro_order_standarad_off_amount = $(".wpc_pro_order_standarad_off_amount").val();
                if (
                    (wpc_pro_discount_standarad_off !== '' && wpc_pro_discount_standarad_off !== '0') ||
                    (wpc_pro_order_standarad_off_amount !== '' && wpc_pro_order_standarad_off_amount !== '0')
                ) {
                    $(".wpc_pro_discount_percentage").val("");
                    var standard_exist = $("#discount_message").data("standard_exist");
                    alert(standard_exist);
                }
            }

        }

        $('input[name="wpc_pro_order_standarad_off_amount"]').on('keyup', function () {
            check_percentage_discount("standard");
        });

        $('input[name="wpc_pro_discount_standarad_off"]').on('keyup', function () {
            var wpc_pro_order_standarad_off_amount = $(".wpc_pro_order_standarad_off_amount").val();
            if (wpc_pro_order_standarad_off_amount == '' || wpc_pro_order_standarad_off_amount == '0') {
                alert("Please fill standard order amount");
            }
        });
        
        //percentage
        $('.wpc_pro_discount_main_block').on('keyup', 'input[name="wpc_pro_discount_percentage"]', function () {
            check_percentage_discount("percentage");
        });
    
    });
})(jQuery);

