<?php
/**
 * Age gate — verificación de edad legal para bebidas alcohólicas.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Encolar assets del age gate en el front.
 */
function misaki_woo_enqueue_age_gate_assets(): void
{
    if (is_admin()) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    $css = $theme_dir . '/assets/css/age-gate.css';
    wp_enqueue_style(
        'misaki-woo-age-gate',
        $theme_uri . '/assets/css/age-gate.css',
        ['misaki-woo-main'],
        file_exists($css) ? (string) filemtime($css) : null
    );

    $js = $theme_dir . '/assets/js/age-gate.js';
    wp_enqueue_script(
        'misaki-woo-age-gate',
        $theme_uri . '/assets/js/age-gate.js',
        [],
        file_exists($js) ? (string) filemtime($js) : null,
        true
    );

    wp_localize_script('misaki-woo-age-gate', 'misakiAgeGate', [
        'storageKey'  => 'misaki_age_verified',
        'deniedKey'   => 'misaki_age_denied',
        'exitUrl'     => 'https://www.google.com/',
        'minAge'      => 18,
    ]);
}

add_action('wp_enqueue_scripts', 'misaki_woo_enqueue_age_gate_assets', 30);

/**
 * Markup del popup (oculto hasta que JS confirme que hace falta).
 */
function misaki_woo_render_age_gate(): void
{
    if (is_admin()) {
        return;
    }
    ?>
    <div
        id="misaki-age-gate"
        class="misaki-age-gate"
        hidden
        role="dialog"
        aria-modal="true"
        aria-labelledby="misaki-age-gate-title"
        aria-describedby="misaki-age-gate-text"
    >
        <div class="misaki-age-gate__panel">
            <p class="misaki-age-gate__brand"><?php echo esc_html(get_bloginfo('name') ?: 'Misaki Drinks'); ?></p>
            <h2 id="misaki-age-gate-title" class="misaki-age-gate__title">
                <?php esc_html_e('Age verification', 'misaki-woo'); ?>
            </h2>
            <p id="misaki-age-gate-text" class="misaki-age-gate__text">
                <?php
                esc_html_e(
                    'This website sells alcoholic beverages. You must be of legal drinking age in your country of residence to enter. Are you 18 years of age or older?',
                    'misaki-woo'
                );
                ?>
            </p>
            <div class="misaki-age-gate__actions">
                <button type="button" class="misaki-age-gate__btn misaki-age-gate__btn--accept" data-age-gate="accept">
                    <?php esc_html_e('Yes, enter', 'misaki-woo'); ?>
                </button>
                <button type="button" class="misaki-age-gate__btn misaki-age-gate__btn--deny" data-age-gate="deny">
                    <?php esc_html_e('No, exit', 'misaki-woo'); ?>
                </button>
            </div>
            <p class="misaki-age-gate__note">
                <?php esc_html_e('By entering, you confirm that you are of legal age to purchase alcohol.', 'misaki-woo'); ?>
            </p>
        </div>
    </div>
    <?php
}

add_action('wp_footer', 'misaki_woo_render_age_gate', 5);
