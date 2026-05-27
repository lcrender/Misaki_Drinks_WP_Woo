<?php
/**
 * Plantilla de producto individual — Misaki Woo.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
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
get_footer();
