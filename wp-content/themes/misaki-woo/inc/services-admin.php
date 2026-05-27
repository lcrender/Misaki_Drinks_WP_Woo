<?php
/**
 * Campos editables de la página Services (admin de WordPress).
 */

if (!defined('ABSPATH')) {
    exit;
}

const MISAKI_SERVICES_META_TITLE       = '_misaki_services_title';
const MISAKI_SERVICES_META_ITEMS       = '_misaki_services_items';
const MISAKI_SERVICES_META_HERO_IMAGE  = '_misaki_services_hero_image_id';
const MISAKI_SERVICES_META_PANEL_IMAGE = '_misaki_services_panel_image_id';
const MISAKI_SERVICES_PAGE_OPTION      = 'misaki_services_page_id';

/**
 * ¿Es la página Services? (slug, plantilla o ID guardado).
 */
function misaki_woo_is_services_page(int $post_id): bool
{
    if ($post_id <= 0) {
        return false;
    }

    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'page') {
        return false;
    }

    $stored_id = (int) get_option(MISAKI_SERVICES_PAGE_OPTION);

    if ($stored_id > 0 && $post_id === $stored_id) {
        return true;
    }

    if ($post->post_name === 'services') {
        return true;
    }

    return get_page_template_slug($post_id) === 'page-services.php';
}

/**
 * ID de la página Services.
 */
function misaki_woo_get_services_page_id(): int
{
    static $page_id = null;

    if ($page_id !== null) {
        return $page_id;
    }

    $stored_id = (int) get_option(MISAKI_SERVICES_PAGE_OPTION);

    if ($stored_id > 0 && get_post_status($stored_id)) {
        $page_id = $stored_id;

        return $page_id;
    }

    $page = get_page_by_path('services');

    if ($page) {
        $page_id = (int) $page->ID;
        update_option(MISAKI_SERVICES_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $by_template = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-services.php',
        'fields'         => 'ids',
    ]);

    if (!empty($by_template[0])) {
        $page_id = (int) $by_template[0];
        update_option(MISAKI_SERVICES_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $page_id = 0;

    return $page_id;
}

/**
 * Asigna plantilla e ID guardado a la página Services existente.
 */
function misaki_woo_services_sync_page(): void
{
    $page_id = misaki_woo_get_services_page_id();

    if (!$page_id) {
        return;
    }

    if (get_page_template_slug($page_id) !== 'page-services.php') {
        update_post_meta($page_id, '_wp_page_template', 'page-services.php');
    }
}

add_action('admin_init', 'misaki_woo_services_sync_page');

/**
 * @return array<int, string>
 */
function misaki_woo_get_services_default_items(): array
{
    return [
        'An excellent product range',
        'Premium \'Europe X Japan\' branding',
        'Large available stock in Europe',
        'Fast deliveries (2-4 working days)',
        'Strong marketing tools & personal attention to our clients',
        'Educational material & courses available for staff',
        'A first-class, fast-growing customer base',
    ];
}

function misaki_woo_get_services_default_title(): string
{
    return 'Our Services';
}

/**
 * @return array{title: string, items: string, hero_image_id: int, panel_image_id: int}
 */
function misaki_woo_get_services_meta_raw(int $page_id): array
{
    return [
        'title'          => (string) get_post_meta($page_id, MISAKI_SERVICES_META_TITLE, true),
        'items'          => (string) get_post_meta($page_id, MISAKI_SERVICES_META_ITEMS, true),
        'hero_image_id'  => (int) get_post_meta($page_id, MISAKI_SERVICES_META_HERO_IMAGE, true),
        'panel_image_id' => (int) get_post_meta($page_id, MISAKI_SERVICES_META_PANEL_IMAGE, true),
    ];
}

function misaki_woo_get_services_title(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_services_page_id();
    $default = misaki_woo_get_services_default_title();

    if (!$page_id) {
        return $default;
    }

    $title = trim(misaki_woo_get_services_meta_raw($page_id)['title']);

    return $title !== '' ? $title : $default;
}

/**
 * @return array<int, string>
 */
function misaki_woo_get_services_items(?int $page_id = null): array
{
    $page_id = $page_id ?: misaki_woo_get_services_page_id();
    $default = misaki_woo_get_services_default_items();

    if (!$page_id) {
        return $default;
    }

    $raw = misaki_woo_get_services_meta_raw($page_id)['items'];

    if (trim($raw) === '') {
        return $default;
    }

    $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
    $items = [];

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line !== '') {
            $items[] = $line;
        }
    }

    return $items !== [] ? $items : $default;
}

function misaki_woo_get_services_image_url(int $attachment_id, string $fallback_filename): string
{
    if ($attachment_id > 0) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return misaki_woo_get_upload_asset_url($fallback_filename);
}

function misaki_woo_get_services_hero_image_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_services_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_get_services_background_filename());
    }

    $meta = misaki_woo_get_services_meta_raw($page_id);

    return misaki_woo_get_services_image_url(
        $meta['hero_image_id'],
        misaki_woo_get_services_background_filename()
    );
}

function misaki_woo_get_services_panel_image_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_services_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_get_services_panel_background_filename());
    }

    $meta = misaki_woo_get_services_meta_raw($page_id);

    return misaki_woo_get_services_image_url(
        $meta['panel_image_id'],
        misaki_woo_get_services_panel_background_filename()
    );
}

function misaki_woo_services_get_editing_post_id(): int
{
    if (isset($_GET['post'])) {
        return absint($_GET['post']);
    }

    global $post;

    return isset($post->ID) ? (int) $post->ID : 0;
}

function misaki_woo_services_admin_is_services_screen(): bool
{
    if (!is_admin()) {
        return false;
    }

    $post_id = misaki_woo_services_get_editing_post_id();

    return misaki_woo_is_services_page($post_id);
}

/**
 * Editor clásico en Services (más claro que bloques vacíos).
 */
function misaki_woo_services_disable_block_editor(bool $use_block_editor, WP_Post $post): bool
{
    if (misaki_woo_is_services_page((int) $post->ID)) {
        return false;
    }

    return $use_block_editor;
}

add_filter('use_block_editor_for_post', 'misaki_woo_services_disable_block_editor', 10, 2);

function misaki_woo_services_render_editor_panel(WP_Post $post): void
{
    if (!misaki_woo_is_services_page((int) $post->ID)) {
        return;
    }

    echo '<div class="misaki-page-editor-panel misaki-services-editor-panel">';
    misaki_woo_services_render_fields($post);
    echo '</div>';
}

add_action('edit_form_after_title', 'misaki_woo_services_render_editor_panel');

function misaki_woo_services_render_fields(WP_Post $post): void
{
    wp_nonce_field('misaki_services_save', 'misaki_services_nonce');

    $meta = misaki_woo_get_services_meta_raw((int) $post->ID);
    $hero_preview = $meta['hero_image_id']
        ? wp_get_attachment_image_url($meta['hero_image_id'], 'medium')
        : '';
    $panel_preview = $meta['panel_image_id']
        ? wp_get_attachment_image_url($meta['panel_image_id'], 'medium')
        : '';
    ?>
    <p class="description" style="margin:0 0 1rem;">
        <?php esc_html_e('Editá el contenido visible en /services/. El recuadro de contenido de WordPress de abajo no se muestra en el sitio.', 'misaki-woo'); ?>
    </p>

    <p>
        <label for="misaki_services_title"><strong><?php esc_html_e('Título', 'misaki-woo'); ?></strong></label><br>
        <input
            type="text"
            id="misaki_services_title"
            name="misaki_services_title"
            class="large-text"
            value="<?php echo esc_attr($meta['title'] !== '' ? $meta['title'] : misaki_woo_get_services_default_title()); ?>"
        >
    </p>

    <p>
        <label for="misaki_services_items"><strong><?php esc_html_e('Lista de servicios', 'misaki-woo'); ?></strong></label><br>
        <span class="description"><?php esc_html_e('Un ítem por línea.', 'misaki-woo'); ?></span><br>
        <textarea
            id="misaki_services_items"
            name="misaki_services_items"
            class="large-text"
            rows="10"
        ><?php
            $items_value = $meta['items'] !== ''
                ? $meta['items']
                : implode("\n", misaki_woo_get_services_default_items());
            echo esc_textarea($items_value);
        ?></textarea>
    </p>

    <div class="misaki-services-media-field" style="margin-bottom:1.25rem;">
        <label><strong><?php esc_html_e('Imagen de fondo (página)', 'misaki-woo'); ?></strong></label>
        <input type="hidden" id="misaki_services_hero_image_id" name="misaki_services_hero_image_id" value="<?php echo esc_attr((string) $meta['hero_image_id']); ?>">
        <p class="misaki-services-media-field__preview" id="misaki_services_hero_preview">
            <?php if ($hero_preview) : ?>
                <img src="<?php echo esc_url($hero_preview); ?>" alt="" style="max-width:240px;height:auto;">
            <?php endif; ?>
        </p>
        <p>
            <button type="button" class="button misaki-services-media-select" data-target="misaki_services_hero_image_id" data-preview="misaki_services_hero_preview">
                <?php esc_html_e('Elegir imagen', 'misaki-woo'); ?>
            </button>
            <button type="button" class="button misaki-services-media-remove" data-target="misaki_services_hero_image_id" data-preview="misaki_services_hero_preview">
                <?php esc_html_e('Quitar', 'misaki-woo'); ?>
            </button>
        </p>
    </div>

    <div class="misaki-services-media-field">
        <label><strong><?php esc_html_e('Imagen de fondo (panel oscuro)', 'misaki-woo'); ?></strong></label>
        <input type="hidden" id="misaki_services_panel_image_id" name="misaki_services_panel_image_id" value="<?php echo esc_attr((string) $meta['panel_image_id']); ?>">
        <p class="misaki-services-media-field__preview" id="misaki_services_panel_preview">
            <?php if ($panel_preview) : ?>
                <img src="<?php echo esc_url($panel_preview); ?>" alt="" style="max-width:240px;height:auto;">
            <?php endif; ?>
        </p>
        <p>
            <button type="button" class="button misaki-services-media-select" data-target="misaki_services_panel_image_id" data-preview="misaki_services_panel_preview">
                <?php esc_html_e('Elegir imagen', 'misaki-woo'); ?>
            </button>
            <button type="button" class="button misaki-services-media-remove" data-target="misaki_services_panel_image_id" data-preview="misaki_services_panel_preview">
                <?php esc_html_e('Quitar', 'misaki-woo'); ?>
            </button>
        </p>
    </div>
    <?php
}

function misaki_woo_services_save_meta(int $post_id): void
{
    if (!isset($_POST['misaki_services_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['misaki_services_nonce'])), 'misaki_services_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    if (!misaki_woo_is_services_page($post_id)) {
        return;
    }

    $title = isset($_POST['misaki_services_title'])
        ? sanitize_text_field(wp_unslash($_POST['misaki_services_title']))
        : '';
    $items = isset($_POST['misaki_services_items'])
        ? sanitize_textarea_field(wp_unslash($_POST['misaki_services_items']))
        : '';
    $hero_image_id = isset($_POST['misaki_services_hero_image_id'])
        ? absint($_POST['misaki_services_hero_image_id'])
        : 0;
    $panel_image_id = isset($_POST['misaki_services_panel_image_id'])
        ? absint($_POST['misaki_services_panel_image_id'])
        : 0;

    update_post_meta($post_id, MISAKI_SERVICES_META_TITLE, $title);
    update_post_meta($post_id, MISAKI_SERVICES_META_ITEMS, $items);
    update_post_meta($post_id, MISAKI_SERVICES_META_HERO_IMAGE, $hero_image_id);
    update_post_meta($post_id, MISAKI_SERVICES_META_PANEL_IMAGE, $panel_image_id);

    update_option(MISAKI_SERVICES_PAGE_OPTION, $post_id);
}

add_action('save_post_page', 'misaki_woo_services_save_meta');

function misaki_woo_services_admin_assets(string $hook): void
{
    if (!in_array($hook, ['post.php', 'post-new.php'], true) || !misaki_woo_services_admin_is_services_screen()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    wp_enqueue_media();

    $admin_js = $theme_dir . '/assets/js/services-admin.js';
    wp_enqueue_script(
        'misaki-woo-services-admin',
        $theme_uri . '/assets/js/services-admin.js',
        ['jquery'],
        file_exists($admin_js) ? (string) filemtime($admin_js) : null,
        true
    );
}

add_action('admin_enqueue_scripts', 'misaki_woo_services_admin_assets');

function misaki_woo_services_admin_head_styles(): void
{
    if (!misaki_woo_services_admin_is_services_screen()) {
        return;
    }
    ?>
    <style>
        .misaki-page-editor-panel,
        .misaki-services-editor-panel {
            margin: 1rem 0 1.5rem;
            padding: 1.25rem 1.5rem 1.5rem;
            border: 1px solid #c3c4c7;
            border-left: 4px solid #c8a85a;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        }

        #postdivrich,
        #wp-content-editor-container,
        .postarea {
            display: none !important;
        }
    </style>
    <?php
}

add_action('admin_head', 'misaki_woo_services_admin_head_styles');
