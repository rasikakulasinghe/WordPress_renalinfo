# Implementation Plan: Paediatric Nephrology Clinic Website

**Branch**: `001-pediatric-nephrology-website` | **Date**: 2025-10-16 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/001-pediatric-nephrology-website/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

A complete WordPress theme for a dual-audience paediatric nephrology clinic website serving parents/guardians and referring physicians. The theme provides multilingual support (English, Sinhala, Tamil), custom content templates for medical conditions and procedures, accessible design meeting WCAG 2.1 AA standards, patient journey modules for sequential article navigation, and privacy-focused implementation with no persistent user tracking. Primary technical approach: Custom WordPress theme with custom post types for content organization, custom taxonomies for categorization, modular template parts for reusability, and vanilla JavaScript for progressive enhancement.

## Technical Context

<!--
  WordPress Theme Development Context
  Fill in the specific details for this feature implementation.
-->

**WordPress Version**: 6.0+ (maintain compatibility with latest two major versions)  
**PHP Version**: 8.0+ (minimum 7.4 for backward compatibility)  
**Primary Dependencies**: WordPress Core, **Polylang** (free version) for multilingual support, Google Fonts API (Inter, Noto Sans Sinhala, Noto Sans Tamil) for web fonts  
**Database**: MySQL 5.7+/MariaDB 10.3+ with UTF-8 Unicode encoding (standard WordPress)  
**CSS Framework**: Custom CSS with PostCSS (postcss-preset-env for modern features, autoprefixer, cssnano for minification)  
**JavaScript Framework**: Vanilla ES6+ JavaScript transpiled with **esbuild** for browser compatibility, no framework dependencies for core functionality  
**Build Tools**: **npm scripts + PostCSS + esbuild** (lightweight modern stack; see research.md for rationale)  
**Testing**: Theme Check plugin, PHP_CodeSniffer with WordPress-Core and WordPress-Extra rulesets, WAVE and axe DevTools for accessibility, PageSpeed Insights  
**Target Browsers**: Chrome, Firefox, Safari, Edge (current version and one version back); IE11 not supported  
**Project Type**: WordPress Theme (single project structure)  
**Performance Goals**: PageSpeed Insights ≥ 90 (mobile & desktop), LCP < 2.5s, FID < 100ms, CLS < 0.1, page load < 3s on 5 Mbps broadband  
**Constraints**: No persistent user tracking (session-only language selection via SessionStorage), no user accounts, must support 500+ articles without performance degradation, WCAG 2.1 AA compliance mandatory, grade 8-9 reading level for family content  
**Scale/Scope**: Medical/healthcare informational theme for single clinic with multilingual content management, custom content templates, and dual-audience navigation

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

### WordPress Coding Standards (Principle I) ✅ REQUIRED
- [x] PHP code follows WordPress PHP Coding Standards
- [x] HTML follows WordPress HTML Standards
- [x] CSS follows WordPress CSS Standards
- [x] JavaScript follows WordPress JavaScript Standards
- [x] Accessibility standards implemented (WCAG 2.1 AA) - explicitly required in spec FR-022
- [x] PHP_CodeSniffer configured with WordPress rulesets

### Theme Review Guidelines (Principle II) ✅ REQUIRED
- [x] Uses WordPress core functionality (no plugin territory) - theme-appropriate content types
- [x] Proper theme support features declared - custom post types, custom taxonomies, menus, HTML5
- [x] Security: all data sanitized, validated, escaped - required for medical content
- [x] No hard-coded URLs or site-specific content - templated approach
- [x] Full internationalization (i18n) for all strings - English/Sinhala/Tamil support required (FR-043-051)
- [x] GPL-compatible licensing - standard GPL v2+
- [x] Theme Check plugin validation planned - listed in testing tools

### Template Hierarchy & Modularity (Principle III) ✅ REQUIRED
- [x] Follows WordPress template hierarchy - custom templates for Condition Explainer, Treatment Procedure, Professional Article, Staff Profile (FR-006-009)
- [x] Uses modular template parts - header variations, footer, content templates planned
- [x] Implements hooks and filters for extensibility - allows customization without template modification
- [x] Separation of concerns (templates/functions/styles) - standard WordPress theme structure

### Security-First Development (Principle IV) ✅ REQUIRED
- [x] Input validation strategy defined - search queries, form inputs validated
- [x] Output escaping with proper WordPress functions - all user-generated content escaped
- [x] Nonces planned for forms/AJAX - language selection, optional feedback forms
- [x] Capability checks for privileged operations - content management by authorized staff only
- [x] SQL queries use $wpdb->prepare() - custom queries for medical term glossary, search

### Performance Standards (Principle V) ✅ REQUIRED
- [x] Asset loading via wp_enqueue_script/style - proper WordPress asset management
- [x] Performance targets: PageSpeed ≥ 90, LCP < 2.5s, FID < 100ms, CLS < 0.1 - explicitly defined in SC-007-008
- [x] Lazy loading implementation planned - FR-053 for images
- [x] Image optimization strategy defined - responsive images, compression (FR-053-054)
- [x] Database query optimization planned - must handle 500+ articles (FR-005)

### Accessibility (Principle VI) ✅ REQUIRED
- [x] Semantic HTML5 markup planned - FR-022 WCAG 2.1 AA compliance
- [x] Keyboard navigation support - FR-025 keyboard-navigable interactive elements
- [x] Color contrast ratios meet WCAG standards - FR-024 high-contrast mode, 4.5:1 / 3:1 ratios
- [x] Screen reader compatibility planned - FR-026 alt text and ARIA labels
- [x] Accessibility testing tools identified - WAVE, axe DevTools (Success Metrics section)

### Internationalization (Principle VII) ✅ REQUIRED
- [x] Text domain: 'renalinfo' consistently used - established text domain
- [x] All strings use i18n functions - FR-044 all content translatable
- [x] RTL language support planned - Sinhala and Tamil require RTL testing
- [x] POT file generation planned - required for translator workflow

**Constitution Compliance**: ✅ **PASS** - All constitutional requirements are met by the specification. No violations or exceptions needed.

## Project Structure

### Documentation (this feature)

```
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (WordPress Theme Structure)
<!--
  WordPress Theme Development Structure
  Customize this structure based on the specific feature requirements.
  Follow WordPress theme structure conventions from the constitution.
-->

```
renalinfo/                    # Theme root directory
├── assets/                   # Static assets
│   ├── css/                  # Compiled/source CSS
│   │   ├── style.css         # Main stylesheet
│   │   ├── editor-style.css  # Gutenberg editor styles
│   │   └── components/       # Component-specific styles
│   ├── js/                   # JavaScript files
│   │   ├── main.js           # Main theme JavaScript
│   │   ├── customizer.js     # Customizer controls
│   │   └── navigation.js     # Navigation functionality
│   ├── images/               # Theme images
│   └── fonts/                # Custom fonts
│
├── inc/                      # PHP includes (theme functionality)
│   ├── customizer.php        # Customizer settings
│   ├── template-functions.php # Template helper functions
│   ├── template-tags.php     # Template tags
│   ├── custom-post-types.php # CPT registration (if needed)
│   ├── custom-taxonomies.php # Taxonomy registration (if needed)
│   ├── widgets.php           # Custom widgets
│   └── classes/              # PHP classes (if using OOP)
│
├── template-parts/           # Modular template parts
│   ├── header/               # Header variations
│   │   └── site-header.php
│   ├── footer/               # Footer variations
│   │   └── site-footer.php
│   ├── content/              # Content templates
│   │   ├── content.php       # Default post content
│   │   ├── content-single.php
│   │   └── content-page.php
│   └── navigation/           # Navigation templates
│       └── primary-menu.php
│
├── languages/                # Translation files
│   └── renalinfo.pot         # POT file for translators
│
├── tests/                    # Testing files (optional)
│   ├── php/                  # PHP unit tests
│   └── accessibility/        # Accessibility test reports
│
├── style.css                 # Main stylesheet with theme header
├── functions.php             # Theme setup and functionality
├── index.php                 # Main template file
├── header.php                # Header template
├── footer.php                # Footer template
├── sidebar.php               # Sidebar template
├── single.php                # Single post template
├── page.php                  # Page template
├── archive.php               # Archive template
├── search.php                # Search results template
├── 404.php                   # 404 error template
├── comments.php              # Comments template (if needed)
├── screenshot.png            # Theme screenshot (1200x900px)
├── readme.txt                # Theme readme
├── LICENSE                   # GPL license
├── package.json              # Build tools dependencies
├── .gitignore                # Git ignore file
└── phpcs.xml                 # PHP_CodeSniffer configuration
```

**Structure Notes**:
- Follow WordPress template hierarchy for template files
- Use `template-parts/` for reusable components
- Keep `inc/` for PHP functionality (separated by concern)
- Use `assets/` for all static resources
- Include `languages/` directory for i18n support

## Complexity Tracking

*Fill ONLY if Constitution Check has violations that must be justified*

**Status**: ✅ **No violations** - All constitutional requirements are met by this implementation plan.

---

## Phase Completion Status

### Phase 0: Research ✅ COMPLETE

**Deliverables**:
- ✅ `research.md` - All technical decisions documented with rationale
- ✅ Multilingual solution: Polylang (free version) selected
- ✅ Build tools: npm scripts + PostCSS + esbuild selected
- ✅ Font strategy: Google Fonts with font-display: swap
- ✅ Search strategy: Enhanced WordPress native search
- ✅ Image optimization: WebP with fallback + lazy loading
- ✅ Session management: SessionStorage + query parameters
- ✅ Version control: WordPress revisions + custom meta
- ✅ Glossary structure: Custom Post Type "Medical Term"

**Key Research Findings**:
All "NEEDS CLARIFICATION" items from Technical Context have been resolved through systematic research of WordPress best practices, multilingual solutions, and modern build tools. Decisions prioritize performance, maintainability, and alignment with WordPress ecosystem standards.

### Phase 1: Design & Contracts ✅ COMPLETE

**Deliverables**:
- ✅ `data-model.md` - Complete data model with 5 custom post types, 4 taxonomies, validation rules, and multilingual implementation
- ✅ `contracts/ajax-api.md` - AJAX API contracts for search autocomplete, feedback submission, and language selection
- ✅ `quickstart.md` - Development environment setup guide with troubleshooting
- ✅ `.github/copilot-instructions.md` - Agent context updated with technology stack

**Key Design Decisions**:
- **Custom Post Types**: Article, Journey, Staff, Medical Term, Support Resource
- **Taxonomies**: Article Category (hierarchical), Audience Type, Specialization, Resource Type
- **AJAX Endpoints**: 3 endpoints with full security (nonces, rate limiting, sanitization)
- **Data Relationships**: Documented with diagram showing translations, journeys, and search index

### Phase 2: Task Breakdown ⏳ PENDING

**Next Step**: Run `/speckit.tasks` command to generate `tasks.md` with work breakdown structure

**Expected Output**:
- Granular development tasks (1-4 hour chunks)
- Task dependencies and sequencing
- Acceptance criteria per task
- Effort estimates

---

## Implementation Readiness Checklist

### Planning Complete ✅
- [x] Technical context fully defined (no NEEDS CLARIFICATION items)
- [x] Constitution compliance verified (all gates passed)
- [x] Research completed with documented decisions
- [x] Data model designed and validated
- [x] API contracts specified
- [x] Development environment documented

### Ready for Development
- [x] Technology stack finalized
- [x] Build tools selected and justified
- [x] Security approach defined
- [x] Performance targets established
- [x] Accessibility requirements mapped
- [x] Multilingual strategy clear
- [x] Agent context updated

### Prerequisites for Task Generation
- [x] All requirements from spec.md understood
- [x] All clarifications from clarification session integrated
- [x] Data model supports all functional requirements
- [x] API contracts support all interactive features
- [x] Quickstart enables immediate developer onboarding

---

## Next Actions

1. **Immediate**: Run `/speckit.tasks` to generate task breakdown
2. **After tasks**: Begin development on highest-priority tasks
3. **Parallel**: Set up CI/CD pipeline for automated testing
4. **Continuous**: Monitor constitution compliance during development

---

## Documentation Index

| Document | Purpose | Status |
|----------|---------|--------|
| `spec.md` | Feature specification with requirements | ✅ Complete |
| `plan.md` | This file - implementation plan | ✅ Complete (Phase 0-1) |
| `research.md` | Technical research and decisions | ✅ Complete |
| `data-model.md` | Data structures and relationships | ✅ Complete |
| `contracts/ajax-api.md` | AJAX API documentation | ✅ Complete |
| `quickstart.md` | Development setup guide | ✅ Complete |
| `tasks.md` | Work breakdown structure | ⏳ Pending (next command) |

---

**Plan Version**: 1.0.0  
**Last Updated**: 2025-10-16  
**Status**: ✅ Phase 0-1 Complete | ⏳ Ready for Phase 2 (Task Generation)

