<?php
/**
 * Plantilla de la página Cart.
 *
 * @package Misaki_Woo
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="site-main" class="site-main site-main--cart" tabindex="-1">
    <section class="misaki-cart home-dark-section" aria-label="<?php esc_attr_e('Cart', 'misaki-woo'); ?>">
        <div class="misaki-cart__inner">
            <h1 class="misaki-section-title misaki-cart__title"><?php esc_html_e('Cart', 'misaki-woo'); ?></h1>

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
