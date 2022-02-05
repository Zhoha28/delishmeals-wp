<?php function goya_shortcode_icon_holder( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'columns_large'			=> '3',
		'columns_medium'			=> '2',
		'columns_small'			=> '1',
		'center'			=> '',
		'extra_class'    => '',
	), $atts ) );

	$element_id = 'et-icon-holder-' . mt_rand(10, 999);
	
	$el_class[] = 'et-icon-holder';
	$el_class[] = 'row';
	$el_class[] = 'center-' . $center;
	$el_class[] = 'lg-block-grid-' . $columns_large;
	$el_class[] = 'md-block-grid-' . $columns_medium;
	$el_class[] = 'block-grid-' . $columns_small;
	$el_class[] = $extra_class;

	$out ='';
	ob_start();
	
	?>
	<div id="<?php echo esc_attr($element_id); ?>" class="<?php echo esc_attr(implode(' ', $el_class)); ?>">
		<?php echo wpb_js_remove_wpautop($content, false); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
add_shortcode('et_icon_holder', 'goya_shortcode_icon_holder');