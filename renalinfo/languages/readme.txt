# Translation Files

This directory contains translation files for the RenalInfo theme.

## Overview

The RenalInfo theme supports three languages:
- **English (en_US)** - Default language
- **Sinhala (si_LK)** - සිංහල
- **Tamil (ta_LK)** - தமிழ்

Translation is managed through two systems:
1. **WordPress .po/.mo files** - For theme strings marked with translation functions
2. **Polylang** - For content translation (articles, pages, menus)

## File Structure

```
languages/
├── readme.txt                    # This file
├── renalinfo.pot                 # Translation template (all translatable strings)
├── renalinfo-si_LK.po           # Sinhala translations (human-readable)
├── renalinfo-si_LK.mo           # Sinhala translations (compiled)
├── renalinfo-ta_LK.po           # Tamil translations (human-readable)
└── renalinfo-ta_LK.mo           # Tamil translations (compiled)
```

## Generating POT File

The POT (Portable Object Template) file contains all translatable strings from the theme.

### Method 1: Using WP-CLI (Recommended)

If you have WP-CLI installed, run this command from the theme directory:

```bash
wp i18n make-pot . languages/renalinfo.pot --domain=renalinfo
```

**Options:**
- `--exclude=node_modules,vendor` - Exclude directories
- `--skip-js` - Skip JavaScript files (if needed)
- `--headers='{"Report-Msgid-Bugs-To":"https://github.com/rasikakulasinghe/WordPress_renalinfo/issues"}'` - Add custom headers

**Full command with options:**
```bash
wp i18n make-pot . languages/renalinfo.pot \
  --domain=renalinfo \
  --exclude=node_modules,vendor,assets/js/main.js \
  --headers='{"Report-Msgid-Bugs-To":"https://github.com/rasikakulasinghe/WordPress_renalinfo/issues","Last-Translator":"RenalInfo Team"}'
```

### Method 2: Using Poedit

1. **Download Poedit**: https://poedit.net/download
2. **Open Poedit** and select "File → New from POT/PO file"
3. **Scan theme directory**:
   - File → New
   - Select "WordPress theme"
   - Browse to theme directory
   - Click "Extract from sources"
4. **Save as** `languages/renalinfo.pot`

### Method 3: Using makepot.php (WordPress Core Tool)

```bash
php /path/to/wordpress/wp-content/themes/renalinfo/vendor/bin/makepot.php wp-theme . languages/renalinfo.pot
```

## Creating Language-Specific .po Files

### From POT File (First Time)

1. **Copy POT to PO**:
   ```bash
   cp languages/renalinfo.pot languages/renalinfo-si_LK.po
   cp languages/renalinfo.pot languages/renalinfo-ta_LK.po
   ```

2. **Update headers** in .po files:
   ```
   "Language: si_LK\n"
   "Plural-Forms: nplurals=2; plural=(n != 1);\n"
   ```

3. **Translate strings** using Poedit or manually

### Using Poedit (Recommended)

1. Open Poedit
2. File → New from POT/PO file → Select `renalinfo.pot`
3. Choose language (Sinhala or Tamil)
4. Save as `renalinfo-si_LK.po` or `renalinfo-ta_LK.po`
5. Translate strings
6. Save (Poedit automatically generates .mo file)

## Compiling .po to .mo Files

### Using WP-CLI

```bash
wp i18n make-mo languages/
```

This will compile all .po files in the languages directory to .mo files.

### Using Poedit

.mo files are automatically generated when you save .po files in Poedit.

### Using msgfmt (Manual)

```bash
msgfmt -o languages/renalinfo-si_LK.mo languages/renalinfo-si_LK.po
msgfmt -o languages/renalinfo-ta_LK.mo languages/renalinfo-ta_LK.po
```

## Translation Workflow

### For Theme Developers

1. **Add translatable strings** in code:
   ```php
   __( 'Text', 'renalinfo' )
   _e( 'Text', 'renalinfo' )
   esc_html__( 'Text', 'renalinfo' )
   esc_html_e( 'Text', 'renalinfo' )
   ```

2. **Register Polylang strings** (already done in functions.php):
   ```php
   pll_register_string( 'renalinfo', 'String name', 'renalinfo' );
   ```

3. **Update POT file** after adding new strings:
   ```bash
   wp i18n make-pot . languages/renalinfo.pot --domain=renalinfo
   ```

4. **Update .po files** with new strings:
   ```bash
   msgmerge -U languages/renalinfo-si_LK.po languages/renalinfo.pot
   msgmerge -U languages/renalinfo-ta_LK.po languages/renalinfo.pot
   ```

5. **Translate new strings** in Poedit

6. **Compile .mo files**:
   ```bash
   wp i18n make-mo languages/
   ```

### For Translators

1. **Open .po file** in Poedit
2. **Translate untranslated strings**
3. **Review fuzzy translations** (marked with yellow)
4. **Save** (generates .mo file automatically)
5. **Test** on WordPress site

## Translation Best Practices

### Text Domain

Always use `renalinfo` as the text domain:
```php
__( 'Text', 'renalinfo' )  // ✓ Correct
__( 'Text', 'textdomain' ) // ✗ Wrong
__( 'Text' )               // ✗ Wrong (missing domain)
```

### Context

Use context for ambiguous strings:
```php
_x( 'Post', 'verb', 'renalinfo' )  // To post something
_x( 'Post', 'noun', 'renalinfo' )  // A blog post
```

### Plurals

Use plural functions:
```php
_n( '%s item', '%s items', $count, 'renalinfo' )
```

### Escaping

Always escape translated strings:
```php
esc_html__( 'Text', 'renalinfo' )
esc_attr__( 'Text', 'renalinfo' )
```

### Polylang Strings

For Polylang-registered strings, use:
```php
pll__( 'String name' )  // Get translation
pll_e( 'String name' )  // Echo translation
```

## Testing Translations

### Switch Language

1. Go to WordPress Admin → Settings → Languages
2. Select Sinhala or Tamil
3. Visit frontend to see translated strings

### Verify Polylang Strings

1. Go to Languages → String translations
2. Search for registered strings
3. Add translations
4. Test on frontend with language switcher

### Check Missing Translations

Use Poedit's "Catalog → Update from sources" to find untranslated strings.

## Common Translation Strings

The theme includes 50+ pre-registered Polylang strings:

- UI: "Read More", "For Families", "For Professionals"
- Navigation: "Home", "Previous", "Next"
- Article meta: "Reading Time", "Last Updated"
- Language switcher: "Select Language", "Choose Your Language"
- Search: "Search Results for", "Nothing Found"

See `functions.php` → `renalinfo_register_polylang_strings()` for complete list.

## Troubleshooting

### Translations not showing

1. **Check .mo file exists**: Compiled .mo file must be in `languages/` directory
2. **Verify locale**: File name must match WordPress locale (si_LK, ta_LK)
3. **Clear cache**: Clear any caching plugins
4. **Check text domain**: Ensure all strings use `renalinfo` domain

### POT file generation errors

1. **Update WP-CLI**: `wp cli update`
2. **Check permissions**: Ensure `languages/` directory is writable
3. **Verify syntax**: PHP syntax errors will prevent extraction

### Polylang strings not translating

1. **Check registration**: Strings must be registered in `functions.php`
2. **Translate in admin**: Go to Languages → String translations
3. **Clear cache**: Clear WordPress object cache

## Resources

- **WP-CLI i18n**: https://developer.wordpress.org/cli/commands/i18n/
- **Poedit**: https://poedit.net/
- **WordPress i18n**: https://developer.wordpress.org/apis/internationalization/
- **Polylang Docs**: https://polylang.pro/doc/
- **Translation Teams**: https://translate.wordpress.org/

## Support

For translation issues or questions:
- GitHub Issues: https://github.com/rasikakulasinghe/WordPress_renalinfo/issues
- WordPress.org Support: https://wordpress.org/support/theme/renalinfo/

---

Last updated: October 17, 2025
