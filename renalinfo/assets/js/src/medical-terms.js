/**
 * Medical Terms Tooltip Component
 * 
 * Provides accessible tooltips for medical terms with keyboard navigation
 * and touch support for mobile devices
 * 
 * @package RenalInfo
 */

(function() {
	'use strict';

	/**
	 * Medical term tooltip manager
	 */
	class MedicalTermTooltip {
		constructor() {
			this.tooltips = new Map();
			this.activeTooltip = null;
			this.init();
		}

		/**
		 * Initialize tooltip system
		 */
		init() {
			// Wait for DOM to be ready
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', () => this.setupTooltips());
			} else {
				this.setupTooltips();
			}
		}

		/**
		 * Set up all medical term tooltips
		 */
		setupTooltips() {
			// Find all medical terms in glossary sections
			const glossaryItems = document.querySelectorAll('.glossary-item');
			
			if (glossaryItems.length === 0) {
				return;
			}

			// Build tooltip data from glossary
			glossaryItems.forEach(item => {
				const termElement = item.querySelector('.glossary-term strong');
				const definitionElement = item.querySelector('.glossary-definition');
				const pronunciationElement = item.querySelector('.glossary-pronunciation');
				
				if (termElement && definitionElement) {
					const term = termElement.textContent.trim();
					const definition = definitionElement.textContent.trim();
					const pronunciation = pronunciationElement ? pronunciationElement.textContent.trim() : '';
					
					this.tooltips.set(term.toLowerCase(), {
						term: term,
						definition: definition,
						pronunciation: pronunciation
					});
				}
			});

			// Add tooltips to article content
			this.processArticleContent();

			// Set up global event listeners
			this.setupEventListeners();
		}

		/**
		 * Process article content and add tooltip markup
		 */
		processArticleContent() {
			const contentAreas = document.querySelectorAll('.article-content, .entry-content');
			
			contentAreas.forEach(content => {
				// Skip if already processed
				if (content.hasAttribute('data-tooltips-processed')) {
					return;
				}

				// Process text nodes
				this.processNode(content);

				// Mark as processed
				content.setAttribute('data-tooltips-processed', 'true');
			});
		}

		/**
		 * Recursively process text nodes to add tooltip markup
		 */
		processNode(node) {
			// Skip script, style, and already processed elements
			if (
				node.nodeType === Node.ELEMENT_NODE && 
				(node.tagName === 'SCRIPT' || node.tagName === 'STYLE' || 
				 node.classList.contains('medical-term-tooltip') ||
				 node.classList.contains('glossary-section'))
			) {
				return;
			}

			if (node.nodeType === Node.TEXT_NODE) {
				this.addTooltipsToText(node);
			} else if (node.nodeType === Node.ELEMENT_NODE) {
				// Process child nodes
				Array.from(node.childNodes).forEach(child => this.processNode(child));
			}
		}

		/**
		 * Add tooltip markup to text content
		 */
		addTooltipsToText(textNode) {
			const text = textNode.textContent;
			let modifiedHTML = '';
			let lastIndex = 0;
			let hasMatches = false;

			// Create regex pattern from all terms
			const terms = Array.from(this.tooltips.keys());
			if (terms.length === 0) return;

			// Sort by length (longest first) to match longer terms first
			terms.sort((a, b) => b.length - a.length);

			// Create pattern that matches whole words only
			const pattern = new RegExp(
				'\\b(' + terms.map(t => this.escapeRegex(t)).join('|') + ')\\b',
				'gi'
			);

			let match;
			while ((match = pattern.exec(text)) !== null) {
				hasMatches = true;
				const matchedTerm = match[0];
				const termData = this.tooltips.get(matchedTerm.toLowerCase());

				// Add text before match
				modifiedHTML += this.escapeHTML(text.substring(lastIndex, match.index));

				// Add tooltip markup
				modifiedHTML += this.createTooltipMarkup(matchedTerm, termData);

				lastIndex = match.index + matchedTerm.length;
			}

			// If matches found, replace text node
			if (hasMatches) {
				modifiedHTML += this.escapeHTML(text.substring(lastIndex));
				
				const wrapper = document.createElement('span');
				wrapper.innerHTML = modifiedHTML;
				
				textNode.parentNode.replaceChild(wrapper, textNode);
			}
		}

		/**
		 * Create tooltip HTML markup
		 */
		createTooltipMarkup(term, data) {
			const tooltipId = 'tooltip-' + this.generateId();
			
			return `<span class="medical-term-wrapper">
				<button type="button" 
					class="medical-term-trigger" 
					aria-describedby="${tooltipId}"
					data-term="${this.escapeHTML(term)}"
					data-definition="${this.escapeHTML(data.definition)}"
					data-pronunciation="${this.escapeHTML(data.pronunciation)}">
					${this.escapeHTML(term)}
				</button>
				<span role="tooltip" 
					id="${tooltipId}" 
					class="medical-term-tooltip" 
					aria-hidden="true">
					<strong class="tooltip-term">${this.escapeHTML(data.term)}</strong>
					${data.pronunciation ? `<span class="tooltip-pronunciation">${this.escapeHTML(data.pronunciation)}</span>` : ''}
					<span class="tooltip-definition">${this.escapeHTML(data.definition)}</span>
				</span>
			</span>`;
		}

		/**
		 * Set up event listeners
		 */
		setupEventListeners() {
			// Handle tooltip trigger interactions
			document.addEventListener('click', (e) => {
				const trigger = e.target.closest('.medical-term-trigger');
				
				if (trigger) {
					e.preventDefault();
					this.toggleTooltip(trigger);
				} else if (!e.target.closest('.medical-term-tooltip')) {
					// Close tooltip if clicking outside
					this.hideAllTooltips();
				}
			});

			// Keyboard support
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape') {
					this.hideAllTooltips();
				}
			});

			// Handle focus events
			document.addEventListener('focusin', (e) => {
				if (e.target.classList.contains('medical-term-trigger')) {
					// Show tooltip on focus for keyboard users
					this.showTooltip(e.target);
				}
			});

			document.addEventListener('focusout', (e) => {
				if (e.target.classList.contains('medical-term-trigger')) {
					// Small delay to allow clicking tooltip content
					setTimeout(() => {
						if (!document.activeElement?.closest('.medical-term-wrapper')) {
							this.hideTooltip(e.target);
						}
					}, 100);
				}
			});

			// Close tooltips on scroll
			let scrollTimeout;
			document.addEventListener('scroll', () => {
				clearTimeout(scrollTimeout);
				scrollTimeout = setTimeout(() => {
					this.hideAllTooltips();
				}, 100);
			}, true);
		}

		/**
		 * Toggle tooltip visibility
		 */
		toggleTooltip(trigger) {
			const tooltip = trigger.nextElementSibling;
			
			if (tooltip.getAttribute('aria-hidden') === 'false') {
				this.hideTooltip(trigger);
			} else {
				this.showTooltip(trigger);
			}
		}

		/**
		 * Show tooltip
		 */
		showTooltip(trigger) {
			// Hide any active tooltips
			this.hideAllTooltips();

			const tooltip = trigger.nextElementSibling;
			
			// Show tooltip
			tooltip.setAttribute('aria-hidden', 'false');
			trigger.setAttribute('aria-expanded', 'true');
			
			// Position tooltip
			this.positionTooltip(trigger, tooltip);
			
			this.activeTooltip = tooltip;
		}

		/**
		 * Hide specific tooltip
		 */
		hideTooltip(trigger) {
			const tooltip = trigger.nextElementSibling;
			tooltip.setAttribute('aria-hidden', 'true');
			trigger.setAttribute('aria-expanded', 'false');
			
			if (this.activeTooltip === tooltip) {
				this.activeTooltip = null;
			}
		}

		/**
		 * Hide all tooltips
		 */
		hideAllTooltips() {
			document.querySelectorAll('.medical-term-tooltip[aria-hidden="false"]').forEach(tooltip => {
				tooltip.setAttribute('aria-hidden', 'true');
				const trigger = tooltip.previousElementSibling;
				if (trigger) {
					trigger.setAttribute('aria-expanded', 'false');
				}
			});
			this.activeTooltip = null;
		}

		/**
		 * Position tooltip relative to trigger
		 */
		positionTooltip(trigger, tooltip) {
			const triggerRect = trigger.getBoundingClientRect();
			const tooltipRect = tooltip.getBoundingClientRect();
			const viewportWidth = window.innerWidth;
			const viewportHeight = window.innerHeight;

			// Reset positioning
			tooltip.style.left = '';
			tooltip.style.right = '';
			tooltip.style.top = '';
			tooltip.style.bottom = '';

			// Default: position below trigger
			let position = 'bottom';

			// Check if tooltip fits below
			if (triggerRect.bottom + tooltipRect.height + 10 > viewportHeight) {
				position = 'top';
			}

			// Check horizontal positioning
			const tooltipLeft = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
			
			if (tooltipLeft < 10) {
				// Align to left edge
				tooltip.style.left = '10px';
			} else if (tooltipLeft + tooltipRect.width > viewportWidth - 10) {
				// Align to right edge
				tooltip.style.right = '10px';
			}

			// Add position class for arrow positioning
			tooltip.setAttribute('data-position', position);
		}

		/**
		 * Escape regex special characters
		 */
		escapeRegex(str) {
			return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
		}

		/**
		 * Escape HTML special characters
		 */
		escapeHTML(str) {
			const div = document.createElement('div');
			div.textContent = str;
			return div.innerHTML;
		}

		/**
		 * Generate unique ID
		 */
		generateId() {
			return Math.random().toString(36).substr(2, 9);
		}
	}

	// Initialize on page load
	new MedicalTermTooltip();

})();
