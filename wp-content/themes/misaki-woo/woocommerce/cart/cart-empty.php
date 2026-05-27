<?php
/**
 * Carrito vacío — Misaki Woo.
 *
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

$return_url = apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'));
?>
<div class="misaki-cart__empty misaki-cart__panel">
    <?php do_action('woocommerce_cart_is_empty'); ?>

    <?php if ($return_url) : ?>
        <p class="return-to-shop">
            <a class="button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" href="<?php echo esc_url($return_url); ?>">
                <?php echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce'))); ?>
            </a>
        </p>
    <?php endif; ?>
</div>
