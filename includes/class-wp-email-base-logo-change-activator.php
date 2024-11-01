<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Email_Base_Logo_Change
 * @subpackage Wp_Email_Base_Logo_Change/includes
 * @author     multidots <jaydeep.rami@multidots.in>
 */
class Wp_Email_Base_Logo_Change_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;	
		set_transient( '_welcome_screen_wp_email_base_logo_activation_redirect_data', true, 30 );
	}

}
