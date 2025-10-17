<?php
/**
 * Template Functions
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize text input
 *
 * @since 1.0.0
 * @param string $input Input string.
 * @return string Sanitized string.
 */
function renalinfo_sanitize_text( $input ) {
	return sanitize_text_field( $input );
}

/**
 * Get reading time for article
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return int Reading time in minutes.
 */
function renalinfo_get_reading_time( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$minutes    = ceil( $word_count / 200 ); // Average reading speed 200 words/minute

	return $minutes;
}
