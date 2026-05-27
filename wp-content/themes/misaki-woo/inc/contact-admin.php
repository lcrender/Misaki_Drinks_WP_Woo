<?php
/**
 * Campos editables de la página Contact (admin de WordPress).
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/contact-data.php';

const MISAKI_CONTACT_PAGE_OPTION           = 'misaki_contact_page_id';
const MISAKI_CONTACT_META_INTRO_TITLE      = '_misaki_contact_intro_title';
const MISAKI_CONTACT_META_INTRO_LEAD       = '_misaki_contact_intro_lead';
const MISAKI_CONTACT_META_INSTAGRAM_URL    = '_misaki_contact_instagram_url';
const MISAKI_CONTACT_META_INSTAGRAM_HANDLE = '_misaki_contact_instagram_handle';
const MISAKI_CONTACT_META_INTRO_IMAGE      = '_misaki_contact_intro_image_id';
const MISAKI_CONTACT_META_TEAM             = '_misaki_contact_team';
const MISAKI_CONTACT_META_COMPANY_NAME     = '_misaki_contact_company_name';
const MISAKI_CONTACT_META_COMPANY_ADDRESS  = '_misaki_contact_company_address';
const MISAKI_CONTACT_META_DIST_TITLE       = '_misaki_contact_distributors_title';
const MISAKI_CONTACT_META_DIST_LEAD        = '_misaki_contact_distributors_lead';
const MISAKI_CONTACT_META_DIST_BG          = '_misaki_contact_distributors_bg_id';
const MISAKI_CONTACT_META_DISTRIBUTORS     = '_misaki_contact_distributors';

function misaki_woo_is_contact_page(int $post_id): bool
{
    if ($post_id <= 0) {
        return false;
    }

    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'page') {
        return false;
    }

    $stored_id = (int) get_option(MISAKI_CONTACT_PAGE_OPTION);

    if ($stored_id > 0 && $post_id === $stored_id) {
        return true;
    }

    if ($post->post_name === 'contact') {
        return true;
    }

    return get_page_template_slug($post_id) === 'page-contact.php';
}

function misaki_woo_get_contact_page_id(): int
{
    static $page_id = null;

    if ($page_id !== null) {
        return $page_id;
    }

    $stored_id = (int) get_option(MISAKI_CONTACT_PAGE_OPTION);

    if ($stored_id > 0 && get_post_status($stored_id)) {
        $page_id = $stored_id;

        return $page_id;
    }

    $page = get_page_by_path('contact');

    if ($page) {
        $page_id = (int) $page->ID;
        update_option(MISAKI_CONTACT_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $by_template = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-contact.php',
        'fields'         => 'ids',
    ]);

    if (!empty($by_template[0])) {
        $page_id = (int) $by_template[0];
        update_option(MISAKI_CONTACT_PAGE_OPTION, $page_id);

        return $page_id;
    }

    $page_id = 0;

    return $page_id;
}

function misaki_woo_contact_sync_page(): void
{
    $page_id = misaki_woo_get_contact_page_id();

    if (!$page_id) {
        return;
    }

    if (get_page_template_slug($page_id) !== 'page-contact.php') {
        update_post_meta($page_id, '_wp_page_template', 'page-contact.php');
    }
}

add_action('admin_init', 'misaki_woo_contact_sync_page');

/**
 * @param mixed $json
 * @return array<int, array<string, mixed>>
 */
function misaki_woo_contact_decode_json_array($json): array
{
    if (!is_string($json) || trim($json) === '') {
        return [];
    }

    $decoded = json_decode($json, true);

    return is_array($decoded) ? $decoded : [];
}

function misaki_woo_get_contact_intro_title(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_contact_intro_title_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_INTRO_TITLE, true));

    return $value !== '' ? $value : $default;
}

function misaki_woo_get_contact_intro_lead(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_contact_intro_lead_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_INTRO_LEAD, true));

    return $value !== '' ? $value : $default;
}

/**
 * @return array{url: string, handle: string}
 */
function misaki_woo_get_contact_instagram(?int $page_id = null): array
{
    $defaults = misaki_woo_get_contact_instagram_defaults();
    $page_id  = $page_id ?: misaki_woo_get_contact_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $url    = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_INSTAGRAM_URL, true));
    $handle = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_INSTAGRAM_HANDLE, true));

    return [
        'url'    => $url !== '' ? $url : $defaults['url'],
        'handle' => $handle !== '' ? $handle : $defaults['handle'],
    ];
}

function misaki_woo_get_contact_intro_image_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_get_contact_intro_image_filename());
    }

    $attachment_id = (int) get_post_meta($page_id, MISAKI_CONTACT_META_INTRO_IMAGE, true);

    if ($attachment_id > 0) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return misaki_woo_get_upload_asset_url(misaki_woo_get_contact_intro_image_filename());
}

/**
 * @return array<int, array{name: string, role: string, role_ja: string, phone: string, email: string}>
 */
function misaki_woo_get_contact_team(?int $page_id = null): array
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_contact_team_defaults();

    if (!$page_id) {
        return $default;
    }

    $saved = misaki_woo_contact_decode_json_array(get_post_meta($page_id, MISAKI_CONTACT_META_TEAM, true));

    return $saved !== [] ? $saved : $default;
}

/**
 * @return array{name: string, address_lines: string[]}
 */
function misaki_woo_get_contact_company(?int $page_id = null): array
{
    $defaults = misaki_woo_get_contact_company_defaults();
    $page_id  = $page_id ?: misaki_woo_get_contact_page_id();

    if (!$page_id) {
        return $defaults;
    }

    $name = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_COMPANY_NAME, true));
    $raw  = (string) get_post_meta($page_id, MISAKI_CONTACT_META_COMPANY_ADDRESS, true);

    $lines = [];

    if (trim($raw) !== '') {
        foreach (preg_split('/\r\n|\r|\n/', $raw) ?: [] as $line) {
            $line = trim($line);

            if ($line !== '') {
                $lines[] = $line;
            }
        }
    }

    return [
        'name'          => $name !== '' ? $name : $defaults['name'],
        'address_lines' => $lines !== [] ? $lines : $defaults['address_lines'],
    ];
}

function misaki_woo_get_contact_distributors_title(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_contact_distributors_title_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_DIST_TITLE, true));

    return $value !== '' ? $value : $default;
}

function misaki_woo_get_contact_distributors_lead(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_contact_distributors_lead_default();

    if (!$page_id) {
        return $default;
    }

    $value = trim((string) get_post_meta($page_id, MISAKI_CONTACT_META_DIST_LEAD, true));

    return $value !== '' ? $value : $default;
}

function misaki_woo_get_contact_distributors_bg_url(?int $page_id = null): string
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();

    if (!$page_id) {
        return misaki_woo_get_upload_asset_url(misaki_woo_get_contact_distributors_bg_filename());
    }

    $attachment_id = (int) get_post_meta($page_id, MISAKI_CONTACT_META_DIST_BG, true);

    if ($attachment_id > 0) {
        $url = wp_get_attachment_image_url($attachment_id, 'full');

        if ($url) {
            return $url;
        }
    }

    return misaki_woo_get_upload_asset_url(misaki_woo_get_contact_distributors_bg_filename());
}

/**
 * @return array<int, array{country: string, flag: string, distributors: array<int, array{name: string, tel: string, emails: string[]}>}>
 */
function misaki_woo_get_distributors(?int $page_id = null): array
{
    $page_id = $page_id ?: misaki_woo_get_contact_page_id();
    $default = misaki_woo_get_distributors_defaults();

    if (!$page_id) {
        return $default;
    }

    $saved = misaki_woo_contact_decode_json_array(get_post_meta($page_id, MISAKI_CONTACT_META_DISTRIBUTORS, true));

    return $saved !== [] ? $saved : $default;
}

/**
 * @param array<int, array{name: string, tel: string, emails: string[]}> $distributors
 */
function misaki_woo_contact_distributors_to_lines(array $distributors): string
{
    $lines = [];

    foreach ($distributors as $item) {
        $emails = !empty($item['emails']) && is_array($item['emails'])
            ? implode(', ', $item['emails'])
            : '';
        $lines[] = trim((string) ($item['name'] ?? ''))
            . ' | '
            . trim((string) ($item['tel'] ?? ''))
            . ' | '
            . $emails;
    }

    return implode("\n", $lines);
}

/**
 * @return array<int, array{name: string, tel: string, emails: string[]}>
 */
function misaki_woo_contact_lines_to_distributors(string $raw): array
{
    $items = [];
    $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '') {
            continue;
        }

        $parts  = array_map('trim', explode('|', $line));
        $name   = $parts[0] ?? '';
        $tel    = $parts[1] ?? '';
        $emails = [];

        if (!empty($parts[2])) {
            foreach (explode(',', $parts[2]) as $email) {
                $email = trim($email);

                if ($email !== '') {
                    $emails[] = sanitize_email($email);
                }
            }
        }

        if ($name === '') {
            continue;
        }

        $items[] = [
            'name'   => sanitize_text_field($name),
            'tel'    => sanitize_text_field($tel),
            'emails' => array_values(array_filter($emails)),
        ];
    }

    return $items;
}

function misaki_woo_contact_get_editing_post_id(): int
{
    if (isset($_GET['post'])) {
        return absint($_GET['post']);
    }

    global $post;

    return isset($post->ID) ? (int) $post->ID : 0;
}

function misaki_woo_contact_admin_is_contact_screen(): bool
{
    if (!is_admin()) {
        return false;
    }

    return misaki_woo_is_contact_page(misaki_woo_contact_get_editing_post_id());
}

function misaki_woo_contact_disable_block_editor(bool $use_block_editor, WP_Post $post): bool
{
    if (misaki_woo_is_contact_page((int) $post->ID)) {
        return false;
    }

    return $use_block_editor;
}

add_filter('use_block_editor_for_post', 'misaki_woo_contact_disable_block_editor', 10, 2);

function misaki_woo_contact_render_editor_panel(WP_Post $post): void
{
    if (!misaki_woo_is_contact_page((int) $post->ID)) {
        return;
    }

    echo '<div class="misaki-page-editor-panel misaki-contact-editor-panel">';
    misaki_woo_contact_render_fields($post);
    echo '</div>';
}

add_action('edit_form_after_title', 'misaki_woo_contact_render_editor_panel');

function misaki_woo_contact_render_team_row(int $index, array $member): void
{
    ?>
    <div class="misaki-contact-team-row" data-index="<?php echo esc_attr((string) $index); ?>">
        <p><strong><?php esc_html_e('Miembro del equipo', 'misaki-woo'); ?> #<?php echo esc_html((string) ($index + 1)); ?></strong>
            <button type="button" class="button-link-delete misaki-contact-remove-row"><?php esc_html_e('Quitar', 'misaki-woo'); ?></button>
        </p>
        <p>
            <label><?php esc_html_e('Nombre', 'misaki-woo'); ?></label><br>
            <input type="text" class="large-text" name="misaki_contact_team[<?php echo esc_attr((string) $index); ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Cargo (EN)', 'misaki-woo'); ?></label><br>
            <input type="text" class="large-text" name="misaki_contact_team[<?php echo esc_attr((string) $index); ?>][role]" value="<?php echo esc_attr($member['role'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Cargo (JA)', 'misaki-woo'); ?></label><br>
            <input type="text" class="large-text" name="misaki_contact_team[<?php echo esc_attr((string) $index); ?>][role_ja]" value="<?php echo esc_attr($member['role_ja'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Teléfono', 'misaki-woo'); ?></label><br>
            <input type="text" class="large-text" name="misaki_contact_team[<?php echo esc_attr((string) $index); ?>][phone]" value="<?php echo esc_attr($member['phone'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Email', 'misaki-woo'); ?></label><br>
            <input type="email" class="large-text" name="misaki_contact_team[<?php echo esc_attr((string) $index); ?>][email]" value="<?php echo esc_attr($member['email'] ?? ''); ?>">
        </p>
        <hr>
    </div>
    <?php
}

function misaki_woo_contact_render_country_block(int $index, array $country): void
{
    $lines = misaki_woo_contact_distributors_to_lines($country['distributors'] ?? []);
    ?>
    <div class="misaki-contact-country-row" data-index="<?php echo esc_attr((string) $index); ?>">
        <p><strong><?php esc_html_e('País', 'misaki-woo'); ?> #<?php echo esc_html((string) ($index + 1)); ?></strong>
            <button type="button" class="button-link-delete misaki-contact-remove-row"><?php esc_html_e('Quitar', 'misaki-woo'); ?></button>
        </p>
        <p>
            <label><?php esc_html_e('Nombre del país', 'misaki-woo'); ?></label><br>
            <input type="text" class="regular-text" name="misaki_contact_countries[<?php echo esc_attr((string) $index); ?>][country]" value="<?php echo esc_attr($country['country'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Bandera (emoji)', 'misaki-woo'); ?></label><br>
            <input type="text" class="small-text" name="misaki_contact_countries[<?php echo esc_attr((string) $index); ?>][flag]" value="<?php echo esc_attr($country['flag'] ?? ''); ?>">
        </p>
        <p>
            <label><?php esc_html_e('Distribuidores', 'misaki-woo'); ?></label><br>
            <span class="description"><?php esc_html_e('Uno por línea: Nombre | Teléfono | email1, email2', 'misaki-woo'); ?></span><br>
            <textarea class="large-text" rows="5" name="misaki_contact_countries[<?php echo esc_attr((string) $index); ?>][lines]"><?php echo esc_textarea($lines); ?></textarea>
        </p>
        <hr>
    </div>
    <?php
}

function misaki_woo_contact_render_media_field(string $label, string $input_id, string $preview_id, int $attachment_id): void
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

function misaki_woo_contact_render_fields(WP_Post $post): void
{
    wp_nonce_field('misaki_contact_save', 'misaki_contact_nonce');

    $page_id         = (int) $post->ID;
    $intro_image_id  = (int) get_post_meta($page_id, MISAKI_CONTACT_META_INTRO_IMAGE, true);
    $dist_bg_id      = (int) get_post_meta($page_id, MISAKI_CONTACT_META_DIST_BG, true);
    $instagram       = misaki_woo_get_contact_instagram($page_id);
    $company         = misaki_woo_get_contact_company($page_id);
    $team            = misaki_woo_get_contact_team($page_id);
    $distributors    = misaki_woo_get_distributors($page_id);
    $company_address = implode("\n", $company['address_lines']);
    $saved_address   = (string) get_post_meta($page_id, MISAKI_CONTACT_META_COMPANY_ADDRESS, true);

    if ($saved_address !== '') {
        $company_address = $saved_address;
    }
    ?>
    <p class="description" style="margin:0 0 1rem;">
        <?php esc_html_e('Editá el contenido visible en /contact/. El editor de WordPress de abajo no se usa en el sitio.', 'misaki-woo'); ?>
    </p>

    <h2 class="misaki-contact-admin-heading"><?php esc_html_e('Get in touch', 'misaki-woo'); ?></h2>
    <p>
        <label for="misaki_contact_intro_title"><strong><?php esc_html_e('Título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_contact_intro_title" name="misaki_contact_intro_title" class="large-text" value="<?php echo esc_attr(misaki_woo_get_contact_intro_title($page_id)); ?>">
    </p>
    <p>
        <label for="misaki_contact_intro_lead"><strong><?php esc_html_e('Texto introductorio', 'misaki-woo'); ?></strong></label><br>
        <textarea id="misaki_contact_intro_lead" name="misaki_contact_intro_lead" class="large-text" rows="3"><?php echo esc_textarea(misaki_woo_get_contact_intro_lead($page_id)); ?></textarea>
    </p>
    <p>
        <label for="misaki_contact_instagram_url"><strong><?php esc_html_e('Instagram URL', 'misaki-woo'); ?></strong></label><br>
        <input type="url" id="misaki_contact_instagram_url" name="misaki_contact_instagram_url" class="large-text" value="<?php echo esc_attr($instagram['url']); ?>">
    </p>
    <p>
        <label for="misaki_contact_instagram_handle"><strong><?php esc_html_e('Instagram (@usuario)', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_contact_instagram_handle" name="misaki_contact_instagram_handle" class="regular-text" value="<?php echo esc_attr($instagram['handle']); ?>">
    </p>
    <?php misaki_woo_contact_render_media_field(__('Imagen lateral (Get in touch)', 'misaki-woo'), 'misaki_contact_intro_image_id', 'misaki_contact_intro_preview', $intro_image_id); ?>

    <h2 class="misaki-contact-admin-heading"><?php esc_html_e('Equipo', 'misaki-woo'); ?></h2>
    <div id="misaki-contact-team-list">
        <?php foreach ($team as $index => $member) : ?>
            <?php misaki_woo_contact_render_team_row((int) $index, $member); ?>
        <?php endforeach; ?>
    </div>
    <p><button type="button" class="button" id="misaki-contact-add-team"><?php esc_html_e('Añadir miembro', 'misaki-woo'); ?></button></p>

    <h2 class="misaki-contact-admin-heading"><?php esc_html_e('Empresa', 'misaki-woo'); ?></h2>
    <p>
        <label for="misaki_contact_company_name"><strong><?php esc_html_e('Nombre', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_contact_company_name" name="misaki_contact_company_name" class="large-text" value="<?php echo esc_attr($company['name']); ?>">
    </p>
    <p>
        <label for="misaki_contact_company_address"><strong><?php esc_html_e('Dirección', 'misaki-woo'); ?></strong></label><br>
        <span class="description"><?php esc_html_e('Una línea por renglón.', 'misaki-woo'); ?></span><br>
        <textarea id="misaki_contact_company_address" name="misaki_contact_company_address" class="large-text" rows="4"><?php echo esc_textarea($company_address); ?></textarea>
    </p>

    <h2 class="misaki-contact-admin-heading"><?php esc_html_e('Distributors', 'misaki-woo'); ?></h2>
    <p>
        <label for="misaki_contact_distributors_title"><strong><?php esc_html_e('Título', 'misaki-woo'); ?></strong></label><br>
        <input type="text" id="misaki_contact_distributors_title" name="misaki_contact_distributors_title" class="large-text" value="<?php echo esc_attr(misaki_woo_get_contact_distributors_title($page_id)); ?>">
    </p>
    <p>
        <label for="misaki_contact_distributors_lead"><strong><?php esc_html_e('Texto introductorio', 'misaki-woo'); ?></strong></label><br>
        <textarea id="misaki_contact_distributors_lead" name="misaki_contact_distributors_lead" class="large-text" rows="3"><?php echo esc_textarea(misaki_woo_get_contact_distributors_lead($page_id)); ?></textarea>
    </p>
    <?php misaki_woo_contact_render_media_field(__('Imagen de fondo (Distributors)', 'misaki-woo'), 'misaki_contact_distributors_bg_id', 'misaki_contact_distributors_bg_preview', $dist_bg_id); ?>

    <div id="misaki-contact-countries-list">
        <?php foreach ($distributors as $index => $country) : ?>
            <?php misaki_woo_contact_render_country_block((int) $index, $country); ?>
        <?php endforeach; ?>
    </div>
    <p><button type="button" class="button" id="misaki-contact-add-country"><?php esc_html_e('Añadir país', 'misaki-woo'); ?></button></p>

    <template id="misaki-contact-team-template">
        <?php misaki_woo_contact_render_team_row(999, ['name' => '', 'role' => '', 'role_ja' => '', 'phone' => '', 'email' => '']); ?>
    </template>
    <template id="misaki-contact-country-template">
        <?php misaki_woo_contact_render_country_block(999, ['country' => '', 'flag' => '', 'distributors' => []]); ?>
    </template>
    <?php
}

function misaki_woo_contact_save_meta(int $post_id): void
{
    if (!isset($_POST['misaki_contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['misaki_contact_nonce'])), 'misaki_contact_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    if (!misaki_woo_is_contact_page($post_id)) {
        return;
    }

    update_post_meta($post_id, MISAKI_CONTACT_META_INTRO_TITLE, isset($_POST['misaki_contact_intro_title']) ? sanitize_text_field(wp_unslash($_POST['misaki_contact_intro_title'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_INTRO_LEAD, isset($_POST['misaki_contact_intro_lead']) ? sanitize_textarea_field(wp_unslash($_POST['misaki_contact_intro_lead'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_INSTAGRAM_URL, isset($_POST['misaki_contact_instagram_url']) ? esc_url_raw(wp_unslash($_POST['misaki_contact_instagram_url'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_INSTAGRAM_HANDLE, isset($_POST['misaki_contact_instagram_handle']) ? sanitize_text_field(wp_unslash($_POST['misaki_contact_instagram_handle'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_INTRO_IMAGE, isset($_POST['misaki_contact_intro_image_id']) ? absint($_POST['misaki_contact_intro_image_id']) : 0);
    update_post_meta($post_id, MISAKI_CONTACT_META_COMPANY_NAME, isset($_POST['misaki_contact_company_name']) ? sanitize_text_field(wp_unslash($_POST['misaki_contact_company_name'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_COMPANY_ADDRESS, isset($_POST['misaki_contact_company_address']) ? sanitize_textarea_field(wp_unslash($_POST['misaki_contact_company_address'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_DIST_TITLE, isset($_POST['misaki_contact_distributors_title']) ? sanitize_text_field(wp_unslash($_POST['misaki_contact_distributors_title'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_DIST_LEAD, isset($_POST['misaki_contact_distributors_lead']) ? sanitize_textarea_field(wp_unslash($_POST['misaki_contact_distributors_lead'])) : '');
    update_post_meta($post_id, MISAKI_CONTACT_META_DIST_BG, isset($_POST['misaki_contact_distributors_bg_id']) ? absint($_POST['misaki_contact_distributors_bg_id']) : 0);

    $team = [];

    if (isset($_POST['misaki_contact_team']) && is_array($_POST['misaki_contact_team'])) {
        foreach ($_POST['misaki_contact_team'] as $row) {
            if (!is_array($row)) {
                continue;
            }

            $name = sanitize_text_field($row['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $team[] = [
                'name'    => $name,
                'role'    => sanitize_text_field($row['role'] ?? ''),
                'role_ja' => sanitize_text_field($row['role_ja'] ?? ''),
                'phone'   => sanitize_text_field($row['phone'] ?? ''),
                'email'   => sanitize_email($row['email'] ?? ''),
            ];
        }
    }

    update_post_meta($post_id, MISAKI_CONTACT_META_TEAM, wp_json_encode($team));

    $countries = [];

    if (isset($_POST['misaki_contact_countries']) && is_array($_POST['misaki_contact_countries'])) {
        foreach ($_POST['misaki_contact_countries'] as $row) {
            if (!is_array($row)) {
                continue;
            }

            $country = sanitize_text_field($row['country'] ?? '');

            if ($country === '') {
                continue;
            }

            $countries[] = [
                'country'      => $country,
                'flag'         => sanitize_text_field($row['flag'] ?? ''),
                'distributors' => misaki_woo_contact_lines_to_distributors((string) ($row['lines'] ?? '')),
            ];
        }
    }

    update_post_meta($post_id, MISAKI_CONTACT_META_DISTRIBUTORS, wp_json_encode($countries));
    update_option(MISAKI_CONTACT_PAGE_OPTION, $post_id);
}

add_action('save_post_page', 'misaki_woo_contact_save_meta');

function misaki_woo_contact_admin_assets(string $hook): void
{
    if (!in_array($hook, ['post.php', 'post-new.php'], true) || !misaki_woo_contact_admin_is_contact_screen()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    wp_enqueue_media();

    $admin_js = $theme_dir . '/assets/js/contact-admin.js';
    wp_enqueue_script(
        'misaki-woo-contact-admin',
        $theme_uri . '/assets/js/contact-admin.js',
        ['jquery'],
        file_exists($admin_js) ? (string) filemtime($admin_js) : null,
        true
    );
}

add_action('admin_enqueue_scripts', 'misaki_woo_contact_admin_assets');

function misaki_woo_contact_admin_head_styles(): void
{
    if (!misaki_woo_contact_admin_is_contact_screen()) {
        return;
    }
    ?>
    <style>
        .misaki-page-editor-panel,
        .misaki-contact-editor-panel {
            margin: 1rem 0 1.5rem;
            padding: 1.25rem 1.5rem 1.5rem;
            border: 1px solid #c3c4c7;
            border-left: 4px solid #c8a85a;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        }

        .misaki-contact-admin-heading {
            margin: 1.5rem 0 0.75rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e2e4e7;
            font-size: 1.1rem;
        }

        .misaki-contact-admin-heading:first-of-type {
            margin-top: 0;
            padding-top: 0;
            border-top: 0;
        }

        .misaki-contact-team-row,
        .misaki-contact-country-row {
            margin-bottom: 0.5rem;
            padding: 0.75rem 0.5rem;
            background: #f9f9f9;
        }

        .misaki-page-media-field {
            margin-bottom: 1.25rem;
        }

        #postdivrich,
        #wp-content-editor-container,
        .postarea {
            display: none !important;
        }
    </style>
    <?php
}

add_action('admin_head', 'misaki_woo_contact_admin_head_styles');
