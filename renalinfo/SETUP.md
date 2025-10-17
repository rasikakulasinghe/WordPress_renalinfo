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

**Phase 1: Setup** - ~85% complete
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
- [ ] Screenshot.png (placeholder needed)
- [ ] npm dependencies installed
- [ ] Assets built

**Ready for**: Phase 2 (Foundational) implementation once theme is activated and tested.

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
