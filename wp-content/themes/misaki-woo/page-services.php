<?php
/**
 * Plantilla de la página Services.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/services-data.php';
require_once get_template_directory() . '/inc/services-admin.php';

get_header();

$page_id           = misaki_woo_get_services_page_id();
$services_title    = misaki_woo_get_services_title($page_id);
$services_items    = misaki_woo_get_services_items($page_id);
$services_bg_url   = esc_url(misaki_woo_get_services_hero_image_url($page_id));
$services_panel_url = esc_url(misaki_woo_get_services_panel_image_url($page_id));
?>
<main
    id="site-main"
    class="site-main site-main--services services-page"
    style="--services-bg-url: url('<?php echo $services_bg_url; ?>');"
    tabindex="-1"
>
    <section class="services-hero" aria-label="<?php echo esc_attr($services_title); ?>">
        <div
            class="services-panel"
            style="--services-panel-bg-url: url('<?php echo $services_panel_url; ?>');"
        >
            <div class="services-panel__inner">
                <h1 class="misaki-section-title services-panel__title"><?php echo esc_html($services_title); ?></h1>
                <ul class="services-panel__list">
                    <?php foreach ($services_items as $item) : ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
