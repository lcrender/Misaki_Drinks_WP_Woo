<?php
/**
 * Sección Our Values — home.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/home-values-data.php';

$page_id = misaki_woo_get_home_page_id();
$blocks  = misaki_woo_get_home_values_blocks($page_id);
?>
<section class="home-values" aria-label="<?php esc_attr_e('Our Values', 'misaki-woo'); ?>">
    <div class="home-values__inner">
        <div class="home-values__grid">
            <?php foreach ($blocks as $block) : ?>
                <?php if ($block['type'] === 'image') : ?>
                    <div class="home-values__cell home-values__cell--media">
                        <?php
                        $image_url = '';

                        if (!empty($block['image_id'])) {
                            $image_url = wp_get_attachment_image_url((int) $block['image_id'], 'full') ?: '';
                        }

                        if ($image_url === '' && !empty($block['image'])) {
                            $image_url = misaki_woo_get_upload_asset_url($block['image']);
                        }
                        ?>
                        <?php if ($image_url) : ?>
                            <img
                                class="home-values__image"
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($block['alt'] ?? ''); ?>"
                                loading="lazy"
                                decoding="async"
                            >
                        <?php else : ?>
                            <div class="home-values__image-placeholder" aria-hidden="true"></div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="home-values__cell home-values__cell--text">
                        <div class="home-values__text">
                            <?php foreach ($block['sections'] as $section) : ?>
                                <?php if (!empty($section['heading'])) : ?>
                                    <?php
                                    $level   = !empty($section['level']) && (int) $section['level'] === 3 ? 3 : 2;
                                    $tag     = 'h' . $level;
                                    $id_attr = !empty($section['anchor'])
                                        ? sprintf(' id="%s"', esc_attr($section['anchor']))
                                        : '';
                                    printf(
                                        '<%1$s%4$s class="home-values__heading home-values__heading--h%2$d">%3$s</%1$s>',
                                        $tag,
                                        $level,
                                        esc_html($section['heading']),
                                        $id_attr
                                    );
                                    ?>
                                <?php endif; ?>

                                <?php if (!empty($section['paragraphs'])) : ?>
                                    <?php foreach ($section['paragraphs'] as $paragraph) : ?>
                                        <p><?php echo esc_html($paragraph); ?></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($section['list'])) : ?>
                                    <ul class="home-values__list">
                                        <?php foreach ($section['list'] as $item) : ?>
                                            <li><?php echo esc_html($item); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
