jQuery( document ).ready( function( $ ){
    "use strict";
    /*==========================================================
        live search apply in shortcode
    ======================================================================*/
    var tab_class = $(".wpc-ajax-locations-wrap");
    var sidebar_class = $(".wpc-ajax-sidebar-filter");

    if (tab_class.length > 0) {
        tab_class.each(function () {
            food_menu_search_fire($, $(this));
        });
    }

    if (sidebar_class.length > 0) {
        sidebar_class.each(function () {
            sidebar_search_fire($, $(this));
            default_products_load($, $(this));
        });
    }

    function sidebar_search_fire( $, $scope ) {
        pickup_delivery_filter(".pickup-delivery-filter", $scope);
    }

    function food_menu_search_fire( $, $scope ) {
        // live search
        var data_obj = get_data_element( $scope );

        var id = typeof $scope.data("id") !=="undefined" ? $scope.data("id") : "";
    
        $(".search_"+id).on('keyup', '.live_food_menu_'+id ,function(e){
            e.preventDefault();
            var $this = $(this);
            let live_ajax_call_data = {
                search_value        : $this.val(),
                total_product       : data_obj.total_product,
                location_arr        : data_obj.location_arr,
                cart_button         : data_obj.cart_button,
                pageurl             : data_obj.pageurl,
                template_name       : data_obj.template_name,
                template_path       : data_obj.template_path,
                widget_arr          : data_obj.widget_arr,
                list_click          : 'loc_search',
                tag_arr             : [],
                cat_arr             : [],
                search_html_div     : $(".get_result")
            };
            live_ajax_call( $, live_ajax_call_data )
        });
    
        // list choice
        $(".search_result_"+id).on('click','ul.get_result li',function(e){
            e.preventDefault();
            var $this = $(this);
            $(".search_result_"+id).attr('data-location_id', $this.data('location_id'));    
            var live_food_menu  = $('.live_food_menu_'+ id);
            live_food_menu.val('');
            live_food_menu.val( $this.text() );
            $('.search_result_'+ id).html(' ');

            let live_search_widget_data = {
                search_value        : $this.text(),
                location_arr        : $this.data('location_id'),
                tag_arr             : [],
                cat_arr             : [],
                pageurl             : data_obj.pageurl,
                template_name       : '',
                template_path       : '',
                widget_arr          : '',
                list_click          : false
            };

            live_ajax_call( $, live_search_widget_data );

        });

        // pickup button click filter
        pickup_delivery_search(".pickup_"+id, id, $scope, data_obj, 'pickup');

        // pickup button click filter
        pickup_delivery_search(".delivery_"+id, id, $scope, data_obj, 'delivery');

    }

    //for pickup and delivery options
    function pickup_delivery_filter(selector_id, $scope){
        var template_path = '';
        var template_name = '';
        var data_section = $scope.find('.data_section_filter');
        template_name       = data_section.data('template_name');
        template_path       = data_section.data('template_path');

        $(selector_id).on('change', function(e){
            var $this = $(this);
            var location_id = $this.data('location_id');
            var cat_arr = [];
            var tag_arr = $this.data('pickup-delivery');

            if($(".product-category-filter:checked").length > 0){
                cat_arr = $(".product-category-filter:checked").val();
            }
            
            e.preventDefault();            
            let live_search_widget_data = {
                $scope          : $scope,
                search_value    : '',
                location_arr    : location_id,
                tag_arr         : tag_arr,
                cat_arr         : cat_arr,
                widget_id       : '',
                data_obj        : '',
                pageurl         : '',
                template_name   : template_name,
                template_path   : template_path,
                widget_arr      : '',
                list_click      : 'pickup_delivery',
                tag_type        : $this.val()
            };
            live_ajax_call( $, live_search_widget_data );
        });
    }

    //for pickup and delivery options
    function pickup_delivery_search(selector_id, widget_id, $scope, data_obj, data_id){
        $(selector_id).on('click', function(e){
            var location_id = $(".search_result_"+widget_id).data('location_id');
            e.preventDefault();
            var $this = $(this);
            let live_search_widget_data = {
                $scope          : $scope,
                search_value    : $(".live_food_menu_"+widget_id).val(),
                location_arr    : location_id,
                tag_arr         : $this.data(data_id),
                cat_arr         : [],
                widget_id       : widget_id,
                data_obj        : data_obj,
                pageurl         : data_obj.pageurl,
                template_name   : data_obj.template_name,
                template_path   : data_obj.template_path,
                widget_arr      : data_obj.widget_arr,
                list_click      : 'search_redirect',
                tag_type        : data_id

            };

            live_ajax_call( $, live_search_widget_data );

        });
    }

    // category filter products
    $(".product-category-filter").on('change', function(e){
        e.preventDefault();
        var $this = $(this);
        
        var $cat_arr = $this.val();
        var $tag_arr = $this.data('wpc_tag');

        if($(".pickup-delivery-filter:checked").length > 0){            
            $tag_arr = $(".pickup-delivery-filter:checked").data("pickup-delivery");
        }

        let live_search_widget_data = {
            search_value        : '',
            location_arr        : $this.data('location_id'),
            tag_arr             : $tag_arr,
            cat_arr             : $cat_arr,
            pageurl             : '',
            template_name       : '',
            template_path       : '',
            widget_arr          : '',
            list_click          : 'category_filter'
        };

        live_ajax_call( $, live_search_widget_data );

    });

    $(".product-filter-search").on('keyup', function(e){
        e.preventDefault();
        var $this = $(this);
        $('.get_product_search').css('display', 'block');
        var data_section = $(".wpc-ajax-sidebar-filter").find('.data_section_filter');

        var $tag_arr = data_section.data('wpc_tag');
        var $cat_arr = [];

        if($(".pickup-delivery-filter:checked").length > 0){            
            $tag_arr = $(".pickup-delivery-filter:checked").data("pickup-delivery");
        }

        if($(".product-category-filter:checked").length > 0){
            $cat_arr = $(".product-category-filter:checked").val();
        }        

        let live_ajax_call_data = {
            search_value        : $this.val(),
            total_product       : data_section.data('total_product'),
            location_arr        : data_section.data('wpc_location'),
            cart_button         : data_section.data('cart_button'),
            pageurl             : '',
            template_name       : data_section.data('template_name'),
            template_path       : data_section.data('template_path'),
            widget_arr          : '',
            list_click          : 'product_search',
            tag_arr             : $tag_arr,
            cat_arr             : $cat_arr,
            search_html_div     : $(".get_product_search")
        };
        live_ajax_call( $, live_ajax_call_data )
    });

    // list choice
    $(".search-result-products").on('click', 'ul.get_product_search li', function(e){
        e.preventDefault();
        var $this = $(this);   
        var live_food_menu  = $('.product-filter-search');
        $('.get_product_search').css('display', 'none');
        live_food_menu.val('');
        live_food_menu.val( $this.text() );
        $('.search-result-products').html(' ');

        let live_search_widget_data = {
            search_value        : $this.text(),
            location_arr        : [],
            tag_arr             : [],
            cat_arr             : [],
            pageurl             : '',
            template_name       : '',
            template_path       : '',
            widget_arr          : '',
            list_click          : 'search_product_single',
            single_product_id   : $this.data('product_id')
        };

        live_ajax_call( $, live_search_widget_data );

    });

    // list choice for min and max price
    $(".product-price-min-max").on('change', function(e){
        e.preventDefault();
        var $this = $(this);

        var last_price = '';
        var $product_price = $this.val();
        var currency = $(".last-price").data("currency");
        var currency_pos = $(".last-price").data("currency-pos");

        if(currency_pos === 'left'){
            last_price = currency + $product_price;
        } else {
            last_price = $product_price + currency; 
        }

        $(".last-price").html("").html(last_price);
        
        var data_section = $(".wpc-ajax-sidebar-filter").find('.data_section_filter');
        var $tag_arr = data_section.data('wpc_tag');
        var $cat_arr = [];

        if($(".pickup-delivery-filter:checked").length > 0){            
            $tag_arr = $(".pickup-delivery-filter:checked").data("pickup-delivery");
        }

        if($(".product-category-filter:checked").length > 0){
            $cat_arr = $(".product-category-filter:checked").val();
        }

        let live_search_widget_data = {
            search_value        : '',
            location_arr        : data_section.data('wpc_location'),
            tag_arr             : $tag_arr,
            cat_arr             : $cat_arr,
            pageurl             : '',
            template_name       : '',
            template_path       : '',
            widget_arr          : '',
            list_click          : 'price_filter',
            product_price       : $product_price,
            product_min_price   : $this.data('minprice')
        };

        live_ajax_call( $, live_search_widget_data );

    });

    // list choice for rest price
    $(".reset_btn_price_filter").on('click', function(e){
        e.preventDefault();
        var $this = $(this);

        var default_price = $this.data("default-price");

        $(".last-price").html("").html(default_price);
        
        var data_section = $(".wpc-ajax-sidebar-filter").find('.data_section_filter');
        var $tag_arr = data_section.data('wpc_tag');
        var $cat_arr = [];

        if($(".pickup-delivery-filter:checked").length > 0){            
            $tag_arr = $(".pickup-delivery-filter:checked").data("pickup-delivery");
        }

        if($(".product-category-filter:checked").length > 0){
            $cat_arr = $(".product-category-filter:checked").val();
        }

        let live_search_widget_data = {
            search_value        : '',
            location_arr        : data_section.data('wpc_location'),
            tag_arr             : $tag_arr,
            cat_arr             : $cat_arr,
            pageurl             : '',
            template_name       : '',
            template_path       : '',
            widget_arr          : '',
            list_click          : 'default'
        };

        live_ajax_call( $, live_search_widget_data );

    });

    // get data for live search
    function get_data_element( $scope ) {
        var data_obj = {}; 
        var total_product = -1 ;
        var location_arr = [];
        var tag_arr = [];
        var cat_arr = [];
        var cart_button = 'yes';
        var template_path = '';
        var template_name = '';
        var pageurl = '';
        var widget_arr = '';  
        var data_section = $scope.find('.data_section');
        
        total_product       = data_section.data('total_product');
        location_arr        = data_section.data('location_arr');
        tag_arr             = data_section.data('tag_arr');
        cat_arr             = data_section.data('cat_arr');
        cart_button         = data_section.data('cart_button');
        pageurl             = data_section.data('pageurl');
        template_name       = data_section.data('template_name');
        template_path       = data_section.data('template_path');
        widget_arr          = data_section.data('widget_arr');
        var widget_id       = typeof $scope.data("id") !=="undefined" ? $scope.data("id") : "";
        var search_data     = $scope.find('.live_food_menu_'+widget_id).val();
        data_obj['search_data']     = search_data;
        data_obj['total_product']   = total_product;
        data_obj['location_arr']    = location_arr;
        data_obj['tag_arr']         = tag_arr;
        data_obj['cat_arr']         = cat_arr;
        data_obj['cart_button']     = cart_button;
        data_obj['pageurl']         = pageurl;
        data_obj['template_name']   = template_name;
        data_obj['template_path']   = template_path;
        data_obj['widget_arr']      = widget_arr;

        return data_obj;
    }

	/**
	 * Default call after reload the page
	 * @param {*} $ 
	 * @param {*} $scope 
	 */
	function default_products_load($, $scope){
        var data_section = $scope.find('.data_section_filter');

		var obj = {
            'search_value'      : data_section.search_value,
            'total_product'     : data_section.data('total_product'),
            'location_arr'      : data_section.data('wpc_location'),
            'wpc_cart_button'   : data_section.data('cart_button'),
            'pageurl'           : '',
            'template_name'     : '',
            'template_path'     : '',
            'widget_arr'        : '',
            'list_click'        : 'default',
            'tag_arr'           : data_section.data('wpc_tag'),
            'cat_arr'           : [],
            'tag_type'          : data_section.data('tag_type'),
            'wrapper_div'       : $(".list_template_list")
        };
		// default data load after page load.
		live_ajax_call( $ , obj );
    }
  

    // live search ajax call
    function live_ajax_call( $ , obj ) {
        var loading_class = 'search-filter-loader';
        var main_elem = $('.list_template_list');
        var main_elem_search = $('.get_product_search');
        var live_search = false;
        var wpc_location = null;
        var wpc_tag = null;
        var wpc_cat = null;
        var tag_type = null;
        var single_product_id = '';
        var product_price = '';
        var product_min_price = '';

        if (obj.product_min_price !== undefined && obj.product_min_price.length !== 0) {
            product_min_price = obj.product_min_price;
        }

        if (obj.product_price !== undefined && obj.product_price.length !== 0) {
            product_price = obj.product_price;
        }

        if (obj.single_product_id !== undefined) {
            single_product_id = obj.single_product_id;
        }
        
        if (obj.location_arr.length !== 0) {
            wpc_location = obj.location_arr;
        }        

        if (obj.tag_arr.length !== 0) {
            wpc_tag = obj.tag_arr;
        }

        if (obj.cat_arr.length !== 0) {
            wpc_cat = obj.cat_arr;
        }

        if (obj.tag_type !== undefined) {
            tag_type = obj.tag_type;
        }

        var data = {
            'action'            : 'get_food_by_location_type',
            'search_value'      : obj.search_value,
            'total_product'     : obj.total_product,
            'location_arr'      : wpc_location,
            'wpc_cart_button'   : obj.cart_button,
            'pageurl'           : obj.pageurl,
            'template_name'     : obj.template_name,
            'template_path'     : obj.template_path,
            'widget_arr'        : obj.widget_arr,
            'list_click'        : obj.list_click,
            'tag_arr'           : wpc_tag,
            'cat_arr'           : wpc_cat,
            'tag_type'          : tag_type,
            'single_product_id' : single_product_id,
            'product_price'     : product_price,
            'product_min_price' : product_min_price
        };

        $.ajax({
            url: pickup_delivery_obj.ajax_url,
            method: 'POST',
            data: data,
            beforeSend:function(){
                live_search = true;
                main_elem.addClass(loading_class);
                main_elem_search.addClass(loading_class);
            },
            success: function (response) {
                main_elem.removeClass(loading_class);
                main_elem_search.removeClass(loading_class);
                if ( response.success  ) {
                    var widget_id = '';
                    if (data.widget_arr) {
                        widget_id =  data.widget_arr['unique_id']
                    }

                    if ( data.list_click == 'loc_search' ) {
						obj?.search_html_div?.length > 0 && obj.search_html_div.empty();
						var source = $("#search_html_data").html();
						var locations = response.data.data.search_html;
						for (var i = 0; i < locations.length; i++) {
							var template = Handlebars.compile(source);
							var template = template(locations[i])
							obj.search_html_div.append(template);
						}
                    }

                    if ( data.list_click == 'product_search' ) {
						obj?.search_html_div?.length > 0 && obj.search_html_div.empty();
						var source = $("#product_html_data").html();
						var products = response.data.data.product_search;
						for (var i = 0; i < products.length; i++) {
							var template = Handlebars.compile(source);
							var template = template(products[i])
							obj.search_html_div.append(template);
						}
                    }

                    if(data.list_click == 'search_redirect'){
                        var loc_id = data.location_arr;
                        var tag_arr = data.tag_arr;
                        var tag_type = data.tag_type;
                        var page_url = data.pageurl+'/?location_id='+loc_id+'&'+'tag_type='+tag_type+'&picup_delivery_id='+tag_arr;
                        window.location.href = page_url;                       
                    }
					else if(data.list_click == "default" || data.list_click == "pickup_delivery" || data.list_click == "category_filter" || data.list_click == 'search_product_single' || data.list_click == 'price_filter'){ 
						var products = response.data.data.products;
						$(".list_template_list").html("");
						var source = $("#pick_delivery_style2").html();
						for (var i = 0; i < products.length; i++) {
							var template = Handlebars.compile(source);
							var template = template(products[i])
							$(".list_template_list").append(template);
						}
						
                    }
                }
            },
            complete:function(){
                live_search = false;
            },
        })
    }

} );
