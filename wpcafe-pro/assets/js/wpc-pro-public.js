(function ($) {
    "use strict";
    $(document).ready(function () {

        $('.wpc-nav-shortcode').each(function () {
            wpc_widgets_popup($, $(this));
        });
        /*==========================================================
            food menu list slider 
        ======================================================================*/
        var $list_slider_scope = $('.wpc-menu-slider-shortcode');
        if ($list_slider_scope.length > 0) {
            slider_action($, $list_slider_scope, '.wpc-food-menu-slider', $('.wpc-nav-shortcode'));
        }
        /*==========================================================
            food menu tab slider 
        ======================================================================*/
        var $tab_slider_scope = $('.wpc-menu-tab-slider-shortcode');
        if ($tab_slider_scope.length > 0) {
            slider_action($, $tab_slider_scope, '.wpc-tab-with-slider', $('.wpc-nav-shortcode'));
        }

        /*==========================================================
           cross sell slider in mini-cart
        ======================================================================*/
        var $cross_slider_scope = $('.wpc-cross-sell-slider');
        if ($cross_slider_scope.length > 0) {
            slider_action($, $cross_slider_scope, '.wpc-cross-sells', $('.wpc-cross-sells'));
        }

        /*--------------------------------
        // product popup accordion
        -----------------------------------*/
        $("body").on('click', '.wpc-variation-title', function () {
            $(this).next('.wpc-variation-body').slideToggle();
            $(this).find('.toggle-icon-trigger').toggleClass('triggered');
        });

        // get object
        var wpc_booking_form_data;
        if (typeof wpc_form_client_data !== undefined) {
            var wpc_form_data = JSON.parse(wpc_form_client_data);
            wpc_booking_form_data = wpc_form_data.settings;
        }


        /*--------------------------------
            set location in local storage
        -----------------------------------*/
        $(document).on('click', '.wpcpro-select-location', function () {

            wpcpro_location = $('.wpcpro-location option:selected').val();
            var local_storage_value = localStorage.getItem('wpcpro_location');
            //TODO we can directly setItem to localStorage(without any checking)
            if (local_storage_value === null) {
                localStorage.setItem('wpcpro_location', wpcpro_location);
            } else {
                localStorage.removeItem('wpcpro_location');
                localStorage.setItem('wpcpro_location', wpcpro_location);
            }
            // search menu
            $('.wpc-tab .wpc-row').css('display', 'none');
            $('.wpc-row[data-id=' + wpcpro_location + ']').css('display', 'block');
            // close modal
            $(".wpc_modal").fadeOut();
            $('body').removeClass('wpc_location_popup');
        });

        const check_obj = () => {
            var response = false;
            if (typeof wpc_obj !== 'undefined' && typeof wpc_obj.settings_options !== 'undefined') {
                response = true;
            }
            return response;
        }

        /*--------------------------------
        // checkout order time radio button on change
        // default close pickup
        -----------------------------------*/


        $("#both_pickup").css("display", "none");

        //Get value from mini cart
        var deliverySavedValue = localStorage.getItem('wpcpro_delivery');
        $("input[name='wpc_pro_order_time'][value='" + deliverySavedValue + "']").attr('checked', 'checked');
        $('input[name="wpc_pro_order_time"]').trigger("change");

        var checkedValue = $('input[name="wpc_pro_order_time"]:checked').val();
        if (typeof checkedValue !== "undefined") {
            order_time_tab(checkedValue)
        }

        //update delivery / pickup value in checkout page
        $(".woocommerce-input-wrapper").on('change', 'input[name="wpc_pro_order_time"]', function () {
            $(".wpc_error_message").css("display", "none").html("");
            var selectedVal = $(this).val();
            order_time_tab(selectedVal);
            localStorage.setItem('wpcpro_delivery',selectedVal);
            Cookies.set('wpcpro_delivery', selectedVal );
            jQuery('body').trigger('update_checkout');

        });


        function order_time_tab(param) {
            if (param !== undefined) {

                var divToShow = $("#both_" + param.toLowerCase());
                divToShow.slideDown().siblings().not('#wpc_pro_order_time_field').slideUp();
            } else {
                $(".wpc_pro_order_time_settings").children().hide('fast');
            }
        }

        // order time settings
        var week_no = [0, 1, 2, 3, 4, 5, 6];
        var disable_delivery_date = [];
        var disable_pickup_date = [];
        var disable_pickup_holiday = [];
        var wpc_delivery_holiday = [];
        var result = check_obj();

        if (result == true) {
            if (typeof wpc_obj.settings_options.wpc_delivery_exception_date !== 'undefined' && wpc_obj.settings_options.wpc_delivery_exception_date.length > 0) {
                disable_delivery_date = wpc_obj.settings_options.wpc_delivery_exception_date;
            }
            if (typeof wpc_obj.settings_options.wpc_pickup_exception_date !== 'undefined' && wpc_obj.settings_options.wpc_pickup_exception_date.length > 0) {
                disable_pickup_date = wpc_obj.settings_options.wpc_pickup_exception_date;
            }
            // delivery

            // weekly schedule
            if (typeof wpc_obj.settings_options.wpc_delivery_schedule !== 'undefined' && typeof get_weekly_schedule === "function") {
                var weekly_delivery_arr           = get_weekly_schedule(wpc_obj.settings_options.wpc_delivery_schedule );
                var disable_delivery_weekly_arr   = get_weekly_day_no( weekly_delivery_arr );
                if (disable_delivery_weekly_arr.length>0) {
                    wpc_delivery_holiday = week_no.filter(val => !disable_delivery_weekly_arr.includes(val));
                }
            }

            if (typeof wpc_obj.settings_options.wpc_delivery_holiday !== 'undefined') {
                var weekly_delivery_holiday_arr = wpc_obj.settings_options.wpc_delivery_holiday;
                $.map(weekly_delivery_holiday_arr, function (i, j) {
                    wpc_delivery_holiday.push(parseInt(j));
                });
            }

            // pickup

            // weekly schedule
            if (typeof wpc_obj.settings_options.wpc_pickup_weekly_schedule !== 'undefined' && typeof get_weekly_schedule === "function") {
                var weekly_pickup_arr           = get_weekly_schedule(wpc_obj.settings_options.wpc_pickup_weekly_schedule );
                var disable_pickup_weekly_arr   = get_weekly_day_no( weekly_pickup_arr );
                if (disable_pickup_weekly_arr.length>0) {
                    disable_pickup_holiday = week_no.filter(val => !disable_pickup_weekly_arr.includes(val));
                }
            }


            if (typeof wpc_obj.settings_options.wpc_pickup_holiday !== 'undefined') {
                var weekly_pickup_holiday_arr = wpc_obj.settings_options.wpc_pickup_holiday;
                $.map(weekly_pickup_holiday_arr, function (i, j) {
                    disable_pickup_holiday.push(parseInt(j));
                });
            }
        }

        /*--------------------------------
        Checkout Delivery & Pickup holiday
        -----------------------------------*/
        var pickup_time_interval = typeof wpc_obj !== "undefined" && wpc_obj.settings_options.pickup_time_interval !== "" ? wpc_obj.settings_options.pickup_time_interval : 15;
        var delivery_time_interval = typeof wpc_obj !== "undefined" && wpc_obj.settings_options.delivery_time_interval !== "" ? wpc_obj.settings_options.delivery_time_interval : 15;

        var time_format = "H:i"; var date_format = "Y-m-d"; var datepicker_local = "en";

        if (typeof wpc_form_client_data !== "undefined") {
            var wpc_form_data = JSON.parse(wpc_form_client_data);
            if (typeof wpc_form_data.settings.wpc_time_format !== "undefined" && wpc_form_data.settings.wpc_time_format !== "") {
                time_format = wpc_form_data.settings.wpc_time_format;
                date_format = wpc_form_data.settings.wpc_date_format;
            } else {
                time_format = "H:i"; 
            }
            datepicker_local = typeof wpc_form_data.settings.reserv_form_local !== "undefined" ? wpc_form_data.settings.reserv_form_local : "en";

        }

        //set time picker
        var wpc_pro_delivery_time = $('#wpc_pro_delivery_time').timepicker(getFlatePickerConfig(delivery_time_interval));

        wpc_pro_delivery_time.on('changeTime', function () {
            var delivery_date = $(".wpc_error_message").data("delivery_date");
            check_order_type_date({ input: $(this), date: $("#wpc_pro_delivery_date"), message: delivery_date })
        })

        //set time picker
        var wpc_pro_pickup_time = $('#wpc_pro_pickup_time').timepicker(getFlatePickerConfig(pickup_time_interval));

        wpc_pro_pickup_time.on('changeTime', function () {
            var pickup_date = $(".wpc_error_message").data("pickup_date");
            check_order_type_date({ input: $(this), date: $("#wpc_pro_pickup_date"), message: pickup_date })
        })

        // TODO implemetn getFlatePickerConfig function as a constructor for timepicker config obj.
        function getFlatePickerConfig(interval){
            return {
                minTime: new Date(),
                timeFormat: time_format,
                step: interval,
                disableTextInput: true
            };
        }

        // Dokan preparing and delivery time picker
        var dokan_change_time = $("#preparing_time, #delivery_time").timepicker({
            timeFormat: "H:i",
            step: 15,
        });

        dokan_change_time.on('changeTime', function () {
            if ("0" == $(this).val()) {
                $(this).timepicker('setTime', '00:00');
            }
        })

        //Check if date exist
        function check_order_type_date(params) {
            if (typeof params !== "undefined" && params.date.val() == "") {
                if ("0" == params.input.val()) {
                    params.input.timepicker('setTime', '12:00 AM');
                }
                $(".wpc_error_message").css("display", "block").html("").html(params.message);
                params.input.timepicker('setTime', '');

            }
        }

        var delivery_preparing_date = typeof wpc_obj.settings_options.delivery_preparing_date === "undefined" ? 'today' : wpc_obj.settings_options.delivery_preparing_date;
        var pickup_preparing_date   = typeof wpc_obj.settings_options.pickup_preparing_date === "undefined" ? 'today' : wpc_obj.settings_options.pickup_preparing_date;
        var pickup_time             = typeof wpc_obj.settings_options.wpc_pro_allow_pickup_time === "undefined" ?  '' : wpc_obj.settings_options.wpc_pro_allow_pickup_time;
        var delivery_time           = typeof wpc_obj.settings_options.wpc_pro_allow_delivery_time === "undefined" ?  '' : wpc_obj.settings_options.wpc_pro_allow_delivery_time;


        // delivery date
        var delivery_time_obj = {
          type: "delivery",
          id: "#wpc_pro_delivery_date",
          pickup_time: pickup_time,
          delivery_time: delivery_time,
          holiday: wpc_delivery_holiday,
          disable_arr: disable_delivery_date,
          input_name: "input[name='wpc_pro_delivery_date']",
          time_obj: wpc_pro_delivery_time,
          prepare_day: delivery_preparing_date,
        };

        order_time_schedule(delivery_time_obj);
        // pickup date
        var pickup_time_obj = {
          type: "pickup",
          id: "#wpc_pro_pickup_date",
          pickup_time: pickup_time,
          delivery_time: delivery_time,
          holiday: disable_pickup_holiday,
          disable_arr: disable_pickup_date,
          input_name: "input[name='wpc_pro_pickup_date']",
          time_obj: wpc_pro_pickup_time,
          prepare_day: pickup_preparing_date,
        };

        order_time_schedule(pickup_time_obj);

        function order_time_schedule(param) {
            // disable holiday and weekly off days
            var disable_holiday = function name(date) {
                
                if (param.holiday.length === 0) {
                    return false;
                } else {
                    // if not found the day no.
                    return ($.inArray(date.getDay(), param.holiday) !== -1);
                }
            }
            
            //TODO why disable_holiday(function) pushed to an array?
            param.disable_arr.push(disable_holiday);

            $(param.id).flatpickr({
                minDate: param.prepare_day,
                dateFormat: date_format,
                locale: datepicker_local,
                disable: param.disable_arr,
                position: "below",
                onChange: function (selectedDates, dateStr, instance) {

                    if ( param.pickup_time == "" && param.delivery_time == "" ) {
                        return true;
                    }

                    $("#wpc_pro_pickup_time").val("");
                    $("#wpc_pro_delivery_time").val("");

                    $(".wpc_error_message").css("display", "none").html("");
                    $(param.input_name).val("");
                    $(param.input_name).removeAttr('readonly').val(dateStr).attr('readonly','readonly');
                    var wpc_pro_result_obj = check_obj();
                    if (wpc_pro_result_obj == true) {
                        var settings = wpc_obj.settings_options;
                        var selected_day = wpc_flatpicker_date_change(selectedDates, "D");
                        if (param.type == "delivery") {
                            var wpc_pro_delivery_start_time = settings.wpc_delivery_weekly_schedule_start_time;
                            var wpc_pro_delivery_end_time = settings.wpc_delivery_weekly_schedule_end_time;
                            var weekly_delivery_schedule_arr = settings.wpc_delivery_schedule;

                            // get weekly delivery schedule
                            var wpc_pro_result = wpc_weekly_schedule_time(weekly_delivery_schedule_arr, selected_day, wpc_pro_delivery_start_time, wpc_pro_delivery_end_time);

                            if (wpc_pro_result.success == true) {

                                wpc_pro_delivery_start_time = wpc_pro_result.wpc_start_time;
                                wpc_pro_delivery_end_time = wpc_pro_result.wpc_end_time;

                                var checking_time = check_time_range_validation(wpc_pro_delivery_start_time, wpc_pro_delivery_end_time, selectedDates, time_format);
                                var disable_time_rage = [];

                                if (checking_time.flag == "start_from_current") {
                                    var start     = moment(new Date());
                                    var remainder = parseInt(delivery_time_interval) + ( start.minute() % 30 );
                                    var get_start = moment(start).add(remainder, "minutes").format("hh:mm A");
                                    wpc_pro_delivery_start_time = get_start;
                                }

                                if (checking_time.flag == "disable_time") {
                                    // TODO why array of array [[]]
                                    disable_time_rage = [[wpc_pro_delivery_start_time, checking_time.end_time]];
                                }

                                param.time_obj.timepicker('option', 'minTime', wpc_pro_delivery_start_time);
                                param.time_obj.timepicker('option', 'maxTime', wpc_pro_delivery_end_time);
                                //TODO what is param.time_obj.timepicker do? 
                                param.time_obj.timepicker('option', 'disableTimeRanges', disable_time_rage);

                            } else {
                                $(param.id).val("");
                            }
                        }
                        else if (param.type == "pickup") {
                            var wpc_pro_pickup_start_time = settings.wpc_pickup_weekly_schedule_start_time;
                            var wpc_pro_pickup_end_time = settings.wpc_pickup_weekly_schedule_end_time;
                            var weekly_pickup_schedule_arr = settings.wpc_pickup_weekly_schedule;
                            // get weekly pickup schedule
                            var wpc_pro_result = wpc_weekly_schedule_time(weekly_pickup_schedule_arr, selected_day, wpc_pro_pickup_start_time, wpc_pro_pickup_end_time);

                            if (wpc_pro_result.success == true) {

                                wpc_pro_pickup_start_time = wpc_pro_result.wpc_start_time;
                                wpc_pro_pickup_end_time = wpc_pro_result.wpc_end_time;
                                var checking_time = check_time_range_validation(wpc_pro_pickup_start_time, wpc_pro_pickup_end_time, selectedDates, time_format);
                                var disable_time_rage = [];

                                if (checking_time.flag == "start_from_current") {
                                    var start     = moment(new Date());
                                    var remainder = parseInt(pickup_time_interval) - (start.minute() % 30);
                                    var get_start = moment(start).add(remainder, "minutes").format("hh:mm A");
                                    wpc_pro_pickup_start_time = get_start;
                                }

                                if (checking_time.flag == "disable_time") {
                                    disable_time_rage = [[wpc_pro_pickup_start_time, checking_time.end_time]];
                                }

                                wpc_pro_pickup_time.timepicker('option', 'minTime', wpc_pro_pickup_start_time);
                                wpc_pro_pickup_time.timepicker('option', 'maxTime', wpc_pro_pickup_end_time);
                                wpc_pro_pickup_time.timepicker('option', 'disableTimeRanges', disable_time_rage);

                            } else {
                                $(param.id).val("");
                            }
                        }
                        
                    }
                }

            });
        }

        /*-------------------------------------------------------------------------
            // enable / disable checkout button on selecting delivery or pickup
        ----------------------------------------------------------------------------*/

        jQuery(document).on("click", ".wpc-minicart-condition-input", function () {
            var selectedValue = $(this).val();

            $(this).siblings("#wpc-minicart-condition-value-holder").val(selectedValue);

            var local_storage_value = localStorage.getItem('wpcpro_delivery');
            // TODO same as before
            localStorage.setItem('wpcpro_delivery', selectedValue);
            Cookies.set('wpcpro_delivery', selectedValue );

        });

        // update location selected value in checkout page
        var location_save_value = localStorage.getItem('wpc_location');
        $("option[value='" + location_save_value + "']").attr("selected", "selected");
 
        /*==========================================================
                        Load more
        ======================================================================*/
        var load_more = $(".loadmore-section");
        if (load_more.length > 0) {
            load_more.each(function () {
                load_more_action($, $(this));
            });
        }


        /*==========================================================
                        Mini-cart update total with quantity
        ======================================================================*/
        $(".wpc-menu-mini-cart").on("change keyup", "input.qty", function () {
            var $this = $(this);
            var quantity = $this.val();
            var $key_div = $this.parents(".mini_cart_item");
            var cart_item_key = $key_div.find(".remove").data("cart_item_key");

            if (typeof cart_item_key !== "undefined" && cart_item_key !== "") {
                // input data
                var data = {
                    action: 'update_cart_with_quantiy',
                    cart_item_key: cart_item_key, quantity: quantity
                };

                $.ajax({
                    url: wpc_obj.ajax_url,
                    type: 'POST',
                    data: data,
                    dataType: 'html',
                    beforeSend: function () {
                        // TODO added loader befor send request, should be removed after response(success/failed)
                        $key_div.addClass("loading");
                    },
                    success: function (response) {
                        if (response !== "") {
                            var fragment = JSON.parse(response);
                            if (fragment) {
                                $.each(fragment.fragments, function (key, value) {
                                    $(key).replaceWith(value);
                                });

                                $(document.body).trigger('wc_fragments_refreshed');
                            }
                        }
                    },
                });
            }
        })

        /*==========================================================
            variation radio input
        ======================================================================*/
        $(".wpc-product-popup-content,.summary,.variations").on('click', '.variation-radios span', function () {
            var radio_value = $(this).prev(":radio").val(),
                radio_id = $(this).prev(":radio").attr("id"),
                radio_name = $(this).prev(":radio").attr('name');
            if (typeof radio_id !== "undefined") {
                $('input[name="' + radio_name + '"][id="' + radio_id + '"]').prop("checked", true);
            } else {
                $('input[name="' + radio_name + '"][value="' + radio_value + '"]').prop("checked", true);
            }
            $('select[name="' + radio_name + '"]').val(radio_value).trigger('change');
        });

        $(document).on('woocommerce_update_variation_values', function () {
            $('.variation-radios input').each(function (index, element) {
                $(element).removeAttr('disabled');
                var thisName = $(element).attr('name');
                var thisVal = $(element).attr('value');
                if ($('select[name="' + thisName + '"] option[value="' + thisVal + '"]').is(':disabled')) {
                    $(element).prop('disabled', true);
                }
            });
        });


        /*==========================================================
            Food with reservation
        ======================================================================*/
        var hide_booking = localStorage.getItem("wpc_reservation_details");

        // Cancel food with reservation
        $(".wpc_booking_modal").on('click', '.cancel_food_booking,.no_cancel_food_booking', function (e) {
            if ($(this).hasClass("cancel_food_booking")) {
                localStorage.removeItem("wpc_reservation_details");
                $(".food-with-reserv-wrap").html("");
            }
            else if ($(this).hasClass("no_cancel_food_booking")) {
                $(".wpc_booking_modal").addClass("hide_field");
            }

            $('body').removeClass('wpc_location_popup');
        })

        // Hide cancel section
        if (hide_booking == null) {
            $(".cancel_section").addClass("wpc-none");
        }

        $(document).on('click', '.mini_cart_item a.remove', function (e) {
            e.preventDefault();
            var id = $(this).data('product_id');
            $(".product_" + id).parent().remove();
        });

        // save reservation details
        $(".save_reservaion_data").on('click', function (e) {

            e.preventDefault();

            var obj = {};

            obj.wpc_name = $("#wpc-name").val();
            obj.wpc_email = $("#wpc-email").val();
            obj.wpc_phone = $("#wpc-phone").val();
            obj.wpc_guest_count = $("#wpc-party").val();
            obj.wpc_from_time = $("#wpc_from_time").val();
            obj.wpc_to_time = $("#wpc_to_time").val();
            obj.wpc_booking_date = $("#wpc_booking_date").data("wpc_booking_date");
            obj.wpc_message = $("textarea#wpc-message").val();
            obj.wpc_branch = $("#wpc-branch").val();

            var get_reserv_detials = localStorage.getItem('wpc_reservation_details');

            if (typeof get_reserv_detials !== "undefined" || get_reserv_detials !== null) {
                localStorage.removeItem("wpc_reservation_details");
            }
            var extra_field = reservation_extra_field();
            if (extra_field.length > 0) {
                obj.reserv_extra = extra_field;

                $.each(extra_field, function (key, data) {
                    obj["reserv_extra_" + key] = data.value
                })

            }

            //save reservation data
            localStorage.setItem('wpc_reservation_details', JSON.stringify(obj));

        });

        var get_reserv_detials = localStorage.getItem('wpc_reservation_details');

        if (typeof get_reserv_detials === "undefined" || get_reserv_detials == null) {
            $(".food_with_reserv").css('display', 'none')
        }

        var reserv_id = $("#reservation_details");
        if (reserv_id.length > 0) {
            reserv_id.val(get_reserv_detials);
        }

        // Hide delivery/pickup in checkout if reservation with food
        if (typeof get_reserv_detials !== "undefined" && get_reserv_detials !== null) {
            $(".wpc_pro_order_time").remove();
            $(".wpc_pro_order_time_settings").remove();
        }

        // Show reservation data

        var food_with_reserv = ('.food_with_reserv').length;
        if (food_with_reserv > 0) {
            var get_reserv_detials = localStorage.getItem('wpc_reservation_details');
            if (typeof get_reserv_detials !== "undefined" && get_reserv_detials !== null) {

                var get_data = JSON.parse(get_reserv_detials);
                var reservation_keys = Object.keys(get_data);

                $.each(reservation_keys, function (index, value) {
                    if (get_data[value] !== null && get_data[value].length !== 0) {
                        $(".food_" + value).text(get_data[value])
                    } else {
                        $("#food_" + value).css('display', 'none')
                    }
                })
            }
        }

        /*==========================================================
            scroll bar for food with reservation
        ======================================================================*/
        if ($(".reserv-with-food-menu-wrapper").length > 0) { 

            $(".reserv-with-food-menu-wrapper").mCustomScrollbar({
                mouseWheel: true,
                scrollButtons: {
                    enable: true
                },
                theme: "outside-dark",
            });

        }

        if ($(".wpc-motd-products").length > 0) {

            $(".wpc-motd-products").mCustomScrollbar({
                mouseWheel: true,
                scrollButtons: {
                    enable: true
                },
                theme: "outside-dark",
            });

        }

        jQuery(document).on('click', 'button#minicart-apply-button', function(e) {
            e.preventDefault();
            var coupon      = ""
            var coupon_from = $('.wpc_coupon_form').serializeArray();

            if ( coupon_from.length > 0 ) {
                var coupon      =  typeof coupon_from[0] !=="undefined" ? coupon_from[0]['value'] : "";
            }else{
                var coupon      = $("#minicart-coupon").val();
            }

            var data = {
                action: "wpc_apply_coupon_code",
                coupon_code: coupon
            };

            jQuery.ajax({
                type: 'POST',
                dataType: 'html',
                url: wc_add_to_cart_params.ajax_url,
                data: data,
                success: function (response) {
                    jQuery('.wpc-menu-mini-cart').html(response)
                },
                error: function (errorThrown) {
                        console.log('error');
                }
            });

        });

        jQuery(document).on('click', '.woocommerce-remove-coupon', function(e) {
            e.preventDefault();
            var $this = $( this );
            var coupon = $this.attr('data-coupon');
            var data = {
                action: "wpc_remove_coupon_code",
                coupon_code: coupon
            };

            jQuery.ajax({
                type: 'POST',
                dataType: 'html',
                url: wc_add_to_cart_params.ajax_url,
                data: data,
                success: function (result) {
                    $( document.body ).trigger( 'removed_coupon', [ coupon ] );
                    var response = JSON.parse(result);
                    if (typeof response.data !=="undefined" ) {
                        $('.wpc-menu-mini-cart').html(response.data.html);
                        $('.wpc-minicart-total').html("").html(response.data.new_amount);
                    }
                },
                error: function (errorThrown) {
                        console.log('error');
                }
            });
        });

    });


    /*==========================================================
        live search apply in shortcode
    ======================================================================*/
    var tab_class = $(".wpc-ajax-livesearch-wrap");
    if (tab_class.length > 0) {
        tab_class.each(function () {
            food_menu_search_fire($, $(this));
        })
    }

    /*==========================================================
      multi step form
    ======================================================================*/
    if ($(".wpc-reservation-pro-wrap").length > 0) {
        var current_fs, next_fs, previous_fs;

        $('.wpc-validate').on('change input', function () {
            $(this).removeClass('error');
            if ($(this).val() === "") {
                $(this).addClass('error');
            }
        })
				var field_set = ".wpc-field-set";

        $(".wpc-form-next").on('click', function () {
            var empty = false;

            $(this).parent().find('.wpc-validate').each(function () {
                if ($(this).val() === "" ) {
                    $(this).addClass('error');
                    empty = true;
                }
                // check for select option
                if ( $("#wpc-party option:selected").val() == ''  ) {
									$("#wpc-party").addClass(" wpc_has_error error");
									empty = true;
								}else{
									$("#wpc-party").removeClass(" wpc_has_error");
								}
            });

            if (empty) { return; }

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            if ($(this).parent().hasClass('field-wrap')) {
                current_fs = $(this).parent().parent();
                next_fs = $(this).parent().parent().next()
            }

            $(".wpc-reservation-pagination li").eq($(field_set).index(next_fs)).addClass("active");
            next_fs.fadeIn(1000);
            current_fs.css(
                { display: 'none' },
                {
                    complete: function () {
                        current_fs.hide();
                    },
                }
            );

        });

        $(".wpc-form-previous").on('click', function () {
            current_fs = $(this).parents(field_set);
            previous_fs = $(this).parents(field_set).prev();
            $(".wpc-reservation-pagination li").eq($(this).parents(field_set).index()).removeClass("active");

            previous_fs.fadeIn(1000);

            current_fs.css(
                { display: 'none' },
                {
                    complete: function () {
                        current_fs.hide();
                    },
                }
            );
        });

    }

    // Hide reservation success block for style one

    $(".wpc-reservation-success").css("display", "none");

    // food with reservation popup

    $(".wpc-btn.menu-select").on('click', function (e) {
        e.preventDefault();
        $('.reserv-with-food-menu-wrap').addClass('wpc-sidebar-active');
    })
    $(".wpc-food-menu-close").on('click', function () {
        $(this).parents().find('.reserv-with-food-menu-wrap').removeClass('wpc-sidebar-active');
    });
 
      // tip field in cart and checkout
    if (typeof select2 === "function") {
        jQuery(".wpc_pro_tip_type, .wpc_pro_percentage_tip_amount").select2();
    }

    // select2
    var select_arr = ['.wpc_pro_include_menu','.wpc_pro_include_cat', '.special_menus', '.wpc_pro_multi_product', '.wpc_pro_multi_cat']

    $.map(select_arr, function (value, index) {
        $(value).select2({
            selectAllOption: true,
        });
    });

})(jQuery);

// slider tab and list 
function slider_action($, $scope, params, popup_param = "") {

    var $container = $scope.find(params);
    var count = $container.data('count');
    var auto_play = $container.data('auto_play');

    if ($container.length > 0) {

        $($container).each(function (index, element) {

            var autoplay = false;

            if (typeof auto_play === "undefined" || auto_play == "yes") {
                $(element).hover(function () {
                    (this).swiper.autoplay.stop();
                }, function () {
                    (this).swiper.autoplay.start();
                });

                autoplay = {
                    delay: 3000,
                    disableOnInteraction: false,
                };
            }

            new Swiper(element, {
                slidesPerView: count,
                spaceBetween: 50,
                autoplay: autoplay,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                observer: true,
                observeParents: true,
                paginationClickable: true,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    600: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: count,
                    }
                }

            });

        });

    }
    //variation 
    if (popup_param !== "") {
        wpc_widgets_popup($, popup_param);
    }
}

// print order details
function wpc_pro_pirnt_content_area(divContents) {
    "use strict";
    var mywindow = window.open('', 'PRINT', 'height=400,width=800');
    mywindow.document.write(
        '<style type="text/css">'
        + '.woocommerce-column--1, .woocommerce-column--2{display:inline-block; float: none; width: 300px; vertical-align: top;}  .woocommerce-table tr th{text-align:left; width: 300px; }' +
        '</style>');

    var contentToPrint = document.getElementsByClassName(divContents)[0].innerHTML;
    contentToPrint = contentToPrint.split("<div class=\"extra-buttons\">")[0];
    mywindow.document.write('</head><body >');
    mywindow.document.write(contentToPrint);
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    return true;
}

//download pdf
function wpc_pro_download_pdf() {
    var contentToPrint = document.getElementsByClassName("woocommerce-order")[0].innerHTML;
    var source = contentToPrint.split("<div class=\"extra-buttons\">")[0];
    var filename = "invoice";
    jQuery('.woocommerce-order').html(source);
    var divToPrint = jQuery('.woocommerce-order')[0];

    // create custom canvas for a better resolution
    var w = 1000;
    var h = 1000;
    var canvas = document.createElement('canvas');
    canvas.width = w * 5;
    canvas.height = h * 5;
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    var context = canvas.getContext('2d');
    context.scale(5, 5);

    html2canvas(divToPrint, {
        scale: 4,
        dpi: 288,
        onrendered: function (canvas) {
            var data = canvas.toDataURL('image/png', 1);
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download(filename + ".pdf");
        }
    });
    window.setTimeout(function () { location.reload() }, 500);

}

/**
 * Get extra field data from reservation form
 * @returns 
 */
function reservation_extra_field() {
    var reserv_extra = jQuery('input[name="reserv_extra[]').map(function (i, val) {
        var $this           = jQuery(this);
		var input_value     = "";
		var label           = "";
        var type            = "";
        var options         = "";

		// get checked value
        if($this.is('input[type="checkbox"]')){
			label           = jQuery('#wpc-reser-extra-' + $this.data("row_id") ).text() ;
            type            = 'checkbox';
			options         = jQuery(`label[for=${$this.attr("id")}]`).html();
			if ($this.is(":checked")) {
				input_value     = $this.val();
			}
			else{  
				input_value     = "";
			}
        }

        if($this.is('input[type="text"]')){
			label           = jQuery('#wpc-reser-extra-' + $this.data("row_id") ).text();
            type            = 'text';
            input_value     = $this.val();
        }

		return ({ label: label, value: input_value, type: type  , options: options });

		
    }).get();
	var final_reserv_field = reserv_extra.reduce((res, curr) => {
		// if label is not same return. for text field
		if ( curr.label == "" && curr.value !== "" && curr.type !=="text") return res;
		// for check box if multiple input has been checked. create group
		var group = res.find((el) =>  (el.label === curr.label) && ( curr.type == "checkbox"  && el.type == "checkbox")  );
		if (group) {

			if ( group.value == "") {
				group.value +=  curr.value ;
			}else{
				group.value += curr.value !=="" ?   ' , ' + curr.value : "";
			}

			if ( group.options == "") {
				group.options +=  curr.options ;
			}else{
				group.options += curr.options !=="" ?   ' , ' + curr.options : "";
			}

		} else {
		// if single check
		res.push(curr);
		}
		return res;
	}, []);
    return final_reserv_field;
}
// reservation extra field
function reservation_extra_field_list() { 
    var reserv_extra = reservation_extra_field()
    if (typeof reserv_extra !== "undefiend" && reserv_extra.length > 0) {
		if ( jQuery(".extra_list").length > 0 ) {
			jQuery(".extra_list").remove();
		}
        var html = "";
        jQuery.each(reserv_extra, function (index, value) {
            if (value.value !== "") {
                html += "<li class='extra_list'>" +
                    "<strong class='wpc-user-field-info'>" + value.label + " : </strong>" +
                    "<span>" + value.value + "</span></li>"
            }
        })
        jQuery(".reservation-info li:last-child , .wpc_reservation_form li:last-child").after(html);
    }

    return;
}

// reservation pro style one
function reservation_success_block(data) {
    var form_style = jQuery(".form_style").data("form_style");
    switch (form_style) {
        case "pro-1":
            jQuery(".reservation-info").fadeOut();
            jQuery(".wpc-reservation-success").fadeIn();
            var booking_details = jQuery(".booking_details").data("booking_details");
            if (typeof data.message !== "undefined") {
                jQuery(".message_block_two ").html("").html(data.message);
            }
            if (typeof data.invoice !== "undefined") {
                jQuery(".booking_details").html("").html(booking_details + " " + data.invoice);
            }
            break;
		case "pro-2":
			jQuery(".reservation-info").fadeOut();
			jQuery(".wpc-reservation-success").fadeIn();
			var booking_details = jQuery(".booking_details").data("booking_details");
			if (typeof data.message !== "undefined") {
					jQuery(".message_block_two ").html("").html(data.message);
			}
			if (typeof data.invoice !== "undefined") {
					jQuery(".booking_details").html("").html(booking_details + " " + data.invoice);
			}
			break;

        default:
            break;
    }
}

// Save reservation custom post
function save_reservation_after_checkout(order_data = {}) {
    var reservation_id = null;
    if (typeof order_data.order_id !== "undefined") {

        var data = {
            action: 'wpc_check_for_submission',
            wpc_action: 'wpc_reservation',
            order_id: order_data.order_id,
        }

        var get_reserv_detials = localStorage.getItem('wpc_reservation_details');

        if (typeof get_reserv_detials !== "undefined" && get_reserv_detials !== null) {
            var get_data = JSON.parse(get_reserv_detials);
            var reservation_keys = Object.keys(get_data);

            jQuery.each(reservation_keys, function (index, value) {
                data[value] = get_data[value];
            });

            // save reservation custom post type
            jQuery.ajax({
                url: wpc_obj.ajax_url,
                method: 'post',
                data: data,
                success: function (response) {
                }
                //TODO no handler on success/failure 
            })
        }

        // finally remove reservation data
        localStorage.removeItem("wpc_reservation_details");
    }
    else {
        jQuery('.order_details').prepend("Reservation isn't place. Order id not found.")
    }

    return reservation_id;
}

// add food details in reservation
function food_details_reservation(data, $) {

    if (typeof data !== "undefined" && Object.keys(data).length > 0) {
        var key_arrs = Object.keys(data);

        var html = "<tr>";
        $.each(key_arrs, function (index, value) {
            if (value !== "product_id") {
                html += "<td class=product_" + data.product_id + ">" + data[value] + "</td>";
            }
        });

        html += "</tr>";

        $('.food_details').append(html)
    }
}



