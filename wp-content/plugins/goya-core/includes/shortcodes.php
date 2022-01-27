<?php 

/* 
Display current year:
[current_year]
*/
if (!function_exists('goya_current_year_shortcode')) {
	function goya_get_current_year_shortcode() {
		$year = date_i18n ('Y');
		return $year;
	}

	add_shortcode ('current_year', 'goya_get_current_year_shortcode');
}


/* 
Progress bar shortcode amount left:
[missing_amount]
*/

function goya_progress_bar_amount_left() {
	$threshold = get_theme_mod('progress_bar_goal', 0);

	$subtotal = WC()->cart->get_subtotal();
	$tax = WC()->cart->get_subtotal_tax();
	$current = $subtotal + $tax;

	$amount_left = wc_price( $threshold - $current, array('decimals' => get_option('woocommerce_price_num_decimals')));
	
	return $amount_left;
}

add_shortcode ('missing_amount', 'goya_progress_bar_amount_left');