# Phase 3: User Story 1 - Testing Guide

## Overview
Testing guide for validating the "Parent Finding Treatment Information" user story implementation. This ensures parents can find and read condition explainer articles within 3 clicks with appropriate accessibility and readability.

---

## Test Environment Setup

### Prerequisites
1. WordPress 6.0+ installed locally
2. RenalInfo theme activated
3. Permalink structure: Post name (`/%postname%/`)
4. Testing browsers: Chrome, Firefox, Safari, Edge
5. Testing devices: Desktop, Tablet, Mobile

### Required Plugins for Testing
- **Polylang (Free)** - For multilingual testing (Phase 4 prep)
- **Query Monitor** - For debugging and performance monitoring
- **Classic Editor** (optional) - For easier content creation

### Sample Content Creation

#### 1. Create Article Categories
Navigate to: `Articles → Categories`

Create the following categories:
- **Kidney Conditions** (slug: `kidney-conditions`)
- **Dialysis Information** (slug: `dialysis-information`)
- **Transplant Guide** (slug: `transplant-guide`)
- **Nutrition & Diet** (slug: `nutrition-diet`)
- **Medications** (slug: `medications`)
- **Living with CKD** (slug: `living-with-ckd`)

Add descriptions to each category (50-100 words explaining the category).

#### 2. Create Medical Terms
Navigate to: `Medical Terms → Add New`

**Sample Medical Terms:**

1. **CKD** (Chronic Kidney Disease)
   - Abbreviation: `CKD`
   - Full Name: `Chronic Kidney Disease`
   - Pronunciation: `KRON-ik KID-nee dih-ZEEZ`
   - Simple Definition: `Long-term damage to the kidneys that gets worse over time. The kidneys can't clean blood as well as they should.`
   - Technical Definition: `Progressive loss of kidney function over time, characterized by decreased glomerular filtration rate and/or evidence of kidney damage.`

2. **GFR** (Glomerular Filtration Rate)
   - Abbreviation: `GFR`
   - Full Name: `Glomerular Filtration Rate`
   - Pronunciation: `gloh-MAIR-yoo-lar fil-TRAY-shun rayt`
   - Simple Definition: `A test that shows how well your kidneys are working. Higher numbers are better.`
   - Technical Definition: `Volume of fluid filtered from the renal glomerular capillaries into Bowman's capsule per unit time (mL/min/1.73m²).`

3. **Nephrologist**
   - Full Name: `Nephrologist`
   - Pronunciation: `neh-FROL-oh-jist`
   - Simple Definition: `A kidney doctor. A medical specialist who treats kidney diseases.`
   - Technical Definition: `Physician specializing in internal medicine with subspecialty training in nephrology and kidney disease management.`

4. **Dialysis**
   - Full Name: `Dialysis`
   - Pronunciation: `dy-AL-ih-sis`
   - Simple Definition: `A treatment that cleans your blood when your kidneys can't do it themselves.`
   - Technical Definition: `Renal replacement therapy that removes waste products and excess fluid from blood when kidneys are unable to adequately perform these functions.`

#### 3. Create Sample Condition Explainer Article

Navigate to: `Articles → Add New`

**Article 1: "Understanding Chronic Kidney Disease in Children"**

**Content:**
```
Chronic Kidney Disease (CKD) is a condition where a child's kidneys gradually lose their ability to work properly over time. The kidneys are important organs that clean waste from the blood, control blood pressure, and make sure the body has the right balance of minerals.

## What Happens in CKD?

When children have CKD, their kidneys can't filter blood as well as they should. This means waste products can build up in the body. Doctors measure kidney function using a test called GFR (Glomerular Filtration Rate) to see how well the kidneys are working.

## What Causes CKD in Children?

Several things can cause CKD in children:
- Birth defects affecting the kidneys or urinary tract
- Inherited kidney diseases
- Infections or blockages
- Kidney damage from other diseases

## How is CKD Treated?

Your Nephrologist (kidney doctor) will create a treatment plan for your child. Treatment may include:
- Medications to control blood pressure
- Special diet to reduce strain on kidneys
- Treatment for anemia (low blood cell count)
- In advanced cases, Dialysis or transplant may be needed

## Living with CKD

Many children with CKD can live normal, active lives with proper treatment. Your healthcare team will work with you to:
- Monitor kidney function regularly
- Adjust medications as needed
- Provide nutrition guidance
- Support your child's growth and development

Early detection and treatment can help slow the progression of CKD and improve your child's quality of life.
```

**Custom Fields (Article Details Meta Box):**
- Template: `condition-explainer`
- Reading Level: `8-9`
- Target Audience: `family`
- Last Medical Review: `[Current Date]`
- Reviewed By: `Dr. Sarah Johnson`

**Custom Fields (Article Content Options):**
- Key Takeaways:
  ```
  - CKD is a long-term condition where kidneys gradually lose function
  - GFR test measures how well kidneys are working
  - Treatment includes medications, diet changes, and regular monitoring
  - Many children with CKD can live normal lives with proper care
  - Early detection and treatment can slow disease progression
  ```

**Custom Fields (FAQ Items):**

Add 5 FAQ items:

1. **Q:** What is the difference between acute kidney injury and chronic kidney disease?
   **A:** Acute kidney injury happens suddenly and may be reversible. Chronic kidney disease develops slowly over time and is usually permanent.

2. **Q:** Will my child need dialysis?
   **A:** Not all children with CKD need dialysis. Your doctor will monitor kidney function and recommend dialysis only if kidneys can't clean blood adequately.

3. **Q:** Can CKD be cured?
   **A:** Most CKD cannot be cured, but treatment can slow progression. A kidney transplant can restore kidney function for children with advanced CKD.

4. **Q:** How often will we need to see the nephrologist?
   **A:** Visit frequency depends on CKD stage. Early stages may need checks every 3-6 months, while advanced stages may require monthly visits.

5. **Q:** Can my child still play sports with CKD?
   **A:** Most children with early-stage CKD can participate in sports. Check with your nephrologist about any specific restrictions.

**Categories:** Kidney Conditions
**Featured Image:** Add a child-friendly illustration (optional)

---

## Testing Checklist

### ✅ 1. Three-Click Navigation Test

**Test Steps:**
1. Navigate to homepage (`/`)
2. **Click 1:** Click "Kidney Conditions" category card
3. **Click 2:** Click "Understanding Chronic Kidney Disease in Children" article
4. **Click 3:** Article content is fully displayed

**Expected Results:**
- ✅ Total clicks from homepage to article content: ≤3
- ✅ Each page loads within 3 seconds
- ✅ Navigation path is clear and intuitive
- ✅ Breadcrumbs show: Home > Kidney Conditions > Article Title

---

### ✅ 2. Medical Term Tooltips Test

**Test Steps:**
1. Open the sample article
2. Hover over "CKD" in the content
3. Click/tap on "Nephrologist"
4. Press Tab to focus "Dialysis" via keyboard
5. Press Escape to close tooltip

**Expected Results:**
- ✅ Terms are underlined with dotted line
- ✅ Tooltip appears on hover (desktop)
- ✅ Tooltip appears on click/tap (mobile)
- ✅ Tooltip includes term, pronunciation, definition
- ✅ Tooltip is keyboard accessible
- ✅ Escape key closes tooltip
- ✅ Click outside closes tooltip
- ✅ Tooltips positioned to avoid viewport edges

**Accessibility:**
- ✅ `role="tooltip"` attribute present
- ✅ `aria-describedby` links trigger to tooltip
- ✅ `aria-expanded` toggles on trigger
- ✅ Focus returns to trigger when closed

---

### ✅ 3. FAQ Display Test

**Test Steps:**
1. Scroll to FAQ section on article
2. Inspect HTML for schema.org markup
3. Test with Google Rich Results Test

**Expected Results:**
- ✅ FAQ section displays with clear Q&A format
- ✅ Questions are bold and easy to scan
- ✅ Schema.org FAQPage markup present:
  - `@type: FAQPage`
  - Each question has `@type: Question`
  - Each answer has `@type: Answer`
- ✅ Passes Google Rich Results Test

**Validation URL:**
https://search.google.com/test/rich-results

---

### ✅ 4. Reading Level Verification

**Test Steps:**
1. Copy article text (excluding headers/metadata)
2. Paste into readability checker
3. Check Flesch-Kincaid Grade Level

**Tools:**
- https://readabilityformulas.com/
- https://www.webfx.com/tools/read-able/

**Expected Results:**
- ✅ Flesch-Kincaid Grade Level: 8-9
- ✅ Flesch Reading Ease: 60-70 (Standard/Easy)
- ✅ No medical jargon without definition
- ✅ Sentences average <20 words
- ✅ Paragraphs average <5 sentences

---

### ✅ 5. Responsive Design Test

**Test Viewports:**
- Mobile: 375px × 667px (iPhone SE)
- Tablet: 768px × 1024px (iPad)
- Desktop: 1920px × 1080px

**Test Steps for Each Viewport:**
1. Navigate through homepage → category → article
2. Test filters on archive page
3. Interact with tooltips
4. Check image loading (WebP support)

**Expected Results:**
- ✅ Layout adapts smoothly to all viewports
- ✅ Text remains readable (min 16px)
- ✅ Touch targets ≥44px × 44px (mobile)
- ✅ Images are responsive with appropriate sizes
- ✅ No horizontal scrolling
- ✅ Category cards stack on mobile (1 column)
- ✅ Featured articles grid: Mobile (1), Tablet (2), Desktop (3)

---

### ✅ 6. WCAG 2.1 AA Compliance Test

**Automated Testing:**
Use browser extensions:
- **axe DevTools** (Chrome/Firefox)
- **WAVE** (Chrome/Firefox/Edge)

**Manual Testing:**

#### 6.1 Keyboard Navigation
- ✅ Tab through all interactive elements
- ✅ Skip link visible on focus
- ✅ Focus indicators visible (2px outline)
- ✅ No keyboard traps
- ✅ Logical tab order

#### 6.2 Color Contrast
- ✅ Text on background: ≥4.5:1 ratio
- ✅ Large text (18pt+): ≥3:1 ratio
- ✅ UI components: ≥3:1 ratio
- ✅ Links distinguishable from text

**Check with:**
- Chrome DevTools Contrast Ratio
- https://webaim.org/resources/contrastchecker/

#### 6.3 Screen Reader Testing
Test with:
- **NVDA** (Windows) - Free
- **JAWS** (Windows) - Trial
- **VoiceOver** (Mac) - Built-in

**Expected Results:**
- ✅ All images have alt text
- ✅ Headings follow logical hierarchy (H1 → H2 → H3)
- ✅ Links have descriptive text
- ✅ Forms have associated labels
- ✅ ARIA attributes used correctly
- ✅ Landmark regions defined

#### 6.4 Content Structure
- ✅ Only one H1 per page (article title)
- ✅ Headings don't skip levels
- ✅ Lists use proper markup (<ul>, <ol>)
- ✅ Tables use <th> for headers (if any)

---

### ✅ 7. Performance Test

**Tools:**
- Google PageSpeed Insights
- WebPageTest.org
- Chrome DevTools Lighthouse

**Test URL:** Homepage, Category Archive, Single Article

**Expected Results:**
- ✅ PageSpeed Score: ≥90 (mobile & desktop)
- ✅ Largest Contentful Paint (LCP): <2.5s
- ✅ First Input Delay (FID): <100ms
- ✅ Cumulative Layout Shift (CLS): <0.1
- ✅ Total page weight: <1MB
- ✅ Images optimized (WebP with fallbacks)
- ✅ CSS minified
- ✅ JavaScript minified

---

### ✅ 8. Cross-Browser Testing

**Test Browsers:**
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile Safari (iOS)
- ✅ Chrome Mobile (Android)

**Test Features:**
- Tooltips display correctly
- CSS Grid layouts work
- Custom properties render
- WebP images display (with PNG fallback)
- JavaScript functionality works

---

### ✅ 9. Content Features Test

#### 9.1 Key Takeaways Display
- ✅ Displays as styled list/box
- ✅ Visually distinct from main content
- ✅ Easy to scan

#### 9.2 Related Articles
- ✅ Shows 3 related articles
- ✅ Manual selection works (if set)
- ✅ Auto-detection by category works
- ✅ Thumbnails display correctly
- ✅ Links work

#### 9.3 Medical Review Information
- ✅ Review date displays
- ✅ Reviewer name displays
- ✅ Update notice shows if >6 months old

#### 9.4 Audience Badge
- ✅ "For Families" badge displays
- ✅ Correct color (distinct from professional)
- ✅ Appears on cards and single article

---

### ✅ 10. Filtering Functionality Test

**Archive Page Filters:**

1. **Audience Filter**
   - ✅ Filter shows "All Audiences", "For Families", "For Professionals"
   - ✅ Selecting "For Families" shows only family articles
   - ✅ Selecting "For Professionals" shows only professional articles
   - ✅ Filter persists in URL (?audience=family)

2. **Reading Level Filter**
   - ✅ Filter shows "All Levels", "Grade 8-9", "Grade 10-12", "College+"
   - ✅ Selecting level filters articles correctly
   - ✅ Multiple filters work together
   - ✅ "No results" message displays if no matches

---

## Bug Tracking Template

If issues are found, document using this format:

```markdown
### Bug #XX: [Brief Description]

**Severity:** Critical / High / Medium / Low
**Browser:** [Browser Name & Version]
**Device:** [Desktop / Tablet / Mobile]
**URL:** [Page where bug occurs]

**Steps to Reproduce:**
1. Step one
2. Step two
3. Step three

**Expected Result:**
[What should happen]

**Actual Result:**
[What actually happens]

**Screenshots:**
[Attach screenshots if applicable]

**WCAG Violation:** [If accessibility-related]
- Guideline: [e.g., 1.4.3 Contrast]
- Level: [A / AA / AAA]
```

---

## Sign-Off Checklist

Before marking Phase 3 complete:

- [ ] All 10 test sections passed
- [ ] No critical or high-severity bugs
- [ ] WCAG 2.1 AA compliance verified
- [ ] PageSpeed scores ≥90
- [ ] Reading level 8-9 grade verified
- [ ] Cross-browser testing complete
- [ ] Mobile responsiveness verified
- [ ] Medical term tooltips working
- [ ] 3-click navigation confirmed
- [ ] Documentation updated

---

## Next Steps After Testing

1. **Fix Any Bugs:** Address issues in priority order (Critical → High → Medium → Low)
2. **Update Documentation:** Document any edge cases or special configurations
3. **Create More Sample Content:** Build out content library for Phase 4 testing
4. **Performance Optimization:** Implement caching, lazy loading if needed
5. **Prepare for Phase 4:** Multilingual content with Polylang integration

---

## Notes for Testers

- Focus on **parent user experience** - would a non-medical parent understand?
- Test on **real devices** when possible, not just emulators
- Check **print styles** - articles should print cleanly
- Verify **focus never gets lost** during keyboard navigation
- Test with **screen zoom** to 200% (WCAG requirement)
- Check **dark mode** if system supports it

**Testing should prioritize real-world scenarios over edge cases.**
