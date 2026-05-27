<?php
/**
 * Archivo de productos — Products.
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

get_header();
?>
<main id="site-main" class="site-main site-main--shop" tabindex="-1">
    <section class="shop-products home-dark-section" aria-label="<?php esc_attr_e('Products', 'misaki-woo'); ?>">
        <div class="shop-products__inner">
            <h1 class="misaki-section-title shop-products__title"><?php esc_html_e('Products', 'misaki-woo'); ?></h1>

            <?php do_action('woocommerce_before_main_content'); ?>

            <?php if (woocommerce_product_loop()) : ?>
                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php
                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();
                        do_action('woocommerce_shop_loop');
                        wc_get_template_part('content', 'product');
                    }
                }
                ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>
            <?php else : ?>
                <?php do_action('woocommerce_no_products_found'); ?>
            <?php endif; ?>

            <?php do_action('woocommerce_after_main_content'); ?>
        </div>
    </section>
</main>
<?php
get_footer();
