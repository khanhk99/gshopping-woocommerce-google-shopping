<?php // Silence is golden
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$pfvi_locale     = explode( '_', get_locale() );
$pfvi_locale     = $pfvi_locale[0];
$pfvi_upload_dir = wp_upload_dir();
$pfvi_dir        = $pfvi_upload_dir['basedir'] . '/gshopping-wc-google-shopping/';
$pfvi_url        = $pfvi_upload_dir['baseurl'] . '/gshopping-wc-google-shopping/';
if (!file_exists($pfvi_dir . 'xml')) {
	mkdir($pfvi_dir. 'xml', 0777, true);
}

define( 'PFVI_PREFIX', 'pfvi_' );
define( 'PFVI_PREFIX_META', '_pfvi_' );
define( 'PFVI_LANGUAGE', $pfvi_locale );
define( 'PFVI_DIR_UPLOAD', $pfvi_dir );
define( 'PFVI_URL_UPLOAD', $pfvi_url );
define( 'PFVI_URL', plugin_dir_url( 'gshopping-woocommerce-google-shopping' ) . 'gshopping-woocommerce-google-shopping' );
define( 'PFVI_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "gshopping-woocommerce-google-shopping" . DIRECTORY_SEPARATOR );
define( 'PFVI_ADMIN', PFVI_DIR . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR );
define( 'PFVI_ADMIN_URL', PFVI_URL . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR );
define( 'PFVI_INCLUDES', PFVI_DIR . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR );

