# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

RenalInfo is a specialized WordPress theme for pediatric nephrology clinics providing multilingual (English, Sinhala, Tamil) medical information for families and healthcare professionals. The theme features custom content templates, WCAG 2.1 AA accessible design, and a comprehensive custom fields system.

**Theme Location**: `renalinfo/` directory contains the WordPress theme
**Current Status**: Phase 2 complete (95%) - Custom fields and foundational infrastructure ready

## Development Commands

### Build & Watch
```powershell
cd renalinfo
npm install          # Install dependencies (first time only)
npm run build        # Build CSS and JavaScript for production
npm run watch        # Watch mode - auto-rebuild on file changes
```

### Linting & Code Quality
```powershell
npm run lint         # Run all linters (CSS, JS, PHP)
npm run lint:css     # Stylelint for CSS files
npm run lint:js      # ESLint for JavaScript files
npm run lint:php     # PHP_CodeSniffer (WordPress Coding Standards)
npm run lint:fix     # Auto-fix CSS and JS issues
```

### Individual Build Commands
```powershell
npm run build:css    # PostCSS: assets/css/src/style.css → assets/css/style.css
npm run build:js     # esbuild: assets/js/src/main.js → assets/js/main.js
```

## Core Architecture

### Custom Post Types (5 total)
Registered in `renalinfo/inc/custom-post-types.php`:

1. **Article** (`article`) - Medical information content
   - Slug: `/article/`
   - Templates: condition-explainer, treatment-procedure, professional-article
   - Custom fields: template, reading level, audience, medical review, FAQs, key takeaways

2. **Journey** (`journey`) - Multi-article patient journey modules
   - Slug: `/journey/`
   - Links ordered sequence of articles for progressive learning

3. **Staff** (`staff`) - Team member profiles
   - Slug: `/staff/`
   - Dual bios: personal (approachable) and professional (detailed)

4. **Medical Term** (`medical_term`) - Glossary entries
   - Slug: `/glossary/`
   - Dual definitions: simple (family-friendly) and technical (professional)
   - Admin columns show abbreviation, full name, synonyms

5. **Support Resource** (`support_resource`) - Community resources
   - Slug: `/resources/`

### Custom Taxonomies (4 total)
Registered in `renalinfo/inc/custom-taxonomies.php`:

- **Article Category** (`article_category`) - Hierarchical, for Articles
- **Audience Type** (`audience_type`) - Non-hierarchical, for Articles (family/professional)
- **Specialization** (`specialization`) - Non-hierarchical, for Staff
- **Resource Type** (`resource_type`) - Non-hierarchical, for Support Resources

### Custom Fields System
Native WordPress meta boxes (no plugin dependency) in `renalinfo/inc/custom-fields.php` (950+ lines):

**Article Meta Boxes:**
1. Article Details - template, reading level, audience, medical review date/reviewer
2. Article Content Options - key takeaways, related articles
3. FAQ Items - dynamic repeater with JavaScript add/remove

**Journey Meta Box:**
- Ordered article IDs, target audience, estimated completion time

**Staff Meta Boxes:**
1. Staff Details - role, credentials, bios (personal + professional with wp_editor), languages
2. Contact Information - email, phone, office hours

**Medical Term Meta Box:**
- Abbreviation, full name, synonyms, simple/technical definitions, pronunciation

### Template Functions (`inc/template-functions.php` - 450+ lines)

**Sanitization:**
- `renalinfo_sanitize_text()` - Text fields
- `renalinfo_sanitize_email()` - Email validation
- `renalinfo_sanitize_url()` - URL sanitization
- `renalinfo_sanitize_html()` - Safe HTML (wp_kses_post)
- `renalinfo_sanitize_ids()` - Integer ID arrays

**Validation:**
- `renalinfo_validate_article()` - Complete article validation (template, audience, reading level)
- `renalinfo_display_validation_errors()` - Admin notice display

**Content Helpers:**
- `renalinfo_get_reading_time()` - Auto-calculate (200 WPM)
- `renalinfo_get_related_articles()` - Manual + automatic by category
- `renalinfo_get_faq_items()` - Retrieve FAQ array
- `renalinfo_get_journey_articles()` - Ordered journey articles
- `renalinfo_get_journey_position()` - Navigation data (current/total/prev/next)
- `renalinfo_search_medical_terms()` - Search by abbreviation/synonym/name
- `renalinfo_is_recently_updated()` - Check update recency
- `renalinfo_get_responsive_image()` - Picture element with WebP sources

### Template Tags (`inc/template-tags.php` - 350+ lines)

Display functions for frontend output:
- `renalinfo_the_reading_time()` - "X min read"
- `renalinfo_breadcrumb()` - Accessible breadcrumb navigation
- `renalinfo_the_medical_review_date()` - Medical review display
- `renalinfo_the_update_notice()` - Update notice (90-day window)
- `renalinfo_the_key_takeaways()` - Formatted bullet list
- `renalinfo_the_faq_items()` - FAQ section with schema.org markup (FAQPage)
- `renalinfo_the_related_articles()` - Related articles grid
- `renalinfo_the_journey_navigation()` - Progress indicator + prev/next
- `renalinfo_the_audience_badge()` - "For Families" / "For Professionals"
- `renalinfo_the_staff_contact()` - Contact info with tel: links
- `renalinfo_the_version_history_link()` - Revision history (capability check)

### Multilingual Support (Polylang Integration)

**Language-specific fonts:**
- English: Inter
- Sinhala (`si`): Noto Sans Sinhala
- Tamil (`ta`): Noto Sans Tamil

**Functions:**
- `renalinfo_get_language_font()` - Get font-family for language code
- `renalinfo_language_body_class()` - Add `lang-{code}` body class
- `renalinfo_register_polylang_strings()` - Register translatable strings

**AJAX language switching:**
- Handler: `renalinfo_ajax_set_language()` in `inc/ajax-handlers.php`
- Returns translation URL or home URL if no translation exists

## CSS Architecture

### Design System (`assets/css/src/style.css` - 600+ lines)

**CSS Custom Properties (80+ variables):**

Color Palette:
- Primary blues: `--color-primary` (#4A90A4), `--color-primary-light`, `--color-primary-dark`
- Secondary greens: `--color-secondary` (#7CB8A8), variants
- Accent teals: `--color-accent` (#B4D4E1)
- Semantic: `--color-success`, `--color-warning`, `--color-error`, `--color-info`
- Gray scale: `--color-gray-50` through `--color-gray-900`

Typography:
- Font families: `--font-family-base`, `--font-family-sinhala`, `--font-family-tamil`
- Font sizes: `--font-size-xs` (12px) to `--font-size-5xl` (48px)
- Line heights: `--line-height-tight` (1.25) to `--line-height-loose` (2)

Spacing:
- Scale: `--spacing-xs` (4px) to `--spacing-4xl` (96px)

**Layout System:**
- `.container` - Max-width 1200px with responsive padding
- `.content-wrapper` - Max-width 720px for readability
- Grid utilities: `.grid`, `.grid-cols-1` to `.grid-cols-4`
- Responsive variants: `sm:`, `md:`, `lg:`

**Responsive Breakpoints (Mobile-First):**
- Base: 0-639px
- Small (sm): 640px+ (landscape phones)
- Medium (md): 768px+ (tablets)
- Large (lg): 1024px+ (desktops)
- XL: 1280px+
- 2XL: 1536px+

**Accessibility Features:**
- Skip-to-content link (keyboard-accessible)
- `.sr-only` - Screen reader only utility
- `:focus-visible` - 2px outline with offset
- `@media (prefers-contrast: high)` - High contrast mode
- `@media (prefers-reduced-motion: reduce)` - Reduced motion support

## File Organization

### Theme Structure
```
renalinfo/
├── inc/                        # PHP includes
│   ├── custom-post-types.php  # 5 CPTs
│   ├── custom-taxonomies.php  # 4 taxonomies
│   ├── custom-fields.php      # Native meta boxes (950+ lines)
│   ├── template-functions.php # Helpers (450+ lines)
│   ├── template-tags.php      # Display functions (350+ lines)
│   ├── ajax-handlers.php      # AJAX handlers (language switcher)
│   ├── widgets.php            # Widget areas
│   └── customizer.php         # Theme customizer (stub)
├── assets/
│   ├── css/
│   │   ├── style.css          # Compiled (production)
│   │   └── src/style.css      # Source (600+ lines)
│   └── js/
│       ├── main.js            # Compiled bundle
│       ├── main.js.map        # Source map
│       └── src/main.js        # Source
├── template-parts/
│   ├── content/               # Content templates
│   │   ├── content.php
│   │   ├── content-article.php
│   │   └── content-none.php
│   └── navigation/
│       └── language-switcher.php
├── languages/                  # Translation files
├── functions.php               # Theme setup
├── style.css                   # Theme header
├── header.php
├── footer.php
├── index.php
├── front-page.php             # Homepage template
├── archive.php                # Archive template
├── single-article.php         # Single article template
├── search.php                 # Search results
├── taxonomy-article_category.php  # Category archive
├── package.json               # Build configuration
├── postcss.config.js          # PostCSS config
├── phpcs.xml                  # PHP_CodeSniffer config
└── SETUP.md                   # Setup documentation
```

### Source vs. Compiled Files

**Always edit source files:**
- CSS: `assets/css/src/style.css`
- JavaScript: `assets/js/src/main.js`

**Never edit compiled files directly:**
- `assets/css/style.css` (auto-generated by PostCSS)
- `assets/js/main.js` (auto-generated by esbuild)

## WordPress Integration

### Theme Setup (`functions.php`)

**Constants:**
- `RENALINFO_VERSION` - Theme version (1.0.0)
- `RENALINFO_THEME_DIR` - Theme directory path
- `RENALINFO_THEME_URI` - Theme directory URL

**Theme Support:**
- Post thumbnails with custom sizes: `article-hero` (1200x600), `staff-profile` (400x400), `article-thumb` (300x200)
- Custom logo (400x100, flexible)
- HTML5 markup
- Title tag management
- Responsive embeds
- Editor styles

**Navigation Menus:**
- `primary-menu` - Main site navigation
- `footer-menu` - Footer navigation

**AJAX Configuration:**
- Nonce: `renalinfo_nonce`
- Localized object: `renalInfoAjax.ajaxUrl`, `renalInfoAjax.nonce`

### Permalink Flush Required

After theme activation or custom post type changes:
```
Settings → Permalinks → Save Changes
```
This flushes rewrite rules for custom post types.

## Code Quality Standards

### WordPress Coding Standards
- PHP_CodeSniffer with WordPress ruleset
- Proper sanitization on input (all `$_POST` data)
- Proper escaping on output (`esc_html`, `esc_attr`, `esc_url`)
- Nonce verification for AJAX (CSRF protection)
- Capability checks (`current_user_can`)
- Internationalization (`__()`, `_e()`, `esc_html__()`)

### Security Practices
- All meta save handlers verify nonce and capabilities
- AJAX handlers use `renalinfo_verify_ajax_nonce()`
- User input sanitized with type-specific functions
- HTML output uses `wp_kses_post()` for safe HTML

### Performance Optimizations
- PostCSS autoprefixer for browser compatibility
- cssnano minification for smaller file sizes
- esbuild bundling (fast compilation, ES2015 target)
- Font-display: swap (prevent FOIT)
- Lazy loading attributes ready
- WebP format support built-in

## Testing & Validation

### Manual Testing Checklist
1. Theme activation in WordPress
2. Custom post types appear in admin
3. Custom fields display in meta boxes
4. Permalinks flush (Settings → Permalinks)
5. Navigation menu assignment (Appearance → Menus)
6. Polylang language setup (English, Sinhala, Tamil)
7. Create test content for each post type
8. Verify reading time calculation
9. Test FAQ schema.org markup
10. Keyboard navigation (Tab through all elements)
11. Screen reader testing (NVDA/JAWS)
12. Color contrast validation (4.5:1 ratio)

### Accessibility Testing
- Skip-to-content link functional
- Focus indicators visible (2px outline)
- ARIA labels present on navigation
- Images have alt text
- Forms have proper labels
- Reduced motion respected
- High contrast mode supported

## Implementation Status

### Completed (Phase 1 & 2)
- ✅ Theme structure and core files
- ✅ 5 custom post types
- ✅ 4 custom taxonomies
- ✅ Custom fields system (native meta boxes)
- ✅ Template functions (sanitization, validation, helpers)
- ✅ Template tags (display functions)
- ✅ CSS framework (variables, grid, typography)
- ✅ Multilingual font support
- ✅ AJAX language switcher
- ✅ Build system (PostCSS, esbuild)
- ✅ Accessibility baseline (WCAG 2.1 AA)
- ✅ Schema.org FAQ markup

### Outstanding Items
- ⏳ screenshot.png (1200x900px) - Create after Phase 3 templates
- ⏳ WordPress revisions documentation - Add to readme.txt

### Next Phase (Phase 3 - User Story 1 MVP)
Goal: Parents can find and read condition explainer articles within 3 clicks

Tasks:
1. Homepage template enhancements
2. Archive page styling
3. Single article template completion
4. Condition-explainer template part
5. Medical term tooltip integration
6. Responsive image implementation with WebP
7. Support resource links section
8. Breadcrumb integration

## Important Notes

### Theme Dependencies
**Required WordPress Plugins:**
- Polylang (for multilingual support)

**Optional but Recommended:**
- Enable `WP_POST_REVISIONS` in `wp-config.php` for version control

### Medical Content Requirements
- Family content: Reading level 7-10 (Flesch-Kincaid grade)
- Professional content: No reading level restriction
- All articles require medical review date
- Medical reviewer name required for family content
- FAQ items support schema.org structured data for SEO

### Language-Specific Considerations
- Sinhala and Tamil content use larger line-height (1.8 vs 1.6)
- Font loading uses `font-display: swap` for performance
- Body class includes `lang-{code}` for language-specific styling
- Translation notices display when content unavailable in selected language

### Performance Targets (Phase 14)
- PageSpeed Insights: ≥90 (mobile & desktop)
- Largest Contentful Paint (LCP): <2.5s
- First Input Delay (FID): <100ms
- Cumulative Layout Shift (CLS): <0.1
- Page Load Time: <3s on 5 Mbps connection
