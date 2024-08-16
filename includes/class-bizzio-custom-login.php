<?php

namespace BizzioSync;

/**
 * Class representing a simple example.
 *
 * This class demonstrates how to use PHPDoc comments
 * to document a class in PHP.
 *
 * @package ExamplePackage
 */
class Bizzio_Custom_Login {

	public function __construct() {
		/*  Register hooks */
		$this->register_hooks();
	}

	/**
	 * Register the necessary hooks for custom login functionality.
	 */
	private function register_hooks(): void {
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_stylesheet' ) );
		add_filter( 'login_headerurl', array( $this, 'set_login_logo_url' ) );
		add_filter( 'login_headertext', array( $this, 'set_login_logo_url_title' ) ); 
	}

	/**
	 * Load custom stylesheet on the WordPress login page.
	 */
	public function enqueue_login_stylesheet(): void {
		$version = '1.0.0'; /*  You can dynamically generate this based on the file's modification time. */
		wp_enqueue_style( 'custom-login', BIZZIO_SYNC_URL . 'assets/css/custom-login.css', array(), $version );
		wp_enqueue_style('bizziosync-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

	}

	/**
	 * Change the URL of the logo on the WordPress login page to the home URL.
	 *
	 * @return string URL of site's homepage.
	 */
	public function set_login_logo_url(): string {
		return home_url();
	}

	/**
	 * Filter the title attribute of the header logo above the login form.
	 *
	 * @return string Site title - Site description(tagline).
	 */
	public function set_login_logo_url_title(): string {
		return get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
	}
}
