<?php
/**
 * Misaki Contact Form (CF7) — formulario B2B / trade.
 */

if (!defined('ABSPATH')) {
    exit;
}

const MISAKI_TRADE_CF7_FORM_OPTION = 'misaki_trade_cf7_form_id';
const MISAKI_TRADE_PAGE_OPTION     = 'misaki_trade_contact_page_id';

/**
 * Markup del formulario CF7 Misaki Contact Form.
 */
function misaki_woo_get_trade_cf7_form_markup(): string
{
    return <<<'FORM'
<div class="misaki-trade-form">
<p class="misaki-trade-form__intro">MISAKI CONTACT FORM</p>

<section class="misaki-trade-form__section">
<h2 class="misaki-trade-form__section-title">Personal Information</h2>

<div class="misaki-trade-form__row">
<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">First Name <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* first-name autocomplete:given-name placeholder "First name"]
</fieldset>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Last Name <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* last-name autocomplete:family-name placeholder "Last name"]
</fieldset>
</div>

<div class="misaki-trade-form__row">
<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Job Title / Position <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* job-title placeholder "Job title / position"]
</fieldset>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Company Name <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* company-name placeholder "Company name"]
</fieldset>
</div>

<fieldset class="misaki-trade-form__field misaki-trade-form__field--business-type">
<legend class="misaki-trade-form__legend">Business Type <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[radio business-type use_label_element "Restaurant" "Cocktail Bar" "Hotel" "Distributor" "Retail Shop" "Importer" "Catering" "Other"]
<div class="misaki-trade-form__other" data-business-other hidden>
[text business-type-other placeholder "Please specify"]
</div>
</fieldset>

<div class="misaki-trade-form__row">
<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">City <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* city autocomplete:address-level2 placeholder "City"]
</fieldset>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Country <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[text* country autocomplete:country-name placeholder "Country"]
</fieldset>
</div>
</section>

<section class="misaki-trade-form__section">
<h2 class="misaki-trade-form__section-title">Contact Details</h2>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Email <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[email* your-email autocomplete:email placeholder "Email"]
</fieldset>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Phone / WhatsApp <span class="misaki-trade-form__required" aria-hidden="true">*</span></legend>
[tel* phone autocomplete:tel placeholder "Phone / WhatsApp"]
</fieldset>

<fieldset class="misaki-trade-form__field">
<legend class="misaki-trade-form__legend">Instagram <span class="misaki-trade-form__optional">(optional)</span></legend>
[text instagram placeholder "@username"]
</fieldset>
</section>

<section class="misaki-trade-form__section">
<h2 class="misaki-trade-form__section-title">Interest</h2>
<p class="misaki-trade-form__hint">Which Misaki products are you interested in?</p>

<fieldset class="misaki-trade-form__field misaki-trade-form__field--products">
[checkbox products use_label_element "Junmai Daiginjo" "Sparkling Sake" "Yuzu Sake Dry" "Yuzu Sake Smooth"]
</fieldset>
</section>

<div class="misaki-trade-form__actions">
[submit "Send"]
</div>
</div>
FORM;
}

/**
 * Configuración de correo CF7 del formulario trade.
 *
 * @return array<string, mixed>
 */
function misaki_woo_get_trade_cf7_mail_properties(): array
{
    $recipient = 'info@misakidrinks.com';

    if (function_exists('misaki_woo_get_contact_form_recipient')) {
        $custom = misaki_woo_get_contact_form_recipient();

        if ($custom !== '') {
            $recipient = $custom;
        }
    }

    $host = wp_parse_url(home_url(), PHP_URL_HOST) ?: 'misakidrinks.com';

    return [
        'active'             => true,
        'subject'            => 'Misaki Contact Form — [company-name]',
        'sender'             => '[first-name] [last-name] <wordpress@' . $host . '>',
        'recipient'          => $recipient,
        'body'               => "MISAKI CONTACT FORM\n\n"
            . "Personal Information\n"
            . "First Name: [first-name]\n"
            . "Last Name: [last-name]\n"
            . "Job Title / Position: [job-title]\n\n"
            . "Company Information\n"
            . "Company Name: [company-name]\n"
            . "Business Type: [business-type]\n"
            . "Business Type (Other): [business-type-other]\n"
            . "City: [city]\n"
            . "Country: [country]\n\n"
            . "Contact Details\n"
            . "Email: [your-email]\n"
            . "Phone / WhatsApp: [phone]\n"
            . "Instagram: [instagram]\n\n"
            . "Interest\n"
            . "Products: [products]\n",
        'additional_headers' => 'Reply-To: [your-email]',
        'attachments'        => '',
        'use_html'           => false,
        'exclude_blank'      => true,
    ];
}

/**
 * Mensaje de éxito tras enviar el formulario.
 */
function misaki_woo_get_trade_cf7_mail_sent_ok_message(): string
{
    return "Welcome to the world of Misaki.\n\n"
        . "Thank you for taking the time to meet us.\n\n"
        . "We look forward to connecting with you soon and introducing you to our collection of premium sake and yuzu sake, crafted in Japan to elevate unforgettable dining and cocktail experiences.\n\n"
        . "See you soon,\n"
        . "The Misaki Team";
}

/**
 * Crea o actualiza el formulario CF7 Misaki Contact Form.
 */
function misaki_woo_ensure_trade_cf7_form(): void
{
    if (!class_exists('WPCF7_ContactForm')) {
        return;
    }

    $form_id   = (int) get_option(MISAKI_TRADE_CF7_FORM_OPTION, 0);
    $form_post = $form_id > 0 ? get_post($form_id) : null;

    // ID de otro entorno / borrado: resetear y buscar por título o crear de nuevo.
    if (!$form_post || $form_post->post_type !== 'wpcf7_contact_form') {
        $form_id = 0;
        $forms   = get_posts([
            'post_type'      => 'wpcf7_contact_form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($forms as $candidate_id) {
            if (get_the_title((int) $candidate_id) === 'Misaki Contact Form') {
                $form_id = (int) $candidate_id;
                break;
            }
        }
    }

    if ($form_id <= 0) {
        $form_id = wp_insert_post([
            'post_title'   => 'Misaki Contact Form',
            'post_status'  => 'publish',
            'post_type'    => 'wpcf7_contact_form',
            'post_content' => misaki_woo_get_trade_cf7_form_markup(),
        ]);

        if (is_wp_error($form_id) || !$form_id) {
            return;
        }
    }

    update_option(MISAKI_TRADE_CF7_FORM_OPTION, (int) $form_id);

    $contact_form = wpcf7_contact_form($form_id);

    if (!$contact_form) {
        return;
    }

    $properties                 = $contact_form->get_properties();
    $properties['form']         = misaki_woo_get_trade_cf7_form_markup();
    $properties['mail']         = misaki_woo_get_trade_cf7_mail_properties();
    $properties['mail_2']['active'] = false;
    $properties['messages']['mail_sent_ok'] = misaki_woo_get_trade_cf7_mail_sent_ok_message();

    $contact_form->set_properties($properties);
    $contact_form->save();

    // get_instance() marca este form como "current"; si no se limpia,
    // Contact → Contact Forms abre el editor en vez de la lista.
    WPCF7_ContactForm::get_instance(0);
}

add_action('init', 'misaki_woo_ensure_trade_cf7_form', 25);

/**
 * ID del formulario CF7 trade.
 */
function misaki_woo_get_trade_cf7_form_id(): int
{
    return (int) get_option(MISAKI_TRADE_CF7_FORM_OPTION, 0);
}

/**
 * Crea la página Misaki Contact Form si no existe.
 */
function misaki_woo_ensure_trade_contact_page(): void
{
    $existing = get_page_by_path('misaki-contact-form');

    if ($existing) {
        update_option(MISAKI_TRADE_PAGE_OPTION, (int) $existing->ID);

        if (get_page_template_slug($existing->ID) !== 'page-misaki-contact-form.php') {
            update_post_meta($existing->ID, '_wp_page_template', 'page-misaki-contact-form.php');
        }

        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'Misaki Contact Form',
        'post_name'    => 'misaki-contact-form',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_option(MISAKI_TRADE_PAGE_OPTION, (int) $page_id);
        update_post_meta($page_id, '_wp_page_template', 'page-misaki-contact-form.php');
    }
}

add_action('after_setup_theme', 'misaki_woo_ensure_trade_contact_page', 36);

/**
 * URL de la página del formulario trade.
 */
function misaki_woo_get_trade_contact_page_url(): string
{
    $page_id = (int) get_option(MISAKI_TRADE_PAGE_OPTION, 0);

    if ($page_id > 0) {
        $url = get_permalink($page_id);

        if ($url) {
            return $url;
        }
    }

    $page = get_page_by_path('misaki-contact-form');

    return $page ? (string) get_permalink($page) : home_url('/misaki-contact-form/');
}

/**
 * Renderiza el formulario trade.
 */
function misaki_woo_render_trade_contact_form(): void
{
    $form_id = misaki_woo_get_trade_cf7_form_id();

    if ($form_id > 0 && function_exists('wpcf7_contact_form')) {
        echo do_shortcode('[contact-form-7 id="' . $form_id . '" title="Misaki Contact Form"]');
        return;
    }

    echo '<p class="misaki-trade-form__missing">' . esc_html__('Contact form is not configured yet. Please install and activate Contact Form 7.', 'misaki-woo') . '</p>';
}

/**
 * Assets del formulario trade.
 */
function misaki_woo_enqueue_trade_contact_assets(): void
{
    if (!is_page('misaki-contact-form') && !is_page_template('page-misaki-contact-form.php')) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    $css = $theme_dir . '/assets/css/misaki-contact-form.css';
    wp_enqueue_style(
        'misaki-woo-trade-contact',
        $theme_uri . '/assets/css/misaki-contact-form.css',
        ['misaki-woo-main'],
        file_exists($css) ? (string) filemtime($css) : null
    );

    $js = $theme_dir . '/assets/js/misaki-contact-form.js';
    wp_enqueue_script(
        'misaki-woo-trade-contact',
        $theme_uri . '/assets/js/misaki-contact-form.js',
        [],
        file_exists($js) ? (string) filemtime($js) : null,
        true
    );

    wp_dequeue_style('contact-form-7');
}

add_action('wp_enqueue_scripts', 'misaki_woo_enqueue_trade_contact_assets', 25);
