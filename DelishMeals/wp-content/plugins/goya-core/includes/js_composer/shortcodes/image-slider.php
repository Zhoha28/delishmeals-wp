<?php function goya_shortcode_image_slider( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'images'			=> '',
		'img_size'        => '',
		'columns'			=> '1',
		'lightbox'			=> 'no',
		'center'			=> '',
		'pagination'		=> '',
		'arrows'		=> '',
		'infinite'       => '',
		'animation'      => 'slide',
		'infinite'       => '',
		'overflow'       => '',
		'autoplay'		=> '',
		'autoplay_speed'		=> '',
		'pause'			=> '',
		'extra_class'    => '',
		'caption'     => '',
	), $atts ) );

	$element_id = 'et-image-slider-' . mt_rand(10, 999);

	$img_size = ($img_size === '' ? 'full' : $img_size);
	
	$el_class[] = 'et-image-slider';
	$el_class[] = 'slick';
	$el_class[] = 'slick-slider';
	$el_class[] = 'slick-dotted';
	$el_class[] = 'slick-dots-centered';
	$el_class[] = ($lightbox != 'no') ? 'mfp-gallery' : '';
	$el_class[] = $overflow;
	$el_class[] = $extra_class;

	$arrows = ($lightbox == 'no') ? $arrows : false;

	$fade = ( $animation != 'slide' ) ? 'true' : false;

	$out ='';
	ob_start();
	$images = explode(',',$images);
	
	?>
	<div id="<?php echo esc_attr($element_id); ?>" class="<?php echo esc_attr(implode(' ', $el_class)); ?>" data-adaptive-height="false" variable-width="true" data-pagination="<?php echo esc_attr($pagination); ?>" data-navigation="<?php echo esc_attr($arrows); ?>" data-center="<?php echo esc_attr($center); ?>" data-fade="<?php echo esc_attr($fade); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-infinite="<?php echo esc_attr($infinite); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>" data-pause="<?php echo esc_attr($pause); ?>">
		<?php
			foreach ($images as $image) {
				
				$img_id = preg_replace('/[^\d]/', '', $image);
				$image_post = get_post($img_id);

				$image_link = wp_get_attachment_image_src($image, 'full');

				$image_title = get_the_title( $image );
				$image_caption = ( isset($image_post->post_excerpt) ) ? $image_post->post_excerpt : $image_title;

				?>
				<div class="gallery-item">
					<?php if($lightbox != 'no') { ?><a href="<?php echo esc_attr($image_link[0]); ?>"><?php } ?>
						<div class="et-image-inner">
							<?php 
							$final_image = wp_get_attachment_image($image, $img_size);
							if ( $final_image != '' ) {
								echo wp_get_attachment_image($image, $img_size);
							} else { ?>
								<img src="<?php echo get_template_directory_uri() . '/assets/img/placeholder.png'; ?>" />
							<?php } ?>
							<?php if ($image_caption && $caption === 'true') { ?>
								<div class="wp-caption-text"><?php echo esc_html($image_caption); ?></div>
							<?php } ?>
						</div>
					<?php if($lightbox != 'no') { ?></a><?php } ?>
				</div>
				<?php
			}
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
add_shortcode('et_image_slider', 'goya_shortcode_image_slider');