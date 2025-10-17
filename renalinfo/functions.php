<?php
/**
 * RenalInfo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define constants
 */
define( 'RENALINFO_VERSION', '1.0.0' );
define( 'RENALINFO_THEME_DIR', get_template_directory() );
define( 'RENALINFO_THEME_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 */
function renalinfo_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'renalinfo', RENALINFO_THEME_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Enable support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 400,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
}
add_action( 'after_setup_theme', 'renalinfo_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * @since 1.0.0
 *
 * @global int $content_width Content width.
 */
function renalinfo_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'renalinfo_content_width', 800 );
}
add_action( 'after_setup_theme', 'renalinfo_content_width', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function renalinfo_scripts() {
	// Get asset version based on file modification time or theme version.
	$version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : RENALINFO_VERSION;

	// Enqueue main stylesheet.
	wp_enqueue_style(
		'renalinfo-style',
		get_stylesheet_uri(),
		array(),
		$version
	);

	// Enqueue main JavaScript.
	wp_enqueue_script(
		'renalinfo-main',
		RENALINFO_THEME_URI . '/assets/js/main.js',
		array(),
		$version,
		true
	);

	// Localize script with AJAX settings.
	wp_localize_script(
		'renalinfo-main',
		'renalInfoAjax',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'renalinfo_nonce' ),
		)
	);

	// Enqueue comment reply script for threaded comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'renalinfo_scripts' );

/**
 * Enqueue Google Fonts.
 *
 * @since 1.0.0
 */
function renalinfo_enqueue_google_fonts() {
	// Build Google Fonts URL with display=swap for performance.
	$font_families = array(
		'Inter:wght@400;500;600;700',
		'Noto+Sans+Sinhala:wght@400;500;600;700',
		'Noto+Sans+Tamil:wght@400;500;600;700',
	);

	$fonts_url = add_query_arg(
		array(
			'family'  => implode( '&family=', $font_families ),
			'display' => 'swap',
		),
		'https://fonts.googleapis.com/css2'
	);

	wp_enqueue_style(
		'renalinfo-google-fonts',
		$fonts_url,
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	);
}
add_action( 'wp_enqueue_scripts', 'renalinfo_enqueue_google_fonts' );

/**
 * Include required files.
 *
 * @since 1.0.0
 */
function renalinfo_includes() {
	// Include custom post types.
	require_once RENALINFO_THEME_DIR . '/inc/custom-post-types.php';

	// Include custom taxonomies.
	require_once RENALINFO_THEME_DIR . '/inc/custom-taxonomies.php';

	// Include custom fields.
	require_once RENALINFO_THEME_DIR . '/inc/custom-fields.php';

	// Include template functions.
	require_once RENALINFO_THEME_DIR . '/inc/template-functions.php';

	// Include template tags.
	require_once RENALINFO_THEME_DIR . '/inc/template-tags.php';

	// Include customizer.
	if ( is_customize_preview() ) {
		require_once RENALINFO_THEME_DIR . '/inc/customizer.php';
	}

	// Include widgets.
	require_once RENALINFO_THEME_DIR . '/inc/widgets.php';

	// Include AJAX handlers.
	require_once RENALINFO_THEME_DIR . '/inc/ajax-handlers.php';
}
add_action( 'after_setup_theme', 'renalinfo_includes' );
