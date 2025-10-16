# Data Model: Paediatric Nephrology Clinic Website

**Feature**: 001-pediatric-nephrology-website  
**Date**: 2025-10-16  
**Status**: Phase 1 Design Complete

## Overview

This document defines all custom post types, taxonomies, meta fields, and data relationships for the RenalInfo WordPress theme. The data model supports multilingual medical content with dual-audience organization, patient journey modules, staff profiles, and a medical terms glossary.

---

## Custom Post Types

### 1. Article (`article`)

**Purpose**: Main content type for condition explainers, treatment procedures, and professional articles

**Configuration**:
```php
'public' => true
'has_archive' => true
'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author' )
'rewrite' => array( 'slug' => 'articles' )
'show_in_rest' => true // Gutenberg support
'taxonomies' => array( 'article_category', 'audience_type' )
```

**Custom Fields (Post Meta)**:

| Field Name | Type | Purpose | Example |
|------------|------|---------|---------|
| `_article_template` | select | Template type: condition-explainer, treatment-procedure, professional-article | `condition-explainer` |
| `_reading_level` | text | Flesch-Kincaid grade level (for family content) | `8.5` |
| `_audience` | select | Primary audience: family, professional | `family` |
| `_medical_review_date` | date | Last medical review date | `2025-10-15` |
| `_medical_reviewer` | text | Name of reviewing physician | `Dr. Jane Smith` |
| `_version_note` | textarea | Description of update/changes | `Updated medication dosages...` |
| `_reading_time` | number | Estimated reading time in minutes | `7` |
| `_faq_items` | repeater | FAQ question/answer pairs (for condition explainer template) | `[ { q: '...', a: '...' } ]` |
| `_procedure_steps` | repeater | Step-by-step procedure description | `[ { step: 1, title: '...', desc: '...' } ]` |
| `_key_takeaways` | textarea | Bullet points summary | `• Key point 1\n• Key point 2` |
| `_related_articles` | relationship | IDs of related articles | `[123, 456, 789]` |
| `_citations` | repeater | Professional article citations | `[ { author: '...', title: '...', journal: '...', year: '...' } ]` |

**Relationships**:
- **Belongs to**: Article Category (taxonomy)
- **Belongs to**: Audience Type (taxonomy)
- **Has many**: Medical Terms (via content mentions or tagging)
- **Belongs to many**: Patient Journey Modules
- **Has many translations** (via Polylang): Links to `article` posts in other languages

### 2. Patient Journey (`journey`)

**Purpose**: Sequential collections of related articles for guided learning paths

**Configuration**:
```php
'public' => true
'has_archive' => true
'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' )
'rewrite' => array( 'slug' => 'journeys' )
'show_in_rest' => true
```

**Custom Fields**:

| Field Name | Type | Purpose | Example |
|------------|------|---------|---------|
| `_journey_articles` | relationship | Ordered array of article IDs | `[101, 102, 103, 104]` |
| `_journey_audience` | select | Target audience: family, professional | `family` |
| `_estimated_time` | number | Total estimated time in minutes | `45` |
| `_journey_icon` | image | Icon for journey module | `[attachment_id]` |

**Relationships**:
- **Has many**: Articles (ordered relationship)
- **Has many translations** (via Polylang)

### 3. Staff Profile (`staff`)

**Purpose**: Care team member profiles with credentials and specializations

**Configuration**:
```php
'public' => true
'has_archive' => true
'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' )
'rewrite' => array( 'slug' => 'team' )
'show_in_rest' => true
'taxonomies' => array( 'specialization' )
```

**Custom Fields**:

| Field Name | Type | Purpose | Example |
|------------|------|---------|---------|
| `_staff_role` | text | Job title/role | `Consultant Paediatric Nephrologist` |
| `_staff_credentials` | textarea | Professional credentials | `MBBS, MD, MRCP(UK), MRCPCH` |
| `_staff_personal_bio` | textarea | Approachable personal information | `Dr. Smith enjoys hiking...` |
| `_staff_professional_bio` | editor | Professional background and expertise | `Over 15 years experience...` |
| `_staff_email` | email | Contact email | `dr.smith@clinic.com` |
| `_staff_phone` | text | Contact phone | `+94 11 234 5678` |
| `_staff_office_hours` | textarea | Availability | `Monday-Friday, 9AM-5PM` |
| `_staff_languages` | multiselect | Languages spoken | `['English', 'Sinhala', 'Tamil']` |
| `_staff_photo_caption` | text | Photo description for accessibility | `Dr. Smith in clinic office` |
| `_authored_articles` | relationship | Articles authored by this staff member | `[201, 202, 203]` |

**Relationships**:
- **Has many**: Specializations (taxonomy)
- **Has many**: Articles (as author)
- **Has many translations** (via Polylang)

### 4. Medical Term (`medical_term`)

**Purpose**: Glossary for medical terminology, abbreviations, and synonyms (for search enhancement)

**Configuration**:
```php
'public' => false // Not public-facing
'show_ui' => true // Show in admin
'supports' => array( 'title', 'editor' )
'show_in_rest' => false
'capability_type' => 'post'
```

**Custom Fields**:

| Field Name | Type | Purpose | Example |
|------------|------|---------|---------|
| `_term_abbreviation` | text | Common abbreviation | `CKD` |
| `_term_full_name` | text | Full medical term | `Chronic Kidney Disease` |
| `_term_synonyms` | textarea | Alternative terms (comma-separated) | `Chronic renal disease, CRD` |
| `_term_simple_definition` | textarea | Family-friendly definition | `A long-term condition where kidneys...` |
| `_term_technical_definition` | textarea | Professional medical definition | `Progressive irreversible deterioration...` |
| `_term_pronunciation` | text | Phonetic pronunciation | `KRAH-nik KID-nee dih-ZEEZ` |
| `_term_related_articles` | relationship | Articles mentioning this term | `[301, 302]` |

**Relationships**:
- **Related to**: Articles (many-to-many)
- **Has many translations** (via Polylang)

### 5. Support Resource (`support_resource`)

**Purpose**: Links to external resources, support groups, educational materials

**Configuration**:
```php
'public' => true
'has_archive' => true
'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' )
'rewrite' => array( 'slug' => 'resources' )
'show_in_rest' => true
'taxonomies' => array( 'resource_type' )
```

**Custom Fields**:

| Field Name | Type | Purpose | Example |
|------------|------|---------|---------|
| `_resource_url` | url | External link | `https://example.org/support` |
| `_resource_phone` | text | Contact phone | `+94 11 567 8901` |
| `_resource_email` | email | Contact email | `info@support.org` |
| `_resource_availability` | textarea | Hours/availability | `24/7 helpline` |
| `_related_conditions` | relationship | Associated article IDs | `[401, 402]` |

**Relationships**:
- **Has**: Resource Type (taxonomy)
- **Related to**: Articles
- **Has many translations** (via Polylang)

---

## Custom Taxonomies

### 1. Article Category (`article_category`)

**Purpose**: Organize articles by medical content type

**Configuration**:
```php
'hierarchical' => true // Like categories
'show_in_rest' => true
'rewrite' => array( 'slug' => 'category' )
```

**Terms**:
- Kidney Conditions
  - Acute conditions
  - Chronic conditions
  - Congenital conditions
- Treatments & Procedures
  - Dialysis
  - Transplantation
  - Medications
  - Surgical procedures
- For Families & Patients
  - Living with kidney disease
  - Nutrition & diet
  - School & activities
  - Emotional support

**Multilingual**: Each category term has Sinhala and Tamil translations via Polylang

### 2. Audience Type (`audience_type`)

**Purpose**: Filter content by target audience

**Configuration**:
```php
'hierarchical' => false // Like tags
'show_in_rest' => true
'rewrite' => array( 'slug' => 'audience' )
```

**Terms**:
- For Families
- For Professionals

**Usage**: Auto-assigned based on `_audience` meta field; used for search filtering (FR-015)

### 3. Specialization (`specialization`)

**Purpose**: Staff member medical specializations

**Configuration**:
```php
'hierarchical' => false
'show_in_rest' => true
'rewrite' => array( 'slug' => 'specialization' )
```

**Terms**:
- Paediatric Nephrology
- Transplant Medicine
- Dialysis
- Hypertension
- Kidney Stones
- Congenital Anomalies

### 4. Resource Type (`resource_type`)

**Purpose**: Categorize support resources

**Configuration**:
```php
'hierarchical' => false
'show_in_rest' => true
'rewrite' => array( 'slug' => 'resource-type' )
```

**Terms**:
- Support Group
- Educational Material
- Government Service
- Hospital Service
- Online Community
- Helpline

---

## WordPress Standard Content

### Pages

**Static pages** (created during theme setup):

| Page Title | Slug | Purpose | Template |
|------------|------|---------|----------|
| Home | `home` | Homepage with category entry points | `front-page.php` |
| About | `about` | Clinic information | `page.php` |
| Contact | `contact` | Contact form and location | `page-contact.php` |
| Request Appointment | `request-appointment` | Appointment request form | `page-appointment.php` |
| Privacy Policy | `privacy-policy` | Data privacy statement | `page.php` |
| Medical Disclaimer | `disclaimer` | Medical information disclaimer | `page.php` |

### Menus

**Navigation menus**:
1. **Primary Menu**: Main navigation (FR-027)
   - Kidney Conditions
   - Treatments & Procedures
   - For Families & Patients
   - Our Team (Staff)
   - Resources
   - Contact

2. **Footer Menu**: Footer navigation (FR-029)
   - About
   - Contact
   - Privacy Policy
   - Medical Disclaimer

3. **Language Switcher**: Custom walker for Polylang language selection

---

## Data Relationships Diagram

```
┌─────────────┐         ┌──────────────────┐         ┌──────────────┐
│   Article   │◄────────┤ Article Category │         │ Medical Term │
│             │         └──────────────────┘         │              │
│  • Title    │         ┌──────────────────┐         │  • Abbrev    │
│  • Content  │◄────────┤  Audience Type   │         │  • Synonyms  │
│  • Template │         └──────────────────┘         │  • Defs      │
│  • Meta     │                                      └──────┬───────┘
└──────┬──────┘                                             │
       │                                                    │
       │ Translations (Polylang)                           │ Related
       │                                                    │
       ├──►┌─────────────┐                                 │
       │   │ Article (SI)│                                 │
       │   └─────────────┘                                 │
       │                                                    │
       ├──►┌─────────────┐                                 │
       │   │ Article (TA)│                                 │
       │   └─────────────┘                                 │
       │                                                    │
       │ Part of Journey                              Mentions│
       │                                                    │
       ▼                                                    ▼
┌──────────────┐                                    ┌──────────────┐
│   Journey    │                                    │  Search Index│
│              │                                    │              │
│  • Articles  │──── Ordered relationship ───►      │  • Posts     │
│  • Audience  │                                    │  • Terms     │
│  • Time      │                                    │  • Meta      │
└──────────────┘                                    └──────────────┘

┌──────────────┐         ┌────────────────┐
│ Staff Profile│◄────────┤ Specialization │
│              │         └────────────────┘
│  • Bio       │
│  • Credentials│ Authored
│  • Contact   ├──────────►┌─────────────┐
│  • Languages │           │   Article   │
└──────────────┘           └─────────────┘
```

---

## Validation Rules

### Article Validation

**Required fields**:
- Title (min 10 chars, max 200 chars)
- Content (min 300 words for family articles, min 500 words for professional)
- Featured image
- Article template selection
- Audience type
- Reading level (if audience = family)
- Medical review date

**Conditional requirements**:
- If template = `condition-explainer`: FAQ items required (min 3)
- If template = `treatment-procedure`: Procedure steps required (min 4)
- If template = `professional-article`: Citations required (min 3)

**Data sanitization**:
- Title: `sanitize_text_field()`
- Content: `wp_kses_post()`
- URLs: `esc_url_raw()`
- Emails: `sanitize_email()`
- Reading level: `floatval()` with min 0, max 20 constraint

### Journey Validation

**Required fields**:
- Title
- Description (min 50 chars)
- Articles (min 3, max 20)
- Audience type

**Business rules**:
- Articles must be ordered sequentially
- All articles in journey must have same language
- All articles in journey should have same or compatible audience type

### Staff Profile Validation

**Required fields**:
- Title (staff name)
- Featured image (photo)
- Role
- Professional bio (min 100 words)
- At least one specialization

**Data sanitization**:
- Email: `sanitize_email()` + `is_email()` validation
- Phone: `sanitize_text_field()` + regex validation for international format
- URLs: `esc_url_raw()`

### Medical Term Validation

**Required fields**:
- Title (term name)
- Either abbreviation OR full name (at least one)
- Simple definition (min 20 chars)

**Data sanitization**:
- Synonyms: `sanitize_textarea_field()` + split by comma, trim each
- Abbreviation: `strtoupper( sanitize_text_field() )`

---

## Multilingual Implementation

All content types support three languages via **Polylang**:

| Language | Code | Polylang Slug | Display Name |
|----------|------|---------------|--------------|
| English | `en` | `en` | English |
| Sinhala | `si` | `si` | සිංහල |
| Tamil | `ta` | `ta` | தமிழ் |

**Translation linking**:
- Each post has language attribute: `pll_get_post_language( $post_id )`
- Translation links: `pll_get_post_translations( $post_id )` returns `['en' => 123, 'si' => 456, 'ta' => 789]`
- Current language: `pll_current_language()`
- Fallback: If translation doesn't exist, link to English version with notification (FR-049)

**URL structure** (FR-048):
```
/en/articles/chronic-kidney-disease/
/si/articles/chronic-kidney-disease/  (URL slug stays English for SEO, content in Sinhala)
/ta/articles/chronic-kidney-disease/
```

**Search implementation**:
- Search queries filtered by `pll_current_language()`
- FR-018: Prioritize current language results
- FR-019: Option to expand search to other languages
- FR-021: Results indicate language with flag icon + language label

---

## Database Schema Notes

### Standard WordPress Tables Used

**wp_posts**:
- Stores all custom post types (article, journey, staff, medical_term, support_resource)
- `post_type` field distinguishes types
- `post_status`: draft, pending, publish, private
- `post_author`: links to wp_users

**wp_postmeta**:
- All custom fields stored as meta
- Format: `meta_key` = field name, `meta_value` = serialized or plain value
- Indexed keys for performance: `_audience`, `_article_template`, `_medical_review_date`

**wp_term_relationships**:
- Links posts to taxonomies
- Many-to-many relationship

**wp_terms** / **wp_term_taxonomy**:
- Stores taxonomy terms (article_category, audience_type, specialization, resource_type)
- Hierarchical data for article_category

**Polylang tables** (added by plugin):
- `wp_term_relationships` stores language taxonomy
- Uses standard WordPress taxonomy for language organization
- No custom tables needed

### Performance Optimization

**Indexes** (added via dbDelta):
```sql
CREATE INDEX idx_article_template ON wp_postmeta(meta_key, meta_value(50)) WHERE meta_key = '_article_template';
CREATE INDEX idx_audience ON wp_postmeta(meta_key, meta_value(20)) WHERE meta_key = '_audience';
CREATE INDEX idx_review_date ON wp_postmeta(meta_key, meta_value) WHERE meta_key = '_medical_review_date';
```

**Query optimization**:
- Use `WP_Query` with specific post type and taxonomy queries
- Avoid `meta_query` with `LIKE` when possible (use exact matches)
- Cache taxonomy queries with transients (24-hour expiry)
- Limit search results to 20 per page

---

## Migration Strategy

### Initial Data Import

**Phase 1**: Create custom post types and taxonomies (theme activation)

**Phase 2**: Import initial content (via CSV or WP All Import plugin):
1. Staff profiles (5-10 profiles)
2. Medical terms glossary (50-100 terms)
3. Core articles (50 articles as per assumption)
4. Categories and taxonomies
5. Support resources

**Phase 3**: Translation workflow:
1. English content published first (FR-051)
2. Send for professional medical translation
3. Import Sinhala translations
4. Import Tamil translations
5. Link translations via Polylang

**Phase 4**: Create patient journeys:
1. Group related articles into journey modules
2. Translate journey titles and descriptions
3. Link journey translations

### Content Management Workflow

**New article creation**:
1. Medical staff writes content in English
2. Set template type, audience, and categories
3. Add medical review date and reviewer name
4. Publish English version
5. Send to translator
6. Translator creates Sinhala/Tamil versions via Polylang
7. Medical staff reviews translations
8. Publish translations

**Content updates**:
1. Edit article in WordPress
2. Add version note describing changes
3. Save (creates revision)
4. Update medical review date
5. Sync translations (translator updates other languages)
6. Frontend displays "Updated [date]" notice (FR-005a)

---

## Next Steps

1. ✅ **Phase 1 Complete**: Data model documented
2. **Phase 1 Next**: Generate API contracts for AJAX endpoints
3. **Phase 1 Next**: Create quickstart.md with development setup
4. **Phase 1 Next**: Update agent context with data model

**Data Model Complete**: Ready for contracts and quickstart documentation
