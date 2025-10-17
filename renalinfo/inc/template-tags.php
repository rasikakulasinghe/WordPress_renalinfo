<?php
/**
 * Template Tags
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display reading time
 *
 * @since 1.0.0
 */
function renalinfo_the_reading_time() {
	$minutes = renalinfo_get_reading_time();
	/* translators: %d: number of minutes */
	printf( esc_html__( '%d min read', 'renalinfo' ), absint( $minutes ) );
}

/**
 * Display breadcrumb navigation
 *
 * @since 1.0.0
 */
function renalinfo_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'renalinfo' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'renalinfo' ) . '</a>';

	if ( is_single() ) {
		$post_type = get_post_type();
		$post_type_object = get_post_type_object( $post_type );
		
		if ( $post_type_object ) {
			echo ' &raquo; <span>' . esc_html( $post_type_object->labels->singular_name ) . '</span>';
		}
	}

	echo '</nav>';
}
