# Research: Paediatric Nephrology Clinic Website

**Feature**: 001-pediatric-nephrology-website  
**Date**: 2025-10-16  
**Status**: Phase 0 Research Complete

## Overview

This document consolidates research findings for technical decisions required to implement the paediatric nephrology clinic WordPress theme. All "NEEDS CLARIFICATION" items from the Technical Context have been resolved through research of WordPress best practices, multilingual solutions, and build tool ecosystems.

---

## 1. Multilingual Plugin Selection

### Decision
**Polylang (Free version)** for multilingual content management

### Rationale
- **Theme-friendly**: Polylang works seamlessly with custom post types and taxonomies without requiring special configuration
- **No hardcoded strings**: Unlike WPML, Polylang doesn't inject JavaScript or modify database queries as extensively, giving theme more control
- **Performance**: Lighter weight than WPML; better suited for the performance targets (PageSpeed ≥ 90)
- **URL structure**: Supports the required `/en/`, `/si/`, `/ta/` URL structure (FR-048)
- **Cost**: Free version sufficient for the requirements (3 languages, no eCommerce)
- **Developer-friendly**: Better documented API for theme integration
- **Fallback handling**: Built-in language fallback mechanism aligns with FR-049 requirements

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **WPML** | Expensive ($99+/year), more complex than needed, adds significant overhead, primarily eCommerce-focused features not needed |
| **qTranslate-X** | Discontinued/unmaintained, security risks |
| **TranslatePress** | Visual translation approach doesn't fit medical content workflow (professional translation required), page builder dependency |
| **Custom solution** | Reinventing wheel, higher maintenance, wouldn't meet timeline, lacks features like translation management |

### Implementation Notes
- Use `pll_register_string()` for theme strings (navigation, UI elements)
- Query modifications with `pll_current_language()` for content filtering
- Language switcher via `pll_the_languages()` with custom styling
- Fallback logic: Check `pll_get_post()` for translation existence before displaying language option

---

## 2. Build Tools Selection

### Decision
**npm scripts + PostCSS + esbuild** (modern lightweight stack)

### Rationale
- **Simplicity**: npm scripts avoid Webpack/Gulp configuration complexity
- **Performance**: esbuild is 10-100x faster than Webpack for JavaScript bundling/transpilation
- **Modern CSS**: PostCSS with autoprefixer handles SCSS-like nesting, variables, and browser prefixes
- **Maintainability**: Fewer dependencies, easier for future developers to understand
- **Size**: Smaller `node_modules` footprint (~50MB vs 200MB+ for Webpack)
- **WordPress alignment**: Many modern WordPress themes moving away from Webpack complexity

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Webpack** | Overly complex for theme needs, slow build times, heavy configuration |
| **Gulp** | Additional abstraction layer, streaming API not needed, declining ecosystem |
| **Parcel** | Zero-config sounds good but less control over output structure, immature WordPress ecosystem |
| **Vite** | Excellent but overkill for non-SPA theme, dev server features not beneficial |

### Build Configuration

**package.json scripts**:
```json
{
  "scripts": {
    "css:build": "postcss assets/css/src/style.css -o assets/css/style.css",
    "css:watch": "postcss assets/css/src/style.css -o assets/css/style.css --watch",
    "js:build": "esbuild assets/js/src/main.js --bundle --minify --target=es2017 --outfile=assets/js/main.min.js",
    "js:watch": "esbuild assets/js/src/main.js --bundle --watch --target=es2017 --outfile=assets/js/main.min.js",
    "build": "npm run css:build && npm run js:build",
    "watch": "npm run css:watch & npm run js:watch",
    "lint:php": "phpcs --standard=WordPress .",
    "lint:css": "stylelint 'assets/css/src/**/*.css'",
    "lint:js": "eslint assets/js/src",
    "lint": "npm run lint:php && npm run lint:css && npm run lint:js"
  }
}
```

**Dependencies**:
- `postcss` + `postcss-cli`: CSS processing
- `postcss-preset-env`: Modern CSS features (nesting, custom properties)
- `autoprefixer`: Browser prefix management
- `cssnano`: CSS minification
- `esbuild`: JavaScript bundling and transpilation
- `phpcs`: PHP linting (WordPress standards)
- `stylelint`: CSS linting
- `eslint`: JavaScript linting

### Implementation Notes
- Source files in `assets/css/src/` and `assets/js/src/`
- Compiled output to `assets/css/` and `assets/js/`
- Add `assets/**/src/` and `node_modules/` to `.gitignore`
- Enqueue minified versions in production, source files in development (based on `SCRIPT_DEBUG`)

---

## 3. Font Loading Strategy for Sinhala/Tamil

### Decision
**Google Fonts API with font-display: swap and system font fallbacks**

### Rationale
- **Availability**: Google Fonts provides high-quality Sinhala and Tamil fonts (Noto Sans Sinhala, Noto Sans Tamil)
- **CDN**: Global CDN ensures fast delivery with high availability
- **Unicode complete**: Noto fonts have full Unicode coverage for medical terminology
- **Free**: No licensing costs
- **font-display: swap**: Resolved in clarifications - shows text immediately with system fonts, swaps to web fonts when loaded (prevents FOIT)
- **Performance**: DNS prefetch and preconnect optimize loading

### Font Selections
| Language | Primary Font | System Fallback |
|----------|--------------|-----------------|
| **English** | Inter or Poppins (Google Fonts) | system-ui, -apple-system, "Segoe UI", sans-serif |
| **Sinhala** | Noto Sans Sinhala (Google Fonts) | "Iskoola Pota", "Nirmala UI", sans-serif |
| **Tamil** | Noto Sans Tamil (Google Fonts) | "Latha", "Nirmala UI", sans-serif |

### Implementation Strategy

**functions.php**:
```php
function renalinfo_enqueue_fonts() {
    // Preconnect to Google Fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    
    // Enqueue Google Fonts with font-display: swap
    wp_enqueue_style(
        'renalinfo-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Sans+Sinhala:wght@400;600;700&family=Noto+Sans+Tamil:wght@400;600;700&display=swap',
        array(),
        null
    );
}
add_action( 'wp_enqueue_scripts', 'renalinfo_enqueue_fonts' );
```

**CSS**:
```css
:root {
    --font-latin: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
    --font-sinhala: 'Noto Sans Sinhala', 'Iskoola Pota', 'Nirmala UI', sans-serif;
    --font-tamil: 'Noto Sans Tamil', 'Latha', 'Nirmala UI', sans-serif;
}

body {
    font-family: var(--font-latin);
}

[lang="si"],
[lang="si"] * {
    font-family: var(--font-sinhala);
}

[lang="ta"],
[lang="ta"] * {
    font-family: var(--font-tamil);
}
```

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Adobe Fonts** | Requires subscription, commercial use restrictions |
| **Self-hosted fonts** | Adds hosting burden, CDN benefits lost, font updates manual |
| **System fonts only** | Inconsistent rendering across devices, limited Sinhala/Tamil quality |

---

## 4. Search Implementation Strategy

### Decision
**Enhanced WordPress native search with custom relevance scoring**

### Rationale
- **Native integration**: Uses WordPress `WP_Query` and search APIs
- **No plugin dependency**: Reduces maintenance burden and potential conflicts
- **Performance**: Lighter than Elasticsearch/Algolia for 500 articles
- **Custom control**: Can implement medical abbreviation handling (FR-016)
- **Language-aware**: Integrates with Polylang for language-specific results (FR-018)
- **Cost**: Free, no third-party service costs

### Implementation Approach

**Custom search query modification**:
```php
// Modify search query to handle medical terms
function renalinfo_search_modifier( $query ) {
    if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
        // Boost title matches
        $query->set( 'orderby', 'relevance' );
        
        // Search in custom fields (medical terms glossary)
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => 'medical_abbreviation',
                'value' => $query->get( 's' ),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'medical_synonym',
                'value' => $query->get( 's' ),
                'compare' => 'LIKE'
            )
        );
        $query->set( 'meta_query', $meta_query );
        
        // Language filtering with Polylang
        if ( function_exists( 'pll_current_language' ) ) {
            $query->set( 'lang', pll_current_language() );
        }
    }
    return $query;
}
add_filter( 'pre_get_posts', 'renalinfo_search_modifier' );
```

**Autocomplete via AJAX**:
- Use `wp_ajax` actions for autocomplete endpoint
- Query posts and medical terms glossary
- Return JSON with sanitized results
- Implement client-side debouncing (300ms delay)

**Medical abbreviation handling**:
- Store abbreviations and synonyms as post meta in Medical Term CPT
- Search query checks both post content and term mappings
- Display matched term definition in search results

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Elasticsearch** | Overkill for 500 articles, hosting complexity, cost |
| **Algolia** | Monthly cost, external dependency, data privacy concerns (medical content) |
| **SearchWP plugin** | Paid plugin ($99+/year), can achieve same with custom code |
| **Relevanssi** | Free version limited, premium $129/year, native solution preferred |

---

## 5. Image Optimization Strategy

### Decision
**WebP format with fallback + responsive images + lazy loading**

### Rationale
- **WebP**: 25-35% smaller file sizes than JPEG with equivalent quality
- **Browser support**: 95%+ browser support (Chrome, Firefox, Safari 14+, Edge)
- **WordPress native**: WordPress 5.8+ supports WebP uploads natively
- **Responsive images**: WordPress `srcset` and `sizes` attributes built-in
- **Lazy loading**: WordPress 5.5+ has native lazy loading for images
- **Performance**: Meets FR-052-054 performance requirements

### Implementation Strategy

**Server-side WebP conversion**:
```php
// Enable WebP uploads
function renalinfo_enable_webp( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'mime_types', 'renalinfo_enable_webp' );

// Generate WebP versions on upload (if server supports)
function renalinfo_generate_webp( $metadata, $attachment_id ) {
    $file = get_attached_file( $attachment_id );
    $upload_dir = wp_upload_dir();
    
    if ( function_exists( 'imagewebp' ) ) {
        $image = wp_get_image_editor( $file );
        if ( ! is_wp_error( $image ) ) {
            $webp_file = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $file );
            $image->save( $webp_file, 'image/webp' );
        }
    }
    
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'renalinfo_generate_webp', 10, 2 );
```

**HTML output with `<picture>` element**:
```php
function renalinfo_responsive_image( $attachment_id, $size = 'full' ) {
    $src = wp_get_attachment_image_src( $attachment_id, $size );
    $srcset = wp_get_attachment_image_srcset( $attachment_id, $size );
    $sizes = wp_get_attachment_image_sizes( $attachment_id, $size );
    $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    
    // WebP source
    $webp_src = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $src[0] );
    
    ?>
    <picture>
        <source type="image/webp" srcset="<?php echo esc_attr( $webp_src ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>">
        <img src="<?php echo esc_url( $src[0] ); ?>" 
             srcset="<?php echo esc_attr( $srcset ); ?>" 
             sizes="<?php echo esc_attr( $sizes ); ?>"
             alt="<?php echo esc_attr( $alt ); ?>"
             loading="lazy"
             width="<?php echo esc_attr( $src[1] ); ?>"
             height="<?php echo esc_attr( $src[2] ); ?>">
    </picture>
    <?php
}
```

**Image size registration**:
```php
function renalinfo_image_sizes() {
    // Article featured images
    add_image_size( 'article-hero', 1200, 600, true );
    
    // Staff profile photos
    add_image_size( 'staff-profile', 400, 400, true );
    
    // Thumbnails
    add_image_size( 'article-thumb', 300, 200, true );
}
add_action( 'after_setup_theme', 'renalinfo_image_sizes' );
```

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **AVIF format** | Limited browser support (~70%), encoding slower, WordPress support immature |
| **Plugin (Smush, ShortPixel)** | Adds dependency, external service limits, prefer native solution |
| **CDN (Cloudflare, Cloudinary)** | Additional cost and complexity, hosting constraint in spec |

---

## 6. Session-Only Language Selection Implementation

### Decision
**SessionStorage API with query parameter fallback**

### Rationale
- **Privacy-compliant**: Clarification Q4 specified no persistent storage
- **Session-scoped**: Data clears when browser tab/window closes
- **No cookies**: Avoids cookie consent banner requirements
- **JavaScript-optional**: Query parameter (`?lang=si`) works without JS
- **Performance**: Local storage access faster than server-side session

### Implementation Strategy

**JavaScript (progressive enhancement)**:
```javascript
// On language selection
function setLanguage( langCode ) {
    sessionStorage.setItem( 'renalinfo_lang', langCode );
    window.location.href = addLangParam( window.location.href, langCode );
}

// On page load
function getPreferredLanguage() {
    // Check sessionStorage first
    const storedLang = sessionStorage.getItem( 'renalinfo_lang' );
    if ( storedLang ) {
        return storedLang;
    }
    
    // Fall back to URL parameter
    const urlParams = new URLSearchParams( window.location.search );
    return urlParams.get( 'lang' ) || 'en'; // Default to English
}

// Persist language across navigation
document.addEventListener( 'DOMContentLoaded', function() {
    const preferredLang = getPreferredLanguage();
    
    // Add lang parameter to all internal links
    document.querySelectorAll( 'a[href^="/"]' ).forEach( link => {
        const href = link.getAttribute( 'href' );
        link.setAttribute( 'href', addLangParam( href, preferredLang ) );
    });
});
```

**PHP fallback (no JavaScript)**:
```php
function renalinfo_get_current_language() {
    // Check URL parameter
    if ( isset( $_GET['lang'] ) ) {
        $lang = sanitize_text_field( $_GET['lang'] );
        if ( in_array( $lang, array( 'en', 'si', 'ta' ), true ) ) {
            return $lang;
        }
    }
    
    // Check Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        return pll_current_language();
    }
    
    // Default
    return 'en';
}
```

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Cookies** | Persistent storage violates clarification decision, requires consent banner |
| **LocalStorage** | Persistent storage violates clarification decision |
| **Server-side PHP sessions** | Persistent storage, adds server state, scaling issues |
| **Browser language detection only** | User can't override, doesn't persist within session |

---

## 7. Content Version Control Strategy

### Decision
**WordPress Revisions + custom version history meta**

### Rationale
- **Native WordPress**: Built-in revision system handles content tracking
- **Medical compliance**: Version history critical for medical accuracy (Clarification Q2)
- **No plugin**: Avoid dependency on revision control plugins
- **Audit trail**: Can track who updated what and when
- **Rollback capability**: Can restore previous versions if needed

### Implementation Strategy

**Enable revisions in wp-config.php**:
```php
define( 'WP_POST_REVISIONS', 50 ); // Keep last 50 revisions
define( 'AUTOSAVE_INTERVAL', 300 ); // Autosave every 5 minutes
```

**Display version history on frontend**:
```php
function renalinfo_display_update_notice( $post_id ) {
    $revisions = wp_get_post_revisions( $post_id, array( 'posts_per_page' => 5 ) );
    
    if ( ! empty( $revisions ) ) {
        $latest_revision = reset( $revisions );
        $update_date = get_the_modified_date( 'F j, Y', $post_id );
        
        echo '<div class="article-update-notice">';
        echo '<strong>' . esc_html__( 'Updated:', 'renalinfo' ) . '</strong> ';
        echo '<time datetime="' . esc_attr( get_the_modified_date( 'c', $post_id ) ) . '">';
        echo esc_html( $update_date );
        echo '</time>';
        
        // Link to version history (for logged-in users)
        if ( is_user_logged_in() && current_user_can( 'edit_post', $post_id ) ) {
            echo ' <a href="' . esc_url( admin_url( 'revision.php?revision=' . $latest_revision->ID ) ) . '">';
            echo esc_html__( 'View history', 'renalinfo' );
            echo '</a>';
        }
        echo '</div>';
    }
}
```

**Custom meta for version notes**:
```php
// Add version note meta box
function renalinfo_version_note_meta_box() {
    add_meta_box(
        'renalinfo_version_note',
        __( 'Version Note', 'renalinfo' ),
        'renalinfo_version_note_callback',
        array( 'article', 'treatment', 'staff_profile' ),
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'renalinfo_version_note_meta_box' );

function renalinfo_version_note_callback( $post ) {
    wp_nonce_field( 'renalinfo_version_note', 'renalinfo_version_note_nonce' );
    $value = get_post_meta( $post->ID, '_version_note', true );
    ?>
    <label for="renalinfo_version_note_field">
        <?php esc_html_e( 'Describe what changed in this update:', 'renalinfo' ); ?>
    </label>
    <textarea id="renalinfo_version_note_field" name="renalinfo_version_note_field" rows="3" style="width:100%;"><?php echo esc_textarea( $value ); ?></textarea>
    <p class="description"><?php esc_html_e( 'e.g., "Updated medication dosages per 2025 guidelines"', 'renalinfo' ); ?></p>
    <?php
}
```

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Git integration plugin** | Overly complex, requires Git knowledge, external dependency |
| **WP Revision Manager plugin** | Prefer native solution, additional maintenance |
| **External versioning system** | Disconnected from WordPress, training burden |

---

## 8. Medical Term Glossary Structure

### Decision
**Custom Post Type "Medical Term" with synonym/abbreviation taxonomies**

### Rationale
- **Scalable**: Can grow to hundreds of terms without performance issues
- **Searchable**: Integrates with WordPress search and custom search enhancements
- **Multilingual**: Works with Polylang for term translations
- **Admin-friendly**: Medical staff can manage through WordPress admin (Clarification Q3)
- **Relational**: Can link terms to related articles

### Data Structure

**Custom Post Type registration**:
```php
function renalinfo_register_medical_term_cpt() {
    $labels = array(
        'name' => __( 'Medical Terms', 'renalinfo' ),
        'singular_name' => __( 'Medical Term', 'renalinfo' ),
        'add_new' => __( 'Add New Term', 'renalinfo' ),
        'add_new_item' => __( 'Add New Medical Term', 'renalinfo' ),
        'edit_item' => __( 'Edit Medical Term', 'renalinfo' ),
        'view_item' => __( 'View Medical Term', 'renalinfo' ),
        'search_items' => __( 'Search Medical Terms', 'renalinfo' ),
    );
    
    $args = array(
        'labels' => $labels,
        'public' => false, // Not public-facing, used for search mapping
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array( 'title', 'editor' ),
        'capability_type' => 'post',
        'has_archive' => false,
        'rewrite' => false,
    );
    
    register_post_type( 'medical_term', $args );
}
add_action( 'init', 'renalinfo_register_medical_term_cpt' );
```

**Custom fields**:
- `term_abbreviation` (text): e.g., "CKD"
- `term_full_name` (text): e.g., "Chronic Kidney Disease"
- `term_synonyms` (textarea): Comma-separated list
- `term_simple_definition` (textarea): For families
- `term_technical_definition` (textarea): For professionals
- `term_pronunciation` (text): For complex terms

**Search integration**: Terms indexed alongside articles, abbreviations matched against search queries

### Alternatives Considered
| Alternative | Rejected Because |
|-------------|------------------|
| **Taxonomy (categories/tags)** | Limited field support, no body content for definitions |
| **Custom database table** | Bypasses WordPress APIs, harder to maintain, translation challenges |
| **Plugin (Glossary plugins)** | External dependency, may not integrate with Polylang, limited customization |

---

## Implementation Checklist

Based on research findings, the following technology decisions are finalized:

- [x] **Multilingual**: Polylang (free version)
- [x] **Build tools**: npm scripts + PostCSS + esbuild
- [x] **Fonts**: Google Fonts (Inter, Noto Sans Sinhala, Noto Sans Tamil) with font-display: swap
- [x] **Search**: Enhanced WordPress native search with custom relevance
- [x] **Images**: WebP with fallback + responsive images + lazy loading
- [x] **Language persistence**: SessionStorage + query parameters (session-only)
- [x] **Version control**: WordPress revisions + custom version meta
- [x] **Glossary**: Custom Post Type "Medical Term"

All "NEEDS CLARIFICATION" items from Technical Context are now resolved and ready for Phase 1 design work.

---

## Next Steps

1. **Phase 1**: Create `data-model.md` documenting all custom post types, taxonomies, and data relationships
2. **Phase 1**: Generate API contracts (if applicable) for AJAX endpoints (search autocomplete, language selection)
3. **Phase 1**: Create `quickstart.md` with development environment setup instructions
4. **Phase 1**: Update agent context with finalized technology stack

**Research Phase Complete**: ✅ Ready to proceed to Phase 1 (Design & Contracts)
