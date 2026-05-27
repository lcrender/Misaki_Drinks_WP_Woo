<?php
/**
 * Tienda WooCommerce — Products.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajustes del listado de productos (shop / archivo).
 */
function misaki_woo_setup_shop(): void
{
    remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

add_action('after_setup_theme', 'misaki_woo_setup_shop', 25);

/**
 * Tamaño de imagen para tarjetas del catálogo (sin recorte).
 */
function misaki_woo_register_shop_image_size(): void
{
    add_image_size('misaki-shop-card', 600, 0, false);
}

add_action('after_setup_theme', 'misaki_woo_register_shop_image_size', 25);

/**
 * Imagen de producto en el listado — proporcional, sin thumb recortado.
 */
function misaki_woo_get_shop_product_image_html(WC_Product $product): string
{
    $image_id = (int) $product->get_image_id();
    $alt      = trim(wp_strip_all_tags(get_post_meta($image_id, '_wp_attachment_image_alt', true))) ?: $product->get_name();

    if ($image_id <= 0) {
        return wc_placeholder_img(
            'woocommerce_single',
            [
                'class' => 'shop-product-card__image-img',
                'alt'   => esc_attr($alt),
            ]
        );
    }

    // Sin woocommerce_thumbnail (suele ir recortada). Priorizar tamaños proporcionales.
    $sizes = ['woocommerce_single', 'large', 'full'];

    if (wp_get_attachment_image_src($image_id, 'misaki-shop-card')) {
        array_unshift($sizes, 'misaki-shop-card');
    }

    foreach ($sizes as $size) {
        $src = wp_get_attachment_image_src($image_id, $size);

        if ($src) {
            return wp_get_attachment_image(
                $image_id,
                $size,
                false,
                [
                    'class'   => 'shop-product-card__image-img',
                    'loading' => 'lazy',
                    'alt'     => $alt,
                ]
            );
        }
    }

    return '';
}

/**
 * Título "Products" en la tienda.
 */
function misaki_woo_shop_page_title(string $page_title): string
{
    if (function_exists('is_shop') && is_shop() && !is_search()) {
        return __('Products', 'misaki-woo');
    }

    return $page_title;
}

add_filter('woocommerce_page_title', 'misaki_woo_shop_page_title');

/**
 * Cuatro productos por fila en desktop.
 */
function misaki_woo_shop_loop_columns(): int
{
    return 4;
}

add_filter('loop_shop_columns', 'misaki_woo_shop_loop_columns');

/**
 * Clases del botón en el loop.
 *
 * @param array<string, mixed> $args
 * @return array<string, mixed>
 */
function misaki_woo_shop_loop_add_to_cart_args(array $args, WC_Product $product): array
{
    $args['class'] = 'button shop-product-card__add-to-cart';

    return $args;
}

add_filter('woocommerce_loop_add_to_cart_args', 'misaki_woo_shop_loop_add_to_cart_args', 10, 2);

/**
 * Renombra la página Shop a Products y slug /products/.
 */
function misaki_woo_ensure_products_shop_page(): void
{
    if (!function_exists('wc_get_page_id')) {
        return;
    }

    $shop_id = wc_get_page_id('shop');

    if ($shop_id <= 0) {
        return;
    }

    $shop = get_post($shop_id);

    if (!$shop instanceof WP_Post) {
        return;
    }

    $needs_update = $shop->post_title !== 'Products' || $shop->post_name !== 'products';

    if ($needs_update) {
        wp_update_post([
            'ID'         => $shop_id,
            'post_title' => 'Products',
            'post_name'  => 'products',
        ]);

        delete_option('misaki_shop_rewrite_flushed');
    }

    if (!get_option('misaki_shop_rewrite_flushed')) {
        flush_rewrite_rules(false);
        update_option('misaki_shop_rewrite_flushed', true);
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_products_shop_page', 30);

/**
 * Redirige /shop/ a /products/ si quedó la URL antigua.
 */
function misaki_woo_redirect_legacy_shop_url(): void
{
    if (is_admin()) {
        return;
    }

    $path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    if (!is_string($path)) {
        return;
    }

    $path = trailingslashit($path);

    if ($path === '/shop/' || $path === trailingslashit(parse_url(home_url('/shop/'), PHP_URL_PATH) ?: '/shop/')) {
        wp_safe_redirect(misaki_woo_get_products_url(), 301);
        exit;
    }
}

add_action('template_redirect', 'misaki_woo_redirect_legacy_shop_url');

/**
 * URL de la tienda (/products/).
 */
function misaki_woo_get_products_url(): string
{
    if (function_exists('wc_get_page_id')) {
        $shop_id = wc_get_page_id('shop');

        if ($shop_id > 0) {
            $url = get_permalink($shop_id);

            if ($url) {
                return $url;
            }
        }
    }

    return home_url('/products/');
}
