<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/includes
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
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/includes
 * @author     multidots <jaydeep.rami@multidots.in>
 */
class Wp_Email_Base_Logo_Change {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Email_Base_Logo_Change_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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

		$this->plugin_name = 'wp-email-base-logo-change';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Email_Base_Logo_Change_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Email_Base_Logo_Change_i18n. Defines internationalization functionality.
	 * - Wp_Email_Base_Logo_Change_Admin. Defines all hooks for the admin area.
	 * - Wp_Email_Base_Logo_Change_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-email-base-logo-change-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-email-base-logo-change-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-email-base-logo-change-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-email-base-logo-change-public.php';

		$this->loader = new Wp_Email_Base_Logo_Change_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Email_Base_Logo_Change_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Email_Base_Logo_Change_i18n();

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

		global $wpdb;
		$plugin_admin = new Wp_Email_Base_Logo_Change_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin,'themeslug_enqueue_script', 10 );
		
		$this->loader->add_filter('admin_init', $plugin_admin , 'register_fields',10);
		
		$check_enable_or_not = get_option( 'WP_Email_Base_Logo_Change_Setting_Enable_Or_Not', '' );  
		if( !empty( $check_enable_or_not ) && $check_enable_or_not === 'yes'){
			$this->loader->add_action('login_head',$plugin_admin,'custom_login_logo');
		}
		$this->loader->add_action('wp_ajax_get_profile_picture_by_email', $plugin_admin ,'get_profile_picture_by_email' ); 
		$this->loader->add_action('wp_ajax_nopriv_get_profile_picture_by_email', $plugin_admin ,'get_profile_picture_by_email' );	

		$this->loader->add_action('admin_init', $plugin_admin, 'welcome_wp_email_base_logo_change_screen_do_activation_redirect');
		$this->loader->add_action('wp_email_base_logo_change_about', $plugin_admin, 'wp_email_base_logo_change_about');
		$this->loader->add_action('admin_menu', $plugin_admin, 'welcome_pages_screen_wp_email_base_logo');
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'welcome_screen_wp_email_base_logo_remove_menus', 999 );
		
		/**Custom pointer hook**/
        $this->loader->add_action('admin_print_footer_scripts', $plugin_admin, 'custom_eblc_pointers_footer');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Email_Base_Logo_Change_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		if( !in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) {  
			$this->loader->add_filter('woocommerce_paypal_args',  $plugin_public,'paypal_bn_code_filter_eblc', 99, 1);
		}
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
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Email_Base_Logo_Change_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
