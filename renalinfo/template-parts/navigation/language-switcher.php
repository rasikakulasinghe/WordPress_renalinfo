<?php
/**
 * Language Switcher Template
 *
 * Displays language switcher with first-visit modal for language selection
 *
 * @package RenalInfo
 * @since 1.0.0
 */

// Exit if Polylang is not active.
if ( ! function_exists( 'pll_the_languages' ) ) {
	return;
}

$current_lang = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';

// Get available languages.
$languages = pll_the_languages( array( 'raw' => 1 ) );

if ( empty( $languages ) ) {
	return;
}
?>

<div class="language-switcher" role="navigation" aria-label="<?php esc_attr_e( 'Language Switcher', 'renalinfo' ); ?>">
	<button type="button" 
		class="language-switcher-toggle" 
		aria-expanded="false"
		aria-controls="language-dropdown"
		aria-label="<?php esc_attr_e( 'Select Language', 'renalinfo' ); ?>">
		<span class="language-switcher-current">
			<?php
			foreach ( $languages as $language ) {
				if ( $language['current_lang'] ) {
					?>
					<span class="flag-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $language['flag'] ); ?>" 
							alt="<?php echo esc_attr( $language['name'] ); ?>" 
							width="20" 
							height="15">
					</span>
					<span class="language-name">
						<?php echo esc_html( $language['name'] ); ?>
					</span>
					<?php
					break;
				}
			}
			?>
			<span class="dropdown-icon" aria-hidden="true">▼</span>
		</span>
	</button>

	<ul id="language-dropdown" class="language-dropdown" hidden>
		<?php foreach ( $languages as $language ) : ?>
			<li class="language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
				<a href="<?php echo esc_url( $language['url'] ); ?>" 
					lang="<?php echo esc_attr( $language['slug'] ); ?>"
					hreflang="<?php echo esc_attr( $language['locale'] ); ?>"
					class="language-link"
					<?php echo $language['current_lang'] ? 'aria-current="true"' : ''; ?>>
					<span class="flag-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $language['flag'] ); ?>" 
							alt="<?php echo esc_attr( $language['name'] ); ?>" 
							width="20" 
							height="15">
					</span>
					<span class="language-name"><?php echo esc_html( $language['name'] ); ?></span>
					<?php if ( $language['current_lang'] ) : ?>
						<span class="checkmark" aria-hidden="true">✓</span>
					<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php
// First-visit language selection modal.
// Only show if user hasn't selected a language in this session.
?>
<div id="language-selection-modal" class="language-modal" role="dialog" aria-labelledby="language-modal-title" aria-modal="true" hidden>
	<div class="language-modal-overlay" aria-hidden="true"></div>
	<div class="language-modal-content">
		<div class="language-modal-header">
			<h2 id="language-modal-title">
				<?php
				if ( function_exists( 'pll__' ) ) {
					echo esc_html( pll__( 'Choose Your Language' ) );
				} else {
					esc_html_e( 'Choose Your Language', 'renalinfo' );
				}
				?>
			</h2>
			<p class="language-modal-description">
				<?php esc_html_e( 'Select your preferred language for medical information', 'renalinfo' ); ?>
			</p>
		</div>

		<div class="language-modal-body">
			<div class="language-selection-grid">
				<?php foreach ( $languages as $language ) : ?>
					<a href="<?php echo esc_url( $language['url'] ); ?>" 
						lang="<?php echo esc_attr( $language['slug'] ); ?>"
						hreflang="<?php echo esc_attr( $language['locale'] ); ?>"
						class="language-selection-card <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>"
						data-lang="<?php echo esc_attr( $language['slug'] ); ?>">
						<span class="language-flag" aria-hidden="true">
							<img src="<?php echo esc_url( $language['flag'] ); ?>" 
								alt="" 
								width="48" 
								height="36">
						</span>
						<span class="language-name-large">
							<?php echo esc_html( $language['name'] ); ?>
						</span>
						<span class="language-native-name">
							<?php
							$native_names = array(
								'en' => 'English',
								'si' => 'සිංහල',
								'ta' => 'தமிழ்',
							);
							echo isset( $native_names[ $language['slug'] ] ) ? esc_html( $native_names[ $language['slug'] ] ) : '';
							?>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="language-modal-footer">
			<button type="button" class="language-modal-close btn btn-secondary">
				<?php
				if ( function_exists( 'pll__' ) ) {
					echo esc_html( pll__( 'Continue' ) );
				} else {
					esc_html_e( 'Continue', 'renalinfo' );
				}
				?>
			</button>
		</div>
	</div>
</div>

<script>
// First-visit language modal initialization
(function() {
	'use strict';
	
	// Check if user has already selected language in this session
	const languageSelected = sessionStorage.getItem('renalinfo_language_selected');
	const modal = document.getElementById('language-selection-modal');
	
	if (!languageSelected && modal) {
		// Small delay to ensure page is loaded
		setTimeout(function() {
			modal.removeAttribute('hidden');
			document.body.classList.add('modal-open');
			
			// Focus on modal for accessibility
			const modalContent = modal.querySelector('.language-modal-content');
			if (modalContent) {
				modalContent.focus();
			}
		}, 500);
	}
	
	// Handle language selection
	const languageCards = document.querySelectorAll('.language-selection-card');
	languageCards.forEach(function(card) {
		card.addEventListener('click', function() {
			const lang = this.getAttribute('data-lang');
			sessionStorage.setItem('renalinfo_language_selected', lang);
		});
	});
	
	// Handle close button
	const closeButton = document.querySelector('.language-modal-close');
	if (closeButton) {
		closeButton.addEventListener('click', function() {
			sessionStorage.setItem('renalinfo_language_selected', '<?php echo esc_js( $current_lang ); ?>');
			modal.setAttribute('hidden', '');
			document.body.classList.remove('modal-open');
		});
	}
	
	// Close modal on overlay click
	const overlay = document.querySelector('.language-modal-overlay');
	if (overlay) {
		overlay.addEventListener('click', function() {
			sessionStorage.setItem('renalinfo_language_selected', '<?php echo esc_js( $current_lang ); ?>');
			modal.setAttribute('hidden', '');
			document.body.classList.remove('modal-open');
		});
	}
	
	// Close modal on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && modal && !modal.hasAttribute('hidden')) {
			sessionStorage.setItem('renalinfo_language_selected', '<?php echo esc_js( $current_lang ); ?>');
			modal.setAttribute('hidden', '');
			document.body.classList.remove('modal-open');
		}
	});
	
	// Language switcher toggle
	const toggleButton = document.querySelector('.language-switcher-toggle');
	const dropdown = document.getElementById('language-dropdown');
	
	if (toggleButton && dropdown) {
		toggleButton.addEventListener('click', function() {
			const isExpanded = this.getAttribute('aria-expanded') === 'true';
			this.setAttribute('aria-expanded', !isExpanded);
			
			if (isExpanded) {
				dropdown.setAttribute('hidden', '');
			} else {
				dropdown.removeAttribute('hidden');
			}
		});
		
		// Close dropdown when clicking outside
		document.addEventListener('click', function(e) {
			if (!e.target.closest('.language-switcher')) {
				dropdown.setAttribute('hidden', '');
				toggleButton.setAttribute('aria-expanded', 'false');
			}
		});
		
		// Close dropdown on Escape
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && !dropdown.hasAttribute('hidden')) {
				dropdown.setAttribute('hidden', '');
				toggleButton.setAttribute('aria-expanded', 'false');
				toggleButton.focus();
			}
		});
	}
})();
</script>
