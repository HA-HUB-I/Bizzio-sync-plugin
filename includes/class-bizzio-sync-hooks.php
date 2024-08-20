<?php

namespace BizzioSync;

class Bizzio_Sync_Hooks {

	public function __construct() {
		add_action( 'bizzio_custom_hook', array( $this, 'custom_hook_action' ) );
		add_action('plugins_loaded',  array( $this, 'bizzio_sync_load_textdomain' ) );
	}

	public function custom_hook_action() {
		add_action( 'bizzio_customized_menu', array( $this, 'handle_custom_hook_one' ) );
		/* Example custom action */
		Bizzio_Sync_Logger::log (_( 'Bizzio Customized Enable' , 'bizzio-sync' ));
		/* error_log( 'Bizzio Customized Enable' ); */
	}

	public function bizzio_sync_load_textdomain() {
		load_plugin_textdomain('bizzio-sync', false, BIZZIO_SYNC_URL . '/languages/');
	}
}


// Set REST API prefix
add_filter('rest_url_prefix', function () {
    return defined('REST_API_PREFIX') ? REST_API_PREFIX : 'wp-json2';
});

// Define the URL filter
add_filter('POST_URL_REQUEST', function ($url) {
    return defined('POST_URL_REQUEST') ? POST_URL_REQUEST : $url;
});
