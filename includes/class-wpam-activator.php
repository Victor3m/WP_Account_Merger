<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/Victor3m
 * @since      1.0.0
 *
 * @package    Wpam
 * @subpackage Wpam/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpam
 * @subpackage Wpam/includes
 * @author     Victor3m <jhester911@gmail.com>
 */
class Wpam_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    add_option( 'wpam_select_target', true );
    add_option( 'wpam_select_all', true );
	}

}
