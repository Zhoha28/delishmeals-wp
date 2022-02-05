<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_before_add_to_cart_button', 'wscsd_pick_date_field', 20 );
/** 
 * Adds custom field for Product
 * 
 * @return [type] [description]
 *
 */
function wscsd_pick_date_field() {
	global $product, $post;
	ob_start();

	$delay_type = get_post_meta( $post->ID, '_wscsd_delay_type', true );
	$dates = '';
	$start_date = '';
	
	if ( !empty( $delay_type ) ) {
		$start_label = get_post_meta( $post->ID, '_wscsd_start_label', true );
		$hide_date = get_post_meta( $post->ID, '_wscsd_hide_date', true );
		$max_dates = get_post_meta( $post->ID, '_wscsd_max_dates', true );
		$multiple_dates = 1;
		if ( 1 == $max_dates ) {
			$multiple_dates = 0;
		}
		$count_dates = 1;
		$nb_date_displayed = 0;

		if ( 'fixed' == $delay_type ) {
			// Fixed dates
			$dates = apply_filters( 'wscsd_filter_future_dates', get_post_meta( $post->ID, '_wscsd_fixed_start_dates', true ), $post->ID );

			if ( !empty( $dates ) ) {
				
				if ( true == $hide_date ) {
					//if date is hidden
					foreach ( $dates as $date ) {
						if ( ( !empty( $date ) ) && ( $date >= get_cut_off_date( $post->ID ) ) ) {
							if ( empty( $start_date ) || ( $date < $start_date ) ) {
								$start_date = $date;
								echo '<input type="hidden" id="wscsd_start_date" name="wscsd_start_date" style="display:none;" value="' . esc_html__( $start_date ) . '">';
							}
						}
					}
				} else {
					//if date( s ) is shown
					
					if ( ( ( 1 == count( $dates ) ) || ( 0 == $multiple_dates ) ) && ( $dates[0] >= get_cut_off_date( $post->ID ) ) ) {
						// Only one date
						echo '<div class="wscsd_date_picker">';
						if ( !empty( $start_label ) ) {
							// Label
							echo '<label for="wscsd_date_picker_display">' . esc_html__( $start_label, 'wscsd' ) . '</label>';
						}
						echo '<span id="wscsd_start_date_display">' . esc_html__( gmdate( get_option( 'date_format' ), strtotime( $dates[0] ) ) ) . '</span>';
						echo '<input type="hidden" id="wscsd_start_date" name="wscsd_start_date" value="' . esc_html__( $dates[0] ) . '" style="display:none;">';
						echo '</div>';

					} elseif ( ( 1 < count( $dates ) ) && ( 1 == $multiple_dates ) ) {
						// Multiple dates
						$future_dates = array();
						foreach ( $dates as $date ) {
							if ( ( !empty( $date ) ) && ( $date >= get_cut_off_date( $post->ID ) ) ) {
								if ( ( $count_dates <= $max_dates ) ||  ( 0 == $max_dates ) || ( '' == $max_dates ) ) {
									$future_dates[] = $date;
									$start_date = $date;
									$count_dates++;
								}	
							}
						}
						if ( 1 == count( $future_dates ) ) {
							echo '<div class="wscsd_date_picker">';
							if ( !empty( $start_label ) ) {
								// Label
								echo '<label for="wscsd_date_picker_display">' . esc_html__( $start_label ) . '</label>';
							}
							echo '<span id="wscsd_date_picker_display" name="wscsd_date_picker_one" value="' . esc_html( $start_date ) . '">' . esc_html__( gmdate( get_option( 'date_format' ), strtotime( $start_date ) ) ) . '</span>';
							echo '</div>';
						} elseif ( 1 < count( $future_dates ) ) {
							echo '<div class="wscsd_date_picker">';
							if ( !empty( $start_label ) ) {
								// Label
								echo '<label for="wscsd_date_picker_display">' . esc_html__( $start_label, 'wscsd'  ) . '</label>';
							}
							echo '<span id="wscsd_date_picker_display" name="wscsd_date_picker_dropdown">';
							echo '<select id="wscsd_start_date" name="wscsd_start_date">';
							foreach ( $future_dates as $future_date ) {
								echo '<option value="' . esc_html__( $future_date ) . '">' . esc_html__( gmdate( get_option( 'date_format' ), strtotime( $future_date ) ) ) . '</option>';   		    
							}
							echo '</select>';
							echo '</span>';
							echo '</div>';
						}
					}

				}
			}
		} elseif ( 'delay' == $delay_type ) {
			$delays = get_post_meta( $post->ID, '_wscsd_fixed_delays', true );
			if ( !empty( $delays ) ) {
				echo '<div class="wscsd_date_picker">';
				if ( !empty( $start_label ) ) {
					// Label
					echo '<label for="wscsd_date_picker_display">' . esc_html__( $start_label, 'wscsd'  ) . '</label>';
				}
				if ( 1 == count( $delays ) ) {
						// Only one delay
					echo '<span id="wscsd_start_date_display">' . esc_html__( gmdate( 'Y-m-d', strtotime( $delays[0] ) ) ) . '</span>';
					echo '<input id="wscsd_start_date" name="wscsd_start_date" value="' . esc_html__( gmdate( get_option( 'date_format' ), strtotime( $delays[0] ) ) ) . '" style="display:none;">';
				} else {
					//multiple delays
					echo '<span id="wscsd_date_picker_display" name="wscsd_date_picker_dropdown">';
					echo '<select id="wscsd_start_date" name="wscsd_start_date">';
					foreach ( $delays as $delay ) {
						echo '<option value="' . esc_html__( gmdate( 'Y-m-d', strtotime( $delay ) ) ) . '">' . esc_html__( gmdate(get_option( 'date_format' ), strtotime( $delay ) ) ) . '</option>';      
					}
							echo '</select>';
							echo '</span>';
				}
				echo '</div>';
				
			}
		} elseif ( 'calendar' == $delay_type ) {
			echo '<div class="wscsd_date_picker">';
			if ( !empty( $start_label ) ) {
				// Label
				echo '<label for="wscsd_start_date">' . esc_html__( $start_label, 'wscsd'  ) . '</label>';
			}
			$defaultDate = gmdate( 'Y-m-d', strtotime( 'now' ) );
			if ( get_cut_off_date( $post->ID ) > $defaultDate ) {
				$defaultDate = get_cut_off_date( $post->ID );
			}
			echo '<input type="date" class="short wscsd_calendar_dates_field" style="" id="wscsd_start_date" name="wscsd_start_date" min="' . esc_html( get_cut_off_date( $post->ID ) ) . '" value="' . esc_html( $defaultDate ) . '" placeholder="' . esc_html( $defaultDate ) . '">';
			echo '</div>';


		}
		echo '<div class="clear"></div>';
	}
	$content = ob_get_contents();
	ob_end_flush();

	return $content;

}

function get_cut_off_date( $post_ID ) {
	//Cut off date
	$wscsd_cut_off = get_post_meta( $post_ID, '_wscsd_cut_off', true ) ? get_post_meta( $post_ID, '_wscsd_cut_off', true ) : 0;
	$wscsd_cut_off_period = get_post_meta( $post_ID, '_wscsd_cut_off_period', true ) ? get_post_meta( $post_ID, '_wscsd_cut_off_period', true ) : 'day';
	$wscsd_cut_off_date = gmdate( 'Y-m-d' );
	if ( 0 != $wscsd_cut_off ) {
		$wscsd_cut_off_date =  gmdate( 'Y-m-d', strtotime( '+' . $wscsd_cut_off . ' ' . $wscsd_cut_off_period ) );
	}
	return $wscsd_cut_off_date;
}

function remove_dates_before_cut_off( $dates, $post_ID ) {
	$wscsd_cut_off_date = get_cut_off_date( $post_ID );
	$i = 0 ;
	foreach ( $dates as $date ) {
		if ( ( empty( $date ) ) || ( $date < $wscsd_cut_off_date ) ) {
			array_splice( $dates, $i, 1 );
		}
		$i++;
	}
	return $dates;
}
add_filter( 'wscsd_filter_future_dates', 'remove_dates_before_cut_off', 100, 2 );


// Display selected variation custom field value to product the tab
add_action( 'woocommerce_after_variations_form', 'display_selected_variation_custom_field_js' );
function display_selected_variation_custom_field_js() {
	?>
	<script type="text/javascript">
	(function($){
		$('form.cart').on('show_variation', function(event, data) {
			var delay_form = '';
			if ( '' != data.delay_type ) {
				//Cut off date calculation
				var cutDate = new Date();
				if ( 'days' == data.cut_off_period ) {
					cutDate.setDate(cutDate.getDate() + parseInt( data.cut_off ) );
				} else if ( 'weeks' == data.cut_off_period ) {
					cutDate.setDate(cutDate.getDate() + ( parseInt( data.cut_off ) * 7 ) );
				} else if ( 'months' == data.cut_off_period ){
					cutDate.setMonth(cutDate.getMonth() + parseInt( data.cut_off ) );
				} else if ( 'years' == data.cut_off_period ) {
					cutDate.setFullYear(cutDate.getFullYear() + parseInt( data.cut_off ) );
				}
				minDate = cutDate.getFullYear() + '-' + ( 9 > cutDate.getMonth() ? '0' : '') + ( cutDate.getMonth() + 1 ) + '-' + ( 9 > cutDate.getDate() ? '0' : '') + cutDate.getDate();

				defaultDate = new Date();
				if ( cutDate > defaultDate ) {
					defaultDate = cutDate;
				}
				defaultStartDate = defaultDate.getFullYear() + '-' + ( 9 > defaultDate.getMonth() ? '0' : '') + ( defaultDate.getMonth() + 1 ) + '-' + ( 9 > defaultDate.getDate() ? '0' : '') + defaultDate.getDate();
				
				if ( 'calendar' == data.delay_type ) {
					delay_form += '<div class="wscsd_date_picker">';
				
					if ( '' != data.start_label ) {
						// Label
						delay_form += '<label for="wscsd_date_picker_display">' + data.start_label + '</label>';
					}
					delay_form += '<div class="wscsd_date_picker"><input type="date" class="short wscsd_calendar_dates_field" style="" id="wscsd_start_date" name="wscsd_start_date" min="'+ minDate + '" value="' + defaultStartDate + '" placeholder="'+ defaultStartDate + '"></div>';

				} else if ( 'fixed' == data.delay_type ) {
					//fixed dates
					if ( 'yes' == data.hide_date ) { 
						//hiden dates
						closest_start_date = '';
						if (( 1 < data.fixed_date.length ) && ( '' != data.fixed_date )) {
							$.each(data.fixed_date, function(key,  value){
								if ( value > minDate ) {
									if ( ( closest_start_date == '' ) || ( closest_start_date > value ) ) {
										closest_start_date = value;
									}
								}
							});
						} else {
							closest_start_date = data.fixed_date;
							
						}
						if ('' == closest_start_date) {
							closest_start_date = new Date();
						}
						if ( closest_start_date != '' ) {
							delay_form +='<input type="hidden" id="wscsd_start_date" name="wscsd_start_date" style="display:none;" value="' + closest_start_date + '">';
						}
				
					} else {
						delay_form += '<div class="wscsd_date_picker">';
				
						if ( '' != data.start_label ) {
							// Label
							delay_form += '<label for="wscsd_date_picker_display">' + data.start_label + '</label>';
						}
						if (( 1 < data.fixed_date.length ) && ( '' != data.fixed_date )) {
							delay_form += '<span id="wscsd_date_picker_display" name="wscsd_date_picker_dropdown"><select id="wscsd_start_date" name="wscsd_start_date">';
							$.each(data.fixed_date, function(key,  value){
								if ( value > minDate ) {
									arrayofStartDate = value.split("-");
									delay_form += '<option value="' + value + '">' + new Date(arrayofStartDate[0], (arrayofStartDate[1] - 1),arrayofStartDate[2]).toLocaleDateString() + '</option>';
								}
							});
							delay_form += '</select></span>';
						} else {
							value = data.fixed_date[0];
							if ('' == value) {
									value = minDate;
								}
							arrayofStartDate = value.split("-");

							delay_form += '<span id="wscsd_start_date_display">' + new Date(arrayofStartDate[0], (arrayofStartDate[1] - 1),arrayofStartDate[2]).toLocaleDateString() + '</span> <input type="hidden" id="wscsd_start_date" name="wscsd_start_date" value="' +  value + '">' ;

						}
						delay_form += '</div>';
					}
						
				} else if ( 'delay' == data.delay_type  ) {
					//delay_form += data.fixed_delay.length;
					if ( 'yes' == data.hide_date ) { 
				
					} else {
						delay_form += '<div class="wscsd_date_picker">';
				
						if ( '' != data.start_label ) {
							// Label
							delay_form += '<label for="wscsd_date_picker_display">' + data.start_label + '</label>';
						}
						if ( 1 < data.fixed_delay.length  ) {
							//multiple delays
							delay_form += '<span id="wscsd_date_picker_display" name="wscsd_date_picker_dropdown"><select id="wscsd_start_date" name="wscsd_start_date">';
							$.each(data.fixed_delay, function(key,  value){
								var nb = value.split(' ')[0];
								var period = value.split(' ')[1];
								var date = new Date(); 
								if ( 'days' == period ) {
									date.setDate(date. getDate() + parseInt( nb ) );
								}if ( 'weeks' == period ) {
									date.setDate(date. getDate() + (parseInt( nb ) * 7) );
								} else if ( 'months' == period ) {
									date.setMonth( date.getMonth() +  parseInt( nb ) );
									 
								} else if ( 'years' == period ) {
									date.setFullYear()( date.getFullYear() +  parseInt( nb ) );

								}
								delay_form += '<option value="' + date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2)  + '">'  + date.toLocaleDateString() + '</option>';
							});
							delay_form += '</select></span>';
						} else {
							// Only one delay
							var value = data.fixed_delay;
							var nb = parseInt(value[0].split(' ')[0]);
								var period = value[0].split(' ')[1];
								var date = new Date();
								if ( 'days' == period) {
									date.setDate(date.getDate() + nb);
								} else if ( 'months' == period ) {
									date.setMonth( date.getMonth() + nb );
								} else if ( 'years' == period ) {
									date.setFullYear()( date.getFullYear() + nb );
								}
							delay_form += '<span id="wscsd_start_date_display">'  + date.toLocaleDateString() + '</span><input id="wscsd_start_date"  type="hidden" name="wscsd_start_date" value="'  + date.getFullYear() + '-' + (date.getMonth()+ 1) + '-' + date.getDate()  + '" style="display:none;">';
						
						} 
						delay_form += '</div>';
					}
				}
				
				$(delay_form).insertAfter('.woocommerce-variation-description');
			}
			
		}).on('hide_variation', function(event) {
			$('.wscsd_date_picker').remove();
		});
	})(jQuery);
	</script>
	<?php
}
