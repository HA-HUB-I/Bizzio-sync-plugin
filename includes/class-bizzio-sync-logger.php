<?php

namespace BizzioSync;

class Bizzio_Sync_Logger {

	/**
	 * Logs a message to the WordPress options table.
	 *
	 * @param string $message The log message.
	 */
	public static function log( $message ) {
		/*  Check if logging is enabled */
		if ( get_option( 'bizzio_enable_logging', 0 ) ) {
			/*  Get existing logs from the options table */
			$logs = get_option( 'bizzio_sync_logs', array() );
			/* Append the new log entry */
			$logs[] = array(
				'date'    => current_time( 'mysql' ), /*  Get current time in MySQL format */
				'message' => $message,
			);

			/* Limit the number of logs to avoid bloating the options table */
			if ( count( $logs ) > 100 ) {
				array_shift( $logs );  /* Remove the oldest log entry */
			}

			/*  Update the option with the new log entry */
			update_option( 'bizzio_sync_logs', $logs );
		}
	}
}
