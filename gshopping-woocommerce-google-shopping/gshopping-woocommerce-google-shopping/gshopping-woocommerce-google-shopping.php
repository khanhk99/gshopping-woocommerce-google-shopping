<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Product_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       GShopping - WooCommerce Google Shopping
 * Plugin URI:
 * Description:       GShopping - WooCommerce Google Shopping
 * Version:           1.0.0
 * Author:            Villatheme
 * Author URI:        https://villatheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gshopping-wc-google-shopping
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to: 5.8
 * WC requires at least: 5.0
 * WC tested up to: 5.6
 * Requires PHP: 7.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Currently plugin version.
 * Start at version 1.0.0
 */
define( 'VI_PRODUCT_FEED_VERSION', '1.0.0' );

function pfvi_check_conditional() {
	$plugin_name = 'GShopping - WooCommerce google shopping';
	$message     = [];
	if ( ! version_compare( phpversion(), '7.0', '>=' ) ) {
		$message[] = $plugin_name . ' ' . esc_html__( 'require PHP version at least 7.0', 'gshopping-wc-google-shopping' );
	}

	global $wp_version;
	if ( ! version_compare( $wp_version, '5.0', '>=' ) ) {
		$message[] = $plugin_name . ' ' . esc_html__( 'require WordPress version at least 5.0', 'gshopping-wc-google-shopping' );
	}

	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		$message[] = $plugin_name . ' ' . esc_html__( 'require WooCommerce is activated', 'gshopping-wc-google-shopping' );
	}

	$wc_version = get_option( 'woocommerce_version' );
	if ( ! ( $wc_version && version_compare( $wc_version, '5.0', '>=' ) ) ) {
		$message[] = $plugin_name . ' ' . esc_html__( 'require WooCommerce version at least 5.0', 'gshopping-wc-google-shopping' );
	}

	return $message;
}

add_action( 'admin_notices', 'pfvi_admin_notices' );

function pfvi_admin_notices() {
	$conditional = pfvi_check_conditional();
	if ( empty( $conditional ) ) {
		return;
	}
	foreach ( $conditional as $message ) {
		echo sprintf( "<div id='message' class='error'><p>%s</p></div>", esc_html( $message ) );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product-feed-activator.php
 */
function activate_vi_product_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product-feed-activator.php';
	Product_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product-feed-deactivator.php
 */
function deactivate_vi_product_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product-feed-deactivator.php';
	Product_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vi_product_feed' );
register_deactivation_hook( __FILE__, 'deactivate_vi_product_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-product-feed.php';
require plugin_dir_path( __FILE__ ) . 'define.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vi_product_feed() {
	$plugin = new Product_Feed();
	$plugin->run();
}

run_vi_product_feed();

function pfvi_wp_remote_patch( $url, $args = array() ) {
	$defaults    = array( 'method' => 'PATCH' );
	$parsed_args = wp_parse_args( $args, $defaults );
	$wp_http     = new WP_Http();

	return $wp_http->request( $url, $parsed_args );
}

function pfvi_wp_remote_put( $url, $args = array() ) {
	$defaults    = array( 'method' => 'PUT' );
	$parsed_args = wp_parse_args( $args, $defaults );
	$wp_http     = new WP_Http();

	return $wp_http->request( $url, $parsed_args );
}