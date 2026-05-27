<?php
/**
 * Plantilla de la página Checkout.
 *
 * @package Misaki_Woo
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="site-main" class="site-main site-main--checkout" tabindex="-1">
    <section class="misaki-checkout home-dark-section" aria-label="<?php esc_attr_e('Checkout', 'misaki-woo'); ?>">
        <div class="misaki-checkout__inner">
            <h1 class="misaki-section-title misaki-checkout__title"><?php esc_html_e('Checkout', 'misaki-woo'); ?></h1>

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
get_footer();
