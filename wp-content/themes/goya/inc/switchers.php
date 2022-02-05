<?php

function goya_currency_switcher( $args = array() ) {

	if ( class_exists( 'WOOCS' ) ) {

	global $WOOCS;

	$args          = wp_parse_args( $args, array( 'label' => '', 'direction' => 'down' ) );
	$currencies    = $WOOCS->get_currencies();
	$currency_list = array();

	foreach ( $currencies as $key => $currency ) {
		if ( $WOOCS->current_currency == $key ) {
			array_unshift( $currency_list, sprintf(
				'<li><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
				esc_attr( $currency['name'] ),
				esc_html( $currency['name'] )
			) );
		} else {
			$currency_list[] = sprintf(
				'<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
				esc_attr( $currency['name'] ),
				esc_html( $currency['name'] )
			);
		}
	}
	?>
	<div class="et-switcher-container et-currency">
		<span class="label"><?php echo esc_html__('Currency', 'goya');	?></span>
		<?php if ( ! empty( $args['label'] ) ) : ?>
			<span class="label"><?php echo esc_html( $args['label'] ); ?></span>
		<?php endif; ?>
		<ul class="et-header-menu">
			<li class="menu-item-has-children">
				<span class="selected"><?php echo esc_html( $currencies[ $WOOCS->current_currency ]['name'] ); ?></span>
				<ul class="sub-menu">
					<?php echo implode( "\n\t", $currency_list ); ?>
				</ul>
			</li>
		</ul>
	</div>
		
	<?php } else if (class_exists('WCML_Currency_Switcher') ) { ?>
		
		<div class="et-switcher-container et-currency">
			<span class="label"><?php echo esc_html__('Currency', 'goya');	?></span> <?php do_action('wcml_currency_switcher', array('format' => '%code%'));  ?>
		</div>

	<?php }
}
add_action( 'goya_currency_switcher', 'goya_currency_switcher' );


/* Custom Language Switcher */
function goya_language_switcher() {
	$langs = array();
	$languages = apply_filters( 'goya_languages', $langs );
	$caption = apply_filters( 'goya_lang_caption', 'name' );

	if ( function_exists('icl_get_languages') || !empty($languages) || function_exists('pll_the_languages')) {
	?>
	<div class="et-switcher-container et-language">
		<span class="label"><?php echo esc_html__('Language', 'goya');	?></span>
		<ul class="et-header-menu">
			<li class="menu-item-has-children">
				<span class="selected"><?php
					
					if (function_exists('pll_the_languages')) {
						$languages = pll_the_languages(array('raw'=>1));	
					} else if (function_exists('icl_get_languages')) {
						$languages = icl_get_languages('skip_missing=0');
					} 
					
					if(1 < count($languages)){
						if (function_exists('pll_the_languages')) { // Polylang
							foreach($languages as $l){
								echo esc_attr($l['current_lang'] ? $l[($caption == 'language_code') ? 'slug' : 'name'] : '');
							}
						} else { // WPML, Custom
							foreach($languages as $l){
								echo esc_attr($l['active'] ? $l[($caption == 'language_code') ? 'slug' : 'native_name'] : '');
							}
						}
					}
				?></span>
				<ul class="sub-menu">
				<?php
					if(0 < count($languages)){
						foreach($languages as $l){
							if (function_exists('pll_the_languages')) {
								if (!$l['current_lang']) {
									echo '<li><a href="'.$l['url'].'" data-lang="'.$l['slug'].'" title="'.$l['name'].'">'.$l[($caption == 'language_code') ? 'slug' : 'name'].'</a></li>';
								}
							} else {
								if (!$l['active']) {
									echo '<li><a href="'.$l['url'].'" data-lang="'.$l['language_code'].'" title="'.$l['native_name'].'" class="nturl">'.$l[($caption == 'language_code') ? 'slug' : 'native_name'].'</a></li>';
								}
							}
						}
					} else {
						echo '<li>'.esc_html__('Add Languages', 'goya').'</li>';	
					}
				?>
				</ul>
			</li>
		</ul>
	</div>
	<?php 
	}
}
add_action( 'goya_language_switcher', 'goya_language_switcher' );
