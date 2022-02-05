<?php
/**
 * Subscription information template
 *
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 3.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( empty( $subscriptions ) ) {
	return;
}

$has_automatic_renewal = false;
$is_parent_order       = wcs_order_contains_subscription( $order, 'parent' );

echo "\n\n" . esc_html__( 'Subscription information', 'woocommerce-subscriptions' ) . "\n\n";
foreach ( $subscriptions as $subscription ) {
	$has_automatic_renewal = $has_automatic_renewal || ! $subscription->is_manual();
	$subscription_id = $subscription->get_id ();
	if ( !empty ( get_metadata ( 'post', $subscription_id , '_wscsd_delayed_start_date', true ) ) ) {
		$start_date = wcs_date_to_time( get_metadata ( 'post', $subscription_id , '_wscsd_delayed_start_date', true ) );
	} else { 
		$start_date = $subscription->get_time( 'start_date', 'site' );
	}

	// translators: placeholder is subscription's number
	echo sprintf( esc_html_x( 'Subscription: %s', 'in plain emails for subscription information', 'woocommerce-subscriptions' ), esc_attr( $subscription->get_order_number() ) ) . "\n";
	// translators: placeholder is either view or edit url for the subscription
	echo sprintf( esc_html_x( 'View subscription: %s', 'in plain emails for subscription information', 'woocommerce-subscriptions' ), esc_attr( $is_admin_email ) ? wcs_get_edit_post_link( $subscription->get_id() ) : esc_url( $subscription->get_view_order_url() ) ) . "\n";
	// translators: placeholder is localised start date
	echo sprintf( esc_html_x( 'Start date: %s', 'in plain emails for subscription information', 'woocommerce-subscriptions' ), esc_html( date_i18n( wc_date_format(), esc_html( $start_date ) ) ) ) . "\n";

	$end_date = ( 0 < $subscription->get_time( 'end' ) ) ? esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) ) : esc_html_x( 'When Cancelled', 'Used as end date for an indefinite subscription', 'woocommerce-subscriptions' );
	// translators: placeholder is localised end date, or "when cancelled"
	echo sprintf( esc_html_x( 'End date: %s', 'in plain emails for subscription information', 'woocommerce-subscriptions' ), esc_html( $end_date)  ) . "\n";
	// translators: placeholder is the formatted order total for the subscription
	echo sprintf( esc_html_x( 'Recurring price: %s', 'in plain emails for subscription information', 'woocommerce-subscriptions' ), esc_html( $subscription->get_formatted_order_total() ) );

	if ( $is_parent_order && $subscription->get_time( 'next_payment' ) > 0 ) {
		// translators: placeholder is the formatted date for the next payment
		echo "\n" . sprintf( esc_html__( 'Next payment: %s', 'woocommerce-subscriptions' ), esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'next_payment', 'site' ) ) ) );
	}

	echo "\n\n";
}
if ( $has_automatic_renewal && ! $is_admin_email && $subscription->get_time( 'next_payment' ) > 0 ) {
	if ( count( $subscriptions ) === 1 ) {
		$subscription   = reset( $subscriptions );
		$my_account_url = $subscription->get_view_order_url();
	} else {
		$my_account_url = wc_get_endpoint_url( 'subscriptions', '', wc_get_page_permalink( 'myaccount' ) );
	}

	echo wp_kses_post( sprintf(esc_html_n(
		// Translators: Placeholder is the My Account URL.
		'This subscription is set to renew automatically using your payment method on file. You can manage or cancel this subscription from your my account page. %s',
		'These subscriptions are set to renew automatically using your payment method on file. You can manage or cancel your subscriptions from your my account page. %s',
		count( $subscriptions ),
		'woocommerce-subscriptions'
	), $my_account_url ) );
}
