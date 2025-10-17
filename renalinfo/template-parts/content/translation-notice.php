<?php
/**
 * Translation Notice Template
 *
 * Displays an informational notice when content is not available in the selected language
 *
 * @package RenalInfo
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if Polylang is not active.
if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_the_languages' ) ) {
	return;
}

$current_lang = pll_current_language();
$post_id = get_the_ID();

// Get available translations for this post.
$translations = pll_the_languages( array( 'raw' => 1 ) );
$available_languages = array();

foreach ( $translations as $language ) {
	if ( ! empty( $language['id'] ) && $language['id'] !== $post_id ) {
		$available_languages[] = $language;
	}
}

// If no translations available, show generic message.
if ( empty( $available_languages ) ) {
	$available_languages = $translations;
}

// Get language names.
$language_names = array(
	'en' => __( 'English', 'renalinfo' ),
	'si' => __( 'Sinhala', 'renalinfo' ),
	'ta' => __( 'Tamil', 'renalinfo' ),
);

$current_lang_name = isset( $language_names[ $current_lang ] ) ? $language_names[ $current_lang ] : $current_lang;
?>

<div class="translation-notice notice notice-info" role="alert">
	<div class="translation-notice-icon" aria-hidden="true">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
		</svg>
	</div>
	
	<div class="translation-notice-content">
		<h3 class="translation-notice-title">
			<?php
			if ( function_exists( 'pll__' ) ) {
				echo esc_html( pll__( 'Translation Not Available' ) );
			} else {
				esc_html_e( 'Translation Not Available', 'renalinfo' );
			}
			?>
		</h3>
		
		<p class="translation-notice-message">
			<?php
			// Translators: %s is the language name (e.g., "Sinhala" or "Tamil").
			printf(
				esc_html__( 'This content is not yet available in %s. You are viewing the English version.', 'renalinfo' ),
				'<strong>' . esc_html( $current_lang_name ) . '</strong>'
			);
			?>
		</p>
		
		<?php if ( ! empty( $available_languages ) ) : ?>
			<div class="translation-notice-languages">
				<p class="translation-notice-available">
					<?php esc_html_e( 'This content is available in:', 'renalinfo' ); ?>
				</p>
				<ul class="available-languages-list">
					<?php foreach ( $available_languages as $language ) : ?>
						<?php if ( ! empty( $language['url'] ) ) : ?>
							<li>
								<a href="<?php echo esc_url( $language['url'] ); ?>" 
									lang="<?php echo esc_attr( $language['slug'] ); ?>"
									hreflang="<?php echo esc_attr( $language['locale'] ); ?>"
									class="language-link">
									<?php if ( ! empty( $language['flag'] ) ) : ?>
										<span class="flag-icon" aria-hidden="true">
											<img src="<?php echo esc_url( $language['flag'] ); ?>" 
												alt="" 
												width="20" 
												height="15">
										</span>
									<?php endif; ?>
									<span class="language-name">
										<?php echo esc_html( $language['name'] ); ?>
									</span>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	
	<button type="button" class="translation-notice-close" aria-label="<?php esc_attr_e( 'Close notice', 'renalinfo' ); ?>">
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
</div>

<script>
(function() {
	'use strict';
	
	// Handle close button
	const closeButton = document.querySelector('.translation-notice-close');
	const notice = document.querySelector('.translation-notice');
	
	if (closeButton && notice) {
		closeButton.addEventListener('click', function() {
			notice.style.display = 'none';
			// Store dismissal in sessionStorage so it doesn't show again this session
			sessionStorage.setItem('renalinfo_translation_notice_dismissed_' + <?php echo absint( $post_id ); ?>, 'true');
		});
		
		// Check if already dismissed this session
		const dismissed = sessionStorage.getItem('renalinfo_translation_notice_dismissed_' + <?php echo absint( $post_id ); ?>);
		if (dismissed === 'true') {
			notice.style.display = 'none';
		}
	}
})();
</script>
