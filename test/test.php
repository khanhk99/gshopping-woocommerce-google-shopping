<?php
/**
 * Plugin Name: test
 */

add_action( 'admin_menu', 'pfv_test' );
function pfv_test() {
	add_menu_page(
		'PFV Test',
		'PFV Test',
		'manage_options',
		'pfv_test',
		'pfv_test_html',
		'',
		3
	);
}

function pfv_test_html() {
//	$option = get_option( PFVI_PREFIX_META . 'woocommerce_google_shopping' );

//	$option['access_token'] = '';
//	$option['refresh_token'] = '';
//	update_option( PFVI_PREFIX_META . 'woocommerce_google_shopping', $option, false );
//	$option = get_option( PFVI_PREFIX_META . 'woocommerce_google_shopping' );
//	echo '<pre>' . print_r( $option, true ) . '</pre>';
//
//	$merchant_config_obj = new Product_Feed_Merchant_Config(25);
//	echo '<pre>' . print_r($merchant_config_obj->product_mapping_merchant("") , true ) . '</pre>';
//
//	$meta_data_product = get_post_meta( 25, PFVI_PREFIX_META . "merchant_data", true );
//	echo '<pre>' . print_r($meta_data_product , true ) . '</pre>';

	$arr = array(
		'0' => array(
			'a' => 'a1',
			'b' => 'b1',
			'c' => 'c1',
		),
		'1' => array(
			'a' => 'a2',
			'b' => 'b2',
			'c' => 'c2',
		),
		'2' => array(
			'a' => 'a3',
			'b' => 'b3',
			'c' => '',
		),
	);

	$object = new Product_Feed_Config();
	echo '<pre>' . print_r($object->reconvert_repeated($arr) , true ) . '</pre>';

//	$wc_product = wc_get_product( 5810 );
//
//	if ( $wc_product->get_type() != 'variation' ) {
//		echo '<pre>' . print_r( $wc_product->get_type(), true ) . '</pre>';
//	}

//	$times = did_action( 'woocommerce_update_product' );
//
//	echo '<pre>' . print_r($times , true ) . '</pre>';

//	$taxes = WC_Tax::get_rates_for_tax_class( $wc_product->get_tax_class() );
//	echo '<pre>' . print_r($taxes , true ) . '</pre>';
}