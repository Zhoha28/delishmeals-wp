<?php
require GOYA_DIR .'/admin/plugins/class-tgm-plugin-activation.php';

function goya_register_required_plugins() {
	
	$plugins = array(

		// Include plugins pre-packaged with the theme
		array(
			'name'               => esc_html__('Goya Core', 'goya'),
			'slug'               => 'goya-core',
			'source'             => 'https://goya.b-cdn.net/assets/plugins/v16-j4d7uhswo/goya-core.zip',
			'required'           => true,
			'version'            => '1.0.5',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => ''
		),
		array(
			'name'               => esc_html__('Envato Market (theme updates)', 'goya'),
			'slug'               => 'envato-market',
			'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required'           => false,
			'version'            => '2.0.7',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => ''
		),
		array(
			'name'               => esc_html__('WPBakery Visual Composer', 'goya'),
			'slug'               => 'js_composer',
			'source'             => 'https://goya.b-cdn.net/assets/plugins/v16-j4d7uhswo/js_composer.zip',
			'required'           => false,
			'version'            => '6.8.0',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => ''
		),
		array(
			'name'               => esc_html__('WC Ajax Product Filters', 'goya'),
			'slug'               => 'wc-ajax-product-filter',
			'source'             => 'https://goya.b-cdn.net/assets/plugins/v16-j4d7uhswo/wc-ajax-product-filter.zip',
			'required'           => false,
			'version'            => '2.0.3.8',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => ''
		),
		array(
			'name'               => esc_html__('Slider Revolution', 'goya'),
			'slug'               => 'revslider',
			'source'             => 'https://goya.b-cdn.net/assets/plugins/v16-j4d7uhswo/revslider.zip',
			'required'           => false,
			'version'            => '6.5.14',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => ''
		),
		
		// Include plugins from the WordPress Plugin Repository
		array(
			'name'               => esc_html__('Kirki Toolkit', 'goya'),
			'slug'               => 'kirki',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('Meta Box ', 'goya'),
			'slug'               => 'meta-box',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('WooCommerce', 'goya'),
			'slug'               => 'woocommerce',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('YITH WooCommerce Wishlist', 'goya'),
			'slug'               => 'yith-woocommerce-wishlist',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('WooCommerce Variation Swatches', 'goya'),
			'slug'               => 'woo-variation-swatches',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('Ninja Forms', 'goya'),
			'slug'               => 'ninja-forms',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__('Mailchimp for WordPress', 'goya'),
			'slug'               => 'mailchimp-for-wp',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
	);

	$config = array(
		'id'           => 'et-framework',
		'default_path' => '',                          // Default absolute path to pre-packaged plugins
		'parent_slug'  => 'themes.php',
		'menu'         => 'install-required-plugins',  // Menu slug
		'has_notices'  => true,                        // Show admin notices or not
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '<div class="notice-warning notice"><p>Install the following required or recommended plugins to get complete functionality from your new theme.</p></div>',                      // Message to output right before the plugins table.
		'strings'      => array(
		'return'       => esc_html__( 'Return to Theme Plugins', 'goya' )
		)
	);

	tgmpa($plugins, $config);

}
add_action('tgmpa_register', 'goya_register_required_plugins');