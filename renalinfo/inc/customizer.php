<?php
/**
 * Theme Customizer
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function renalinfo_customize_register( $wp_customize ) {
	// Placeholder for customizer settings
	// Will be implemented in Phase 11
}
add_action( 'customize_register', 'renalinfo_customize_register' );
