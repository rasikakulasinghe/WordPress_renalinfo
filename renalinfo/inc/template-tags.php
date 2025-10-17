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
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_reading_time( $post_id = 0 ) {
	$minutes = renalinfo_get_reading_time( $post_id );
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
		$post_type        = get_post_type();
		$post_type_object = get_post_type_object( $post_type );

		if ( $post_type_object ) {
			echo ' &raquo; <span>' . esc_html( $post_type_object->labels->singular_name ) . '</span>';
		}
	} elseif ( is_archive() ) {
		echo ' &raquo; <span>' . esc_html( get_the_archive_title() ) . '</span>';
	} elseif ( is_search() ) {
		echo ' &raquo; <span>' . esc_html__( 'Search Results', 'renalinfo' ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Display medical review date
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_medical_review_date( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$review_date = get_post_meta( $post_id, '_medical_review_date', true );
	if ( ! empty( $review_date ) ) {
		/* translators: %s: review date */
		printf(
			'<div class="medical-review-date">%s</div>',
			sprintf(
				esc_html__( 'Medically reviewed: %s', 'renalinfo' ),
				esc_html( date_i18n( get_option( 'date_format' ), strtotime( $review_date ) ) )
			)
		);
	}
}

/**
 * Display medical reviewer name
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_medical_reviewer( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$reviewer = get_post_meta( $post_id, '_medical_reviewer', true );
	if ( ! empty( $reviewer ) ) {
		/* translators: %s: reviewer name */
		printf(
			'<div class="medical-reviewer">%s</div>',
			sprintf(
				esc_html__( 'Reviewed by: %s', 'renalinfo' ),
				esc_html( $reviewer )
			)
		);
	}
}

/**
 * Display update notice
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_update_notice( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! renalinfo_is_recently_updated( $post_id, 90 ) ) { // Show for updates within 90 days.
		return;
	}

	$modified_date = get_the_modified_date( '', $post_id );
	$version_note  = get_post_meta( $post_id, '_version_note', true );

	echo '<div class="update-notice">';
	/* translators: %s: update date */
	printf(
		'<strong>%s</strong>',
		sprintf(
			esc_html__( 'Updated: %s', 'renalinfo' ),
			esc_html( $modified_date )
		)
	);

	if ( ! empty( $version_note ) ) {
		echo '<p>' . esc_html( $version_note ) . '</p>';
	}
	echo '</div>';
}

/**
 * Display key takeaways
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_key_takeaways( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$takeaways = get_post_meta( $post_id, '_key_takeaways', true );
	if ( empty( $takeaways ) ) {
		return;
	}

	$lines = explode( "\n", $takeaways );
	echo '<div class="key-takeaways">';
	echo '<h3>' . esc_html__( 'Key Takeaways', 'renalinfo' ) . '</h3>';
	echo '<ul>';
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( ! empty( $line ) ) {
			$line = ltrim( $line, '•-*' ); // Remove bullet characters.
			echo '<li>' . esc_html( trim( $line ) ) . '</li>';
		}
	}
	echo '</ul>';
	echo '</div>';
}

/**
 * Display FAQ items with schema.org markup
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_faq_items( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$faq_items = renalinfo_get_faq_items( $post_id );
	if ( empty( $faq_items ) ) {
		return;
	}

	echo '<div class="faq-section" itemscope itemtype="https://schema.org/FAQPage">';
	echo '<h3>' . esc_html__( 'Frequently Asked Questions', 'renalinfo' ) . '</h3>';

	foreach ( $faq_items as $faq ) {
		echo '<div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">';
		echo '<h4 class="faq-question" itemprop="name">' . esc_html( $faq['question'] ) . '</h4>';
		echo '<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">';
		echo '<div itemprop="text">' . wp_kses_post( wpautop( $faq['answer'] ) ) . '</div>';
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
}

/**
 * Display related articles
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 * @param int $limit Number of articles to display.
 */
function renalinfo_the_related_articles( $post_id = 0, $limit = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$related_articles = renalinfo_get_related_articles( $post_id, $limit );
	if ( empty( $related_articles ) ) {
		return;
	}

	echo '<div class="related-articles">';
	echo '<h3>' . esc_html__( 'Related Articles', 'renalinfo' ) . '</h3>';
	echo '<div class="related-articles-grid">';

	foreach ( $related_articles as $article ) {
		?>
		<article class="related-article-card">
			<?php if ( has_post_thumbnail( $article->ID ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( $article->ID ) ); ?>" class="related-article-thumbnail">
					<?php echo get_the_post_thumbnail( $article->ID, 'article-thumb' ); ?>
				</a>
			<?php endif; ?>
			<div class="related-article-content">
				<h4>
					<a href="<?php echo esc_url( get_permalink( $article->ID ) ); ?>">
						<?php echo esc_html( get_the_title( $article->ID ) ); ?>
					</a>
				</h4>
				<?php if ( $article->post_excerpt ) : ?>
					<p><?php echo esc_html( $article->post_excerpt ); ?></p>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	echo '</div>';
	echo '</div>';
}

/**
 * Display journey navigation
 *
 * @since 1.0.0
 * @param int $article_id Article ID.
 * @param int $journey_id Journey ID.
 */
function renalinfo_the_journey_navigation( $article_id, $journey_id ) {
	$position = renalinfo_get_journey_position( $article_id, $journey_id );
	if ( empty( $position ) ) {
		return;
	}

	echo '<nav class="journey-navigation" aria-label="' . esc_attr__( 'Journey Navigation', 'renalinfo' ) . '">';
	
	// Progress indicator.
	echo '<div class="journey-progress">';
	/* translators: 1: current article number, 2: total articles */
	printf(
		esc_html__( 'Article %1$d of %2$d', 'renalinfo' ),
		absint( $position['current'] ),
		absint( $position['total'] )
	);
	echo '</div>';

	// Navigation buttons.
	echo '<div class="journey-nav-buttons">';
	
	if ( $position['prev'] ) {
		printf(
			'<a href="%s" class="journey-nav-button journey-nav-prev">&larr; %s</a>',
			esc_url( get_permalink( $position['prev'] ) ),
			esc_html__( 'Previous', 'renalinfo' )
		);
	}

	printf(
		'<a href="%s" class="journey-nav-button journey-nav-overview">%s</a>',
		esc_url( get_permalink( $journey_id ) ),
		esc_html__( 'View All', 'renalinfo' )
	);

	if ( $position['next'] ) {
		printf(
			'<a href="%s" class="journey-nav-button journey-nav-next">%s &rarr;</a>',
			esc_url( get_permalink( $position['next'] ) ),
			esc_html__( 'Next', 'renalinfo' )
		);
	}

	echo '</div>';
	echo '</nav>';
}

/**
 * Display audience badge
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_audience_badge( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$audience = get_post_meta( $post_id, '_audience', true );
	if ( empty( $audience ) ) {
		return;
	}

	$label = ( 'professional' === $audience ) ? __( 'For Professionals', 'renalinfo' ) : __( 'For Families', 'renalinfo' );
	$class = 'audience-badge audience-badge-' . esc_attr( $audience );

	printf(
		'<span class="%s">%s</span>',
		esc_attr( $class ),
		esc_html( $label )
	);
}

/**
 * Display staff profile contact information
 *
 * @since 1.0.0
 * @param int $staff_id Staff post ID.
 */
function renalinfo_the_staff_contact( $staff_id ) {
	$email        = get_post_meta( $staff_id, '_staff_email', true );
	$phone        = get_post_meta( $staff_id, '_staff_phone', true );
	$office_hours = get_post_meta( $staff_id, '_staff_office_hours', true );

	if ( empty( $email ) && empty( $phone ) && empty( $office_hours ) ) {
		return;
	}

	echo '<div class="staff-contact">';
	echo '<h4>' . esc_html__( 'Contact Information', 'renalinfo' ) . '</h4>';

	if ( ! empty( $email ) ) {
		printf(
			'<p><strong>%s:</strong> <a href="mailto:%s">%s</a></p>',
			esc_html__( 'Email', 'renalinfo' ),
			esc_attr( $email ),
			esc_html( $email )
		);
	}

	if ( ! empty( $phone ) ) {
		printf(
			'<p><strong>%s:</strong> <a href="tel:%s">%s</a></p>',
			esc_html__( 'Phone', 'renalinfo' ),
			esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ),
			esc_html( $phone )
		);
	}

	if ( ! empty( $office_hours ) ) {
		printf(
			'<p><strong>%s:</strong> %s</p>',
			esc_html__( 'Office Hours', 'renalinfo' ),
			esc_html( $office_hours )
		);
	}

	echo '</div>';
}

/**
 * Display staff credentials
 *
 * @since 1.0.0
 * @param int $staff_id Staff post ID.
 */
function renalinfo_the_staff_credentials( $staff_id ) {
	$role        = get_post_meta( $staff_id, '_staff_role', true );
	$credentials = get_post_meta( $staff_id, '_staff_credentials', true );

	if ( empty( $role ) && empty( $credentials ) ) {
		return;
	}

	echo '<div class="staff-credentials">';
	
	if ( ! empty( $role ) ) {
		echo '<p class="staff-role">' . esc_html( $role ) . '</p>';
	}

	if ( ! empty( $credentials ) ) {
		echo '<p class="staff-credentials-list">' . esc_html( $credentials ) . '</p>';
	}

	echo '</div>';
}

/**
 * Display staff languages
 *
 * @since 1.0.0
 * @param int $staff_id Staff post ID.
 */
function renalinfo_the_staff_languages( $staff_id ) {
	$languages = get_post_meta( $staff_id, '_staff_languages', true );
	if ( empty( $languages ) ) {
		return;
	}

	printf(
		'<div class="staff-languages"><strong>%s:</strong> %s</div>',
		esc_html__( 'Languages', 'renalinfo' ),
		esc_html( $languages )
	);
}

/**
 * Display version history link for editors
 *
 * @since 1.0.0
 * @param int $post_id Post ID (optional).
 */
function renalinfo_the_version_history_link( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$revisions = wp_get_post_revisions( $post_id );
	if ( empty( $revisions ) ) {
		return;
	}

	printf(
		'<a href="%s" class="version-history-link">%s (%d)</a>',
		esc_url( admin_url( 'revision.php?revision=' . key( $revisions ) ) ),
		esc_html__( 'View Version History', 'renalinfo' ),
		count( $revisions )
	);
}
