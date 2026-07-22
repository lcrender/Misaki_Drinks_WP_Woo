<?php
/**
 * Funciones base del tema Misaki Woo.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/services-admin.php';
require_once get_template_directory() . '/inc/contact-admin.php';
require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/misaki-contact-form.php';
require_once get_template_directory() . '/inc/terms-conditions.php';
require_once get_template_directory() . '/inc/home-admin.php';
require_once get_template_directory() . '/inc/product-gallery.php';
require_once get_template_directory() . '/inc/shop.php';
require_once get_template_directory() . '/inc/my-account.php';
require_once get_template_directory() . '/inc/cart.php';
require_once get_template_directory() . '/inc/checkout.php';
require_once get_template_directory() . '/inc/age-gate.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('site-icon');

    register_nav_menus([
        'primary' => __('Menú principal (lateral)', 'misaki-woo'),
    ]);
});

/**
 * Ajustes de WooCommerce para el layout de producto personalizado.
 */
function misaki_woo_setup_woocommerce(): void
{
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
}

add_action('after_setup_theme', 'misaki_woo_setup_woocommerce', 20);

/**
 * Usar plantillas PHP del tema en lugar de block templates de WooCommerce.
 */
function misaki_woo_disable_wc_block_templates(bool $has_template, string $template_name): bool
{
    if (in_array($template_name, ['single-product', 'archive-product'], true)) {
        return false;
    }

    return $has_template;
}

add_filter('woocommerce_has_block_template', 'misaki_woo_disable_wc_block_templates', 10, 2);

/**
 * Desactiva zoom/lightbox de WooCommerce en la página de producto.
 */
function misaki_woo_dequeue_product_gallery_assets(): void
{
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    wp_dequeue_script('zoom');
    wp_dequeue_script('photoswipe');
    wp_dequeue_script('photoswipe-ui-default');
    wp_dequeue_style('photoswipe');
    wp_dequeue_style('photoswipe-default-skin');
}

add_action('wp_enqueue_scripts', 'misaki_woo_dequeue_product_gallery_assets', 100);

/**
 * URL del favicon (Media Library o ruta por defecto en uploads).
 */
function misaki_woo_get_favicon_url(): string
{
    $attachment_id = misaki_woo_get_attachment_id_by_filename('favicon.png');

    if ($attachment_id) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return content_url('uploads/2026/05/favicon.png');
}

/**
 * Registra favicon.png como icono del sitio en WordPress.
 */
function misaki_woo_setup_favicon(): void
{
    $attachment_id = misaki_woo_get_attachment_id_by_filename('favicon.png');

    if (!$attachment_id) {
        return;
    }

    if ((int) get_option('site_icon') === $attachment_id) {
        return;
    }

    update_option('site_icon', $attachment_id);
}

add_action('after_setup_theme', 'misaki_woo_setup_favicon', 20);

add_filter('get_site_icon_url', static function (): string {
    return misaki_woo_get_favicon_url();
});

add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    $css_path = $theme_dir . '/assets/css/main.css';
    wp_enqueue_style(
        'misaki-woo-main',
        $theme_uri . '/assets/css/main.css',
        [],
        file_exists($css_path) ? (string) filemtime($css_path) : null
    );

    $cookies_css = $theme_dir . '/assets/css/cookies.css';
    wp_enqueue_style(
        'misaki-woo-cookies',
        $theme_uri . '/assets/css/cookies.css',
        ['misaki-woo-main'],
        file_exists($cookies_css) ? (string) filemtime($cookies_css) : null
    );

    $js_path = $theme_dir . '/assets/js/header.js';
    wp_enqueue_script(
        'misaki-woo-header',
        $theme_uri . '/assets/js/header.js',
        [],
        file_exists($js_path) ? (string) filemtime($js_path) : null,
        true
    );

    if (is_front_page()) {
        $we_are_js = $theme_dir . '/assets/js/home-we-are.js';
        wp_enqueue_script(
            'misaki-woo-home-we-are',
            $theme_uri . '/assets/js/home-we-are.js',
            [],
            file_exists($we_are_js) ? (string) filemtime($we_are_js) : null,
            true
        );

        $values_nav_js = $theme_dir . '/assets/js/home-values-nav.js';
        wp_enqueue_script(
            'misaki-woo-home-values-nav',
            $theme_uri . '/assets/js/home-values-nav.js',
            [],
            file_exists($values_nav_js) ? (string) filemtime($values_nav_js) : null,
            true
        );
    }

    if (is_page('contact') || is_page_template('page-contact.php')) {
        $contact_css = $theme_dir . '/assets/css/contact.css';
        wp_enqueue_style(
            'misaki-woo-contact',
            $theme_uri . '/assets/css/contact.css',
            ['misaki-woo-main'],
            file_exists($contact_css) ? (string) filemtime($contact_css) : null
        );

        $section_bg = esc_url(misaki_woo_get_contact_distributors_bg_url());

        wp_add_inline_style(
            'misaki-woo-contact',
            ".contact-section--intro{background-image:url('{$section_bg}');}"
        );
    }

    if (is_page('services') || is_page_template('page-services.php')) {
        $services_css = $theme_dir . '/assets/css/services.css';
        wp_enqueue_style(
            'misaki-woo-services',
            $theme_uri . '/assets/css/services.css',
            ['misaki-woo-main'],
            file_exists($services_css) ? (string) filemtime($services_css) : null
        );
    }

    if (function_exists('is_checkout') && is_checkout()) {
        $checkout_css = $theme_dir . '/assets/css/checkout.css';
        wp_enqueue_style(
            'misaki-woo-checkout',
            $theme_uri . '/assets/css/checkout.css',
            ['misaki-woo-main'],
            file_exists($checkout_css) ? (string) filemtime($checkout_css) : null
        );
    }

    if (function_exists('is_cart') && is_cart()) {
        $cart_css = $theme_dir . '/assets/css/cart.css';
        wp_enqueue_style(
            'misaki-woo-cart',
            $theme_uri . '/assets/css/cart.css',
            ['misaki-woo-main'],
            file_exists($cart_css) ? (string) filemtime($cart_css) : null
        );
    }

    if (function_exists('is_account_page') && is_account_page()) {
        $account_css = $theme_dir . '/assets/css/my-account.css';
        wp_enqueue_style(
            'misaki-woo-my-account',
            $theme_uri . '/assets/css/my-account.css',
            ['misaki-woo-main'],
            file_exists($account_css) ? (string) filemtime($account_css) : null
        );
    }

    if ((function_exists('is_shop') && is_shop()) || (function_exists('is_product_taxonomy') && is_product_taxonomy())) {
        $shop_css = $theme_dir . '/assets/css/shop.css';
        wp_enqueue_style(
            'misaki-woo-shop',
            $theme_uri . '/assets/css/shop.css',
            ['misaki-woo-main'],
            file_exists($shop_css) ? (string) filemtime($shop_css) : null
        );
    }

    if (function_exists('is_product') && is_product()) {
        $product_css = $theme_dir . '/assets/css/product.css';
        wp_enqueue_style(
            'misaki-woo-product',
            $theme_uri . '/assets/css/product.css',
            ['misaki-woo-main'],
            file_exists($product_css) ? (string) filemtime($product_css) : null
        );

        $quantity_js = $theme_dir . '/assets/js/product-quantity.js';
        wp_enqueue_script(
            'misaki-woo-product-quantity',
            $theme_uri . '/assets/js/product-quantity.js',
            [],
            file_exists($quantity_js) ? (string) filemtime($quantity_js) : null,
            true
        );

        $gallery_js = $theme_dir . '/assets/js/product-gallery.js';
        wp_enqueue_script(
            'misaki-woo-product-gallery',
            $theme_uri . '/assets/js/product-gallery.js',
            ['jquery'],
            file_exists($gallery_js) ? (string) filemtime($gallery_js) : null,
            true
        );
    }
});

/**
 * URL de un archivo en uploads (Media Library o ruta por defecto).
 */
function misaki_woo_get_upload_asset_url(string $filename): string
{
    $attachment_id = misaki_woo_get_attachment_id_by_filename($filename);

    if ($attachment_id) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return content_url('uploads/2026/05/' . ltrim($filename, '/'));
}

/**
 * Convierte un teléfono visible en enlace tel:.
 */
function misaki_woo_phone_href(string $phone): string
{
    $normalized = preg_replace('/[^\d+]/', '', $phone);

    return $normalized ? 'tel:' . $normalized : '';
}

/**
 * URL del ancla About (sección We Are en la home).
 */
function misaki_woo_about_url(): string
{
    return home_url('/#about');
}

/**
 * Enlaces del menú About → ancla We Are en la home.
 *
 * @param array<int, WP_Post> $items
 * @return array<int, WP_Post>
 */
function misaki_woo_filter_about_menu_link(array $items): array
{
    $about_paths = [
        trailingslashit(home_url('/about')),
        trailingslashit(home_url('/about/')),
    ];

    foreach ($items as $item) {
        $title_match = strtolower(trim($item->title)) === 'about';
        $url_match   = in_array(trailingslashit(untrailingslashit($item->url)), $about_paths, true);

        if ($title_match || $url_match) {
            $item->url = misaki_woo_about_url();
        }
    }

    return $items;
}

add_filter('wp_nav_menu_objects', 'misaki_woo_filter_about_menu_link');

/**
 * Menú principal por defecto.
 */
function misaki_woo_primary_menu_fallback(): void
{
    $shop_url = function_exists('misaki_woo_get_products_url') ? misaki_woo_get_products_url() : home_url('/products/');

    $links = [
        ['label' => __('Home', 'misaki-woo'), 'url' => home_url('/')],
        ['label' => __('About', 'misaki-woo'), 'url' => misaki_woo_about_url()],
        ['label' => __('Products', 'misaki-woo'), 'url' => $shop_url],
        ['label' => __('Services', 'misaki-woo'), 'url' => home_url('/services/')],
        ['label' => __('Contact', 'misaki-woo'), 'url' => home_url('/contact/')],
    ];

    echo '<ul class="site-drawer__menu">';
    foreach ($links as $link) {
        printf(
            '<li><a href="%s">%s</a></li>',
            esc_url($link['url']),
            esc_html($link['label'])
        );
    }
    echo '</ul>';
}

/**
 * Crea la página Contact si no existe.
 */
function misaki_woo_ensure_contact_page(): void
{
    $existing = get_page_by_path('contact');

    if ($existing) {
        update_option('misaki_contact_page_id', (int) $existing->ID);

        if (get_page_template_slug($existing->ID) !== 'page-contact.php') {
            update_post_meta($existing->ID, '_wp_page_template', 'page-contact.php');
        }

        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Contact',
        'post_name'    => 'contact',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_option('misaki_contact_page_id', (int) $page_id);
        update_post_meta($page_id, '_wp_page_template', 'page-contact.php');
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_contact_page');

/**
 * Crea la página Home y la define como portada.
 */
function misaki_woo_ensure_home_page(): void
{
    $existing = get_page_by_path('home');

    if (!$existing) {
        $front_id = (int) get_option('page_on_front');

        if ($front_id > 0) {
            $existing = get_post($front_id);
        }
    }

    if ($existing && !is_wp_error($existing)) {
        $page_id = (int) $existing->ID;
        update_option('misaki_home_page_id', $page_id);
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);

        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Home',
        'post_name'    => 'home',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_option('misaki_home_page_id', (int) $page_id);
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $page_id);
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_home_page');

/**
 * Crea la página Services si no existe.
 */
function misaki_woo_ensure_services_page(): void
{
    $existing = get_page_by_path('services');

    if ($existing) {
        update_option('misaki_services_page_id', (int) $existing->ID);

        if (get_page_template_slug($existing->ID) !== 'page-services.php') {
            update_post_meta($existing->ID, '_wp_page_template', 'page-services.php');
        }

        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Services',
        'post_name'    => 'services',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_option('misaki_services_page_id', (int) $page_id);
        update_post_meta($page_id, '_wp_page_template', 'page-services.php');
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_services_page');

/**
 * Obtiene el ID de un attachment por nombre de archivo.
 */
function misaki_woo_get_attachment_id_by_filename(string $filename): int
{
    static $cache = [];

    if (isset($cache[$filename])) {
        return $cache[$filename];
    }

    global $wpdb;

    $attachment_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like($filename)
        )
    );

    $cache[$filename] = $attachment_id ? (int) $attachment_id : 0;

    return $cache[$filename];
}

