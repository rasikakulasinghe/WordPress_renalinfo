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

/**
 * AJAX handler for language selection
 *
 * Sets the selected language and returns the URL for the current page in that language.
 *
 * @since 1.0.0
 */
function renalinfo_ajax_set_language() {
	// Verify nonce for security.
	if ( ! renalinfo_verify_ajax_nonce() ) {
		wp_send_json_error(
			array(
				'message' => __( 'Security check failed', 'renalinfo' ),
			),
			403
		);
	}

	// Check if Polylang is active.
	if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_get_post' ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Multilingual plugin not active', 'renalinfo' ),
			),
			500
		);
	}

	// Get and validate the requested language.
	$requested_lang = isset( $_POST['language'] ) ? sanitize_text_field( wp_unslash( $_POST['language'] ) ) : '';

	if ( empty( $requested_lang ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Language parameter is required', 'renalinfo' ),
			),
			400
		);
	}

	// Get available languages.
	$available_languages = pll_languages_list();

	if ( ! in_array( $requested_lang, $available_languages, true ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Invalid language code', 'renalinfo' ),
			),
			400
		);
	}

	// Get current post/page ID if provided.
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	// Get the URL for the requested language.
	$redirect_url = '';

	if ( $post_id > 0 ) {
		// Try to get the translation of the current post.
		$translation_id = pll_get_post( $post_id, $requested_lang );

		if ( $translation_id && get_post_status( $translation_id ) === 'publish' ) {
			// Translation exists, get its URL.
			$redirect_url = get_permalink( $translation_id );
		} else {
			// No translation, get home URL in the requested language.
			if ( function_exists( 'pll_home_url' ) ) {
				$redirect_url = pll_home_url( $requested_lang );
			}
		}
	} else {
		// No post ID provided, redirect to home in requested language.
		if ( function_exists( 'pll_home_url' ) ) {
			$redirect_url = pll_home_url( $requested_lang );
		}
	}

	// Fallback to adding language parameter to current URL.
	if ( empty( $redirect_url ) ) {
		$current_url = isset( $_POST['current_url'] ) ? esc_url_raw( wp_unslash( $_POST['current_url'] ) ) : home_url( '/' );
		$redirect_url = add_query_arg( 'lang', $requested_lang, $current_url );
	}

	// Return success with redirect URL.
	wp_send_json_success(
		array(
			'message'      => __( 'Language changed successfully', 'renalinfo' ),
			'language'     => $requested_lang,
			'redirect_url' => $redirect_url,
		)
	);
}
add_action( 'wp_ajax_renalinfo_set_language', 'renalinfo_ajax_set_language' );
add_action( 'wp_ajax_nopriv_renalinfo_set_language', 'renalinfo_ajax_set_language' );
