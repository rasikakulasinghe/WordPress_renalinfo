# Custom Post Types & Taxonomies Quick Reference

## Custom Post Types

### 1. Article (`article`)
**Purpose**: Main medical information content  
**URL**: `/article/article-slug/`  
**Admin Menu**: "Articles"  
**Icon**: Document icon  
**Supports**: Title, Editor, Author, Thumbnail, Excerpt, Revisions, Custom Fields  
**Archive**: Yes (`/article/`)

**Taxonomies**:
- Article Category (hierarchical)
- Audience Type (for targeting families vs. professionals)

**Custom Fields** (to be implemented):
- Article Template (_article_template)
- Reading Level (_reading_level)
- Audience (_audience)
- Medical Review Date (_medical_review_date)
- Version Notes (_version_notes)
- Redirect URL (_redirect_url) for archived content

**Templates**:
- Condition Explainer
- Treatment Procedure
- Professional Article

---

### 2. Journey (`journey`)
**Purpose**: Sequential article collections (e.g., "Kidney Transplant Journey")  
**URL**: `/journey/journey-slug/`  
**Admin Menu**: "Journeys"  
**Icon**: Arrow right icon  
**Supports**: Title, Editor, Thumbnail, Revisions  
**Archive**: Yes (`/journey/`)

**Custom Fields** (to be implemented):
- Journey Articles (_journey_articles) - Ordered list of article IDs
- Journey Audience (_journey_audience)
- Estimated Time (_estimated_time)
- Journey Status (_journey_status)

**Features**:
- Article X of Y progress indicator
- Next/Previous navigation
- Overview page with all articles
- "Part of Journey" badge on articles

---

### 3. Staff (`staff`)
**Purpose**: Team member profiles  
**URL**: `/staff/staff-slug/`  
**Admin Menu**: "Team"  
**Icon**: Groups icon  
**Supports**: Title, Editor, Thumbnail, Revisions  
**Archive**: Yes (`/staff/`)

**Taxonomies**:
- Specialization (e.g., "Pediatric Nephrologist", "Nurse Practitioner")

**Custom Fields** (to be implemented):
- Role (_staff_role)
- Credentials (_staff_credentials)
- Personal Bio (_staff_personal_bio) - Friendly, approachable
- Professional Bio (_staff_professional_bio) - Credentials, experience
- Contact Email (_staff_email)
- Contact Phone (_staff_phone)
- Office Hours (_staff_hours)
- Languages Spoken (_staff_languages)
- Photo (_staff_photo) - 400×400px

**Features**:
- Grid/card layout on archive
- Links to authored articles
- Professional credentials display
- Contact information

---

### 4. Medical Term (`medical_term`)
**Purpose**: Glossary entries for medical terminology  
**URL**: `/glossary/term-slug/`  
**Admin Menu**: Under "Articles" → "Medical Terms"  
**Icon**: Book icon  
**Supports**: Title, Editor, Revisions  
**Archive**: Yes (`/glossary/`)

**Custom Fields** (to be implemented):
- Abbreviation (_term_abbreviation) - e.g., "CKD" for "Chronic Kidney Disease"
- Synonyms (_term_synonyms) - Alternative terms
- Simple Definition (_term_simple_definition) - For families
- Technical Definition (_term_technical_definition) - For professionals
- Related Terms (_related_terms) - Links to other glossary entries

**Features**:
- Tooltip integration in article content
- Search by abbreviation (e.g., search "CKD" finds "Chronic Kidney Disease")
- Admin quick-edit for abbreviations
- Dual-audience definitions

---

### 5. Support Resource (`support_resource`)
**Purpose**: External support organizations, helplines, materials  
**URL**: `/resources/resource-slug/`  
**Admin Menu**: "Support Resources"  
**Icon**: Heart icon  
**Supports**: Title, Editor, Thumbnail, Revisions  
**Archive**: Yes (`/resources/`)

**Taxonomies**:
- Resource Type (e.g., "Support Group", "Educational Material", "Helpline")

**Custom Fields** (to be implemented):
- Resource URL (_resource_url) - External link
- Contact Phone (_resource_phone)
- Contact Email (_resource_email)
- Availability Hours (_resource_hours)
- Location (_resource_location)
- Languages (_resource_languages)
- Target Audience (_resource_audience)

**Features**:
- External link icon
- rel="noopener noreferrer" for security
- Appears on related article pages
- Filter by resource type

---

## Custom Taxonomies

### 1. Article Category (`article_category`)
**Type**: Hierarchical (like categories)  
**Applies to**: Article  
**URL**: `/article-category/category-slug/`  
**Purpose**: Organize articles by medical topics

**Example Structure**:
```
Kidney Conditions
  ├── Chronic Kidney Disease
  ├── Acute Kidney Injury
  └── Nephrotic Syndrome
Treatments
  ├── Dialysis
  ├── Transplant
  └── Medications
Laboratory Tests
  └── Blood Tests
      ├── Creatinine
      └── eGFR
```

---

### 2. Audience Type (`audience_type`)
**Type**: Non-hierarchical (like tags)  
**Applies to**: Article  
**URL**: `/audience/audience-slug/`  
**Purpose**: Target content to specific audiences

**Standard Terms**:
- For Families
- For Professionals
- For Patients
- For Caregivers

**Features**:
- Badge display on article cards
- Filter in search results
- Separate navigation paths

---

### 3. Specialization (`specialization`)
**Type**: Non-hierarchical  
**Applies to**: Staff  
**URL**: `/specialization/spec-slug/`  
**Purpose**: Categorize staff by medical specialty

**Example Terms**:
- Pediatric Nephrologist
- Nurse Practitioner
- Dietitian
- Social Worker
- Transplant Coordinator

**Features**:
- Filter staff by specialty
- Link to related articles
- Display on staff profile

---

### 4. Resource Type (`resource_type`)
**Type**: Non-hierarchical  
**Applies to**: Support Resource  
**URL**: `/resource-type/type-slug/`  
**Purpose**: Categorize support resources

**Example Terms**:
- Support Group
- Educational Material
- Helpline
- Financial Assistance
- Transportation Services
- Parent Network

**Features**:
- Filter resources archive
- Icon display per type
- Quick access sections

---

## Content Relationships

### Article → Journey
- Articles can be part of multiple journeys
- Journey stores ordered list of article IDs
- Navigation: Previous/Next within journey context

### Article → Medical Term
- Medical terms automatically linked in article content
- Tooltip display on hover
- Click to view full glossary entry

### Article → Staff (Author)
- Articles linked to staff member as author
- Display author bio and credentials
- Link to author's other articles

### Article → Support Resource
- Related resources displayed on article pages
- Filtered by article category or tags
- Manual selection or automatic matching

### Staff → Article
- Query articles by staff author
- Display on staff profile
- Filterable by article category

---

## URL Structure

### Standard Posts
- Article: `/article/chronic-kidney-disease/`
- Journey: `/journey/transplant-preparation/`
- Staff: `/staff/dr-jane-smith/`
- Medical Term: `/glossary/egfr/`
- Support Resource: `/resources/kidney-foundation/`

### Archives
- All Articles: `/article/`
- Article Category: `/article-category/kidney-conditions/`
- Audience: `/audience/for-families/`
- All Journeys: `/journey/`
- All Staff: `/staff/`
- Specialization: `/specialization/nephrologist/`
- All Glossary: `/glossary/`
- All Resources: `/resources/`
- Resource Type: `/resource-type/support-group/`

### Language Variants (with Polylang)
- English: `/en/article/chronic-kidney-disease/`
- Sinhala: `/si/article/chronic-kidney-disease/`
- Tamil: `/ta/article/chronic-kidney-disease/`

---

## Admin Menu Structure

```
Dashboard
Posts
Media
Pages
Comments
Articles
  ├── All Articles
  ├── Add New
  ├── Categories (article_category)
  ├── Audience (audience_type)
  └── Medical Terms
      ├── All Medical Terms
      └── Add New
Journeys
  ├── All Journeys
  └── Add New
Team (Staff)
  ├── All Staff
  ├── Add New
  └── Specializations
Support Resources
  ├── All Resources
  ├── Add New
  └── Resource Types
Appearance
Tools
Settings
Languages (Polylang)
```

---

## Content Creation Workflow

### Creating a New Article
1. Articles → Add New
2. Enter title (e.g., "Understanding Chronic Kidney Disease")
3. Add content in editor
4. Select Article Category
5. Select Audience Type (For Families/For Professionals)
6. Choose Article Template (custom field)
7. Set Reading Level (custom field)
8. Add Medical Review Date
9. Upload Featured Image (1200×600px)
10. Publish

### Creating a Patient Journey
1. Journeys → Add New
2. Enter journey name (e.g., "Kidney Transplant Preparation")
3. Add overview content
4. Select articles to include (custom field)
5. Order articles sequentially
6. Set estimated completion time
7. Upload Featured Image
8. Publish

### Creating a Staff Profile
1. Team → Add New
2. Enter staff name
3. Add personal bio (friendly tone)
4. Add professional bio (credentials)
5. Select Specialization
6. Add contact information (custom fields)
7. List languages spoken
8. Upload profile photo (400×400px)
9. Publish

### Creating a Glossary Term
1. Articles → Medical Terms → Add New
2. Enter term name (e.g., "eGFR")
3. Add abbreviation (custom field)
4. Add simple definition (for families)
5. Add technical definition (for professionals)
6. Add synonyms
7. Link related terms
8. Publish

### Creating a Support Resource
1. Support Resources → Add New
2. Enter resource name
3. Add description
4. Select Resource Type
5. Add external URL (custom field)
6. Add contact information
7. Set availability hours
8. Publish

---

## Best Practices

### Articles
- Keep family content at grade 8-9 reading level
- Include FAQs section for condition explainers
- Add medical term tooltips for technical terms
- Link to related treatments and support resources
- Update Medical Review Date annually

### Journeys
- Limit to 5-10 articles per journey
- Ensure logical progression
- Provide estimated reading time
- Create clear overview page

### Staff Profiles
- Use friendly, approachable photos
- Keep personal bio warm and relatable
- List credentials clearly in professional bio
- Update annually

### Glossary Terms
- Provide both simple and technical definitions
- Include all common abbreviations
- Link related terms
- Keep definitions concise

### Support Resources
- Verify URLs regularly
- Update contact information
- Note if services are available in multiple languages
- Indicate target audience clearly

---

**Last Updated**: October 16, 2025  
**Theme Version**: 1.0.0
