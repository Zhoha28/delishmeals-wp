<?php

class Scheduled_Woocommerce_Status_For_Subscription {

	/**
	 * Initialize Hooks.
	 *
	 */
	public function run() {
		// a woocommerce function to register new woocommerce status
		add_action( 'init', array( $this, 'register_scheduled_order_statuses' ), 100 );

		/**
		 * Following hooks are from woocommerce. You can find its implementation for on-hold status
		 * in file `woocommerce-subscriptions/includes/class-wc-subscriptions-manager.php`
		 */
		add_filter( 'wc_order_statuses', array( $this, 'scheduled_wc_order_statuses' ), 100, 1 );
		add_action( 'woocommerce_order_status_scheduled', array( $this, 'put_subscription_on_scheduled_for_order' ), 100 );
	}

	/**
	 * Registered new woocommerce status for `Scheduled`.
	 *
	 *
	 */
	public function register_scheduled_order_statuses() {
		register_post_status( 'wc-scheduled', array( 
			'label' => _x( 'Scheduled', 'Order status', 'wscsd' ), 
			'public' => true, 
			'exclude_from_search' => false, 
			'show_in_admin_all_list' => true, 
			'show_in_admin_status_list' => true, 
			// translators: count
			'label_count' => _n_noop( 'Scheduled <span class="count">( %s )</span>', 'Scheduled<span class="count">( %s )</span>', 'wscsd' ), 
		 ) );
	}

	/** 
	 * Add new status `Scheduled` to $order_statuses array.
	 * 
	 * @param array $order_statuses current order statuses array.
	 * @return array $order_statuses with the new status added to it.
	 */
	public function scheduled_wc_order_statuses( $order_statuses ) {
		$order_statuses['wc-scheduled'] = _x( 'Scheduled', 'Order status', 'wscsd' );
		return $order_statuses;
	}

	/** 
	 * Change status of all the subscription in an order to `Scheduled` when order status is changed to `Scheduled`.
	 * 
	 * @param object $order woocommerce order.
	 */
	 
	public function put_subscription_on_scheduled_for_order( $order ) {
		$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'parent' ) );

		if ( !empty( $subscriptions ) ) {
			foreach ( $subscriptions as $subscription ) {
				try {
					if ( !$subscription->has_status( wcs_get_subscription_ended_statuses() ) ) {
						$subscription->update_status( 'scheduled' );
					}
				} catch ( Exception $e ) {
					// translators: $1: order number, $2: error message
					$subscription->add_order_note( sprintf( __( 'Failed to update subscription status after order #%1$s was put to scheduled: %2$s', 'wscsd' ), is_object( $order ) ? $order->get_order_number() : $order, $e->getMessage() ) );
				}
			}

			// Added a new action the same way subscription plugin has added for on-hold
			do_action( 'subscriptions_put_to_scheduled_for_order', $order );
		}
	}
}
