<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_add_cart_item_data', 'wscsd_add_item_data', 10, 3 );

/** 
 * Add custom data to Cart
 * 
 * @param  [type] $cart_item_data [description]
 * @param  [type] $product_id     [description]
 * @param  [type] $variation_id   [description]
 * @return [type]                 [description]
 *
 */
function wscsd_add_item_data( $cart_item_data, $product_id, $variation_id ) {
	
	if ( isset( $_REQUEST['wscsd_start_date'] ) ) {
		$cart_item_data['wscsd_start_date'] = sanitize_text_field( $_REQUEST['wscsd_start_date'] );
		remove_action( 'woocommerce_calculated_total', 'WC_Subscriptions_Cart::calculate_subscription_totals', 1000, 2 );
	} else {
		$delay_type = get_post_meta( $product_id, '_wscsd_delay_type', true );
		if ( !empty( $delay_type ) ) {
			$hide_date = get_post_meta( $product_id, '_wscsd_hide_date', true );
			$max_dates = get_post_meta( $product_id, '_wscsd_max_dates', true );
			if ( ( true == $hide_date ) || ( 1 == $max_dates ) ) {
				//if date is hidden
				$dates = '';
				$start_date = '';
				if ( 'fixed' == $delay_type ) {
					// Fixed dates
					$dates = apply_filters( 'wscsd_filter_future_dates', get_post_meta( $product_id, '_wscsd_fixed_start_dates', true ), $product_id);

					if ( !empty( $dates ) ) {
						foreach ( $dates as $date ) {
							if ( ( !empty( $date ) ) && ( $date >= get_cut_off_date( $product_id ) ) ) {
								if ( empty( $start_date ) || ( $date < $start_date ) ) {
									$start_date = $date;
								}
							}
						}
					}
				} elseif ( 'delay' == $delay_type ) {
					// Fixed delay
					$delays = get_post_meta( $product_id, '_wscsd_fixed_delays', true );
					if ( !empty( $delays ) ) {
						$start_date = gmdate( 'Y-m-d', strtotime( $delays[0] ) );		
					}
				}
				$cart_item_data['wscsd_start_date'] = esc_attr( $start_date );
			}
		}
	}
	return $cart_item_data;
}


add_filter( 'woocommerce_get_item_data', 'wscsd_add_item_meta', 10, 2 );

/** 
* Display information as Meta on Cart page
* 
 * @param  [type] $item_data [description]
 * @param  [type] $cart_item [description]
 * @return [type]            [description]
 *
 */
function wscsd_add_item_meta( $item_data, $cart_item ) {
	
	if ( array_key_exists( 'wscsd_start_date', $cart_item ) ) {
		$custom_details = $cart_item['wscsd_start_date'];

		$item_data[] = array( 
			'key'   => __('Start date', 'wscsd'),
			'value' => $custom_details
		);
	}

	return $item_data;
}

