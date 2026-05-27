<?php
/**
 * Tarjeta de producto en el listado — Products.
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('shop-product-card', $product); ?>>
    <article class="shop-product-card__inner">
        <a class="shop-product-card__image-link" href="<?php echo esc_url($product->get_permalink()); ?>">
            <div class="shop-product-card__image">
                <?php echo misaki_woo_get_shop_product_image_html($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </a>

        <div class="shop-product-card__body">
            <h2 class="shop-product-card__title">
                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <?php echo esc_html($product->get_name()); ?>
                </a>
            </h2>

            <div class="shop-product-card__price">
                <?php woocommerce_template_loop_price(); ?>
            </div>

            <div class="shop-product-card__actions">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    </article>
</li>
