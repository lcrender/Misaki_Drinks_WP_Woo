<?php
/**
 * Template Name: Misaki Contact Form
 * Página del formulario B2B Misaki Contact Form.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="site-main" class="site-main site-main--trade-contact misaki-trade-page" tabindex="-1">
    <div class="misaki-trade-page__inner">
        <?php misaki_woo_render_trade_contact_form(); ?>
    </div>
</main>
<?php
get_footer();
