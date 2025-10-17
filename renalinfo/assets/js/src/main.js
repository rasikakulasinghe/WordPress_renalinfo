/**
 * Main JavaScript file for RenalInfo theme
 *
 * @package RenalInfo
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * Initialize theme functionality when DOM is ready
	 */
	function init() {
		// Mobile menu toggle
		initMobileMenu();

		// Skip link focus fix for WebKit browsers
		skipLinkFocusFix();
	}

	/**
	 * Initialize mobile menu toggle
	 */
	function initMobileMenu() {
		const menuToggle = document.querySelector('.menu-toggle');
		const navigation = document.querySelector('#site-navigation');

		if (!menuToggle || !navigation) {
			return;
		}

		menuToggle.addEventListener('click', function() {
			const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
			menuToggle.setAttribute('aria-expanded', !expanded);
			navigation.classList.toggle('toggled');
		});

		// Close menu on ESC key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && navigation.classList.contains('toggled')) {
				menuToggle.setAttribute('aria-expanded', 'false');
				navigation.classList.remove('toggled');
				menuToggle.focus();
			}
		});
	}

	/**
	 * Fix skip link focus in WebKit browsers
	 */
	function skipLinkFocusFix() {
		const isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1;
		const isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1;
		const isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;

		if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
			window.addEventListener('hashchange', function() {
				const id = location.hash.substring(1);
				let element;

				if (!/^[A-z0-9_-]+$/.test(id)) {
					return;
				}

				element = document.getElementById(id);

				if (element) {
					if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
						element.tabIndex = -1;
					}
					element.focus();
				}
			}, false);
		}
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
