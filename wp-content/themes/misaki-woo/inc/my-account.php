<?php
/**
 * My Account — estilos y ajustes.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Oculta el título por defecto de WooCommerce en la cuenta.
 */
function misaki_woo_account_show_page_title(bool $show): bool
{
    if (function_exists('is_account_page') && is_account_page()) {
        return false;
    }

    return $show;
}

add_filter('woocommerce_show_page_title', 'misaki_woo_account_show_page_title');

/**
 * Fuerza la plantilla de cuenta aunque el slug de la página cambie.
 */
function misaki_woo_account_page_template(string $template): string
{
    if (!function_exists('is_account_page') || !is_account_page()) {
        return $template;
    }

    $account_template = locate_template('page-my-account.php');

    return $account_template ?: $template;
}

add_filter('template_include', 'misaki_woo_account_page_template', 20);

/**
 * Envuelve login/registro (invitado) — WC usa form-login.php, no my-account.php.
 */
function misaki_woo_account_guest_wrapper_open(): void
{
    if (!function_exists('is_account_page') || !is_account_page() || is_user_logged_in()) {
        return;
    }

    echo '<div class="misaki-account__guest">';
}

function misaki_woo_account_guest_wrapper_close(): void
{
    if (!function_exists('is_account_page') || !is_account_page() || is_user_logged_in()) {
        return;
    }

    echo '</div>';
}

add_action('woocommerce_before_customer_login_form', 'misaki_woo_account_guest_wrapper_open', 5);
add_action('woocommerce_after_customer_login_form', 'misaki_woo_account_guest_wrapper_close', 50);
