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

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary-menu' => esc_html__( 'Primary Menu', 'renalinfo' ),
			'footer-menu'  => esc_html__( 'Footer Menu', 'renalinfo' ),
		)
	);
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

/**
 * Register Polylang strings for translation
 *
 * @since 1.0.0
 */
function renalinfo_register_polylang_strings() {
	// Check if Polylang function exists.
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	// Common UI strings.
	pll_register_string( 'renalinfo', 'Read More', 'renalinfo' );
	pll_register_string( 'renalinfo', 'For Families', 'renalinfo' );
	pll_register_string( 'renalinfo', 'For Professionals', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Related Articles', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Medical Terms Explained', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Frequently Asked Questions', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Support Resources', 'renalinfo' );
	pll_register_string( 'renalinfo', 'View All', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Search', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Categories', 'renalinfo' );

	// Article meta strings.
	pll_register_string( 'renalinfo', 'Reading Time', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Last Updated', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Medically Reviewed By', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Key Takeaways', 'renalinfo' );

	// Navigation strings.
	pll_register_string( 'renalinfo', 'Home', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Previous', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Next', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Back to', 'renalinfo' );

	// Filter strings.
	pll_register_string( 'renalinfo', 'Filter by Audience', 'renalinfo' );
	pll_register_string( 'renalinfo', 'All Audiences', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Reading Level', 'renalinfo' );
	pll_register_string( 'renalinfo', 'All Levels', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Apply Filter', 'renalinfo' );

	// Language switcher strings.
	pll_register_string( 'renalinfo', 'Select Language', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Choose Your Language', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Continue', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Language', 'renalinfo' );

	// Translation notice strings.
	pll_register_string( 'renalinfo', 'Translation Not Available', 'renalinfo' );
	pll_register_string( 'renalinfo', 'This content is not yet available in your selected language. Showing English version.', 'renalinfo' );

	// Form strings.
	pll_register_string( 'renalinfo', 'Submit', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Required', 'renalinfo' );

	// Error/notice strings.
	pll_register_string( 'renalinfo', 'No results found', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Content updated', 'renalinfo' );

	// Journey navigation.
	pll_register_string( 'renalinfo', 'Article', 'renalinfo' );
	pll_register_string( 'renalinfo', 'of', 'renalinfo' );
	pll_register_string( 'renalinfo', 'Journey Overview', 'renalinfo' );

	// Customizer strings (register these when customizer values are set).
	$hero_title = get_theme_mod( 'renalinfo_hero_title' );
	if ( $hero_title ) {
		pll_register_string( 'renalinfo', 'Hero Title', 'renalinfo' );
	}

	$hero_subtitle = get_theme_mod( 'renalinfo_hero_subtitle' );
	if ( $hero_subtitle ) {
		pll_register_string( 'renalinfo', 'Hero Subtitle', 'renalinfo' );
	}

	$footer_text = get_theme_mod( 'renalinfo_footer_text' );
	if ( $footer_text ) {
		pll_register_string( 'renalinfo', 'Footer Text', 'renalinfo' );
	}
}
add_action( 'init', 'renalinfo_register_polylang_strings' );

/**
 * Get language-specific font family
 *
 * @since 1.0.0
 * @param string $lang_code Language code (en, si, ta).
 * @return string Font family CSS value.
 */
function renalinfo_get_language_font( $lang_code = '' ) {
	if ( empty( $lang_code ) && function_exists( 'pll_current_language' ) ) {
		$lang_code = pll_current_language();
	}

	$fonts = array(
		'si' => "'Noto Sans Sinhala', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
		'ta' => "'Noto Sans Tamil', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
		'en' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
	);

	return isset( $fonts[ $lang_code ] ) ? $fonts[ $lang_code ] : $fonts['en'];
}

/**
 * Add language-specific body class
 *
 * @since 1.0.0
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function renalinfo_language_body_class( $classes ) {
	if ( function_exists( 'pll_current_language' ) ) {
		$current_lang = pll_current_language();
		$classes[]    = 'lang-' . $current_lang;
	}
	return $classes;
}
add_filter( 'body_class', 'renalinfo_language_body_class' );

/**
 * Output inline CSS for language-specific fonts
 *
 * @since 1.0.0
 */
function renalinfo_language_font_styles() {
	if ( ! function_exists( 'pll_current_language' ) ) {
		return;
	}

	$current_lang = pll_current_language();
	$font_family  = renalinfo_get_language_font( $current_lang );

	// Only output custom font for Sinhala and Tamil.
	if ( 'si' === $current_lang || 'ta' === $current_lang ) {
		?>
		<style id="renalinfo-language-fonts">
			body {
				font-family: <?php echo esc_attr( $font_family ); ?>;
			}
		</style>
		<?php
	}
}
add_action( 'wp_head', 'renalinfo_language_font_styles', 100 );

