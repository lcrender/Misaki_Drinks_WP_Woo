<?php
/**
 * Campos editables de la homepage (admin de WordPress).
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/home-data.php';

const MISAKI_HOME_PAGE_OPTION         = 'misaki_home_page_id';
const MISAKI_HOME_META_HERO_BG        = '_misaki_home_hero_bg_id';
const MISAKI_HOME_META_HERO_BRAND     = '_misaki_home_hero_brand_id';
const MISAKI_HOME_META_PRODUCTS_TITLE = '_misaki_home_products_title';
const MISAKI_HOME_META_PRODUCTS_INTRO = '_misaki_home_products_intro';
const MISAKI_HOME_META_PRODUCTS       = '_misaki_home_products';
const MISAKI_HOME_META_WE_ARE_TITLE   = '_misaki_home_we_are_title';
const MISAKI_HOME_META_WE_ARE_TEXT    = '_misaki_home_we_are_text';
const MISAKI_HOME_META_WE_ARE_BG      = '_misaki_home_we_are_bg_id';
const MISAKI_HOME_META_WE_ARE_PANEL   = '_misaki_home_we_are_panel_id';
const MISAKI_HOME_META_VALUES_FLAT    = '_misaki_home_values_flat';
const MISAKI_HOME_META_VALUES_IMAGES  = '_misaki_home_values_images';

function misaki_woo_get_home_page_id(): int
{
    static $page_id = null;

    if ($page_id !== null) {
        return $page_id;
    }

    $front_id = (int) get_option('page_on_front');

    if ($front_id > 0 && get_post_status($front_id)) {
        $page_id = $front_id;
        update_option(MISAKI_HOME_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $stored_id = (int) get_option(MISAKI_HOME_PAGE_OPTION);

    if ($stored_id > 0 && get_post_status($stored_id)) {
        $page_id = $stored_id;

        return $page_id;
    }

    $page = get_page_by_path('home');

    if ($page) {
        $page_id = (int) $page->ID;
        update_option(MISAKI_HOME_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $page_id = 0;

    return $page_id;
}

function misaki_woo_is_home_page(int $post_id): bool
{
    if ($post_id <= 0) {
        return false;
    }

    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'page') {
        return false;
    }

    if ((int) get_option('page_on_front') === $post_id) {
        return true;
    }

    $stored_id = (int) get_option(MISAKI_HOME_PAGE_OPTION);

    if ($stored_id > 0 && $post_id === $stored_id) {
        return true;
    }

    return in_array($post->post_name, ['home', 'inicio'], true);
}

function misaki_woo_home_sync_page(): void
{
    $page_id = misaki_woo_get_home_page_id();

    if (!$page_id) {
        return;
    }

    if ((int) get_option('page_on_front') !== $page_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
    }
}

add_action('admin_init', 'misaki_woo_home_sync_page');

function misaki_woo_home_image_url(int $attachment_id, string $fallback_filename): string
{
    if ($attachment_id > 0) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return misaki_woo_get_upload_asset_url($fallback_filename);
}

function misaki_woo_get_home_hero_bg_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_home_hero_bg_filename());
    }

    return misaki_woo_home_image_url(
        (int) get_post_meta($page_id, MISAKI_HOME_META_HERO_BG, true),
        misaki_woo_home_hero_bg_filename()
    );
}

function misaki_woo_get_home_hero_brand_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_home_hero_brand_filename());
    }

    return misaki_woo_home_image_url(
        (int) get_post_meta($page_id, MISAKI_HOME_META_HERO_BRAND, true),
        misaki_woo_home_hero_brand_filename()
    );
}

function misaki_woo_get_home_products_title(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();
    $default = misaki_woo_get_home_products_title_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_HOME_META_PRODUCTS_TITLE, true));

    return $value !== '' ? $value : $default;
}

function misaki_woo_get_home_products_intro(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();
    $default = misaki_woo_get_home_products_intro_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_HOME_META_PRODUCTS_INTRO, true));

    return $value !== '' ? $value : $default;
}

/**
 * Guarda la URL o ruta de un producto de la home (absoluta o relativa al sitio).
 */
function misaki_woo_sanitize_home_product_url(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $url)) {
        return esc_url_raw($url) ?: '';
    }

    $path = '/' . ltrim($url, '/');
    $path = preg_replace('#/+#', '/', $path);

    return $path;
}

/**
 * Convierte ruta relativa (/product/...) en URL del sitio actual.
 */
function misaki_woo_resolve_home_product_url(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $url)) {
        return esc_url($url);
    }

    return esc_url(home_url($url));
}

/**
 * @return array<int, array{title: string, filename?: string, attachment_id: int, url: string}>
 */
function misaki_woo_get_home_products(?int $page_id = null): array
{
    $page_id  = $page_id ?: misaki_woo_get_home_page_id();
    $defaults = misaki_woo_get_home_products_defaults();
    $items    = [];

    if ($page_id) {
        $saved = json_decode((string) get_post_meta($page_id, MISAKI_HOME_META_PRODUCTS, true), true);

        if (is_array($saved) && $saved !== []) {
            foreach ($saved as $row) {
                if (!is_array($row) || empty($row['title'])) {
                    continue;
                }

                $attachment_id = (int) ($row['image_id'] ?? 0);
                $filename      = sanitize_file_name((string) ($row['filename'] ?? ''));

                if (!$attachment_id && $filename) {
                    $attachment_id = misaki_woo_get_attachment_id_by_filename($filename);
                }

                $items[] = [
                    'title'         => sanitize_text_field($row['title']),
                    'filename'      => $filename,
                    'attachment_id' => $attachment_id,
                    'url'           => misaki_woo_sanitize_home_product_url((string) ($row['url'] ?? '')),
                ];
            }
        }
    }

    if ($items === []) {
        foreach ($defaults as $item) {
            $items[] = [
                'title'         => $item['title'],
                'filename'      => $item['filename'],
                'attachment_id' => misaki_woo_get_attachment_id_by_filename($item['filename']),
                'url'           => '',
            ];
        }
    }

    return $items;
}

function misaki_woo_get_home_we_are_title(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();
    $default = misaki_woo_get_home_we_are_title_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_TITLE, true));

    return $value !== '' ? $value : $default;
}

/**
 * @return array<int, string>
 */
function misaki_woo_get_home_we_are_paragraphs(?int $page_id = null): array
{
    $page_id  = $page_id ?: misaki_woo_get_home_page_id();
    $defaults = misaki_woo_get_home_we_are_paragraphs_default();

    if (!$page_id) {
        return $defaults;
    }

    $raw = (string) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_TEXT, true);

    if (trim($raw) === '') {
        return $defaults;
    }

    $lines = [];

    foreach (preg_split('/\r\n|\r|\n/', $raw) ?: [] as $line) {
        $line = trim($line);

        if ($line !== '') {
            $lines[] = $line;
        }
    }

    return $lines !== [] ? $lines : $defaults;
}

function misaki_woo_get_home_we_are_bg_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_home_we_are_bg_filename());
    }

    return misaki_woo_home_image_url(
        (int) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_BG, true),
        misaki_woo_home_we_are_bg_filename()
    );
}

function misaki_woo_get_home_we_are_panel_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_home_we_are_panel_filename());
    }

    return misaki_woo_home_image_url(
        (int) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_PANEL, true),
        misaki_woo_home_we_are_panel_filename()
    );
}

function misaki_woo_get_home_we_are_panel_attachment_id(?int $page_id = null): int
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if ($page_id) {
        $attachment_id = (int) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_PANEL, true);

        if ($attachment_id > 0) {
            return $attachment_id;
        }
    }

    return misaki_woo_get_attachment_id_by_filename(misaki_woo_home_we_are_panel_filename());
}

/**
 * @return array<string, string>
 */
function misaki_woo_get_home_values_flat(?int $page_id = null): array
{
    $defaults = misaki_woo_get_home_values_flat_defaults();
    $page_id  = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $saved = json_decode((string) get_post_meta($page_id, MISAKI_HOME_META_VALUES_FLAT, true), true);

    if (!is_array($saved)) {
        return $defaults;
    }

    return array_merge($defaults, array_intersect_key($saved, $defaults));
}

/**
 * @return array<string, int>
 */
function misaki_woo_get_home_values_image_ids(?int $page_id = null): array
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return [];
    }

    $saved = json_decode((string) get_post_meta($page_id, MISAKI_HOME_META_VALUES_IMAGES, true), true);

    return is_array($saved) ? array_map('intval', $saved) : [];
}

/**
 * @return array<int, array<string, mixed>>
 */
function misaki_woo_get_home_values_blocks(?int $page_id = null): array
{
    $page_id = $page_id ?: misaki_woo_get_home_page_id();

    if (!$page_id) {
        return misaki_woo_get_home_values_blocks_defaults();
    }

    $flat = misaki_woo_get_home_values_flat($page_id);
    $ids  = misaki_woo_get_home_values_image_ids($page_id);

    return misaki_woo_home_build_values_blocks($flat, $ids);
}

/**
 * @return array<int, array{id: string, label: string}>
 */
function misaki_woo_get_home_values_jump_links(?int $page_id = null): array
{
    return misaki_woo_get_home_values_jump_links_defaults();
}

function misaki_woo_home_get_editing_post_id(): int
{
    if (isset($_GET['post'])) {
        return absint($_GET['post']);
    }

    global $post;

    return isset($post->ID) ? (int) $post->ID : 0;
}

function misaki_woo_home_admin_is_home_screen(): bool
{
    return is_admin() && misaki_woo_is_home_page(misaki_woo_home_get_editing_post_id());
}

function misaki_woo_home_disable_block_editor(bool $use_block_editor, WP_Post $post): bool
{
    if (misaki_woo_is_home_page((int) $post->ID)) {
        return false;
    }

    return $use_block_editor;
}

add_filter('use_block_editor_for_post', 'misaki_woo_home_disable_block_editor', 10, 2);

function misaki_woo_home_render_media_field(string $label, string $input_id, string $preview_id, int $attachment_id): void
{
    $preview = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="misaki-page-media-field">
        <label><strong><?php echo esc_html($label); ?></strong></label>
        <input type="hidden" id="<?php echo esc_attr($input_id); ?>" name="<?php echo esc_attr($input_id); ?>" value="<?php echo esc_attr((string) $attachment_id); ?>">
        <p class="misaki-page-media-field__preview" id="<?php echo esc_attr($preview_id); ?>">
            <?php if ($preview) : ?>
                <img src="<?php echo esc_url($preview); ?>" alt="" style="max-width:240px;height:auto;">
            <?php endif; ?>
        </p>
        <p>
            <button type="button" class="button misaki-page-media-select" data-target="<?php echo esc_attr($input_id); ?>" data-preview="<?php echo esc_attr($preview_id); ?>">
                <?php esc_html_e('Elegir imagen', 'misaki-woo'); ?>
            </button>
            <button type="button" class="button misaki-page-media-remove" data-target="<?php echo esc_attr($input_id); ?>" data-preview="<?php echo esc_attr($preview_id); ?>">
                <?php esc_html_e('Quitar', 'misaki-woo'); ?>
            </button>
        </p>
    </div>
    <?php
}

function misaki_woo_home_render_product_row(int $index, array $product): void
{
    $attachment_id = (int) ($product['attachment_id'] ?? 0);
    $preview       = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="misaki-home-product-row" data-index="<?php echo esc_attr((string) $index); ?>">
        <p><strong><?php esc_html_e('Producto', 'misaki-woo'); ?> #<?php echo esc_html((string) ($index + 1)); ?></strong>
            <button type="button" class="button-link-delete misaki-home-remove-row"><?php esc_html_e('Quitar', 'misaki-woo'); ?></button>
        </p>
        <p>
            <label><?php esc_html_e('Título', 'misaki-woo'); ?></label><br>
            <input type="text" class="large-text" name="misaki_home_products[<?php echo esc_attr((string) $index); ?>][title]" value="<?php echo esc_attr($product['title'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Enlace (URL)', 'misaki-woo'); ?></label><br>
            <input
                type="text"
                class="large-text"
                name="misaki_home_products[<?php echo esc_attr((string) $index); ?>][url]"
                value="<?php echo esc_attr($product['url'] ?? ''); ?>"
                placeholder="/product/ejemplo/"
            >
            <span class="description">
                <?php esc_html_e('Opcional. Podés usar una ruta relativa (ej. /product/nombre/) o una URL completa. La foto y el nombre enlazarán ahí.', 'misaki-woo'); ?>
            </span>
        </p>
        <input type="hidden" name="misaki_home_products[<?php echo esc_attr((string) $index); ?>][filename]" value="<?php echo esc_attr($product['filename'] ?? ''); ?>">
        <input type="hidden" id="misaki_home_product_image_<?php echo esc_attr((string) $index); ?>" name="misaki_home_products[<?php echo esc_attr((string) $index); ?>][image_id]" value="<?php echo esc_attr((string) $attachment_id); ?>">
        <p class="misaki-page-media-field__preview" id="misaki_home_product_preview_<?php echo esc_attr((string) $index); ?>">
            <?php if ($preview) : ?>
                <img src="<?php echo esc_url($preview); ?>" alt="" style="max-width:180px;height:auto;">
            <?php endif; ?>
        </p>
        <p>
            <button type="button" class="button misaki-page-media-select" data-target="misaki_home_product_image_<?php echo esc_attr((string) $index); ?>" data-preview="misaki_home_product_preview_<?php echo esc_attr((string) $index); ?>">
                <?php esc_html_e('Elegir imagen', 'misaki-woo'); ?>
            </button>
        </p>
        <hr>
    </div>
    <?php
}

function misaki_woo_home_render_fields(WP_Post $post): void
{
    wp_nonce_field('misaki_home_save', 'misaki_home_nonce');

    $page_id     = (int) $post->ID;
    $products    = misaki_woo_get_home_products($page_id);
    $values_flat = misaki_woo_get_home_values_flat($page_id);
    $values_ids  = misaki_woo_get_home_values_image_ids($page_id);
    $we_are_text = implode("\n", misaki_woo_get_home_we_are_paragraphs($page_id));
    $saved_we    = (string) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_TEXT, true);

    if ($saved_we !== '') {
        $we_are_text = $saved_we;
    }
    ?>
    <p class="description" style="margin:0 0 1rem;">
        <?php esc_html_e('Editá el contenido de la página de inicio. El editor de WordPress de abajo no se usa en el sitio.', 'misaki-woo'); ?>
    </p>

    <h2 class="misaki-home-admin-heading"><?php esc_html_e('Hero', 'misaki-woo'); ?></h2>
    <?php
    misaki_woo_home_render_media_field(
        __('Imagen de fondo', 'misaki-woo'),
        'misaki_home_hero_bg_id',
        'misaki_home_hero_bg_preview',
        (int) get_post_meta($page_id, MISAKI_HOME_META_HERO_BG, true)
    );
    misaki_woo_home_render_media_field(
        __('Logo / imagen central', 'misaki-woo'),
        'misaki_home_hero_brand_id',
        'misaki_home_hero_brand_preview',
        (int) get_post_meta($page_id, MISAKI_HOME_META_HERO_BRAND, true)
    );
    ?>

    <h2 class="misaki-home-admin-heading"><?php esc_html_e('Our Products', 'misaki-woo'); ?></h2>
    <p>
        <label for="misaki_home_products_title"><strong><?php esc_html_e('Título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_home_products_title" name="misaki_home_products_title" class="large-text" value="<?php echo esc_attr(misaki_woo_get_home_products_title($page_id)); ?>">
    </p>
    <p>
        <label for="misaki_home_products_intro"><strong><?php esc_html_e('Texto introductorio', 'misaki-woo'); ?></strong></label><br>
        <textarea id="misaki_home_products_intro" name="misaki_home_products_intro" class="large-text" rows="3"><?php echo esc_textarea(misaki_woo_get_home_products_intro($page_id)); ?></textarea>
    </p>
    <div id="misaki-home-products-list">
        <?php foreach ($products as $index => $product) : ?>
            <?php misaki_woo_home_render_product_row((int) $index, $product); ?>
        <?php endforeach; ?>
    </div>
    <p><button type="button" class="button" id="misaki-home-add-product"><?php esc_html_e('Añadir producto', 'misaki-woo'); ?></button></p>

    <h2 class="misaki-home-admin-heading"><?php esc_html_e('We Are', 'misaki-woo'); ?></h2>
    <?php
    misaki_woo_home_render_media_field(
        __('Imagen cascada (fondo)', 'misaki-woo'),
        'misaki_home_we_are_bg_id',
        'misaki_home_we_are_bg_preview',
        (int) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_BG, true)
    );
    misaki_woo_home_render_media_field(
        __('Imagen del panel (junto al texto)', 'misaki-woo'),
        'misaki_home_we_are_panel_id',
        'misaki_home_we_are_panel_preview',
        (int) get_post_meta($page_id, MISAKI_HOME_META_WE_ARE_PANEL, true)
    );
    ?>
    <p>
        <label for="misaki_home_we_are_title"><strong><?php esc_html_e('Título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_home_we_are_title" name="misaki_home_we_are_title" class="large-text" value="<?php echo esc_attr(misaki_woo_get_home_we_are_title($page_id)); ?>">
    </p>
    <p>
        <label for="misaki_home_we_are_text"><strong><?php esc_html_e('Párrafos', 'misaki-woo'); ?></strong></label><br>
        <span class="description"><?php esc_html_e('Un párrafo por línea.', 'misaki-woo'); ?></span><br>
        <textarea id="misaki_home_we_are_text" name="misaki_home_we_are_text" class="large-text" rows="6"><?php echo esc_textarea($we_are_text); ?></textarea>
    </p>

    <h2 class="misaki-home-admin-heading"><?php esc_html_e('Our Values', 'misaki-woo'); ?></h2>
    <p>
        <label for="misaki_home_values_h2"><strong><?php esc_html_e('Título principal', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_home_values_h2" name="misaki_home_values[values_h2]" class="large-text" value="<?php echo esc_attr($values_flat['values_h2']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Subsección 1 — título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[values_h3_1_title]" class="large-text" value="<?php echo esc_attr($values_flat['values_h3_1_title']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Subsección 1 — texto', 'misaki-woo'); ?></strong></label><br>
        <textarea name="misaki_home_values[values_h3_1_text]" class="large-text" rows="3"><?php echo esc_textarea($values_flat['values_h3_1_text']); ?></textarea>
    </p>
    <p>
        <label><strong><?php esc_html_e('Subsección 2 — título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[values_h3_2_title]" class="large-text" value="<?php echo esc_attr($values_flat['values_h3_2_title']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Subsección 2 — texto', 'misaki-woo'); ?></strong></label><br>
        <textarea name="misaki_home_values[values_h3_2_text]" class="large-text" rows="4"><?php echo esc_textarea($values_flat['values_h3_2_text']); ?></textarea>
    </p>
    <?php
    misaki_woo_home_render_media_field(__('Imagen Our Values', 'misaki-woo'), 'misaki_home_values_img_values_id', 'misaki_home_values_img_values_preview', (int) ($values_ids['img_values'] ?? 0));
    misaki_woo_home_render_media_field(__('Imagen Our Mission', 'misaki-woo'), 'misaki_home_values_img_mission_id', 'misaki_home_values_img_mission_preview', (int) ($values_ids['img_mission'] ?? 0));
    ?>
    <p>
        <label><strong><?php esc_html_e('Cita (Our Mission)', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[mission_quote]" class="large-text" value="<?php echo esc_attr($values_flat['mission_quote']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Título Our Mission', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[mission_h2]" class="large-text" value="<?php echo esc_attr($values_flat['mission_h2']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Texto Our Mission', 'misaki-woo'); ?></strong></label><br>
        <textarea name="misaki_home_values[mission_text]" class="large-text" rows="4"><?php echo esc_textarea($values_flat['mission_text']); ?></textarea>
    </p>
    <p>
        <label><strong><?php esc_html_e('Título The Brewery', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[brewery_h2]" class="large-text" value="<?php echo esc_attr($values_flat['brewery_h2']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Texto The Brewery', 'misaki-woo'); ?></strong></label><br>
        <textarea name="misaki_home_values[brewery_text]" class="large-text" rows="5"><?php echo esc_textarea($values_flat['brewery_text']); ?></textarea>
    </p>
    <?php misaki_woo_home_render_media_field(__('Imagen The Brewery', 'misaki-woo'), 'misaki_home_values_img_brewery_id', 'misaki_home_values_img_brewery_preview', (int) ($values_ids['img_brewery'] ?? 0)); ?>
    <p>
        <label><strong><?php esc_html_e('Título Sake Production', 'misaki-woo'); ?></strong></label><br>
        <input type="text" name="misaki_home_values[sake_h2]" class="large-text" value="<?php echo esc_attr($values_flat['sake_h2']); ?>">
    </p>
    <p>
        <label><strong><?php esc_html_e('Lista Sake Production', 'misaki-woo'); ?></strong></label><br>
        <span class="description"><?php esc_html_e('Un ítem por línea.', 'misaki-woo'); ?></span><br>
        <textarea name="misaki_home_values[sake_list]" class="large-text" rows="7"><?php echo esc_textarea($values_flat['sake_list']); ?></textarea>
    </p>
    <?php misaki_woo_home_render_media_field(__('Imagen Sake Production', 'misaki-woo'), 'misaki_home_values_img_sake_id', 'misaki_home_values_img_sake_preview', (int) ($values_ids['img_sake'] ?? 0)); ?>

    <template id="misaki-home-product-template">
        <?php misaki_woo_home_render_product_row(999, ['title' => '', 'filename' => '', 'attachment_id' => 0, 'url' => '']); ?>
    </template>
    <?php
}

function misaki_woo_home_render_editor_panel(WP_Post $post): void
{
    if (!misaki_woo_is_home_page((int) $post->ID)) {
        return;
    }

    echo '<div class="misaki-page-editor-panel misaki-home-editor-panel">';
    misaki_woo_home_render_fields($post);
    echo '</div>';
}

add_action('edit_form_after_title', 'misaki_woo_home_render_editor_panel');

function misaki_woo_home_save_meta(int $post_id): void
{
    if (!isset($_POST['misaki_home_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['misaki_home_nonce'])), 'misaki_home_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    if (!misaki_woo_is_home_page($post_id)) {
        return;
    }

    update_post_meta($post_id, MISAKI_HOME_META_HERO_BG, isset($_POST['misaki_home_hero_bg_id']) ? absint($_POST['misaki_home_hero_bg_id']) : 0);
    update_post_meta($post_id, MISAKI_HOME_META_HERO_BRAND, isset($_POST['misaki_home_hero_brand_id']) ? absint($_POST['misaki_home_hero_brand_id']) : 0);
    update_post_meta($post_id, MISAKI_HOME_META_PRODUCTS_TITLE, isset($_POST['misaki_home_products_title']) ? sanitize_text_field(wp_unslash($_POST['misaki_home_products_title'])) : '');
    update_post_meta($post_id, MISAKI_HOME_META_PRODUCTS_INTRO, isset($_POST['misaki_home_products_intro']) ? sanitize_textarea_field(wp_unslash($_POST['misaki_home_products_intro'])) : '');
    update_post_meta($post_id, MISAKI_HOME_META_WE_ARE_TITLE, isset($_POST['misaki_home_we_are_title']) ? sanitize_text_field(wp_unslash($_POST['misaki_home_we_are_title'])) : '');
    update_post_meta($post_id, MISAKI_HOME_META_WE_ARE_TEXT, isset($_POST['misaki_home_we_are_text']) ? sanitize_textarea_field(wp_unslash($_POST['misaki_home_we_are_text'])) : '');
    update_post_meta($post_id, MISAKI_HOME_META_WE_ARE_BG, isset($_POST['misaki_home_we_are_bg_id']) ? absint($_POST['misaki_home_we_are_bg_id']) : 0);
    update_post_meta($post_id, MISAKI_HOME_META_WE_ARE_PANEL, isset($_POST['misaki_home_we_are_panel_id']) ? absint($_POST['misaki_home_we_are_panel_id']) : 0);

    $products = [];

    if (isset($_POST['misaki_home_products']) && is_array($_POST['misaki_home_products'])) {
        foreach ($_POST['misaki_home_products'] as $row) {
            if (!is_array($row) || trim((string) ($row['title'] ?? '')) === '') {
                continue;
            }

            $products[] = [
                'title'    => sanitize_text_field($row['title']),
                'filename' => sanitize_file_name((string) ($row['filename'] ?? '')),
                'image_id' => absint($row['image_id'] ?? 0),
                'url'      => misaki_woo_sanitize_home_product_url((string) ($row['url'] ?? '')),
            ];
        }
    }

    update_post_meta($post_id, MISAKI_HOME_META_PRODUCTS, wp_json_encode($products));

    $defaults     = misaki_woo_get_home_values_flat_defaults();
    $values_flat  = $defaults;
    $values_images = [];

    if (isset($_POST['misaki_home_values']) && is_array($_POST['misaki_home_values'])) {
        foreach ($defaults as $key => $default_value) {
            if (!isset($_POST['misaki_home_values'][$key])) {
                continue;
            }

            $values_flat[$key] = in_array($key, ['values_h3_1_text', 'values_h3_2_text', 'mission_text', 'brewery_text', 'sake_list'], true)
                ? sanitize_textarea_field(wp_unslash($_POST['misaki_home_values'][$key]))
                : sanitize_text_field(wp_unslash($_POST['misaki_home_values'][$key]));
        }
    }

    $image_keys = [
        'img_values'  => 'misaki_home_values_img_values_id',
        'img_mission' => 'misaki_home_values_img_mission_id',
        'img_brewery' => 'misaki_home_values_img_brewery_id',
        'img_sake'    => 'misaki_home_values_img_sake_id',
    ];

    foreach ($image_keys as $meta_key => $post_key) {
        $values_images[$meta_key] = isset($_POST[$post_key]) ? absint($_POST[$post_key]) : 0;
    }

    update_post_meta($post_id, MISAKI_HOME_META_VALUES_FLAT, wp_json_encode($values_flat));
    update_post_meta($post_id, MISAKI_HOME_META_VALUES_IMAGES, wp_json_encode($values_images));
    update_option(MISAKI_HOME_PAGE_OPTION, $post_id);

    if ((int) get_option('page_on_front') !== $post_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $post_id);
    }
}

add_action('save_post_page', 'misaki_woo_home_save_meta');

function misaki_woo_home_admin_assets(string $hook): void
{
    if (!in_array($hook, ['post.php', 'post-new.php'], true) || !misaki_woo_home_admin_is_home_screen()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    wp_enqueue_media();

    $admin_js = $theme_dir . '/assets/js/home-admin.js';
    wp_enqueue_script(
        'misaki-woo-home-admin',
        $theme_uri . '/assets/js/home-admin.js',
        ['jquery'],
        file_exists($admin_js) ? (string) filemtime($admin_js) : null,
        true
    );
}

add_action('admin_enqueue_scripts', 'misaki_woo_home_admin_assets');

function misaki_woo_home_admin_head_styles(): void
{
    if (!misaki_woo_home_admin_is_home_screen()) {
        return;
    }
    ?>
    <style>
        .misaki-home-admin-heading {
            margin: 1.5rem 0 0.75rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e2e4e7;
            font-size: 1.1rem;
        }

        .misaki-home-admin-heading:first-of-type {
            margin-top: 0;
            padding-top: 0;
            border-top: 0;
        }

        .misaki-home-product-row {
            margin-bottom: 0.5rem;
            padding: 0.75rem 0.5rem;
            background: #f9f9f9;
        }

        #postdivrich,
        #wp-content-editor-container,
        .postarea {
            display: none !important;
        }
    </style>
    <?php
}

add_action('admin_head', 'misaki_woo_home_admin_head_styles');
