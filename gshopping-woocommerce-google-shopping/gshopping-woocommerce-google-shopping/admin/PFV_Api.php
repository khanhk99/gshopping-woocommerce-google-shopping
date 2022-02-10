<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class PFVI_Api {
	public function __construct() {
		$data_config = new Product_Feed_Config();
		$data_config->get_token();

		//check access_token
	}

	public function insert( $data ) {
		$data_config  = new Product_Feed_Config();
		$api_key      = $data_config->get_params( 'api_key' );
		$access_token = $data_config->get_params( "access_token" );

		$url    = "https://shoppingcontent.googleapis.com/content/v2.1/products/batch?key=$api_key";
		$append = wp_remote_post( $url,
			array(
				'headers' => array(
					'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
				),
				'body'    => json_encode(
					array(
						"entries" => $data
					)
				)
			)
		);

		return $append;
	}

	public function update( $lang, $product_id ) {
		$data_config         = new Product_Feed_Config();
		$config_params       = $data_config->mapping_attr_config();
		$get_params          = $data_config->get_params( "" );
		$merchant_id         = $get_params['merchant_id'];
		$access_token        = $get_params['access_token']['access_token'];
		$type_token          = $get_params['access_token']['token_type'];

		$merchant_config = new Product_Feed_Merchant_Config( $product_id );

		$meta_data = array();

		foreach ( $config_params as $key_param => $param ) {
			$key_product_request               = $data_config->underscoreToCamelCase( $key_param );
			$meta_data[ $key_product_request ] = $merchant_config->product_mapping_merchant( $key_param );
			if ( empty( $meta_data[ $key_product_request ] ) ) {
				$meta_data[ $key_product_request ] = $merchant_config->show_structure_product_empty( $key_param );
			}
		}

		$product_merchant_id = $get_params['api'][ $lang ]['channel'] . ":" .
		                       $lang . ":" .
		                       $get_params['api'][ $lang ]['api_country'] . ":" .
		                       $meta_data['offerId'];

		unset( $meta_data['offerId'] );

		$update = pfvi_wp_remote_patch( "https://shoppingcontent.googleapis.com/content/v2.1/$merchant_id/products/$product_merchant_id", array(
			'headers' => array(
				'Authorization' => "$type_token $access_token",
				'Accept'        => 'application/json',
				'Content-Type'  => 'application/json',
			),
			'body'    => json_encode( $meta_data )
		) );

		return $update;
	}
}