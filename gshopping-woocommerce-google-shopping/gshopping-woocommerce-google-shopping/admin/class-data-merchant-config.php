<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once PFVI_INCLUDES . 'class-product-feed-config.php';

class Product_Feed_Merchant_Config {
	private $mapping_data;
	private $data_value;
	private $wc_product;
	private $meta_data_product;
	private $meta_key;

	public function __construct( $product_id ) {
		$this->meta_key          = PFVI_PREFIX_META . "merchant_data";
		$this->wc_product        = wc_get_product( $product_id );
		$this->meta_data_product = get_post_meta( $product_id, $this->meta_key, true );

		$this->mapping_data = array(
			"offer_id"                  => ( ! empty( $this->wc_product->get_sku() ) ) ? $this->wc_product->get_sku() : $this->wc_product->get_id(),
			"title"                     => $this->wc_product->get_title(),
			"description"               => $this->clean_description( $this->wc_product->get_description() ),
			"link"                      => $this->wc_product->get_permalink(),
			"image_link"                => $this->get_image_link(),
			"additional_image_links"    => $this->get_galleries( $this->wc_product->get_gallery_image_ids() ),
			"availability"              => $this->switch_availability( $this->wc_product->get_availability()["class"] ),
			"price"                     => ! empty( $this->wc_product->get_regular_price() ) ? $this->get_price() : "",
			"sale_price"                => ! empty( $this->wc_product->get_sale_price() ) ? $this->get_sale_price() : '',
			"sale_price_effective_date" => $this->get_sale_price_effective_date(),
//			"condition"                 => $this->get_condition(),
			"shipping"                  => $this->get_shipping(),
		);

		$this->data_value = array(
			"condition" => "new",
		);
	}

	/**
	 * Get default if value empty
	 *
	 * @since    1.0.0
	 */
	public function get_default( $name ) {
		if ( ! $name ) {
			return $this->data_value;
		} elseif ( isset( $this->data_value[ $name ] ) ) {
			return $this->data_value[ $name ];
		} else {
			return "";
		}
	}

	/**
	 * Get value via key
	 *
	 * @since    1.0.0
	 */
	public function product_mapping_merchant( $name ) {
		if ( ! $name ) {
			return $this->mapping_data;
		} elseif ( isset( $this->mapping_data[ $name ] ) ) {
			return $this->mapping_data[ $name ];
		} elseif ( ! isset( $this->mapping_data[ $name ] ) && isset( $this->meta_data_product[ $name ] ) ) {
			return $this->meta_data_product[ $name ];
		} elseif ( strpos( $name, '-' ) !== false ) {
			// exist child element
			// split string
			$explodes = explode( '-', $name, 2 );

			if ( isset( $this->mapping_data[ $explodes[0] ] ) ) {
				// get value from Woocommerce
				return $this->search_product_mapping( $this->mapping_data, $name );
			} elseif ( ! isset( $this->mapping_data[ $explodes[0] ] ) && isset( $this->meta_data_product[ $explodes[0] ] ) ) {
				// get value from metadata
				return $this->search_product_mapping( $this->meta_data_product, $name );
			}
		} else {
			return "";
		}
	}

	public function show_structure_product_empty( $name ) {
		$config_object = new Product_Feed_Config();
		$attr          = $config_object->get_attributes( $name );
		if ( isset( $attr['element'] ) ) {
			return $this->recursive_product_arr( $attr['element'] );
		} else {
			return "";
		}
	}

	private function recursive_product_arr( $arrs ) {
		$config_object = new Product_Feed_Config();
		$result        = [];
		foreach ( $arrs as $key => $arr ) {
			$key_camel = $config_object->underscoreToCamelCase( $key );
			if ( isset( $arr['element'] ) ) {
				$result[ $key_camel ] = $this->recursive_product_arr( $arr['element'] );
			} else {
				$result[ $key_camel ] = "";
			}
		}

		return $result;
	}

	private function search_product_mapping( $arr, $key ) {
		$keys = explode( '-', $key );
		if ( count( $keys ) === 2 ) {
			return $arr[ $keys[0] ][ $keys[1] ] ?? "";
		} elseif ( count( $keys ) === 3 ) {
			return $arr[ $keys[0] ][ $keys[1] ][ $keys[2] ] ?? "";
		} else {
			return "";
		}
	}

	public function convert_meta_data_to_string( $arr, $meta_data ) {
		$count  = 0;
		$result = [];
		foreach ( $arr as $key => $value ) {
			$meta_data_value = $meta_data[ $key ] ?? " ";
			if ( isset( $meta_data_value['type'] ) && ( $meta_data_value['type'] == 'multiple' ) ) {
				unset( $meta_data_value['type'] );
				$result_multi = "";
				foreach ( $meta_data_value as $row => $item_multi ) {
					if ( isset( $arr[ $key ]['element'] ) ) {
//				        >1 dimensional
						if ( $arr[ $key ]['separate'] === ":" ) {
//				            3 dimensional
							$result_two = "";
							foreach ( $arr[ $key ]['element'] as $key_two => $value_two ) {
								if ( isset( $value_two['element'] ) ) {
									$result_three = "";
									foreach ( $value_two['element'] as $key_three => $value_three ) {
										$result_three .= $item_multi[ $key_two ][ $key_three ];
									}
									$result_two .= $result_three;
									$result_two .= "";
								} else {
									$result_two .= $item_multi[ $key_two ];
									$result_two .= ":";
								}
							}
							$result_multi .= $result_two;
						} else {
//				            2 dimensional (price)
							$result_two = "";
							foreach ( $arr[ $key ]['element'] as $key_two => $value_two ) {
								$result_two .= $item_multi[ $key_two ] ?? "";
								$result_two .= " ";
							}
							$result_multi .= $result_two;
						}
					} else {
//				        1 dimensional
						$result_multi .= $item_multi;
					}
					$result_multi .= ",";
				}
				$result[ $count ] = $result_multi;
				$count ++;
			} else {
				if ( isset( $arr[ $key ]['element'] ) ) {
//				>1 dimensional
					if ( $arr[ $key ]['separate'] === ":" ) {
//				    3 dimensional
						$result_two = "";
						foreach ( $arr[ $key ]['element'] as $key_two => $value_two ) {
							if ( isset( $value_two['element'] ) ) {
								$result_three = "";
								foreach ( $value_two['element'] as $key_three => $value_three ) {
									$result_three .= $meta_data_value[ $key_two ][ $key_three ];
								}
								$result_two .= $result_three;
								$result_two .= " ";
							} else {
								$result_two .= !empty($meta_data_value[ $key_two ]) ? $meta_data_value[ $key_two ] : "";
								$result_two .= ":";
							}
						}
						$result[ $count ] = $result_two;
						$count ++;
					} else {
//				    2 dimensional (price)
						$result_two = "";
						foreach ( $arr[ $key ]['element'] as $key_two => $value_two ) {
							$result_two .= $meta_data_value[ $key_two ] ?? "";
							$result_two .= " ";
						}
						$result[ $count ] = $result_two;
						$count ++;
					}
				} else {
//				1 dimensional
					$result[ $count ] = $meta_data_value;
					$count ++;
				}
			}
		}

		return $result;
	}

	public function get_meta_key() {
		return $this->meta_key;
	}

	private function switch_availability( $value ) {
		switch ( $value ) {
			case "out-of-stock":
				return "out_of_stock";
				break;
			case "in-stock":
				return "in_stock";
				break;
			case "available-on-backorder":
				return "backorder";
				break;
			case "preorder":
				return "preorder";
				break;
		}
	}

	private function get_galleries( $arr ) {
		if ( $arr ) {
			$result = array(
				'type' => 'multiple'
			);
			foreach ( $arr as $item ) {
				$link = ( wp_get_attachment_image_src( $item )[0] ) ?? '';
				array_push( $result, $link );
			}

			return $result;
		} else {
			return "";
		}
	}

	private function get_sale_price_effective_date() {
		if ( ! empty( $this->wc_product->get_date_on_sale_from() ) && ! empty( $this->wc_product->get_date_on_sale_to() ) ) {
			return $this->wc_product->get_date_on_sale_from() . "/" . $this->wc_product->get_date_on_sale_to();
		} else {
			return "";
		}
	}

	private function clean_description( $description ) {
		$description = strip_tags( $description );
		$re          = '/&nbsp;|\s+/';
		$subst       = ' ';

		return trim( preg_replace( $re, $subst, $description ) );
	}

	private function get_image_link() {
		if ( $this->wc_product->get_image_id() ) {
			return wp_get_attachment_image_src( $this->wc_product->get_image_id() )[0];
		}
	}

	private function get_price() {
		return array(
			"value"    => $this->wc_product->get_regular_price(),
			"currency" => get_woocommerce_currency(),
		);
	}

	private function get_sale_price() {
		return array(
			"value"    => $this->wc_product->get_sale_price(),
			"currency" => get_woocommerce_currency(),
		);
	}

	private function get_shipping() {
		$shipping_zones = WC_Shipping_Zones::get_zones();
		$shipping       = array(
			'type' => 'multiple'
		);

		foreach ( $shipping_zones as $zone ) {
			$zone_item = array(
				'country' => "",
				'region'  => "",
				'price'   => "",
			);
			foreach ( $zone['zone_locations'] as $location ) {
				if ( $location->type == "country" ) {
					$zone_item['country'] = sanitize_text_field( $location->code );
				} elseif ( $location->type == "postcode" ) {
					$zone_item['region'] = sanitize_text_field( $location->code );
				} elseif ( $location->type == "state" ) {
					$zone_expl           = explode( ":", $location->code );
					$zone_item['region'] = sanitize_text_field( $zone_expl[0] );
				} elseif ( $location->type == "code" ) {
					$zone_item['country'] = sanitize_text_field( $location->code );
				}
			}

			foreach ( $zone['shipping_methods'] as $key => $value ) {
				if ( $value->enabled == "yes" ) {
					$zone_item['price'] = array(
						'value'    => $value->cost ?? "0",
						'currency' => get_woocommerce_currency(),
					);
					array_push( $shipping, $zone_item );
				}
			}
		}

//		echo json_encode($shipping);
//		die();
		return $shipping;
	}
}