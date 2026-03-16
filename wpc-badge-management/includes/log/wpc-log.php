<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCBM_LITE' ) ? WPCBM_LITE : WPCBM_FILE, 'wpcbm_activate' );
register_deactivation_hook( defined( 'WPCBM_LITE' ) ? WPCBM_LITE : WPCBM_FILE, 'wpcbm_deactivate' );
add_action( 'admin_init', 'wpcbm_check_version' );

function wpcbm_check_version() {
	if ( ! empty( get_option( 'wpcbm_version' ) ) && ( get_option( 'wpcbm_version' ) < WPCBM_VERSION ) ) {
		wpc_log( 'wpcbm', 'upgraded' );
		update_option( 'wpcbm_version', WPCBM_VERSION, false );
	}
}

function wpcbm_activate() {
	wpc_log( 'wpcbm', 'installed' );
	update_option( 'wpcbm_version', WPCBM_VERSION, false );
}

function wpcbm_deactivate() {
	wpc_log( 'wpcbm', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}