# Theme Setup Notes

## Installation Status

✅ **Theme structure created** - All core files in place  
✅ **Custom post types registered** - Article, Journey, Staff, Medical Term, Support Resource  
✅ **Custom taxonomies registered** - Article Category, Audience Type, Specialization, Resource Type  
✅ **Basic templates created** - header.php, footer.php, index.php, content templates  

## Next Steps for Testing

### 1. Install Node.js and npm (if not installed)
- Download from: https://nodejs.org/
- Recommended: LTS version
- Verify installation: `npm --version`

### 2. Install Theme Dependencies
```powershell
cd "d:\Projects\Current Projects\WordPress Theme Projects\RenalInfo\renalinfo"
npm install
```

### 3. Build Assets
```powershell
npm run build
```

This will compile:
- CSS from `assets/css/src/style.css` to `assets/css/style.css`
- JavaScript from `assets/js/src/main.js` to `assets/js/main.js`

### 4. Install WordPress Theme

1. Copy the `renalinfo` folder to your WordPress installation:
   - Location: `wp-content/themes/renalinfo`

2. Activate the theme:
   - WordPress Admin → Appearance → Themes
   - Activate "RenalInfo Paediatric Nephrology"

3. Install Polylang plugin:
   - WordPress Admin → Plugins → Add New
   - Search for "Polylang"
   - Install and activate

4. Configure Polylang:
   - Go to Languages → Languages
   - Add three languages: English, Sinhala, Tamil
   - Set English as default

5. Create menus:
   - WordPress Admin → Appearance → Menus
   - Create "Primary Menu" and assign to "Primary Menu" location
   - Create "Footer Menu" and assign to "Footer Menu" location

## Development Commands

- `npm run watch` - Watch for changes and rebuild automatically
- `npm run lint` - Check code quality
- `npm run lint:fix` - Auto-fix linting issues

## Current Implementation Status

**Phase 1: Setup** - ✅ **100% complete**
- [x] Theme structure
- [x] Core files (style.css, functions.php, header.php, footer.php, index.php)
- [x] Package.json with build scripts
- [x] PHP CodeSniffer configuration
- [x] PostCSS configuration
- [x] EditorConfig
- [x] Custom post types (all 5)
- [x] Custom taxonomies (all 4)
- [x] Basic template parts
- [x] Navigation menu registration
- [x] Google Fonts integration
- [x] AJAX handlers stub
- [x] npm dependencies installed
- [x] Assets built
- [ ] Screenshot.png (placeholder needed for WordPress theme)

**Phase 2: Foundational** - ✅ **95% complete**
- [x] Custom fields framework (native WordPress meta boxes)
- [x] Article custom fields (template, reading level, audience, medical review, FAQs)
- [x] Journey custom fields (articles, audience, estimated time)
- [x] Staff custom fields (role, credentials, bio, contact info)
- [x] Medical term custom fields (abbreviation, synonyms, definitions)
- [x] Medical term admin UI (custom columns, sortable)
- [x] Template functions (sanitization, validation, helpers)
- [x] Template tags (display functions for all custom fields)
- [x] CSS framework (variables, typography, layout grid, responsive breakpoints)
- [x] Color palette implementation (calming blues, soft greens, muted teals)
- [x] Multilingual font support (Inter, Noto Sans Sinhala, Noto Sans Tamil)
- [x] Asset versioning strategy
- [x] Build system working (CSS + JS compiled)
- [ ] WordPress revisions documentation (wp-config.php guidance)

**Ready for**: Phase 3 (User Story 1 - Parent Finding Treatment Information) implementation.

## Phase 2 Implementation Summary

### Custom Fields Implemented

**Article Custom Fields:**
- Article template selection (condition-explainer, treatment-procedure, professional-article)
- Reading level tracking (Flesch-Kincaid grade)
- Primary audience (family/professional)
- Medical review date and reviewer name
- Version notes for content updates
- Key takeaways (bullet points)
- Related articles (ID references)
- FAQ items (repeatable question/answer pairs)
- Auto-calculated reading time

**Journey Custom Fields:**
- Ordered article IDs
- Target audience
- Estimated completion time

**Staff Custom Fields:**
- Role/job title
- Professional credentials
- Personal bio (approachable)
- Professional bio (detailed with rich text)
- Contact information (email, phone, office hours)
- Languages spoken

**Medical Term Custom Fields:**
- Abbreviation
- Full medical term name
- Synonyms/alternative terms
- Simple definition (family-friendly)
- Technical definition (professional)
- Pronunciation guide
- Admin columns showing abbreviation, full name, synonyms

### Template Functions Added

**Sanitization:**
- Text sanitization
- Email sanitization
- URL sanitization
- HTML sanitization (wp_kses_post)
- ID array sanitization

**Validation:**
- Article validation (template, audience, reading level checks)
- Medical review date validation (no future dates)
- Display validation errors in admin

**Helper Functions:**
- Get reading time (auto-calculated from content)
- Get related articles (manual or automatic by category)
- Get FAQ items
- Get journey articles in order
- Get journey position (current/total/prev/next)
- Get staff authored articles
- Search medical terms by abbreviation/synonym
- Check if recently updated
- Get article template type
- WebP support detection
- Responsive image generation

### Template Tags Added

**Display Functions:**
- Reading time display
- Breadcrumb navigation
- Medical review date display
- Medical reviewer display
- Update notice (for recently updated content)
- Key takeaways display
- FAQ items with schema.org markup
- Related articles grid
- Journey navigation (with progress indicator)
- Audience badge
- Staff contact information
- Staff credentials
- Staff languages
- Version history link (for editors)

### CSS Framework Features

**CSS Custom Properties (Variables):**
- Complete color system (primary blues, secondary greens, accent teals)
- Semantic colors (success, warning, error, info)
- Comprehensive gray scale
- Text color variables
- Background color variables
- Border color variables

**Typography:**
- Latin fonts (Inter)
- Sinhala fonts (Noto Sans Sinhala)
- Tamil fonts (Noto Sans Tamil)
- Font size scale (xs to 5xl)
- Line height scale (tight to loose)
- Font weight scale
- Language-specific font application

**Layout System:**
- Container with max-width
- Content wrapper
- CSS Grid system (1-4 columns)
- Flexbox utilities
- Spacing utilities (margin, padding)
- Responsive grid classes

**Responsive Breakpoints:**
- Mobile-first approach
- Small (640px): tablet landscape
- Medium (768px): tablets
- Large (1024px): desktops
- Extra large (1280px): large desktops
- 2XL (1536px): extra large displays

**Accessibility Features:**
- Skip-to-content link
- Screen reader only utility
- Focus-visible styles
- High contrast mode support
- Reduced motion support
- WCAG 2.1 AA compliant focus indicators

**Other Features:**
- Shadows (sm to xl)
- Border radius scale
- Transition speeds
- Z-index scale
- Print-friendly styles (planned)

## Next Implementation Steps

### Phase 3: User Story 1 (MVP - Priority P1)

**Goal**: Parents can find and read condition explainer articles within 3 clicks

**Tasks to Complete (T051-T067):**
1. Create homepage template (front-page.php)
2. Create archive template (archive.php)
3. Create single article template (single-article.php)
4. Create condition-explainer template part
5. Implement FAQ display with schema.org
6. Add medical term tooltip/glossary integration
7. Create related articles widget
8. Style condition-explainer template
9. Add support resource links section
10. Implement breadcrumb navigation
11. Create homepage CSS (hero, category cards)
12. Create archive page CSS
13. Implement responsive images with WebP
14. Create category taxonomy template
15. Style taxonomy archives

**Estimated Time**: 40-60 hours

**Testing Criteria:**
- Navigate from homepage → category → article (≤3 clicks)
- Verify FAQ display with proper markup
- Check reading level display
- Test related articles section
- Validate WCAG 2.1 AA compliance
- Test on mobile devices (320px width)

## Development Workflow

### Making Changes

1. **Edit Source Files:**
   - CSS: `assets/css/src/style.css`
   - JavaScript: `assets/js/src/main.js`

2. **Build Assets:**
   ```powershell
   npm run build
   ```

3. **Watch Mode (Auto-rebuild):**
   ```powershell
   npm run watch
   ```

4. **Code Quality:**
   ```powershell
   npm run lint
   npm run lint:fix
   ```

### Testing Custom Fields

1. **Create Test Content:**
   - Go to WordPress Admin → Articles → Add New
   - Fill in all custom fields
   - Save draft and check meta boxes display correctly

2. **Test Medical Terms:**
   - Go to Medical Terms → Add New
   - Add abbreviation (e.g., "CKD")
   - Add full name and synonyms
   - Check admin columns display correctly

3. **Test Journeys:**
   - Create multiple articles first
   - Create journey and link articles in order
   - Verify article order is maintained

### Accessibility Testing

1. **Keyboard Navigation:**
   - Tab through all interactive elements
   - Verify focus indicators are visible
   - Test skip-to-content link

2. **Screen Reader:**
   - Test with NVDA or JAWS
   - Verify all images have alt text
   - Check ARIA labels are present

3. **Color Contrast:**
   - Use browser dev tools contrast checker
   - Verify 4.5:1 ratio for normal text
   - Verify 3:1 ratio for large text

## Performance Benchmarks

### Current Status (Phase 2 Complete)

**Target Metrics** (to be achieved by Phase 14):
- PageSpeed Insights: ≥90 (mobile & desktop)
- Largest Contentful Paint (LCP): <2.5s
- First Input Delay (FID): <100ms
- Cumulative Layout Shift (CLS): <0.1
- Page Load Time: <3s on 5 Mbps connection

**Optimization Strategies Implemented:**
- CSS variables for efficient styling
- Minimal CSS framework (no bloat)
- PostCSS for minification
- esbuild for fast JavaScript compilation
- Font-display: swap for Google Fonts
- Lazy loading attributes ready
- WebP support built-in

## Known Issues & Limitations

### Phase 2 Complete - Outstanding Items:

1. **Screenshot.png** (T007): Theme screenshot not created yet
   - Required: 1200x900px PNG
   - Should show actual theme design
   - Create after Phase 3 templates are complete

2. **WordPress Revisions Documentation** (T044): 
   - Need to document wp-config.php settings
   - Recommend WP_POST_REVISIONS constant
   - Add to readme.txt in Phase 17

### Future Enhancements (Post-MVP):

- CMB2 or ACF integration (optional upgrade from native meta boxes)
- Advanced custom field validation UI
- Field conditional logic
- Import/export functionality for sample content
- Theme options panel (Customizer expansion)

## File Structure Reference

```
renalinfo/
├── inc/
│   ├── custom-fields.php      ✅ Complete (950+ lines)
│   ├── custom-post-types.php  ✅ Complete
│   ├── custom-taxonomies.php  ✅ Complete
│   ├── template-functions.php ✅ Complete (450+ lines)
│   ├── template-tags.php      ✅ Complete (350+ lines)
│   ├── ajax-handlers.php      ✅ Stub (to be expanded)
│   ├── widgets.php            ⏳ Placeholder
│   └── customizer.php         ⏳ Placeholder
├── assets/
│   ├── css/
│   │   ├── style.css          ✅ Compiled
│   │   └── src/
│   │       └── style.css      ✅ Complete (600+ lines)
│   └── js/
│       ├── main.js            ✅ Compiled
│       └── src/
│           └── main.js        ✅ Basic structure
└── template-parts/            ⏳ To be created in Phase 3
```

## Ready for**: Phase 3 (User Story 1) implementation once theme is activated and tested.

## Troubleshooting

### Theme won't activate
- Check PHP version (minimum 7.4)
- Check WordPress version (minimum 6.0)
- Check error logs in `wp-content/debug.log`

### Assets not loading
- Run `npm install` and `npm run build`
- Check file permissions
- Clear browser cache

### Custom post types not showing
- Go to Settings → Permalinks and click "Save Changes"
- This flushes rewrite rules

## Next Implementation Phase

Once testing is complete, continue with **Phase 2: Foundational** (T019-T050):
- Custom fields implementation
- Asset versioning strategy
- Template functions
- CSS framework setup
- Initial build of assets
