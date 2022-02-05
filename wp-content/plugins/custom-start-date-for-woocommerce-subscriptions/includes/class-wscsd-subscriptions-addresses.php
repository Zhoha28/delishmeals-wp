<?php
if ( class_exists( 'WC_Subscriptions_Addresses' ) ) {
	class WSCSD_Subscriptions_Addresses extends WC_Subscriptions_Addresses {
		/**
		 * When a subscriber's billing or shipping address is successfully updated, check if the subscriber
		 * has also requested to update the addresses on existing subscriptions and if so, go ahead and update
		 * the addresses on the initial order for each subscription.
		 *
		 * @param int $user_id The ID of a user who own's the subscription (and address)
		 * @since 1.3
		 */
		public static function maybe_update_subscription_addresses( $user_id, $address_type ) {

			if ( ! wcs_user_has_subscription( $user_id ) || wc_notice_count( 'error' ) > 0 || empty( $_POST['_wcsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wcsnonce'], 'wcs_edit_address' ) ) ) {
				return;
			}

			$address_type   = ( 'billing' == $address_type || 'shipping' == $address_type ) ? $address_type : '';
			$address_fields = '';
			if ( isset( $_POST[ $address_type . '_country' ] ) ) {
				$address_fields = ( null !== WC()->countries->get_address_fields( sanitize_text_field( $_POST[ $address_type . '_country' ], $address_type . '_' ) ) ) ? WC()->countries->get_address_fields( sanitize_text_field( $_POST[ $address_type . '_country' ] ), $address_type . '_' ) : '';
			}
			
			$address        = array();

			foreach ( $address_fields as $key => $field ) {
				if ( isset( $_POST[ $key ] ) ) {
					$address[ str_replace( $address_type . '_', '', $key ) ] = wc_clean( $_POST[ $key ] );
				}
			}

			if ( isset( $_POST['update_all_subscriptions_addresses'] ) ) {

				$users_subscriptions = wcs_get_users_subscriptions( $user_id );

				foreach ( $users_subscriptions as $subscription ) {
					if ( $subscription->has_status( 'scheduled' ) ) {
						$subscription->set_address( $address, $address_type );
					}
				}
			} elseif ( isset( $_POST['update_subscription_address'] ) ) {

				$subscription = wcs_get_subscription( intval( $_POST['update_subscription_address'] ) );

				// Update the address only if the user actually owns the subscription
				if ( ! empty( $subscription ) ) {
					$subscription->set_address( $address, $address_type );
				}

				wp_safe_redirect( $subscription->get_view_order_url() );
				exit();
			}
		}

	}
	add_action( 'woocommerce_customer_save_address', array ( 'WSCSD_Subscriptions_Addresses', 'maybe_update_subscription_addresses' ), 10, 2 );
}
