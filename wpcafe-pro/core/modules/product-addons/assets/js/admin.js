(function($) {
    "use strict";

    $(document).ready(function() {

        var pao_wrapper = $('#wpc_pro_pao_content');

        // if desc checkbox is checked, then show description textarea box
        pao_wrapper.on( 'click', '.wpc_pro_pao_desc_enable', function() {
            var current_this = $(this);

            if ( current_this.is( ':checked' ) ) {
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc_pro_pao_desc' ).css('display', 'block');
            } else {
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc_pro_pao_desc' ).css('display', 'none');
            }
        } )
        // if char limit checkbox is checked, then show min, max char input area
        pao_wrapper.on( 'click', '.wpc_pro_pao_char_limit_enable', function() {
            var current_this = $(this);

            if ( current_this.is( ':checked' ) ) {
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addon-char-limit-wrap' ).addClass('show');
            } else {
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addon-char-limit-wrap' ).removeClass('show');
            }
        } )
        .on('change', '.wpc_pro_pao_type', function() {
            var current_this = $(this);

            if(current_this.val() != 'text') { // others
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addons-place-holder' ).css('display', 'none');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addon-char-limit-main' ).css('display', 'none');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-row');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-title, .wpc-pro-pao-option-footer, .wpc-pro-pao-option-sort-wrap, .wpc-pro-pao-option-label, .wpc-pro-pao-option-default-wrap' )
                    .removeClass('hide_block').addClass('show_block');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-remove' ).css('display', 'block');
            } else {
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addons-place-holder' ).css('display', 'block');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-addon-char-limit-main' ).css('display', 'block');

                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-row').not(':first').css('display', 'none');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-title, .wpc-pro-pao-option-footer, .wpc-pro-pao-option-sort-wrap, .wpc-pro-pao-option-label, .wpc-pro-pao-option-default-wrap, .wpc-pro-pao-option-remove')
                    .removeClass('show_block').addClass('hide_block');
                current_this.parents( '.wpc_pro_pao_wrap' ).find( '.wpc-pro-pao-option-remove' ).css('display', 'none');
            }
        })

        // add new pao field
        .on('click', '.wpc_pro_pao_add_fields', function() {
            var clicked_this   = $(this);
            var next_pao_index = clicked_this.attr('data-next_pao_index');

            $.ajax({
                type: "POST",
                url: wpc_pro_pao_obj.ajax_url,
                dataType: 'json',
                data: {
                    action: 'add_pao', security: wpc_pro_pao_obj.add_pao_nonce,
                    'next_pao_index': next_pao_index,
                },
                complete: function() {
                },
                success: function (res) {
                    if( res.status_code == 1 ) {
                        clicked_this.attr('data-next_pao_index', parseInt(next_pao_index)+1);

                        var new_pao = res.content;
                        new_pao     = new_pao.replace( /hide_block/g, 'show_block' );

                        pao_wrapper.find('.wpc_pro_pao_main_block').append(new_pao);
                    }
                }
            });
        })
        // add new price option block under specific pao field
        .on('click', '.wpc_pro_pao_add_option', function() {
            var clicked_this   = $(this);
            var current_counter_index = clicked_this.data('current_counter_index');
            var next_option_index     = parseInt( clicked_this.attr('data-next_option_index') );
						var content = `<div class="wpc-pro-pao-option-row">
						<div class="wpc-pro-pao-option-sort-wrap show_block">
							<span class="wpc-pro-pao-option-sort-handle dashicons dashicons-menu"></span>
						</div>

						<div class="wpc-pro-pao-option-label show_block">
							<input type="text" class="wpc-settings-input" name="wpc_pro_pao_option_label[`+current_counter_index+`][]" value="" placeholder="`+ wpc_pro_pao_obj.repeater_text.opt_name +`">
						</div>

						<div class="wpc-pro-pao-option-price-type">
							<select name="wpc_pro_pao_option_price_type[`+current_counter_index+`][]" class="wpc-settings-input wpc-pro-pao-option-price-type">
								<option value="quantity_based">`+ wpc_pro_pao_obj.repeater_text.qty_based +`</option>
							</select>
						</div>

						<div class="wpc-pro-pao-option-price">
							<input type="text" name="wpc_pro_pao_option_price[`+current_counter_index+`][]" class="wpc-settings-input wc_input_price wpc_pro_pao_opt_price" value="" placeholder="0">
						</div>

						<div class="wpc-label-item wpc-pro-pao-option-default-wrap show_block">
							<div class="wpc-meta wpc-pro-pao-option-default">
							<input type="radio" class="" id="wpc_pro_pao_option_default_`+current_counter_index+`_`+next_option_index+`" name="wpc_pro_pao_option_default[`+next_option_index+`][]" value="`+next_option_index+`">
							<label for="wpc_pro_pao_option_default_`+current_counter_index+`_`+next_option_index+`">`+ wpc_pro_pao_obj.repeater_text.def_select +`</label>
							</div>
						</div>

						<button type="button" class="wpc-pro-pao-option-remove button"">
							<i class="dashicons dashicons-no-alt"></i>
						</button>
					</div>`;

					$( content ).appendTo( clicked_this.parent().siblings('.wpc-pro-pao-option-wrap') );
                        clicked_this.attr('data-next_option_index', next_option_index+1);

        })
        // show/hide pao content whenever that pao header is clicked
        .on( 'click', '.wpc-pro-pao-header', function( e ) {
            e.preventDefault();
            var pao_block = $(this).next( '.wpc_pro_pao_wrap' );
            pao_block.slideToggle();
        } )

        // sort pao fields
        if(typeof sortable =='function'){
            $( '.wpc_pro_pao_main_block' ).sortable( {
                items: '.wpc-pro-pao-fields',
                cursor: 'move',
                axis: 'y',
                handle: '.wpc-pro-pao-header',
                scrollSensitivity: 40,
                helper: function( e, view ) {
                    return view;
                },
                start: function( eventt, view ) {
                    view.item.css({ 'border-style': 'dotted', 'border-width': '1px', 'border-color': 'darkorange' });
                },
                stop: function( eventt, view ) {
                    view.item.removeAttr( 'style' );
                    paoBlockIndexesChange($);
                }
            } );
        }

        if (typeof sortable === "function") {

        // sort price option block under specific pao field
        $( '.wpc-pro-pao-option-wrap' ).sortable( {
            items: '.wpc-pro-pao-option-row',
            cursor: 'move',
            axis: 'y',
            handle: '.wpc-pro-pao-option-sort-handle',
            scrollSensitivity: 40,
            helper: function( e, view ) {
                return view;
            },
            start: function( eventt, view ) {
                view.item.css({ 'border-style': 'dotted', 'border-width': '1px', 'border-color': 'darkorange' });
            },
            stop: function( eventt, view ) {
                view.item.removeAttr( 'style' );
                paoBlockIndexesChange($);
            }
        } );
    }
        // need to change to match with existing remove function
        var obj = {
            parent_block: '.wpc_pro_pao_main_block',
            remove_button: '.wpc-pro-pao-remove',
            removing_block: '.wpc-pro-pao-fields'
        };
        // remove_block(remove_pao_block);

        $(obj.parent_block).on( 'click' , obj.remove_button , function(e) {
            e.preventDefault();
            $(this).parents( obj.removing_block ).remove();
        });

        // remove price option block
        var remove_pao_option_block = {
            parent_block: '#wpc_pro_pao_content',
            remove_button: '.wpc-pro-pao-option-remove',
            removing_block: '.wpc-pro-pao-option-row'
        };

        if (typeof remove_block == 'function') {
            remove_block(remove_pao_option_block);
        }

        // show error message and highlight fields if empty title/price option name
        if(pao_wrapper.length > 0) {
            $( '#publishing-action input[name="save"], .wpc_global_addons_save' ).on( 'click', function() {

								var has_error = check_addons_validation( 'on_submit' );

                if ( has_error ) {
                    var err_msg_block = '<div class="notice notice-error wpc-pro-pao-error-msg"><p><a href="#wpc_pro_pao_content">' + wpc_pro_pao_obj.empty_label_price_msg + '</a></p></div>';
                    $( '.wrap #post' ).before( err_msg_block );
                }

                return !has_error; // to allow/prevent normal submit action
            } );
        }
    });

check_addons_validation( 'typing' );
// TODO : Check validation on submit and typing.
function check_addons_validation( checking_type ) {
	var has_error    = false,
	border_error = false ;

// remove notice error msg which is showing on top
$( '.wpc-pro-pao-error-msg' ).remove();

	var current_this;
	$( '.wpc_pro_pao_main_block' ).find( '.wpc-pro-pao-fields' ).each( function( i ) {
			current_this    = $(this);
			var pao_type    = $(this).find( '.wpc_pro_pao_type' );
			var pao_title   = $(this).find( '.wpc_pro_pao_title' );

			if ( checking_type =='typing' ) {
				var option_name = current_this.find( '.wpc-pro-pao-option-label input:first' );

			// title on keyup
				pao_title.on('keyup',function(){
					if ( $.trim(pao_title.val()).length == 0) {
						current_this.addClass( 'wpc-pro-pao-error' );
						pao_title.addClass( 'wpc-pro-pao-error' ).after('<div class="wpc-warning">'+ wpc_pro_pao_obj.addons.pao_title +'</div>');
						has_error = border_error = true;
					} else {
							pao_title.removeClass( 'wpc-pro-pao-error' ).next('div').remove();
					}
				});

				// option name on keyup
				option_name.on('keyup',function(){
					if ( $.trim(option_name.val()).length == 0) {
							current_this.parents( '.wpc-pro-pao-fields' ).eq(0).addClass( 'wpc-pro-pao-error' );
							option_name.addClass( 'wpc-pro-pao-error' ).
							after('<div class="wpc-warning">'+ wpc_pro_pao_obj.addons.option_name +'</div>');
							has_error = border_error = true;
					} else {
							option_name.removeClass( 'wpc-pro-pao-error' ).next('div').remove();
					}
				});
			}
			else{
				// check on submit
				if ( $.trim(pao_title.val()).length == 0) {
					current_this.addClass( 'wpc-pro-pao-error' );
					pao_title.addClass( 'wpc-pro-pao-error' );
					if (!pao_title.next().hasClass("wpc-warning")) {
						pao_title.after('<div class="wpc-warning">'+ wpc_pro_pao_obj.addons.pao_title +'</div>');
					}
					has_error = border_error = true;
				} else {
						pao_title.removeClass( 'wpc-pro-pao-error' );
				}

				if ( pao_type.val() != 'text' ) {
						var option_name = current_this.find( '.wpc-pro-pao-option-label input:first' );

						if ( $.trim(option_name.val()).length == 0) {
								option_name.addClass( 'wpc-pro-pao-error' );
								if (!option_name.next().hasClass("wpc-warning")) {
									option_name.after('<div class="wpc-warning">'+ wpc_pro_pao_obj.addons.option_name +'</div>');
								}
								current_this.parents( '.wpc-pro-pao-fields' ).eq(0).addClass( 'wpc-pro-pao-error' );
								has_error = border_error = true;
						} else {
								option_name.removeClass( 'wpc-pro-pao-error' );
						}
				}
				// for individual field block: if no error
				if ( !border_error ) {
						current_this.removeClass( 'wpc-pro-pao-error');
				}
				if (has_error) {
					$('html, body').animate({
						scrollTop: ( current_this.offset().top )
					}, 1000 );
				}
			}

	});

	return has_error;
}

})(jQuery);

function paoBlockIndexesChange($) {
    $( '.wpc_pro_pao_main_block .wpc-pro-pao-fields' ).each( function( i, ele ) {
        var position = parseInt( $( ele ).index( '.wpc_pro_pao_main_block .wpc-pro-pao-fields' ) );
        $( '.wpc_pro_pao_position', ele ).val( position );
    } );
}