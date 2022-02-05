<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( class_exists( 'WCS_Action_Scheduler' ) ) {
	class WSCSD_Action_Scheduler extends WCS_Action_Scheduler {

		public static function wscsd_schedule_start_date( $subscription_id, $start_date ) {
			as_schedule_single_action( $start_date, 'wscsd_scheduled_start', array( 'subscription_id' => $subscription_id ) );
		}
		public static function wscsd_schedule_active_status( $subscription_id ) {
			$subscription = wcs_get_subscription( $subscription_id );
			$subscription->set_status( 'wc-active' );
			wcs_make_user_active( $subscription->get_user_id() );
			$subscription->save();
		}

		public static function wscsd_schedule_order( $order_id, $start_date ) {
			as_schedule_single_action( $start_date, 'wscsd_scheduled_order', array( 'order_id' => $order_id ) );
		}
		public static function wscsd_schedule_processing_status( $order_id ) {
			$order = wc_get_order( $order_id );
			$order->set_status( 'wc-processing' );
			wcs_make_user_active( $order->get_user_id() );
			$order->save();
		}
	}

	add_action( 'wscsd_start_date_changed', array ( 'WSCSD_Action_Scheduler','wscsd_schedule_start_date' ), 10, 2 );
	add_action ('wscsd_scheduled_start', array ( 'WSCSD_Action_Scheduler', 'wscsd_schedule_active_status'), 10, 1 );
	add_action( 'wscsd_start_date_order', array ( 'WSCSD_Action_Scheduler','wscsd_schedule_order' ), 10, 2 );
	add_action ('wscsd_scheduled_order', array ( 'WSCSD_Action_Scheduler', 'wscsd_schedule_processing_status'), 10, 1 );
}
