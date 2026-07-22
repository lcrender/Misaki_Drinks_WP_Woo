<?php
/**
 * Plugin Name: Misaki — Complianz cookie banner
 * Description: Forces the Complianz cookie banner on for EU transparency even when only essential cookies are used.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Complianz decides at plugins_loaded whether to enqueue the banner.
 * With only essential cookies it normally skips the banner; we still want
 * Accept / Manage preferences for GDPR clarity and future categories.
 */
add_filter('cmplz_site_needs_cookiewarning', '__return_true');

/**
 * Complianz builds cookie_path from home_url() and breaks when a port is present
 * (e.g. localhost:8080 → ":8080/"). Always use site root.
 */
add_filter('cmplz_cookie_path', static function (): string {
    return '/';
});
