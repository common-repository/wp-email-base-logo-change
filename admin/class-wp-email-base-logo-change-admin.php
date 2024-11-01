<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/admin
 * @author     multidots <jaydeep.rami@multidots.in>
 */
class Wp_Email_Base_Logo_Change_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Email_Base_Logo_Change_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Email_Base_Logo_Change_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-email-base-logo-change-admin.css', array( 'wp-jquery-ui-dialog' ), $this->version, 'all' );
		wp_enqueue_style( 'wp-pointer' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Email_Base_Logo_Change_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Email_Base_Logo_Change_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-email-base-logo-change-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wp-pointer' );
	}

	/**
	 * Function For Register Custom Field
	 *
	 *
	 */


	function themeslug_enqueue_script() {
		wp_enqueue_script( "jquery" );
	}


	function register_fields() {

		register_setting( 'general', 'WP_Email_Base_Logo_Change_Setting_Enable_Or_Not', 'esc_attr' );
		add_settings_field( 'WP_Email_Base_Logo_Change_Setting_Enable_Or_Not', '<label for="WordPress Email Base Logo Change">' . __( 'WordPress Email Base Logo Change', 'wp-email-base-logo-change' ) . '</label>', array(
			$this,
			'custom_add_wp_setting_field',
		), 'general' );
	}

	/**
	 * Function For Custom Field Html
	 *
	 *
	 */
	function custom_add_wp_setting_field() {
		$value = get_option( 'WP_Email_Base_Logo_Change_Setting_Enable_Or_Not', '' );
		if ( ! empty( $value ) && $value === 'yes' ) {
			$checkbox = "checked";
		} else {
			$checkbox = "";
		}

		?>
		<input type="checkbox" id="WP_Email_Base_Logo_Change_Setting_Enable_Or_Not" name="WP_Email_Base_Logo_Change_Setting_Enable_Or_Not" value="yes" <?php echo
		esc_html( $checkbox ); ?>/>
		<?php
	}

	/**
	 * Function  For custom logo image
	 */
	function custom_login_logo() {
		$url      = get_bloginfo( 'template_directory' );
		$site_url = site_url( 'wp-admin/admin-ajax.php' );
		?>
		<style type="text/css">
			.login h1 a {
				background-size: cover !important;
				border-radius: 15px !important;
				width: 85px !important;
			}
		</style>

		<script type="text/javascript">
					jQuery( document ).ready( function() {
						jQuery( 'body' ).on( 'focusout', '#user_login', function() {
							var user_email = jQuery( '#user_login' ).val();
							jQuery.ajax( {
								type: 'POST',
								url: '<?php echo esc_js( $site_url ); ?>',
								data: ({
									action: 'get_profile_picture_by_email',
									user_email: user_email
								}),
								success: function( data ) {
									jQuery( '.login h1 a' ).css( 'background-image', 'url(' + data + ')' );
									jQuery( '.login h1 a' ).css( 'box-shadow', '0 0 0 1px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.15) inset, 0 1px 1px 1px rgba(42, 47, 50, 0.5) !important' );
								}
							} );
						} );
					} );
		</script>
	<?php }

	function get_profile_picture_by_email() {
		$user_email = isset( $_POST['user_email'] ) ? esc_attr( $_POST['user_email'] ) : $_POST['user_email'];
		$grav_url   = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user_email ) ) );
		echo $grav_url;
		die();
	}

	// Function for welocme screen page
	public function welcome_wp_email_base_logo_change_screen_do_activation_redirect() {

		if ( ! get_transient( '_welcome_screen_wp_email_base_logo_activation_redirect_data' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_welcome_screen_wp_email_base_logo_activation_redirect_data' );

		// if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect( add_query_arg( array( 'page' => 'wp-email-base-logo-about&tab=about' ), admin_url( 'index.php' ) ) );
	}

	public function welcome_pages_screen_wp_email_base_logo() {
		add_dashboard_page(
			'Wordpress-Email-Base-Logo-Change Dashboard', 'Wordpress Email Base Logo Change Dashboard', 'read', 'wp-email-base-logo-about', array(
				&$this,
				'welcome_screen_content_wp_email_base_logo_change_counter',
			)
		);
	}

	public function welcome_screen_content_wp_email_base_logo_change_counter() { ?>
		<div class="wrap about-wrap">
			<h1 style="font-size: 2.1em;"><?php printf( __( 'Welcome to Wordpress Email Base Logo Change', 'wp-email-base-logo-change' ) ); ?></h1>
			<div class="about-text woocommerce-about-text">
				<?php
				$message = '';
				printf( __( '%s This plugin will gives you the power to convert the boring WordPress login page logo with personalized logo.', 'wp-email-base-logo-change' ), $message, $this->version );
				?>
				<img class="version_logo_img" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/wp-email-base-logo-change.png' ); ?>">
			</div>
			<?php
			$setting_tabs_wc = apply_filters( 'wp_email_base_logo_fields_setting_tab', array( "about" => "Overview", "other_plugins" => "Checkout our other plugins" ) );
			$current_tab_wc  = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
			$aboutpage       = isset( $_GET['page'] );
			?>
			<h2 id="wp-email-base-logo-wrapper" class="nav-tab-wrapper">
				<?php
				foreach ( $setting_tabs_wc as $name => $label ) {
					?>
					<a href="<?php echo home_url( 'wp-admin/index.php?page=wp-email-base-logo-about&tab=' . $name ); ?>"
					   class="nav-tab <?php echo( $current_tab_wc == $name ? 'nav-tab-active' : '' ); ?>">
						<?php echo esc_attr( $label ); ?>
					</a>
					<?php
				}
				?>
			</h2>
			<?php
			foreach ( $setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue ) {
				switch ( $setting_tabkey_wc ) {
					case $current_tab_wc:
						do_action( 'wp_email_base_logo_change_' . $current_tab_wc );
						break;
				}
			}
			?>
			<hr />
			<div class="return-to-dashboard">
				<a href="<?php echo home_url( '/wp-admin/options-general.php' ); ?>"><?php _e( 'Go to Wordpress Email Base Logo Change Settings', 'wp-email-base-logo-change' ); ?></a>
			</div>
		</div>
	<?php }

	/**
	 * Extra flate rate overview welcome page content function
	 *
	 */
	public function wp_email_base_logo_change_about() {
		$current_user = wp_get_current_user(); ?>
		<div class="changelog">
			</br>
			<style type="text/css">
				p.weblc_overview {
					max-width: 100% !important;
					margin-left: auto;
					margin-right: auto;
					font-size: 15px;
					line-height: 1.5;
				}

				.weblc_ul ul li {
					margin-left: 3%;
					list-style: initial;
					line-height: 23px;
				}
			</style>
			<div class="changelog about-integrations">
				<div class="wc-feature feature-section col three-col">
					<div>
						<p class="weblc_overview"><?php _e( 'WordPress Email Base Logo Change plugin is easy to use plugin. It gives you the power to convert the boring WordPress login page logo with personalized login logo from your WordPress Avatar. This plugin helps to improve your brand authority and also helps to improve user experience and you can change wordpress default logo and display your WordPress Avatar. It provides you nice user interface while doing login or register via WordPress backend on your site.', 'wp-email-base-logo-change' ); ?></p>

						<p class="weblc_overview"><strong>What is used for this plugin? </strong></p>
						<p class="weblc_overview"><?php _e( 'WordPress Email Base Logo Change plugin is for these who like to update WordPress admin logo based on their email address.', 'wp-email-base-logo-change' ); ?></p>

						<p class="weblc_overview"><strong>Plugin Functionality: </strong></p>
						<div class="weblc_ul">
							<ul>
								<li>Easy to use.</li>
								<li>Provide nice user experience.</li>
								<li>Change WordPress logo based on their email address.</li>
								<li>Change WordPress logo and use WordPress Avatar.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Remove the Extra flate rate menu in dashboard
	 *
	 */
	public function welcome_screen_wp_email_base_logo_remove_menus() {
		remove_submenu_page( 'index.php', 'wp-email-base-logo-about' );
	}

	public function custom_eblc_pointers_footer() {
		$admin_pointers = custom_eblc__pointers_admin_pointers();
		?>
		<script type="text/javascript">
					/* <![CDATA[ */
					(function( $ ) {
			  <?php
			  foreach ( $admin_pointers as $pointer => $array ) {
			  if ( $array['active'] ) {
			  ?>
						$( '<?php echo esc_js( $array['anchor_id'] ); ?>' ).pointer( {
							content: '<?php echo esc_js( $array['content'] ); ?>',
							position: {
								edge: '<?php echo esc_js( $array['edge'] ); ?>',
								align: '<?php echo esc_js( $array['align'] ); ?>'
							},
							close: function() {
								$.post( ajaxurl, {
									pointer: '<?php echo esc_js( $pointer ); ?>',
									action: 'dismiss-wp-pointer'
								} );
							}
						} ).pointer( 'open' );
			  <?php
			  }
			  }
			  ?>
					})( jQuery );
					/* ]]> */
		</script>
		<?php
	}
}


function custom_eblc__pointers_admin_pointers() {

	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$version   = '1_0'; // replace all periods in 1.0 with an underscore
	$prefix    = 'custom_eblc_pointers' . $version . '_';

	$new_pointer_content = '<h3>' . __( 'Welcome to WordPress Email Base Logo Change' ) . '</h3>';
	$new_pointer_content .= '<p>' . __( 'WordPress Email Base Logo Change plugin is easy to use. This plugin gives you the power to convert the boring WordPress login page logo with personalized logo. This plugin helps to improve your brand authority and also helps to improve user experience and you can change wordpress default logo and display your WordPress Avatar. It provides you nice user interface while doing login or register via WordPress backend on your site.' ) . '</p>';

	return array(
		$prefix . 'assb_notice_view' => array(
			'content'   => $new_pointer_content,
			'anchor_id' => '#adminmenu',
			'edge'      => 'left',
			'align'     => 'left',
			'active'    => ( ! in_array( $prefix . 'assb_notice_view', $dismissed ) ),
		),
	);

}