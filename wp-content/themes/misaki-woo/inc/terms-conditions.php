<?php
/**
 * Terms & Conditions — página legal ecommerce.
 */

if (!defined('ABSPATH')) {
    exit;
}

const MISAKI_TERMS_PAGE_OPTION = 'misaki_terms_page_id';

/**
 * Contenido HTML de Terms & Conditions.
 */
function misaki_woo_get_terms_conditions_content(): string
{
    $path = get_template_directory() . '/inc/terms-conditions-content.html';

    if (!file_exists($path)) {
        return '';
    }

    return (string) file_get_contents($path);
}

/**
 * Crea o sincroniza la página Terms & Conditions.
 */
function misaki_woo_ensure_terms_conditions_page(): void
{
    $content  = misaki_woo_get_terms_conditions_content();
    $existing = get_page_by_path('terms-conditions');

    if ($existing) {
        update_option(MISAKI_TERMS_PAGE_OPTION, (int) $existing->ID);

        if ($content !== '' && $existing->post_content !== $content) {
            wp_update_post([
                'ID'           => (int) $existing->ID,
                'post_content' => $content,
            ]);
        }

        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Terms & Conditions',
        'post_name'    => 'terms-conditions',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => $content,
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_option(MISAKI_TERMS_PAGE_OPTION, (int) $page_id);
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_terms_conditions_page', 40);

/**
 * URL de Terms & Conditions.
 */
function misaki_woo_get_terms_conditions_url(): string
{
    $page_id = (int) get_option(MISAKI_TERMS_PAGE_OPTION, 0);

    if ($page_id > 0) {
        $url = get_permalink($page_id);

        if ($url) {
            return $url;
        }
    }

    $page = get_page_by_path('terms-conditions');

    return $page ? (string) get_permalink($page) : home_url('/terms-conditions/');
}
