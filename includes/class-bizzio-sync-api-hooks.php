<?php
namespace BizzioSync;

class BizzioSyncApiHooks {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_api_button' ) );
		add_action( 'admin_post_bizzio_sync_send_data', array( $this, 'handle_post_request' ) );
	}

	public function add_api_button() {
		add_submenu_page(
			'bizzio-sync',
			'API Sync',
			'API Sync',
			'manage_options',
			'bizzio-sync-api',
			array( $this, 'render_api_button' )
		);
	}

	public function render_api_button() {
		?>
		<div class="wrap">
			<h1><?php echo __('Send Data to External API' , 'bizzio-sync' ); ?></h1>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'bizzio_sync_send_data_nonce', 'bizzio_sync_send_data_nonce' ); ?>
				<input type="hidden" name="action" value="bizzio_sync_send_data">
				<input type="submit" class="button-primary" value="Sync Product">
			</form>
		</div>
		<?php
	}

	public function handle_post_request() {
		// Verify nonce
		if ( ! isset( $_POST['bizzio_sync_send_data_nonce'] ) || ! wp_verify_nonce( $_POST['bizzio_sync_send_data_nonce'], 'bizzio_sync_send_data_nonce' ) ) {
			/* wp_die('Invalid nonce'); */
			Bizzio_Sync_Logger::log(__( 'Invalid nonce' ,  'bizzio-sync' ) );
		}

        $args = array(
            'limit' => 3,
        );
        $products = wc_get_products( $args );
		 $data = array(
			 'id'     => 'example_value',
			 'status' => 'completed',
			 'NOW'    => current_time('h:m:s:d-m-Y'),
			 'Total'  => $products ,
		 );

		 // Set up the API URL (validate the URL first)
		 $url = 'http://example.test/'; // Ensure this URL is correct
		 if ( ! wp_http_validate_url( $url ) ) {
			 wp_die( 'Invalid URL' );
		 }

		 // Set up headers, including secure authentication
		 $args = array(
			 'headers'   => array(
				 'Content-Type'           => 'application/json',
				 'Authorization'          => 'Bearer ' . '123', // Replace with your token or secret
				 
			 ),
			 'body'      => json_encode( $data ),
			 'timeout'   => 30, // Increased timeout
			 'sslverify' => false, // Enable SSL verification (recommended)
		 );

		 // Send the POST request
		 $response = wp_remote_post( $url, $args );

		 // Check for errors
		 if ( is_wp_error( $response ) ) {
			 $error_message = $response->get_error_message();
			 /* wp_die('Request failed: ' . esc_html($error_message)); */
			 Bizzio_Sync_Logger::log(__( 'Request failed:' , 'bizzio-sync' . esc_html( $error_message ) ) );
		 }

		 // Process the response
		 $response_code = wp_remote_retrieve_response_code( $response );
		 if ( $response_code === 200 ) {
			 /*  echo '<div class="updated notice"><p>Data sent successfully!</p></div>'; */
			 wp_safe_redirect( add_query_arg( 'message', 'success', wp_get_referer() ) );
			 Bizzio_Sync_Logger::log(__( 'Data sent successfully!' , 'bizzio-sync' ) );
			 exit;
		 } else {
			 /* echo '<div class="error notice"><p>Failed to send data. HTTP Status: ' . esc_html($response_code) . '</p></div>'; */
			 Bizzio_Sync_Logger::log(__( 'Failed to send data. HTTP Status' , 'bizzio-sync' . esc_html( $response_code ) ) );
			 wp_safe_redirect( add_query_arg( 'message', 'error', wp_get_referer() ) );
			 exit;

		 }
	}
}
