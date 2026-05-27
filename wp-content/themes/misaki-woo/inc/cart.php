<?php
/**
 * Cart — plantilla, layout y ajustes.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Usa el carrito clásico (shortcode) para poder aplicar plantillas del tema.
 */
function misaki_woo_setup_cart_classic(): void
{
    if (get_option('misaki_woo_cart_classic_content')) {
        return;
    }

    $cart_id = function_exists('wc_get_page_id') ? wc_get_page_id('cart') : 0;

    if ($cart_id > 0) {
        wp_update_post([
            'ID'           => $cart_id,
            'post_content' => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
        ]);
    }

    update_option('misaki_woo_cart_classic_content', '1');
}

add_action('after_setup_theme', 'misaki_woo_setup_cart_classic', 25);

/**
 * Fuerza la plantilla del carrito.
 */
function misaki_woo_cart_page_template(string $template): string
{
    if (!function_exists('is_cart') || !is_cart()) {
        return $template;
    }

    $cart_template = locate_template('page-cart.php');

    return $cart_template ?: $template;
}

add_filter('template_include', 'misaki_woo_cart_page_template', 20);

/**
 * Oculta el título por defecto de WooCommerce en el carrito.
 */
function misaki_woo_cart_show_page_title(bool $show): bool
{
    if (function_exists('is_cart') && is_cart()) {
        return false;
    }

    return $show;
}

add_filter('woocommerce_show_page_title', 'misaki_woo_cart_show_page_title');

/**
 * "Return to shop" apunta a Products.
 */
function misaki_woo_cart_return_to_shop_url(string $url): string
{
    if (function_exists('misaki_woo_get_products_url')) {
        return misaki_woo_get_products_url();
    }

    return $url;
}

add_filter('woocommerce_return_to_shop_redirect', 'misaki_woo_cart_return_to_shop_url');

/**
 * Abre el layout principal del carrito.
 */
function misaki_woo_cart_layout_open(): void
{
    if (!function_exists('is_cart') || !is_cart() || !WC()->cart || WC()->cart->is_empty()) {
        return;
    }

    echo '<div class="misaki-cart__grid">';
    echo '<div class="misaki-cart__main misaki-cart__panel">';
}

add_action('woocommerce_before_cart', 'misaki_woo_cart_layout_open', 15);

/**
 * Cierra la tabla y abre el resumen lateral.
 */
function misaki_woo_cart_sidebar_open(): void
{
    if (!function_exists('is_cart') || !is_cart() || !WC()->cart || WC()->cart->is_empty()) {
        return;
    }

    echo '</div><aside class="misaki-cart__sidebar misaki-cart__panel">';
}

add_action('woocommerce_before_cart_collaterals', 'misaki_woo_cart_sidebar_open', 5);

/**
 * Cierra el layout principal del carrito.
 */
function misaki_woo_cart_layout_close(): void
{
    if (!function_exists('is_cart') || !is_cart() || !WC()->cart || WC()->cart->is_empty()) {
        return;
    }

    echo '</aside></div>';
}

add_action('woocommerce_after_cart', 'misaki_woo_cart_layout_close', 50);

/**
 * Oculta cross-sells en el carrito para un layout más limpio.
 */
function misaki_woo_cart_setup(): void
{
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
}

add_action('after_setup_theme', 'misaki_woo_cart_setup', 25);
