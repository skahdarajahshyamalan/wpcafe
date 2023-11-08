(function ($, elementor) {
    "use strict";

    var wpc = {

        init: function () {
            var widgets = {
                //for pro widgets
                'wpc-food-menu-slider-pro.default': wpc.wpc_food_menu_slider_pro,
                'wpc-menu-tab-with-slider.default': wpc.wpc_menu_tab_with_slider,
                'wpc-menu-slider-classic-pro.default': wpc.wpc_menu_slider_classic_pro,
                'wpc-menu-tab-pro.default': wpc.wpc_menu_tab_pro,
                'wpc-food-menu-loadmore.default': wpc.wpc_menu_loadmore,
                'wpc-menu-list-pro.default': wpc.wpc_menu_list_pro,

                //for free widgets
                'wpc-menu-tab.default': wpc.wpc_menu_tab,
                'wpc-menu.default': wpc.wpc_menu,
            };
            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });

        },

        //start for free widgets
        wpc_menu_tab: function ($scope) {
            wpc_widgets_popup($, $scope);
        },

        wpc_menu: function ($scope) {
            wpc_widgets_popup($, $scope);
            if ( typeof food_menu_search_fire !== 'undefined' ) {
                food_menu_search_fire( $, $scope )
            }
        },
        // end for free widgets


        //start for pro widgets
        wpc_menu_list_pro: function ($scope) {
            // variation popup
            wpc_widgets_popup($, $scope);
            
            // live search
            if ( typeof food_menu_search_fire !== 'undefined' ) {
                food_menu_search_fire( $, $scope )
            }
        },

        wpc_menu_tab_pro: function ($scope) {
            // variation popup
            wpc_widgets_popup($, $scope);
            
        },

        wpc_menu_loadmore: function ($scope) {
            // variation popup
            load_more_action($, $scope);
        },


        // food menu slider start
        wpc_food_menu_slider_pro: function ($scope) {
            var $list_slider_scope = $scope.find('.wpc-food-wrapper');
            slider_action( $ ,$list_slider_scope ,'.wpc-food-menu-slider', $('.wpc-nav-shortcode') );
        },

        // food menu tab with slider

        wpc_menu_tab_with_slider: function ($scope) {
            var $tab_slider_scope = $scope.find('.wpc-food-wrapper');
            slider_action( $ ,$tab_slider_scope ,'.wpc-tab-with-slider', $('.wpc-nav-shortcode'))
        },
        // food menu slider classic

        wpc_menu_slider_classic_pro: function ($scope) {
            var $tab_slider_scope = $scope.find('.wpc-food-wrapper');
            slider_action( $ ,$tab_slider_scope ,'.wpc-food-menu-slider-classic', $('.wpc-nav-shortcode') )
        },


        //end for pro widgets
    };

    $(window).on('elementor/frontend/init', wpc.init);
}(jQuery, window.elementorFrontend));





