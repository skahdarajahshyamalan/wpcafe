(function($) {
    "use strict";

    $(document).ready(function() {

        var tip_wrapper = $('.woocommerce');

        // tip type on change show/hide fixed input textbox or percentage selection block
        tip_wrapper.on('change', '.wpc_pro_tip_type', function() {
            tip_wrapper.find('.wpc_pro_tip_msg').text('');

            var selected_type   = $(this).val();

            var fixed_wrap      = tip_wrapper.find('.wpc_pro_tip_type_fixed_wrap');
            var percentage_wrap = tip_wrapper.find('.wpc_pro_tip_type_percentage_wrap');

            if(selected_type == 'tip_percentage'){ 
                percentage_wrap.show();
                fixed_wrap.hide();

                show_hide_add_btn( percentage_wrap.find('.wpc_pro_percentage_tip_amount').val() );
            } else {
                fixed_wrap.show();
                percentage_wrap.hide();

                show_hide_add_btn( fixed_wrap.find('.wpc_pro_fixed_tip_amount').val() );
            }
        });

        // tip amount on change show add button: if amount > 0
        tip_wrapper.on('change keyup', '.wpc_pro_fixed_tip_amount, .wpc_pro_percentage_tip_amount', function() {
            tip_wrapper.find('.wpc_pro_tip_msg').text('');
            
            show_hide_add_btn( $(this).val() );
        });

        // tip add button on click functionality: add tip to session and to view
        tip_wrapper.on('click', '.wpc_pro_add_tip', function(){
            
            var msg_showing_block = tip_wrapper.find('.wpc_pro_tip_msg');
            msg_showing_block.text('');

            var tip_selected_type     = tip_wrapper.find('.wpc_pro_tip_type option:selected').val();
            var tip_fixed_amount      = tip_wrapper.find('.wpc_pro_fixed_tip_amount').val();
            var tip_percentage_amount = tip_wrapper.find('.wpc_pro_percentage_tip_amount').val();

            // wpc_pro_order_tip_block
            $('.woocommerce').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            // add tip to session and to view
            $.ajax({
                type: "POST",
                url: wpc_pro_tip_obj.ajax_url,
                dataType: 'json',
                data: { 
                    action: 'add_tip', security: wpc_pro_tip_obj.add_tip_nonce,
                    tip_selected_type: tip_selected_type, tip_fixed_amount: tip_fixed_amount, tip_percentage_amount: tip_percentage_amount, 
                },
                complete: function(){
                    $('.woocommerce').unblock();
                },
                success: function (res) {
                    if( res.status_code == 1 ) {
                        $('body').trigger( 'update_checkout' );
                        $('[name="update_cart"]').attr('aria-disabled', false).removeAttr('disabled').trigger('click');
                      
                        tip_wrapper.find('.wpc_pro_remove_tip').show();
                    }

                    msg_showing_block.text(res.message);
                }
            });
        });

        // remove tip from session and from view
        tip_wrapper.on('click', '.wpc_pro_remove_tip', function(){
            var msg_showing_block = tip_wrapper.find('.wpc_pro_tip_msg');
            msg_showing_block.text('');

            var btn_this = $(this);

            $('.woocommerce').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            $.ajax({
                type: "POST",
                url: wpc_pro_tip_obj.ajax_url,
                dataType: 'json',
                data: { 
                    action: 'remove_tip', security: wpc_pro_tip_obj.remove_tip_nonce,
                },
                complete: function(){
                    $('.woocommerce').unblock();
                },
                success: function (res) {
                    if( res.status_code == 1 ) {
                        $('body').trigger( 'update_checkout' );
                        $('[name="update_cart"]').attr('aria-disabled', false).removeAttr('disabled').trigger('click');

                        tip_wrapper.find('.wpc_pro_fixed_tip_amount').val(0);
                        tip_wrapper.find('.wpc_pro_percentage_tip_amount').val(0);

                        tip_wrapper.find('.wpc_pro_add_tip').prop('disabled', true);
                        btn_this.hide();
                    }

                    msg_showing_block.text(res.message);
                }
            });
        });

    });

})(jQuery);

// show hide tip add button
function show_hide_add_btn( tip_amount = 0 ){
    var tip_add_btn = jQuery('.wpc_pro_order_tip_block').find('.wpc_pro_add_tip');

    if( parseFloat(tip_amount) > 0 ){
        tip_add_btn.removeAttr('disabled');
    } else {
        tip_add_btn.prop('disabled', true);
    }
}