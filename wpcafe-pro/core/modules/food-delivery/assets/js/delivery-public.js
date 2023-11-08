(function($) {
	"use strict";

	$(document).ready(function() {
			let map_parent              = ".wpc-front-map";
			let map_container           = "#wpc-front-map-container";
			let location_result_field   = ".wpc-location-result";
			let map_result_wrapper      = ".wpc_map_and_result_wrapper";
			let loader_wrapper          = ".wpc_loader_wrapper";
			
			let location_data       = localStorage.getItem('wpc_location');
			let default_loc         = '';
			if(location_data !== null) {
					var location_data_parse = JSON.parse(location_data);
					default_loc = location_data_parse.value;
					$('#wpc_loc_address').val(default_loc);
			}

			var map_settings = {
					map_parent                  : map_parent,
					map_container               : map_container,
					location_result_field       : location_result_field,
					map_result_wrapper          : map_result_wrapper,
					loader_wrapper              : loader_wrapper,
					lat                         : $(map_parent).data('lat'),
					long                        : $(map_parent).data('long'),
					zoom                        : $(map_parent).data('zoom'),
					radius                      : $(map_parent).data('radius'),
					redirect_url                : $(map_parent).data('redirect_url'),
					markers                     : [],
					radiusCircle                : {},
			};

			if (typeof google === 'object' && typeof google.maps === 'object') {
	            let geocoder = new google.maps.Geocoder();
	            map_settings.geocoder = geocoder;

	            if( $(map_container).length > 0 ) {
	                map_functions.locationMapFunctionalities($, map_settings, true);
	                if(default_loc != '') {
	                    $('.wpc_loc_address_search').trigger('click');
	                }
	            }
	        }

			/**
			 * Get user default position of user
			 */
			$('#wpc_loc_my_position').on('click', function(e){
					e.preventDefault();
					map_functions.getMyPosition($, map_settings);
			});

			
			/**
			 * Get nearest location on type
			 */
			var wpc_loc_address = $("#wpc_loc_address");
			
			$(wpc_loc_address).on("keyup",function(){
					var location_keys = $(this).val();
					if ( location_keys.length >= 3 ) {
							get_auto_complete("wpc_loc_address")
					}
			});

			/**
			 * Get Address from googl api response
			 * @param {*} search_input 
			 */
			function get_auto_complete(search_input) {
					var autocomplete;
					autocomplete = new google.maps.places.Autocomplete((document.getElementById(search_input)), {
							types: ['geocode']
						 
					});
					
					google.maps.event.addListener(autocomplete, 'place_changed', function () {
							var near_place = autocomplete.getPlace();
							if( $('.wpc_loc_address_search').length > 0 ) {
									$('.wpc_loc_address_search').trigger('click');
							}
					});
			}

							
			/**
			 * Checkout Address validation
			 */

			 checkout_address_validation();

			 function checkout_address_validation() {
					 var address_validation = typeof wpc_pro_delivery_obj.form_data !=="undefined" ? wpc_pro_delivery_obj.form_data.address_validation : "";
					 if ( address_validation =="on") {
							 var search_input = "billing_address_1";
							 $("#"+search_input).on('keyup',function(){
									 if ($(this).val().length >= 3 ) {
											get_auto_complete(search_input);
									 }
							 })
					 }
			 }

			/**
			 * Search button click to find location
			 */
			$('.wpc_loc_address_search').on('click', function(e){
					let address_val    = $('#wpc_loc_address').val();
	
					if(address_val) {
							geocoder.geocode( {address: address_val}, function (res, status) {
									if(status === google.maps.GeocoderStatus.OK){
											let outLatLong      = res[0].geometry.location;
											map_settings.lat    = outLatLong.lat();
											map_settings.long   = outLatLong.lng();
											map_functions.locationMapFunctionalities($, map_settings, false, true);
											map_functions.locationSearchResult($, map_settings);
									}
							});
					} else {
							alert('Location field is empty.');
					}
			});

			let wpcpro_delivery = localStorage.getItem('wpcpro_delivery');
			if(wpcpro_delivery == 'Delivery') {
					$('.wpc_opt_delivery').addClass('active');
					$('.wpc_opt_pickup').removeClass('active');
			} else {
					$('.wpc_opt_pickup').addClass('active');
					$('.wpc_opt_delivery').removeClass('active');
			}

			/**
			 * Pickup delivery on click add/remove active class
			 */
			$('.wpc_opt_delivery_pickup').on('click', function(e){
					let current_this = $(this);
					let pick_deli    = current_this.data('opt');
			
					localStorage.setItem('wpcpro_delivery', pick_deli)

					if(pick_deli == 'Delivery') {
							$('.wpc_opt_delivery').addClass('active');
							$('.wpc_opt_pickup').removeClass('active');
					} else {
							$('.wpc_opt_pickup').addClass('active');
							$('.wpc_opt_delivery').removeClass('active');
					}
			});


	});
})(jQuery);

var map_functions = {
	// all map initialization functionalities
	locationMapFunctionalities: function($, map_settings, initial=false, ajax_time=false) {
			let container       = $(map_settings.map_container);
			let zoom            = parseInt(map_settings.zoom);
			let lat             = parseFloat(map_settings.lat);
			let long            = parseFloat(map_settings.long);

			if( !isNaN(lat) && !isNaN(long) ) {
					// map drawing
					let map_params = {
							zoom        : zoom,
							center      : new google.maps.LatLng(lat, long),
							scrollwheel : false,
					};

					let d_map                    = new google.maps.Map(container[0], map_params);
					map_settings.map             = d_map;
					map_settings.currentPosition = map_params.center;

					if(!initial) {
							// delete old marker
							if(!map_functions.isEmptyFunc(map_settings.marker)) {
									map_settings.marker.setMap(null);
							}

							// marker generate
							let marker_params = {
									map       : map_settings.map,
									position  : new google.maps.LatLng(lat, long),
									draggable : false,
							};
							let d_marker = new google.maps.Marker(marker_params);
							d_marker.setMap(map_settings.map);

							map_settings.marker = d_marker;
					}

					map_settings.map.addListener('tilesloaded', function () {
							if(!ajax_time) {
									$(map_settings.map_result_wrapper).removeClass('wpc_map_loading');
									$(map_settings.loader_wrapper).css({ 'display': 'none' });
							}
					} );
			}
	},
	/**
	 * Get default position
	 * 
	 * @param {*} $ 
	 * @param {*} address_field 
	 */
	getMyPosition: function($, map_settings) {
			if (navigator.geolocation) {

					var options = {
							enableHighAccuracy: true,
							timeout           : 5000,
							maximumAge        : 0
					};

					navigator.geolocation.getCurrentPosition(function(position) {
							let address_field = $('#wpc_loc_address');

							let pos_coords = position.coords;
							let lat        = pos_coords.latitude;
							let long       = pos_coords.longitude;
							map_settings.lat    = lat;
							map_settings.long   = long;
							var currentPosition = new google.maps.LatLng(lat, long); 

							map_functions.latLongToAddress($, map_settings, currentPosition, address_field);

					}, function(error) {
							alert('Only secure origins(HTTPS sites, basically) are allowed. To know more https://goo.gl/Y0ZkNV');
					}, options);
			}
	},

	// convert current position's lat, long to address
	latLongToAddress: function($, map_settings, currentPosition, address_field) {
			let lat_lng = {
					lat: currentPosition.lat(), 
					lng: currentPosition.lng()
			};

			map_settings.geocoder.geocode({'location': lat_lng}, function(res, status) {
					if (status === google.maps.GeocoderStatus.OK) {
							if(res[1]) {
									let res1_address            = res[1].address_components;
									let new_formatted_address   = res1_address[1].short_name + ' ' + res1_address[5].short_name + ', ' + res1_address[4].long_name;
									
									// address_field.val(res[1].formatted_address);
									address_field.val(new_formatted_address);
									map_functions.locationMapFunctionalities($, map_settings, false)
							} else {
									window.alert('No result found');
							}
					} else {
							window.alert('An error occured due to Geocoder failed.');
					}
			});
	},

	// location search result functionalities
	locationSearchResult: function($, map_settings) {
			// draw the circle of the search area
			// map_functions.drawRadiusCircleFunc($, map_settings, true);

			// get search specific locations
			map_functions.getLocations($, map_settings);
	},

	// dynamic way zoom level from radius
	radiusToZoom: function(radius){
			return Math.round(14-Math.log(radius)/Math.LN2);
	},

	// check is empty or not
	isEmptyFunc: function(obj) {

			if (obj == null)		return true;
			if (obj.length > 0)		return false;
			if (obj.length === 0)	return true;

			for (var key in obj) {
					if (hasOwnProperty.call(obj, key)) return false;
			}

			return true;
	},

	// draw circle area in map 
	drawRadiusCircleFunc: function($, map_settings, inital) {
			let mapRadius;
			var earthRadius = 6371;

			map_settings.earthRadi_mt                       = 6378100;
			map_settings.mapDrawRadiusCircleFillColor       = '#004de8';
			map_settings.mapDrawRadiusCircleFillOpacity     = 27;
			map_settings.mapDrawRadiusCircleStrokeColor     = '#004de8';
			map_settings.mapDrawRadiusCircleStrokeOpacity   = 62;

			if(!map_functions.isEmptyFunc(map_settings.radiusCircle) && typeof(map_settings.radiusCircle.setMap) !== "undefined") {
					map_settings.radiusCircle.setMap(null);
			}

			// map_settings.map.setZoom(map_functions.radiusToZoom(map_settings.radius));
			map_settings.map.setZoom(10);

			mapRadius = (map_settings.radius / earthRadius) * map_settings.earthRadi_mt;
			map_settings.radiusCircle = new google.maps.Circle({
					center: map_settings.currentPosition,
					clickable: true,
					draggable: false,
					editable: false,
					fillColor: map_settings.mapDrawRadiusCircleFillColor,
					fillOpacity: map_settings.mapDrawRadiusCircleFillOpacity / 100,
					map: map_settings.map,
					radius: mapRadius,
					strokeColor: map_settings.mapDrawRadiusCircleStrokeColor,
					strokeOpacity: map_settings.mapDrawRadiusCircleStrokeOpacity / 100,
					strokeWeight: 1
			});

			if(inital !== true) {
					map_settings.map.fitBounds(map_settings.radiusCircle.getBounds());
			}

			if(!map_functions.isEmptyFunc(map_settings.radiusCircle)) {
					map_settings.map.addListener('idle', function () {
							$(map_settings.map_result_wrapper).removeClass('wpc_map_loading');
							$(map_settings.loader_wrapper).css({ 'display': 'none' });
					} );
			}
	},

	// ajax call to calculate and get store locations
	getLocations: function($, map_settings) {

			$.ajax({
					url: wpc_pro_delivery_obj.ajax_url,
					type: 'post',
					dataType: 'JSON',
					data: {
							action      : 'get_all_locations',
							security    : wpc_pro_delivery_obj.location_map_nonce,
							lat         : map_settings.lat,
							lng         : map_settings.long,
							radius      : map_settings.radius,
							redirect_url     : map_settings.redirect_url,
					},
					beforeSend: function() {
							$(map_settings.map_result_wrapper).addClass('wpc_map_loading');
							$(map_settings.loader_wrapper).css({ 'display': 'block' });
					},
					success: function(res) {
							if(res.success){
									let data                 = res.data.data;
									let locations            = data.locations;
									let locations_html       = data.locations_html;
									let locations_html_data  = data.locations_html_data;

									if(locations_html != ''){

									} else {
											locations_html = res.data.message;
									}

									$(map_settings.location_result_field).html(locations_html);
									map_functions.createLocationMarkers($, map_settings, locations, locations_html_data);
							}

							map_functions.drawRadiusCircleFunc($, map_settings, true);
					},
					error: function(jqXHR, textStatus, errorThrown) {
							map_functions.drawRadiusCircleFunc($, map_settings, true);

							alert('An Error Occured: ' + jqXHR.status + ' ' + errorThrown + '! Please contact System Administrator!');
					}
			});
	},

	// location marker show in map
	createLocationMarkers: function($, map_settings, locations, locations_html_data) {
			// clean existing all markers
			while(map_settings.markers.length){
					map_settings.markers.pop().setMap(null);
			}

			let loc_len = Object.keys(locations).length;
			if(loc_len>0) {
					let i = 0;
					let location;
					let marker;
					let map = map_settings.map;

					let info_window = new google.maps.InfoWindow({
							content: ""
					});

					// create markers
					for(i; i<loc_len; i++){
							location           = locations[i];
							location.icon      = wpc_pro_delivery_obj.location_icon;
							location.map       = map;
							location.position  = new google.maps.LatLng(location.lat, location.lng);

							marker = new google.maps.Marker(location);
							map_settings.markers.push(marker);

							map_functions.createInfoWindow($, map_settings, marker, map, info_window, location, locations_html_data[i]);
					}
			}
	},

	// in map, clicking marker icon will toggle marker specific information 
	createInfoWindow: function($, map_settings, marker, map, info_window, location, location_html) {
			marker.addListener('click', function() {
					info_window.setContent(location_html);
					info_window.open(map, this);

					google.maps.event.addListener(map, 'click', function() {
							info_window.close();
					});
					google.maps.event.addListener(map_settings.radiusCircle, 'click', function() {
							info_window.close();
					});
			});
	},
};
