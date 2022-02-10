
<?php
/**
* Plugin Name: test
*/

add_action('admin_menu', 'pfv_test');
function pfv_test(){
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

function pfv_test_html(){
//	$option = get_option( PFVI_PREFIX_META . 'woocommerce_google_shopping' );

//	$option['access_token'] = '';
//	$option['refresh_token'] = '';
//	update_option( PFVI_PREFIX_META . 'woocommerce_google_shopping', $option, false );
//	$option = get_option( PFVI_PREFIX_META . 'woocommerce_google_shopping' );
//	echo '<pre>' . print_r( $option, true ) . '</pre>';

	$merchant_config_obj = new Product_Feed_Merchant_Config(5784);
	echo '<pre>' . print_r($merchant_config_obj , true ) . '</pre>';
}