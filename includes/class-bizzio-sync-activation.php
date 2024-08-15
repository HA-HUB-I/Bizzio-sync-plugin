<?php

namespace BizzioSync;

class Bizzio_Sync_Activation {

	public static function activate() {
		/* Ensure required conditions are met */
		if ( ! function_exists( 'add_action' ) ) {
			wp_die( 'WordPress environment is not properly initialized.' );
		}

		/* Example: Ensure a required PHP version */
		if ( version_compare( PHP_VERSION, '7.2', '<' ) ) {
			wp_die( 'This plugin requires PHP version 7.2 or higher.' );
		}

		/* Example: Add a default option during activation */
		if ( false === get_option( 'bizzio_enable_sync' ) ) {
			add_option( 'bizzio_enable_sync', 0 );
		}
	}

	public static function deactivate() {
		/*
		 Cleanup or reset any settings, if necessary
		Example: Optionally remove the option on deactivation
		delete_option('bizzio_enable_sync'); */
	}
}
