<?php
/**
 * Cabecera del sitio (enfoque móvil).
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-main"><?php esc_html_e('Ir al contenido', 'misaki-woo'); ?></a>

<header class="site-header" role="banner">
    <div class="site-header__bar">
        <div class="site-header__start">
            <button type="button" class="site-header__menu-toggle" aria-expanded="false" aria-controls="site-drawer" aria-label="<?php esc_attr_e('Abrir menú', 'misaki-woo'); ?>">
                <span class="site-header__menu-bars" aria-hidden="true">
                    <span class="site-header__menu-line"></span>
                    <span class="site-header__menu-line"></span>
                    <span class="site-header__menu-line"></span>
                </span>
            </button>
        </div>
        <a class="site-header__logo" href="<?php echo esc_url(home_url('/')); ?>">
            <img
                src="<?php echo esc_url(content_url('uploads/2026/05/logo-misaki-drinks.png')); ?>"
                alt="<?php echo esc_attr(get_bloginfo('name') ?: 'Misaki Drinks'); ?>"
                class="site-header__logo-image"
            >
        </a>
        <div class="site-header__actions">
            <?php
            $account_url = function_exists('wc_get_page_permalink')
                ? wc_get_page_permalink('myaccount')
                : '';
            if (!$account_url) {
                $account_url = wp_login_url();
            }
            $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/');
            $cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
            ?>
            <a class="site-header__icon site-header__icon--account" href="<?php echo esc_url($account_url); ?>" aria-label="<?php esc_attr_e('Mi cuenta', 'misaki-woo'); ?>">
                <span class="site-header__icon-svg" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4Z" fill="currentColor"/></svg>
                </span>
            </a>
            <?php
            $cart_display = (int) min(99, $cart_count);
            $cart_aria     = sprintf(
                /* translators: %d: number of items in the shopping cart */
                _n('Carrito de compras, %d artículo', 'Carrito de compras, %d artículos', $cart_count, 'misaki-woo'),
                $cart_count
            );
            ?>
            <a class="site-header__icon site-header__icon--cart" href="<?php echo esc_url($cart_url); ?>" aria-label="<?php echo esc_attr($cart_aria); ?>">
                <span class="site-header__icon-svg" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2Zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2ZM7.2 14h9.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0 0 21.05 5H6.21L5.27 3H2v2h2l3.6 7.59-1.35 2.44C5.52 15.37 6.48 17 8 17h12v-2H8.42a.25.25 0 0 1-.22-.37L8.13 14H7.2Z" fill="currentColor"/></svg>
                </span>
                <span class="site-header__cart-count" aria-hidden="true"><?php echo esc_html((string) $cart_display); ?></span>
            </a>
        </div>
    </div>

    <div class="site-drawer__overlay" id="site-drawer-overlay" aria-hidden="true"></div>
    <aside class="site-drawer" id="site-drawer" aria-hidden="true" aria-label="<?php esc_attr_e('Menú principal', 'misaki-woo'); ?>">
        <div class="site-drawer__head">
            <span class="site-drawer__title"><?php esc_html_e('Menú', 'misaki-woo'); ?></span>
            <button type="button" class="site-drawer__close" aria-label="<?php esc_attr_e('Cerrar menú', 'misaki-woo'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
        <nav class="site-drawer__nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'site-drawer__menu',
                'fallback_cb'    => 'misaki_woo_primary_menu_fallback',
            ]);
            ?>
        </nav>
    </aside>
</header>
