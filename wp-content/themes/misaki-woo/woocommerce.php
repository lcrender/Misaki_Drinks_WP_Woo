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
else :
    ?>
    <main id="site-main" class="site-main site-main--woo" tabindex="-1">
        <?php woocommerce_content(); ?>
    </main>
    <?php
endif;

get_footer();
