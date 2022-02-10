<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Feed
 * @subpackage Product_Feed/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Product_Feed
 * @subpackage Product_Feed/includes
 * @author     Your Name <email@example.com>
 */
class Product_Feed {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Product_Feed_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $product_feed The string used to uniquely identify this plugin.
	 */
	protected $product_feed;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'VI_PRODUCT_FEED_VERSION' ) ) {
			$this->version = VI_PRODUCT_FEED_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->product_feed = 'vi-product-feed';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Product_Feed_Loader. Orchestrates the hooks of the plugin.
	 * - Product_Feed_i18n. Defines internationalization functionality.
	 * - Product_Feed_Admin. Defines all hooks for the admin area.
	 * - Product_Feed_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product-feed-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product-feed-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-product-feed-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		$this->loader = new Product_Feed_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Product_Feed_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Product_Feed_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/define.php';
		$plugin_admin = new Product_Feed_Admin( $this->get_product_feed(), $this->get_version() );

		//action hook
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_localize_script' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_admin' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_merchant' );
		$this->loader->add_action( 'save_post_product', $plugin_admin, 'save_custom_content_meta_box' );
		$this->loader->add_action( 'woocommerce_update_product', $plugin_admin, 'update_product_merchant' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'push_xml', $plugin_admin, 'push_xml' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'push_sheet', $plugin_admin, 'push_sheet' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'push_merchant', $plugin_admin, 'push_merchant' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'clear_data_xml', $plugin_admin, 'clear_xml' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'generate_file_xml', $plugin_admin, 'generate_xml' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'get_all_product', $plugin_admin, 'get_all_product' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'get_client', $plugin_admin, 'get_client' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'create_sheet', $plugin_admin, 'create_sheet' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'wizard_credential', $plugin_admin, 'wizard_credential' );
		$this->loader->add_action( 'wp_ajax_' . PFVI_PREFIX . 'wizard_data', $plugin_admin, 'wizard_data' );
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'activation_redirect' );
//		$this->loader->add_action('updated_post_meta', $plugin_admin, 'product_updated');
		//		filter hook
		$this->loader->add_filter( 'bulk_actions-edit-product', $plugin_admin, 'add_option_bulk_action_merchant' );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'setting_link', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_product_feed() {
		return $this->product_feed;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Product_Feed_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
}
