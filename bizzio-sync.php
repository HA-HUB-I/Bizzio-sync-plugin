<?php
/**
 * Plugin Name: Bizzio Sync
 * Plugin URI:  https://gencloud.bg/
 * Description: Bizzio WP Plugin
 * Version:     0.0.1
 * Author:      GCLD TEAM
 * Author URI:  https://gcld-workspace.slack.com/
 * Text Domain: Bizzio-sync
 *
 *  @package tag:
 * Domain Path: //
 */

namespace BizzioSync;

/**
 * Exit if accessed directly
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*  Define plugin constants */
define( 'BIZZIO_SYNC_PATH', plugin_dir_path( __FILE__ ) );
define( 'BIZZIO_SYNC_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include necessary files
 */

require_once BIZZIO_SYNC_PATH . 'includes/hooks/class-bizzio-sync-hook-loader.php';

/*
 * require_once BIZZIO_SYNC_PATH . 'includes/hooks/class-bizzio-sync-core-hooks.php';
 *  require_once BIZZIO_SYNC_PATH . 'includes/hooks/class-bizzio-sync-custom-hooks.php';
*/

require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-init.php';
require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-activation.php';
require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-custom-login.php';
require_once BIZZIO_SYNC_PATH . 'includes/class-bizzio-sync-api-hooks.php';

/* Register activation and deactivation hooks */
register_activation_hook( __FILE__, array( \BizzioSync\Bizzio_Sync_Activation::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \BizzioSync\Bizzio_Sync_Activation::class, 'deactivate' ) );

/**
 * Initialize the plugin
 * BizzioSync\BizzioSyncInit::get_instance();
 * */

/*  Initialize the plugin */
$init = \BizzioSync\Bizzio_Sync_Init::get_instance();
new \BizzioSync\BizzioSyncApiHooks();
$hook_loader  = new \BizzioSync\Bizzio_Sync_Hook_Loader();
$custom_login = new \BizzioSync\Bizzio_Custom_Login();
