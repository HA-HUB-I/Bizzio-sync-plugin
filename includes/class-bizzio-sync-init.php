<?php

namespace BizzioSync;

/**
 * Singleton class to initialize and load all necessary components for Bizzio Sync.
 * This class is responsible for loading the logger, admin functions,
 * and hooks necessary for the Bizzio Sync plugin to function correctly.
 */
class Bizzio_Sync_Init {
	/**
	 * Holds the single instance of the class.
	 *
	 * @var Bizzio_Sync_Init|null
	 */

	private static $instance = null;
	/**
	 * Retrieves the single instance of the class.
	 *
	 * If the instance is not already created, it creates a new one.
	 *
	 * @return Bizzio_Sync_Init The single instance of the class.
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Private constructor to prevent multiple instances.
	 * Loads the necessary components such as the logger, admin functions,
	 * and hooks required by the Bizzio Sync plugin.
	 */
	private function __construct() {
		/* Load the logger */
		require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-logger.php';

		/* Load admin functions */
		require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-admin.php';

		/* Load hooks */
		require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-hooks.php';

		/* Initialize admin and hooks */
		if ( is_admin() ) {
			new Bizzio_Sync_Admin();
		}

		new Bizzio_Sync_Hooks();
	}
}
