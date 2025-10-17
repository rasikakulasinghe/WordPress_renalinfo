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
 * Sanitize email input
 *
 * @since 1.0.0
 * @param string $input Email string.
 * @return string Sanitized email.
 */
function renalinfo_sanitize_email( $input ) {
	return sanitize_email( $input );
}

/**
 * Sanitize URL input
 *
 * @since 1.0.0
 * @param string $input URL string.
 * @return string Sanitized URL.
 */
function renalinfo_sanitize_url( $input ) {
	return esc_url_raw( $input );
}

/**
 * Sanitize HTML content (allows safe HTML tags)
 *
 * @since 1.0.0
 * @param string $input HTML content.
 * @return string Sanitized HTML.
 */
function renalinfo_sanitize_html( $input ) {
	return wp_kses_post( $input );
}

/**
 * Sanitize array of IDs
 *
 * @since 1.0.0
 * @param string|array $input Comma-separated IDs or array of IDs.
 * @return array Array of integer IDs.
 */
function renalinfo_sanitize_ids( $input ) {
	if ( is_string( $input ) ) {
		$input = explode( ',', $input );
	}
	return array_map( 'absint', $input );
}

/**
 * Validate article requirements per data model
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return array Array of validation errors (empty if valid).
 */
function renalinfo_validate_article( $post_id ) {
	$errors = array();

	// Check if article has a template assigned.
	$template = get_post_meta( $post_id, '_article_template', true );
	if ( empty( $template ) ) {
		$errors[] = __( 'Article template is required.', 'renalinfo' );
	}

	// Check if article has an audience assigned.
	$audience = get_post_meta( $post_id, '_audience', true );
	if ( empty( $audience ) ) {
		$errors[] = __( 'Primary audience is required.', 'renalinfo' );
	}

	// Validate reading level for family content.
	if ( 'family' === $audience ) {
		$reading_level = get_post_meta( $post_id, '_reading_level', true );
		if ( ! empty( $reading_level ) && ( $reading_level < 7 || $reading_level > 10 ) ) {
			$errors[] = __( 'Reading level should be between 7-10 for family content.', 'renalinfo' );
		}
	}

	// Check medical review date is not in the future.
	$review_date = get_post_meta( $post_id, '_medical_review_date', true );
	if ( ! empty( $review_date ) && strtotime( $review_date ) > time() ) {
		$errors[] = __( 'Medical review date cannot be in the future.', 'renalinfo' );
	}

	return $errors;
}

/**
 * Display validation errors as admin notice
 *
 * @since 1.0.0
 * @param array $errors Array of error messages.
 */
function renalinfo_display_validation_errors( $errors ) {
	if ( ! empty( $errors ) ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p><strong><?php esc_html_e( 'Article Validation Errors:', 'renalinfo' ); ?></strong></p>
			<ul>
				<?php foreach ( $errors as $error ) : ?>
					<li><?php echo esc_html( $error ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
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
	$minutes    = ceil( $word_count / 200 ); // Average reading speed 200 words/minute.

	return $minutes;
}

/**
 * Get related articles
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @param int $limit Number of articles to retrieve.
 * @return array Array of WP_Post objects.
 */
function renalinfo_get_related_articles( $post_id = 0, $limit = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// First, check for manually assigned related articles.
	$related_ids = get_post_meta( $post_id, '_related_articles', true );
	if ( ! empty( $related_ids ) ) {
		$related_ids = renalinfo_sanitize_ids( $related_ids );
		$args        = array(
			'post_type'      => 'article',
			'post__in'       => $related_ids,
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
			'orderby'        => 'post__in',
		);
		return get_posts( $args );
	}

	// Fallback: Get articles from the same category.
	$terms = get_the_terms( $post_id, 'article_category' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$term_ids = wp_list_pluck( $terms, 'term_id' );
		$args     = array(
			'post_type'      => 'article',
			'tax_query'      => array(
				array(
					'taxonomy' => 'article_category',
					'field'    => 'term_id',
					'terms'    => $term_ids,
				),
			),
			'post__not_in'   => array( $post_id ),
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
		);
		return get_posts( $args );
	}

	return array();
}

/**
 * Get FAQ items for an article
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return array Array of FAQ items with 'question' and 'answer' keys.
 */
function renalinfo_get_faq_items( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$faq_items = get_post_meta( $post_id, '_faq_items', true );
	if ( ! is_array( $faq_items ) ) {
		return array();
	}

	return $faq_items;
}

/**
 * Get journey articles in order
 *
 * @since 1.0.0
 * @param int $journey_id Journey post ID.
 * @return array Array of WP_Post objects in order.
 */
function renalinfo_get_journey_articles( $journey_id ) {
	$article_ids = get_post_meta( $journey_id, '_journey_articles', true );
	if ( empty( $article_ids ) ) {
		return array();
	}

	$article_ids = renalinfo_sanitize_ids( $article_ids );
	$args        = array(
		'post_type'      => 'article',
		'post__in'       => $article_ids,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'post__in',
	);

	return get_posts( $args );
}

/**
 * Get current article position in journey
 *
 * @since 1.0.0
 * @param int $article_id Article post ID.
 * @param int $journey_id Journey post ID.
 * @return array Array with 'current', 'total', 'prev', 'next' keys.
 */
function renalinfo_get_journey_position( $article_id, $journey_id ) {
	$article_ids = get_post_meta( $journey_id, '_journey_articles', true );
	if ( empty( $article_ids ) ) {
		return array();
	}

	$article_ids = renalinfo_sanitize_ids( $article_ids );
	$position    = array_search( $article_id, $article_ids, true );

	if ( false === $position ) {
		return array();
	}

	$total = count( $article_ids );
	return array(
		'current' => $position + 1,
		'total'   => $total,
		'prev'    => ( $position > 0 ) ? $article_ids[ $position - 1 ] : null,
		'next'    => ( $position < $total - 1 ) ? $article_ids[ $position + 1 ] : null,
	);
}

/**
 * Get staff authored articles
 *
 * @since 1.0.0
 * @param int $staff_id Staff post ID.
 * @param int $limit Number of articles to retrieve.
 * @return array Array of WP_Post objects.
 */
function renalinfo_get_staff_articles( $staff_id, $limit = -1 ) {
	$args = array(
		'post_type'      => 'article',
		'author'         => get_post_field( 'post_author', $staff_id ),
		'posts_per_page' => $limit,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	return get_posts( $args );
}

/**
 * Search medical terms by abbreviation or synonym
 *
 * @since 1.0.0
 * @param string $search_term Search query.
 * @return array Array of WP_Post objects (medical_term).
 */
function renalinfo_search_medical_terms( $search_term ) {
	$args = array(
		'post_type'      => 'medical_term',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_term_abbreviation',
				'value'   => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key'     => '_term_full_name',
				'value'   => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key'     => '_term_synonyms',
				'value'   => $search_term,
				'compare' => 'LIKE',
			),
		),
	);

	return get_posts( $args );
}

/**
 * Check if content was recently updated
 *
 * @since 1.0.0
 * @param int    $post_id Post ID.
 * @param string $threshold Number of days to consider "recent" (default 30).
 * @return bool True if updated recently.
 */
function renalinfo_is_recently_updated( $post_id, $threshold = 30 ) {
	$modified_time = get_post_modified_time( 'U', false, $post_id );
	$days_ago      = ( time() - $modified_time ) / DAY_IN_SECONDS;

	return $days_ago <= $threshold;
}

/**
 * Get article template type
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return string Template type (condition-explainer, treatment-procedure, professional-article).
 */
function renalinfo_get_article_template( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return get_post_meta( $post_id, '_article_template', true );
}

/**
 * Check if user's browser supports WebP
 *
 * @since 1.0.0
 * @return bool True if WebP is supported.
 */
function renalinfo_supports_webp() {
	return isset( $_SERVER['HTTP_ACCEPT'] ) && strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false;
}

/**
 * Get responsive image HTML with WebP support
 *
 * @since 1.0.0
 * @param int    $attachment_id Image attachment ID.
 * @param string $size Image size.
 * @param array  $attr Additional attributes.
 * @return string Picture element HTML.
 */
function renalinfo_get_responsive_image( $attachment_id, $size = 'full', $attr = array() ) {
	if ( ! $attachment_id ) {
		return '';
	}

	$image_src = wp_get_attachment_image_src( $attachment_id, $size );
	if ( ! $image_src ) {
		return '';
	}

	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$alt = $alt ? $alt : get_the_title( $attachment_id );

	$default_attr = array(
		'loading' => 'lazy',
		'class'   => 'responsive-image',
		'alt'     => $alt,
	);

	$attr = wp_parse_args( $attr, $default_attr );

	// Build picture element with WebP source if supported.
	$html = '<picture>';
	
	// Add WebP source if available.
	$webp_src = str_replace( array( '.jpg', '.jpeg', '.png' ), '.webp', $image_src[0] );
	if ( file_exists( str_replace( wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_src ) ) ) {
		$html .= sprintf(
			'<source srcset="%s" type="image/webp">',
			esc_url( $webp_src )
		);
	}

	// Add original image.
	$html .= wp_get_attachment_image( $attachment_id, $size, false, $attr );
	$html .= '</picture>';

	return $html;
}

/**
 * Check if translation is available for current post
 *
 * Checks if the current post has a translation in the selected language.
 * If not, returns false and the translation notice should be displayed.
 *
 * @since 1.0.0
 * @param int    $post_id Post ID (optional, defaults to current post).
 * @param string $lang    Language code (optional, defaults to current language).
 * @return bool True if translation exists, false otherwise.
 */
function renalinfo_check_translation_available( $post_id = 0, $lang = '' ) {
	// Exit if Polylang is not active.
	if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_get_post' ) ) {
		return true; // Assume available if Polylang not active.
	}

	// Get post ID if not provided.
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get current language if not provided.
	if ( empty( $lang ) ) {
		$lang = pll_current_language();
	}

	// Get the default language.
	if ( function_exists( 'pll_default_language' ) ) {
		$default_lang = pll_default_language();
	} else {
		$default_lang = 'en';
	}

	// If we're viewing the default language, translation is always available.
	if ( $lang === $default_lang ) {
		return true;
	}

	// Get the post's language.
	if ( function_exists( 'pll_get_post_language' ) ) {
		$post_lang = pll_get_post_language( $post_id );
	} else {
		return true;
	}

	// If post language matches current language, translation is available.
	if ( $post_lang === $lang ) {
		return true;
	}

	// Check if translation exists for this post in the selected language.
	$translation_id = pll_get_post( $post_id, $lang );

	// If translation ID exists and is published, translation is available.
	if ( $translation_id && get_post_status( $translation_id ) === 'publish' ) {
		return true;
	}

	// No translation available.
	return false;
}

/**
 * Get available translation languages for a post
 *
 * Returns an array of language codes where translations exist for the given post.
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional, defaults to current post).
 * @return array Array of language codes with available translations.
 */
function renalinfo_get_available_translations( $post_id = 0 ) {
	$available = array();

	// Exit if Polylang is not active.
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $available;
	}

	// Get post ID if not provided.
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get all languages.
	$languages = pll_the_languages( array( 'raw' => 1 ) );

	if ( empty( $languages ) ) {
		return $available;
	}

	// Check each language for translation.
	foreach ( $languages as $language ) {
		if ( ! empty( $language['id'] ) ) {
			$translation_id = $language['id'];
			if ( get_post_status( $translation_id ) === 'publish' ) {
				$available[] = $language['slug'];
			}
		}
	}

	return $available;
}

/**
 * Display translation notice if needed
 *
 * Checks if translation is available and displays notice if not.
 * This function should be called in template files where translation notice is needed.
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional, defaults to current post).
 * @return void
 */
function renalinfo_maybe_show_translation_notice( $post_id = 0 ) {
	// Check if translation is available.
	if ( ! renalinfo_check_translation_available( $post_id ) ) {
		// Load the translation notice template.
		get_template_part( 'template-parts/content/translation-notice' );
	}
}

/**
 * Modify search query to prioritize current language results
 *
 * Filters search queries to show results in current language first,
 * followed by results in other languages.
 *
 * @since 1.0.0
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function renalinfo_filter_search_by_language( $query ) {
	// Only modify main search queries on frontend.
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		// Check if Polylang is active.
		if ( function_exists( 'pll_current_language' ) ) {
			$current_lang = pll_current_language();
			
			// Add language filter to prioritize current language.
			// Note: We don't restrict to current language only, just prioritize it.
			$query->set( 'lang', $current_lang );
			
			// Allow filtering all languages if user prefers.
			// This can be overridden with a query parameter.
			if ( isset( $_GET['all_languages'] ) && $_GET['all_languages'] === '1' ) {
				$query->set( 'lang', '' ); // Show all languages.
			}
		}
	}
}
add_action( 'pre_get_posts', 'renalinfo_filter_search_by_language' );

/**
 * Get language name for display
 *
 * Returns the display name for a language code.
 *
 * @since 1.0.0
 * @param string $lang_code Language code (e.g., 'en', 'si', 'ta').
 * @return string Language display name.
 */
function renalinfo_get_language_name( $lang_code ) {
	$language_names = array(
		'en' => __( 'English', 'renalinfo' ),
		'si' => __( 'Sinhala', 'renalinfo' ),
		'ta' => __( 'Tamil', 'renalinfo' ),
	);

	return isset( $language_names[ $lang_code ] ) ? $language_names[ $lang_code ] : $lang_code;
}

/**
 * Get language native name for display
 *
 * Returns the native name for a language code (in its own script).
 *
 * @since 1.0.0
 * @param string $lang_code Language code (e.g., 'en', 'si', 'ta').
 * @return string Language native name.
 */
function renalinfo_get_language_native_name( $lang_code ) {
	$native_names = array(
		'en' => 'English',
		'si' => 'සිංහල',
		'ta' => 'தமிழ்',
	);

	return isset( $native_names[ $lang_code ] ) ? $native_names[ $lang_code ] : $native_names['en'];
}

/**
 * Get post language code
 *
 * Returns the language code for a given post.
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional, defaults to current post).
 * @return string|null Language code or null if not available.
 */
function renalinfo_get_post_language( $post_id = 0 ) {
	// Check if Polylang is active.
	if ( ! function_exists( 'pll_get_post_language' ) ) {
		return null;
	}

	// Get post ID if not provided.
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return pll_get_post_language( $post_id );
}
