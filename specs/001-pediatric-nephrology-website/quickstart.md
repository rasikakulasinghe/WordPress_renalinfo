# Quickstart Guide: RenalInfo WordPress Theme Development

**Feature**: 001-pediatric-nephrology-website  
**Date**: 2025-10-16  
**Version**: 1.0.0

## Overview

This guide provides step-by-step instructions for setting up a local development environment for the RenalInfo paediatric nephrology clinic WordPress theme. Follow these instructions to get from zero to a running development site in approximately 30 minutes.

---

## Prerequisites

### Required Software

| Software | Minimum Version | Recommended | Purpose |
|----------|----------------|-------------|---------|
| **PHP** | 7.4 | 8.0+ | WordPress core requirement |
| **MySQL** or **MariaDB** | 5.7 / 10.3 | 8.0 / 10.6 | Database |
| **Node.js** | 16.x | 18.x LTS | Build tools (npm) |
| **Git** | 2.x | Latest | Version control |
| **Composer** | 2.x | Latest | PHP dependencies (for PHP_CodeSniffer) |
| **Local WordPress Environment** | — | Local by Flywheel, XAMPP, or Docker | WordPress hosting |

### Recommended Tools

- **Code Editor**: VS Code with extensions:
  - PHP Intelephense
  - WordPress Snippets
  - ESLint
  - Stylelint
  - EditorConfig
- **Browser**: Chrome or Firefox with DevTools
- **Browser Extensions**:
  - axe DevTools (accessibility testing)
  - React Developer Tools (if using Gutenberg blocks)

---

## Step 1: Set Up Local WordPress Environment

### Option A: Local by Flywheel (Recommended for Beginners)

1. **Download and install** [Local by Flywheel](https://localwp.com/)

2. **Create new site**:
   - Click "+" to create a new site
   - Site name: `renalinfo-dev`
   - Environment: PHP 8.0, Web Server: Nginx, Database: MySQL 8.0
   - WordPress credentials:
     - Username: `admin`
     - Password: (choose a strong password)
     - Email: your email

3. **Start site** and note the local URL (e.g., `http://renalinfo-dev.local`)

4. **Access site**:
   - Frontend: `http://renalinfo-dev.local`
   - Admin: `http://renalinfo-dev.local/wp-admin`

### Option B: Docker (Recommended for Advanced Users)

1. **Create `docker-compose.yml`** in project root:

```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:6.4-php8.0-apache
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DEBUG: 1
      WORDPRESS_CONFIG_EXTRA: |
        define('WP_DEBUG_LOG', true);
        define('WP_DEBUG_DISPLAY', false);
        define('SCRIPT_DEBUG', true);
        define('SAVEQUERIES', true);
    volumes:
      - ./renalinfo:/var/www/html/wp-content/themes/renalinfo
      - wordpress_data:/var/www/html

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: rootpassword

volumes:
  wordpress_data:
  db_data:
```

2. **Start containers**:
```bash
docker-compose up -d
```

3. **Access site**:
   - Frontend: `http://localhost:8080`
   - Admin: `http://localhost:8080/wp-admin`
   - phpMyAdmin: `http://localhost:8081`

4. **Complete WordPress installation** through web interface

---

## Step 2: Clone Repository

1. **Navigate to themes directory**:

```bash
# Local by Flywheel
cd ~/Local\ Sites/renalinfo-dev/app/public/wp-content/themes/

# Docker
cd ./renalinfo  # Already mounted in docker-compose
```

2. **Clone repository**:

```bash
git clone https://github.com/rasikakulasinghe/WordPress_renalinfo.git renalinfo
cd renalinfo
```

3. **Checkout feature branch**:

```bash
git checkout 001-pediatric-nephrology-website
```

---

## Step 3: Install Dependencies

### PHP Dependencies (Code Quality Tools)

1. **Install Composer dependencies**:

```bash
composer install
```

This installs:
- `squizlabs/php_codesniffer`: PHP linting
- `wp-coding-standards/wpcs`: WordPress coding standards
- `phpcompatibility/phpcompatibility-wp`: PHP compatibility checker

2. **Configure PHP_CodeSniffer**:

```bash
# Set WordPress coding standards as default
./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs,vendor/phpcompatibility/phpcompatibility-wp

# Set default standard
./vendor/bin/phpcs --config-set default_standard WordPress
```

3. **Verify installation**:

```bash
./vendor/bin/phpcs --version
# Should output: PHP_CodeSniffer version 3.x.x
```

### Node.js Dependencies (Build Tools)

1. **Install npm packages**:

```bash
npm install
```

This installs (as defined in research.md):
- `postcss` + `postcss-cli`: CSS processing
- `postcss-preset-env`: Modern CSS features
- `autoprefixer`: Browser prefixes
- `cssnano`: CSS minification
- `esbuild`: JavaScript bundling
- `stylelint` + config: CSS linting
- `eslint` + config: JavaScript linting

2. **Verify installation**:

```bash
npm run lint
# Should run PHP, CSS, and JS linters
```

---

## Step 4: Install Required WordPress Plugins

1. **Log in to WordPress admin**: `http://renalinfo-dev.local/wp-admin`

2. **Install plugins**:
   - Navigate to **Plugins > Add New**
   - Search and install these plugins:

| Plugin | Purpose | Required? |
|--------|---------|-----------|
| **Polylang** | Multilingual content management | ✅ Yes |
| **Theme Check** | Theme validation | ✅ Yes (dev only) |
| **Query Monitor** | Performance monitoring | Recommended (dev only) |
| **Classic Editor** | If not using Gutenberg | Optional |

3. **Activate plugins**:
   - Polylang
   - Theme Check (for development)
   - Query Monitor (for development)

4. **Configure Polylang**:
   - Go to **Languages > Languages**
   - Add languages:
     - English (en): Default language
     - Sinhala (si): Add with flag
     - Tamil (ta): Add with flag
   - Language switcher: **Show in header menu location**
   - URL structure: **The language code is added to all URLs**

---

## Step 5: Activate RenalInfo Theme

1. **Navigate to Appearance > Themes** in WordPress admin

2. **Activate "RenalInfo"** theme

3. **Verify theme activation**:
   - Visit frontend: Should see basic theme structure
   - Check for PHP errors in **Tools > Site Health > Info > Server**

---

## Step 6: Build Assets

### Initial Build

```bash
# Build CSS and JavaScript
npm run build
```

This compiles:
- `assets/css/src/style.css` → `assets/css/style.css` (minified)
- `assets/js/src/main.js` → `assets/js/main.min.js` (bundled & minified)

### Watch Mode (for active development)

```bash
# Watch for file changes and rebuild automatically
npm run watch
```

Leave this running in a terminal window while developing. Files rebuild instantly on save.

---

## Step 7: Import Sample Content (Optional but Recommended)

### Create Sample Data

1. **Navigate to Tools > Import** in WordPress admin

2. **Import sample content**:
   - Download [WordPress sample content XML](https://github.com/WPTT/theme-unit-test/blob/master/themeunittestdata.wordpress.xml)
   - Import to get sample posts/pages

3. **Create custom content**:

**Sample Articles** (create 5-10):
- Go to **Articles > Add New** (custom post type)
- Title: e.g., "Chronic Kidney Disease"
- Content: Sample medical content
- Template: Select "Condition Explainer"
- Audience: "For Families"
- Language: Start with English
- Featured Image: Upload sample image
- Publish

**Sample Staff Profiles** (create 3-5):
- Go to **Staff > Add New**
- Title: e.g., "Dr. Jane Smith"
- Add bio content
- Set featured image (staff photo)
- Fill custom fields
- Publish

**Sample Medical Terms** (create 10-20):
- Go to **Medical Terms > Add New**
- Add abbreviations, definitions
- Link to related articles

### Create Translations

1. **Edit English article**

2. **Click "+" next to language flag** in Polylang meta box

3. **Create Sinhala version**:
   - Translates content
   - Links translations automatically

4. **Repeat for Tamil**

---

## Step 8: Configure Theme Settings

### Menus

1. **Go to Appearance > Menus**

2. **Create "Primary Menu"**:
   - Add pages: Home, About, Contact
   - Add custom links to article archives
   - Assign to "Primary Menu" location

3. **Create "Footer Menu"**:
   - Add pages: Privacy Policy, Medical Disclaimer
   - Assign to "Footer Menu" location

### Customizer Settings

1. **Go to Appearance > Customize**

2. **Site Identity**:
   - Upload site logo
   - Set tagline

3. **RenalInfo Settings** (custom panel):
   - Header: Phone number, CTA button text/link
   - Footer: Social media links, copyright text
   - Medical Disclaimer: Enter disclaimer text (syncs across all pages)

4. **Colors** (if applicable):
   - Set primary color: Calming blue (e.g., `#2C7DA0`)
   - Set secondary color: Soft green (e.g., `#61A788`)

5. **Publish changes**

---

## Step 9: Verify Installation

### Run Checklists

1. **Theme Check**:
   - Go to **Appearance > Theme Check**
   - Select "RenalInfo" theme
   - Click "Check it!"
   - ✅ Should show minimal warnings, **zero errors**

2. **PHP Code Standards**:

```bash
./vendor/bin/phpcs
```

✅ Should show zero errors (warnings acceptable during development)

3. **Accessibility Check**:
   - Visit frontend
   - Right-click > Inspect
   - Run axe DevTools scan
   - ✅ Should show zero critical issues

### Manual Testing

- [ ] Homepage loads without errors
- [ ] Navigation menu works
- [ ] Language switcher switches languages
- [ ] Search bar appears and is functional
- [ ] Article pages load with correct template
- [ ] Staff profile pages load
- [ ] Mobile responsive (test in DevTools device mode)
- [ ] Images load and are optimized

---

## Step 10: Development Workflow

### Daily Development

1. **Start environment**:

```bash
# Local by Flywheel: Open Local app and start site

# Docker:
docker-compose up -d
```

2. **Start watch mode**:

```bash
cd ~/path/to/themes/renalinfo
npm run watch
```

3. **Develop**:
   - Edit PHP files in theme directory
   - Edit CSS in `assets/css/src/`
   - Edit JS in `assets/js/src/`
   - Changes rebuild automatically (CSS/JS)
   - Refresh browser to see changes

4. **Run linters before committing**:

```bash
npm run lint
# Fix any errors before committing
```

### Git Workflow

1. **Create feature branch**:

```bash
git checkout -b feature/my-feature
```

2. **Make changes and commit**:

```bash
git add .
git commit -m "feat: Add feature description"
```

3. **Push and create pull request**:

```bash
git push origin feature/my-feature
```

4. **Code review**: Wait for review before merging

---

## Common Issues & Troubleshooting

### Issue: Theme doesn't appear in Themes list

**Cause**: Missing `style.css` with proper header

**Solution**:
```bash
# Ensure style.css exists with theme header
cat style.css | head -n 20
# Should show:
# /*
# Theme Name: RenalInfo
# ...
# */
```

### Issue: CSS/JS not loading

**Cause**: Assets not built or enqueued incorrectly

**Solution**:
```bash
# Rebuild assets
npm run build

# Check functions.php enqueue code
grep -n "wp_enqueue_style\|wp_enqueue_script" functions.php
```

### Issue: PHP errors on theme activation

**Cause**: Syntax errors or missing dependencies

**Solution**:
```bash
# Check PHP syntax
./vendor/bin/phpcs functions.php

# Enable WordPress debug mode
# Edit wp-config.php:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

# Check debug.log
tail -f wp-content/debug.log
```

### Issue: Polylang not showing languages

**Cause**: Polylang not configured

**Solution**:
1. Go to **Languages > Languages**
2. Add languages: English, Sinhala, Tamil
3. Set English as default
4. Enable language switcher in menu

### Issue: Database connection errors

**Cause**: Wrong database credentials in `wp-config.php`

**Solution** (Docker):
```php
// wp-config.php
define('DB_NAME', 'wordpress');
define('DB_USER', 'wordpress');
define('DB_PASSWORD', 'wordpress');
define('DB_HOST', 'db'); // Important for Docker!
```

### Issue: Permissions errors

**Cause**: File ownership/permissions issues

**Solution**:
```bash
# Fix ownership (adjust user:group as needed)
sudo chown -R www-data:www-data /path/to/wordpress

# Fix permissions
find /path/to/wordpress -type d -exec chmod 755 {} \;
find /path/to/wordpress -type f -exec chmod 644 {} \;
```

---

## Performance Testing

### Test Page Load Speed

1. **Install Query Monitor plugin** (if not already)

2. **Visit article page**

3. **Check Query Monitor panel**:
   - Total page load time should be < 3 seconds
   - Database queries < 50
   - HTTP requests < 30

### Google PageSpeed Insights

1. **Visit** [PageSpeed Insights](https://pagespeed.web.dev/)

2. **Enter local URL** (use ngrok for public URL):

```bash
# Install ngrok
brew install ngrok  # macOS
# or download from ngrok.com

# Expose local site
ngrok http 8080

# Copy public URL and test in PageSpeed
```

3. **Target scores**:
   - Performance: ≥ 90
   - Accessibility: 100
   - Best Practices: ≥ 90
   - SEO: ≥ 90

---

## Useful Commands Reference

### Development

```bash
# Build assets once
npm run build

# Watch for changes (keep running during development)
npm run watch

# Lint code
npm run lint            # All linters
npm run lint:php        # PHP only
npm run lint:css        # CSS only
npm run lint:js         # JavaScript only

# Fix auto-fixable issues
./vendor/bin/phpcbf     # Fix PHP
npm run lint:css -- --fix  # Fix CSS
npm run lint:js -- --fix   # Fix JS
```

### WordPress CLI (if installed)

```bash
# Activate theme
wp theme activate renalinfo

# Install plugin
wp plugin install polylang --activate

# Create sample post
wp post create --post_type=article --post_title="Sample Article" --post_status=publish

# Export database
wp db export backup.sql

# Search-replace domain (for production deployment)
wp search-replace 'http://renalinfo-dev.local' 'https://renalinfo.com'
```

### Docker

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f wordpress

# Access WordPress container shell
docker-compose exec wordpress bash

# Access database
docker-compose exec db mysql -u wordpress -pwordpress wordpress
```

---

## Next Steps After Setup

1. ✅ **Environment set up and running**
2. **Start feature development**:
   - Refer to `data-model.md` for custom post type structure
   - Refer to `contracts/ajax-api.md` for AJAX endpoints
   - Follow constitution principles from `.specify/memory/constitution.md`
3. **Run tests before committing**
4. **Create pull request for review**

---

## Additional Resources

### Documentation

- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Polylang Documentation](https://polylang.pro/doc/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

### Tools

- [Theme Check Plugin](https://wordpress.org/plugins/theme-check/)
- [Query Monitor](https://wordpress.org/plugins/query-monitor/)
- [axe DevTools](https://www.deque.com/axe/devtools/)
- [WordPress Coding Standards for VSCode](https://marketplace.visualstudio.com/items?itemName=shevaua.phpcs)

### Learning

- [WordPress.tv](https://wordpress.tv/) - Video tutorials
- [Learn WordPress](https://learn.wordpress.org/) - Official training
- [WPBeginner](https://www.wpbeginner.com/) - Beginner tutorials

---

## Support

**Issues**: Create an issue on [GitHub repository](https://github.com/rasikakulasinghe/WordPress_renalinfo/issues)

**Questions**: Contact development team via project communication channels

---

**Quickstart Guide Version**: 1.0.0  
**Last Updated**: 2025-10-16  
**Status**: ✅ Ready for use
