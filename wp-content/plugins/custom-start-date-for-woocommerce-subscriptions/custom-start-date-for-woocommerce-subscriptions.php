<?php
/*
Plugin Name: Custom Start Date For WooCommerce Subscriptions
Plugin URI: https://launchandsell.com/plugins/custom-start-date-woocommerce-subscriptions
Description: Delay the start of your subscription by setting up a custom start date
Version: 1.1.9
Author: Launch & Sell
Author URI: https://launchandsell.com

Woo: 7747886:b1f048903d4638f255192b34ab6a7047
WC requires at least: 4.6
WC tested up to: 5.6
*/

defined( 'ABSPATH' ) || die();
define( 'WSCSD_URL', plugin_dir_URL( __FILE__ ) );

function wscsd_check_woocommerce_activated() {
	if ( class_exists( 'woocommerce' ) ) {
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-admin.php';
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-product.php';
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-add-to-cart.php';
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-cart.php';
		require plugin_dir_path( __FILE__ ) . 'includes/class-wscsd-start-date-scheduler.php';
		require plugin_dir_path( __FILE__ ) . 'includes/class-wscsd-subscriptions-addresses.php';
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-order.php';
		require plugin_dir_path( __FILE__ ) . 'includes/wscsd-account.php';


		require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-subscriptions-scheduled-status.php';
$CWSS = new Scheduled_Woocommerce_Subscription_Status();
$CWSS->run(); // initiate the status hooks from woocommerce subscription

require plugin_dir_path( __FILE__ ) . 'includes/class-scheduled-status-for-subscription.php';
$CWSFS = new Scheduled_Woocommerce_Status_For_Subscription();
$CWSFS->run(); // initiate the status hooks from woocommerce

		wp_enqueue_script( 'wscsd_script', WSCSD_URL . 'js/js.js', array( 'jquery' ), '1.0.0', 100 );
	}
}
add_action( 'init', 'wscsd_check_woocommerce_activated' );

function wscsd_load_plugin_css() {
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'style', $plugin_url . 'css/style.css', array(), '1.0.0');
}
add_action( 'wp_enqueue_scripts', 'wscsd_load_plugin_css' );


function wscsd_load_admin_style() {
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'wscsd-admin-style', $plugin_url . 'css/admin-style.css', array(), '1.0.0');
	wp_enqueue_style( 'subscription-status-style', $plugin_url . 'css/subscription-status.css', array(), '1.0.0');
}
add_action( 'admin_enqueue_scripts', 'wscsd_load_admin_style' );
