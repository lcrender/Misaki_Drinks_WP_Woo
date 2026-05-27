<?php
/**
 * Contenido de producto — layout centrado.
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    return;
}

if (!$product instanceof WC_Product) {
    return;
}

$image_id   = $product->get_image_id();
$image_html = '';

if ($image_id) {
    $image_html = wp_get_attachment_image(
        $image_id,
        'large',
        false,
        [
            'class'   => 'product-parallax-scene__img',
            'loading' => 'eager',
            'alt'     => trim(wp_strip_all_tags(get_post_meta($image_id, '_wp_attachment_image_alt', true))) ?: $product->get_name(),
        ]
    );
}

$description       = $product->get_description();
$short_description = $product->get_short_description();
?>
<article id="product-<?php the_ID(); ?>" <?php wc_product_class('product-parallax-scene', $product); ?>>
    <div class="product-parallax-scene__inner">
        <header class="product-parallax-scene__head">
            <h1 class="product-parallax-scene__title">
                <?php echo esc_html($product->get_name()); ?>
            </h1>
        </header>

        <?php if ($image_html) : ?>
            <figure class="product-parallax-scene__media">
                <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </figure>
        <?php endif; ?>

        <?php if ($description) : ?>
            <div class="product-parallax-scene__body product-parallax-scene__body--description">
                <div class="product-parallax-scene__richtext">
                    <?php echo apply_filters('the_content', $description); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($short_description) : ?>
            <div class="product-parallax-scene__body product-parallax-scene__body--short">
                <div class="product-parallax-scene__richtext product-parallax-scene__richtext--short">
                    <?php echo apply_filters('woocommerce_short_description', $short_description); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <section class="product-purchase" aria-label="<?php esc_attr_e('Comprar producto', 'misaki-woo'); ?>">
        <div class="product-purchase__inner">
            <div class="product-purchase__gallery">
                <?php misaki_woo_render_purchase_gallery($product); ?>
            </div>

            <div class="product-purchase__summary">
                <div class="product-purchase__notices">
                    <?php woocommerce_output_all_notices(); ?>
                </div>

                <h2 class="product-purchase__title"><?php echo esc_html($product->get_name()); ?></h2>

                <div class="product-purchase__price">
                    <?php woocommerce_template_single_price(); ?>
                </div>

                <p class="product-purchase__legal">
                    <?php esc_html_e('Consumption of alcohol by persons under 21 is prohibited by law.', 'misaki-woo'); ?>
                </p>

                <div class="product-purchase__cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>

                <div class="product-purchase__policies">
                    <div class="product-purchase__policy">
                        <h3 class="product-purchase__policy-title"><?php esc_html_e('SHIPPING', 'misaki-woo'); ?></h3>
                        <p class="product-purchase__policy-text">
                            <?php esc_html_e('Please note that we may not be able to deliver on your specified date to certain areas, including remote islands. If you contact us outside of business hours to request changes to your order, we may not be able to deliver on the specified date.', 'misaki-woo'); ?>
                        </p>
                    </div>

                    <div class="product-purchase__policy">
                        <h3 class="product-purchase__policy-title"><?php esc_html_e('RETURNS & EXCHANGES', 'misaki-woo'); ?></h3>
                        <p class="product-purchase__policy-text">
                            <?php esc_html_e('If there are any issues such as leakage or damage, we will accept returns or exchanges within 7 days of delivery. Please check your item as soon as it arrives to ensure there are no defects.', 'misaki-woo'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
<?php
do_action('woocommerce_after_single_product');
