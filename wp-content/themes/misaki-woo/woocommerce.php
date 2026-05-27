<?php
/**
 * Plantilla base WooCommerce — Misaki Woo.
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

get_header();

if (function_exists('is_account_page') && is_account_page()) :
    ?>
    <main id="site-main" class="site-main site-main--account" tabindex="-1">
        <section class="misaki-account home-dark-section" aria-label="<?php esc_attr_e('My Account', 'misaki-woo'); ?>">
            <div class="misaki-account__inner">
                <h1 class="misaki-section-title misaki-account__title"><?php esc_html_e('My Account', 'misaki-woo'); ?></h1>

                <?php
                while (have_posts()) {
                    the_post();
                    the_content();
                }
                ?>
            </div>
        </section>
    </main>
    <?php
elseif (function_exists('is_product') && is_product()) :
    ?>
    <main id="site-main" class="site-main site-main--product" tabindex="-1">
        <?php
        do_action('woocommerce_before_main_content');

        while (have_posts()) {
            the_post();
            wc_get_template_part('content', 'single-product');
        }

        do_action('woocommerce_after_main_content');
        ?>
    </main>
    <?php
elseif (
    (function_exists('is_shop') && is_shop())
    || (function_exists('is_product_taxonomy') && is_product_taxonomy())
) :
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
else :
    ?>
    <main id="site-main" class="site-main site-main--woo" tabindex="-1">
        <?php woocommerce_content(); ?>
    </main>
    <?php
endif;

get_footer();
