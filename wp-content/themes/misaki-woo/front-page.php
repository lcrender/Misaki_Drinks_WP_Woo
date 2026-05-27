<?php
/**
 * Home principal del sitio.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/home-values-data.php';

get_header();

$page_id           = misaki_woo_get_home_page_id();
$we_are_bg_url          = esc_url(misaki_woo_get_home_we_are_bg_url($page_id));
$we_are_panel_id        = misaki_woo_get_home_we_are_panel_attachment_id($page_id);
$we_are_panel_alt       = esc_attr(misaki_woo_get_home_we_are_title($page_id));
$hero_bg_url       = esc_url(misaki_woo_get_home_hero_bg_url($page_id));
$hero_brand_url    = esc_url(misaki_woo_get_home_hero_brand_url($page_id));
$values_jump_links = misaki_woo_get_home_values_jump_links($page_id);
?>
<main id="site-main" class="home-main" tabindex="-1">
    <section class="home-hero" aria-label="<?php esc_attr_e('Inicio Misaki Drinks', 'misaki-woo'); ?>">
        <div
            class="home-hero__background"
            style="background-image: url('<?php echo $hero_bg_url; ?>');"
            aria-hidden="true"
        ></div>
        <div class="home-hero__content">
            <img
                class="home-hero__brand"
                src="<?php echo $hero_brand_url; ?>"
                alt="<?php echo esc_attr(get_bloginfo('name') ?: 'Misaki Drinks'); ?>"
            >
        </div>
    </section>

    <section class="home-dark-section" aria-label="<?php esc_attr_e('Contenido principal', 'misaki-woo'); ?>">
        <div class="home-products">
            <h2 class="misaki-section-title home-products__title"><?php echo esc_html(misaki_woo_get_home_products_title($page_id)); ?></h2>

            <p class="home-products__intro">
                <?php echo esc_html(misaki_woo_get_home_products_intro($page_id)); ?>
            </p>

            <div class="home-products__grid">
                <?php foreach (misaki_woo_get_home_products($page_id) as $product) : ?>
                    <?php
                    $product_url = !empty($product['url'])
                        ? misaki_woo_resolve_home_product_url($product['url'])
                        : '';
                    ?>
                    <article class="home-product-card">
                        <div class="home-product-card__image">
                            <?php if ($product_url) : ?>
                                <a class="home-product-card__media-link" href="<?php echo esc_url($product_url); ?>">
                            <?php endif; ?>
                            <?php if ($product['attachment_id']) : ?>
                                <?php
                                echo wp_get_attachment_image(
                                    $product['attachment_id'],
                                    'large',
                                    false,
                                    [
                                        'class'   => 'home-product-card__image-img',
                                        'loading' => 'lazy',
                                        'alt'     => $product['title'],
                                    ]
                                );
                                ?>
                            <?php endif; ?>
                            <?php if ($product_url) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <h3 class="home-product-card__title">
                            <?php if ($product_url) : ?>
                                <a class="home-product-card__title-link" href="<?php echo esc_url($product_url); ?>">
                                    <?php echo esc_html($product['title']); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html($product['title']); ?>
                            <?php endif; ?>
                        </h3>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section
        id="about"
        class="home-we-are-scene"
        data-we-are-scene
        aria-label="<?php esc_attr_e('We Are', 'misaki-woo'); ?>"
    >
        <div class="home-we-are-scene__track" data-we-are-track>
            <div class="home-we-are-scene__stage">
                <div class="home-we-are-scene__bg" data-we-are-bg>
                    <img
                        class="home-we-are-scene__bg-img"
                        src="<?php echo $we_are_bg_url; ?>"
                        alt=""
                        width="1920"
                        height="1080"
                        decoding="async"
                    >
                </div>

                <div class="home-we-are-scene__panel" data-we-are-panel>
                    <div class="home-we-are-scene__panel-inner home-we-are">
                        <div class="home-we-are__inner">
                            <div class="home-we-are__content">
                                <h2 class="home-we-are__title"><?php echo esc_html(misaki_woo_get_home_we_are_title($page_id)); ?></h2>
                                <div class="home-we-are__text">
                                    <?php foreach (misaki_woo_get_home_we_are_paragraphs($page_id) as $paragraph) : ?>
                                        <p><?php echo esc_html($paragraph); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="home-we-are__media">
                                <figure class="home-we-are__figure">
                                    <?php if ($we_are_panel_id) : ?>
                                        <?php
                                        echo wp_get_attachment_image(
                                            $we_are_panel_id,
                                            'large',
                                            false,
                                            [
                                                'class'   => 'home-we-are__image',
                                                'loading' => 'lazy',
                                                'alt'     => $we_are_panel_alt,
                                            ]
                                        );
                                        ?>
                                    <?php else : ?>
                                        <img
                                            class="home-we-are__image"
                                            src="<?php echo esc_url(misaki_woo_get_home_we_are_panel_url($page_id)); ?>"
                                            alt="<?php echo $we_are_panel_alt; ?>"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    <?php endif; ?>
                                </figure>
                            </div>
                        </div>

                        <nav
                            class="home-we-are__jump-nav"
                            aria-label="<?php esc_attr_e('Ir a secciones', 'misaki-woo'); ?>"
                        >
                            <?php foreach ($values_jump_links as $jump_link) : ?>
                                <a
                                    class="home-we-are__jump-link"
                                    href="#<?php echo esc_attr($jump_link['id']); ?>"
                                    data-values-jump
                                ><?php echo esc_html($jump_link['label']); ?></a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/home', 'values'); ?>
</main>
<?php
get_footer();
