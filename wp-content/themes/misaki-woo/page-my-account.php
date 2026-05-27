<?php
/**
 * Plantilla de la página My Account (slug: my-account).
 *
 * @package Misaki_Woo
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
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
get_footer();
