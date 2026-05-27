<?php
/**
 * Our Values — carga home-data y home-admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/home-data.php';

if (!function_exists('misaki_woo_get_home_page_id')) {
    require_once get_template_directory() . '/inc/home-admin.php';
}
