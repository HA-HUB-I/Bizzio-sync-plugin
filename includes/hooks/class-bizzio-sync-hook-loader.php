<?php

namespace BizzioSync;

use WP_Admin_Bar;

class Bizzio_Sync_Hook_Loader {

	/**
	 * Constructor to initialize the hook registration.
	 */
	public function __construct() {
		/* Register hooks */
		$this->register_hooks();
	}

	/**
	 * Registers necessary hooks conditionally based on the sync option.
	 */
	private function register_hooks(): void {
		/* Conditionally register the toolbar removal hook */
		if ( $this->is_sync_enabled() ) {
			add_action( 'admin_bar_menu', array( $this, 'remove_admin_toolbar_items' ), 999 );
			add_action( 'wp_dashboard_setup', array( $this, 'remove_admin_widget_items' ) );
			add_action('admin_init', [$this, 'remove_admin_menu_items']);
		}
	}

	/**
     * Remove unnecessary menu pages from the WordPress admin.
     */
    public function remove_admin_menu_items(): void {
        if (!current_user_can('manage_options')) {
            return; // Ensure only users with appropriate permissions can see these changes.
        }

        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('edit.php'); // Posts
        remove_menu_page('index.php'); // Dashboard

        // Uncomment lines as needed
        // remove_menu_page('edit.php?post_type=page'); // Pages
        // remove_menu_page('upload.php'); // Media
    }


	/**
	 * Checks if the sync option is enabled.
	 *
	 * @return bool True if sync is enabled, false otherwise.
	 */
	private function is_sync_enabled(): bool {
		/* Check if 'bizzio_enable_sync' option is enabled */
		return get_option( 'bizzio_enable_sync', false );
	}

	
	/**
	 * Removes specified items from the admin toolbar.
	 *
	 * @param WP_Admin_Bar $menu The WordPress Admin Bar instance.
	 */
	public function remove_admin_toolbar_items( WP_Admin_Bar $menu ): void {
		/* Correct type-hinting with use statement */
		$nodes_to_remove = array(
			'archive',
			'comments',
			'customize',
			'dashboard',
			'edit',
			'menus',
			'new-content',
			'search',
			'themes',
			'updates',
			'view-site',
			'view',
			'widgets',
			'wp-logo',
			'plugins',
		);

		foreach ( $nodes_to_remove as $node ) {
			$menu->remove_node( $node );
		}
	}

	/**
	 * Removes specified dashboard widgets.
	 */
	public function remove_admin_widget_items(): void {
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); /* Activity */
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); /* At a Glance */
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );  /* Site Health Status */
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); /* WordPress Events and News */
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); /* Quick Draft */
	}

}
