=== RenalInfo Paediatric Nephrology ===

Contributors: rasikakulasinghe
Tags: accessibility-ready, custom-logo, custom-menu, featured-images, footer-widgets, healthcare, medical, multilingual, translation-ready, two-columns
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A specialized WordPress theme for paediatric nephrology clinics providing multilingual medical information with accessible design.

== Description ==

RenalInfo is a comprehensive WordPress theme designed specifically for paediatric nephrology clinics to provide clear, accessible medical information to families and healthcare professionals. The theme features:

* **Multilingual Support**: Full support for English, Sinhala, and Tamil languages using the Polylang plugin
* **Custom Content Templates**: Specialized templates for condition explainers, treatment procedures, professional articles, and patient journey modules
* **Accessibility Ready**: WCAG 2.1 AA compliant with high-contrast mode, keyboard navigation, and screen reader support
* **Medical-Specific Features**: Built-in medical term glossary, patient journey navigation, staff profiles, and support resources
* **Performance Optimized**: Meets Core Web Vitals targets with PageSpeed score ≥90
* **Mobile-First Design**: Fully responsive with touch-optimized interface
* **Privacy-Focused**: No persistent user tracking, session-only language preferences

=== Core Features ===

* Custom post types for articles, journeys, staff, medical terms, and support resources
* Dual-audience content (families and healthcare professionals)
* Medical term tooltips and glossary
* Patient journey modules with progress tracking
* Staff profiles with credentials and specializations
* Enhanced search with medical abbreviation matching
* Optional article feedback system
* Version control and update notices
* Responsive images with WebP support

=== Requirements ===

* WordPress 6.0 or higher
* PHP 7.4 or higher
* Polylang plugin (free version) for multilingual support
* MySQL 5.7+ or MariaDB 10.3+

== Installation ==

1. Upload the `renalinfo` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Appearance > Themes' menu in WordPress
3. Install and activate the Polylang plugin from the WordPress plugin directory
4. Configure Polylang with three languages: English, Sinhala, and Tamil
5. Navigate to Appearance > Customize to configure theme settings
6. Create navigation menus for Primary Menu and Footer Menu locations
7. Begin adding content using the custom post types

== Build Process ==

This theme uses modern build tools for asset optimization:

1. Install Node.js (v16 or higher)
2. Navigate to the theme directory
3. Run `npm install` to install dependencies
4. Run `npm run build` to compile assets for production
5. Run `npm run watch` during development for automatic rebuilding

=== Available npm Scripts ===

* `npm run build` - Build all assets (CSS and JavaScript)
* `npm run watch` - Watch for changes and rebuild automatically
* `npm run lint` - Run all linters (CSS, JavaScript, PHP)
* `npm run lint:fix` - Auto-fix linting issues where possible

== Frequently Asked Questions ==

= Do I need to install any plugins? =

Yes, the Polylang plugin (free version) is required for multilingual functionality. Other features work without additional plugins.

= Can I use this theme without multilingual support? =

Yes, the theme functions in English-only mode, but installing Polylang is recommended for full functionality.

= Is this theme compatible with page builders? =

The theme is designed for native WordPress content editing. Page builder compatibility has not been extensively tested.

= How do I add medical terms to the glossary? =

Navigate to Articles > Medical Terms in the WordPress admin and add new terms with definitions and optional abbreviations.

= Can I customize the color scheme? =

Yes, navigate to Appearance > Customize to modify colors, fonts, and other visual settings.

== Changelog ==

= 1.0.0 =
* Initial release
* Custom post types for articles, journeys, staff, medical terms, and support resources
* Multilingual support with Polylang integration
* Accessibility features meeting WCAG 2.1 AA standards
* Patient journey navigation system
* Medical term glossary with tooltips
* Staff profile system
* Enhanced search functionality
* Performance optimization meeting Core Web Vitals targets

== Upgrade Notice ==

= 1.0.0 =
Initial release of RenalInfo Paediatric Nephrology theme.

== Credits ==

* Google Fonts: Inter, Noto Sans Sinhala, Noto Sans Tamil (SIL Open Font License)
* PostCSS and esbuild for build tools (MIT License)
* WordPress Theme Coding Standards

== Support ==

For support, please visit: https://github.com/rasikakulasinghe/WordPress_renalinfo/issues

== Theme Development ==

Development repository: https://github.com/rasikakulasinghe/WordPress_renalinfo
