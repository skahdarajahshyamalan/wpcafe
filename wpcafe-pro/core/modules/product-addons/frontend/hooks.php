<?php

namespace WpCafe_Pro\Core\Modules\Product_Addons\Frontend;

defined( "ABSPATH" ) || exit;

class Hooks {

  use \WpCafe_Pro\Traits\Singleton;

		/**
		 * call hooks for frontend.
		 */
		public function init() {
			if ( class_exists( 'WooCommerce' ) ) {
				// cart functionality.
				\WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\CartHooks::instance()->init();
				// display templates.
				\WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Control::instance()->init();
			}
		}
}