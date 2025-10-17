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

=== Polylang Plugin Setup (Required for Multilingual Support) ===

**Step 1: Install Polylang Plugin**
1. Navigate to Plugins > Add New in WordPress admin
2. Search for "Polylang"
3. Click "Install Now" on "Polylang" by WP SYNTEX
4. Click "Activate" after installation completes

**Step 2: Configure Languages**
1. Navigate to Languages > Languages in WordPress admin
2. Add three languages in this order:
   
   **English (Primary Language)**
   - Full name: English
   - Locale: en_US
   - Language code: en
   - Text direction: ltr (left to right)
   - Order: 1
   - Set as default language: Yes
   
   **Sinhala**
   - Full name: සිංහල (Sinhala)
   - Locale: si_LK
   - Language code: si
   - Text direction: ltr (left to right)
   - Order: 2
   
   **Tamil**
   - Full name: தமிழ் (Tamil)
   - Locale: ta_LK
   - Language code: ta
   - Text direction: ltr (left to right)
   - Order: 3

3. Click "Add new language" for each language configuration above

**Step 3: Configure Polylang Settings**
Navigate to Languages > Settings and configure:

*URL Modifications Tab:*
- URL structure: "The language is set from the directory name in pretty permalinks"
  (Example: yoursite.com/en/, yoursite.com/si/, yoursite.com/ta/)
- Hide URL language information for default language: No (recommended for clarity)
- Force language in URLs: Yes

*Media Tab:*
- Media: "Translate media" (allows different images for different languages)

*Custom Post Types and Taxonomies Tab:*
Enable translation for:
- ✅ Articles (article)
- ✅ Journeys (journey)
- ✅ Staff (staff)
- ✅ Medical Terms (medical_term)
- ✅ Support Resources (support_resource)
- ✅ Article Categories (article_category)
- ✅ Audience Types (audience_type)
- ✅ Specializations (specialization)
- ✅ Resource Types (resource_type)

*Synchronization Tab:*
Synchronize the following for ease of content management:
- ✅ Post date
- ✅ Comment status
- ✅ Ping status
- ✅ Sticky posts
- ✅ Post format
- ✅ Taxonomies (article categories, etc.)
- ✅ Custom fields (optional - allows auto-copy of custom field values)

**Step 4: Configure Language Switcher**
1. Navigate to Appearance > Widgets
2. Add "Language Switcher" widget to desired location (Header, Sidebar, or Footer)
3. Configure display options:
   - Show as: Dropdown
   - Display language names
   - Show flags: Yes
   - Force link to front page: No
   - Hide current language: No
   - Hide if no translation available: Yes (recommended)

Note: The theme includes a custom language switcher in the header with first-visit language selection prompt.

**Step 5: Create Navigation Menus for Each Language**
1. Navigate to Appearance > Menus
2. Create three menus:
   - "Primary Menu - English" (Language: English)
   - "Primary Menu - Sinhala" (Language: Sinhala)
   - "Primary Menu - Tamil" (Language: Tamil)
3. Assign each menu to the "Primary Menu" location for its respective language
4. Repeat for Footer Menu if needed

**Step 6: Translate Default Content**
1. Navigate to Pages > All Pages
2. For each page, you'll see language flags in the listing
3. Click the "+" icon under the Sinhala or Tamil flag to create a translation
4. Add translated content and publish
5. Repeat for Articles, Medical Terms, and other custom post types

**Step 7: Register Theme Strings for Translation**
The theme automatically registers the following strings for translation:
- Theme customizer settings (site title, hero text, phone number)
- Navigation menu labels
- Widget titles
- Footer text

To translate these:
1. Navigate to Languages > String translations
2. Search for the string you want to translate
3. Enter translations for Sinhala and Tamil
4. Click "Save"

**Common String Translations Needed:**
- "Read More" → "වැඩිදුර කියවන්න" (Sinhala) / "மேலும் வாசிக்க" (Tamil)
- "For Families" → "පවුල් සඳහා" (Sinhala) / "குடும்பங்களுக்கு" (Tamil)
- "For Professionals" → "වෘත්තිකයන් සඳහා" (Sinhala) / "வல்லுநர்களுக்கு" (Tamil)
- "Related Articles" → "ආශ්‍රිත ලිපි" (Sinhala) / "தொடர்புடைய கட்டுரைகள்" (Tamil)
- "Medical Terms Explained" → "වෛද්‍ය පද පැහැදිලි කිරීම්" (Sinhala) / "மருத்துவ சொற்கள் விளக்கம்" (Tamil)

**Step 8: Test Language Switching**
1. Visit your site's homepage
2. Use the language switcher to change languages
3. Verify:
   - ✅ Fonts render correctly (Noto Sans Sinhala/Tamil load)
   - ✅ Content displays in selected language
   - ✅ Untranslated content shows fallback notice
   - ✅ Language persists during browsing session
   - ✅ Navigation menus display in correct language

=== Troubleshooting Polylang ===

**Issue: Fonts not displaying correctly for Sinhala/Tamil**
- Solution: Clear browser cache and ensure Google Fonts API is not blocked

**Issue: Language switcher not appearing**
- Solution: Check that widget is added to a widget area or custom language switcher is in header.php

**Issue: Translations not appearing**
- Solution: Ensure content is published (not draft) and linked to original language post

**Issue: Custom fields not translating**
- Solution: Enable "Custom fields" synchronization in Polylang settings, or manually enter values for each language

**Issue: Language resets to default on page load**
- Solution: Check browser sessionStorage is enabled and not blocked by privacy settings

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
