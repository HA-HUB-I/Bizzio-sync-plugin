<?php

namespace BizzioSync;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Bizzio_Sync_Admin_Post {

    /**
     * Handles the syncing of data to the external API.
     */
    public static function handle_sync_data() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bizzio_sync_data_verify' ) ) {
            wp_die( 'Invalid nonce' );
        }

        // Prepare the data to send.
        $data = array(
            'option_1' => get_option( 'option_name_1' ),
            'option_2' => get_option( 'option_name_2' ),
            // Add more options as needed.
        );

        // API endpoint
        $api_url = 'https://api.example.com/endpoint';
        $validated_url = wp_http_validate_url( $api_url );

        if ( ! $validated_url ) {
            wp_die( 'Invalid API URL' );
        }

        // Prepare the headers with a secret key.
        $headers = array(
            'Authorization' => 'Bearer ' . sanitize_text_field( get_option( 'bizzio_api_secret' ) ),
            'Content-Type'  => 'application/json',
        );

        // Send the request.
        $response = wp_remote_post( $validated_url, array(
            'method'    => 'POST',
            'headers'   => $headers,
            'body'      => wp_json_encode( wp_unslash( $data ) ),
            'timeout'   => 45,
            'sslverify' => true,
        ) );

        // Check for errors.
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            wp_die( 'Something went wrong: ' . esc_html( $error_message ) );
        } else {
            wp_safe_redirect( admin_url( 'admin.php?page=bizzio-sync&updated=true' ) );
            exit;
        }
    }
}

// Ensure the action is hooked.
add_action( 'admin_post_bizzio_sync_data', array( 'BizzioSync\Bizzio_Sync_Admin_Post', 'handle_sync_data' ) );

