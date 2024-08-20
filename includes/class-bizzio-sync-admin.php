<?php

namespace BizzioSync;

/**
 * Class responsible for handling the admin interface and related hooks for the Bizzio Sync plugin.
 */
class Bizzio_Sync_Admin {

	/**
	 * Constructor method. Adds necessary WordPress hooks.
	 */
	public function __construct() {
		/* Hook to add admin menu */
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		/* 
		*add_action( 'admin_menu', array( $this, 'conditionally_remove_menu_pages' ) );
		 */
		/* Hook to save options */
		add_action( 'admin_post_bizzio_save_options', array( $this, 'save_options' ) );

		/* Enqueue admin styles */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

		add_action( 'admin_post_clear_logs', array( $this, 'handle_clear_logs' ) );
	}

	/**
	 * Adds the main admin menu and submenu pages.
	 */
	public function add_admin_menu() {
		add_menu_page(
			'Bizzio Sync',
			'Bizzio Sync Settings',
			'manage_options',
			'bizzio-sync',
			array( $this, 'admin_page' ),
			'dashicons-update',
			100
		);

		/* Add a submenu for logs */
		add_submenu_page(
			'bizzio-sync',
			'Bizzio Sync Logs',
			'Logs',
			'manage_options',
			'bizzio-sync-logs',
			array( $this, 'logs_page' )
		);
	}

	/**
	 * Checks if sync is enabled.
	 *
	 * @return bool True if sync is enabled, false otherwise.
	 */
	private function is_sync_enabled() {
		/* Check if 'bizzio_enable_sync' option is enabled */
		return get_option( 'bizzio_enable_sync', false );
	}

	/**
	 * Conditionally removes menu pages based on the sync status.
	 */

	/* public function conditionally_remove_menu_pages() {
		if ( $this->is_sync_enabled() ) {


		}
	} */


	/**
	 * Renders the main admin page.
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1>Bizzio Sync Settings</h1>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="bizzio_save_options">
				<?php wp_nonce_field( 'bizzio_save_options_verify' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Customized</th>
						<td><input type="checkbox" name="enable_sync" value="1" <?php checked( get_option( 'bizzio_enable_sync' ), 1 ); ?> /></td>
					</tr>
					<tr valign="top">
						<th scope="row">API PREFIX</th>
						<td><?php 
						 $current_prefix = apply_filters('rest_url_prefix' , 'wp-json');
						echo  esc_html($current_prefix); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">Enable Logging</th>
						<td><input type="checkbox" name="enable_logging" value="1" <?php checked( get_option( 'bizzio_enable_logging' ), 1 ); ?> /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function logs_page() {
		?>
		<div class="wrap">
			<h1>Bizzio Sync Logs</h1>
			<table class="widefat">
				<thead>
					<tr>
						<th>Date</th>
						<th>Log Message</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$logs = get_option( 'bizzio_sync_logs', array() );
					if ( ! empty( $logs ) ) {
						foreach ( $logs as $log ) {
							echo '<tr>';
							echo '<td>' . esc_html( $log['date'] ) . '</td>';
							echo '<td>' . esc_html( $log['message'] ) . '</td>';
							echo '</tr>';
						}
					} else {
						echo __('<tr><td colspan="2">No logs available.</td></tr>' , 'bizzio-sync');
					}
					?>
				</tbody>
			</table>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="clear_logs">
				<input type="submit" value="Clear All Logs" class="button button-danger" onclick="return confirm('Are you sure you want to clear all logs?');">
			</form>
		</div>
		<?php
	}

	/**
	 * Handles the saving of options from the admin page.
	 */
	public function save_options() {
		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';

		/* Verify the nonce */
		if ( ! wp_verify_nonce( $nonce, 'bizzio_save_options_verify' ) ) {
			wp_die( 'Invalid nonce' );
		}

		/* Unslash and sanitize input */
		$enable_sync    = isset( $_POST['enable_sync'] ) ? 1 : 0;
		$enable_logging = isset( $_POST['enable_logging'] ) ? 1 : 0;

		update_option( 'bizzio_enable_sync', $enable_sync );
		update_option( 'bizzio_enable_logging', $enable_logging );

		if ( $enable_sync ) {
			do_action( 'bizzio_custom_hook' );
		}

		wp_safe_redirect( esc_url( admin_url( 'admin.php?page=bizzio-sync&updated=true' ) ) );
		exit;
	}

	/**
	 * Enqueues admin-specific styles.
	 */
	public function enqueue_admin_styles() {
		/* Add a version number to the stylesheet for cache busting */
		$version = '1.0.0'; /*  You can dynamically generate this based on the file's modification time. */
		wp_enqueue_style( 'bizzio-sync-admin', BIZZIO_SYNC_URL . 'assets/css/bizzio-sync-admin.css', array(), $version );
	}

	public function handle_clear_logs() {
		/* Check user capability for security */
		if ( ! current_user_can( 'manage_options' ) ) {
			Bizzio_Sync_Logger::log(__( 'Unauthorized user', 'bizzio-sync' ) );
		}

		/* Clear the logs */
		delete_option( 'bizzio_sync_logs' );
		Bizzio_Sync_Logger::log(__( 'All logs cleared.' , 'bizzio-sync' ) );

		/* Redirect back to the logs page */
		wp_safe_redirect( admin_url( 'admin.php?page=bizzio-sync-logs' ) );
		exit;
	}
}

