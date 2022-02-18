<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
require_once PFVI_DIR . 'vendor/autoload.php';
require_once PFVI_INCLUDES . "class-product-feed-config.php";

class PFVI_Sheet {
	public function get_all( $lang, $majorDimension = "COLUMNS" ) {
		$data_config  = new Product_Feed_Config();
		$sheet_config = $data_config->get_params( "sheet" )[ $lang ];
		$api_key      = $data_config->get_params( 'api_key' );
		$access_token = $data_config->get_params( "access_token" );

		$url      = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_config['sheet_id'] . "/values/" . $sheet_config['sheet_range'] . "?majorDimension=$majorDimension&key=$api_key";
		$all_data = wp_remote_get( $url,
			array(
				'headers' => array(
					'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
				),
			)
		);

		return json_decode( $all_data['body'] );
	}

	public function create( $language ) {
		$data_config  = new Product_Feed_Config();
		$title        = $language . " - GShopping - WooCommerce Google Shopping";
		$api_key      = $data_config->get_params( 'api_key' );
		$access_token = $data_config->get_params( "access_token" );

		$url    = "https://sheets.googleapis.com/v4/spreadsheets?key=$api_key";
		$create = wp_remote_post( $url,
			array(
				'headers' => array(
					'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
				),
				'body'    => json_encode( array(
						"properties" => array(
							"title" => $title
						)
					)
				),
			)
		);

		return json_decode( $create['body'] );
	}

	public function append( $lang, $data ) {
		$data_config  = new Product_Feed_Config();
		$sheet_config = $data_config->get_params( "sheet" )[ $lang ];
		$api_key      = $data_config->get_params( 'api_key' );
		$access_token = $data_config->get_params( "access_token" );

		$url    = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_config['sheet_id'] . "/values/" . $sheet_config['sheet_range'] . ":append?valueInputOption=RAW&key=$api_key";
		$append = wp_remote_post( $url,
			array(
				'headers' => array(
					'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
				),
				'body'    => json_encode( array(
						"values" => $data
					)
				)
			)
		);

		return $append;
	}

	public function update_title( $lang ) {
		$data_config  = new Product_Feed_Config();
		$attributes   = $data_config->get_params( "attributes" );
		$sheet_config = $data_config->get_params( "sheet" )[ $lang ];
		$api_key      = $data_config->get_params( 'api_key' );
		$access_token = $data_config->get_params( "access_token" );

		$url    = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_config['sheet_id'] . "/values/" . $sheet_config['sheet_range'] . "?valueInputOption=RAW&key=$api_key";
		$update = pfvi_wp_remote_put( $url,
			array(
				'headers' => array(
					'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
				),
				'body'    => json_encode( array(
						"values" => array( $attributes )
					)
				),
			)
		);

		return $update;
	}

	public function update_product( $lang, $product_ids = [] ) {
		$data_config   = new Product_Feed_Config();
		$config_params = $data_config->mapping_attr_config();

		$all_data    = $this->get_all( $lang );
		$all_ids     = $all_data->values[0];
		$sheet       = $data_config->get_params( "sheet" );
		$sheet_range = $sheet[ $lang ]['sheet_range'];

		$data = array();

		foreach ( $product_ids as $product_id ) {
			$merchant_config = new Product_Feed_Merchant_Config( $product_id );
			$meta_data       = array();

			foreach ( $config_params as $key_param => $param ) {
				if ( ! empty( $merchant_config->product_mapping_merchant( $key_param ) ) ) {
					$meta_data[ $key_param ] = $merchant_config->product_mapping_merchant( $key_param );
				} elseif ( $merchant_config->get_default( $key_param ) ) {
					$meta_data[ $key_param ] = $merchant_config->get_default( $key_param );
				} else {
					$meta_data[ $key_param ] = '';
				}
			}

			$product_arr = $merchant_config->convert_meta_data_to_string( $config_params, $meta_data );
			$key         = array_search( $product_arr[0], $all_ids );

			if ( ! empty( $key ) ) {
				$data_item                   = array();
				$range_update                = $sheet_range . '!A' . ++ $key;
				$data_item["range"]          = $range_update;
				$data_item["values"]         = [ $product_arr ];
				$data_item["majorDimension"] = "ROWS";

				array_push( $data, $data_item );
			}
		}

		if ( ! empty( $data ) ) {
			$spreadsheet_id = $sheet[ $lang ]['sheet_id'];
			$api_key        = $data_config->get_params( 'api_key' );
			$access_token   = $data_config->get_params( "access_token" );

			$url    = "https://sheets.googleapis.com/v4/spreadsheets/$spreadsheet_id/values:batchUpdate?key=$api_key";
			wp_remote_post( $url,
				array(
					'headers' => array(
						'Authorization' => $access_token['token_type'] . " " . $access_token['access_token'],
						'Accept'        => 'application/json',
						'Content-Type'  => 'application/json',
					),
					'body'    => json_encode(
						array(
							"valueInputOption" => "RAW",
							"data"             => $data
						)

					),
				)
			);
		}
	}

	public function get_data_not_exist( $lang, $datas_arr ) {
		$all_data = $this->get_all( $lang );
		$all_ids  = isset( $all_data->values ) ? $all_data->values[0] : array();

		$result = [];
		foreach ( $datas_arr as $data ) {
			if ( ! in_array( strval( $data[0] ), $all_ids ) ) {
				array_push( $result, $data );
			}
		}

		return $result;
	}
}