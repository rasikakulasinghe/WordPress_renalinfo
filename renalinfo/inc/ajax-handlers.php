<?php
/**
 * AJAX Handlers
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verify AJAX nonce
 *
 * @since 1.0.0
 * @return bool
 */
function renalinfo_verify_ajax_nonce() {
	return isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'renalinfo_nonce' );
}

/**
 * AJAX handler for search autocomplete
 * Will be fully implemented in Phase 10
 *
 * @since 1.0.0
 */
function renalinfo_ajax_search_autocomplete() {
	if ( ! renalinfo_verify_ajax_nonce() ) {
		wp_send_json_error( array( 'message' => __( 'Security check failed', 'renalinfo' ) ) );
	}

	// Placeholder - full implementation in Phase 10
	wp_send_json_success( array( 'results' => array() ) );
}
add_action( 'wp_ajax_renalinfo_search_autocomplete', 'renalinfo_ajax_search_autocomplete' );
add_action( 'wp_ajax_nopriv_renalinfo_search_autocomplete', 'renalinfo_ajax_search_autocomplete' );
