/**
 * Language Handler
 *
 * Manages language selection, persistence, and AJAX switching
 *
 * @package RenalInfo
 * @since 1.0.0
 */

class LanguageHandler {
	constructor() {
		this.storageKey = 'renalinfo_language_preference';
		this.currentLang = document.documentElement.lang || 'en';
		this.ajaxUrl = renalInfoAjax?.ajaxUrl || '/wp-admin/admin-ajax.php';
		this.nonce = renalInfoAjax?.nonce || '';
		
		this.init();
	}

	/**
	 * Initialize language handler
	 */
	init() {
		// Check URL parameters first
		this.checkUrlParameter();
		
		// Set up event listeners
		this.setupEventListeners();
		
		// Store current language preference
		this.storeLanguagePreference(this.getCurrentLanguage());
	}

	/**
	 * Get current language from document
	 * 
	 * @return {string} Language code
	 */
	getCurrentLanguage() {
		const html = document.documentElement;
		const lang = html.getAttribute('lang');
		
		if (lang) {
			// Extract language code (e.g., 'en' from 'en-US')
			return lang.split('-')[0].toLowerCase();
		}
		
		return 'en';
	}

	/**
	 * Check for language parameter in URL
	 */
	checkUrlParameter() {
		const urlParams = new URLSearchParams(window.location.search);
		const langParam = urlParams.get('lang');
		
		if (langParam) {
			// Valid language codes
			const validLangs = ['en', 'si', 'ta'];
			
			if (validLangs.includes(langParam)) {
				// Store preference
				this.storeLanguagePreference(langParam);
				
				// If language differs from current, switch
				if (langParam !== this.getCurrentLanguage()) {
					this.switchLanguage(langParam, true);
				} else {
					// Remove lang parameter from URL (clean URL)
					this.removeUrlParameter();
				}
			}
		}
	}

	/**
	 * Remove lang parameter from URL without reload
	 */
	removeUrlParameter() {
		const url = new URL(window.location.href);
		url.searchParams.delete('lang');
		
		// Update URL without reload
		if (window.history && window.history.replaceState) {
			window.history.replaceState({}, document.title, url.toString());
		}
	}

	/**
	 * Store language preference in sessionStorage
	 * 
	 * @param {string} lang Language code
	 */
	storeLanguagePreference(lang) {
		try {
			sessionStorage.setItem(this.storageKey, lang);
		} catch (e) {
			console.warn('SessionStorage not available:', e);
		}
	}

	/**
	 * Get stored language preference
	 * 
	 * @return {string|null} Stored language code or null
	 */
	getStoredLanguagePreference() {
		try {
			return sessionStorage.getItem(this.storageKey);
		} catch (e) {
			console.warn('SessionStorage not available:', e);
			return null;
		}
	}

	/**
	 * Set up event listeners for language switching
	 */
	setupEventListeners() {
		// Language switcher links
		document.addEventListener('click', (e) => {
			const link = e.target.closest('.language-link, .language-selection-card');
			
			if (link && link.hasAttribute('data-lang')) {
				e.preventDefault();
				const targetLang = link.getAttribute('data-lang');
				this.switchLanguage(targetLang);
			}
		});

		// Handle language switcher dropdown links
		const languageLinks = document.querySelectorAll('.language-dropdown .language-link');
		languageLinks.forEach(link => {
			link.addEventListener('click', (e) => {
				// Allow default behavior but store preference
				const lang = link.getAttribute('lang');
				if (lang) {
					this.storeLanguagePreference(lang);
				}
			});
		});
	}

	/**
	 * Switch language via AJAX
	 * 
	 * @param {string} targetLang Target language code
	 * @param {boolean} fromUrl Whether switch was triggered by URL parameter
	 */
	switchLanguage(targetLang, fromUrl = false) {
		// Don't switch if already on target language
		if (!fromUrl && targetLang === this.getCurrentLanguage()) {
			return;
		}

		// Show loading indicator (optional)
		this.showLoadingIndicator();

		// Get current post ID if on single post/page
		const postId = this.getPostId();

		// Prepare AJAX data
		const data = new FormData();
		data.append('action', 'renalinfo_set_language');
		data.append('nonce', this.nonce);
		data.append('language', targetLang);
		data.append('current_url', window.location.href);
		
		if (postId) {
			data.append('post_id', postId);
		}

		// Send AJAX request
		fetch(this.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
		.then(response => response.json())
		.then(result => {
			this.hideLoadingIndicator();

			if (result.success && result.data.redirect_url) {
				// Store preference
				this.storeLanguagePreference(targetLang);
				
				// Redirect to translated page
				window.location.href = result.data.redirect_url;
			} else {
				// Fallback: add language parameter and reload
				const url = new URL(window.location.href);
				url.searchParams.set('lang', targetLang);
				window.location.href = url.toString();
			}
		})
		.catch(error => {
			console.error('Language switch error:', error);
			this.hideLoadingIndicator();
			
			// Fallback: add language parameter and reload
			const url = new URL(window.location.href);
			url.searchParams.set('lang', targetLang);
			window.location.href = url.toString();
		});
	}

	/**
	 * Get post ID from body class or meta tag
	 * 
	 * @return {number|null} Post ID or null
	 */
	getPostId() {
		// Try to get from body class (WordPress adds postid-{id})
		const bodyClasses = document.body.className.split(' ');
		for (const className of bodyClasses) {
			if (className.startsWith('postid-')) {
				const id = parseInt(className.replace('postid-', ''), 10);
				if (!isNaN(id)) {
					return id;
				}
			}
		}

		// Try to get from meta tag (if added by theme)
		const metaTag = document.querySelector('meta[name="post-id"]');
		if (metaTag) {
			const id = parseInt(metaTag.getAttribute('content'), 10);
			if (!isNaN(id)) {
				return id;
			}
		}

		return null;
	}

	/**
	 * Show loading indicator
	 */
	showLoadingIndicator() {
		// Add loading class to body
		document.body.classList.add('language-switching');
		
		// Create or show loading overlay
		let overlay = document.getElementById('language-switch-overlay');
		
		if (!overlay) {
			overlay = document.createElement('div');
			overlay.id = 'language-switch-overlay';
			overlay.className = 'language-switch-overlay';
			overlay.innerHTML = `
				<div class="language-switch-spinner">
					<div class="spinner"></div>
					<p>${this.getLoadingText()}</p>
				</div>
			`;
			document.body.appendChild(overlay);
		}
		
		overlay.style.display = 'flex';
	}

	/**
	 * Hide loading indicator
	 */
	hideLoadingIndicator() {
		document.body.classList.remove('language-switching');
		
		const overlay = document.getElementById('language-switch-overlay');
		if (overlay) {
			overlay.style.display = 'none';
		}
	}

	/**
	 * Get loading text (can be translated)
	 * 
	 * @return {string} Loading message
	 */
	getLoadingText() {
		const messages = {
			'en': 'Switching language...',
			'si': 'භාෂාව වෙනස් කරමින්...',
			'ta': 'மொழியை மாற்றுகிறது...'
		};
		
		return messages[this.currentLang] || messages.en;
	}
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', () => {
		new LanguageHandler();
	});
} else {
	new LanguageHandler();
}

export default LanguageHandler;
