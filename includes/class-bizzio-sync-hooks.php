<?php

namespace BizzioSync;

class Bizzio_Sync_Hooks {

	public function __construct() {
		add_action( 'bizzio_custom_hook', array( $this, 'custom_hook_action' ) );
	}

	public function custom_hook_action() {
		add_action( 'bizzio_customized_menu', array( $this, 'handle_custom_hook_one' ) );
		/* Example custom action */
		Bizzio_Sync_Logger::log( 'Bizzio Customized Enable' );
		error_log( 'Bizzio Customized Enable' );
	}
}
