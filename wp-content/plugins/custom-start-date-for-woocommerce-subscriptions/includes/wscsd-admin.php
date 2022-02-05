<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'woocommerce_product_options_general_product_data', 'wscsd_woocommerce_subscription_delay' );
//Display
add_action( 'woocommerce_process_product_meta', 'wscsd_woocommerce_subscription_delay_save' );
//Save
function wscsd_woocommerce_subscription_delay() {
	global $woocommerce, $post;
	$periods = ['days', 'weeks', 'months', 'years'];
	$wscsd_cut_off = get_post_meta( $post->ID, '_wscsd_cut_off', true ) ? get_post_meta( $post->ID, '_wscsd_cut_off', true ) : 0;
	$wscsd_cut_off_period = get_post_meta( $post->ID, '_wscsd_cut_off_period', true ) ? get_post_meta( $post->ID, '_wscsd_cut_off_period', true ) : 'day';
	$wscsd_max_dates = get_post_meta( $post->ID, '_wscsd_max_dates', true ) ? get_post_meta( $post->ID, '_wscsd_max_dates', true ) : '';
	$wscsd_delay_type = get_post_meta( $post->ID, '_wscsd_delay_type', true ) ? get_post_meta( $post->ID, '_wscsd_delay_type', true ) : '';
	$wscsd_hide_date = get_post_meta( $post->ID, '_wscsd_hide_date', true ) ? get_post_meta( $post->ID, '_wscsd_hide_date', true ) : 'yes';

	echo '<div class="options_group wscsd_custom_star_date show_if_subscription">';
	woocommerce_wp_select( 
		array( 
			'id' => '_wscsd_delay_type', 
			'name' => '_wscsd_delay_type', 
			'placeholder' => '', 
			'label' => __( 'Delay Subscription Start', 'wscsd' ), 
			'desc_tip'  => false, 
			'options' => array( 
				'' => __( 'No delay', 'wscsd' ), 
				'fixed' => __( 'Fixed start dates', 'wscsd' ), 
				//'recurring' => __( 'Recurring start dates', 'wscsd' ), 
				'delay' => __( 'Fixed delay periods', 'wscsd' ), 
				'calendar' => __( 'Let the customer pick any dates', 'wscsd' ), 
			 ), 
		)
	);
	
	//Display dates field
	echo '<div class="wscsd_delay_additional_options wscsd_hide_date" style="display:' . ( ( '' != $wscsd_delay_type ) && ( 'calendar' != $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';
	$checked = get_post_meta( $post->ID, '_wscsd_hide_date', true) ? get_post_meta( $post->ID, '_wscsd_hide_date', true) : 1;
		woocommerce_wp_checkbox ( 
			array( 
				'id' => '_wscsd_hide_date', 
				'placeholder' => '', 
				'label' => __( 'Hide start date option( s )', 'wscsd' ), 
				'desc_tip'  => true, 
				'description'=> __( 'Hide the start dates above the purchase button', 'wscsd' ), 

			), $checked
		);
	//Max nb of dates displayed
	echo '<div class="wscsd_delay_additional_options wscsd_max_dates" style="display:' . ( ( 'yes' == $wscsd_hide_date ) && ( 'fixed' == $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';  

	echo '<p class="form-field">
				<label for="_wscsd_max_dates">' . esc_html__( 'Max nb of dates to display', 'wscsd' ) . '</label>
				<input type="number" name="_wscsd_max_dates" id="_wscsd_max_dates" value="' . esc_html( $wscsd_max_dates ) . '" style="width: 24%; margin-right: 2%;"/> 
				<span>Leave empty or set to 0 to display all the dates</span>  
			</p>';
	
	echo '</div>'; 
			
	echo '</div>';
		//Label
	echo '<div class="wscsd_hide_date_options wscsd_label" style="display:' . ( ( ( '' != $wscsd_delay_type ) ) && ( ( 'yes' == $wscsd_hide_date ) || ( 'calendar' == $wscsd_delay_type ) )? 'block' : 'none' ) . ';">';
	woocommerce_wp_text_input( 
		array( 
			'id' => '_wscsd_start_label', 
			'placeholder' => '', 
			'label' => __( 'Start date label', 'wscsd' ), 
			'desc_tip'  => true, 
			'description'=> __( 'Label to be place before the start date options on the product page', 'wscsd' ), 
		)
	);
	echo '</div>';
	

	//Fixed Dates
	echo '<div class="wscsd_delay_additional_options wscsd_fixed" style="display:' . ( 'fixed' == $wscsd_delay_type ? 'block' : 'none' ) . ';">';
	woocommerce_wp_text_input( 
		array( 
			'id' => '_wscsd_fixed_start_dates_field', 
			'placeholder' => __( 'Add a start date ( + )', 'wscsd' ), 
			'label' => __( 'Start Date( s )', 'wscsd' ), 
			'description'=> '<span id="_wscsd_fixed_start_dates_add" class="wscsd_add_dates">' . __( 'Add start date ( + )', 'wscsd' ) . '</span>', 
			'desc_tip'  => false, 
			'type' => 'date', 
		)
	 );
	echo '<div id="_wscsd_fixed_start_dates_display" class="wscsd_dates_display">';
	$dates = get_post_meta( $post->ID, '_wscsd_fixed_start_dates', true );
	$datelist = '';
	$prefix = '|';
	$wscd_cut_date = gmdate( 'Y-m-d', strtotime( ' +' . $wscsd_cut_off . ' ' . $wscsd_cut_off_period ) );
	if ( !empty( $dates ) ) {
		foreach ( $dates as $date ) {
			if ( ( !empty( $date ) ) && ( $date >= $wscd_cut_date ) ) {
				$datelist .= $date . $prefix;
			echo "<span class='wscsd_fixed_start_date'><span class='wscsd_fixed_start_date_data'>" . esc_html( $date ) . "</span><span class='wscsd_fixed_start_date_delete'> X </span></span>";
			}			  
		}
	}
	echo '</div>';
	woocommerce_wp_hidden_input( 
		array( 
			'id' => '_wscsd_fixed_start_dates_save', 
			'class' => 'wscsd_fixed_start_dates_save',
			'desc_tip'  => false, 
			'type' => 'text', 
			'value'     => $datelist, 
		)
	 );
	echo '<span class="wscsd_fixed_start_dates_deleteall"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16" ><g fill="#444"><path d="M2 6v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V6H2z"/><path data-color="color-2" d="M12 3V1c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v2H0v2h16V3h-4zm-2 0H6V2h4v1z"/></g></svg> Delete all date</span>';

		

	echo '</div>';

	//Fixed Delays
	echo '<div class="wscsd_delay_additional_options wscsd_delay" style="display:' . ( 'delay' == $wscsd_delay_type ? 'block' : 'none' ) . ';">';

	$wscsd_fixed_delay = get_post_meta( $post->ID, '_wscsd_fixed_delay', true );
	$wscsd_fixed_delay_period = get_post_meta( $post->ID, '_wscsd_fixed_delay_period', true );
	echo '<p class="form-field">
	<label for="_wscsd_fixed_delay_field">' . esc_html__( 'Fixed delay( s )', 'wscsd' ) . '</label>
	<input type="number" name="_wscsd_fixed_delay_field" id="_wscsd_fixed_delay_field" value="" style="width: 24%; margin-right: 2%;"/>
	<select name="_wscsd_fixed_delay_period" id="_wscsd_fixed_delay_period" style="width: 24%;">';
	foreach ( $periods as $period ) {
		echo '<option value="' . esc_html( $period ) . '" >' . esc_html( $period ) . '</option>';
	}
	echo '</select>
	<span class="description"><span id="_wscsd_fixed_delays_add" class="wscsd_add_dates">' . esc_html__( 'Add delay ( + )', 'wscsd' ) . '</span></span>   
	</p>';

	echo '<div id="_wscsd_fixed_delays_display" class="wscsd_delay_display">';
	$delays = get_post_meta( $post->ID, '_wscsd_fixed_delays', true );

	$delaylist = '';
	$prefix = '|';
	if ( !empty( $delays ) ) {
		foreach ( $delays as $delay ) {
			if ( !empty( $delay ) ) {
				$delaylist .= $delay . $prefix;
			echo "<span class='wscsd_fixed_delay'><span class='wscsd_fixed_delay_data'>" . esc_html( $delay ) . "</span><span class='wscsd_fixed_delay_delete'> X </span></span>";
			}
		}
	}
		
	echo '</div>';
	woocommerce_wp_hidden_input( 
		array( 
			'id' => '_wscsd_fixed_delays_save', 
			'class' => 'wscsd_fixed_delays_save',
			'desc_tip'  => false, 
			'type' => 'text', 
			'value'     => $delaylist, 
		)
	);
	echo '<span class="wscsd_fixed_delays_deleteall"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16" ><g fill="#444"><path d="M2 6v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V6H2z"/><path data-color="color-2" d="M12 3V1c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v2H0v2h16V3h-4zm-2 0H6V2h4v1z"/></g></svg> Delete all delays</span>';

	echo '</div>';

	//Cut off date
	echo '<div class="wscsd_delay_additional_options wscsd_cut_off" style="display:' . ( ( '' != $wscsd_delay_type ) && ( 'delay' != $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';  

	echo '<p class="form-field">
	<label for="_wscsd_cut_off">' . esc_html__( 'Cut off time', 'wscsd' ) . '</label>
	<input type="number" name="_wscsd_cut_off" id="_wscsd_cut_off" value="' . esc_html( $wscsd_cut_off ) . '" style="width: 24%; margin-right: 2%;"/>
	<select name="_wscsd_cut_off_period" style="width: 24%;">';
	foreach ( $periods as $period ) {
		echo '<option value="' . esc_html( $period ) . '" ' . ( ( $wscsd_cut_off_period == $period ) ? ' selected' : '' ) . '>' . esc_html( $period ) . '</option>';
	}
	echo '</select>
	<span class="description">before the start date</span>    
	</p>';

	echo '</div>';
		
	

	wp_nonce_field( 'wcs_custom_start_meta', '_wscsd_nonce' );
	echo '</div>';  
}


function wscsd_woocommerce_subscription_delay_save( $post_id ) {
	if ( ! isset( $_POST['_wscsd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['_wscsd_nonce'] ), 'wcs_custom_start_meta' ) ) {
		return;
	}
	// Delay Type
	update_post_meta( $post_id, '_wscsd_delay_type', isset( $_POST['_wscsd_delay_type'] ) ? sanitize_text_field( $_POST['_wscsd_delay_type'] ) : '' );
	// Display Date
	update_post_meta( $post_id, '_wscsd_hide_date', isset( $_POST['_wscsd_hide_date'] ) ? sanitize_text_field( $_POST['_wscsd_hide_date'] ) : '' );
	// Start Label
	update_post_meta( $post_id, '_wscsd_start_label', isset( $_POST['_wscsd_start_label'] ) ? sanitize_text_field( $_POST['_wscsd_start_label'] ) : '' );
	// Max dates displayed
	update_post_meta( $post_id, '_wscsd_max_dates', isset( $_POST['_wscsd_max_dates'] ) ? sanitize_text_field( $_POST['_wscsd_max_dates'] ) : '' );
	//Cut-off time
	update_post_meta( $post_id, '_wscsd_cut_off', isset( $_POST['_wscsd_cut_off'] ) ? sanitize_text_field( $_POST['_wscsd_cut_off'] ) : '' );
	update_post_meta( $post_id, '_wscsd_cut_off_period', isset( $_POST['_wscsd_cut_off_period'] ) ? sanitize_text_field( $_POST['_wscsd_cut_off_period'] ) : '' );
	//Fixed dates
	$wscsd_fixed_start_dates_save = isset( $_POST['_wscsd_fixed_start_dates_save'] ) ? substr( ( sanitize_text_field( $_POST['_wscsd_fixed_start_dates_save'] ) ), 0, -1 ) : array();
	$wscsd_fixed_start_dates = array_map( 'trim', explode( '|', $wscsd_fixed_start_dates_save ) );
	sort( $wscsd_fixed_start_dates );
	update_post_meta( $post_id, '_wscsd_fixed_start_dates', $wscsd_fixed_start_dates ) ;
	//Fixed delays
	$wscsd_fixed_delays_save = isset( $_POST['_wscsd_fixed_delays_save'] ) ? substr( ( sanitize_text_field( $_POST['_wscsd_fixed_delays_save'] ) ), 0, -1 ) : array();
	$wscsd_fixed_delays = array_map( 'trim', explode( '|', $wscsd_fixed_delays_save ) );
	usort( $wscsd_fixed_delays, 'wscsd_date_compare' );
	update_post_meta( $post_id, '_wscsd_fixed_delays', $wscsd_fixed_delays ) ;
}

function wscsd_date_compare( $a, $b ) {
	$t1 = strtotime( $a );
	$t2 = strtotime( $b );
	return $t1 - $t2;
}  


/* 
* VARIATION
*/

// Add a custom field to variation settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
function variation_settings_fields( $loop, $variation_data, $variation ) {

	$wscsd_delay_type = get_post_meta( $variation->ID, '_wscsd_delay_type', true ) ? get_post_meta( $variation->ID, '_wscsd_delay_type', true ) : '';
	$wscsd_hide_date = get_post_meta( $variation->ID, '_wscsd_hide_date', true ) ? get_post_meta( $variation->ID, '_wscsd_hide_date', true ) : 'yes';
	$periods = ['days', 'weeks', 'months', 'years'];
	$wscsd_cut_off = get_post_meta( $variation->ID, '_wscsd_cut_off', true ) ? get_post_meta( $variation->ID, '_wscsd_cut_off', true ) : 0;
	$wscsd_cut_off_period = get_post_meta( $variation->ID, '_wscsd_cut_off_period', true ) ? get_post_meta( $variation->ID, '_wscsd_cut_off_period', true ) : 'day';
	$wscsd_max_dates = get_post_meta( $variation->ID, '_wscsd_max_dates', true ) ? get_post_meta( $variation->ID, '_wscsd_max_dates', true ) : '';

	echo '<div class="wscsd_custom_star_date show_if_subscription">';

	woocommerce_wp_select( 
		array( 
			'id' => '_wscsd_delay_type[' . $loop . ']', 
			'name' => '_wscsd_delay_type[' . $loop . ']', 
			'placeholder' => '', 
			'label' => __( 'Delay Subscription Start', 'wscsd' ),
			'desc_tip'  => false, 
			'options' => array( 
				'' => __( 'No delay', 'wscsd' ), 
				'fixed' => __( 'Fixed start dates', 'wscsd' ), 
				//'recurring' => __( 'Recurring start dates', 'wscsd' ), 
				'delay' => __( 'Fixed delay periods', 'wscsd' ), 
				'calendar' => __( 'Let the customer pick any dates', 'wscsd' ), 
			 ), 
			'value'       => get_post_meta( $variation->ID, '_wscsd_delay_type', true ),
		)
	);
	//Display dates field
	echo '<div id="_wscsd_hide_date_options[' . esc_html( $loop ) . ']" class="wscsd_delay_additional_options wscsd_hide_date" style="display:' . ( ( '' != $wscsd_delay_type ) && ( 'calendar' != $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';
	$checked = get_post_meta( $variation->ID, '_wscsd_hide_date', true) ? get_post_meta( $variation->ID, '_wscsd_hide_date', true) : 1;
		woocommerce_wp_checkbox ( 
			array( 
				'id' => '_wscsd_hide_date[' . $loop . ']', 
				'placeholder' => '', 
				'label' => __( 'Hide start date option( s )', 'wscsd' ), 
				'desc_tip'  => true, 
				'description'=> __( 'Hide the start dates above the purchase button', 'wscsd' ), 
				'value' => get_post_meta( $variation->ID, '_wscsd_hide_date', true),

			), $checked
		);
	//Max nb of dates displayed
	/*echo '<div id="_wscsd_max_dates_form[' . $loop . ']" class="wscsd_delay_additional_options wscsd_max_dates" style="display:' . ( ( 'yes' == $wscsd_hide_date ) && ( 'fixed' == $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';  

	echo '<p class="form-field">
				<label for="_wscsd_max_dates">' . esc_html__( 'Max nb of dates to display', 'wscsd' ) . '</label>
				<input type="number" name="_wscsd_max_dates" id="_wscsd_max_dates[' . $loop . ']" value="' . esc_html( $wscsd_max_dates ) . '" style="width: 24%; margin-right: 2%;"/> 
				<span>Leave empty or set to 0 to display all the dates</span>  
			</p>';
	
	echo '</div>'; */
			
	echo '</div>';

	//Label
	echo '<div id="_wscsd_start_date_label[' . esc_html( $loop ) . ']" class="wscsd_hide_date_options wscsd_label" style="display:' . ( ( ( '' != $wscsd_delay_type ) ) && ( ( 'no' == $wscsd_hide_date ) || ( 'calendar' == $wscsd_delay_type ) )? 'block' : 'none' ) . ';">';
	woocommerce_wp_text_input( 
		array( 
			'id' => '_wscsd_start_label[' . $loop . ']', 
			'placeholder' => '', 
			'label' => __( 'Start date label', 'wscsd' ), 
			'desc_tip'  => true, 
			'description'=> __( 'Label to be place before the start date options on the product page', 'wscsd' ), 
			'value'       => get_post_meta( $variation->ID, '_wscsd_start_label', true ),
		)
	);
	echo '</div>';
	

	//Fixed Dates
	echo '<div id="_wscsd_fixed[' . esc_html( $loop ) . ']" class="wscsd_delay_additional_options wscsd_fixed wscsd_variation" style="display:' . ( 'fixed' == $wscsd_delay_type ? 'block' : 'none' ) . ';">';
	woocommerce_wp_text_input( 
		array( 
			'id' => '_wscsd_fixed_start_dates_field[' . $loop . ']', 
			'placeholder' => __( 'Add a start date ( + )', 'wscsd' ), 
			'label' => __( 'Start Date( s )', 'wscsd' ), 
			'description'=> '<span id="_wscsd_fixed_start_dates_add[' . $loop . ']" class="wscsd_add_dates">' . __( 'Add start date ( + )', 'wscsd' ) . '</span>', 
			'desc_tip'  => false, 
			'type' => 'date', 
		)
	 );
	echo '<div id="_wscsd_fixed_start_dates_display[' . esc_html( $loop ) . ']" class="wscsd_dates_display">';
	$dates = get_post_meta( $variation->ID, '_wscsd_fixed_start_dates', true );
	$datelist = '';
	$prefix = '|';
	$wscd_cut_date = gmdate( 'Y-m-d', strtotime( ' +' . $wscsd_cut_off . ' ' . $wscsd_cut_off_period ) );
	if ( !empty( $dates ) ) {
		foreach ( $dates as $date ) {
			if ( ( !empty( $date ) ) && ( $date >= $wscd_cut_date ) ) {
				$datelist .= $date . $prefix;
			echo '<span class="wscsd_fixed_start_date"><span class="wscsd_fixed_start_date_data">' . esc_html( $date ) . '</span><span id="_wscsd_fixed_start_date_delete[' . esc_html( $loop ) . ']" class="wscsd_fixed_start_date_delete"> X </span></span>';
			}			  
		}
	}
	echo '</div>';
	woocommerce_wp_hidden_input( 
		array( 
			'id' => '_wscsd_fixed_start_dates_save[' . esc_html( $loop ) . ']', 
			'class' => 'wscsd_fixed_start_dates_save',
			'desc_tip'  => false, 
			'type' => 'text', 
			'value'     => $datelist, 
		)
	 );
	echo '<span id="_wscsd_fixed_start_dates_deleteall[' . esc_html( $loop ) . ']" class="wscsd_fixed_start_dates_deleteall"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16" ><g fill="#444"><path d="M2 6v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V6H2z"/><path data-color="color-2" d="M12 3V1c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v2H0v2h16V3h-4zm-2 0H6V2h4v1z"/></g></svg> Delete all date</span>';

	echo '</div>';

	//Fixed Delays
	echo '<div id="_wscsd_delay[' . esc_html( $loop ) . ']" class="wscsd_delay_additional_options wscsd_delay wscsd_variation" style="display:' . ( 'delay' == $wscsd_delay_type ? 'block' : 'none' ) . ';">';

	$wscsd_fixed_delay = get_post_meta( $variation->ID, '_wscsd_fixed_delay', true );
	$wscsd_fixed_delay_period = get_post_meta( $variation->ID, '_wscsd_fixed_delay_period', true );
	echo '<p class="form-field">
	<label for="_wscsd_fixed_delay_field">' . esc_html__( 'Fixed delay( s )', 'wscsd' ) . '</label>
	<input type="number" name="_wscsd_fixed_delay_field" id="_wscsd_fixed_delay_field" value="" style="width: 24%; margin-right: 2%;"/>
	<select name="_wscsd_fixed_delay_period" id="_wscsd_fixed_delay_period" style="width: 24%;">';
	foreach ( $periods as $period ) {
		echo '<option value="' . esc_html( $period ) . '" >' . esc_html( $period ) . '</option>';
	}
	echo '</select>
	<span class="description"><span id="_wscsd_fixed_delays_add" class="wscsd_add_dates">' . esc_html__( 'Add delay ( + )', 'wscsd' ) . '</span></span>   
	</p>';

	echo '<div id="_wscsd_fixed_delays_display" class="wscsd_delay_display">';
	$delays = get_post_meta( $variation->ID, '_wscsd_fixed_delays', true );

	$delaylist = '';
	$prefix = '|';
	if ( !empty( $delays ) ) {
		foreach ( $delays as $delay ) {
			if ( !empty( $delay ) ) {
				$delaylist .= $delay . $prefix;
			echo "<span class='wscsd_fixed_delay'><span class='wscsd_fixed_delay_data'>" . esc_html( $delay ) . "</span><span class='wscsd_fixed_delay_delete'> X </span></span>";
			}
		}
	}
		
	echo '</div>';
	woocommerce_wp_hidden_input( 
		array( 
			'id' => '_wscsd_fixed_delays_save[' . $loop . ']', 
			'class' => 'wscsd_fixed_delays_save',
			'desc_tip'  => false, 
			'type' => 'text', 
			'value'     => $delaylist, 
		)
	);
	echo '<span class="wscsd_fixed_delays_deleteall"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16" ><g fill="#444"><path d="M2 6v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V6H2z"/><path data-color="color-2" d="M12 3V1c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v2H0v2h16V3h-4zm-2 0H6V2h4v1z"/></g></svg> Delete all delays</span>';

	echo '</div>';

	//Cut off date
	echo '<div id="_wscsd_cut_off_field[' . esc_html( $loop ) . ']" class="wscsd_delay_additional_options wscsd_cut_off" style="display:' . ( ( '' != $wscsd_delay_type ) && ( 'delay' != $wscsd_delay_type ) ? 'block' : 'none' ) . ';">';  

	echo '<p class="form-field">
	<label for="_wscsd_cut_off">' . esc_html__( 'Cut off time', 'wscsd' ) . '</label>
	<input type="number" name="_wscsd_cut_off[' . esc_html( $loop ) . ']" id="_wscsd_cut_off[' . esc_html( $loop ) . ']" value="' . esc_html( $wscsd_cut_off ) . '" style="width: 24%; margin-right: 2%;"/>
	<select name="_wscsd_cut_off_period[' . esc_html( $loop ) . ']" id="_wscsd_cut_off_period[' . esc_html( $loop ) . ']" style="width: 24%;" value ="' . esc_html( $wscsd_cut_off_period ) . '">';
	foreach ( $periods as $period ) {
		echo '<option value="' . esc_html( $period ) . '" ' . ( ( $wscsd_cut_off_period == $period ) ? ' selected' : '' ) . '>' . esc_html( $period ) . '</option>';
	}
	echo '</select>
	<span class="description">before the start date</span>    
	</p>';

	echo '</div>';

	echo '</div>';

	wp_nonce_field( 'wcs_custom_start_meta', '_wscsd_nonce[' . $loop . ']' );
}

// Save custom field value from variation settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );
function save_variation_settings_fields( $variation_ID, $loop ) {

	if ( ! isset( $_POST['_wscsd_nonce'][$loop] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wscsd_nonce'][$loop] ), 'wcs_custom_start_meta' ) ) {
		return;
	}
	
	if ( isset($_POST['_wscsd_delay_type'][$loop] ) ) {
		update_post_meta( $variation_ID, '_wscsd_delay_type', sanitize_text_field($_POST['_wscsd_delay_type'][$loop]) );
	}

	// Display Date

	update_post_meta( $variation_ID, '_wscsd_hide_date', isset( $_POST['_wscsd_hide_date'][$loop] ) ? sanitize_key( $_POST['_wscsd_hide_date'][$loop] ) : '' );
	// Start Label
	update_post_meta( $variation_ID, '_wscsd_start_label', isset( $_POST['_wscsd_start_label'][$loop] ) ? sanitize_text_field( $_POST['_wscsd_start_label'][$loop] ) : '' );

	//Fixed dates
	$wscsd_fixed_start_dates_save = isset( $_POST['_wscsd_fixed_start_dates_save'][$loop] ) ? substr( ( sanitize_text_field( $_POST['_wscsd_fixed_start_dates_save'][$loop] ) ), 0, -1 ) : array();
	$wscsd_fixed_start_dates = array_map( 'trim', explode( '|', $wscsd_fixed_start_dates_save ) );
	sort( $wscsd_fixed_start_dates );
	update_post_meta( $variation_ID, '_wscsd_fixed_start_dates', $wscsd_fixed_start_dates ) ;


	//Fixed delays
	$wscsd_fixed_delays_save = isset( $_POST['_wscsd_fixed_delays_save'][$loop]  ) ? substr( ( sanitize_text_field( $_POST['_wscsd_fixed_delays_save'][$loop]  ) ), 0, -1 ) : array();
	$wscsd_fixed_delays = array_map( 'trim', explode( '|', $wscsd_fixed_delays_save ) );
	usort( $wscsd_fixed_delays, 'wscsd_date_compare' );
	update_post_meta( $variation_ID, '_wscsd_fixed_delays', $wscsd_fixed_delays ) ;
	 // Cut Off
	update_post_meta( $variation_ID, '_wscsd_cut_off', isset( $_POST['_wscsd_cut_off'][$loop] ) ? sanitize_text_field( $_POST['_wscsd_cut_off'][$loop] ) : '' );
	update_post_meta( $variation_ID, '_wscsd_cut_off_period', isset( $_POST['_wscsd_cut_off_period'][$loop] ) ? sanitize_text_field( $_POST['_wscsd_cut_off_period'][$loop] ) : '' );
	 // Max Dates
	/*update_post_meta( $variation_ID, '_wscsd_max_dates', isset( $_POST['_wscsd_max_dates'][$loop] ) ? sanitize_text_field( $_POST['_wscsd_max_dates'][$loop] ) : '' );*/
	

}

// Add variation custom field to single variable product form
add_filter( 'woocommerce_available_variation', 'add_variation_custom_field_to_variable_form', 10, 3 );
function add_variation_custom_field_to_variable_form( $variation_data, $product, $variation ) {
	$variation_data['delay_type'] = $variation->get_meta('_wscsd_delay_type');
	$variation_data['hide_date'] = $variation->get_meta('_wscsd_hide_date');
	$variation_data['start_label'] = $variation->get_meta('_wscsd_start_label');
	$variation_data['fixed_date'] = $variation->get_meta('_wscsd_fixed_start_dates');
	$variation_data['fixed_delay'] = $variation->get_meta('_wscsd_fixed_delays');
	$variation_data['cut_off'] = $variation->get_meta('_wscsd_cut_off');
	$variation_data['cut_off_period'] = $variation->get_meta('_wscsd_cut_off_period');
	//$variation_data['max_dates'] = $variation->get_meta('_wscsd_max_dates');


	return $variation_data;
}

add_action( 'woocommerce_product_additional_information', 'add_html_container_to_display_selected_variation_custom_field' );
function add_html_container_to_display_selected_variation_custom_field( $product ) {
	echo '<div class="custom_variation-text-field"></div>';
}

