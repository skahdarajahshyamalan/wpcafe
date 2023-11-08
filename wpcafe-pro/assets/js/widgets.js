

var popup_div  = jQuery( ".wpc_variation_popup_content");

function wpc_widgets_popup($, $scope) {

    var currentButton   = ".customize_button";
    var content_class   = $(".wpc_variation_popup_content");
    var id              = typeof $scope.data("id") !=="undefined" ? $scope.data("id") : "";
    var prepend_to      = $(".main_wrapper_"+id);
    var popup_working   = false;

    // open popup
    $( prepend_to ).on("click", currentButton , function (e) {
        e.preventDefault();
		// ajax add to cart function
		wpc_add_to_cart($);
		
        $(this).parents(prepend_to).find('.wpc-menu-footer').addClass('wpc-popup-loaded');
        
        if (popup_working) {
            return;
        }

        var product_id = $(this).data("product_id");
        var data = {
            action: 'variaion_product_popup_content',
            product_id: product_id,
            wpc_action: 'variation_popup' 
        };

        content_class.empty();
    
        $.ajax({
            url: wpc_obj.ajax_url,
            type: 'POST',
            data: data,
            dataType: 'html',
            beforeSend:function(){
                popup_working = true;
                $( "#product_popup"+product_id+id ).addClass("loading");
            },
            success: function (response) {
                var content_data = JSON.parse(response);
                if (content_data.success == false) {
                    return;
                }

                // open modal
                $( "#popup_wrapper" ).fadeIn('slow');
                var parsed_data  = JSON.parse(content_data.data.data);
                // attach data 
                popup_div.html("").html( parsed_data );
                
                // Variation Form
                var form_variation = content_class.find(".variations_form");
                form_variation.each(function () {
                    $(this).wc_variation_form();
                });

                form_variation.trigger('check_variations');
                form_variation.trigger('reset_image');
                
                if (typeof $.fn.wc_product_gallery !== 'undefined') {
                    content_class.find('.woocommerce-product-gallery').each(function () {
                        $(this).wc_product_gallery();
                    });
                }
                // addon price calculate
                if (typeof getCalculatedPrice === 'function') {

                    const classNameTotal = $('.wpc-grand-total');
                    getCalculatedPrice($, classNameTotal, true);
                    getCalculatedPrice($, classNameTotal);
                    getVeriationPrice($, classNameTotal);

                    validateRequiredBlock($);
                }

            },
            complete:function(){
                popup_working = false;
                $(currentButton).removeClass("loading");
                $(".wpc_variation_popup_content_"+product_id).removeClass("popup_loading")

            },
        }); 
    });

    // variation layout style 2
    content_class.on("click", "a.reset_variations" , function (e) {
        $("input:radio").removeAttr("checked");
    });
}

jQuery(".wpc-product-popup-content").on("click",".wpc-close",function(){
    jQuery(".wpc-product-popup-content").fadeOut();
    jQuery(document).find('.wpc-menu-footer').removeClass('wpc-popup-loaded');
    popup_div.html("").fadeOut();
    jQuery(".wpc_variation_popup_content").removeAttr("style");
});

jQuery(document).on('mouseup', function (e) {
    var container = jQuery(".wpc-popup-wrap");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        jQuery(".wpc-product-popup-content").fadeOut();
        popup_div.html("")
        todg = true;
        jQuery(".wpc_variation_popup_content").removeAttr("style");
    }
});

// get data for live search
function get_data_element( $scope ) {
    var data_obj = {}; 
    var total_product = -1 ; var cat_arr=[]; var cart_button='yes'; var template_name=""; 
    var data_section    = $scope.find('.data_section');
    total_product       = data_section.data('total_product');
    cat_arr             = data_section.data('cat_arr');
    cart_button         = data_section.data('cart_button');
    template_name       = data_section.data('template_name');
    template_path       = data_section.data('template_path');
    widget_arr          = data_section.data('widget_arr');
    var widget_id       = typeof $scope.data("id") !=="undefined" ? $scope.data("id") : "";
    var search_data     = $scope.find('.live_food_menu_'+widget_id).val();
    data_obj['search_data']     = search_data;
    data_obj['total_product']   = total_product;
    data_obj['cat_arr']         = cat_arr;
    data_obj['cart_button']     = cart_button;
    data_obj['template_name']   = template_name;
    data_obj['template_path']   = template_path;
    data_obj['widget_arr']      = widget_arr;
    
    return data_obj;
}

function widget_live_search( $ , obj ) {
    if ( obj.search_data !='' ) {
        var tab_wrapper     = ".wpc-food-tab-wrapper";    
        var live_food_menu  = $('.live_food_menu_'+ obj.widget_id);
        if ( obj.tab && obj.$scope.find(tab_wrapper).length>0 ) {
            let tab_wrapper = obj.$scope.find(tab_wrapper)
            tab_wrapper.find(".wpc-tab").removeClass('tab-active');
            tab_wrapper.find(".wpc-tab-a").removeClass('wpc-active');
            tab_wrapper.find(".wpc-tab-a[data-cat_id='" + obj.list_cat_id + "']").addClass("wpc-active");
            tab_wrapper.find(".wpc-tab[data-cat_id='" + obj.list_cat_id + "']").addClass("tab-active");
        }
        live_food_menu.val('');
        live_food_menu.val( obj.search_data );
        $('.search_result_'+ obj.widget_id).html(' ');
        let live_ajax_call_data = {
            search_value : obj.search_data,
            total_product : obj.data_obj.total_product ,
            cat_arr : obj.data_obj.cat_arr,
            cart_button : obj.data_obj.cart_button,
            template_name : obj.data_obj.template_name,
            template_path : obj.data_obj.template_path,
            widget_arr : obj.data_obj.widget_arr,
            list_click : true
        };
        live_ajax_call( $, live_ajax_call_data)
    }
}

function food_menu_search_fire( $, $scope ) {
    // live search
    var data_obj        = get_data_element( $scope );
    var id              = typeof $scope.data("id") !=="undefined" ? $scope.data("id") : "";

    $(".search_"+id).on('keyup', '.live_food_menu_'+id ,function(e){
        e.preventDefault();
        let live_ajax_call_data = {
            search_value : $(this).val(),
            total_product : data_obj.total_product ,
            cat_arr : data_obj.cat_arr,
            cart_button : data_obj.cart_button,
            template_name : data_obj.template_name,
            template_path : data_obj.template_path,
            widget_arr : data_obj.widget_arr ,
            list_click : false
        };
        live_ajax_call( $, live_ajax_call_data )
    });

    // list choice
    $(".search_result_"+id).on('click','ul.get_result li',function(e){
        e.preventDefault();
        let live_search_widget_data = {
            $scope : $scope,
            search_data : $(this).text(),
            widget_id : id,
            data_obj : data_obj,
            tab : true ,
            list_cat_id : $(this).data('cat_id')
        };
        widget_live_search( $, live_search_widget_data );
    })
}

function food_tab_action( $ , cat_id , wrapper_class) {
    var tab_wrapper = $( '.'+wrapper_class );   
      
    if (tab_wrapper.length>0) {
        tab_wrapper.find(".wpc-tab").removeClass('tab-active');
        tab_wrapper.find(".wpc-tab-a").removeClass('wpc-active');
        tab_wrapper.find(".wpc-tab-a[data-cat_id='" + cat_id + "']").addClass("wpc-active");
        tab_wrapper.find(".wpc-tab[data-cat_id='" + cat_id + "']").addClass("tab-active"); 
    }
}
var live_search = false;

// live search ajax call
function live_ajax_call( $ , obj ) {
    var wpc_cat = null
    if (obj.cat_arr.length !== 0) {
        wpc_cat = obj.cat_arr;
    }

    var data = {
        'action'            : 'live_search_ajax',
        'search_value'      : obj.search_value,
        'total_product'     : obj.total_product,
        'cat_arr'           : wpc_cat,
        'wpc_cart_button'   : obj.cart_button,
        'template_name'     : obj.template_name,
        'template_path'     : obj.template_path,
        'widget_arr'        : obj.widget_arr,
        'list_click'        : obj.list_click,
    };

    $.ajax({
        url: wpc_obj.ajax_url,
        method: 'POST',
        data: data,
        beforeSend:function(){
            live_search = true;
        },
        success: function (response) {
            if ( response.success  ) {
                var widget_id = ''; 
                if (widget_arr) {
                    widget_id =  widget_arr['unique_id']
                }
                var search_result=$(".search_result_"+widget_id);
                search_result.html(" ")

                if ( data.list_click == false ) {
                    search_result.html( response.data.data.search_html )
                }
                if ( response.data.data.template_name ) {
                    var template_name   = "."+response.data.data.template_name;
                    var cat_id          = response.data.data.cat_id;
                    var popup_html      = ""; var template_id = "";
                    
                    if (template_name == ".list_template" ) {
                        template_id = '_'+widget_id; 
                    }else{
                        template_id = '_'+cat_id + '_'+widget_id;
                        var template_data = $(".template_data"+template_id);
                        
                         // hide default tab html if exist and show result
                        if ( template_data.length >0 ){
                            template_data.fadeOut();
                            food_tab_action( $ , cat_id ,'main_wrapper_'+widget_id );
                        }
                    }
                    
                    $( template_name+template_id ).html(" ").html( response.data.data.content ) 

                    popup_html = $(".main_wrapper_"+widget_id)
    
                    $('.wpc-nav-shortcode').each(function () {
                        wpc_widgets_popup($, popup_html );
                    }); 
                }
            }
        },
        complete:function(){
            live_search = false;
        },
    })
}

/*==========================================================
                Loadmore
======================================================================*/
function load_more_action($, $scope){
    wpc_widgets_popup($, $scope)
    var unique_identity ='';
    var container       = $scope.find(".loadmore-btn-wrap");
    var unique_identity = typeof $scope.data('id') !== "undefined" ? $scope.data('id') : "";
    var button          = $('.loadmore'+unique_identity);
    var load_more_working = false;
    $( container ).on('click', '.loadmore'+unique_identity , function () {
        if (load_more_working) {
            return;
        }
        var ajaxjsondata    = button.data('json_grid_meta');
        var contentwrap     = $('.wpc-loadmore-wrap'+unique_identity ), // item contentwrap
            showallposts    = parseInt(ajaxjsondata.total_post); // total posts count
        var paged           = parseInt(button.attr('data-paged'));
        var data = {
            'action': 'load_posts_by_ajax',
            'paged': paged,
            'security_nonce': wpc_obj.security,
            'ajax_json_data': ajaxjsondata,
        };

        $.ajax({
            url: wpc_obj.ajax_url,
            method: 'post',
            data: data,
            beforeSend:function(){
                load_more_working = true;
            },
            success: function (response) {
                if ($.trim(response) != '') {
                    $( '.wpc-loadmore-wrap'+unique_identity ).append(response);
                    paged++;
                    button.attr('data-paged', paged);
                    var newLenght = contentwrap.find('.wpc-food-menu-item').length;

                    if (showallposts <= newLenght) {
                        $('.loadmore'+unique_identity ).hide();
                    }
                } else {
                    $('.loadmore'+unique_identity ).hide();
                }
            },
            complete:function(){
                load_more_working = false;
            },
        })
    });
}

