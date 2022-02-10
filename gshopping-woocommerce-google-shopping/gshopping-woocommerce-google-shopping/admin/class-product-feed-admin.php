<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
require_once PFVI_INCLUDES . "class-product-feed-config.php";
require_once PFVI_ADMIN . "class-data-merchant-config.php";
require_once PFVI_DIR . 'vendor/autoload.php';
require_once PFVI_ADMIN . "PFV_Sheet.php";
require_once PFVI_ADMIN . "PFV_Schedule.php";
require_once PFVI_ADMIN . "PFV_Api.php";
require_once PFVI_ADMIN . "support.php";

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Feed
 * @subpackage Product_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Product_Feed
 * @subpackage Product_Feed/admin
 * @author     Your Name <email@example.com>
 */
class Product_Feed_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $product -feed    The ID of this plugin.
	 */
	private $product_feed;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	protected $error;
	protected $data_config;
	public $screen;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $product -feed       The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $product_feed, $version ) {
		$this->data_config  = new Product_Feed_Config();
		$this->product_feed = $product_feed;
		$this->version      = $version;
		$this->screen       = array(
			'toplevel_page_vi-product-feed',
			'edit-product',
			'product',
			'gshopping_page_pfvi-wizard'
		);
		$this->support();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function activation_redirect() {
		$notice = pfvi_check_conditional();
		if ( empty( $notice ) ) {
			$active = get_option( PFVI_PREFIX_META . 'count_active' );
			if ( empty( $active ) || ( $active !== '1' ) ) {
				update_option( PFVI_PREFIX_META . 'count_active', '1', false );
				exit( wp_redirect( admin_url( 'admin.php?page=pfvi-wizard' ) ) );
			}
		}
	}

	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen()->id;
		if ( in_array( $screen, $this->screen ) ) {
			wp_enqueue_style( $this->product_feed, plugin_dir_url( __FILE__ ) . 'css/product-feed-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-form-css', plugin_dir_url( __FILE__ ) . 'css/form.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-menu-css', plugin_dir_url( __FILE__ ) . 'css/menu.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-tab-css', plugin_dir_url( __FILE__ ) . 'css/tab.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-segment-css', plugin_dir_url( __FILE__ ) . 'css/segment.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-button-css', plugin_dir_url( __FILE__ ) . 'css/button.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-icon-css', plugin_dir_url( __FILE__ ) . 'css/icon.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-message-css', plugin_dir_url( __FILE__ ) . 'css/message.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-table-css', plugin_dir_url( __FILE__ ) . 'css/table.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-accordion-css', plugin_dir_url( __FILE__ ) . 'css/accordion.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-checkbox-css', plugin_dir_url( __FILE__ ) . 'css/checkbox.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-label-css', plugin_dir_url( __FILE__ ) . 'css/label.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-dropdown-css', plugin_dir_url( __FILE__ ) . 'css/dropdown.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-search-css', plugin_dir_url( __FILE__ ) . 'css/search.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-transition-css', plugin_dir_url( __FILE__ ) . 'css/transition.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-popup-css', plugin_dir_url( __FILE__ ) . 'css/popup.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-loader-css', plugin_dir_url( __FILE__ ) . 'css/loader.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-progress-css', plugin_dir_url( __FILE__ ) . 'css/progress.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-input-css', plugin_dir_url( __FILE__ ) . 'css/input.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-list-css', plugin_dir_url( __FILE__ ) . 'css/list.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'vi-ui-step-css', plugin_dir_url( __FILE__ ) . 'css/step.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen()->id;
		if ( in_array( $screen, $this->screen ) ) {
			wp_enqueue_script( $this->product_feed, plugin_dir_url( __FILE__ ) . 'js/product-feed-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'api-google-js', 'https://apis.google.com/js/api.js', '', $this->version, false );
			wp_enqueue_script( 'vi-ui-tab-js', plugin_dir_url( __FILE__ ) . 'js/tab.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-form-js', plugin_dir_url( __FILE__ ) . 'js/form.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-accordion-js', plugin_dir_url( __FILE__ ) . 'js/accordion.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-checkbox-js', plugin_dir_url( __FILE__ ) . 'js/checkbox.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-popup-js', plugin_dir_url( __FILE__ ) . 'js/popup.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-dropdown-js', plugin_dir_url( __FILE__ ) . 'js/dropdown.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-search-js', plugin_dir_url( __FILE__ ) . 'js/search.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-transition-js', plugin_dir_url( __FILE__ ) . 'js/transition.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-address-js', plugin_dir_url( __FILE__ ) . 'js/address.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'vi-ui-progress-js', plugin_dir_url( __FILE__ ) . 'js/progress.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pfvi-script-js', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pfvi-validate-js', plugin_dir_url( __FILE__ ) . 'js/validate.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pfvi-wizard-js', plugin_dir_url( __FILE__ ) . 'js/setup-wizard.js', array( 'jquery' ), $this->version, false );
		}

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	public function enqueue_localize_script() {
		$params_config     = $this->data_config->get_params( "" );
		$attributes_config = $this->data_config->get_attributes( "" );
		$languages         = $this->data_config->get_languages( "" );
		$countries         = $this->data_config->get_countries( "" );

		$attributes = array();

		foreach ( $params_config["attributes"] as $attribute ) {
			$attributes[ $attribute ] = $attributes_config[ $attribute ];
		}

		$url_page_config  = admin_url( "admin.php?page=vi-product-feed" );
		$url_setup_wizard = admin_url( "admin.php?page=pfvi-wizard" );

		$products_selected = array(
			"ajax_url"         => admin_url( 'admin-ajax.php' ),
			"nonce"            => wp_create_nonce( 'pfvi_nonce_js' ),
			'attributes'       => $attributes,
			'params_config'    => $params_config,
			'url_page_config'  => $url_page_config,
			'url_setup_wizard' => $url_setup_wizard,
			'admin_url'        => PFVI_ADMIN_TEMPLATES,
		);

		$destination = array(
			"ajax_url"  => admin_url( 'admin-ajax.php' ),
			"nonce"     => wp_create_nonce( 'pfvi_nonce_js' ),
			'languages' => $languages,
			'countries' => $countries,
		);

		wp_localize_script( $this->product_feed, 'pfvi_woo_admin_products_js', $products_selected );
		wp_localize_script( 'pfvi-script-js', 'pfvi_destination', $destination );
	}

	public function support() {
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/gshopping-wc-google-shopping/',
					'docs'      => 'http://docs.villatheme.com/?item=gshopping-wc-google-shopping',
					'review'    => 'https://wordpress.org/support/plugin/gshopping-wc-google-shopping/reviews/?rate=5#rate-response',
					'css'       => PFVI_ADMIN_CSS,
					'image'     => PFVI_ADMIN_IMAGES,
					'slug'      => 'gshopping-wc-google-shopping',
					'menu_slug' => 'vi-product-feed',
					'version'   => VI_PRODUCT_FEED_VERSION
				)
			);
		}
	}

	/**
	 * Add menu admin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_admin() {
		$notice = pfvi_check_conditional();
		if ( empty( $notice ) ) {
			add_menu_page(
				esc_html__( 'GShopping' ),
				esc_html__( 'GShopping' ),
				'manage_options',
				$this->product_feed,
				array( $this, 'add_menu_admin_html' ),
				'dashicons-google',
				3
			);

			add_submenu_page(
				$this->product_feed,
				esc_html( 'Setup Wizard' ),
				esc_html( 'Setup Wizard' ),
				'manage_options',
				'pfvi-wizard',
				array( $this, 'setup_wizard' )
			);
		}
	}

	/**
	 * Google merchant feed
	 * @since    1.0.0
	 */
	protected function save_data_setting( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		} else {
			return sanitize_text_field( $value );
		}
	}

	protected function save_google_merchant( $post, $config_arr ) {
		$data_save = array();

		$languages = "";
		if ( class_exists( 'Polylang' ) ) {
			$languages = pll_languages_list();
		} else {
			$languages = [ PFVI_LANGUAGE ];
		}

		foreach ( $languages as $language ) {
			$data_child = array();
			foreach ( $config_arr as $key_config => $value_config ) {
				if ( isset( $post[ $key_config ][ $language ] ) ) {
					$data_child[ $key_config ] = $this->save_data_setting( $post[ $key_config ][ $language ] );
				} else {
					$data_child[ $key_config ] = "";
				}
			}
			$data_save[ $language ] = $data_child;
		}

		return $data_save;
	}

	public function setup_wizard() {
		$data_config = new Product_Feed_Config();

		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template(
				'setup-wizard.php',
				array(
					'params_config' => $data_config->get_params( "" ),
					'attributes'    => $data_config->get_attributes( "" ),
					'languages_map' => $data_config->get_languages( "" ),
					'countries'     => $data_config->get_countries( "" ),
				),
				'',
				PFVI_ADMIN_TEMPLATES
			);
		}
	}

	public function wizard_credential() {
		if ( isset( $_POST['_ajax_nonce'] )
		     && wp_verify_nonce( $_POST['_ajax_nonce'], 'pfvi_wizard_credential_nonce' ) ) {
			$list_params = $this->data_config->get_params( "" );
			$data_save   = $list_params;

			foreach ( $list_params as $key => $param ) {
				if ( isset( $_POST[ $key ] ) ) {
					$data_save[ $key ] = $this->save_data_setting( $_POST[ $key ] );
				}
			}

			update_option( PFVI_PREFIX_META . 'woocommerce_google_shopping', $data_save, false );

			die();
		}
	}

	public function wizard_data() {
		if ( isset( $_POST['pfvi_nonce'] )
		     && wp_verify_nonce( $_POST['pfvi_nonce'], 'pfvi_nonce_data' ) ) {
			$list_params = $this->data_config->get_params( "" );
			$data_save   = $list_params;

			$languages = "";
			if ( class_exists( 'Polylang' ) ) {
				$languages = pll_languages_list();
			} else {
				$languages = [ PFVI_LANGUAGE ];
			}

			foreach ( $list_params as $key => $param ) {
				if ( $key === 'schedule' ) {
					$data_save['schedule'] = $this->save_google_merchant( $_POST, $this->data_config->get_schedule() );
					//generate file xml
					foreach ( $languages as $language ) {
						$data_save['schedule'][ $language ]['link_xml'] = $this->generate_xml( $language );
					}
				} elseif ( $key === 'sheet' ) {
					$data_save['sheet'] = $this->save_google_merchant( $_POST, $this->data_config->get_sheet() );
				} elseif ( $key === 'api' ) {
					$data_save['api'] = $this->save_google_merchant( $_POST, $this->data_config->get_api() );
				} elseif ( ( $key === 'access_token' ) || ( $key === 'refresh_token' ) ) {
					continue;
				} else {
					if ( isset( $_POST[ $key ] ) ) {
						$data_save[ $key ] = $this->save_data_setting( $_POST[ $key ] );
					}
				}
			}

			update_option( PFVI_PREFIX_META . 'woocommerce_google_shopping', $data_save, false );

			die();
		}
	}

	public function add_menu_admin_html() {
		/**
		 * Save setting
		 * @since    1.0.0
		 */
		//		oauth
		if ( isset( $_GET['view'] ) && ( $_GET['view'] == 'oauth' ) ) {
			$this->data_config->get_token();
		}

		if ( isset( $_POST[ PFVI_PREFIX . 'save_setting_feed' ] )
		     && isset( $_POST['pfvi_nonce'] )
		     && wp_verify_nonce( $_POST['pfvi_nonce'], 'pfvi_save_config' ) ) {
			//Get all param config
			$list_params = $this->data_config->get_params( "" );
			$data_save   = $list_params;

			$languages = "";
			if ( class_exists( 'Polylang' ) ) {
				$languages = pll_languages_list();
			} else {
				$languages = [ PFVI_LANGUAGE ];
			}

			foreach ( $list_params as $key => $param ) {
				if ( $key === 'schedule' ) {
					$data_save['schedule'] = $this->save_google_merchant( $_POST, $this->data_config->get_schedule() );
					//generate file xml
					foreach ( $languages as $language ) {
						$data_save['schedule'][ $language ]['link_xml'] = $this->generate_xml( $language );
					}
				} elseif ( $key === 'sheet' ) {
					$data_save['sheet'] = $this->save_google_merchant( $_POST, $this->data_config->get_sheet() );
				} elseif ( $key === 'api' ) {
					$data_save['api'] = $this->save_google_merchant( $_POST, $this->data_config->get_api() );
				} elseif ( ( $key === 'access_token' ) || ( $key === 'refresh_token' ) ) {
					continue;
				} else {
					if ( isset( $_POST[ $key ] ) ) {
						$data_save[ $key ] = $this->save_data_setting( $_POST[ $key ] );
					} else {
						$data_save[ $key ] = $this->data_config->get_default( $key );
					}
				}
			}

			update_option( PFVI_PREFIX_META . 'woocommerce_google_shopping', $data_save, false );
		}

		if ( isset( $_POST[ PFVI_PREFIX . 'reset_save_setting_feed' ] )
		     && isset( $_POST['pfvi_nonce'] )
		     && wp_verify_nonce( $_POST['pfvi_nonce'], 'pfvi_save_config' ) ) {
			delete_option( PFVI_PREFIX_META . 'woocommerce_google_shopping' );
		}

		$data_config = new Product_Feed_Config();

		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template(
				'product-feed-admin-display.php',
				array(
					'params_config' => $data_config->get_params( "" ),
					'attributes'    => $data_config->get_attributes( "" ),
					'languages_map' => $data_config->get_languages( "" ),
					'countries'     => $data_config->get_countries( "" ),
				),
				'',
				PFVI_ADMIN_TEMPLATES
			);
		}
	}

	public function generate_xml( $language ) {
		if ( isset( $_POST['language'] ) ) {
			$language = sanitize_text_field($_POST['language']);
		}

		$name_file_xml = 'feed-' . $language;

		$xml_body = '<?xml version="1.0" ?>
			<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
				<channel>
				</channel>
			</rss>';

		$dir = PFVI_DIR_UPLOAD . 'xml/' . $name_file_xml . '.xml';
		$url = PFVI_URL_UPLOAD . 'xml/' . $name_file_xml . '.xml';
		if ( ! file_exists( $dir ) ) {
			$file = fopen( $dir, "w" );
			fwrite( $file, $xml_body );
			fclose( $file );
		}

		if ( isset( $_POST['language'] ) ) {
			$schedule_config = $this->data_config->get_params( 'schedule' );
			$record          = $schedule_config[ $language ];

			$languages_map = $this->data_config->get_languages( $language );
			$html          = "";
			if ( ! empty( $record["link_xml"] ) && file_exists( PFVI_DIR_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . $name_file_xml . ".xml" ) ) {
				$html = '<div class="vi-ui buttons">
					<div class="vi-ui small icon button copy-xml" data-tooltip="Copy url">
					<i class="copy icon"></i>
					<input name="link_xml[' . $language . ']"
					       value="' . $record["link_xml"] . '" hidden>
				</div>
				<div class="vi-ui small icon button clear-xml" data-tooltip="Clear data">
					<i class="redo icon"></i>
					<input value="' . $language . '" hidden>
				</div>
				<div class="vi-ui small icon button push-all-schedule" data-tooltip="Push all product">
					<i class="angle double up icon"></i>
					<input language="' . $languages_map . '" value="' . $language . '" hidden>
				</div>
				</div>';
			}
			echo json_encode( $html );
			die();
		}

		return $url;
	}

	public function clear_xml() {
		$language = sanitize_text_field($_POST['language']);
		$xml_body = '<?xml version="1.0" ?>
			<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
				<channel>
				</channel>
			</rss>';

		$dir = PFVI_DIR_UPLOAD . 'xml' . DIRECTORY_SEPARATOR . 'feed-' . $language . '.xml';

		$file = fopen( $dir, "w" );
		fwrite( $file, $xml_body );
		fclose( $file );

		die();
	}

	public function setting_link( $plugin_actions, $plugin_file ) {
		$setting_link = array();

		if ( 'gshopping-woocommerce-google-shopping/gshopping-woocommerce-google-shopping.php' === $plugin_file ) {
			$setting_link['setting'] = sprintf( '<a href="%1s" >%2s</a>', esc_url( admin_url( 'admin.php?page=' . $this->product_feed ) ), esc_html__( 'Setting', 'gshopping-wc-google-shopping' ) );
		}

		return array_merge( $setting_link, $plugin_actions );
	}

	/**
	 * Add option to bulk action into All products page
	 *
	 * @since    1.0.0
	 */
	public function add_option_bulk_action_merchant( $bulk_actions ) {
		$bulk_actions['add_to_xml']       = esc_html__( 'Add to XML', 'gshopping-wc-google-shopping' );
		$bulk_actions['add_to_sheet']     = esc_html__( 'Add to Google Sheet', 'gshopping-wc-google-shopping' );
		$bulk_actions['push_to_merchant'] = esc_html__( 'Push to Merchant', 'gshopping-wc-google-shopping' );

		return $bulk_actions;
	}

	public function check_enable( $name, $lang ) {
		$type = $this->data_config->get_params( $name );
		$key  = $name . '_enable';
		if ( isset( $type[ $lang ] ) && $type[ $lang ][ $key ] == "on" ) {
			return true;
		}

		return false;
	}

	public function push_xml( $product_ids ) {
		$productIds = [];
		if ( ! empty( $product_ids ) ) {
			$productIds = array_map( 'sanitize_text_field', $product_ids );
		}
		if ( isset( $_GET["productIds"] ) ) {
			if ( ! isset( $_GET['_ajax_nonce'] ) || ! wp_verify_nonce( $_GET['_ajax_nonce'], 'pfvi_nonce_js' ) ) {
				return;
			}
			$productIds = array_map( 'sanitize_text_field', $_GET["productIds"] );
		}

//		get attributes exist inside config
		$config_params = $this->data_config->mapping_attr_config();

		foreach ( $productIds as $product_id ) {
			$dir = "";
			if ( class_exists( 'Polylang' ) ) {
				$lang = pll_get_post_language( $product_id );
				if ( empty( $lang ) ) {
					$lang = pll_default_language();
				}
			} else {
				$lang = PFVI_LANGUAGE;
			}
			$dir = PFVI_DIR_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'feed-' . $lang . '.xml';

			//Check config enable
			if ( ! $this->check_enable( 'schedule', $lang ) ) {
				continue;
			}

			if ( ! file_exists( $dir ) ) {
				$this->generate_xml( $lang );
			}

			$xml = simplexml_load_file( $dir );

			$merchant_config = new Product_Feed_Merchant_Config( $product_id );

			//check product exist
			$check_exist = true;

			$list_items = $xml->channel->item;
			foreach ( $list_items as $item ) {
				$item_prefix = $item->children( 'g' );
				if ( $item_prefix->id == $merchant_config->product_mapping_merchant( 'offer_id' ) ) {
					$check_exist = false;
					break;
				}
			}

			if ( $check_exist ) {
				$channel  = $xml->channel;
				$xml_item = $channel->addChild( 'item' );
				foreach ( $config_params as $key_param => $param ) {
					$param_value = $merchant_config->product_mapping_merchant( $key_param );
					if ( isset( $param_value['type'] ) && ( $param_value['type'] == 'multiple' ) ) {
						unset( $param_value['type'] );
						foreach ( $param_value as $row => $item_value ) {
							if ( isset( $param['element'] ) ) {
//						>1 dimensional
								if ( $param['separate'] === ":" ) {
//							3 dimensional
									$xml_parent = $xml_item->addChild( 'g:' . $key_param, '', 'g' );
									foreach ( $param['element'] as $key_element => $element ) {
										if ( isset( $element['element'] ) ) {
											$value    = $param_value[ $row ][ $key_element ]['value'];
											$currency = $param_value[ $row ][ $key_element ]['currency'];
											$xml_parent->addChild( 'g:' . $key_element, $value . " " . $currency, 'g' );
										} else {
											$value_child = $param_value[ $row ][ $key_element ];
											$xml_parent->addChild( 'g:' . $key_element, $value_child, 'g' );
										}
									}
								} else {
//							2 dimensional
									$value_child = '';
									foreach ( $param['element'] as $key_element => $element ) {
										$value_child .= $param_value[ $row ][ $key_element ];
										$value_child .= " ";
									}
									$xml_item->addChild( 'g:' . $key_param, sanitize_text_field( $value_child ), 'g' );
								}
							} else {
//						    1 dimensional
								if ( $key_param === 'offer_id' ) {
									$xml_item->addChild( 'g:id', $param_value[ $row ], 'g' );
								} else {
									$xml_item->addChild( 'g:' . $key_param, $param_value[ $row ], 'g' );
								}
							}
						}
					} else {
						if ( isset( $param['element'] ) ) {
//						>1 dimensional
							if ( $param['separate'] === ":" ) {
//							3 dimensional
								$xml_parent = $xml_item->addChild( 'g:' . $key_param, '', 'g' );
								foreach ( $param['element'] as $key_element => $element ) {
									if ( isset( $element['element'] ) ) {
										$value_child = "";
										foreach ( $element['element'] as $k_child => $v_child ) {
											$value_child .= $merchant_config->product_mapping_merchant( $key_param . '-' . $key_element . '-' . $k_child );
										}
										$xml_parent->addChild( 'g:' . $key_element, $value_child, 'g' );
									} else {
										$value_child = $merchant_config->product_mapping_merchant( $key_param . '-' . $key_element );
										$xml_parent->addChild( 'g:' . $key_element, $value_child, 'g' );
									}
								}
							} else {
//							2 dimensional
								$value_child = '';
								foreach ( $param['element'] as $key_element => $element ) {
									$value_child .= $merchant_config->product_mapping_merchant( $key_param . '-' . $key_element );
									$value_child .= " ";
								}

								$xml_item->addChild( 'g:' . $key_param, sanitize_text_field( $value_child ), 'g' );
							}

						} else {
//						    1 dimensional
							if ( $key_param === 'offer_id' ) {
								$xml_item->addChild( 'g:id', $param_value, 'g' );
							} else {
								$xml_item->addChild( 'g:' . $key_param, $param_value, 'g' );
							}
						}
					}
				}
				$xml->asXML( $dir );
			}
		}
	}

	/**
	 * Handle data to push
	 *
	 * @since    1.0.0
	 */
	public function push_sheet() {
		$productIds = [];
		if ( ! empty( $product_ids ) ) {
			$productIds = array_map( 'sanitize_text_field', $product_ids );
		}
		if ( isset( $_GET["productIds"] ) ) {
			if ( ! isset( $_GET['_ajax_nonce'] ) || ! wp_verify_nonce( $_GET['_ajax_nonce'], 'pfvi_nonce_js' ) ) {
				return;
			}
			$productIds = array_map( 'sanitize_text_field', $_GET["productIds"] );
		}

//		get attributes exist inside config
		$config_params = $this->data_config->mapping_attr_config();

		$languages = array();
		if ( class_exists( 'Polylang' ) ) {
			$languages = pll_languages_list();
		} else {
			$languages = array( PFVI_LANGUAGE );
		}

		$request_body = array();
		foreach ( $languages as $language ) {
			if ( $this->check_enable( 'sheet', $language ) ) {
				$request_body[ $language ] = array();
			}
		}

//		sort product with language
		foreach ( $productIds as $product_id ) {
			//Get language of product
			$lang_product = $this->get_lang_product( $product_id );

			//Check config enable
			if ( ! $this->check_enable( 'sheet', $lang_product ) ) {
				continue;
			}

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

			array_push( $request_body[ $lang_product ], $product_arr );
		}

		$sheet_obj       = new PFVI_Sheet();
		$notice_response = [];
		foreach ( $request_body as $lang => $request_lang ) {
			$sheet_obj->update_title( $lang );

			if ( ! empty( $request_lang ) ) {
				$request_data = $sheet_obj->get_data_not_exist( $lang, $request_lang );
				$response     = $sheet_obj->append( $lang, $request_data );
				array_push( $notice_response, $response );
			}
		}

		wp_send_json( $notice_response );
	}

	public function push_merchant() {
		$productIds = [];
		if ( ! empty( $product_ids ) ) {
			$productIds = array_map( 'sanitize_text_field', $product_ids );
		}
		if ( isset( $_GET["productIds"] ) ) {
			if ( ! isset( $_GET['_ajax_nonce'] ) || ! wp_verify_nonce( $_GET['_ajax_nonce'], 'pfvi_nonce_js' ) ) {
				return;
			}
			$productIds = array_map( 'sanitize_text_field', $_GET["productIds"] );
		}

//		get attributes exist inside config
		$config_params = $this->data_config->mapping_attr_config();
		$merchant_id   = $this->data_config->get_params( "merchant_id" );
		$config_apis   = $this->data_config->get_params( "api" );

		$languages = array();
		if ( class_exists( 'Polylang' ) ) {
			$languages = pll_languages_list();
		} else {
			$languages = array( PFVI_LANGUAGE );
		}

		$request_body = array();
		foreach ( $languages as $language ) {
			if ( $this->check_enable( 'api', $language ) ) {
				$request_body[ $language ] = array();
			}
		}

		foreach ( $productIds as $key_id => $product_id ) {
			//Get language of product
			$lang_product = $this->get_lang_product( $product_id );

			//Check config enable
			if ( ! $this->check_enable( 'api', $lang_product ) ) {
				continue;
			}

			$merchant_config = new Product_Feed_Merchant_Config( $product_id );
			$meta_data       = array();

			foreach ( $config_params as $key_param => $param ) {
				$key_product_request               = $this->data_config->underscoreToCamelCase( $key_param );
				$meta_data[ $key_product_request ] = $merchant_config->product_mapping_merchant( $key_param );
				if ( empty( $meta_data[ $key_product_request ] ) ) {
					$meta_data[ $key_product_request ] = $merchant_config->show_structure_product_empty( $key_param );
				}
				if ( isset( $meta_data[ $key_product_request ]['type'] ) && ( $meta_data[ $key_product_request ]['type'] == 'multiple' ) ) {
					unset( $meta_data[ $key_product_request ]['type'] );
				}
			}

			$meta_data["channel"]         = $config_apis[ $lang_product ]['channel'];
			$meta_data["contentLanguage"] = $lang_product;
			$meta_data["targetCountry"]   = $config_apis[ $lang_product ]['api_country'];

			$request_product               = array();
			$request_product["batchId"]    = $key_id;
			$request_product["merchantId"] = $merchant_id;
			$request_product["method"]     = "insert";
			$request_product["product"]    = $meta_data;

			array_push( $request_body[ $lang_product ], $request_product );
		}

		$api_obj = new PFVI_Api();
		foreach ( $request_body as $lang => $request_lang ) {
			if ( ! empty( $request_lang ) ) {
				$api_obj->insert( $request_body[ $lang ] );
			}
		}
	}

	public function get_all_product() {
		$language = sanitize_text_field($_GET['language']);

		$products = get_posts( array(
			'post_type'   => 'product',
			'numberposts' => - 1,
			'post_status' => 'publish',
			'fields'      => 'ids',
		) );

		$result = array();
		if ( class_exists( 'Polylang' ) ) {
			$language_default = pll_default_language();
			if ( $language_default === $language ) {
				foreach ( $products as $product ) {
					$product_language = pll_get_post_language( $product );
					if ( $product_language === $language || empty( $product_language ) ) {
						array_push( $result, $product );
					}
				}
			} else {
				foreach ( $products as $product ) {
					$product_language = pll_get_post_language( $product );
					if ( $product_language === $language ) {
						array_push( $result, $product );
					}
				}
			}
		} else {
			foreach ( $products as $product ) {
				array_push( $result, $product );
			}
		}

		wp_send_json_success($result);

//error_log( print_r($result,true));
//		  print_r($result) ;
//		wp_send_json($result);
//		die();
	}

	/**
	 * Add meta box into Custom product page
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes_merchant() {
		$warning = "warning";
		add_settings_error( '', '', $warning, 'warning' );
		add_meta_box(
			PFVI_PREFIX . 'custom_product_meta_box',
			'Google product feed',
			array( $this, 'add_custom_content_meta_box' ),
			'product',
			'normal',
			'low'
		);
	}

	public function add_custom_content_meta_box( $post ) {
		$attributes_all     = $this->data_config->get_attributes( "" );
		$attributes_setting = $this->data_config->get_params( "attributes" );

		$attributes = array();

		foreach ( $attributes_setting as $attribute ) {
			$attributes[ $attribute ] = $attributes_all[ $attribute ];
		}

		$merchant_config = new Product_Feed_Merchant_Config( $post->ID );
		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template(
				'custom-product-admin-display.php',
				array(
					'attributes'      => $attributes,
					'merchant_config' => $merchant_config,
				),
				'',
				PFVI_ADMIN_TEMPLATES
			);
		}
	}

	/**
	 * Save meta box inside Custom product page
	 *
	 * @since    1.0.0
	 */
	public function save_custom_content_meta_box( $post_id ) {
		$attributes_all  = $this->data_config->get_params( "attributes" );
		$merchant_config = new Product_Feed_Merchant_Config( $post_id );

		$attributes = array();
		foreach ( $attributes_all as $attr ) {
			$attributes[ $attr ] = $this->data_config->get_attributes( $attr );
		}

		$meta_values = $this->save_custom_product( $attributes, [], '' );

		update_post_meta(
			$post_id,
			$merchant_config->get_meta_key(),
			$meta_values
		);
	}

	private function save_custom_product( $attributes, $result, $prefix ) {
		foreach ( $attributes as $key => $value ) {
			$name = $prefix . $key;
			if ( $value['type'] === 'element' ) {
				$name           .= '-';
				$result[ $key ] = $this->save_custom_product( $value['element'], $result[ $key ], $name );
			} else {
				$result[ $key ] = sanitize_text_field($_POST[ $name ] ?? "");
			}
		}

		return $result;
	}

	public function update_product_merchant( $product_id ) {
		$this->updateXml( $product_id );
		$this->updateSheet( $product_id );
		$this->updateMerchant( $product_id );
	}

	public function updateXml( $product_id ) {
		$dir = "";
		if ( class_exists( 'Polylang' ) ) {
			$lang = pll_get_post_language( $product_id );
			if ( empty( $lang ) ) {
				$lang = pll_default_language();
			}
		} else {
			$lang = PFVI_LANGUAGE;
		}

		$dir = PFVI_DIR_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'feed-' . $lang . '.xml';

		if ( ! file_exists( $dir ) ) {
			$this->generate_xml( $lang );
		}

		$xml = simplexml_load_file( $dir );

		$merchant_config = new Product_Feed_Merchant_Config( $product_id );
		$list_items      = $xml->channel->item;
		$id              = $merchant_config->product_mapping_merchant( 'offer_id' );

		$count_item = 0;
		foreach ( $list_items as $item ) {
			$item_prefix = $item->children( 'g' );
			if ( $item_prefix->id == $id ) {
				unset( $xml->channel->item[ $count_item ] );
				break;
			}
			$count_item ++;
		}
		file_put_contents( $dir, $xml->asXML() );
		$this->push_xml( [ $product_id ] );
	}

	public function updateSheet( $product_id ) {
		//Get language of product
		$lang_product = $this->get_lang_product( $product_id );

		//Check config enable
		if ( $this->check_enable( 'sheet', $lang_product ) ) {
			$this->data_config->get_token();
			$sheet_obj = new PFVI_Sheet();
			$sheet_obj->update_title( $lang_product );
			$sheet_obj->update_product( $lang_product, $product_id );
		}
	}

	public function updateMerchant( $product_id ) {
		//Get language of product
		$lang_product = $this->get_lang_product( $product_id );

		//Check config enable
		if ( $this->check_enable( 'api', $lang_product ) ) {
			$this->data_config->get_token();
			$api_obj = new PFVI_Api();
			$api_obj->update( $lang_product, $product_id );
		}
	}

	public function get_lang_product( $product_id ) {
		$lang_product = "";
		if ( class_exists( 'Polylang' ) ) {
			$lang_product = pll_get_post_language( $product_id );
			if ( empty( $lang_product ) ) {
				$lang_product = pll_default_language();
			}
		} else {
			$lang_product = PFVI_LANGUAGE;
		}

		return $lang_product;
	}

	public function get_client() {
		$this->data_config->get_token();
	}

	public function product_updated_messages( $messages ) {
		$messages['post'][4] = 'Just published a post custom text';
		$messages['post'][1] = 'Just published a post custom text';

		return $messages;
	}

	public function create_sheet() {
		if ( ! isset( $_GET['_ajax_nonce'] ) || ! wp_verify_nonce( $_GET['_ajax_nonce'], 'pfvi_nonce_js' ) ) {
			return;
		}
		if ( $this->data_config->check_token_expire() ) {
			$this->data_config->get_token();
		}

		$language = $_GET['language'] ?? "";
		$sheet_obj = new PFVI_Sheet();
		$create    = $sheet_obj->create($language);
		echo json_encode( $create );
		die();
	}
}
