<?php
/**
 * Galería de compra — solo imágenes de galería (sin foto principal).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return int[]
 */
function misaki_woo_get_purchase_gallery_ids(WC_Product $product): array
{
    $gallery_ids = array_values(array_filter(array_map('absint', $product->get_gallery_image_ids())));

    if (!empty($gallery_ids)) {
        return $gallery_ids;
    }

    $featured_id = (int) $product->get_image_id();

    return $featured_id > 0 ? [$featured_id] : [];
}

function misaki_woo_render_purchase_gallery(WC_Product $product): void
{
    $gallery_ids = misaki_woo_get_purchase_gallery_ids($product);

    if (empty($gallery_ids)) {
        echo '<div class="product-purchase-gallery product-purchase-gallery--empty">';
        echo '<div class="product-purchase-gallery__placeholder">';
        echo esc_html__('No hay imágenes disponibles.', 'misaki-woo');
        echo '</div></div>';
        return;
    }

    $main_id = $gallery_ids[0];
    $main_alt = trim(wp_strip_all_tags(get_post_meta($main_id, '_wp_attachment_image_alt', true))) ?: $product->get_name();
    ?>
    <div class="product-purchase-gallery" data-product-gallery>
        <figure class="product-purchase-gallery__main">
            <?php
            echo wp_get_attachment_image(
                $main_id,
                'large',
                false,
                [
                    'class'    => 'product-purchase-gallery__main-img',
                    'loading'  => 'eager',
                    'alt'      => $main_alt,
                    'data-gallery-main' => '',
                ]
            );
            ?>
        </figure>

        <?php if (count($gallery_ids) > 1) : ?>
            <ul class="product-purchase-gallery__thumbs" role="list">
                <?php foreach ($gallery_ids as $index => $attachment_id) : ?>
                    <?php
                    $thumb_alt = trim(wp_strip_all_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))) ?: $product->get_name();
                    $full_url  = wp_get_attachment_image_url($attachment_id, 'large') ?: wp_get_attachment_image_url($attachment_id, 'full');
                    ?>
                    <li class="product-purchase-gallery__thumb-item">
                        <button
                            type="button"
                            class="product-purchase-gallery__thumb<?php echo $index === 0 ? ' is-active' : ''; ?>"
                            data-gallery-thumb
                            data-full-url="<?php echo esc_url((string) $full_url); ?>"
                            data-full-alt="<?php echo esc_attr($thumb_alt); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('Ver imagen %d', 'misaki-woo'), $index + 1)); ?>"
                            aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        >
                            <?php
                            echo wp_get_attachment_image(
                                $attachment_id,
                                'woocommerce_thumbnail',
                                false,
                                ['alt' => $thumb_alt]
                            );
                            ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}
