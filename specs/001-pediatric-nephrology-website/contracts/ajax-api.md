# API Contracts: RenalInfo Theme AJAX Endpoints

**Feature**: 001-pediatric-nephrology-website  
**Date**: 2025-10-16  
**Version**: 1.0.0

## Overview

This document defines all AJAX API endpoints for the RenalInfo WordPress theme. The theme uses WordPress AJAX API (`wp_ajax_*` and `wp_ajax_nopriv_*` actions) for asynchronous operations including search autocomplete, language selection, and optional feedback collection.

All endpoints follow WordPress security best practices with nonce verification, capability checks, and proper data sanitization/escaping.

---

## Authentication & Security

### WordPress Nonces

All AJAX requests MUST include a nonce for CSRF protection:

**Nonce generation** (in PHP):
```php
wp_localize_script( 'renalinfo-main', 'renalinfo_ajax', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'search_nonce' => wp_create_nonce( 'renalinfo_search' ),
    'feedback_nonce' => wp_create_nonce( 'renalinfo_feedback' ),
    'lang_nonce' => wp_create_nonce( 'renalinfo_lang' ),
) );
```

**Nonce verification** (in AJAX handler):
```php
check_ajax_referer( 'renalinfo_search', 'nonce' );
```

### Rate Limiting

- Search autocomplete: Max 10 requests per 10 seconds per IP (prevent abuse)
- Feedback submission: Max 5 submissions per hour per IP
- Implemented via WordPress transients

---

## Endpoint 1: Search Autocomplete

### Request

**Endpoint**: `/wp-admin/admin-ajax.php`  
**Action**: `renalinfo_search_autocomplete`  
**Method**: `POST` or `GET`  
**Auth Required**: No (public endpoint)

**Parameters**:

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `action` | string | Yes | WordPress action name | `renalinfo_search_autocomplete` |
| `nonce` | string | Yes | Security nonce | `a1b2c3d4e5...` |
| `query` | string | Yes | Search query (min 3 chars) | `kidney stones` |
| `lang` | string | No | Language code (en, si, ta); defaults to current | `si` |
| `audience` | string | No | Filter by audience: family, professional, or empty for all | `family` |

**JavaScript Example**:
```javascript
fetch( renalinfo_ajax.ajax_url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        action: 'renalinfo_search_autocomplete',
        nonce: renalinfo_ajax.search_nonce,
        query: 'kidney',
        lang: 'en',
        audience: 'family'
    })
})
.then( response => response.json() )
.then( data => {
    if ( data.success ) {
        console.log( data.data.suggestions );
    }
});
```

### Response

**Success Response** (HTTP 200):
```json
{
  "success": true,
  "data": {
    "suggestions": [
      {
        "id": 123,
        "title": "Chronic Kidney Disease",
        "excerpt": "A long-term condition where kidneys don't work as well...",
        "url": "https://example.com/en/articles/chronic-kidney-disease/",
        "type": "article",
        "audience": "family",
        "language": "en",
        "match_type": "title"
      },
      {
        "id": 456,
        "title": "Kidney Stones",
        "excerpt": "Hard deposits of minerals and salts that form...",
        "url": "https://example.com/en/articles/kidney-stones/",
        "type": "article",
        "audience": "family",
        "language": "en",
        "match_type": "content"
      },
      {
        "id": 789,
        "title": "Kidney Transplantation",
        "excerpt": "A surgical procedure to place a healthy kidney...",
        "url": "https://example.com/en/articles/kidney-transplantation/",
        "type": "article",
        "audience": "professional",
        "language": "en",
        "match_type": "title"
      }
    ],
    "total": 3,
    "query_time": 0.045
  }
}
```

**Error Response** (HTTP 200 with success: false):
```json
{
  "success": false,
  "data": {
    "message": "Query must be at least 3 characters",
    "code": "invalid_query"
  }
}
```

**Error Codes**:

| Code | Description | HTTP Status |
|------|-------------|-------------|
| `invalid_nonce` | Security nonce verification failed | 200 |
| `invalid_query` | Query less than 3 characters or empty | 200 |
| `rate_limit_exceeded` | Too many requests from this IP | 200 |
| `invalid_language` | Language code not in [en, si, ta] | 200 |

### Business Logic

1. Verify nonce
2. Check rate limit (10 requests per 10 seconds)
3. Sanitize query: `sanitize_text_field( $_REQUEST['query'] )`
4. If query < 3 chars, return error
5. Query articles matching:
   - Title contains query (highest priority)
   - Content contains query (medium priority)
   - Medical term abbreviation/synonym matches query (via glossary)
6. Filter by language (current or specified)
7. Filter by audience if specified
8. Order by relevance (title match > content match > meta match)
9. Limit to 10 results
10. Return JSON response

**PHP Handler**:
```php
add_action( 'wp_ajax_renalinfo_search_autocomplete', 'renalinfo_ajax_search_autocomplete' );
add_action( 'wp_ajax_nopriv_renalinfo_search_autocomplete', 'renalinfo_ajax_search_autocomplete' );

function renalinfo_ajax_search_autocomplete() {
    // 1. Verify nonce
    check_ajax_referer( 'renalinfo_search', 'nonce' );
    
    // 2. Rate limiting
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'renalinfo_search_rate_' . md5( $ip );
    $request_count = get_transient( $transient_key );
    
    if ( $request_count && $request_count >= 10 ) {
        wp_send_json_error( array(
            'message' => __( 'Too many requests. Please wait.', 'renalinfo' ),
            'code' => 'rate_limit_exceeded'
        ) );
    }
    
    set_transient( $transient_key, ( $request_count ? $request_count + 1 : 1 ), 10 );
    
    // 3. Sanitize input
    $query = sanitize_text_field( $_REQUEST['query'] ?? '' );
    $lang = sanitize_text_field( $_REQUEST['lang'] ?? pll_current_language() );
    $audience = sanitize_text_field( $_REQUEST['audience'] ?? '' );
    
    // 4. Validate
    if ( strlen( $query ) < 3 ) {
        wp_send_json_error( array(
            'message' => __( 'Query must be at least 3 characters', 'renalinfo' ),
            'code' => 'invalid_query'
        ) );
    }
    
    if ( ! in_array( $lang, array( 'en', 'si', 'ta' ), true ) ) {
        $lang = 'en';
    }
    
    // 5. Build query
    $args = array(
        'post_type' => 'article',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        's' => $query,
        'lang' => $lang,
    );
    
    if ( $audience && in_array( $audience, array( 'family', 'professional' ), true ) ) {
        $args['meta_query'] = array(
            array(
                'key' => '_audience',
                'value' => $audience,
                'compare' => '='
            )
        );
    }
    
    $start_time = microtime( true );
    $search_query = new WP_Query( $args );
    $query_time = microtime( true ) - $start_time;
    
    // 6. Format results
    $suggestions = array();
    if ( $search_query->have_posts() ) {
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
            
            $suggestions[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => wp_trim_words( get_the_excerpt(), 20 ),
                'url' => get_permalink(),
                'type' => 'article',
                'audience' => get_post_meta( get_the_ID(), '_audience', true ),
                'language' => $lang,
                'match_type' => 'title' // Simplified; could analyze query position
            );
        }
        wp_reset_postdata();
    }
    
    // 7. Return response
    wp_send_json_success( array(
        'suggestions' => $suggestions,
        'total' => count( $suggestions ),
        'query_time' => round( $query_time, 3 )
    ) );
}
```

---

## Endpoint 2: Feedback Submission

### Request

**Endpoint**: `/wp-admin/admin-ajax.php`  
**Action**: `renalinfo_submit_feedback`  
**Method**: `POST`  
**Auth Required**: No (optional feedback from anonymous users)

**Parameters**:

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `action` | string | Yes | WordPress action name | `renalinfo_submit_feedback` |
| `nonce` | string | Yes | Security nonce | `x1y2z3...` |
| `post_id` | integer | Yes | Article ID feedback is about | `123` |
| `helpful` | boolean | Yes | Was article helpful? | `true` or `false` |
| `comment` | string | No | Optional comment (max 500 chars) | `Very informative!` |

**JavaScript Example**:
```javascript
fetch( renalinfo_ajax.ajax_url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        action: 'renalinfo_submit_feedback',
        nonce: renalinfo_ajax.feedback_nonce,
        post_id: 123,
        helpful: true,
        comment: 'Very helpful article!'
    })
})
.then( response => response.json() )
.then( data => {
    if ( data.success ) {
        console.log( 'Feedback submitted' );
    }
});
```

### Response

**Success Response** (HTTP 200):
```json
{
  "success": true,
  "data": {
    "message": "Thank you for your feedback!",
    "feedback_id": 456
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "data": {
    "message": "You have already submitted feedback for this article",
    "code": "duplicate_feedback"
  }
}
```

**Error Codes**:

| Code | Description |
|------|-------------|
| `invalid_nonce` | Security nonce verification failed |
| `invalid_post_id` | Post ID is not a valid article |
| `rate_limit_exceeded` | Too many submissions from this IP (max 5/hour) |
| `duplicate_feedback` | User already submitted feedback for this article (session check) |
| `comment_too_long` | Comment exceeds 500 characters |

### Business Logic

1. Verify nonce
2. Check rate limit (5 submissions per hour per IP)
3. Check session for duplicate (prevent multiple submissions for same article)
4. Sanitize inputs
5. Store feedback as custom post type or meta (decision: meta on article post)
6. Increment helpful/not helpful counters
7. If comment provided, store separately for review
8. Return success

**Storage Strategy**:
- `_feedback_helpful_count` (post meta): Count of "helpful" votes
- `_feedback_not_helpful_count` (post meta): Count of "not helpful" votes
- Comments stored separately with timestamp and IP (for abuse prevention)

---

## Endpoint 3: Language Selection

### Request

**Endpoint**: `/wp-admin/admin-ajax.php`  
**Action**: `renalinfo_set_language`  
**Method**: `POST`  
**Auth Required**: No (public functionality)

**Parameters**:

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `action` | string | Yes | WordPress action name | `renalinfo_set_language` |
| `nonce` | string | Yes | Security nonce | `p1q2r3...` |
| `lang` | string | Yes | Language code: en, si, ta | `si` |

**JavaScript Example**:
```javascript
// Set language and redirect
function setLanguage( langCode ) {
    fetch( renalinfo_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'renalinfo_set_language',
            nonce: renalinfo_ajax.lang_nonce,
            lang: langCode
        })
    })
    .then( response => response.json() )
    .then( data => {
        if ( data.success ) {
            // Store in sessionStorage (session-only, per clarification Q4)
            sessionStorage.setItem( 'renalinfo_lang', langCode );
            
            // Redirect to translated page
            window.location.href = data.data.redirect_url;
        }
    });
}
```

### Response

**Success Response** (HTTP 200):
```json
{
  "success": true,
  "data": {
    "language": "si",
    "redirect_url": "https://example.com/si/articles/chronic-kidney-disease/",
    "fallback": false
  }
}
```

**Fallback Response** (translation doesn't exist):
```json
{
  "success": true,
  "data": {
    "language": "si",
    "redirect_url": "https://example.com/en/articles/chronic-kidney-disease/",
    "fallback": true,
    "message": "This article is only available in English"
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "data": {
    "message": "Invalid language code",
    "code": "invalid_language"
  }
}
```

### Business Logic

1. Verify nonce
2. Validate language code (en, si, ta)
3. Get current post ID from referer
4. Check if translation exists for requested language using Polylang
5. If exists: return translated URL
6. If not exists: return English URL with fallback flag (FR-049)
7. Client stores preference in sessionStorage (session-only per clarification)

**PHP Handler**:
```php
add_action( 'wp_ajax_renalinfo_set_language', 'renalinfo_ajax_set_language' );
add_action( 'wp_ajax_nopriv_renalinfo_set_language', 'renalinfo_ajax_set_language' );

function renalinfo_ajax_set_language() {
    check_ajax_referer( 'renalinfo_lang', 'nonce' );
    
    $lang = sanitize_text_field( $_POST['lang'] ?? '' );
    
    if ( ! in_array( $lang, array( 'en', 'si', 'ta' ), true ) ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid language code', 'renalinfo' ),
            'code' => 'invalid_language'
        ) );
    }
    
    // Get current post ID from referer
    $referer = wp_get_referer();
    $post_id = url_to_postid( $referer );
    
    if ( $post_id && function_exists( 'pll_get_post' ) ) {
        $translated_post_id = pll_get_post( $post_id, $lang );
        
        if ( $translated_post_id ) {
            // Translation exists
            wp_send_json_success( array(
                'language' => $lang,
                'redirect_url' => get_permalink( $translated_post_id ),
                'fallback' => false
            ) );
        } else {
            // Fallback to English
            $en_post_id = pll_get_post( $post_id, 'en' );
            wp_send_json_success( array(
                'language' => $lang,
                'redirect_url' => get_permalink( $en_post_id ),
                'fallback' => true,
                'message' => __( 'This article is only available in English', 'renalinfo' )
            ) );
        }
    }
    
    // Default to homepage in selected language
    wp_send_json_success( array(
        'language' => $lang,
        'redirect_url' => pll_home_url( $lang ),
        'fallback' => false
    ) );
}
```

---

## Error Handling

### Standard Error Response Format

All endpoints follow WordPress standard AJAX error format:

```json
{
  "success": false,
  "data": {
    "message": "Human-readable error message",
    "code": "machine_readable_error_code"
  }
}
```

### Global Error Codes

| Code | Description | User Action |
|------|-------------|-------------|
| `invalid_nonce` | Security check failed | Reload page and try again |
| `rate_limit_exceeded` | Too many requests | Wait and try again |
| `server_error` | Unexpected server error | Contact support if persists |

### Logging

All AJAX errors logged to WordPress debug log when `WP_DEBUG` enabled:

```php
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    error_log( sprintf(
        'RenalInfo AJAX Error [%s]: %s',
        $error_code,
        $error_message
    ) );
}
```

---

## Testing

### Manual Testing Checklist

- [ ] Search autocomplete returns results in under 500ms
- [ ] Search autocomplete handles special characters (Sinhala, Tamil scripts)
- [ ] Search autocomplete respects audience filter
- [ ] Search autocomplete rate limiting works (11th request in 10s fails)
- [ ] Feedback submission prevents duplicates
- [ ] Feedback submission rate limiting works
- [ ] Feedback increments counters correctly
- [ ] Language selection redirects to correct translation
- [ ] Language selection shows fallback message when translation missing
- [ ] Language preference persists within session only (cleared on browser close)
- [ ] All endpoints verify nonces correctly
- [ ] All inputs sanitized and validated
- [ ] Error messages display in appropriate language

### Automated Testing

**PHPUnit tests** (examples):

```php
public function test_search_autocomplete_requires_nonce() {
    $_POST['action'] = 'renalinfo_search_autocomplete';
    $_POST['query'] = 'kidney';
    // Omit nonce
    
    $this->expectException( WPDieException::class );
    renalinfo_ajax_search_autocomplete();
}

public function test_search_autocomplete_requires_min_chars() {
    $_POST['nonce'] = wp_create_nonce( 'renalinfo_search' );
    $_POST['query'] = 'ab'; // Only 2 chars
    
    ob_start();
    renalinfo_ajax_search_autocomplete();
    $output = ob_get_clean();
    
    $result = json_decode( $output, true );
    $this->assertFalse( $result['success'] );
    $this->assertEquals( 'invalid_query', $result['data']['code'] );
}
```

---

## Performance Considerations

### Caching Strategy

- **Transients**: Cache search results for 5 minutes per query
- **Object Cache**: If available (Redis/Memcached), cache taxonomy queries
- **Database**: Indexed meta queries for `_audience` and `_article_template`

### Optimization

- Limit autocomplete results to 10 items
- Use `fields` parameter in WP_Query to fetch only needed data
- Debounce client-side: 300ms delay before sending autocomplete request
- Minify AJAX responses (WordPress handles automatically)

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-10-16 | Initial API contract definition |

---

## Next Steps

1. ✅ **Phase 1**: API contracts documented
2. **Phase 1 Next**: Create quickstart.md
3. **Phase 1 Next**: Update agent context

**Contracts Complete**: Ready for quickstart documentation
