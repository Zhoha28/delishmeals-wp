<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_subscription_date_to_display', 'wscsd_next_payment_date_display', 100, 3 );

function wscsd_next_payment_date_display ( $date_to_display, $date_type, $subscription ) {
	if ( $subscription->has_status( 'scheduled' ) && ( 'next_payment' == $date_type ) ) {
		$timestamp_gmt = $subscription->get_time( $date_type, 'site' );
		if ( $timestamp_gmt > 0 ) {

			$time_diff = $timestamp_gmt - time();

			if ( $time_diff > 0 && $time_diff < WEEK_IN_SECONDS ) {
				// translators: placeholder is human time diff (e.g. "3 weeks")
				$date_to_display = sprintf( __( 'In %s', 'woocommerce-subscriptions' ), human_time_diff( time(), $timestamp_gmt ) );
			} elseif ( $time_diff < 0 && absint( $time_diff ) < WEEK_IN_SECONDS ) {
				// translators: placeholder is human time diff (e.g. "3 weeks")
				$date_to_display = sprintf( __( '%s ago', 'woocommerce-subscriptions' ), human_time_diff( time(), $timestamp_gmt ) );
			} else {
				$date_to_display = date_i18n( wc_date_format(), $subscription->get_time( $date_type, 'site' ) );
			}
		}
	}
	return $date_to_display;
}

function wscsd_woocommerce_remove_reactivate_button( $actions, $subscription_ID ) {
	$subscription = wcs_get_subscription( $subscription_ID );
	if ( $subscription->has_status( 'scheduled' ) ) {
		foreach ( $actions as $action_key => $action ) {
			switch ( $action_key ) {
				case 'reactivate': 
					unset( $actions[ $action_key ] );
					break;  
				default: 
					error_log( '-- $action = ' . print_r( $action, true ) );
					break;
			}
		}    
	}
	return $actions;
}

add_filter( 'wcs_view_subscription_actions', 'wscsd_woocommerce_remove_reactivate_button', 100, 2 );
