 
jQuery(function ($) {
	"use strict";

	// addon price calculate
	const classNameTotal = $('.wpc-grand-total');

	getCalculatedPrice($, classNameTotal, true);
	getCalculatedPrice($, classNameTotal);
	getVeriationPrice($, classNameTotal);

	validateRequiredBlock($);
});

// calculation price
function totalCalc($) {
	let decimal_number_points 	= $('.wpc-product-totals').data('decimal-number-points');

	let price_1 = $('.wpc-product-totals').find('.wpc-options-total').text(),
		price_2 = $('.wpc-product-totals').find('.wpc-product-total').text();

	return ( ( Number(price_1) + Number(price_2) ).toFixed(decimal_number_points) );
}

// calculation of total option selection value and total price value
function calculateOptionsTotalPrice($,classNameTotal) {
	let optionPrice = 0;
	$('.wpc-addon-field:checked, .wpc-addon-field :selected, .wpc-addon-text').each(function(){
		var current_this = $(this);

		if (current_this.is("textarea") && $.trim(current_this.val()).length == 0) {
			return;
		}

		var fieldPrice = Number(current_this.attr('data-price'));
		optionPrice += fieldPrice;
	});

	var discount_product 		= parseInt( $('.wpc-product-totals').data('discount-product') );
	var discount_percentage 	= parseInt( $('.wpc-product-totals').data('discount-percentage') );
	var discount_applicable_to 	= $('.wpc-product-totals').data('discount-applicable-to');
	var decimal_number_points 	= $('.wpc-product-totals').data('decimal-number-points');

	option_total_price = optionPrice;
	if(discount_product && discount_applicable_to == 'options_total' && optionPrice > 0) {
		price_after_discount = (discount_percentage / 100) * optionPrice;
		option_total_price   = optionPrice - price_after_discount;
	}

	$('.wpc-product-totals').find('.wpc-options-total').text(option_total_price.toFixed(decimal_number_points));
	classNameTotal.text(totalCalc($));
}

// get price on initial time or change item
function getCalculatedPrice($, classNameTotal, initialSituation = false){
	$(".wpc-inner-addon-container").each(function(){
		if(initialSituation) {
			calculateOptionsTotalPrice($, classNameTotal);
		} else {
			$(this).on('change', function(){
				calculateOptionsTotalPrice($, classNameTotal);
			});
		}
	});
}

// get veriation price on change variable item
function getVeriationPrice($, classNameTotal){
	$( '.variations_form' ).each( function() {
		$(this).on( 'found_variation', function( event, variation ) {
			var variationPrice = variation.display_price;
			$('.wpc-product-totals').find('.wpc-product-total').text(variationPrice);
			classNameTotal.text(totalCalc($))
		});
	});
}

// validate required block for each block and during add to cart click time
function validateRequiredBlock($){
	// on change checkbox, radio, select validate required
	$('.wpc-addons-container').find('.wpc-addon-required-block').each(function () {
		var current_block = this;
		var field_type    = $(current_block).data('field_type');

		$(this).find('.wpc-addon-field').on('change keyup', function () {

			if(field_type == 'dropdown' || field_type == 'text') {
				if ($.trim($(this).val()).length == 0) {
					$(current_block).parent().addClass('wpc-addon-field-error');
				} else {
					$(current_block).parent().removeClass('wpc-addon-field-error');
				}
			} else {
				if ($(current_block).find('input:checked').length == 0) {
					$(current_block).parent().addClass('wpc-addon-field-error');

					// add required attribute to each item
					$(current_block).find('input').each(function () {
						$(this).attr('required', true);
					});
				} else {
					$(current_block).parent().removeClass('wpc-addon-field-error');
					$(current_block).find('input').each(function () {
						$(this).attr('required', false);
					});
				}
			}
		});
	});

	// on add to cart validate required
	$('.single_add_to_cart_button').on('click', function (e) {
		var proceed_cart_action = true;

		$('.wpc-addons-container').find('.wpc-addon-required-block').each(function () {
			var current_block = this;
			var field_type    = $(current_block).data('field_type');

			if(field_type == 'dropdown') {
				if ($(current_block).find('option:selected').val() == '') {
					proceed_cart_action = false;
					$(current_block).parent().addClass('wpc-addon-field-error');
				} else {
					$(current_block).parent().removeClass('wpc-addon-field-error');
				}
			} else if(field_type == 'text') {
				if ($.trim($(current_block).find('input[type=text]').val()).length == 0) {
					$(current_block).parent().addClass('wpc-addon-field-error');
				} else {
					$(current_block).parent().removeClass('wpc-addon-field-error');
				}
			} else {
				if ($(current_block).find('input:checked').length == 0) {
					proceed_cart_action = false;
					$(current_block).parent().addClass('wpc-addon-field-error');

					// add required attribute to each item
					$(current_block).find('input').each(function () {
						$(this).attr('required', true);
					});
				} else {
					$(current_block).parent().removeClass('wpc-addon-field-error');
					$(current_block).find('input').each(function () {
						$(this).attr('required', false);
					});
				}
			}
		});

		if(!proceed_cart_action) {
			e.preventDefault();
			window.scrollTo({top: jQuery('.wpc-inner-addon-container.wpc-addon-field-error').offset().top - 50
			, behavior: 'smooth'});
		}

	});
}