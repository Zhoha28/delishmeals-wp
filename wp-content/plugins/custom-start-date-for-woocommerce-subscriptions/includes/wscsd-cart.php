<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_subscriptions_recurring_cart_key', 'wscsd_add_custom_cart_key_meta', 10, 2 );

function wscsd_add_custom_cart_key_meta( $cart_key, $cart_item ) {
	//prevent the grouping of subscriptions when they have different start dates by adding the start date to the $cart_key
	if ( array_key_exists( 'wscsd_start_date', $cart_item ) ) {
		$start_date_key = $cart_item['wscsd_start_date'];

		if ( !empty( $start_date_key ) ) {
			$cart_key .= $start_date_key;
		}
	}
	return $cart_key;
}

add_filter( 'wcs_recurring_cart_next_payment_date', 'wscsd_change_next_date', 100, 3 );
function wscsd_change_next_date( $date, $recurring_cart, $product ) {
	$customer_selected_start_date = '';
	foreach ( $recurring_cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( ( ''== $customer_selected_start_date ) && ( array_key_exists( 'wscsd_start_date', $cart_item ) ) && ( !empty( $date ) ) ) {
			$site_time_offset = (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
			$customer_selected_start_time = strtotime( $cart_item['wscsd_start_date'] );
			$customer_selected_start_time -= $site_time_offset;
			$customer_selected_start_date = gmdate( 'Y-m-d H:i:s', $customer_selected_start_time );
			$date = WC_Subscriptions_Product::get_first_renewal_payment_date( $product, $customer_selected_start_date );
			$today = gmdate( 'Y-m-d');
			if ( $date <= $today ) {
				$date = WC_Subscriptions_Product::get_first_renewal_payment_date( $product, $today );
			}
			if ( ( gmdate( 'Y-m-d', strtotime( $date ) ) == gmdate( 'Y-m-d', strtotime( $customer_selected_start_date ) ) || ( gmdate( 'Y-m-d', strtotime( $date ) ) == gmdate( 'Y-m-d', strtotime( $cart_item['wscsd_start_date'] ) ) ) ) ) {
				//synchronized payment
				$prorate_synced_payments = ( 'no' == get_option( WC_Subscriptions_Admin::$option_prefix . '_sync_payments' ) ? 'none' : get_option( WC_Subscriptions_Admin::$option_prefix . '_prorate_synced_payments', 'none' ) );
				if ( 'recurring' == $prorate_synced_payments ) {
					//check if the amount if charged upfront 'recurring'
					$synchronized_date = gmdate( 'Y-m-d  H:i:s', strtotime( $date . '+1 day' ) );
					$date = WC_Subscriptions_Product::get_first_renewal_payment_date( $product, $synchronized_date );
				} /*elseif ( ( 'yes' == $prorate_synced_payments ) || ( ( 'virtual' == $prorate_synced_payments ) && ( $product->is_virtual() ) ) ) {
					//add_filter( 'woocommerce_subscriptions_synced_first_payment_date', function( $first_payment, $product, $type, $from_date, $from_date_param ) use ( $date ) { return $date; }  );
				}*/
			}
			break; 
		}
	}
			
	return $date;
}

add_filter( 'wcs_recurring_cart_end_date', 'wscsd_change_end_date', 100, 3 );
function wscsd_change_end_date( $date, $recurring_cart, $product ) {
	if ( !empty( $date ) ) {
		$customer_selected_start_date = '';
		foreach ( $recurring_cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( ( ''== $customer_selected_start_date ) && ( array_key_exists( 'wscsd_start_date', $cart_item ) ) ) {
				$customer_selected_start_date = gmdate( 'Y-m-d', strtotime( $cart_item['wscsd_start_date'] ) );
				$old_start_date = gmdate( 'Y-m-d', strtotime( $recurring_cart->start_date ) );
				$old_end_date = gmdate( 'Y-m-d', strtotime( $date ) );
				$diff_payment_dates = ( strtotime( $customer_selected_start_date ) - strtotime( $old_start_date ) );
				$new_end_date = strtotime ( $old_end_date ) + $diff_payment_dates;
				$date = gmdate( 'Y-m-d H:i:s', $new_end_date );
				break; 
			}
		}
	}
	
			
	return $date ;
}
add_filter( 'wcs_recurring_cart_trial_end_date', 'wscsd_change_trial_end_date', 100, 3 );
function wscsd_change_trial_end_date( $date, $recurring_cart, $product ) {
	if ( !empty( $date ) ) {
		$customer_selected_start_date = '';
		foreach ( $recurring_cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( ( ''== $customer_selected_start_date ) && ( array_key_exists( 'wscsd_start_date', $cart_item ) ) ) {
				$site_time_offset = (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
				$customer_selected_start_date = gmdate( 'Y-m-d', strtotime( $cart_item['wscsd_start_date'] ) );
				$old_start_date = gmdate( 'Y-m-d', strtotime( $recurring_cart->start_date ) );
				$old_trial_end_date = gmdate( 'Y-m-d', strtotime( $date ) );
				$diff_payment_dates = ( strtotime( $customer_selected_start_date ) - strtotime( $old_start_date ) );
				$new_trial_end_date = strtotime ( $old_trial_end_date ) + $diff_payment_dates - $site_time_offset;
				$date = gmdate( 'Y-m-d H:i:s', $new_trial_end_date );
				break; 
			}
		}
	}
			
	return $date ;
}

add_filter( 'woocommerce_get_item_data', 'wscsd_start_date_cart_formats', 10, 2 );
//Better display date with site format in cart
function wscsd_start_date_cart_formats( $item_data, $cart_item ) {
	if ( !empty( $item_data ) ) {
		foreach ( $item_data as $index=>$item_column ) {
			if ( ( array_key_exists( 'key', $item_column ) ) && ( 'Start date' == $item_column['key'] ) ) {
				$old_date = $item_data[$index]['value'];
				$formated_date = date_i18n( wc_date_format(), strtotime( $old_date ) );
				$item_data[$index]['value'] = $formated_date;
			}

		}
		
	}

	return $item_data;
}


/*
add_filter( 'woocommerce_subscriptions_cart_get_price', 'wscsd_pay_later_price', 100, 2 );
function wscsd_pay_later_price( $price, $product ) {

	if ( WC_Subscriptions_Product::is_subscription( $product ) ) {
		$calculation_type = WC_Subscriptions_Cart::get_calculation_type();

			// For original calculations, we need the items price to account for delayed payments
			if ( 'none' == $calculation_type ) {

				$price = 0;
			}  
 
	}

	return $price;
}

//apply_filters( 'woocommerce_subscriptions_cart_shipping_up_front', $charge_shipping_up_front );


function cart_contains_delayed_payment() {

		$cart_contains_free_trial = false;

		if ( WC_Subscriptions_Cart::cart_contains_subscription() ) {
			foreach ( WC()->cart->cart_contents as $cart_item ) {
				if ( WC_Subscriptions_Product::get_trial_length( $cart_item['data'] ) > 0 ) {
					$cart_contains_free_trial = true;
					break;
				}
			}
		}

		return $cart_contains_free_trial;
	}

/*function wscsd_add_cart_first_renewal_payment_date( $order_total_html, $cart ) {

	if ( 0 !== $cart->next_payment_date ) {
		$first_renewal_date = date_i18n( wc_date_format(), wcs_date_to_time( get_date_from_gmt( $cart->next_payment_date ) ) );
		// translators: placeholder is a date
		//$order_total_html  .= '<div class="first-payment-date"><small>' . sprintf( __( 'First payment: %s', 'woocommerce-subscriptions' ), $first_renewal_date ) . '</small></div>';
	}

	return $order_total_html;
}
add_filter( 'wcs_cart_totals_order_total_html', 'wscsd_add_cart_first_renewal_payment_date', 4, 2 );


//apply_filters( 'wcs_cart_totals_order_total_html', 'wscsd_change_first_renewal_text', 1000, 2 );

function wscsd_change_first_renewal_text ( $order_total_html, $cart ){
	$order_total_html_2 = preg_replace( 'renewal', 'payment', $order_total_html );

	return $order_total_html_2;

}

add_filter( 'wcs_recurring_cart_next_payment_date', 'wscsd_change_next_date_2', 1000, 3 );

function wscsd_change_next_date_2( $date, $recurring_cart, $product ){
	$customer_selected_start_date = '';
	foreach ( $recurring_cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( ( ''== $customer_selected_start_date ) && ( array_key_exists( 'wscsd_start_date', $cart_item ) ) && ( !empty( $date ) ) ) {
			$date = gmdate( 'Y-m-d H:i:s', strtotime( $cart_item['wscsd_start_date'] ) );
		}
	}
			
	return $date ;

}*/
