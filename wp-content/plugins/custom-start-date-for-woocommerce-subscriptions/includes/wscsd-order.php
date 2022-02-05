<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'woocommerce_checkout_create_subscription', 'wscsd_store_start_date', 10, 4 );

function wscsd_store_start_date( $subscription, $posted_data, $order, $recurring_cart ) {
   
	$customer_selected_start_date = '';
	foreach ( $recurring_cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( ( ''== $customer_selected_start_date ) && ( array_key_exists( 'wscsd_start_date', $cart_item ) ) ) {
			$customer_selected_start_date = $cart_item['wscsd_start_date'];
			$date = gmdate( 'Y-m-d H:i:s', strtotime ( $customer_selected_start_date ) );
			break; 
		}
	}
	if ( !empty ( $date ) ) {

		add_post_meta( $subscription->get_id (), '_wscsd_delayed_start_date', $date );
	}
		

}

add_action( 'subscriptions_activated_for_order', 'wscsd_update_start_date', 10, 1 );
//Update the start date of the subscription
function wscsd_update_start_date( $order_id ) {
	 $contains_subscription = wcs_order_contains_subscription( $order_id );

	if ( !$order_id || !$contains_subscription ) {
		return;
	}
	$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
	// We get all related subscriptions for this order
	foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
		//if( $subscription_obj->order->id == $order_id ) break; // Stop the loop
		if ( !empty ( get_metadata ( 'post', $subscription_id , '_wscsd_delayed_start_date', true ) ) ) {
			$start_date = get_metadata ( 'post', $subscription_id , '_wscsd_delayed_start_date', true );
			update_post_meta( $subscription_id, '_schedule_start', $start_date );
			do_action ( 'wscsd_start_date_changed', $subscription_id, $start_date );
			//if expires before next payment date
			$next_payment_date = $subscription_obj->get_date( 'next_payment' );
			$end = $subscription_obj->get_date( 'end' );
			if ( ( $next_payment_date < $start_date ) || ( ( $next_payment_date > $end ) && ( 0 != $end ) ) ) {
				$subscription_obj->delete_date( 'next_payment' );
			}
				
		}
	}
	
}


add_filter( 'woocommerce_subscriptions_thank_you_message', 'wscsd_thank_you_message', 10, 2 );

function wscsd_thank_you_message ( $thank_you_message, $order_id ) {

	if ( wcs_order_contains_subscription( $order_id, 'any' ) ) {
		$scheduled_order = 0;

		//Item count in order
		$order = wc_get_order( $order_id );
		$total_quantity = $order->get_item_count();

		//Check Subscriptions
		$subscriptions                = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
		$subscription_count           = count( $subscriptions );
		$new_message				  = '';
		$my_account_subscriptions_url = get_permalink( wc_get_page_id( 'myaccount' ) );
		$check_start_date = '';

		if ( $total_quantity == $subscription_count ) {
			$scheduled_order = 1;
		}
		
		if ( $subscription_count ) {
			foreach ( $subscriptions as $subscription ) {
				if ( $subscription->has_status( 'scheduled' ) ) {
					$start_date = get_metadata ( 'post', $subscription->get_id(), '_wscsd_delayed_start_date', true );
					 $formated_start_date = date_i18n( wc_date_format(), strtotime( $start_date ) );
					 // translators: placeholders are opening and closing link tags
					 $new_message = '<p>' . sprintf( _n( 'Your subscription will be activated the %s ', 'Your subscriptions will be activated the', $subscription_count, 'woocommerce-subscriptions' ) . $formated_start_date, '</a>' ) . '</p>';

					/*Make sure tall the subcriptions start on the same day
					if ( ( '' != $check_start_date ) && ( $check_start_date != $start_date ) ) {
						$scheduled_order = 0;
					}
					$check_start_date = $start_date;*/

					//Pick up earliest start date
					if ( ( '' == $check_start_date ) || ( $start_date < $check_start_date ) ) {
						$check_start_date = $start_date;
					}
				} else {
					$scheduled_order = 0;
				}
			}
		}
		if ( ! empty( $new_message ) ) {
			 // translators: placeholders are opening and closing link tags
			$new_message .= '<p>' . sprintf( _n( 'View the status of your subscription in %1$syour account%2$s.', 'View the status of your subscriptions in %1$syour account%2$s.', $subscription_count, 'woocommerce-subscriptions' ), '<a href="' . $my_account_subscriptions_url . '">', '</a>' ) . '</p>';
			$thank_you_message = $new_message;
		}

		if ( ( 1 == $scheduled_order ) && ( $check_start_date > gmdate( 'Y-m-d H:i:s' ) ) ) {
			if ( $order->get_status() == 'processing' ) {
				$order->update_status( 'scheduled' );
				do_action ( 'wscsd_start_date_order', $order_id, $check_start_date );
			}
		}
		   
	}
	return $thank_you_message;
}



add_action( 'woocommerce_email_after_order_table', 'wscsd_add_sub_info_email', 5, 3 );

/**
 * Adds the subscription information to our order emails.
 *
 * @since 1.5
 */
function wscsd_add_sub_info_email( $order, $is_admin_email, $plaintext = false ) {
	remove_action( 'woocommerce_email_after_order_table', array( 'WC_Subscriptions_Order', 'add_sub_info_email' ), 15, 3 );

	$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) );

	if ( ! empty( $subscriptions ) ) {

		$template_base  = plugin_dir_path( __DIR__ ) . 'templates/';
		$template = ( $plaintext ) ? 'emails/plain/subscription-info.php' : 'emails/subscription-info.php';

		wc_get_template(
			$template,
			array(
				'order'          => $order,
				'subscriptions'  => $subscriptions,
				'is_admin_email' => $is_admin_email,
			),
			'',
			$template_base
		);
	}
}
