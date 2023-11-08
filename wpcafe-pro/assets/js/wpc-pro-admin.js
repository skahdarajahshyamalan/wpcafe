(function ($) {

    "use strict";

    var pickup_selected_values      = [];
    var delivery_selected_values    = [];
    var selected_diff_values        = [];
    var multi_block     = $( '.multi_diff_block .week_diff_schedule_wrap' ).length;
    
    // reservation multi slot dynamic start/end time increase/decrease
    var reserve_multi_slot_diff_fields = {
            main_wrapper: '.multi_diff_block',
            wrapper_block: '.week_diff_schedule_wrap .schedule_block',
            parent_block: '.week_diff_schedule_wrap',
            second_wrapper: 'schedule_block',
            append_wrapper: '.week_diff_schedule_wrap',
            button_wrapper: '.add_multi_time_block',
            multi_start_time: 'multi_diff_start_time',
            multi_end_time: 'multi_diff_end_time',
            schedule_name: 'diff_schedule_name',
            seat_capacity: 'diff_seat_capacity',
            remove_button: 'remove_multi_time_block',
            clear_button: 'wpc_multi_weekly_clear'
        };



    $(document).ready(function () {
        /*
         * Enable pro settings
         */
        var pro_feature_arr = [
            '#wpc_reservation_form_display_page',
            '#wpc_user_notification_for_confirm_req',
            '#wpc_admin_notification_for_confirm_req',
            '#wpc_admin_cancel_notification',
            '#wpc_admin_booking_confirm_subject',
            '#wpc_admin_booking_cancel_subject',
            '#wpc_confirm_email_subject',
            '#wpc_rejected_email_subject',
            '#wpc_user_notification_for_cancel_req',
            '#wpc_reservation_with_menu_email'
        ];

        $.map(pro_feature_arr, function (value, index) {
            $(value).attr('disabled', false);
        });

        // hide text
        $(".wpc-pro-text").css('display', 'none');

        //remove class
        var pro_feature_editor_arr = [
            '#wp-wpc_admin_booking_confirm_email-wrap',
            '#wp-wpc_admin_booking_cancel_email-wrap',
            '#wp-wpc_rejected_email-wrap',
            '#wp-wpc_confirm_email-wrap',
            '#wp-wpc_reservation_with_menu_email-wrap'
        ];
        $.map(pro_feature_editor_arr, function (value, index) {
            $(value).css({
                'opacity': 'unset',
                'background': 'transparent',
                'pointer-events': 'unset'
            });
        });

        // select2
        var select_arr = ['.wpc_pro_include_menu','.wpc_pro_include_cat', '.special_menus', '.wpc_pro_multi_product', '.wpc_pro_multi_cat']

        $.map(select_arr, function (value, index) {
            $(value).select2({
                selectAllOption: true,
            });
        }); 
   
        /*
         * Meta box time picker
         */
        $('#wpc_pro_preparing_time,#wpc_pro_delivery_time').timepicker({
            timeFormat: "H:i",
            step: 15 //time gap in minutes
        });

        // Weekly pickup schedule dynamically increase decrease
        var add_weekly_pickup_schedule_block_params = {
            parent_block        : 'pickup_schedule_block',
            second_wrapper      : 'wpc-weekly-schedule-list',
            second_wrapper_extra: 'pickup_weekly_block',
            append_wrapper      : '.pickup_schedule_main_block',
            button_wrapper      : '.wpc-weekly-schedule-btn',
            button_class        : '.add_pickup_weekly_block',
            field_name          : 'wpc_pickup_weekly_schedule',
            start_time_name     : 'wpc_pickup_weekly_schedule_start_time',
            end_time_name       : 'wpc_pickup_weekly_schedule_end_time',
            remove_button       : 'remove_pickup_block',
            clear_button        : 'wpc_pickup_weekly_clear',
            validation_message  : 'pickup_valid_message',
            block_type          : 'pickup',
            start_class         : 'wpc_pickup_start_time',
            end_class           : 'wpc_pickup_end_time',
            start_class_wrap    : 'wpc_pickup_weekly_start_wrap',
            end_class_wrap      : 'wpc_pickup_weekly_end_wrap',
        };

        add_week_block( add_weekly_pickup_schedule_block_params ); 

        // remove pick up exception block
        var remove_pickup_block = {
            parent_block: '.pickup_schedule_main_block',
            remove_button: '.remove_pickup_block',
            removing_block: '.pickup_schedule_block',
            type: 'pickup',
        };

        remove_day_schedule_block(remove_pickup_block);


        // Weekly delivery schedule dynamically increase decrease
        var add_delivery_schedule_block_params = {
            parent_block        : 'delivery_schedule_block',
            second_wrapper      : 'wpc-weekly-schedule-list',
            second_wrapper_extra: 'delivery_weekly_block',
            append_wrapper      : '.delivery_schedule_main_block',
            button_wrapper      : '.wpc-weekly-schedule-btn',
            button_class        : '.add_delivery_weekly_block',
            field_name          : 'wpc_delivery_schedule',
            start_time_name     : 'wpc_delivery_weekly_schedule_start_time',
            end_time_name       : 'wpc_delivery_weekly_schedule_end_time',
            remove_button       : 'remove_delivery_block',
            clear_button        : 'wpc_delivery_weekly_clear',
            validation_message  : 'delivery_valid_message',
            block_type          : 'delivery',
            start_class         : 'wpc_delivery_start_time',
            end_class           : 'wpc_delivery_end_time',
            start_class_wrap    : 'wpc_delivery_weekly_start_wrap',
            end_class_wrap      : 'wpc_delivery_weekly_end_wrap',
        };

        add_week_block( add_delivery_schedule_block_params );

        var remove_delivery_block = {
            parent_block: '.delivery_schedule_main_block',
            remove_button: '.remove_delivery_block',
            removing_block: '.delivery_schedule_block',
            type: 'delivery',
        };

        remove_day_schedule_block(remove_delivery_block);

        // Check overlapping single and multi slot schedule
        var multi_time = [];

        $('.wpc-meta').on('focus', ".multi_all_start_time,.multi_all_end_time", function () { 
            $(this).on('changeTime', function () {
                var element = $(this).attr('name');
                var value = $(this).val();
                var id = $(this).attr('id');
                var obj = {};
                if (element == 'multi_start_time[]' || element == 'multi_end_time[]') {
                    // find index of same id and name
                    let index = multi_time.findIndex((ele) => ele.id === id && ele.name === element)
                    // remove same index object
                    if (index !== -1) multi_time.splice(index, 1);
                    if (multi_time.some(ele => ele.value === value)) {
                        var multi_time_exist = $("#multi_time_exist").data("multi_time_exist");
                        alert(multi_time_exist);
                        $(this).val("");

                    } else {
                        obj.id = id;
                        obj.name = element;
                        obj.value = value;
                        multi_time.push(obj)
                    }
                }
                reserv_time_picker( $(this) , 'h:i A' ); 
                $(this).timepicker('hide');

                // enable end time
                if ( value !=="" ) {
                    var get_id              = $(this).attr('id');
                    var multi_all_end_time  = $(".multi_all_end_time_"+get_id)
        
                    if ( multi_all_end_time.attr("disabled") ) {
                        multi_all_end_time.removeAttr("disabled")
                    }
                }

            })
        });

        // multi different schedule time onChange action
        $('.wpc-meta').on('focus', ".multi_diff_start_time,.multi_diff_end_time", function () {

            $(this).on('changeTime', function () {
                // enable end time
                if ( $(this).val() !=="" ) {
                    var get_id               = $(this).attr('id');
                    var multi_diff_end_time  = $(".multi_diff_end_time_"+get_id)
    
                    if ( multi_diff_end_time.attr("disabled") ) {
                        multi_diff_end_time.removeAttr("disabled")
                    }

                }

                reserv_time_picker( $(this) , 'h:i A' );
                $(this).timepicker('hide');
            })
        });

        var time_class = ['.wpc_pickup_weekly_schedule_start_time', '.wpc_pickup_weekly_schedule_end_time', '.wpc_delivery_weekly_schedule_start_time','.wpc_delivery_weekly_schedule_end_time','.wpc_exception_end_time' ];

        // date timepicker
        $.map(time_class, function (value, index) {
            $('.pickup_schedule_main_block,.delivery_schedule_main_block').on('focus', value, function () {

                var clicked_start         = true;

                var current_id            = $(this).attr('id');
                var related_picker_class  = '';

                if ( value == ".wpc_pickup_weekly_schedule_start_time" || value == ".wpc_delivery_weekly_schedule_start_time" ) {
                    related_picker_class = time_class[index+1] + '_' + current_id;
                } else {
                    clicked_start        = false;
                    related_picker_class = time_class[index-1] + '_' + current_id;
                }

                initialize_any_time_picker($, this, clicked_start, related_picker_class); 

                if ( index == 0 || index == 1 ) {
                    time_picker( $, $(this), "pickup", clicked_start, related_picker_class, '.wpc_pickup_weekly_clear' )
                }
                else if ( index == 2 || index == 3 ) {
                    time_picker( $, $(this), "delivery", clicked_start, related_picker_class, '.wpc_delivery_weekly_clear' )
                }
            });
        });

        //clear action
        var clear_class = ['.wpc_pickup_weekly_clear', '.wpc_delivery_weekly_clear', '.wpc_multi_clear', '.wpc_extra_field_clear', '.wpc_pickup_exception_date_clear', '.wpc_delivery_exception_date_clear', '.wpc_reservation_holiday_clear','.wpc_all_multi_clear', '.wpc_pro_tip_percentage_data_clear', '.wpc_pro_qrcode_data_clear'];
        
        $.each(clear_class, function (ind, val) {
            $('.pickup_schedule_main_block,.delivery_schedule_main_block,.multi_schedule,.reserv_extra_main_block,.pickup_exception_main_block,.delivery_exception_main_block,.tip_percentage_main_block, .qrcode_main_block').on('click', val, function () {
                var id   = $(this).attr('id');
                var type = 'reset_time';

                switch (ind) {
                    case 0:
                        // pickup
                        $('.wpc_pickup_start_time_' + id).val('').timepicker('remove');
                        $('.wpc_pickup_end_time_' + id).val('').timepicker('remove');
                        $('.pickup_valid_message_' + id).html('');

                        var field_blocks           = [ $('.wpc_pickup_weekly_schedule_start_time_'+id ), ];
                        var field_dependent_blocks = [ $('.wpc_pickup_weekly_schedule_end_time_'+id ), ];
     
                        add_field_validation_error_content($, type, field_blocks, false);
                        add_field_validation_error_content($, type, field_dependent_blocks, true);

                        hide_reset_button($(this));
                        break;
                    case 1:
                        // delivery
                        $('.wpc_delivery_start_time_' + id).val('').timepicker('remove');
                        $('.wpc_delivery_end_time_' + id).val('').timepicker('remove');
                        $('.delivery_valid_message_' + id).html('');

                        var field_blocks           = [ $('.wpc_delivery_weekly_schedule_start_time_'+id ), ];
                        var field_dependent_blocks = [ $('.wpc_delivery_weekly_schedule_end_time_'+id ), ];
     
                        add_field_validation_error_content($, type, field_blocks, false);
                        add_field_validation_error_content($, type, field_dependent_blocks, true);

                        hide_reset_button($(this));
                        break;
                    case 2:
                        // multi diff day schedule
                        $('.multi_start_time_' + id).val('');
                        $('.multi_end_time_' + id).val('');
                        $('.schedule_name_' + id).val('');
                        $('.seat_capacity_' + id).val('');
                        $('.allday_multi_message_' + id).html('');
                        break;
                    case 3:
                        // extra field
                        $('.wpc_extra_field_required_' + id).val('optional');
                        $('.reserv_extra_label_' + id).val('');
                        $('.reserv_extra_place_ho_' + id).val('');
                        break;
                    case 4:
                        // pickup exception
                        $('.wpc_pickup_exception_date_' + id).val('');
                        break;
                    case 5:
                        // delivery exception
                        $('.wpc_delivery_exception_date_' + id).val('');
                        break;
                    case 6:
                        // delivery exception
                        $('.wpc_reservation_holiday__' + id).val('');
                        break;
                    case 7:
                        // multi all schedule
                        $('.multi_all_start_time_' + id).val('').timepicker('remove');
                        $('.multi_all_end_time_' + id).val('').timepicker('remove');
                        $('.all_schedule_name_' + id).val('');
                        $('.all_seat_capacity_' + id).val('');
                        break;
                    case 8:
                        // tip percentage
                        $('.wpc_pro_tip_percentage_data_' + id).val('');
                        break;
                    case 9:
                        //  Qr code block
                         $('.wpc_pro_qrcode_data_' + id).val('');
                        break;
                    default:
                        break;
                }
            })
        })

        function callBack ({ctx, parentClass, selectedValues, getMsgClass, weeklyStartTime, weeklyEndTime, type, startTime, endTime, status}) {
            var value = $(ctx).attr('class');
            var get_id  = $(ctx).parents(parentClass).data('id');

            var parent_block = $(ctx).parents(parentClass);

            if ($(ctx).is(":checked")) {
                var check = $.inArray(value, selectedValues);
                // if not exist , push in array
                if (check == -1) {
                    selectedValues.push(value);
                    var get_message = $(ctx).parents(parentClass).children(getMsgClass);
                    if (get_message.length > 0 ) {
                        get_message.html("")
                    }

                    var field_blocks           = [ parent_block.find(weeklyStartTime), ]
                    var field_dependent_blocks = [ parent_block.find(weeklyEndTime), ]

                    var type = type;
                    add_field_validation_error_content($, type, field_blocks, true);
                    add_field_validation_error_content($, type, field_dependent_blocks, false);                        

                } else {
                    $(ctx).prop("checked", false);
                    var day_exist = $("#multi_time_exist").data("day_exist");

                    alert(value.toUpperCase() + " " + day_exist );
                }

            } else {
                if(status == 'pickup'){
                    selectedValues.splice(selectedValues.indexOf(value), 1);
                }

                var checked_values = [];
                $(`.pickup_schedule_main_block ${parentClass}_${get_id} :checkbox`).each(function(){
                    var value = $(ctx).attr('class');
                    if($(ctx).is(":checked")){
                        checked_values.push(value);
                    }
                });
                if ( checked_values.length == 0 ) {
                    $(startTime + get_id ).val("").timepicker('remove');
                    $( endTime + get_id ).val("").timepicker('remove');

                    // remove error msg for this block
                    var get_message = $(ctx).parents(parentClass).children(getMsgClass);
                    if (get_message.length > 0 ) {
                        get_message.html("")
                    }

                    var disable_fields = parent_block.find(weeklyStartTime, weeklyEndTime);
                    if(![...selectedValues].length){
                        disable_all_fields(disable_fields);                         
                    }

                    hide_reset_button(parent_block.find(".wpc_pickup_weekly_clear"));
                }
                
            }
        }

        // pickup weekly schedule select
        $('.pickup_weekly_block :checkbox').each(function () {
            if ($(this).is(":checked")) {
                pickup_selected_values.push($(this).attr('class'))
            }
        });

        // TODO merge pickup_weekly_block and delivery_weekly_block onChange callback function(should check very carefully) 
        $('.pickup_schedule_main_block').on('change', '.pickup_weekly_block :checkbox', function(){ 
            const _this = this;
            callBack({ctx: _this, parentClass: ".pickup_weekly_block", selectedValues: pickup_selected_values, getMsgClass: ".wpc-default-guest-message", weeklyStartTime: ".wpc_pickup_weekly_schedule_start_time", weeklyEndTime: ".wpc_pickup_weekly_schedule_end_time", type: 'day_date_checked', startTime: ".wpc_pickup_start_time_", endTime: ".wpc_pickup_end_time_", status: 'pickup'});
        });

        
        // TODO merge pickup_weekly_block and delivery_weekly_block onChange callback function(should check very carefully)
        // delivery weekly schedule select
        $('.delivery_weekly_block :checkbox').each(function () {
            // console.log("this : ", $(this).is(":checked"));
            if ($(this).is(":checked")) {
                delivery_selected_values.push($(this).attr('class'))
            }else{

            }
        });

        $('.delivery_schedule_main_block').on('change', '.delivery_weekly_block :checkbox',  function(){
            const _this = this;
            if ($(this).is(":checked")) {
            }else{
                delivery_selected_values = [...delivery_selected_values].filter(item => item !== $(this).attr('class'))
            }
            if([...delivery_selected_values].length){
               $(".wpc_pro_time_picker")
            }
            callBack({ctx: _this, parentClass: ".delivery_weekly_block", selectedValues: delivery_selected_values, getMsgClass: ".wpc-default-guest-message", weeklyStartTime: ".wpc_delivery_weekly_schedule_start_time", weeklyEndTime: ".wpc_delivery_weekly_schedule_end_time", type: 'day_date_checked', startTime: ".wpc_delivery_start_time_", endTime: ".wpc_delivery_end_time_", status: 'delivery'});
        });

        // pickup exception schedule dynamically increase decrease

        var pickup_exception_obj = {
            wrapper_block: '.pickup_exception_block',
            parent_block: '.pick_add_section',
            second_wrapper: 'exception_block',
            append_wrapper: '.pickup_exception_main_block',
            button_wrapper: '.add_pickup_exception_block',
            date_name: 'wpc_pickup_exception_date',
            remove_button: 'remove_pickup_exception_block',
            clear_button: 'wpc_pickup_exception_date_clear',
        };

        add_repeating_block(pickup_exception_obj, 'order_time_exception'); 

        // remove pickup exception block
        var remove_pick_exc_block = {
            parent_block: '.pickup_exception_main_block',
            remove_button: '.remove_pickup_exception_block',
            removing_block: '.exception_block'
        };

        remove_block(remove_pick_exc_block);

        // delivery exception schedule dynamically increase decrease

        var delivery_exception_obj = {
            wrapper_block: '.delivery_exception_block',
            parent_block: '.delivery_add_section',
            second_wrapper: 'exception_block',
            append_wrapper: '.delivery_exception_main_block',
            button_wrapper: '.add_delivery_exception_block',
            date_name: 'wpc_delivery_exception_date',
            remove_button: 'remove_delivery_exception_block',
            clear_button: 'wpc_reservation_holiday_clear',
        };

        add_repeating_block(delivery_exception_obj, 'order_time_exception');

        // remove delivery exception block
        var remove_deliver_exc_block = {
            parent_block: '.delivery_exception_main_block',
            remove_button: '.remove_delivery_exception_block',
            removing_block: '.exception_block'
        };

        remove_block(remove_deliver_exc_block);

        // holiday reservation dynamically increase decrease

        var holiday_reservation_obj = {
            wrapper_block: '.holiday_exception_block',
            parent_block: '.holiday_add_section',
            second_wrapper: 'exception_block',
            append_wrapper: '.holiday_exception_main_block',
            button_wrapper: '.add_holiday_reservation_block',
            date_name: 'wpc_reservation_holiday',
            remove_button: 'remove_holiday_exception_block',
            clear_button: 'wpc_reservation_holiday_clear',
        };

        add_repeating_block(holiday_reservation_obj, 'holiday_time_exception');

        // remove delivery exception block
        var remove_holiday_reservation_block = {
            parent_block: '.holiday_exception_main_block',
            remove_button: '.remove_holiday_exception_block',
            removing_block: '.exception_block'
        };

        remove_block(remove_holiday_reservation_block);

        // reservation extra field dynamically increase decrease
        var reserve_extra_field_obj = {
            wrapper_block: '.extra_field_block',
            parent_block: '.reserv_extra_section',
            second_wrapper: 'schedule_block',
            append_wrapper: '.reserv_extra_main_block',
            button_wrapper: '.add_reserve_extra_block',
            reserv_field_required: 'wpc_extra_field_required',
            reserv_field_type: 'wpc_extra_field_type',
            label_name: 'reserv_extra_label',
            place_ho_name: 'reserv_extra_place_ho',
            remove_button: 'remove_reserve_extra_field',
            clear_button: 'wpc_extra_field_clear',
        };

        add_repeating_block(reserve_extra_field_obj, 'reservation_extra_field');

        // remove reservation extra field block
        var remove_reserv_extra_block = {
            parent_block: '.reserv_extra_main_block',
            remove_button: '.remove_reserve_extra_field',
            removing_block: '.schedule_block'
        };

        remove_block(remove_reserv_extra_block);

        // reservation multi slot dynamically increase decrease block
        var reserve_multi_slot_obj = {
            wrapper_block: '.multi_schedule_block',
            parent_block: '.multi_block_add_section',
            second_wrapper: 'multi_schedule_wrap',
            append_wrapper: '.multi_schedule',
            button_wrapper: '.add_multi_schedule',
            multi_start_time: 'multi_all_start_time',
            multi_end_time: 'multi_all_end_time',
            multi_start_name: 'multi_start_time',
            multi_end_name: 'multi_end_time',
            schedule_name: 'schedule_name',
            seat_capacity: 'seat_capacity',
            remove_button: 'remove_reserve_multi_field',
            clear_button: 'wpc_all_multi_clear'
        };

        add_repeating_block(reserve_multi_slot_obj, 'multi_slot_schedule');

        // reservation multi slot diff dynamically increase decrease block
        var reserve_multi_slot_diff_obj = {
            parent_block        : '.multi_schedule_block',
            parent_wrapper_block: 'schedule_block',
            wrapper_block       : '.schedule_block.week_diff_schedule_wrap',
            second_wrapper      : 'wpc-weekly-schedule-list',
            second_wrapper_extra: 'week_diff_schedule_wrap',
            append_wrapper      : '.multi_diff_block',
            button_wrapper      : '.add_multi_diff_schedule_block',
            button_class        : 'add_multi_diff_schedule_block',
            field_name          : 'multi_diff_weekly_schedule',
            start_time_name     : 'multi_diff_start_time',
            end_time_name       : 'multi_diff_end_time',
            schedule_name       : 'diff_schedule_name',
            seat_capacity       : 'diff_seat_capacity',
            remove_button       : 'remove_multi_schedule_block',
            clear_button        : 'wpc_multi_weekly_clear',
            validation_message  : 'multi_diff_weekly_clear',
            block_type          : '', //weekly
            start_class         : 'multi_diff_start_time',
            end_class           : 'multi_diff_end_time',
        };

        add_repeating_block(reserve_multi_slot_diff_obj, 'multi_slot_diff_schedule');

        // remove reservation multi different days  block
        var remove_reserv_ext_block = {
            parent_block: '.multi_diff_block',
            remove_button: '.remove_multi_schedule_block',
            removing_block: '.week_diff_schedule_wrap',
            type: 'multi_slot_diff',
        };

        remove_day_schedule_block(remove_reserv_ext_block);

        add_multi_time_block_row(reserve_multi_slot_diff_fields);

        // remove reservation multi slot different time block
        var remove_diff_multi_time_block = {
            parent_block: '.multi_diff_block',
            remove_button: '.remove_multi_time_block',
            removing_block: '.wpc-schedule-field'
        };
        remove_block(remove_diff_multi_time_block);

        // remove reservation multi slot allday time block
        var remove_multi_time_block = {
            parent_block: '.multi_schedule',
            remove_button: '.remove_reserve_multi_field',
            removing_block: '.wpc-schedule-field'
        };

        remove_block(remove_multi_time_block);

        /* tip percentage add/remove */
        // custom tip percentage data addition
        var tip_percentage_obj = {
            wrapper_block: '.tip_percentage_block', // textbox wrapper
            parent_block: '.tip_percentage_add_section', // button_wrapper parent
            second_wrapper: 'percentage_block', // textbox 2nd wrapper
            append_wrapper: '.tip_percentage_main_block', // main block
            button_wrapper: '.add_tip_percentage_block', 
            data_name:      'wpc_pro_tip_percentage_data',
            remove_button: 'remove_tip_percentage_block',
            clear_button: 'wpc_pro_tip_percentage_data_clear',
        };
        add_repeating_block(tip_percentage_obj, 'tip_percentage_addition');

        // remove tip percentage block
        var remove_tip_percentage_block = {
            parent_block: '.tip_percentage_main_block',
            remove_button: '.remove_tip_percentage_block',
            removing_block: '.percentage_block'
        };
        remove_block(remove_tip_percentage_block);


        // qrcode data addition
        var qrcode_obj = {
            wrapper_block: '.qrcode_block', // textbox wrapper
            parent_block: '.qrcode_add_section', // button_wrapper parent
            second_wrapper: 'qrcode_block', // textbox 2nd wrapper
            append_wrapper: '.qrcode_main_block', // main block
            button_wrapper: '.add_qrcode_block', 
            data_name:      'wpc_pro_qrcode_data',
            remove_button: 'remove_qrcode_block',
            clear_button: 'wpc_pro_qrcode_data_clear',
            data_name2: 'wpc_pro_qrcode_id',
        };
        add_repeating_block(qrcode_obj, 'qrcode_addition');

        // remove qrcode block
        var remove_qrcode_block = {
            parent_block: '.qrcode_main_block',
            remove_button: '.remove_qrcode_block',
            removing_block: '.qrcode_block'
        };
        remove_block(remove_qrcode_block);

        function remove_day_schedule_block( obj  ) {
            $(obj.parent_block).on( 'click' , obj.remove_button , function(e) {
                 // TODO event bubbling
                e.preventDefault(); 
                $(this).parent( obj.removing_block ).remove();

                switch ( obj.type ) {
                    case "pickup":

                        pickup_selected_values = [];
                        $('.pickup_weekly_block :checkbox').each(function(){
                            if($(this).is(":checked")){
                                var value = $(this).attr('class');
                                pickup_selected_values.push(value);
                            }
                        });

                        // re-order data id
                        $('.pickup_weekly_block').each( (index , value )=>{
                            var get_id = $(value).attr('data-id');
                            $(value)
                            .removeClass('pickup_weekly_block_'+get_id)
                            .addClass('pickup_weekly_block_'+index)
                            .attr('data-id',index);
                        });

                        break;

                    case "delivery":

                        delivery_selected_values = [];
                        $('.delivery_weekly_block :checkbox').each(function(){
                            if($(this).is(":checked")){
                                var value = $(this).attr('class');
                                delivery_selected_values.push(value);
                            }
                        });

                        // re-order data id
                        $('.delivery_weekly_block').each( (index , value )=>{
                            var get_id = $(value).attr('data-id');
                            $(value)
                            .removeClass('delivery_weekly_block_'+get_id)
                            .addClass('delivery_weekly_block_'+index)
                            .attr('data-id',index);
                        });

                        break;

                    case "multi_slot_diff":
                        selected_diff_values = [];
                        $('.week_diff_schedule_wrap :checkbox').each(function(){
                            if($(this).is(":checked")){
                                selected_diff_values.push($(this).attr('class').replace(/\d+/g, ''))
                            }
                        });

                        break;
                
                    default:
                        break;
                }

            });
        }

        // add repeating block
        function add_repeating_block(obj, block_name = '') {
            var increase = $(obj.wrapper_block).length;
            $(obj.parent_block).on('click', obj.button_wrapper, function (e) {

                var $this = $(this);

                switch (block_name) {
                    //TODO merged 2 case into 1, as they share same markup
                    case "order_time_exception":
                    case "holiday_time_exception":
                        var dateText          = $(this).data("date_text");
                        var clear_button_text = $(this).data("clear_button_text");
                        $(obj.append_wrapper).append(
                            `<div class="${obj.second_wrapper} d-flex mb-2">
                            <input type="text" name="${obj.date_name}[]" class="${obj.date_name} ${obj.date_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${dateText} " id="${obj.date_name}_${increase}" />
                            <span class="${obj.clear_button} " id="${increase}" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="${clear_button_text}"> <small class="wpc-tooltip-angle"></small></span></span> 
                            <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position"></span>
                            </div>`);

                        $("#"+obj.date_name + '_' + increase ).flatpickr();

                    break;

                    case "reservation_extra_field":
                        var reserv_extra_type = $(this).data("reserv_extra_type");
                        var label_text        = $(this).data("label_text");
                        var placeholder_text  = $(this).data("placeholder_text");
                        var clear_button_text = $(this).data("clear_button_text");
                        $(obj.append_wrapper).append(
                            `<div class="wpc-schedule-field ${obj.second_wrapper} d-flex mb-2">
                            <select name='${obj.reserv_field_required}[${increase}]' class="wpc-settings-input wpc-form-control">
                                <option value="optional" selected>Optional</option>
                                <option value="required">Required</option>
                            </select>
                            <select name="${obj.reserv_field_type}[${increase}]" class="mr-1 wpc-settings-input wpc-form-control ${obj.reserv_field_type} ${obj.reserv_field_type}_${increase}" data-current_extra_block_index="${increase}" id="${obj.reserv_field_type}_${increase}">
                            <option value="text">Text</option>
                            <option value="checkbox">Checkbox</option>
                            </select>
                            <input type="text" name="${obj.label_name}[${increase}]" class="${obj.label_name} ${obj.label_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${label_text}" id="${obj.label_name}_${increase}" />
                            <input type="text" name="${obj.place_ho_name}[${increase}]" class="${obj.place_ho_name} ${obj.place_ho_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${placeholder_text}" id="${obj.place_ho_name}_${increase}" />
                            <span class="${obj.clear_button}" id="${increase}" > <span class="dashicons dashicons-update-alt wpc-tooltip" data-title="${clear_button_text}"> <small class="wpc-tooltip-angle"></small></span></span> 
                            <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position"></span>
                            </div>`);
                        break;
                    case "multi_slot_schedule":
                        var start_time      = $this.data("start_time");
                        var end_time        = $this.data("end_time");
                        var schedule_name   = $this.data("schedule_name");
                        var seat_capactiy   = $this.data("seat_capactiy");
                        var clear_text      = $this.data("clear_text");
                        var remove_text     = $this.data("remove_text");

                        $(obj.append_wrapper).append(
                            `<div class="wpc-schedule-field ${obj.second_wrapper} d-flex mb-2">
                            <input type="text" name="${obj.multi_start_name}[]" id="${increase}" class="${obj.multi_start_time}_${increase} multi_start_time multi_all_start_time wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${start_time}" id="${obj.multi_start_time}_${increase}" />
                            <input type="text" name="${obj.multi_end_name}[]"  id="${increase}" class="${obj.multi_end_time}_${increase} multi_end_time multi_all_end_time wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${end_time}" id="${obj.multi_end_time}_${increase}" disabled="disabled" />
                            <input type="text" name="${obj.schedule_name}[]" class="all_${obj.schedule_name} all_${obj.schedule_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${schedule_name}" id="${obj.schedule_name}_${increase}" />
                            <input type="number" name="${obj.seat_capacity}[]" min="1" class="all_${obj.seat_capacity} all_${obj.seat_capacity}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${seat_capactiy}" id="${obj.seat_capacity}_${increase}" />
                            <span class="${obj.clear_button}" id="${increase}">  <span class="dashicons dashicons-update-alt wpc-tooltip" data-title="${clear_text}"> <small class="wpc-tooltip-angle"></small></span> </span>
                            <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position wpc-tooltip" data-title="${remove_text}"><small class="wpc-tooltip-angle"></small></span>
                            </div>
                            <div class="allday_multi_message_${increase} wpc-default-guest-message"></div>`
                        );
                        break;
                    case "multi_slot_diff_schedule":
                        var start_time      = $this.data("diff_start_time");
                        var end_time        = $this.data("diff_end_time");
                        var schedule_name   = $this.data("diff_schedule_name");
                        var seat_capacity   = $this.data("diff_seat_capacity");
                        var clear_text      = $this.data("clear_text");
                        var addMore_btn_text= $this.data("same-day-title");
                        var remove_text     = $this.data("remove_text");
                        var week_days       = ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'];
                        var html = "";
                        let blocks = $( '.multi_diff_block .week_diff_schedule_wrap' ).length;

                        multi_block ++;
                            
                        if( blocks <=7  ){
     
                            jQuery( obj.append_wrapper ).append(
                                `<div class="${obj.parent_wrapper_block} ${obj.second_wrapper_extra} ${obj.second_wrapper_extra}_${multi_block} " 
                                data-schedule_diff_block="${multi_block}">
                                <div class="${obj.second_wrapper}">
                                ${jQuery.map( week_days , function( day , key ){
                                    var day_lower = day.toLowerCase();
                                    html +='<input type="checkbox" name="'+obj.field_name+'['+multi_block+']['+day+']" class="'+day_lower+multi_block+'" id="'+day_lower+multi_block+'"/><label for="'+day_lower+multi_block+'">'+day+'</label>'
                                })}
                                ${html}</div>
                                <div class="schedule_block wpc-schedule-field" data-id="${multi_block}0">
                                <input type="text" name="${obj.start_time_name}[${multi_block}][0]" class="${obj.start_time_name} ${obj.start_time_name}_${multi_block} ${obj.start_class}_${multi_block}0 wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" id="${multi_block}0" placeholder="${start_time }"/>
                                <input type="text" name="${obj.end_time_name}[${multi_block}][0]" class="${obj.end_time_name} ${obj.end_time_name}_${multi_block} ${obj.end_class}_${multi_block}0  wpc-settings-input attr-form-control" id='${multi_block}0' placeholder="${end_time} " disabled="disabled" />
                                <input type="text" name="${obj.schedule_name}[${multi_block}][0]" class="${obj.schedule_name} ${obj.schedule_name}_${multi_block} ${obj.schedule_name}_${multi_block}0  wpc-settings-input attr-form-control" id='${multi_block}0' placeholder="${schedule_name} " />
                                <input type="number" name="${obj.seat_capacity}[${multi_block}][0]" class="${obj.seat_capacity} ${obj.schedule_name}_${multi_block} ${obj.seat_capacity}_${multi_block}0 wpc-settings-input attr-form-control" id='${multi_block}0' placeholder="${seat_capacity} "/>
                                <span class="${obj.clear_button}" id='${multi_block}0'>  <span class="dashicons dashicons-update-alt wpc-tooltip" data-title="${clear_text}"> <small class="wpc-tooltip-angle"></small></span> </span>
                                </div>
                                <span class="dashicons wpc-btn dashicons-plus add_multi_time_block pl-1 wpc-tooltip" data-clear_text="${clear_text}" data-remove_text="${remove_text}" data-title="${addMore_btn_text}" data-diff_start_time="${start_time} " data-diff_end_time="${ end_time}" data-diff_seat_capacity="${ seat_capacity}" data-diff_schedule_name="${ schedule_name} "><small class="wpc-tooltip-angle"></small></span>
                                <div class="weekly_multi_message_${multi_block}0' wpc-default-guest-message"></div>
                                <div class="${obj.validation_message}_${multi_block} wpc-default-guest-message"></div>
                                <span class="dashicons dashicons-no-alt ${obj.remove_button} wpc-btn-close wpc-tooltip" data-title="${remove_text}"><small class="wpc-tooltip-angle"></small></span>
                                </div>`);

                            blocks++;

                        }
                        break;
                    case "tip_percentage_addition":
                        var dataText          = $(this).data("pc_text");
                        var clear_button_text = $(this).data("clear_button_text");
                        $(obj.append_wrapper).append(
                            `<div class="${obj.second_wrapper} d-flex mb-2 wpc-schedule-field">
                            <input type="number" min="0" name="${obj.data_name}[]" class="${obj.data_name} ${obj.data_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${dataText}" id="${obj.data_name}_${increase}" />
                            <span class="${obj.clear_button} " id="${increase}" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="${clear_button_text}"> <small class="wpc-tooltip-angle"></small></span></span> 
                            <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position"></span>
                            </div>`);

                        break;

                    case "qrcode_addition":
                        var dataText          = $(this).data("pc_text");
                        var table_name          = $(this).data("table-name");
                        var clear_button_text = $(this).data("clear_button_text");
                        $(obj.append_wrapper).append(
                            `<div class="${obj.second_wrapper} d-flex mb-2 wpc-schedule-field">
                            <label class="wpc-qr-info-label wpc-qr-info-label-id"> <span class="wpc-qr-info-labe-txt">${table_name}</span>
                            <input type="text" name="${obj.data_name2}[]" class="${obj.data_name2} ${obj.data_name2}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${table_name}" id="${obj.data_name2}_${increase}" />
                            </label>
                            <label class="wpc-qr-info-label"> <span class="wpc-qr-info-labe-txt">${dataText}</span>
                            <input type="text" name="${obj.data_name}[]" class="${obj.data_name} ${obj.data_name}_${increase} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${dataText}" id="${obj.data_name}_${increase}" />
                            </label>
                            <div class="wpc-qr-img"></div>
                            <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position"></span>
                            </div>`);

                        break;
                    default:
                        break;
                }

                increase++;
            });
        }

        var warning_message = typeof wpc_pro_admin_object !== "undefined" ? wpc_pro_admin_object.warning_message : "";
        var warning_icon = typeof wpc_pro_admin_object !== "undefined" ? wpc_pro_admin_object.warning_icon : "";
        var extra_main_block = $('.reserv_extra_main_block');
        // extra type select(option) change
        extra_main_block.on('change', '.wpc_extra_field_type', function () {
            var $this = $(this);
            var selected_type = $(this).val();

            var special_types = [
                'checkbox'
            ];

            // force to fill up label of checkbox.
            var current_index = $this.attr('data-current_extra_block_index');
            var get_id = $("#reserv_extra_label_" + current_index);
            var get_label = get_id.val();
            var block_length = $(".schedule_block").length;
            var block = $(".warning_" + block_length);

            if (get_label == "" && block.length == 0) {
                get_id.addClass('wpc-field-invalid').after("<span class='warning_" + block_length + "'>" + warning_icon + warning_message + "</span>");
            } else {
                get_id.removeClass('wpc-field-invalid');
                block.remove();
            }

            // in case of date/radio/checkbox type, hide placeholder field
            if (special_types.includes(selected_type)) {
                $(this).siblings('.reserv_extra_place_ho').css('display', 'none');
            } else {
                $(this).siblings('.reserv_extra_place_ho').css('display', 'block');
            }

            // checkbox section logic
            if (selected_type == 'checkbox') {
                var already_checkbox_block_exist = $(this).siblings('.wpc_extra_type_checkbox_main_block').length;

                if (already_checkbox_block_exist == 1) {
                    $(this).siblings('.wpc_extra_type_checkbox_main_block').css('display', 'block');
                } else {
                    // add checkbox block markup
                    var checkbox_placeholder_text = $('.add_reserve_extra_block').data("checkbox_placeholder_text");
                    var checkbox_add_btn_text = $('.add_reserve_extra_block').data("checkbox_add_btn_text");

                    var current_extra_block_index = $(this).data('current_extra_block_index');

                    console.log(current_extra_block_index);

                    // may delete later
                    var checkbox_text_field = '<div class="wpc_extra_type_checkbox_block mb-2">' +
                        '<input type="text" name="wpc_extra_field_option[' + current_extra_block_index + '][0]" value=""' +
                        'id="wpc_extra_type_' + current_extra_block_index + '_checkbox_0" class="wpc_extra_type_checkbox mr-1 wpc-settings-input wpc-form-control"' +
                        'placeholder="' + checkbox_placeholder_text + '" />' +
                        '</div>';

                    var checkbox_add_more_btn = '<div class="wpc_flex_reverse wpc_extra_type_checkbox_section">' +
                        '<span class="add_wpc_extra_type_checkbox_block wpc-btn-text"' +
                        'data-checkbox_placeholder_text="' + checkbox_placeholder_text + '"' +
                        'data-next_add_time_checkbox_parent_index="' + current_extra_block_index + '" data-next_add_time_checkbox_index="1">' +
                        checkbox_add_btn_text +
                        '</span>' +
                        '</div>';

                    var checkbox_block_markup = '<div class="wpc_extra_type_checkbox_main_block">' +
                        checkbox_text_field +
                        checkbox_add_more_btn +
                        '</div>';

                    $(this).next().after(checkbox_block_markup);
                }
            } else {
                $(this).siblings('.wpc_extra_type_checkbox_main_block').css('display', 'none');
            }
        });

        // add more attendee extra type checkbox field
        extra_main_block.on('click', '.add_wpc_extra_type_checkbox_block', function (e) {
            var checkbox_placeholder_text = $(this).data("checkbox_placeholder_text");

            var checkbox_parent_index = parseInt($(this).attr("data-next_add_time_checkbox_parent_index"));
            var checkbox_index = parseInt($(this).attr("data-next_add_time_checkbox_index"));

            var new_checkbox_markup = '<div class="wpc_extra_type_checkbox_block mb-2">' +
                '<input type="text" name="wpc_extra_field_option[' + checkbox_parent_index + '][' + checkbox_index + ']" value="" class="wpc_extra_type_checkbox mr-1 wpc-settings-input wpc-form-control" id="wpc_extra_type_' + checkbox_parent_index + '_checkbox_' + checkbox_index + '" placeholder="' + checkbox_placeholder_text + '" />' +
                '<span class="wpc-btn-close dashicons dashicons dashicons-no-alt remove_wpc_extra_type_checkbox_field pl-1"></span>' +
                '</div>';

            $(this).closest('.wpc_extra_type_checkbox_main_block')
                .children('.wpc_extra_type_checkbox_block:last').after(new_checkbox_markup);

            $(this).attr('data-next_add_time_checkbox_index', checkbox_index + 1);
        });

        // remove attendee extra type checkbox field
        extra_main_block.on('click', '.remove_wpc_extra_type_checkbox_field', function (e) {
        $(this).parent().remove();
        });

        // remove reservation multi slot in different day block
        var remove_diff_multi_repeating_block = {
            parent_block: '.schedule_main_block',
            remove_button: '.remove_multi_schedule_block',
            removing_block: '.week_diff_schedule_wrap',
            type:'multi_slot_same',
        };

        remove_diff_block(remove_diff_multi_repeating_block);

        // remove diff block
        function remove_diff_block( obj ) {
            jQuery(obj.parent_block).on( 'click' , obj.remove_button , function(e) {
                e.preventDefault();
                let parent_id = $(this).parent( obj.removing_block ).data('schedule_diff_block');
                $(this).closest(obj.parent_block).find(`${obj.removing_block}[data-schedule_diff_block="${parent_id}"]`).remove();
            });
        }

        // add child rows start/end time for multi slot weekdays
        function add_multi_time_block_row(obj) {

            $(obj.main_wrapper).on('click', obj.button_wrapper, function (e) {

                let parent_id = $(this).parent( obj.parent_block ).data('schedule_diff_block');
                let index     = $(this).parent( obj.parent_block ).find('.schedule_block').length;
                let increase  = index++;

                let start_time      = $(this).data("diff_start_time");
                let end_time        = $(this).data("diff_end_time");
                let schedule_name   = $(this).data("diff_schedule_name");
                let seat_capacity   = $(this).data("diff_seat_capacity");
                let clear_text      = $(this).data("clear_text");
                let remove_text     = $(this).data("remove_text");
                $( $(this).parent( ''+obj.append_wrapper+'' ) ).append(
                    `<div class="wpc-schedule-field ${obj.second_wrapper}  ${obj.second_wrapper}_${parent_id}" data-id="${parent_id}">
                    <input type="text" name="${obj.multi_start_time}[${parent_id}][]" id="${parent_id}${increase}" class="${obj.multi_start_time} ${obj.multi_start_time}_${ parent_id} ${obj.multi_start_time}_${parent_id}${increase} multi_start_time  wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${ start_time}" id="${obj.multi_start_time}_${parent_id}${increase}" />
                    <input type="text" name="${obj.multi_end_time}[ ${parent_id}][]"   id="${parent_id}${increase}" class="${obj.multi_end_time} ${obj.multi_end_time}_${parent_id} ${obj.multi_end_time}_${parent_id}${increase} multi_end_time wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${end_time}" id="${obj.multi_end_time}_${parent_id}${increase}" disabled="disabled"/>
                    <input type="text" name="${obj.schedule_name}[ ${parent_id}][]" class="${obj.schedule_name} ${obj.schedule_name}_ ${parent_id+increase} ${obj.schedule_name}_${parent_id} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${schedule_name}" id="${obj.schedule_name}_${parent_id}${increase}" />
                    <input type="number" name="${obj.seat_capacity}[ ${parent_id}][]" min="1" class="${obj.seat_capacity}  ${obj.seat_capacity}_ ${parent_id} ${increase} ${ obj.seat_capacity}_${parent_id} wpc_mt_two wpc-mr-one wpc-settings-input attr-form-control" placeholder="${seat_capacity}" id="${obj.seat_capacity}_${parent_id}${increase}" />
                    <span class="${obj.clear_button}" id="${parent_id}${increase}"> <span class="dashicons dashicons-update-alt wpc-tooltip" data-title=" ${clear_text}"> <small class="wpc-tooltip-angle"></small></span> </span>
                    <span class="wpc-btn-close dashicons dashicons-no-alt ${obj.remove_button} wpc_icon_middle_position wpc-tooltip" data-title=" ${remove_text}"><small class="wpc-tooltip-angle"></small></span>
                    <div class="weekly_multi_message_${parent_id}${increase} wpc-default-guest-message"></div>
                    </div>`);
            });
        }

        var date_format = "Y-m-d";
        if ( typeof admin_object !=="undefined"  && typeof admin_object.reser_date_format !=="undefined" ) {
            date_format = admin_object.reser_date_format;
        }

        // datepicker for reservation report filter
        $("#booking_date").flatpickr({
            dateFormat: date_format,
        })

            // single / multi slot show hide

            // for reservation schedule
            var multi_schedule          = $(".wpc-multi-slot-tab");
            var single_schedule         = $(".single_schedule");
            var reser_multi_schedule    = $("#reser_multi_schedule");
            
            // show hide tab
            toggle_tab(multi_schedule,single_schedule,reser_multi_schedule);

            // for zapier
            var zap_web_hook            = $(".zap_web_hook");
            var single_hook             = null;
            var wpc_allow_zapier        = $("#wpc_allow_zapier");

            // show hide tab
            toggle_tab(zap_web_hook,single_hook,wpc_allow_zapier);

            // for pabbly
            var pabbly_web_hook         = $(".pabbly_web_hook");
            var single_hook             = null;
            var wpc_allow_pabbly        = $("#wpc_allow_pabbly");

            // show hide tab
            toggle_tab(pabbly_web_hook,single_hook,wpc_allow_pabbly);

            function toggle_tab(tab_one,tab_two,toggle_name) {
                if (toggle_name.is(':checked')) {
                    if ( tab_two !==null ) {
                        tab_two.addClass("hide_field");
                    }
                }
                toggle_name.on('change', function () {

                    if ($(this).is(':checked')) {
                        tab_one.fadeIn();
                        tab_one.removeClass("hide_field");
                        if ( tab_two !==null ) {
                            tab_two.fadeOut();
                        }
        
                    } else {
                        tab_one.addClass("hide_field");
                        tab_one.fadeOut();
                        if ( tab_two !==null ) {
                            tab_two.fadeIn();
                            tab_two.removeClass("hide_field");
                        }
                    }
                });

            }


            // checkbox default show hide
            var checkbox_arr = ['#show_form_field', '#required_from_field', '#show_to_field', '#required_to_field', '#wpc_pro_allow_delivery_date', '#wpc_pro_allow_pickup_date'];
            checkbox_default_show_hide($, checkbox_arr)

            // check license
            $("form#wpc-admin-license-form").on("submit", function (e) {
                e.preventDefault();
                var __this = $(this),
                    edd_action_type = "activate_license",
                    license_key = __this.find("#wpc-admin-option-text-wpc-license-key").val();
                var successText = "Congratulations! Your product is activated. Refreshing..."; //TODO success/failure text should be translatable
                var failureText = "Invalid Credentials";

                $.ajax({
                    url: admin_object.ajax_url,
                    type: "POST",
                    data: {
                        action: 'activate_wpcafe_license',
                        edd_action_type: edd_action_type,
                        license_key: license_key
                    },
                    success: function (response) {
                        if (response == "valid") {
                            var content = "<div class='wpc-license-form-result'><p class='attr-alert attr-alert-success'>" + successText + "<\/p><\/div>";
                            __this.parents(".wpc-license-module-parent").find(".wpc-license-result-box").html(content);
                            location.reload();
                        } else {
                            var content = "<div class='wpc-license-form-result'><p class='attr-alert attr-alert-warning'>" + failureText + "<\/p><\/div>";
                            __this.parents(".wpc-license-module-parent").find(".wpc-license-result-box").html(content);
                        }
                    },
                    error: function (response) {
                    }

                });
            });

            $('.wpc-revoke-license-text').on('click', function(e){
                var __this = $(this),
                    edd_action_type = "deactivate_license",
                    successText = 'License Revoked! Refreshing...',
                    failureText  = 'Could not revoke license. Please try again!'; //TODO success/failure text should be translatable
                $.ajax({
                    url: admin_object.ajax_url,
                    type: "POST",
                    data: {
                        action: 'deactivate_license',
                        edd_action_type: edd_action_type
                    },
                    success: function (response) {

                        if( response == 'deactivated' ){
                            __this.parents('.wpc-license-form-result').find('.attr-alert-success').empty().html( successText );
                            location.reload();
                        } else {
                            __this.parents('.wpc-license-form-result').find('.attr-alert-success').empty().html( failureText );
                        }
                    },
                    error: function (data) {
                    }
                });
            });

            $(".wpc-select-marketplace").on("change", function(e){
                var __this = $(this);
                var selectedVal = __this.val();
                var parentDiv   = __this.parents(".wpc-license-module-parent");
                if( selectedVal == "codecanyon" ){
                    parentDiv.find(".wpc-marketplace-codecanyon").css("display", "block");
                    parentDiv.find(".wpc-marketplace-themewinter").css("display", "none");
                }else if( selectedVal == "themewinter" ){
                    parentDiv.find(".wpc-marketplace-codecanyon").css("display", "none");
                    parentDiv.find(".wpc-marketplace-themewinter").css("display", "block");
                } else {
                    parentDiv.find(".wpc-marketplace-codecanyon").css("display", "none");
                    parentDiv.find(".wpc-marketplace-themewinter").css("display", "none");
                }
            });

            $(".wpc-select-marketplace").trigger("change");


            $(".wpc-btn-save-marketplace").on("click", function(){
                var __this = $(this);
                var marketPlaceValue = __this.parents(".wpc-license-module-parent").find(".wpc-select-marketplace").val();
                var successText = "Marketplace Saved"; //TODO success/failure text should be translatable
                var failureText = "Couldn't save marketplace value";
                var action_url = admin_object.ajax_url;
                $.ajax({
                    url: action_url,
                    type: "POST",
                    data: {
                        action: 'save_market_place',
                        market_place: marketPlaceValue
                    },
                    success: function (response) {

                        if( response == "valid" ){
                            var content = `<div class='wpc-license-form-result'><p class='attr-alert attr-alert-success'>${successText}<\/p><\/div>`;
                        } else {
                            var content = `<div class='wpc-license-form-result'><p class='attr-alert attr-alert-warning'>${failureText}<\/p><\/div>`;
                        }
                        __this.parents(".wpc-license-module-parent").find(".wpc-marketplace-save-result").html(content);
                    },
                    error: function (data) {
                    }
                });
            });

            /**********************
             Weekly Multi slot validation start
            **********************/

            // multi slot weekly schedule select
            $('.week_diff_schedule_wrap :checkbox').each(function(){
                if($(this).is(":checked")){
                    selected_diff_values.push($(this).attr('class').replace(/\d+/g, ''))
                }
            });

            $('.multi_diff_block').on( 'change' , '.week_diff_schedule_wrap :checkbox' , function(){

                var multi_all_start_time = $(".multi_all_start_time").val();
                var multi_all_end_time   = $(".multi_all_end_time").val();

                if( multi_all_start_time == "" && multi_all_end_time == "" ){
                    var diff_value      = $(this).attr('class').replace(/\d+/g, '');

                    var get_id          = $(this).parents(".week_diff_schedule_wrap").data('schedule_diff_block');

                    var multi_diff_start_time   = $('.multi_diff_block').find(".multi_diff_start_time_"+get_id)

                    var multi_diff_end_time     = $('.multi_diff_block').find(".multi_diff_end_time_"+get_id)
                    var diff_schedule_name      = $(".diff_schedule_name_"+get_id);
                    var diff_seat_capacity      = $(".diff_seat_capacity_"+get_id);
                    if( $(this).is(":checked") ){
                        var check_day = $.inArray(diff_value , selected_diff_values );

                        // if not exist , push in array
                        if( check_day == -1 ){

                            selected_diff_values.push(diff_value);

                            if ( !multi_diff_start_time.hasClass("wpc_field_error") && multi_diff_start_time.val() == "" ) {
                                multi_diff_start_time.addClass("wpc_field_error").removeAttr("disabled");
                            }
                            if ( !multi_diff_end_time.hasClass("wpc_field_error") && multi_diff_end_time.val() == "" ) {
                                multi_diff_end_time.addClass("wpc_field_error").removeAttr("disabled");
                            }
                            if ( !diff_schedule_name.hasClass("wpc_field_error") && diff_schedule_name.val() == "" ) {
                                diff_schedule_name.addClass("wpc_field_error").removeAttr("disabled");
                            }
                            if ( !diff_seat_capacity.hasClass("wpc_field_error") && diff_seat_capacity.val() == "" ) {
                                diff_seat_capacity.addClass("wpc_field_error").removeAttr("disabled");
                            }

                        }else{
                            $(this).prop("checked", false);
                            var day_exist = $("#multi_time_exist").data("day_exist");
                            alert( diff_value.toUpperCase() + " " + day_exist );
                        }
                    }
                    else{
                        selected_diff_values.splice(selected_diff_values.indexOf(diff_value),1);
                        var checked_values = [];
                        $('.multi_diff_block .week_diff_schedule_wrap_'+get_id+' :checkbox').each(function(){
                            if($(this).is(":checked")){
                                checked_values.push(diff_value);
                            }
                        });


                        if ( checked_values.length == 0 ) {

                            multi_diff_start_time.val("").removeClass("wpc_field_error").prop("disabled", true);
                            multi_diff_end_time.val("").removeClass("wpc_field_error").prop("disabled", true);
                            diff_schedule_name.val("").removeClass("wpc_field_error").prop("disabled", true);
                            diff_seat_capacity.val("").removeClass("wpc_field_error").prop("disabled", true);

                            $('input.multi_all_start_time, input.multi_all_end_time, input.all_schedule_name, input.all_seat_capacity').removeAttr("disabled");
                        }

                    }

                }
                else
                {
                    var schedule_block = $(".week_diff_schedule_wrap :checkbox");
                    $(schedule_block). prop("checked", false);
                    var multi_diff_block =$("#multi_diff_block").data("multi_diff_block");
                    alert( multi_diff_block );
                }
            });

            // multi slot all day schedule
            $(".multi_schedule").on('focus',".multi_all_start_time,.multi_all_end_time,.all_schedule_name, .all_seat_capacity", function(){
                
                if( selected_diff_values.length == 0 ){
                    $('input.multi_all_start_time, input.multi_all_end_time, input.all_schedule_name, input.all_seat_capacity').removeAttr("disabled");
                    if($(this).hasClass("multi_start_time") || $(this).hasClass("multi_end_time") 
                    || $(this).hasClass("multi_all_start_time") || $(this).hasClass("multi_all_end_time") ){

                        var clicked_start         = true;

                        var current_id            = $(this).attr('id');
                        var related_picker_class  = '';
        
                        if ( $(this).hasClass("multi_start_time") || $(this).hasClass("multi_all_start_time") ) {
                            related_picker_class = '.multi_all_end_time_' + current_id;
                        } else {
                            clicked_start        = false;
                            related_picker_class = '.multi_all_start_time_' + current_id;
                        }

                        initialize_any_time_picker($, this, clicked_start, related_picker_class);
                        time_picker( $, $(this) , 'multi_all_day', clicked_start, related_picker_class );
                    }

                }else{

                    $('input.multi_all_start_time, input.multi_all_end_time, input.all_schedule_name, input.all_seat_capacity').prop( 'disabled', 'disabled' ).val("");
                    var every_diff_block =$("#every_diff_block").data("every_diff_block");
                    alert( every_diff_block );

                    return;
                }
            });

            $(".multi_diff_block").on("focus",".diff_schedule_name, .diff_seat_capacity", function(){
                $(this).removeClass('wpc_field_error');
            });

            // multi slot different weekday schedule
            $(".multi_diff_block").on('focus',".multi_diff_start_time, .multi_diff_end_time", function(){
                if( selected_diff_values.length > 0 ){
                    $('input.multi_diff_start_time, input.multi_diff_end_time, input.diff_schedule_name, input.diff_seat_capacity').removeAttr("disabled");
                    var clicked_start         = true;

                    var current_id            = $(this).attr('id');
                    var related_picker_class  = '';

                    if ( $(this).hasClass("multi_diff_start_time") ) {
                        related_picker_class = '.multi_diff_end_time_' + current_id;
                    } else {
                        clicked_start        = false;
                        related_picker_class = '.multi_diff_start_time_' + current_id;
                    }

                    initialize_any_time_picker($, this, clicked_start, related_picker_class);

                    time_picker( $, $(this) , 'multi_diff_days', clicked_start, related_picker_class );

                    $(this).removeClass("wpc_field_error");
                }
            });
        });

        // whenever tip is enabled or not: toggle tip enabled blocks
        $('#wpc_pro_tip_enable').on('click', function(){
            $('.wpc-pro-tip-enabled-block').slideToggle();
        });
        // special menu of
        $('#enable_special_menu').on('click', function(){
            $('.special-menu-block').slideToggle();
        });

        // sound notifications option enable or not
        $('#wpc_pro_sound_notify').on('click', function(){
            $('.wpc-pro-sound-enabled-block').slideToggle();
        });

        // repeat sound option enable or not
        $('#wpc_pro_sound_repeat').on('click', function(){
            $('.wpc-pro-interval-enabled').slideToggle();
        });

})(jQuery);

// print reservation details
function wpc_pro_pirnt_content_area(divContents) {
    "use strict";
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    // TODO why we need if/else here as they use same code
   
    if (divContents == "print_reservation_details") {
        mywindow.document.write('<style type="text/css">' +
            'table{' +
            'width:100%;' +
            '}' +
            'table th, table td {' +
            'border:1px solid #000;' +
            'padding:0.5em;' +
            '}' +
            '</style>');
    } else {
        mywindow.document.write('<style type="text/css">' +
            'table{' +
            'width:100%;' +
            '}' +
            'table th, table td {' +
            'border:1px solid #000;' +
            'padding:0.5em;' +
            '}' +
            '</style>');
    }
    mywindow.document.write('</head><body >');
    mywindow.document.write(document.getElementById(divContents).innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    return true;
}


// backup for delivery weekly block


// function () {
//     var value   = $(this).attr('class');
//     var get_id  = $(this).parents(".delivery_weekly_block").data('id');
    
//     var parent_block = $(this).parents(".delivery_weekly_block");

//     if ($(this).is(":checked")) {
//         var check = $.inArray(value, delivery_selected_values);
//         // if not exist , push in array
//         if (check == -1) {

//             delivery_selected_values.push(value);
//             var get_message = $(this).parents(".delivery_weekly_block").children(".wpc-default-guest-message");
//             if (get_message.length > 0 ) {
//                 get_message.html("")
//             }

//             var field_blocks           = [ parent_block.find(".wpc_delivery_weekly_schedule_start_time"), ]
//             var field_dependent_blocks = [ parent_block.find(".wpc_delivery_weekly_schedule_end_time"), ]

//             var type = 'day_date_checked';
//             add_field_validation_error_content($, type, field_blocks, true);
//             add_field_validation_error_content($, type, field_dependent_blocks, false);    

//         } else {
//             $(this).prop("checked", false);
//             var day_exist = $("#multi_time_exist").data("day_exist");
//             alert(value.toUpperCase() + " " + day_exist );
//         }

//     } else {

//         var checked_values = [];
//         $('.delivery_schedule_main_block .delivery_weekly_block_'+get_id+' :checkbox').each(function(){
//             if($(this).is(":checked")){
//                 var value = $(this).attr('class');
//                 checked_values.push(value);
//             }
//         });
        
//         if ( checked_values.length == 0 ) {
//             $( ".wpc_delivery_start_time_" + get_id ).val("").timepicker('remove');
//             $( ".wpc_delivery_end_time_" + get_id ).val("").timepicker('remove');

//             // remove error msg for this block
//             var get_message = $(this).parents(".delivery_weekly_block").children(".wpc-default-guest-message");
//             if (get_message.length > 0 ) {
//                 get_message.html("")
//             }

//             var disable_fields = parent_block.find(".wpc_delivery_weekly_schedule_start_time, .wpc_delivery_weekly_schedule_end_time");
//             disable_all_fields(disable_fields);

//             hide_reset_button(parent_block.find(".wpc_delivery_weekly_clear"));
//         }

//         delivery_selected_values.splice(delivery_selected_values.indexOf(value), 1);
//     }
// }