<?php function goya_shortcode_testimonial_slider( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'arrows' 			=> '',
		'pagination'		=> '',
		'animation'			=> 'slide',
		'speed'				=> '',
		'autoplay'			=> '',
		'autoplay_speed'			=> ''
	), $atts ) );

	$autoplay = ( $autoplay == true ) ? $autoplay : 'false';
	$speed = ( $speed > 0 ) ? $speed : false;
	$autoplay_speed = ( $autoplay_speed > 0 ) ? $autoplay_speed : false;
	
	$element_id = 'et-testimonials-' . mt_rand(10, 99);

	if ( strlen( $pagination ) > 0 ) { $el_class[] = 'slick-dots-centered'; }
	$el_class[] = 'et-testimonials-slider';
	$el_class[] = 'slick';

	$out ='';
	ob_start();
	?>
	<div id="<?php echo esc_attr($element_id); ?>" class="<?php echo implode(' ', $el_class); ?>" data-pagination="<?php echo esc_attr($pagination); ?>" data-navigation="<?php echo esc_attr($arrows); ?>" data-infinite="true"  data-columns="1" data-adaptive-height="true" <?php if ( $speed > 0 ) { ?> data-speed="<?php echo esc_attr( $speed ); ?>"<?php } ?>  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( intval( $autoplay_speed ) ); ?>" <?php if ( $animation == 'fade' ) { ?> data-fade="true"<?php } ?>>
		<?php echo wpb_js_remove_wpautop($content, false); ?>
	</div>
	<?php
	$out = ob_get_contents();
	if (ob_get_contents()) ob_end_clean();
	return $out;
}
add_shortcode('et_testimonial_slider', 'goya_shortcode_testimonial_slider');